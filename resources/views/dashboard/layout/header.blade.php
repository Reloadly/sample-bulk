<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mx-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav mr-4">
                        <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon feather icon-menu"></i></a></li>
                    </ul>
                    <h4>Welcome back <i><b>{{Auth::user()['name']}}</b></i></h4>
                </div>
            </div>
        </div>
    </div>
</nav>
