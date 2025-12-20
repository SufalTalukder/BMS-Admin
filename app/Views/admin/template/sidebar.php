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
?>

<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/activity-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/activity-service') ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="blue" class="bi bi-person" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a7 7 0 0 0-6.293 3.473C1.69 13.751 2.682 15 4 15h8c1.318 0 2.31-1.249 2.293-2.527A7 7 0 0 0 8 9z" />
                </svg>&nbsp;
                <span>Track Your Activity</span>
            </a>
        </li>
    </ul>
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/auth-users-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/auth-users-service') ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="blue" class="bi bi-person" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a7 7 0 0 0-6.293 3.473C1.69 13.751 2.682 15 4 15h8c1.318 0 2.31-1.249 2.293-2.527A7 7 0 0 0 8 9z" />
                </svg>&nbsp;
                <span>Auth User Management</span>
            </a>
        </li>
    </ul>
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?= ($current_url == base_url('admin/banners-service')) ? 'active' : '' ?>" href="<?php echo base_url('admin/banners-service') ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="blue" class="bi bi-person" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a7 7 0 0 0-6.293 3.473C1.69 13.751 2.682 15 4 15h8c1.318 0 2.31-1.249 2.293-2.527A7 7 0 0 0 8 9z" />
                </svg>&nbsp;
                <span>Banner Management</span>
            </a>
        </li>
    </ul>
</aside>