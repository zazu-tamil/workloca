<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo PG_HEAD;
    if (isset($title))
        echo " - " . $title; ?></title>
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url() ?>/asset/images/icon-cce.png">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/dist/css/skins/_all-skins.min.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/morris.js/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/jvectormap/jquery-jvectormap.css">
    <!-- Date Picker -->
    <link rel="stylesheet"
        href="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet"
        href="<?php echo base_url() ?>asset/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet"
        href="<?php echo base_url() ?>asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <style>
        .skin-blue .main-header .navbar {
            background-color: #eca810;
        }

        .skin-blue .main-header .logo {
            background-color: #eca810 !important;
        }

        .skin-blue .wrapper,
        .skin-blue .main-sidebar,
        .skin-blue .left-side {
            background-color: #1b1b1d;
        }

        .skin-blue .sidebar-menu>li.header {
            color: #ffffff !important;
            background: #eca810 !important;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class=" logo-mini">
                    <!-- <img
                        src="<?php echo base_url() ?>/asset/images/Classwall-Logo-SM.png"
                        class="img-circle img-responsive" alt="<?php echo PG_HEAD; ?>"> -->
                    <?php echo PG_HEAD; ?>
                </span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><?php echo PG_HEAD; ?></span>
                <!-- <div class="logo-lg" style="background-color:#f4f4f4;border-radius:0px; ">
                    <img
                        src="<?php echo base_url() ?>/asset/images/Classwall-H.png" class="img-rounded img-responsive"
                        alt="<?php echo PG_HEAD; ?>"></div> -->
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <span class="navbar-brand hidden-xs" style="font-size: 18px; color: #fff; margin-left: 15px;">
                    <?php echo COMPANY; ?>
                </span>

                <div class="navbar-custom-menu">

                    <ul class="nav navbar-nav">

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo base_url() ?>asset/images/user.jpg" class="user-image"
                                    alt="User Image">
                                <span
                                    class="hidden-xs"><?php echo strtoupper($this->session->userdata(SESS_HD . 'staff_name')); ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo base_url() ?>asset/images/user.jpg" class="img-circle"
                                        alt="User Image">

                                    <p>
                                        <?php echo strtoupper($this->session->userdata(SESS_HD . 'staff_name')); ?>
                                        <small><?php echo date('d-M-Y') ?></small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <?php /*
                 <li class="user-body">
                   <div class="row">
                     <div class="col-xs-4 text-center">
                       <a href="#">Followers</a>
                     </div>
                     <div class="col-xs-4 text-center">
                       <a href="#">Sales</a>
                     </div>
                     <div class="col-xs-4 text-center">
                       <a href="#">Friends</a>
                     </div>
                   </div>
                   <!-- /.row -->
                 </li> */ ?>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left hide">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo site_url('logout') ?>"
                                            class="btn btn-defaultv btn-flat btn-danger">Sign
                                            out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <li class="hide">
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <?php include_once('left-menu.php'); ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div id="zazualert"></div>