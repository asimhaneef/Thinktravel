<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Think Travel</title>
    <link rel="icon" href="{{asset(config('constants.IMAGES_PATH').'/fav.png')}}" type="image/x-icon">
    <link href="{{ asset(config('constants.ASSETS_PATH') . '/themecss.css') }}" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" id="app">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item sidebar-toggle">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <ul class="ml-auto navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">0</span>
                    </a>
                    <!--<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="mr-2 fas fa-envelope"></i> 4 new messages
                            <span class="float-right text-sm text-muted">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="mr-2 fas fa-users"></i> 8 friend requests
                            <span class="float-right text-sm text-muted">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="mr-2 fas fa-file"></i> 3 new reports
                            <span class="float-right text-sm text-muted">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>-->
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <!-- Stop Impersonation Button (Visible only if impersonating) -->
                @if (session()->has('impersonated_by'))
                <li class="nav-item">
                    <form id="stop-impersonation-form" action="{{ route('impersonate.stop') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a class="nav-link" href="#" title="Stop Impersonation"
                        onclick="event.preventDefault(); document.getElementById('stop-impersonation-form').submit();">
                        <i class="fas fa-user-slash"></i>
                    </a>
                </li>
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-power-off"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                        <a href="#" class="dropdown-item"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="mr-2 fas fa-power-off"></i> Logout
                        </a>
                        <router-link to="/change-password" class="dropdown-item">
                            <i class="mr-2 fas fa-key"></i> Change Password
                        </router-link>
                    </div>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!--<a href="{{ url('/dashboard') }}" class="brand-link ">-->
            <router-link to="/" class="nav-link comp-logo">
                <img src="{{ asset(config('constants.IMAGES_PATH') . '/logo-transparent.png') }}" alt="Admin LTE Logo"
                    class="pl-2 pr-2">
            </router-link>
            <router-link to="/" class="nav-link small-logo" style="display: none;">
                <img src="{{ asset(config('constants.IMAGES_PATH') . '/logo-sm.png') }}" alt="Admin LTE Logo"
                    class="pl-2 pr-2">
            </router-link>
            <!--</a>-->
            <div class="sidebar">
                <nav class="mt-2 mb-4">
                    <ul class="nav nav-pills nav-sidebar flex-column mb-5" data-widget="treeview" role="menu"
                        data-accordion="false">
                        @can('system-menus.view')
                        <li class="nav-header">SYSTEM MENU</li>
                        @can('dashboard.view')
                        <li class="nav-item">
                            <router-link to="/dashboard" class="nav-link">
                                <i class="pi pi-home nav-icon"></i>
                                <p>Dashboard</p>
                            </router-link>
                        </li>
                        @endcan
                        @can('dashboard.view')
                        <li class="nav-item">
                            <router-link to="/inquiries-dashboard" class="nav-link">
                                <i class="pi pi-question-circle nav-icon"></i>
                                <p>Inquiries Dashboard</p>
                            </router-link>
                        </li>
                        @endcan
                        @can('dashboard.view') 
                        <li class="nav-item">
                            <router-link to="/sales-dashboard" class="nav-link">
                                <i class="pi pi-money-bill nav-icon"></i>
                                <p>Sales Dashboard</p>
                            </router-link>
                        </li>
                        @endcan
                        @can('users.view') 
                        <li class="nav-item">
                            <router-link to="/users" class="nav-link">
                                <i class="pi pi-user nav-icon"></i>
                                <p>User Managenement</p>
                            </router-link>
                        </li>
                        @endcan
                        @endcan
                        <li class="nav-item">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                            <!--<a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>-->
                        </li>
                        @can('application-menus.view')
                        
                        <li class="nav-header">Application Menu</li>
                        @can('members.view')
                        <li class="nav-item">
                            <router-link to="/members" class="nav-link">
                                <i class="pi pi-users nav-icon"></i>
                                <p>Members</p>
                            </router-link>
                        </li>
                        @endcan
                        @can('link-categories.view')
                        <li class="nav-item">
                            <router-link to="/link-categories" class="nav-link">
                                <i class="pi pi-link nav-icon"></i>
                                <p>Link Categories</p>
                            </router-link>
                        </li>  
                        @endcan
                        @can('bookings.view') 
                        <li class="nav-item">
                            <router-link to="/inquiries" class="nav-link">
                                <i class="pi pi-file nav-icon"></i>
                                <p>Inquiries</p>
                            </router-link>
                        </li> 
                        @endcan 
                        @can('sale-forms.view')  
                        <li class="nav-item">
                            <router-link to="/sale-form" class="nav-link">
                                <i class="pi pi-shopping-cart nav-icon"></i>
                                <p>Sale Form</p>
                            </router-link>
                        </li>
                        @endcan
                        @endcan
                        @can('lookup-data.view')
                        <li class="nav-header">Lookup Data</li>
                        @can('regions.view')
                                <li class="nav-item">
                                    <router-link to="/regions" class="nav-link">
                                        <i class="pi pi-sitemap nav-icon"></i>
                                        <p>Regions</p>
                                    </router-link>
                                </li>
                                
                        @endcan
                        @can('airports.view')
                                <li class="nav-item">
                                    <router-link to="/airports" class="nav-link">
                                        <i class="pi pi-map nav-icon"></i>
                                        <p>Airports</p>
                                    </router-link>
                                </li>
                        @endcan
                        @can('airlines.view')
                                <li class="nav-item">
                                    <router-link to="/airlines" class="nav-link">
                                        <i class="pi pi-send nav-icon"></i>
                                        <p>AirLines</p>
                                    </router-link>
                                </li>
                        @endcan
                        @can('countries.view')
                                <li class="nav-item">
                                    <router-link to="/countries" class="nav-link">
                                        <i class="pi pi-globe nav-icon"></i>
                                        <p>Countries</p>
                                    </router-link>
                                </li>
                        @endcan
                        @can('provinces.view')
                                <li class="nav-item">
                                    <router-link to="/provinces" class="nav-link">
                                        <i class="pi pi-map-marker nav-icon"></i>
                                        <p>Provinces</p>
                                    </router-link>
                                </li>
                        @endcan
                        @can('cities.view')                                
                                <li class="nav-item">
                                    <router-link to="/cities" class="nav-link">
                                        <i class="pi pi-building nav-icon"></i>
                                        <p>Cities</p>
                                    </router-link>
                                </li>
                        @endcan
                        @can('codes-lists.view')
                                <li class="nav-item">
                                    <router-link to="/codes-list" class="nav-link">
                                        <i class="pi pi-sitemap nav-icon"></i>
                                        <p>Supplier List</p>
                                    </router-link>
                                </li>
                        @endcan
                        @can('file-managments.view')
                                <li class="nav-item">
                                    <router-link to="/file-management" class="nav-link">
                                        <i class="pi pi-file nav-icon"></i>
                                        <p>Reference documents</p>
                                    </router-link>
                                </li>
                        @endcan
                        @endcan
                        @can('roles-&-permissions-menus.view')
                                <li class="nav-header">Role & Rights</li>
                                <li class="nav-item">
                                    <router-link to="/roles" class="nav-link">
                                        <i class="pi pi-envelope nav-icon"></i>
                                        <p>Roles</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/permissions" class="nav-link">
                                        <i class="pi pi-database nav-icon"></i>
                                        <p>Permissions</p>
                                    </router-link>
                                </li>
                        @endcan
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="content-wrapper">
            <router-view></router-view>
        </div>
        <aside class="control-sidebar control-sidebar-dark">
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                Anything you want ❤
            </div>
            <strong>Copyright &copy; 2014-2024 <a href="#">Three Apples</a>.</strong> All rights reserved.
        </footer>
    </div>
</body>

</html>
