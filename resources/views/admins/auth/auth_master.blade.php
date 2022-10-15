<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html">
    <meta name="author" content="Muhammad Anisuzzaman">
    <meta name="description" content="Final Year Project">
    <meta name="keywords" content="Booking Hotel">
    <link rel="shortcut icon" href="{{ asset('public/default/favicon.png') }}"/>

    <title>Booking Advisor | @yield('auth_title')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('public/backend/plugins/core/core.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backend/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backend/plugins/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backend/css/style.min.css') }}">
    <!-- fontawsome -->
    <script src="https://kit.fontawesome.com/fbcd216f09.js" crossorigin="anonymous"></script>
</head>

<body class="sidebar-dark">
    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">
                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-8 col-xl-6 mx-auto">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-8 m-auto">
                                    @yield('auth_content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('public/backend/plugins/core/core.js') }}"></script>
    <script src="{{ asset('public/backend/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('public/backend/plugins/sweetalert2/sweetalert2@11.js') }}"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
        })

        @if (Session::has('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                })
            @endif
            
        @if (Session::has('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}',
            })
        @endif

        @if (Session::has('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{{ session('warning') }}',
            })
        @endif

        @foreach ($errors->all() as $error)
            Toast.fire({
                icon: 'error',
                title: '{{ $error }}',
            })
        @endforeach
        
    </script>
</body>
</html>
