<!DOCTYPE html>
<html lang="en">
    <!-- Head -->
    @include('admins.layouts.includes._head')

    <body class="sidebar-dark">
        <div class="main-wrapper">
            <!-- Sidebar -->
            @include('admins.layouts.includes._sidebar')
            @include('admins.layouts.includes._settings-sidebar')

            <div class="page-wrapper">
            <!-- Navbar -->
                @include('admins.layouts.includes._navbar')

                <div class="page-content">
                @yield('admins_content')
                </div>

                <!-- Footer -->
                @include('admins.layouts.includes._footer')
            </div>
        </div>
        <!-- Scripts -->
        @include('admins.layouts.includes._script')
    </body>
</html>