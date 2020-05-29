<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="/dashboard">
                    <div class="brand-logo" style="background : url('{{ \App\System::me()['icon_logo'] }}') no-repeat;"></div>
                    <img src="{{ \App\System::me()['text_logo'] }}" class="brand-text mb-0" width="120px">
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="icon-disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item {{ Request::is('dashboard') || Request::is('dashboard/*')?'active':'' }}">
                <a href="/dashboard">
                    <i class="feather icon-home"></i>
                    <span class="menu-title" data-i18n="dashboard">Dashboard</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('countries') || Request::is('countries/*')?'active':'' }}">
                <a href="/countries">
                    <i class="feather icon-globe"></i>
                    <span class="menu-title" data-i18n="countries">Countries</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('wizard') || Request::is('wizard/*')?'active':'' }}">
                <a href="/wizard">
                    <i class="feather icon-award"></i>
                    <span class="menu-title" data-i18n="wizard">Send Topup</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('topups') || Request::is('topups/*')?'active':'' }}">
                <a href="/topups">
                    <i class="feather icon-codepen"></i>
                    <span class="menu-title" data-i18n="topups">Topups</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('settings') || Request::is('settings/*')?'active':'' }}">
                <a href="/settings">
                    <i class="feather icon-settings"></i>
                    <span class="menu-title" data-i18n="settings">Settings</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('logout') || Request::is('logout/*')?'active':'' }}">
                <a href="/logout">
                    <i class="feather icon-power"></i>
                    <span class="menu-title" data-i18n="logout">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
