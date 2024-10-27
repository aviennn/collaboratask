<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Collaboratask') }}</title>

    <!-- jQuery (load first for dependencies) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome (icons) -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Bootstrap CSS (load first for base styles) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AdminLTE (load after Bootstrap) -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom Styles and Vite (Load Last) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles') <!-- For page-specific styles -->

    <style>
        body {
            font-family: {{ Auth::check() && Auth::user()->font_family ? Auth::user()->font_family : 'Arial, sans-serif' }};
            font-size: {{ Auth::check() && Auth::user()->font_size ? Auth::user()->font_size : '16px' }};
            color: {{ Auth::check() && Auth::user()->font_color ? Auth::user()->font_color : '#000000' }};
            background-color: {{ Auth::check() && Auth::user()->background_color ? Auth::user()->background_color : '#ffffff' }};
        }

        .main-header {
            background-color: {{ Auth::check() && Auth::user()->navbar_color ? Auth::user()->navbar_color : '#ffffff' }};
        }

        .main-sidebar {
            background-color: {{ Auth::check() && Auth::user()->sidebar_color ? Auth::user()->sidebar_color : '#00355b' }};
        }

        .main-sidebar a, .main-header .nav-link {
            color: #ffffff !important; /* Ensure nav links are white */
        }

        .content-wrapper {
            background-color: {{ Auth::check() && Auth::user()->background_color ? Auth::user()->background_color : '#ffffff' }};
        }

        /* Dark mode styles */
        body.dark-mode .main-header {
            background-color: #343a40 !important;
            color: #ffffff !important;
        }

        body.dark-mode .main-sidebar {
            background-color: #1d1d1d !important;
            color: #e0e0e0 !important;
        }

        body.dark-mode .content-wrapper {
            background-color: #2a2a2a !important;
        }

        body.dark-mode .dataTables_wrapper {
            color: #d1d1d1;
        }

        body.dark-mode table.dataTable {
            background-color: #333;
            border-color: #444;
        }

        body.dark-mode table.dataTable th,
        body.dark-mode table.dataTable td {
            background-color: #333;
            color: #d1d1d1;
            border-color: #444;
        }

        body.dark-mode .fc-toolbar-button {
            background-color: #444;
            color: #e0e0e0;
            border: 1px solid #555;
        }

          /* Dark mode adjustment for specific text styles */
    .dark-mode .text-gray-800 {
        color: #f0f0f0 !important; /* Lighter color for dark mode */
    }

    .dark-mode .font-semibold {
        color: #f0f0f0 !important; /* Lighter color for better contrast */
    }

    .sidebar-mini.sidebar-collapse .main-sidebar .user-panel {
    overflow: visible !important; /* Ensure profile image isn't cut off */
}
@media (min-width: 992px) {
    .sidebar-mini.sidebar-collapse .user-panel .info {
        display: none; /* Hide the user info in collapsed mode to save space */
    }

    .sidebar-mini.sidebar-collapse .user-panel {
        justify-content: center; /* Center the image when collapsed */
    }
}

@media (min-width: 992px) {
    .sidebar-mini.sidebar-collapse .user-panel .image img {
        width: 30px !important; /* Adjust to fit within the collapsed sidebar */
        height: 30px !important;
        display: block !important; /* Ensure it remains visible */
    }

    .sidebar-mini.sidebar-collapse .user-panel {
        display: flex !important; /* Keep the user-panel visible */
        justify-content: center; /* Center content */
        padding: 5px 0; /* Adjust padding to fit the smaller space */
    }
}
    </style>

    <!-- Additional Font Links -->
    <link href="https://fonts.googleapis.com/css?family=Roboto|Open+Sans|Lato" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.navigation')

        <!-- Main Sidebar Container -->
        @if(Auth::check() && Auth::user()->usertype == 'admin')
            @include('layouts.sidebar-admin')
        @else
            @include('layouts.sidebar-user')
        @endif

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @isset($header)
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                {{ $header }}
                            </div>
                        </div>
                    </div>
                </section>
            @endisset

            <!-- Main content -->
            <section class="content">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </section>
        </div>

        <!-- JavaScript Libraries (place at the end) -->

        <!-- Popper.js (Bootstrap 4 requires Popper.js v1.x) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

        <!-- Bootstrap 4 JavaScript -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

        <!-- jQuery UI (Sortable, etc.) -->
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

        <!-- AdminLTE App -->
        <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Chart.js -->
        <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

        <!-- Custom JavaScript -->
        <script src="{{ asset('vendor/bootstrap/js/sb-admin-2.min.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Check if dark mode is enabled in localStorage
                const darkModePreference = localStorage.getItem('darkMode');

                // Apply dark mode if preference is enabled
                if (darkModePreference === 'enabled') {
                    activateDarkMode();
                }

                // Event listener for the dark mode toggle button
                const darkModeToggle = document.getElementById('darkModeToggle');
                if (darkModeToggle) {
                    darkModeToggle.addEventListener('click', function () {
                        if (document.body.classList.contains('dark-mode')) {
                            deactivateDarkMode();
                        } else {
                            activateDarkMode();
                        }
                    });
                }

                // Function to activate dark mode
                function activateDarkMode() {
                    document.body.classList.add('dark-mode');
                    localStorage.setItem('darkMode', 'enabled');
                }

                // Function to deactivate dark mode
                function deactivateDarkMode() {
                    document.body.classList.remove('dark-mode');
                    localStorage.setItem('darkMode', 'disabled');
                }
            });

            // Function to check brightness
            function getBrightness(hexColor) {
                hexColor = hexColor.replace('#', '');

                const r = parseInt(hexColor.substr(0, 2), 16);
                const g = parseInt(hexColor.substr(2, 2), 16);
                const b = parseInt(hexColor.substr(4, 2), 16);

                return (r * 299 + g * 587 + b * 114) / 1000;
            }

            // Adjust icon and link colors dynamically
            function adjustIconAndLinkColors() {
                const navbar = document.querySelector('.main-header');
                const sidebar = document.querySelector('.main-sidebar');
                const links = document.querySelectorAll('.main-sidebar a, .main-header .nav-link, .dropdown-menu a');
                const icons = document.querySelectorAll('.main-sidebar i, .main-header i, .dropdown-menu i');

                const navbarColor = window.getComputedStyle(navbar).backgroundColor;
                const sidebarColor = window.getComputedStyle(sidebar).backgroundColor;

                function rgbToHex(rgb) {
                    const rgbValues = rgb.match(/\d+/g);
                    return `#${((1 << 24) + (parseInt(rgbValues[0]) << 16) + (parseInt(rgbValues[1]) << 8) + parseInt(rgbValues[2])).toString(16).slice(1)}`;
                }

                function adjustColorBasedOnBrightness(bgColor, elements) {
                    const brightness = getBrightness(rgbToHex(bgColor));
                    const textColor = brightness < 128 ? '#ffffff' : '#000000';

                    elements.forEach(element => {
                        element.style.color = textColor;
                    });
                }

                adjustColorBasedOnBrightness(navbarColor, links);
                adjustColorBasedOnBrightness(navbarColor, icons);
                adjustColorBasedOnBrightness(sidebarColor, links);
                adjustColorBasedOnBrightness(sidebarColor, icons);
            }

            document.addEventListener('DOMContentLoaded', adjustIconAndLinkColors);

            const themePresets = document.getElementById('theme_presets');
            if (themePresets) {
                themePresets.addEventListener('change', adjustIconAndLinkColors);
            }

            const colorInputs = document.querySelectorAll('#background_color, #navbar_color, #sidebar_color');
            colorInputs.forEach(input => {
                input.addEventListener('input', adjustIconAndLinkColors);
            });
        </script>

        @yield('scripts') <!-- For page-specific scripts -->
    </div>
</body>
</html>
