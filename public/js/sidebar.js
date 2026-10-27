
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('sidebarToggle');
    const toggleIcon = document.getElementById('toggleIcon');

    if (!sidebar || !toggleButton) {
        return;
    }

    toggleButton.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');

        if (toggleIcon) {
            toggleIcon.textContent =
                sidebar.classList.contains('collapsed') ? '⇥' : '⇤';
        }
    });
});
