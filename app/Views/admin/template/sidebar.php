<style>
    .sidebar .nav-link {
        color: #333;
        /* Default link color */
        padding: 10px 20px;
        display: flex;
        align-items: center;
        text-decoration: none;
        border-radius: 4px;
    }

    .sidebar .nav-link:hover {
        background-color: #0bcbe2;
        color: #fff;

    }

    .sidebar .nav-link.active {
        background-color: #0bcbe2;
        color: #fff;
    }

    .sidebar .nav-link svg {
        margin-right: 8px;
        fill: currentColor;
    }

    .nav-content {
        background-color: #e9ecef;
    }

    .nav-content .nav-link {
        padding-left: 30px;
        /* Indent for nested items */
    }

    .nav-content .nav-link:hover {
        background-color: #495057;
        color: #fff;
    }

    /* Hide chevron icons when collapsed */
    .nav-item .bi-chevron-down {
        transition: transform 0.3s ease;
    }

    .nav-item .nav-link.collapsed .bi-chevron-down {
        transform: rotate(180deg);
    }
</style>

<?php
$current_url = current_url();
$store_active = in_array($current_url, [
    base_url('admin/category-service'),
    base_url('admin/sub-category-service'),
    base_url('admin/product-service')
]);
?>

<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/activity-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/activity-service') ?>">
                <i class="bi bi-file-person"></i>
                &nbsp;
                <span>Track Your Activity</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/systems-activity-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/systems-activity-service') ?>">
                <i class="bi bi-file-person"></i>
                &nbsp;
                <span>Track System(s) Activity</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/auth-users-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/auth-users-service') ?>">
                <i class="ri-shield-user-fill"></i>
                &nbsp;
                <span>Auth User Management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/users-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/users-service') ?>">
                <i class="ri-user-received-fill"></i>
                &nbsp;
                <span>User Management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/banners-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/banners-service') ?>">
                <i class="bi bi-card-image"></i>
                &nbsp;
                <span>Banner Management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/language-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/language-service') ?>">
                <i class="ri-english-input"></i>
                &nbsp;
                <span>Language Management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $store_active ? '' : 'collapsed'; ?>" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="ri-shopping-bag-3-line"></i><span>Product Management</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse <?= $store_active ? 'show' : ''; ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?= base_url('admin/category-service') ?>" class="<?= ($current_url == base_url('admin/category-service')) ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Manage Category</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/sub-category-service') ?>" class="<?= ($current_url == base_url('admin/sub-category-service')) ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Manage Subcategory</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/product-service') ?>" class="<?= ($current_url == base_url('admin/product-service')) ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Manage Product</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/wishlist-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/wishlist-service') ?>">
                <i class="bx bx-bookmark-heart"></i>
                &nbsp;
                <span>Wishlist Management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/cart-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/cart-service') ?>">
                <i class="bx bx-cart"></i>
                &nbsp;
                <span>Cart Management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/checkout-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/checkout-service') ?>">
                <i class="ri-secure-payment-fill"></i>
                &nbsp;
                <span>Checkout Management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/newsletter-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/newsletter-service') ?>">
                <i class="bx bx-news"></i>
                &nbsp;
                <span>Newsletter Management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/support-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/support-service') ?>">
                <i class="bx bx-news"></i>
                &nbsp;
                <span>Support</span>
            </a>
        </li>
    </ul>
</aside>