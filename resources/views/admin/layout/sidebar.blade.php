<div class="sidebar__menu-group">
    <ul class="sidebar_nav">
        @permission('dashboard.view')
        <li>
            <a href="{{ route('dashboard') }}" class="active">
                <span data-feather="home" class="nav-icon"></span>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>
        @endpermission
        <li class="has-child">
            <a href="#" class="">
                <span data-feather="database" class="nav-icon"></span>
                <span class="menu-text">Roles & Permissions</span>
                <span class="toggle-icon"></span>
            </a>
            <ul>
                @permission('role.view')
                <li>
                    <a class="" href="{{ route('admin.roles.index') }}">All Roles</a>
                </li>
                @endpermission
                @permission('role.create')
                <li>
                    <a class="" href="{{ route('admin.roles.create') }}">Create Role</a>
                </li>
                @endpermission
            </ul>
        </li>

        @permission('organization.view')
        <li>
            <a href="{{ route('admin.organizations.index') }}">
                <span data-feather="home" class="nav-icon"></span>
                <span class="menu-text">Organizations</span>
            </a>
        </li>
        @endpermission

        @permission('admin.view')
        <li>
            <a href="{{ route('admin.users.index') }}">
                <span data-feather="home" class="nav-icon"></span>
                <span class="menu-text">Admins</span>
            </a>
        </li>
        @endpermission

        @permission('device.view')
        <li>
            <a href="{{ route('admin.devices.index') }}">
                <span data-feather="home" class="nav-icon"></span>
                <span class="menu-text">Devices</span>
            </a>
        </li>
        @endpermission

        @permission('package.view')
        <li>
            <a href="{{ route('admin.packages.index') }}">
                <span data-feather="home" class="nav-icon"></span>
                <span class="menu-text">Packages</span>
            </a>
        </li>
        @endpermission

        @permission('package.view')
        <li>
            <a href="{{ route('admin.students.index') }}">
                <span data-feather="home" class="nav-icon"></span>
                <span class="menu-text">Students</span>
            </a>
        </li>
        @endpermission

        @permission('package.view')
{{--        <li>--}}
{{--            <a href="{{ route('admin.attendences.index') }}">--}}
{{--                <span data-feather="home" class="nav-icon"></span>--}}
{{--                <span class="menu-text">Attendences</span>--}}
{{--            </a>--}}
{{--        </li>--}}
        @endpermission
    </ul>
</div>
