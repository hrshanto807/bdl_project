<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="row d-flex">
            <div class="ml-3">
                <h4 class="p-3">.shop</h4>
            </div>
        </div>
        <div class="ml-auto dropdown d-flex">
            <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown">
                <img src="{{ asset('images/user.webp') }}" class="rounded-circle" alt="User" width="30"> {{ Auth::user()->firstName }} {{ Auth::user()->lastName }}
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('userProfilePage') }}">Profile</a>
                <a class="dropdown-item" href="{{ route('userLogout') }}">Logout</a>
            </div>
        </div>

    </nav>

    <div class="row">
        <div class="col-md-2">
            <nav class=" d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky ml-3">
                    <ul class="nav flex-column ">
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('categoryList') }}">Category</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('productList') }}">Product</a></li>
                        <!-- <li class="nav-item"><a class="nav-link" href="#">Customer</a></li> -->
                        <!-- <li class="nav-item"><a class="nav-link" href="#">Create Sale</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Invoice</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Report</a></li> -->
                    </ul>
                </div>
            </nav>
        </div>
        <div class="col-md-10">
            <div class="row">
                <main role="main" class="col-md-12 ml-sm-auto">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>


    <footer class="text-center py-3">
        &copy; {{ date('Y') }} .shop. All Rights Reserved.
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS and AJAX to Fetch Categories -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>