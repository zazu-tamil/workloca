<?php
$current_page = $this->uri->segment(1);
?>

<!-- DASHBOARD -->
<li class="header">Dashboard</li>

<li class="<?= ($current_page == 'dash') ? 'active' : '' ?>">
    <a href="<?= site_url('dash') ?>">
        <i class="fa fa-dashboard"></i>
        <span>Dashboard</span>
    </a>
</li>

<!-- EMPLOYEE DETAILS -->
<?php
$employee_pages = ['employee-details-add','employee-details-list','employee-details-edit'];
$employee_active = in_array($current_page, $employee_pages);
?>
<li class="treeview <?= $employee_active ? 'active treeview-open' : '' ?>">
    <a href="#">
        <i class="fa fa-users"></i>
        <span>Employee Details</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu" style="<?= $employee_active ? 'display:block;' : '' ?>">
        <li class="<?= ($current_page == 'employee-details-add') ? 'active' : '' ?>">
            <a href="<?= site_url('employee-details-add') ?>">
                <i class="fa fa-plus"></i> Add Employee
            </a>
        </li>
        <li class="<?= ($current_page == 'employee-details-list') ? 'active' : '' ?>">
            <a href="<?= site_url('employee-details-list') ?>">
                <i class="fa fa-list"></i> Employee List
            </a>
        </li>
    </ul>
</li>

<!-- SUPERVISOR DETAILS -->
<?php
$supervisor_pages = ['supervisor-details-add','supervisor-details-list','supervisor-details-edit'];
$supervisor_active = in_array($current_page, $supervisor_pages);
?>
<li class="treeview <?= $supervisor_active ? 'active treeview-open' : '' ?>">
    <a href="#">
        <i class="fa fa-user-secret"></i>
        <span>Supervisor Details</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu" style="<?= $supervisor_active ? 'display:block;' : '' ?>">
        <li class="<?= ($current_page == 'supervisor-details-add') ? 'active' : '' ?>">
            <a href="<?= site_url('supervisor-details-add') ?>">
                <i class="fa fa-plus"></i> Add Supervisor
            </a>
        </li>
        <li class="<?= ($current_page == 'supervisor-details-list') ? 'active' : '' ?>">
            <a href="<?= site_url('supervisor-details-list') ?>">
                <i class="fa fa-list"></i> Supervisor List
            </a>
        </li>
    </ul>
</li>
<?php
$company_page = ['company-add','company-list','company-edit'];
$company_active = in_array($current_page, $company_page);
?>
<li class="treeview <?= $company_active ? 'active treeview-open' : '' ?>">
    <a href="#">
        <i class="fa fa-user-secret"></i>
        <span>company Details</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu" style="<?= $company_active ? 'display:block;' : '' ?>">
        <li class="<?= ($current_page == 'company-add') ? 'active' : '' ?>">
            <a href="<?= site_url('company-add') ?>">
                <i class="fa fa-plus"></i> Add Company
            </a>
        </li>
        <li class="<?= ($current_page == 'company-list') ? 'active' : '' ?>">
            <a href="<?= site_url('company-list') ?>">
                <i class="fa fa-list"></i> Company List
            </a>
        </li>
    </ul>
</li>

<!-- MASTER -->
<li class="header">Master</li>

<?php
$master_pages = [
    'employee-category-list','employee-skill-list','pincode-list',
    'health-issues-list','sports-list','hobbies-list',
    'disability-list','business-list','talent-list','user-list'
];
$master_active = in_array($current_page, $master_pages);
?>
<li class="treeview <?= $master_active ? 'active treeview-open' : '' ?>">
    <a href="#">
        <i class="fa fa-cog"></i>
        <span>Masters Info</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>

    <ul class="treeview-menu" style="<?= $master_active ? 'display:block;' : '' ?>">

        <li class="<?= ($current_page == 'employee-category-list') ? 'active' : '' ?>">
            <a href="<?= site_url('employee-category-list') ?>">
                <i class="fa fa-cubes"></i> Employee Category
            </a>
        </li>

        <li class="<?= ($current_page == 'employee-skill-list') ? 'active' : '' ?>">
            <a href="<?= site_url('employee-skill-list') ?>">
                <i class="fa fa-cogs"></i> Employee Skill
            </a>
        </li>

        <li class="<?= ($current_page == 'pincode-list') ? 'active' : '' ?>">
            <a href="<?= site_url('pincode-list') ?>">
                <i class="fa fa-map-marker"></i> Pincode
            </a>
        </li>

        <li class="<?= ($current_page == 'health-issues-list') ? 'active' : '' ?>">
            <a href="<?= site_url('health-issues-list') ?>">
                <i class="fa fa-stethoscope"></i> Health Issues
            </a>
        </li>

        <li class="<?= ($current_page == 'sports-list') ? 'active' : '' ?>">
            <a href="<?= site_url('sports-list') ?>">
                <i class="fa fa-futbol-o"></i> Sports
            </a>
        </li>

        <li class="<?= ($current_page == 'hobbies-list') ? 'active' : '' ?>">
            <a href="<?= site_url('hobbies-list') ?>">
                <i class="fa fa-heart"></i> Hobbies
            </a>
        </li>

        <li class="<?= ($current_page == 'disability-list') ? 'active' : '' ?>">
            <a href="<?= site_url('disability-list') ?>">
                <i class="fa fa-wheelchair"></i> Disability
            </a>
        </li>

        <li class="<?= ($current_page == 'business-list') ? 'active' : '' ?>">
            <a href="<?= site_url('business-list') ?>">
                <i class="fa fa-briefcase"></i> Business
            </a>
        </li>

        <li class="<?= ($current_page == 'talent-list') ? 'active' : '' ?>">
            <a href="<?= site_url('talent-list') ?>">
                <i class="fa fa-star"></i> Talent
            </a>
        </li>

        <li class="<?= ($current_page == 'user-list') ? 'active' : '' ?>">
            <a href="<?= site_url('user-list') ?>">
                <i class="fa fa-user"></i> Create Users
            </a>
        </li>

    </ul>
</li>
