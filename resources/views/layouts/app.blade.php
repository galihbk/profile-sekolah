<!doctype html>
<html lang="en" class="color-sidebar sidebarcolor5">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/img/LogoSLMatahari.png') }}" type="image/png" />
    <link href="{{ asset('assets-admin/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets-admin/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets-admin/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets-admin/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets-admin/js/pace.min.js') }}"></script>
    <link href="{{ asset('assets-admin/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets-admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets-admin/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('assets-admin/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets-admin/css/icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets-admin/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-admin/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-admin/css/header-colors.css') }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="{{ asset('assets/img/LogoSLMatahari.png') }}" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text">SL Matahari</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">

                {{-- Menu untuk semua role --}}
                <li>
                    <a href="{{ route('dashboard') }}">
                        <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>

                {{-- Menu khusus untuk admin --}}
                @if(Auth::user()->role === 'admin')
                <li>
                    <a href="{{ route('medis') }}">
                        <div class="parent-icon"><i class='bx bx-book-heart'></i></div>
                        <div class="menu-title">Data Medis</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}">
                        <div class="parent-icon"><i class='bx bx-group'></i></div>
                        <div class="menu-title">Data Lansia</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.pengajar') }}">
                        <div class="parent-icon"><i class='bx bx-group'></i></div>
                        <div class="menu-title">Data Pengajar</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('diagnosa') }}">
                        <div class="parent-icon"><i class='bx bx-book-content'></i></div>
                        <div class="menu-title">Data Diagnosa</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('berita') }}">
                        <div class="parent-icon"><i class='bx bx-list-check'></i></div>
                        <div class="menu-title">Berita</div>
                    </a>
                </li>
                @endif

                {{-- Menu untuk pengajar --}}
                @if(Auth::user()->role === 'pengajar')
                <li>
                    <a href="{{ route('materi') }}">
                        <div class="parent-icon"><i class='bx bx-folder'></i></div>
                        <div class="menu-title">Data Materi</div>
                    </a>
                </li>
                @endif

                {{-- Menu untuk user --}}
                @if(Auth::user()->role === 'user')
                <li>
                    <a href="{{ route('medis.history') }}">
                        <div class="parent-icon"><i class='bx bx-history'></i></div>
                        <div class="menu-title">History Pemeriksaan</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('materi.user') }}">
                        <div class="parent-icon"><i class='bx bx-folder'></i></div>
                        <div class="menu-title">Data Materi</div>
                    </a>
                </li>
                @endif

            </ul>

            <!--end navigation-->
        </div>
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>
                    <div class="search-bar flex-grow-1">
                        <div class="position-relative search-bar-box">
                            <input type="text" class="form-control search-control" placeholder="Type to search...">
                            <span class="position-absolute top-50 search-show translate-middle-y"><i
                                    class='bx bx-search'></i></span>
                            <span class="position-absolute top-50 search-close translate-middle-y"><i
                                    class='bx bx-x'></i></span>
                        </div>
                    </div>
                    <div class="top-menu ms-auto" style="display: none;">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item mobile-search-icon">
                                <a class="nav-link" href="#"> <i class='bx bx-search'></i>
                                </a>
                            </li>
                            <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false"> <i
                                        class='bx bx-category'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="row row-cols-3 g-3 p-3">

                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="alert-count">7</span>
                                    <i class='bx bx-bell'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Notifications</p>
                                            <p class="msg-header-clear ms-auto">Marks all as read</p>
                                        </div>
                                    </a>
                                    <div class="header-notifications-list">
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="alert-count">8</span>
                                    <i class='bx bx-comment'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Messages</p>
                                            <p class="msg-header-clear ms-auto">Marks all as read</p>
                                        </div>
                                    </a>
                                    <div class="header-message-list">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-info ps-3">
                                <p class="user-name mb-0">{{Auth::user()->name}}</p>
                                <p class="designattion mb-0">{{Auth::user()->role}}</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:;"><i
                                        class="bx bx-user"></i><span>Profile</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class='bx bx-log-out-circle'></i> <span>Logout</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <div class="page-wrapper">
            <div class="page-content">
                {{ $slot }}
            </div>
        </div>
        <div class="overlay toggle-icon"></div>
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <footer class="page-footer">
            <p class="mb-0">Copyright © 2021. All right reserved.</p>
        </footer>
    </div>
    <script src="{{ asset('assets-admin/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('assets-admin/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets-admin/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ url('assets-admin') }}/plugins/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('assets-admin/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets-admin/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets-admin/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets-admin/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <!--app JS-->
    @stack('scripts')
    <script src="{{ asset('assets-admin/js/app.js') }}"></script>
</body>

</html>