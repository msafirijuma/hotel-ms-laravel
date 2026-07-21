<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm px-4 mx-auto w-50">
        <div class="d-flex align-items-center">
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle fs-5 me-2"></i>
                    {{ Auth::user()->name ?? 'My Account' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user me-2"></i> My Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li> 
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger py-2">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>