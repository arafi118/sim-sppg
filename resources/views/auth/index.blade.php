<!doctype html>

<html lang="en" class=" layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-skin="default"
    data-assets-path="/assets/" data-template="vertical-menu-template" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Login | {{ env('APP_NAME') }}</title>
    <meta name="description" content="SIM SPPG | Sistem Informasi Manajemen Satuan Pelayanan Pemenuhan Gizi" />
    <meta property="og:description" content="SIM SPPG | Sistem Informasi Manajemen Satuan Pelayanan Pemenuhan Gizi" />

    <link rel="icon" type="image/x-icon" href="/assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="/assets/vendor/fonts/iconify-icons.css" />
    <link rel="stylesheet" href="/assets/vendor/css/pages/page-auth.css" />
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
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Login -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">

                        <div class="app-brand justify-content-center">
                            <a href="index.html" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-heading fw-bold">SIM <span
                                        class="text-primary">SPPG</span>
                            </a>
                        </div>

                        <h4 class="mb-1">Selamat Datang</h4>
                        <p class="mb-6">
                            Silahkan Login menggunakan username dan password anda
                        </p>

                        <form id="formAuthentication" class="mb-6" action="/auth" method="POST">
                            @csrf

                            <div class="mb-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Masukkan Username" autofocus />
                            </div>

                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                            </div>

                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Login -->
            </div>
        </div>
    </div>

    <!-- / Content -->

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
</body>

</html>
