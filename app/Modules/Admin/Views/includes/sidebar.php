<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="<?= admin_base_url('dashboard'); ?>">
                    <span data-feather="home"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= admin_base_url('category/list'); ?>">
                    <span data-feather="box"></span>
                    Manage categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= admin_base_url('track/list'); ?>">
                    <span data-feather="music"></span>
                    Manage tracks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= admin_base_url('package/list'); ?>">
                    <span data-feather="package"></span>
                    Manage packages
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= admin_base_url('discount/list'); ?>">
                    <span data-feather="bell"></span>
                    Manage discounts
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= admin_base_url('user/list'); ?>">
                    <span data-feather="users"></span>
                    Manage users
                </a>
            </li>
            <!--<li class="nav-item">
                <a class="nav-link" href="<?= admin_base_url('user/list'); ?>">
                    <span data-feather="upload"></span>
                    File manager
                </a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" href="<?= admin_base_url('statistics'); ?>">
                    <span data-feather="bar-chart-2"></span>
                    Purchase statistics
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= admin_base_url('settings'); ?>">
                    <span data-feather="settings"></span>
                    Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= admin_base_url('logout'); ?>">
                    <span data-feather="log-out"></span>
                    Log out
                </a>
            </li>
        </ul>

    </div>
</nav>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
