<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo base_url() ?>/asset/images/user.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <a href="#">
                    <p><?php echo strtoupper($this->session->userdata(SESS_HD . 'user_name')); ?></p>
                </a>
                <a href="#"><i class="fa fa-circle text-success"></i> Online </a>

            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <?php
            
            if ($this->session->userdata(SESS_HD . 'user_type') == 'Admin') {
                include_once('admin-menu.php');
            }
            
            ?>
            <li>
                <a href="<?php echo site_url('logout') ?>">
                    <i class="fa fa-sign-out"></i> <span>Logout</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>