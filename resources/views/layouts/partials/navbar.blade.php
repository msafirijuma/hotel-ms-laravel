<!-- TOP RIGHT: Notifications + Profile -->
<ul class="navbar-nav ms-auto align-items-center mb-3 gap-1">

    <!-- ===== NOTIFICATION BELL ===== -->
    <li class="nav-item dropdown me-3">
        <a class="nav-link position-relative px-3" href="#" id="notificationDropdown"
           role="button" data-bs-toggle="dropdown" aria-expanded="false"
           style="font-size: 1.25rem;">
            <i class="fas fa-bell"></i>

            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                      style="font-size: 0.65rem; transform: translate(-60%, 30%) !important;">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow border-0"
            aria-labelledby="notificationDropdown"
            style="width: 360px; max-height: 420px; overflow-y: auto;">

            <!-- Header -->
            <li class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                <strong class="small">Notifications</strong>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.read-all') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0">
                            Mark all read
                        </button>
                    </form>
                @endif
            </li>

            <!-- Notification items -->
            @forelse(auth()->user()->notifications->take(8) as $notification)
                <li>
                    <a href="{{ route('notifications.read', $notification->id) }}"
                       class="dropdown-item py-3 px-3 {{ $notification->read_at ? '' : 'bg-light' }}"
                       style="white-space: normal;">
                        <div class="d-flex gap-2">
                            <div class="flex-shrink-0 mt-1">
                                @php
                                    $type = $notification->data['type'] ?? '';
                                    $icon = match($type) {
                                        'leave_requested' => 'fa-calendar-plus text-primary',
                                        'leave_status'    => 'fa-calendar-check text-success',
                                        'payroll_generated' => 'fa-money-bill-wave text-success',
                                        'new_announcement' => 'fa-bullhorn text-warning',
                                        default => 'fa-bell text-secondary',
                                    };
                                @endphp
                                <i class="fas {{ $icon }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small mb-0">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $notification->data['message'] ?? '' }}
                                </div>
                                <div class="text-muted" style="font-size: 0.7rem;">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                            @if(!$notification->read_at)
                                <span class="bg-primary rounded-circle flex-shrink-0 mt-1"
                                      style="width: 8px; height: 8px;"></span>
                            @endif
                        </div>
                    </a>
                </li>
            @empty
                <li class="px-3 py-4 text-center text-muted small">
                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                    Hakuna notifications
                </li>
            @endforelse

            <!-- Footer -->
            <li class="border-top">
                <a href="{{ route('notifications.index') }}"
                   class="dropdown-item text-center small py-2 text-primary fw-semibold">
                    View all notifications
                </a>
            </li>
        </ul>
    </li>

    <!-- ===== USER PROFILE ===== -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-2"
           href="#" id="userDropdown" role="button"
           data-bs-toggle="dropdown" aria-expanded="false">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 34px; height: 34px; font-size: 0.85rem; font-weight: 600;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="d-none d-md-inline small fw-semibold">{{ auth()->user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 200px;">
            <li class="px-3 py-2 border-bottom">
                <div class="fw-semibold small">{{ auth()->user()->name }}</div>
                <div class="text-muted" style="font-size: 0.75rem;">{{ auth()->user()->email }}</div>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('my-profile') }}">
                    <i class="fas fa-user me-2 text-muted"></i> Profile
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('password.change') }}">
                    <i class="fas fa-key me-2 text-muted"></i> Change Password
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </li>
</ul>