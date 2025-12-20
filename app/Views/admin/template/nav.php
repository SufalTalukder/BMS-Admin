<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="<?= base_url('admin/auth-users') ?>" class="logo d-flex align-items-center">
            <img src="<?= base_url('assets/img/e-com.jfif') ?>" alt="E-commerce Logo">
            <span class="d-none d-lg-block"><?= PROJECT_NAME; ?></span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <img src="<?= !empty($auth_user_details->authUserImage) ? $auth_user_details->authUserImage : base_url('assets/img/admin.jfif') ?>" alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2"><?= !empty($auth_user_details->authUserName) ? $auth_user_details->authUserName : "NA"; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <span><?= !empty($auth_user_details->authUserPhoneNumber) ? $auth_user_details->authUserPhoneNumber : "NA"; ?></span>
                        <h6><?= !empty($auth_user_details->authUserEmailAddress) ? $auth_user_details->authUserEmailAddress : "NA"; ?></h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/logout') ?> ">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>