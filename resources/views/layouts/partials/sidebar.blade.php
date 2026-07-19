@auth
<div class="sidebar p-3 text-white d-flex flex-column h-100">
    <!-- Brand Title -->
    <h4 class="mb-4 text-center fw-bold py-2 border-bottom border-secondary">
        <i class="fas fa-building me-2 text-info"></i> Hotel MS
    </h4>
    
    <ul class="nav flex-column mb-4 flex-grow-1">
        
        <!-- Dashboard - All users -->
        <li class="nav-item mb-1">
            <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center @if(Route::currentRouteName() == 'dashboard') active @endif">
                <i class="fas fa-th-large me-3 text-light"></i> Dashboard
            </a>
        </li>

        <!-- Receptionist & Super Admin Only -->
        @if(auth()->user()->hasAnyRole(['admin', 'receptionist']))
            <!-- Management Section Header -->
            <li class="nav-item mt-3 mb-2">
                <span class="text-uppercase text-muted fw-bold small tracking-wider px-3">CORE FRONT DESK</span>
            </li>
            <!-- Rooms -->
            <li class="nav-item dropdown mb-1">
                <a class="nav-link d-flex align-items-center justify-content-between text-white dropdown-toggle" href="#" id="payrollDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-bed me-3"></i> Rooms
                    </div>
                </a>
                <ul class="dropdown-menu bg-dark border-0 shadow w-100 ps-3" aria-labelledby="payrollDropdown">
                    <li>
                        <a class="dropdown-item text-white py-2" href="{{ route('rooms.index') }}">
                            <i class="fas fa-list me-2 small"></i> Rooms List
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-white py-2" href="{{ route('room-types.index') }}">
                            <i class="fas fa-building me-2 small"></i> Room Types
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('bookings.index') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'bookings.index') active @endif">
                    <i class="fas fa-calendar me-3"></i> Bookings
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('bookings.checkin-checkout') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'bookings.checkin-checkout') active @endif">
                    <i class="fas fa-sign-in-alt me-3"></i> Check-in / Check-out
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('payments.index') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'payments.index') active @endif">
                    <i class="fas fa-money-bill-wave me-3"></i> Payments
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('maintenance.logs') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'maintenance.logs') active @endif">
                    <i class="fas fa-tools me-3"></i> Maintenance Logs
                </a>
            </li>
        @endif

        @if(auth()->user()->hasRole('admin'))
            <!-- Admin Section Header -->
            <li class="nav-item mt-3 mb-2">
                <span class="text-uppercase text-muted fw-bold small tracking-wider px-3">ADMINISTRATION</span>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('housekeeping.index') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'housekeeping.index') active @endif">
                    <i class="fas fa-tasks me-3"></i> Housekeeping Tasks
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('staff-schedules.index') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'staff-schedules.index') active @endif">
                    <i class="fas fa-calendar-alt me-3"></i> Staff Scheduling
                </a>
            </li>
            <!-- Admin Tools -->
            <li class="nav-item dropdown mb-1">
                <a class="nav-link d-flex align-items-center justify-content-between text-white dropdown-toggle py-2 px-3 rounded" 
                href="#" id="adminToolsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="transition: all 0.2s;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-tools me-3"></i> Admin Tools
                    </div>
                </a>
                <ul class="dropdown-menu border-0 shadow" aria-labelledby="adminToolsDropdown" style="min-width: 220px;">
                    <li>
                        <a class="dropdown-item" href="{{ route('reports.index') }}">
                            <i class="fas fa-chart-line"></i> Reports
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('users.index')}}">
                            <i class="fas fa-users-cog"></i> Workers
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('shifts.index') }}">
                            <i class="fas fa-calendar-alt"></i> Manage Shift
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('settings.show') }}">
                            <i class="fas fa-cog"></i> Hotel Settings
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('audit-logs.index') }}">
                            <i class="fas fa-history"></i> Audit Log
                        </a>
                    </li>
                </ul>
            </li>

        @endif

        <!-- Housekeeping and reception staff only -->
        @if(auth()->user()->hasRole('receptionist'))
            <li class="nav-item mt-3 mb-2">
                <span class="text-uppercase text-muted fw-bold small tracking-wider px-3">Staff Shift</span>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('housekeeping.my-schedule') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'my-tasks') active @endif">
                    <i class="fas fa-tasks me-3"></i> My Shift
                </a>
            </li>
        @endif

        <!-- Housekeeping Only -->
        @if(auth()->user()->hasRole('housekeeper'))
            <li class="nav-item mt-3 mb-2">
                <span class="text-uppercase text-muted fw-bold small tracking-wider px-3">Housekeeping</span>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('housekeeping.my-schedule') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'my-tasks') active @endif">
                    <i class="fas fa-tasks me-3"></i> My Schedule
                </a>
            </li>
            <!-- Assigned tasks for housekeeping staff -->
            <li class="nav-item">
                <a class="nav-link position-relative" href="#">
                    <i class="fas fa-bell"></i>
                    <span>Housekeeping Tasks</span>
                        <span class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                            <span class="visually-hidden">New tasks</span>
                        </span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('housekeeping.dirty-rooms') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'my-tasks') active @endif">
                    <i class="fas fa-bed me-3"></i> Dirty Rooms
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('housekeeping.history') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'my-tasks') active @endif">
                    <i class="fas fa-history me-3"></i> Tasks History
                </a>
            </li>
        @endif

        <!-- Personal Section -->
        <li class="nav-item mt-3 mb-2">
            <span class="text-uppercase text-muted fw-bold small tracking-wider px-3">Personal</span>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ route('my-profile') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'my-profile') active @endif">
                <i class="fas fa-user-circle me-3"></i> My Profile
            </a>
        </li>
    </ul>

    <!-- Logout -->
    <div class="mt-auto pt-4 pb-4 border-top border-secondary">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center py-2">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>
</div>
@endauth
