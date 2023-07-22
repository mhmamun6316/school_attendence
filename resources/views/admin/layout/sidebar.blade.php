<div class="sidebar__menu-group">
    <ul class="sidebar_nav">
        @permission('dashboard.view')
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" @activeRoute('dashboard') class="active" @endactiveRoute>
                <span class="nav-icon"> <i class="fas fa-tachometer-alt"></i></span>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>
        @endpermission
        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon"> <i class="fas fa-hand-sparkles"></i></span>
                <span class="menu-text">Roles & Permissions</span>
                <span class="toggle-icon"></span>
            </a>
            <ul>
                @permission('role.view')
                <li>
                    <a @activeRoute('admin.organizations.index') class="active" @endactiveRoute href="{{ route('admin.roles.index') }}">All Roles</a>
                </li>
                @endpermission
                @permission('role.create')
                <li>
                    <a @activeRoute('admin.organizations.index') class="active" @endactiveRoute href="{{ route('admin.roles.create') }}">Create Role</a>
                </li>
                @endpermission
            </ul>
        </li>

        @permission('organization.view')
        <li>
            <a href="{{ route('admin.organizations.index') }}" @activeRoute('admin.organizations.index') class="active" @endactiveRoute>
                <span data-feather="users" class="nav-icon"></span>
                <span class="menu-text">Organizations</span>
            </a>
        </li>
        @endpermission

        @permission('admin.view')
        <li>
            <a href="{{ route('admin.users.index') }}" @activeRoute('admin.users.index') class="active" @endactiveRoute>
                <span data-feather="user" class="nav-icon"></span>
                <span class="menu-text">Admins</span>
            </a>
        </li>
        @endpermission

        @permission('device.view')
        <li>
            <a href="{{ route('admin.devices.index') }}" @activeRoute('admin.devices.index') class="active" @endactiveRoute>
                <span class="nav-icon"> <i class="fas fa-fingerprint"></i></span>
                <span class="menu-text">Devices</span>
            </a>
        </li>
        @endpermission

        @permission('package.view')
        <li>
            <a href="{{ route('admin.packages.index') }}" @activeRoute('admin.packages.index') class="active" @endactiveRoute>
                <span data-feather="box" class="nav-icon"></span>
                <span class="menu-text">Packages</span>
            </a>
        </li>
        @endpermission

        @permission('student.view')
        <li>
            <a href="{{ route('admin.students.index') }}" @activeRoute('admin.students.index') class="active" @endactiveRoute>
                <span data-feather="user" class="nav-icon"></span>
                <span class="menu-text">Students</span>
            </a>
        </li>
        @endpermission

        @permission('attendance.view')
        <li>
            <a href="{{ route('admin.attendances') }}" @activeRoute('admin.attendances') class="active" @endactiveRoute>
                <span class="nav-icon"><i class="fas fa-list"></i></span>
                <span class="menu-text">Attendences</span>
            </a>
        </li>
        @endpermission

        @permission('bill.view')
        <li>
            <a href="{{ route('admin.bills') }}" @activeRoute('admin.bills') class="active" @endactiveRoute>
                <span data-feather="file" class="nav-icon"></span>
                <span class="menu-text">Bill Generate</span>
            </a>
        </li>
        @endpermission
    </ul>
</div>
