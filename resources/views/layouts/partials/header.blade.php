<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HRM System')</title>
    <link rel="icon" href="data:,">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
        }

        .nav-link {
            color: #bdc3c7 !important;
        }

        .nav-link:hover, .nav-link.active {
            background: #34495e;
            color: white !important;
        }

        .main-content {
            min-height: 100vh;
        }
        
        .sidebar {
            background-color: #1a233a !important; 
            min-height: 100vh;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        
        .sidebar .nav-link {
            color: #a3b1cc !important; 
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 4px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
 
        .sidebar .nav-link i {
            font-size: 1.1rem;
            color: #8a99b5;
            transition: all 0.3s ease;
        }
   
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
            transform: translateX(4px); 
        }

        .sidebar .nav-link:hover i {
            color: #38bdf8; 
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important; 
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(3, 105, 161, 0.3);
        }

        .sidebar .nav-link.active i {
            color: #ffffff !important;
        }

        .sidebar .tracking-wider {
            letter-spacing: 0.08em;
            font-size: 0.75rem;
            color: #64748b !important;
            display: block;
            margin-top: 15px;
        }

        .sidebar .btn-outline-danger {
            color: #f1f5f9;
            border-color: rgba(239, 68, 68, 0.4);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar .btn-outline-danger:hover {
            background-color: #ef4444 !important;
            border-color: #ef4444 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }
    </style>

    <!-- Local CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap-5.min.css') }}">

    @yield('styles')
</head>