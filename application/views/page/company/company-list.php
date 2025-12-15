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
            <form method="post" action="<?php echo site_url('company-list'); ?>" id="frmsearch">
                <div class="container-fluid">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>Name</label>
                            <?php echo form_dropdown(
                                'srch_company_id',
                                $company_opt + array('' => 'All'),
                                set_value('srch_company_id', $srch_company_id),
                                'class="form-control" id="srch_company_id"'
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
            <a href="<?php echo site_url('company-add') ?>" class="btn btn-success mb-1">
                <span class="fa fa-plus-circle"></span> Add New
            </a>
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
                        <th>Company Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th> Status</th>
                        <th colspan="2" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($record_list as $j => $ls) { ?>
                        <tr>

                            <td class="text-center"><?php echo ($j + 1 + $sno); ?></td>
                            <td>
                                <strong><?php echo $ls['company_name']; ?></strong><br>
                                <span class="badge label-success">GST: <?php echo $ls['gst_number']; ?></span>
                                <span class="badge label-success">PAN: <?php echo $ls['pan_number']; ?></span>
                            </td>

                            <td>
                                <a href="tel:<?php echo $ls['mobile']; ?>"><?php echo $ls['mobile'] ?></a>
                            </td>
                            <td>
                                <a href="mailto:<?php echo $ls['email']; ?>"><?php echo $ls['email'] ?></a>
                            </td>
                            <td><?php echo nl2br($ls['address']) ?></td>

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
                                <a href="<?php echo site_url('company-edit/' . $ls['company_id']); ?>"
                                    class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></a>
                            </td>

                            <td class="text-center">
                                <button value="<?php echo $ls['company_id']; ?>" class="del_record btn btn-danger btn-xs"
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