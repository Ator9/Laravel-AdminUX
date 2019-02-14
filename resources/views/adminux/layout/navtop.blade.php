<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{ asset('admin') }}">{{ config('app.name', 'Admin') }}</a>
    <ul class="nav w-100 ml-2">
        @foreach($Helpers->getNavTop(Request::path()) as $dir => $name)
            @if ($loop->index > 0 and $dir = 'admin_'.$dir) @endif
            <li class="nav-item">
                @if(Request::is('admin/'.$dir))
                    <a class="nav-link text-white disabled" href="{{ asset('admin/'.$dir) }}">{{ $name }}</a>
                @else
                    <a class="nav-link text-warning" href="{{ asset('admin/'.$dir) }}">{{ $name }}</a>
                @endif
            </li>
        @endforeach
    </ul>
    <span class="text-white">{{ Auth::guard('adminux')->user()->email }}</span>
    <ul class="navbar-nav mx-4">
        <li class="nav-item">
            <a class="nav-link" href="{{ asset('admin/logout') }}"><span data-feather="log-out"></span></a>
        </li>
    </ul>
</nav>
