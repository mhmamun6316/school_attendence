<div class="sidebar__menu-group">
    <ul class="sidebar_nav">
        <li>
            <a href="{{ route('dashboard') }}" class="active">
                <span data-feather="home" class="nav-icon"></span>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>
        <li class="has-child">
            <a href="#" class="">
                <span data-feather="database" class="nav-icon"></span>
                <span class="menu-text">Roles & Permissions</span>
                <span class="toggle-icon"></span>
            </a>
            <ul>
                <li>
                    <a class="" href="{{ route('admin.roles.index') }}">All Roles</a>
                </li>
                <li>
                    <a class="" href="{{ route('admin.roles.create') }}">Create Role</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('admin.organizations.index') }}">
                <span data-feather="home" class="nav-icon"></span>
                <span class="menu-text">Organizations</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.index') }}">
                <span data-feather="home" class="nav-icon"></span>
                <span class="menu-text">Admins</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.devices.index') }}">
                <span data-feather="home" class="nav-icon"></span>
                <span class="menu-text">Admins</span>
            </a>
        </li>
    </ul>
</div>
