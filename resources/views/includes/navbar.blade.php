<nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <div class="navbar-toggle">
                <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            <a class="navbar-brand" href="javascript:;">HR Portal Admin Panel</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav">
                <li class="nav-item dropdown notification_dropdown">
                    <a class="nav-link  ai-icon" href="#" role="button" data-toggle="dropdown" id="notificationAjax" data-id="{{ Auth::id() }}">
                        <svg width="26" height="28" viewBox="0 0 26 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.45251 25.6682C10.0606 27.0357 11.4091 28 13.0006 28C14.5922 28 15.9407 27.0357 16.5488 25.6682C15.4266 25.7231 14.2596 25.76 13.0006 25.76C11.7418 25.76 10.5748 25.7231 9.45251 25.6682Z" fill="#3E4954" />
                            <path d="M25.3531 19.74C23.8769 17.8785 21.3995 14.2195 21.3995 10.64C21.3995 7.09073 19.1192 3.89758 15.7995 2.72382C15.7592 1.21406 14.5183 0 13.0006 0C11.4819 0 10.2421 1.21406 10.2017 2.72382C6.88095 3.89758 4.60064 7.09073 4.60064 10.64C4.60064 14.2207 2.12434 17.8785 0.647062 19.74C0.154273 20.3616 0.00191325 21.1825 0.240515 21.9363C0.473484 22.6721 1.05361 23.2422 1.79282 23.4595C3.08755 23.8415 5.20991 24.2715 8.44676 24.491C9.84785 24.5851 11.3543 24.64 13.0007 24.64C14.646 24.64 16.1524 24.5851 17.5535 24.491C20.7914 24.2715 22.9127 23.8415 24.2085 23.4595C24.9477 23.2422 25.5268 22.6722 25.7597 21.9363C25.9983 21.1825 25.8448 20.3616 25.3531 19.74Z" fill="#3E4954" />
                        </svg>
                        @if (auth()->user()->notificaitons()->where('read', 0)->count() > 0)
                            <span class="badge light text-white bg-primary rounded-circle" id="notificationNumber">{{ auth()->user()->notificaitons()->where('read', 0)->count() }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="heading">
                            <p class="heading--title"> Notifications</p>
                            <a href="{{ url(config('data.admin.user.markNotificationAsRead')) }}" class="heading--mark-read">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g data-name="Layer 2">
                                        <g data-name="done-all">
                                            <path d="M16.62 6.21a1 1 0 0 0-1.41.17l-7 9-3.43-4.18a1 1 0 1 0-1.56 1.25l4.17 5.18a1 1 0 0 0 .78.37 1 1 0 0 0 .83-.38l7.83-10a1 1 0 0 0-.21-1.41zm5 0a1 1 0 0 0-1.41.17l-7 9-.61-.75-1.26 1.62 1.1 1.37a1 1 0 0 0 .78.37 1 1 0 0 0 .78-.38l7.83-10a1 1 0 0 0-.21-1.4z" />
                                            <path d="M8.71 13.06 10 11.44l-.2-.24a1 1 0 0 0-1.43-.2 1 1 0 0 0-.15 1.41z" />
                                        </g>
                                    </g>
                                </svg>
                                Mark all as read</a>
                        </div>
                        <div id="DZ_W_Notification1" class="widget-media dz-scroll p-3 height380">
                            <ul class="timeline" id="displayNotifications">
                                @foreach (auth()->user()->notificaitons()->orderBy('created_at', 'desc')->limit(5)->get() as $notify)
                                    <li class="notification-list" style="{{ $notify->read == 1 ? 'background: #ddd;' : '' }}">
                                        <div class="timeline-panel">
                                            <a href="{{ url($notify->type) }}" data-id="{{ $notify->id }}" class="notification-click">
                                                <div class="media-body">
                                                    <h5 class="mb-1">{{ $notify->title }}</h5>
                                                    <h6 class="mb-1">{{ $notify->message }}</h6>
                                                    <small class="d-block">{{ \Carbon\Carbon::parse($notify->created_at)->format('d M Y - h:m a') }}</small>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                                {{-- <li>
                                        <div class="timeline-panel">
                                            <div class="media mr-2">
                                                <img alt="image" width="50" src="public/images/avatar/1.jpg">
                                            </div>
                                            <div class="media-body">
                                                <h6 class="mb-1">Dr sultads Send you Photo</h6>
                                                <small class="d-block">29 July 2020 - 02:26 PM</small>
                                            </div>
                                        </div>
                                    </li> --}}

                            </ul>
                        </div>
                        <div class="footer-notification">
                            <a href="{{ url(config('data.admin.user.notifications')) }}" class="all-notification">
                                See all notifications <i class="ti-arrow-right"></i></a>
                        </div>
                    </div>
                </li>
                {{-- <li class="nav-item btn-rotate dropdown">
                    <a class="nav-link dropdown-toggle" href="#!" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="nc-icon nc-bell-55"></i>
                        <p>
                            <span class="d-lg-none d-md-block">Some Actions</span>
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li> --}}
                <li class="nav-item">
                    <a href="{{ url('logout') }}" class="nav-link btn-rotate" href="javascript:;">
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
