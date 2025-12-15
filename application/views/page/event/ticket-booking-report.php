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

    <!-- Search Filter (Optional for Print: hide in print) -->
    <div class="box box-info no-print">
        <div class="box-header with-border">
            <h3 class="box-title text-white">Search Filter</h3>
        </div>
        <div class="box-body">
            <form method="post" action="<?php echo site_url('booking-report'); ?>" id="frmsearch">
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
                        <div class="form-group col-md-4">
                            <label>Payment Status</label>
                            <?= form_dropdown('srch_payment_status', ['' => 'All'] + $payment_status_opt, set_value('srch_payment_status', $srch_payment_status), 'id="srch_payment_status" class="form-control" style="width:100%" '); ?>
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

    <!-- Report Table -->
    <div class="box box-info ">
        <div class="box-header with-border no-print">
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" title="Collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="box-body table-responsive">
            <table id="content-table" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Booking Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Event Date</th>
                        <th>No of Ticket</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($record_list as $j => $ls) { ?>
                        <tr>
                            <td class="text-center"><?php echo ($j + 1); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($ls['booking_date'])) ?></td>
                            <td><?php echo $ls['a_name']; ?></td>
                            <td><a href="mailto:<?php echo $ls['a_email']; ?>"><?php echo $ls['a_email']; ?></a></td>
                            <td><a href="tel:<?php echo $ls['a_mobile']; ?>"><?php echo $ls['a_mobile']; ?></a></td>
                            <td><?php echo date('d-m-Y', strtotime($ls['event_date'])) ?></td>
                            <td><?php echo $ls['no_of_tickets']; ?></td>
                            <td><?php echo $ls['ticket_amount']; ?></td>
                            <td>
                                <?php
                                if ($ls['payment_status'] == 'Paid') {
                                    echo '<span style="color: green; font-weight: bold;">Paid</span>';
                                } else {
                                    echo '<span style="color: red; font-weight: bold;">Pending</span>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Print & Download Buttons (hide in print) -->
        <div class="box-footer with-border no-print">
            <div class="form-group col-sm-6 text-right">
                <button type="button" class="btn btn-success" onclick="window.print();">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
            <div class="form-group col-sm-6 text-right">
                <button type="button" data="<?php echo $heading; ?>" class="btn btn-success dl-xls">
                    <i class="fa fa-file-excel-o"></i> XLS
                </button>
            </div>
        </div>
    </div>

</section>

<?php include_once(VIEWPATH . 'inc/footer.php'); ?>

<!-- Optional: Simple print CSS -->
<style>
    @media print {
        .no-print {
            display: none !important;
        }

        table {
            font-size: 12px;
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }
    }
</style>