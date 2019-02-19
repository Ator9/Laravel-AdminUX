<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link{{ Request::is('admin') ? ' active' : '' }}" href="{{ asset('admin') }}">
                    <span data-feather="home"></span>
                    Dashboard
                </a>
            </li>
            @foreach($Helpers->getNavLeft() as $module)
                <li class="nav-item">
                    <a class="nav-link{{ (Request::is('admin/'.strtolower($module['dir'])) or str_contains(Request::path(), 'admin/'.strtolower($module['dir']).'_')) ? ' active' : '' }}" href="{{ asset('admin/'.strtolower($module['dir'])) }}">
                        <span data-feather="{{ $module['icon'] }}"></span>
                        {{ $module['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
