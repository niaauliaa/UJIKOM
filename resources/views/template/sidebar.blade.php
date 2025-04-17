<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('Resources/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('Assets/css/sidebar.css') }}">
    <link rel="icon" href="{{ asset('Assets/img/logo.png') }}">
    <title>FlexyLite</title>  
</head>
<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="h-100">
                <div class="sidebar-logo">
                    <img src="{{ asset('Assets/img/group 1.png') }}" alt="">
                </div>
    
                <nav class="navbar navbar-expand bg-white shadow-sm p-2">
                    <div class="d-flex align-items-center ms-auto">
                        <img src="{{ asset('Assets/img/user.jpg') }}" alt="Profile" style="width: 30px; height: 30px; border-radius: 50%;">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownProfile" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownProfile">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <i class="ti ti-user me-2"></i> View Profile
                                    </a>
                                </li>         
                                <li>
                                    <form action="/logout" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center">
                                            <i class="ti ti-logout me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>                    
                        </div>
                    </div>  
                </nav>
    
                <ul class="sidebar-nav">
                    @if(auth()->user()->role == 'admin')
                        <li class="sidebar-item">
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" style="font-size: 20px">
                                <i class="ti ti-layout-dashboard-filled" style="font-size: 30px;"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.product.index') }}" class="sidebar-link {{ request()->routeIs('admin.product.*') ? 'active' : '' }}" style="font-size: 20px">
                                <i class="ti ti-home" style="font-size: 30px;"></i>
                                Produk
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.pembelian.index') }}" class="sidebar-link {{ request()->routeIs('admin.pembelian.*') ? 'active' : '' }}" style="font-size: 20px">
                                <i class="ti ti-shopping-cart-filled" style="font-size: 30px;"></i>
                                Penjualan
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.user.index') }}" class="sidebar-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}" style="font-size: 20px">
                                <i class="ti ti-user" style="font-size: 30px;"></i>
                                User
                            </a>
                        </li>
                    @endif
    
                    @if(auth()->user()->role == 'petugas')
                        <li class="sidebar-item">
                            <a href="{{ route('petugas.dashboard') }}" class="sidebar-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}" style="font-size: 20px">
                                <i class="ti ti-layout-dashboard-filled" style="font-size: 30px;"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('petugas.product.index') }}" class="sidebar-link {{ request()->routeIs('petugas.product.*') ? 'active' : '' }}" style="font-size: 20px">
                                <i class="ti ti-home" style="font-size: 30px;"></i>
                                Produk
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('petugas.pembelian.index') }}" class="sidebar-link {{ request()->routeIs('petugas.pembelian.*') ? 'active' : '' }}" style="font-size: 20px">
                                <i class="ti ti-shopping-cart-filled" style="font-size: 30px;"></i>
                                Penjualan
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </aside>
    
        <div class="main">
            <main class="content">
                <div class="ml-2" style="position: relative; top:70px; padding:15px;">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    @stack('scripts')

</body>
</html>
