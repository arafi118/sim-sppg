<!doctype html>

<html lang="en" class=" layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-skin="default"
    data-assets-path="/assets/" data-template="vertical-menu-template" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>{{ $title }} | {{ env('APP_NAME') }}</title>
    <meta name="description" content="SIM SPPG | Sistem Informasi Manajemen Satuan Pelayanan Pemenuhan Gizi" />
    <meta property="og:description" content="SIM SPPG | Sistem Informasi Manajemen Satuan Pelayanan Pemenuhan Gizi" />


    <link rel="icon" type="image/x-icon" href="/assets/img/favicon/favicon.ico" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="/assets/vendor/fonts/iconify-icons.css" />
    <link rel="stylesheet" href="/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="/assets/css/demo.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="/assets/vendor/fonts/flag-icons.css" />
    <link rel="stylesheet" href="/assets/vendor/libs/apex-charts/apex-charts.css" />

    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.9.1/dist/pickr.min.js"></script>
    <script src="/assets/vendor/js/helpers.js"></script>
    <script src="/assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <!-- Menu -->
            @include('app.layouts.sidebar')

            <div class="menu-mobile-toggler d-xl-none rounded-1">
                <a href="javascript:void(0);"
                    class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
                    <i class="bx bx-menu icon-base"></i>
                    <i class="bx bx-chevron-right icon-base"></i>
                </a>
            </div>

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('app.layouts.navbar')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="mb-2 mb-md-0">
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , made with ❤️ by <a href="https://themeselection.com" target="_blank"
                                        class="footer-link">ThemeSelection</a>
                                </div>
                                <div class="d-none d-lg-inline-block">

                                    <a href="https://themeselection.com/license/" class="footer-link me-4"
                                        target="_blank">License</a>
                                    <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More
                                        Themes</a>

                                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/"
                                        target="_blank" class="footer-link me-6">Documentation</a>


                                    <a href="https://themeselection.com/support/" target="_blank"
                                        class="footer-link d-none d-sm-inline-block">Support</a>

                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <script src="/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="/assets/vendor/libs/popper/popper.js"></script>
    <script src="/assets/vendor/js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@algolia/autocomplete-js@1.19.2/dist/umd/index.production.js"
        integrity="sha256-pJWTMvqlBlMBxdkM5bI1ScIVC7LFJoCplF0qQtcL62s=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.9.1/dist/pickr.min.js"></script>
    <script src="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.js"
        integrity="sha512-qRj8N7fxOHxPkKjnQ9EJgLJ8Ng1OK7seBn1uk8wkqaXpa7OA13LO6txQ7+ajZonyc9Ts4K/ugXljevkFTUGBcw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18n-js@4.5.1/dist/browser/index.min.js"></script>
    <script src="/assets/vendor/js/menu.js"></script>
    <script src="/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="/assets/js/main.js"></script>

    @yield('script')
</body>

</html>
