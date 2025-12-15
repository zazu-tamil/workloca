<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php include_once('inc/header.php'); ?>  
        <div class="content mt-3">
            <div class="row">  
                <div class="col-md-12 mb-4">
                     <div class="card card-info">
                        <div class="card-header">Last 10 days Booking</div>
                        <div class="card-body">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Date</th>  
                                        <th class="text-center">Booking</th>   
                                        <th class="text-right">C Amount</th>   
                                        <th class="text-right">A Amount</th>  
                                        <th class="text-right">E/B</th>  
                                    </tr>
                                </thead>
                                  <tbody>
                                       <?php $tot = $tot1 = $bok =  $vis =  0;  
                                           foreach($last_booking_list as $j=> $ls){ 
                                            $tot +=$ls['courier_charges'];
                                            $tot1 +=$ls['approx_charges'];
                                            $bok +=$ls['cnt']; 
                                        ?> 
                                        <tr> 
                                            <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                                            <td><?php echo $ls['b_day']?></td>     
                                            <td class="text-center"><?php echo $ls['cnt']?></td>   
                                            <td class="text-right"><?php echo number_format($ls['courier_charges'],2)?></td>   
                                            <td class="text-right"><?php echo number_format($ls['approx_charges'],2)?></td> 
                                            <td class="text-right"><?php echo number_format(($ls['approx_charges']/ $ls['cnt']),2)?></td>  
                                        </tr>
                                        <?php
                                            }
                                        ?>                                 
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">Total</th>  
                                            <th class="text-center"><?php echo number_format($bok,0)?></th>
                                            <th class="text-right"><?php echo number_format($tot,2)?></th>
                                            <th class="text-right"><?php echo number_format($tot1,2)?></th>
                                            <th class="text-right"><?php echo number_format(($tot1 / $bok),2)?></th> 
                                            
                                        </tr> 
                                    </tfoot>
                              </table> 
                        </div>
                     </div>   
                     
                </div>
            </div>
            <div class="row">  
                <div class="col-md-12 mb-4">
                     <div class="card card-info">
                        <div class="card-header">Last 12 Month Booking</div>
                        <div class="card-body">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Month</th>   
                                        <th>Visitors</th>   
                                        <th>Booking</th>   
                                        <th>C Amount</th>   
                                        <th>A Amount</th>    
                                        <th>E/B</th>    
                                        <th>E/V</th>    
                                        <th>V/B</th>    
                                    </tr>
                                </thead>
                                  <tbody>
                                       <?php $tot = $tot1 = $bok =  $vis =  0;  
                                           foreach($booking_summary as $j=> $ls){ 
                                            $tot +=$ls['courier_charges'];
                                            $tot1 +=$ls['approx_charges'];
                                            $bok +=$ls['cnt'];
                                            $vis +=$ls['visitor'];
                                        ?> 
                                        <tr> 
                                            <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                                            <td><?php echo $ls['b_month']?></td>   
                                            <td class="text-center"><?php echo $ls['visitor']?></td>   
                                            <td class="text-center"><?php echo $ls['cnt']?></td>   
                                            <td class="text-right"><?php echo number_format($ls['courier_charges'],2)?></td>   
                                            <td class="text-right"><?php echo number_format($ls['approx_charges'],2)?></td> 
                                            <td class="text-right"><?php echo number_format(($ls['approx_charges']/ $ls['cnt']),2)?></td> 
                                            <td class="text-right"><?php echo number_format(($ls['approx_charges']/$ls['visitor']),2)?></td> 
                                            <td class="text-right"><?php echo number_format(($ls['visitor']/$ls['cnt']),0)?></td> 
                                        </tr>
                                        <?php
                                            }
                                        ?>                                 
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">Total</th> 
                                            <th class="text-right"><?php echo number_format($vis,0)?></th>
                                            <th class="text-right"><?php echo number_format($bok,0)?></th>
                                            <th class="text-right"><?php echo number_format($tot,2)?></th>
                                            <th class="text-right"><?php echo number_format($tot1,2)?></th>
                                            <th class="text-right"><?php echo number_format(($tot1 / $bok),2)?></th>
                                            <th class="text-right"><?php echo number_format(($tot1 / $vis),2)?></th>
                                            <th class="text-right"><?php echo number_format(($vis / $bok),0)?></th>
                                            
                                        </tr>
                                        <tr>
                                            <th colspan="2">Avg / Month</th> 
                                            <th class="text-right"><?php echo number_format(($vis /12),0)?></th>
                                            <th class="text-right"><?php echo number_format(($bok/12),0)?></th>
                                            <th class="text-right"><?php echo number_format(($tot/12),2)?></th>
                                            <th class="text-right"><?php echo number_format(($tot1/12),2)?></th>
                                            <th class="text-right"><?php echo number_format((($tot1 / $bok)/12),2)?></th>
                                            <th class="text-right"><?php echo number_format((($tot1 / $vis)/12),2)?></th>
                                            <th class="text-right"><?php echo number_format((($vis / $bok)/12),0)?></th>
                                            
                                        </tr>
                                    </tfoot>
                              </table> 
                        </div>
                     </div>   
                     
                </div>
            </div> 
            
             
        </div> <!-- .content -->
<?php include_once('inc/footer.php'); ?>