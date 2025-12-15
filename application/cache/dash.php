<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php include_once('inc/header.php'); ?> 
<?php /*
	 <div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Dashboard</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
 </div> 
 */
?>

        <div class="content mt-3">
            <div class="row">  
            <div class="col-sm-12 mb-4">
                <div class="card-group">
                    <div class="card col-md-6 no-padding bg-flat-color-1">
                        <div class="card-body text-light text-center">
                            <div class="h1 text-muted text-center mb-4">
                                <i class="fa fa-users text-light"></i>
                            </div>

                            <div class="h4 mb-0">
                                <span class="count"><?php echo $total_visitor; ?></span>
                            </div>

                            <small class="text-uppercase font-weight-bold ">Visitors</small>
                            <div class="progress progress-xs mt-3 mb-0 bg-light" style="width: 100%; height: 5px;"></div>
                        </div>
                    </div>
                    <div class="card col-md-6 no-padding bg-flat-color-2">
                        <div class="card-body text-light text-center">
                            <div class="h1 text-muted text-center mb-4">
                                <i class="fa fa-user-plus text-light"></i>
                            </div>
                            <div class="h4 mb-0">
                                <span class="count"><?php echo $todays_visitor; ?></span>
                            </div>
                            <small class="text-light text-uppercase font-weight-bold">Today's Visitors</small>
                            <div class="progress progress-xs mt-3 mb-0 bg-light" style="width: 100%; height: 5px;"></div>
                        </div>
                    </div>
                    <div class="card col-md-6 no-padding bg-flat-color-3">
                        <div class="card-body text-light text-center">
                            <div class="h1 text-muted text-center mb-4">
                                <i class="fa fa-comment-o text-light"></i>
                            </div>
                            <div class="h4 mb-0">
                                <span class="count"><?php echo $todays_franchisee; ?></span>
                            </div>
                            <small class="text-light text-uppercase font-weight-bold">TODAY'S FRANCHISEE ENQUIRY</small>
                            <div class="progress progress-xs mt-3 mb-0 bg-light" style="width: 100%; height: 5px;"></div>
                        </div>
                    </div>
                    <div class="card col-md-3 no-padding bg-flat-color-4">
                        <div class="card-body text-light text-center">
                            <div class="h1 text-muted text-center mb-4">
                                <i class="fa fa-envelope-o text-light"></i>
                            </div>
                            <div class="h4 mb-0">
                                <a href="<?php echo site_url('pickup-list')?>" target="_blank"><span class="count"><?php echo $todays_pickup; ?></span></a>
                            </div>
                            <small class="text-light text-uppercase font-weight-bold">Today's Pick-Up </small>
                            <div class="progress progress-xs mt-3 mb-0 bg-light" style="width: 100%; height: 5px;"></div>
                        </div>
                    </div>
                    <div class="card col-md-3 no-padding" style="background-color: #D73F21;">
                        <div class="card-body text-light text-center">
                            <div class="h1 text-muted text-center mb-4">
                                <i class="fa fa-rupee text-light"></i>
                            </div>
                            <div class="h4 mb-0">
                                <span class="count"><?php if(isset($today_revenue[date('Ymd')])) echo $today_revenue[date('Ymd')]; ?></span>
                            </div>
                            <small class="text-light text-uppercase font-weight-bold">Today's Collection</small>
                            <div class="progress progress-xs mt-3 mb-0 bg-light" style="width: 100%; height: 5px;"></div>
                        </div>
                    </div>
                    <?php /*
                    <div class="card col-md-6 no-padding ">
                        <div class="card-body">
                            <div class="h1 text-muted text-right mb-4">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <div class="h4 mb-0">5:34:11</div>
                            <small class="text-muted text-uppercase font-weight-bold">Avg. Time</small>
                            <div class="progress progress-xs mt-3 mb-0 bg-flat-color-5" style="width: 40%; height: 5px;"></div>
                        </div>
                    </div>
                    <div class="card col-md-6 no-padding ">
                        <div class="card-body">
                            <div class="h1 text-muted text-right mb-4">
                                <i class="fa fa-comments-o"></i>
                            </div>
                            <div class="h4 mb-0">
                                <span class="count">972</span>
                            </div>
                            <small class="text-muted text-uppercase font-weight-bold">COMMENTS</small>
                            <div class="progress progress-xs mt-3 mb-0 bg-flat-color-1" style="width: 40%; height: 5px;"></div>
                        </div>
                    </div>
                    */ ?>
                </div>
            </div>
            </div>
          <div class="row">  
               <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">Courier Pickup <span class="badge badge-success">Last 10 Days</span></h4>
                            <canvas id="pickup-1"></canvas>
                        </div>
                    </div>
               </div><!-- /# column -->
               <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">Courier Pickup <span class="badge badge-success">Last 10 Months</span></h4>
                            <canvas id="pickup-2"></canvas>
                        </div>
                    </div>
               </div><!-- /# column -->  
           </div>  
           <?php if($this->session->userdata('m_is_admin') != USER_MANAGER){ ?>
           <div class="row">
           <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Pickup Revenue <span class="badge badge-success">Last 10 Days</span></h4>
                        <canvas id="pickup-3"></canvas>
                    </div>
                </div>
           </div><!-- /# column -->  
           <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Pickup Revenue <span class="badge badge-success">Last 10 Months</span></h4>
                        <canvas id="pickup-4"></canvas>
                    </div>
                </div>
           </div><!-- /# column --> 
           </div>
            <?php } ?>
           <div class="row">
           <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Site Visitors <span class="badge badge-success">Last 10 Days </span></h4>
                        <canvas id="visitor-1"></canvas>
                    </div>
                </div>
           </div><!-- /# column -->
           <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Site Visitors <span class="badge badge-success">Last 10 Months</span></h4>
                        <canvas id="visitor-2"></canvas>
                    </div>
                </div>
           </div><!-- /# column -->  
           </div>
           <div class="row">
           <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Visitors vs Pickups <span class="badge badge-success">Last 7 Days</span></h4>
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
           </div><!-- /# column -->
           <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Visitors vs Pickups <span class="badge badge-success">Last 5 Months</span></h4>
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
           </div><!-- /# column --> 
          </div>  
          <div class="row"> 
           <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Domestic Pickup <span class="badge badge-info">Top 10 Cities </span> <span class="badge badge-success"><?php echo date('M-Y'); ?></span> </h4>
                        <canvas id="dtpick"></canvas>
                    </div>
                </div>
            </div><!-- /# column --> 
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">International Pickup <span class="badge badge-info">Top 10 Cities </span> <span class="badge badge-success"><?php echo date('M-Y'); ?></span> </h4>
                        <canvas id="intpick"></canvas>
                    </div>
                </div>
            </div><!-- /# column --> 
            </div>
            <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Courier Status <span class="badge badge-success"><?php echo date('M-Y'); ?></span> </h4>
                        <canvas id="polarChart"></canvas>
                    </div>
                </div>
            </div><!-- /# column -->  
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Courier Status <span class="badge badge-success"><?php //echo $polar_prev_mon; ?></span> </h4>
                        <canvas id="polarChart_prev"></canvas>
                    </div>
                </div>
            </div><!-- /# column -->    
            </div>
            <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                            <h4 class="mb-3">Todays Pickup <span class="badge badge-info">Domestic</span></h4>
                         <table class="table table-bordered  table-striped table-condensed" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>State</th>
                                <th>District</th>
                                <th>Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($domestic_pick_up_summary as $k => $info) {?>
                                <tr>
                                    <td><?php echo ($k+1); ?></td>
                                    <td><?php echo $info['state']; ?></td>
                                    <td class="text-capitalize"><?php echo strtolower($info['area']); ?></td>
                                    <td><?php echo $info['cnt']; ?></td>
                                </tr>
                            <?php }?>
                            </tbody>
                         </table>
                    </div>
                </div>
            </div><!-- /# column --> 
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                            <h4 class="mb-3">Todays Pickup <span class="badge badge-info">International</span></h4>
                         <table class="table table-bordered  table-striped table-condensed" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>State</th>
                                <th>District</th>
                                <th>Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($international_pick_up_summary as $k => $info) {?>
                                <tr>
                                    <td><?php echo ($k+1); ?></td>
                                    <td><?php echo $info['state']; ?></td>
                                    <td class="text-capitalize"><?php echo strtolower($info['area']); ?></td>
                                    <td><?php echo $info['cnt']; ?></td>
                                </tr>
                            <?php }?>
                            </tbody>
                         </table>
                    </div>
                </div>
            </div><!-- /# column -->
            </div>
            <div class="row">            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Today's Delivery List</h4>
                        <table class="table table-bordered  table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Ref.No</th>
                                <th>Source</th>
                                <th>Destination</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($todays_delivery as $k => $info) {?>
                                <tr>
                                    <td><?php echo ($k+1); ?></td>
                                    <td><?php echo $info['ref_no']; ?></td> 
                                    <td><?php echo $info['src_state'] . '<br>' . $info['src_city']; ?></td>
                                    <td><?php echo $info['dest_state'] . '<br>' . $info['dest_city']; ?></td>
                                </tr>
                            <?php }?>
                            </tbody>
                         </table>
                    </div>
                </div>
            </div><!-- /# column -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Today's Pickup List</h4>
                        <table class="table table-bordered  table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Ref.No</th>
                                <th>Source</th>
                                <th>Destination</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($todays_pick as $k => $info) {?>
                                <tr>
                                    <td><?php echo ($k+1); ?></td>
                                    <td><?php echo $info['ref_no']; ?></td> 
                                    <td><?php echo $info['src_state'] . '<br>' . $info['src_city']; ?></td>
                                    <td><?php echo $info['dest_state'] . '<br>' . $info['dest_city']; ?></td>
                                </tr>
                            <?php }?>
                            </tbody>
                         </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Today's Log</h4>
                        <div id="todays_logs"></div>
                    </div>
                </div>
            </div><!-- /# column --> 
            </div>

        </div> <!-- .content -->
<?php include_once('inc/footer.php'); ?>