<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * مرحله اول: نمایش فرم انتخاب مشتری
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();

        return view('orders.create', compact('customers'));
    }

    /**
     * ایجاد سفارش اولیه، بدون کالا
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => [
                'required',
                'exists:customers,id',
            ],
        ]);

        $order = DB::transaction(function () use ($validated) {
            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'order_number' => 'TEMP-' . Str::uuid(),
                'status' => 'pending',
                'total_amount' => 0,
            ]);

            $order->update([
                'order_number' => 'ORD-'
                    . $order->id
                    . '-'
                    . $order->customer_id,
            ]);

            return $order;
        });

        return redirect()
            ->route('orders.items.create', ['order' => $order->id])
            ->with('success', 'سفارش ایجاد شد. اکنون کالاها را اضافه کنید.');
    }

    /**
     * مرحله دوم: نمایش صفحه افزودن اقلام سفارش
     */
    public function createItems(Order $order)
    {
        $order->load(['customer', 'items.product']);

        $categories = Category::orderBy('name')->get();

        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('orders.items.create', compact(
            'order',
            'categories',
            'products'
        ));
    }
    public function storeItem(Request $request, Order $order)
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $product = Product::where('is_active', true)
            ->findOrFail($validated['product_id']);

        $quantity = (int) $validated['quantity'];

        DB::transaction(function () use (
            $order,
            $product,
            $quantity
        ) {
            $item = OrderItem::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            if ($item) {
                // اگر کالا قبلاً اضافه شده، تعداد آن افزایش می‌یابد.
                $newQuantity = $item->quantity + $quantity;

                $item->update([
                    'quantity' => $newQuantity,
                    'subtotal' => round(
                        (float) $item->unit_price * $newQuantity,
                        2
                    ),
                ]);
            } else {
                // ثبت قلم جدید
                $unitPrice = (float) $product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => round(
                        $unitPrice * $quantity,
                        2
                    ),
                ]);
            }

            // محاسبه مجدد مبلغ کل سفارش
            $totalAmount = OrderItem::where('order_id', $order->id)
                ->sum('subtotal');

            $order->update([
                'total_amount' => $totalAmount,
            ]);
        });

        return redirect()
            ->route('orders.items.create', ['order' => $order->id])
            ->with('success', 'کالا به سفارش اضافه شد.');
    }

    /**
     * حذف یک قلم از سفارش
     */
    public function destroyItem(Order $order, OrderItem $item)
    {
        abort_unless(
            (int) $item->order_id === (int) $order->id,
            404
        );

        DB::transaction(function () use ($order, $item) {
            $item->delete();

            $totalAmount = OrderItem::where('order_id', $order->id)
                ->sum('subtotal');

            $order->update([
                'total_amount' => $totalAmount,
            ]);
        });

        return redirect()
            ->route('orders.items.create', ['order' => $order->id])
            ->with('success', 'قلم سفارش حذف شد.');
    }

    /**
     * فهرست سفارش‌ها
     */
    public function index()
    {
        $orders = Order::with('customer')
            ->latest()
            ->paginate(20);

        return view('orders.index', compact('orders'));
    }

    /**
     * جزئیات یک سفارش
     */
    public function show(Order $order)
    {
        $order->load([
            'customer',
            'items.product',
        ]);

        return view('orders.show', compact('order'));
    }
    
    public function searchProducts(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],
        ]);

        $term = trim($validated['q'] ?? '');

        // نام ستون‌های کد کالا که واقعاً در جدول products وجود دارند
        $codeColumns = array_values(array_filter(
            ['code', 'product_code', 'sku', 'barcode'],
            fn ($column) => Schema::hasColumn('products', $column)
        ));

        $query = Product::query();

        // فقط کالاهای فعال
        $query->where('is_active', true);

        // فیلتر اختیاری دسته‌بندی
        if (!empty($validated['category_id'])) {
            $query->where('category_id', $validated['category_id']);
        }

        // جستجو بر اساس نام یا کد کالا
        if ($term !== '') {
            $query->where(function ($builder) use ($term, $codeColumns) {
                $builder->where('name', 'like', '%' . $term . '%');

                foreach ($codeColumns as $column) {
                    $builder->orWhere(
                        $column,
                        'like',
                        '%' . $term . '%'
                    );
                }
            });
        }

        return response()->json(
            $query->select('id', 'name', 'price', 'category_id')
                ->orderBy('name')
                ->limit(50)
                ->get()
        );
    }
    public function storeCustomer(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'mobile' => [
            'required',
            'string',
            'regex:/^09[0-9]{9}$/',
            'unique:customers,mobile',
        ],
        'email' => ['nullable', 'email', 'max:255'],
        'address' => ['nullable', 'string', 'max:2000'],
    ]);

    $customer = Customer::create($validated);

    return response()->json([
        'message' => 'مشتری با موفقیت ثبت شد.',
        'customer' => [
            'id' => $customer->id,
            'name' => $customer->name,
            'mobile' => $customer->mobile,
            'email' => $customer->email,
            'address' => $customer->address,
        ],
    ], 201);
}

}
