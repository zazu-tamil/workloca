<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php include_once('inc/header.php'); ?> 
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Booking Count Summary</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title"> 
                <ol class="breadcrumb text-right">  
                    <li><a href="#">Reports</a></li> 
                    <li class="active">Booking Count Summary</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content mt-3">
            <div class="animated">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo site_url('booking-summary'); ?>" method="post" id="frm"> 
                        <div class="row form-group">
                        <div class="col-md-3 form-group"> 
                            <label>From</label>
                            <input type="date" class="form-control" name="srch_frm_date" id="srch_frm_date" value="<?php echo set_value('srch_frm_date', $srch_frm_date) ?>" />
                        </div>
                        <div class="col-md-3 form-group">
                            <label>To</label>
                            <input type="date" class="form-control" name="srch_to_date" id="srch_to_date" value="<?php echo set_value('srch_to_date', $srch_to_date) ?>" />
                        </div> 
                        <div class="col-md-3 form-group">  
                            <label>State</label>
                            <?php echo form_dropdown('srch_state', array('' => 'All State') + $state_opt , set_value('srch_state', $srch_state),'id="srch_state" class="form-control"')?>
                        </div>
                        <div class="col-md-3 form-group"> 
                            <br />
                            <button class="btn btn-sm btn-info form-control" type="submit">Search</button>
                        </div> 
                        
                        </div>
                         
                        </form>     
                    </div>
                </div> 
                <div class="card">  
                    <div class="card-body">                        
                      <table class="table table-striped table-bordered table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>State</th>  
                                    <th>District</th>  
                                    <th>Count</th>  
                                    <th>Amount</th>  
                                    <th>Apx.Amount</th>  
                                </tr>
                            </thead>
                            <tbody>
                               <?php $total1 = $total_amt1 = $total_amt2 = 0;
                                    foreach($record_list as $state=> $ret){  
                                    echo "<tr><th colspan='6'>". $state ."</th></tr>" ;   
                                    $total = $total_amt = 0;$total_amt_apx = 0;
	                               foreach($ret as $j=> $ls){  
                                     $total += $ls['cnt'];     
                                     $total_amt += $ls['courier_charges'];     
                                     $total_amt_apx += $ls['approx_charges'];     
                                ?>
                               
                                <tr> 
                                    <td class="text-center"><?php echo ($j + 1 );?></td> 
                                    <td class="text-left"><?php echo $ls['state']?></td>  
                                    <td class="text-left text-capitalize"><?php echo strtolower($ls['area']);?></td>  
                                    <td class="text-right"><?php echo number_format($ls['cnt'],0)?></td> 
                                    <td class="text-right"><?php echo number_format($ls['courier_charges'],2)?></td> 
                                    <td class="text-right"><?php echo number_format($ls['approx_charges'],2)?></td> 
                                </tr>
                                <?php
                                    }
                                ?>   
                                <tr>
                                    <th colspan="3" class="text-right">Total</th> 
                                    <th class="text-right"><?php echo number_format($total,0); ?></th>  
                                    <th class="text-right"><?php echo number_format($total_amt,2); ?></th>  
                                    <th class="text-right"><?php echo number_format($total_amt_apx,2); ?></th>  
                                     
                                </tr> 
                                <?php 
                                $total1 += $total;
                                $total_amt1 += $total_amt;
                                $total_amt2 += $total_amt_apx;
                                } ?>   
                                <tr>
                                    <th colspan="3" class="text-right">Total Summary</th> 
                                    <th class="text-right"><?php echo number_format($total1,0); ?></th>  
                                    <th class="text-right"><?php echo number_format($total_amt1,2); ?></th>  
                                    <th class="text-right"><?php echo number_format($total_amt2,2); ?></th>  
                                     
                                </tr>                               
                            </tbody>
                        </table> 
                        <div class="form-group col-sm-6">
                            <label>Total Records : <?php echo count($record_list);?></label>
                        </div>
                         
                    </div>
                    
                     
                
                    
                </div> 
                  
                
            </div><!-- .animated -->
        </div><!-- .content --> 
<?php include_once('inc/footer.php'); ?>