<!DOCTYPE html>
<html lang="en-US">
    @include('layouts.partials.header')
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Fixed sidebar on the screen */
        .app-sidebar-container {
            width: 260px; /* sidebar width */
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            background-color: #1a233a; 
            overflow-y: auto;
        }

        /* Content container */
        .app-content-container {
            flex: 1;
            margin-left: 260px; /* equal to sidebar px */
            padding: 1.5rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* UDropdown Menu (Admin Tools) */
        .app-sidebar-container .dropdown-menu {
            background-color: #ffffff !important; 
            border: none !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important; 
            border-radius: 8px !important;
            padding: 0.5rem 0 !important;
            margin-top: 5px !important;
        }

        /* Items in the Dropdwon */
        .app-sidebar-container .dropdown-item {
            color: #4a5568 !important; 
            font-weight: 500 !important;
            padding: 0.6rem 1.2rem !important;
            transition: all 0.2s ease !important;
            display: flex !important;
            align-items: center !important;
            background: none !important;
        }

        /* Icons inside Dropdown */
        .app-sidebar-container .dropdown-item i {
            color: #718096 !important; 
            width: 25px !important;
            font-size: 1rem !important;
            transition: all 0.2s ease !important;
        }

        /* Dropdown effect on dropdown items  */
        .app-sidebar-container .dropdown-item:hover {
            background-color: #f1f5f9 !important; 
            color: #0d6efd !important;            
            padding-left: 1.5rem !important;      
        }

        /* Icons change color on hovering */
        .app-sidebar-container .dropdown-item:hover i {
            color: #0d6efd !important;
        }

    </style>
<body>

<!-- Check current page is login page -->
@if(request()->routeIs('login'))
    
    <!-- sidebar sticks in the middle -->
    <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh; background-color: #f8fafc; padding: 0;">
        <div class="w-100">
            @yield('content')
        </div>
    </div>

@else
    <!-- Show sidebar & content-container -->
    <div class="app-wrapper">
        <!-- Right side: Sidebar -->
        <div class="app-sidebar-container">
            @include('layouts.partials.sidebar')
        </div>

        <!-- Left side: Main Content & Footer -->
        <div class="app-content-container">
            <div class="main-content-body w-100">
                @yield('content')
            </div>
            
            <!-- Footer -->
            <div class="w-100 mt-5">
                @include('layouts.partials.footer')
            </div>
        </div>
    </div>
@endif


<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Local JS -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<!-- Datatable -->
<script>
    $(document).ready(function() {
        $('#bookingsTable, #paymentsTable, #roomsTable, #roomTypesTable, #usersTable, #shiftTable, #staffTable, #housekeepingTable, #maintenanceTable').DataTable({
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Prev"
                }
            }
        });
    });
</script>

<!-- SweetAlert2 -->
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
        }
    });

    // success message
    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif

    // error message
    @if($errors->any())
        Toast.fire({
            icon: 'error',
            title: "{{ $errors->first() }}" 
        });
    @endif
        
</script>

<script>
    window.addEventListener('pageshow', function (event) {
        // Check if the page was loaded from the browser history cache (Back button pressed)
        var historyTraversal = event.persisted || 
                               (typeof window.performance != 'undefined' && 
                                window.performance.navigation.type === 2);
                                
        if (historyTraversal) {
            // Close any frozen SweetAlert spinner immediately
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
        }
    });
</script>

@yield('scripts')
</body>
</html>
