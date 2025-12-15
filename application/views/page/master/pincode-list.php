<?php include_once(VIEWPATH . '/inc/header.php'); ?>
<section class="content-header">
    <h1><?php echo $title; ?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li>
        <li class="active"><?php echo $title; ?></li>
    </ol>
</section>
<section class="content">

    <div class="box box-info no-print">
        <div class="box-header with-border">
            <h3 class="box-title text-white">Search Filter</h3>
        </div>
        <div class="box-body">
            <form method="post" action="<?php echo site_url('pincode-list'); ?>" id="frmsearch">
                <div class="row">
                    <!-- State -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>State</label>
                            <?php echo form_dropdown('srch_state_id', $state_opt, set_value('srch_state_id', $srch_state_id), 'id="srch_state_id" class="form-control"'); ?>
                        </div>
                    </div>

                    <!-- District -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>District</label>
                            <?php echo form_dropdown('srch_district_id', $district_opt, set_value('srch_district_id', $srch_district_id), 'id="srch_district_id" class="form-control"'); ?>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <br />
                            <button class="btn btn-success" name="btn_show" type="submit" value="Show">
                                <i class="fa fa-search"></i> Show
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>


    <div class="box box-info">
        <div class="box-header with-border">
            <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#add_modal">
                <span class="fa fa-plus-circle"></span> Add New
            </button>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body table-responsive">
            <?php if ($this->session->flashdata('alert_success')): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> Alert! : <?php echo $this->session->flashdata('alert_success'); ?>
                    </h4>
                </div>
            <?php endif; ?>
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">S.No</th>
                        <th>State</th>
                        <th>Districts</th>
                        <th>Pincode</th>
                        <th>Area</th>
                        <th>Status</th>
                        <th colspan="2" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($record_list as $j => $ls): ?>
                        <tr>
                            <td class="text-center"><?php echo ($j + 1 + $sno); ?></td>
                            <td><?php echo $ls['state_name']; ?></td>
                            <td><?php echo $ls['district_name']; ?></td>
                            <td><?php echo $ls['pincode']; ?></td>
                            <td><?php echo $ls['area_name']; ?></td>
                            <td><?php echo $ls['status']; ?></td>

                            <td class="text-center">
                                <button data-toggle="modal" data-target="#edit_modal"
                                    value="<?php echo $ls['pincode_id']; ?>" class="edit_record btn btn-primary btn-xs"
                                    title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </td>
                            <td class="text-center">
                                <button value="<?php echo $ls['pincode_id']; ?>" class="del_record btn btn-danger btn-xs"
                                    title="Delete">
                                    <i class="fa fa-remove"></i>
                                </button>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            <div class="form-group col-sm-6">
                <label>Total Records : <?php echo $total_records; ?></label>
            </div>
            <div class="form-group col-sm-6">
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form method="post" action="<?php echo site_url('pincode-list'); ?>" id="frmadd">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h3 class="modal-title" id="scrollmodalLabel"><strong>Add <?php echo $title; ?></strong></h3>
                        <input type="hidden" name="mode" value="Add" />
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>State</label>
                                <input class="form-control" type="text" name="state_name" id="state_name" value=""
                                    required="true">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Districts</label>
                                <input class="form-control" type="text" name="district_name" id="district_name" value=""
                                    required="true">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Pincode</label>
                                <input class="form-control" type="text" name="pincode" id="pincode" value=""
                                    required="true">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Status</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="status" value="Active" checked="true" /> Active
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="status" value="InActive" /> InActive
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <input type="submit" name="Save" value="Save" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="edit_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form method="post" action="<?php echo site_url('pincode-list'); ?>" id="frmedit" class="form-material">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h3 class="modal-title" id="scrollmodalLabel"><strong>Edit <?php echo $title; ?></strong></h3>
                        <input type="hidden" name="mode" value="Edit" />
                        <input type="hidden" name="pincode_id" id="pincode_id" value="" />
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>State</label>
                                <input class="form-control" type="text" name="state_name" id="state_name" value=""
                                    required="true">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Districts</label>
                                <input class="form-control" type="text" name="district_name" id="district_name" value=""
                                    required="true">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Pincode</label>
                                <input class="form-control" type="text" name="pincode" id="pincode" value=""
                                    required="true">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Status</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="status" value="Active" checked="true" /> Active
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="status" value="InActive" /> InActive
                                    </label>
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
<?php include_once(VIEWPATH . '/inc/footer.php'); ?>