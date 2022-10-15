<!DOCTYPE html>
<html lang="en">
    <!-- Head -->
    @include('super_admin.layouts.includes._head')

    <body class="sidebar-dark">
        <div class="main-wrapper">
            <!-- Sidebar -->
            @include('super_admin.layouts.includes._sidebar')
            @include('super_admin.layouts.includes._settings-sidebar')

            <div class="page-wrapper">
            <!-- Navbar -->
                @include('super_admin.layouts.includes._navbar')

                <div class="page-content">
                @yield('super_admin_content')
                </div>

                <!-- Footer -->
                @include('super_admin.layouts.includes._footer')
            </div>
        </div>
        <!-- Scripts -->
        @include('super_admin.layouts.includes._script')
    </body>
</html>