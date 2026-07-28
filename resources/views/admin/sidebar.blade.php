{{--
    ============================================================
    resources/views/admin/sidebar.blade.php — Admin Navigation Sidebar
    ============================================================
--}}
<div class="sidebar p-3 d-flex flex-column" id="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin') ? 'active' : '' }}" href="{{ url('/admin') }}">
                <i class="bi bi-speedometer2"></i> <span>{{ __('admin.dashboard') ?? 'لوحة التحكم' }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/orders*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                <i class="bi bi-cart"></i> <span>{{ __('admin.orders') ?? 'الطلبات' }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <i class="bi bi-bag"></i> <span>{{ __('admin.products') ?? 'المنتجات' }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/category*') ? 'active' : '' }}" href="{{ route('category.index') }}">
                <i class="bi bi-tags"></i> <span>{{ __('admin.categories') ?? 'الأقسام' }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="bi bi-people"></i> <span>{{ __('admin.users') ?? 'المستخدمين' }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/sliders*') ? 'active' : '' }}" href="{{ route('sliders.index') }}">
                <i class="bi bi-images"></i> <span>{{ __('admin.sliders') ?? 'السلايدر' }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                <i class="bi bi-gear"></i> <span>{{ __('admin.settings') ?? 'الإعدادات' }}</span>
            </a>
        </li>
        <li class="nav-item mt-auto">
            <a class="nav-link text-danger" href="#" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                <i class="bi bi-box-arrow-left"></i> <span>{{ __('admin.logout') ?? 'تسجيل الخروج' }}</span>
            </a>
            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </li>
    </ul>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<button id="sidebarToggle" class="sidebar-toggle-btn" aria-label="Toggle Sidebar">
    <i class="bi bi-list"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtns = document.querySelectorAll('#sidebarToggle');
    const overlay    = document.getElementById('sidebarOverlay');

    function toggleSidebar(e) {
        e.stopPropagation();
        document.body.classList.toggle('sidebar-open');
    }

    function closeSidebar() {
        document.body.classList.remove('sidebar-open');
    }

    toggleBtns.forEach(btn => btn.addEventListener('click', toggleSidebar));
    overlay?.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });
});
</script>