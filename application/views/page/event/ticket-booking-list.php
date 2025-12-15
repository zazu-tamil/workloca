<?php include_once(VIEWPATH . 'inc/header.php'); ?>
<section class="content-header">
    <h1><?php echo $title; ?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-cubes"></i> Ticket</a></li>
        <li class="active"><?php echo $title; ?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-info no-print">
        <div class="box-header with-border">
            <h3 class="box-title text-white">Search Filter</h3>
        </div>
        <div class="box-body">
            <form method="post" action="<?php echo site_url('booking-list'); ?>" id="frmsearch">
                <div class="container-fluid">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>From Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control"
                                value="<?php echo set_value('from_date', $from_date); ?>">
                        </div>

                        <div class="form-group col-md-3">
                            <label>To Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control"
                                value="<?php echo set_value('to_date', $to_date); ?>">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Participant Name</label>
                            <input type="text" name="srch_name" id="srch_name" class="form-control"
                                value="<?php echo set_value('srch_name', $srch_name); ?>" placeholder="Enter name">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Email</label>
                            <input type="email" name="srch_email" id="srch_email" class="form-control"
                                value="<?php echo set_value('srch_email', $srch_email); ?>" placeholder="Enter email">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Mobile</label>
                            <input type="text" name="srch_mobile" id="srch_mobile" class="form-control"
                                value="<?php echo set_value('srch_mobile', $srch_mobile); ?>"
                                placeholder="Enter mobile">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Payment Status</label>
                            <select name="srch_payment_status" id="srch_payment_status" class="form-control">
                                <option value="">All</option>
                                <option value="Paid" <?php echo set_select('srch_payment_status', 'Paid'); ?>>Paid
                                </option>
                                <option value="Pending" <?php echo set_select('srch_payment_status', 'Pending'); ?>>
                                    Pending</option>

                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <br />
                            <button class="btn btn-success" name="btn_show" value="Show"><i class="fa fa-search"></i>
                                Show</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="box box-info">
        <div class="box-header with-border">
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="box-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Booking Date</th>
                        <th>Name</th>
                        <th>Email & Mobile</th>
                        <th>Event Date</th>
                        <th>No of Ticket</th>
                        <th>Amount</th>
                        <th>UPI Details</th>
                        <th>Payment Status</th>
                        <th colspan="3" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($record_list as $j => $ls) { ?>
                        <tr>
                            <td class="text-center"><?php echo ($j + 1 + $sno); ?></td>
                            <td>
                                <?php echo date('d-m-Y', strtotime($ls['booking_date'])) ?>
                            </td>
                            <td><?php echo $ls['a_name']; ?></td>

                            <td>
                                <a href="mailto:<?php echo $ls['a_email']; ?>">
                                    <?php echo $ls['a_email']; ?>
                                </a>
                                <br>
                                <a href="tel:<?php echo $ls['a_mobile']; ?>">
                                    <?php echo $ls['a_mobile']; ?>
                                </a>
                            </td>

                            <td> <?php echo date('d-m-Y', strtotime($ls['event_date'])) ?></td>
                            <td><?php echo $ls['no_of_tickets']; ?></td>
                            <td><?php echo $ls['ticket_amount']; ?></td>
                            <td>
                                <a href="upi://pay?pa=<?php echo $ls['ref_no']; ?>" target="_blank">
                                    <?php echo $ls['ref_no']; ?>
                                </a>
                                <br>
                                <?php echo $ls['upi_ac_name']; ?>
                            </td>


                            <td>
                                <?php
                                if ($ls['payment_status'] == 'Paid') {
                                    echo '<span style="color: green; font-weight: bold;">Paid</span>';
                                } else {
                                    echo '<span style="color: red; font-weight: bold;">Pending</span>';
                                }
                                ?>
                            </td> 
 
                            <td class="text-center">
                                <button data-toggle="modal" data-target="#edit_modal"
                                    value="<?php echo $ls['ticket_booking_id']; ?>"
                                    class="edit_record btn btn-primary btn-xs" title="Edit"><i
                                        class="fa fa-edit"></i></button>
                            </td>

                            <td class="text-center">
                                <button value="<?php echo $ls['ticket_booking_id']; ?>"
                                    class="del_record btn btn-danger btn-xs" title="Delete"><i
                                        class="fa fa-remove"></i></button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            <div class="form-group col-sm-6">
                <label>Total Records: <?php echo $total_records; ?></label>
            </div>
            <div class="form-group col-sm-6">
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="edit_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="post" action="" id="frmedit" class="form-material">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title" id="scrollmodalLabel"><strong>Edit Registration</strong></h3>
                        <input type="hidden" name="mode" value="Edit" />
                        <input type="hidden" name="ticket_booking_id" id="ticket_booking_id" value="" />
                        <input type="text" name="booking_date" id="booking_date" value="">
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <!-- Participant Name -->
                            <div class="form-group col-md-6 mb-3">
                                <label>Participant Name <span class="text-danger">*</span></label>
                                <input type="text" name="a_name" id="a_name" class="form-control" readonly>
                            </div>

                            <!-- Email -->
                            <div class="form-group col-md-6 mb-3">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="a_email" id="a_email" class="form-control" readonly>
                            </div>

                            <!-- Mobile -->
                            <div class="form-group col-md-6 mb-3">
                                <label>Mobile <span class="text-danger">*</span></label>
                                <input type="text" name="a_mobile" id="a_mobile" class="form-control" readonly>
                            </div>

                            <!-- Event Date -->
                            <div class="form-group col-md-6 mb-3">
                                <label class="fw-bold">Select Event Date <span class="text-danger">*</span></label>

                                <div class="icheck-primary">
                                    <input type="radio" id="event_22" name="event_date" value="2025-12-22" checked
                                        required>
                                    <label for="event_22">22 Dec 2025 @ 06:30 PM</label>
                                </div>

                                <div class="icheck-primary">
                                    <input type="radio" id="event_23" name="event_date" value="2025-12-23">
                                    <label for="event_23">23 Dec 2025 @ 06:30 PM</label>
                                </div>
                            </div>

                            <!-- Ticket Count -->
                            <div class="form-group col-md-6 mb-3">
                                <label><small class="text-danger">Each Ticket ₹300</small></label>
                                <select class="form-control" id="no_of_tickets" name="no_of_tickets" aria-readonly="true">
                                    <option value="">Number of Tickets</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <!-- Event Amount -->
                            <div class="form-group col-md-6 mb-3">
                                <label>Event Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="ticket_amount" id="ticket_amount"
                                    class="form-control" required readonly>
                            </div>

                            <!-- Reference Number -->
                            <div class="form-group col-md-6 mb-3">
                                <label>Reference Number / Transaction Number</label>
                                <input type="text" class="form-control" name="ref_no" id="ref_no"
                                    placeholder="Enter transaction number" required readonly>
                            </div>

                            <!-- UPI Account Holder Name -->
                            <div class="form-group col-md-6 mb-3">
                                <label>UPI Account Holder Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="upi_ac_name" id="upi_ac_name"
                                    placeholder="Enter UPI account holder name" required>
                            </div>

                            <!-- Transaction Screenshot -->
                            <div class="form-group col-md-12 mb-3">
                                <label>Screenshot of Transaction ID <span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <input type="file" class="form-control" name="trans_doc" id="trans_doc"
                                        accept=".jpg,.jpeg,.png,.pdf">

                                    <span class="input-group-btn">
                                        <a href="" class="btn btn-info" id="view_trans_doc" target="_blank">
                                            View Image
                                        </a>
                                    </span>
                                </div>

                                <small class="help-block text-muted">Upload 1 supported file. Max 2MB.</small>
                            </div>


                            <!-- Payment Status -->
                            <div class="form-group col-md-6 mb-3">
                                <label class="d-block">Payment Status</label>

                                <div class="icheck-success d-block">
                                    <input type="radio" id="payment_pending" name="payment_status" value="Pending"
                                        checked>
                                    <label for="payment_pending">Pending</label>
                                </div>

                                <div class="icheck-success d-block">
                                    <input type="radio" id="payment_paid" name="payment_status" value="Paid">
                                    <label for="payment_paid">Paid</label>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group col-md-6 mb-3">
                                <label class="d-block">Status</label>

                                <div class="icheck-primary d-block">
                                    <input type="radio" id="status_active" name="status" value="Active" checked>
                                    <label for="status_active">Active</label>
                                </div>

                                <div class="icheck-primary d-block">
                                    <input type="radio" id="status_inactive" name="status" value="InActive">
                                    <label for="status_inactive">InActive</label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <input type="submit" name="Save" value="Update" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include_once(VIEWPATH . 'inc/footer.php'); ?>