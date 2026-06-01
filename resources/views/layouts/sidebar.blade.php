<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <i class="fas fa-tint brand-image ml-3"></i>
        <span class="brand-text font-weight-light">{{ __('messages.portal_name') }}</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('messages.dashboard') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('applications.index') }}" class="nav-link {{ request()->routeIs('applications.*') && !request()->routeIs('excel.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>{{ __('messages.applications') }}</p>
                    </a>
                </li>
                @if(auth()->user()->canEditApplications())
                <li class="nav-item">
                    <a href="{{ route('applications.create') }}" class="nav-link">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>{{ __('Add Application') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('excel.import.form') }}" class="nav-link {{ request()->routeIs('excel.import*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-upload"></i>
                        <p>{{ __('messages.import_excel') }}</p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('excel.export') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-download"></i>
                        <p>{{ __('messages.export_excel') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('reports.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>{{ __('messages.reports') }} <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="{{ route('reports.daily') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Daily') }}</p></a></li>
                        <li class="nav-item"><a href="{{ route('reports.monthly') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Monthly') }}</p></a></li>
                        <li class="nav-item"><a href="{{ route('reports.connections') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Connections') }}</p></a></li>
                        <li class="nav-item"><a href="{{ route('reports.categories') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Categories') }}</p></a></li>
                    </ul>
                </li>
                @if(auth()->user()->canManageUsers())
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>{{ __('messages.users') }}</p>
                    </a>
                </li>
                @endif
                @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>{{ __('messages.settings') }}</p>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
