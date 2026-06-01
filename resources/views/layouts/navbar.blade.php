<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-language"></i> {{ strtoupper(app()->getLocale()) }}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('locale.switch', 'en') }}" class="dropdown-item">English</a>
                <a href="{{ route('locale.switch', 'dv') }}" class="dropdown-item">ދިވެހި</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" id="theme-toggle" title="{{ __('messages.dark_mode') }}">
                <i class="fas fa-moon"></i>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">!</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{ __('System Alerts') }}</span>
                <div class="dropdown-divider"></div>
                <a href="{{ route('applications.index', ['status' => 'pending']) }}" class="dropdown-item">
                    <i class="fas fa-clock mr-2"></i> {{ __('Pending applications need review') }}
                </a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('profile.edit') }}"><i class="fas fa-user"></i> {{ auth()->user()->name }}</a>
        </li>
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="nav-link btn btn-link">{{ __('messages.logout') }}</button>
            </form>
        </li>
    </ul>
</nav>
