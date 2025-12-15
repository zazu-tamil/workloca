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
            <form method="post" action="<?php echo site_url('employee-details-list'); ?>" id="frmsearch">
                <div class="container-fluid">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>Name</label>
                            <?php echo form_dropdown(
                                'srch_employee_id',
                                $name_opt + array('' => 'All'),
                                set_value('srch_employee_id', $srch_employee_id),
                                'class="form-control" id="srch_employee_id"'
                            ); ?>


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
                        <th>Name</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th> Status</th>
                        <th colspan="2" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($record_list as $j => $ls) { ?>
                        <tr>

                            <td class="text-center"><?php echo ($j + 1 + $sno); ?></td>
                            <td><?php echo $ls['full_name']; ?></td>

                            <td>
                                <?php echo date('d-m-Y', strtotime($ls['dob'])) ?>
                            </td>
                            <td>
                                <?php echo $ls['gender']; ?>
                            </td>
                            <td><a href="tel:<?php echo $ls['mobile_primary']; ?>"> <?php echo $ls['mobile_primary']; ?></a>
                            </td>
                            <td>
                                <?php
                                if ($ls['status'] == 'Active') {
                                    echo '<span style="color: green; font-weight: bold;">Active</span>';
                                } else {
                                    echo '<span style="color: red; font-weight: bold;">Inactive</span>';
                                }
                                ?>
                            </td>


                            <td class="text-center">
                                <a href="<?php echo site_url('employee-details-edit/' . $ls['employee_id']); ?>"
                                    class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></a>
                            </td>

                            <td class="text-center">
                                <button value="<?php echo $ls['employee_id']; ?>" class="del_record btn btn-danger btn-xs"
                                    title="Delete"><i class="fa fa-remove"></i></button>
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

</section>
<?php include_once(VIEWPATH . 'inc/footer.php'); ?>