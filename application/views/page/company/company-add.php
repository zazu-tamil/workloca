<?php include_once(VIEWPATH . 'inc/header.php');

// echo '<pre>';
// print_r($terms_and_conditions);
// echo '</pre>';

?>

<section class="content-header">
    <h1><?php echo $title; ?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-users"></i>Company</a></li>
        <li class="active"><?php echo $title; ?></li>
    </ol>
</section>

<!-- MAIN FORM - ONLY ONE FORM -->
<form method="post" action="<?php echo site_url('company-add'); ?>" id="frmscompany" enctype="multipart/form-data">
    <input type="hidden" name="mode" id="" value="Add">
    <section class="content">

        <!-- COMPANY DETAILS -->
        <div class="box box-info no-print">
            <div class="box-header with-border" style="background: linear-gradient(135deg, #4f46e5, #1e1b4b);">
                <h3 class="box-title text-white">Company Details</h3>
            </div>
            <div class="box-body">
                <div class="container-fluid">
                    <div class="row">

                        <div class="form-group col-md-4">
                            <label>Company Name <span class="text-required">*</span></label>
                            <input type="text" name="company_name" class="form-control" required
                                placeholder="Enter Company name">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Mobile Number <span class="text-required">*</span></label>
                            <input type="text" name="mobile" id="mobile" class="form-control" maxlength="10" required
                                placeholder="Enter mobile number">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Alternative Mobile</label>
                            <input type="text" name="mobile_alt" class="form-control" maxlength="10"
                                placeholder="Alternate number (optional)">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Email </label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email address">
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>State</label>
                                <select id="state_id" name="state_id" class="form-control select2"
                                    data-placeholder="Select State" required="true">
                                    <option></option> <!-- placeholder -->
                                    <?php foreach ($state_opt as $key => $val) { ?>
                                        <option value="<?= $key ?>"><?= $val ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>District</label>
                                <select id="district_id" name="district_id" class="form-control select2"
                                    data-placeholder="Select District" required="true">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Area</label>
                                <select id="area_id" name="area[]" class="form-control select2-multiple" multiple
                                    data-placeholder="Select Area" required="true">
                                    <?php foreach ($area_opt as $key => $val) { ?>
                                        <option value="<?= $key ?>"><?= $val ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="logo">Company Logo</label>
                            <input type="file" name="logo" id="imageInput" class="form-control" accept="image/*">
                        </div>


                        <div class="form-group col-md-4">
                            <label>Website </label>
                            <input type="text" name="website" class="form-control" placeholder="Enter website ">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-2">
                            <div id="imagePreview" class="image-preview-box">
                                <img id="previewImg" src="<?php echo base_url('asset/images/images.jpg'); ?>"
                                    alt="Preview">
                            </div>
                        </div>
                        <div class="form-group col-md-10">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Address </label>
                                    <textarea name="address" class="form-control" rows="5"
                                        placeholder="Enter address"></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>GST No.</label>
                                    <input type="text" name="gst_number" class="form-control"
                                        placeholder="Enter GST Number">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>PAN No. </label>
                                    <input type="text" name="pan_number" class="form-control"
                                        placeholder="Enter PAN Number ">
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>


        <div class="box box-info">
            <div class="box-header with-border" style="background: linear-gradient(135deg, #4f46e5, #1e1b4b);">
                <h3 class="box-title text-white">Company Registration – Full Details</h3>
            </div>

            <div class="box-body">

                <ul class="nav nav-tabs" id="registrationTabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tab_1" data-toggle="tab">1. Employee</a></li>
                </ul>

                <div class="tab-content" style="padding:25px 15px; background:#fafafa; border-radius:0 0 10px 10px;">

                    <!-- TAB 1 -->
                    <div role="tabpanel" class="tab-pane active" id="tab_1">
                        <h4>Company Details</h4>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Experience (Years)</label>
                                <input type="number" name="experience_years" class="form-control" min="0"
                                    placeholder="Enter Your Experience">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Salary Type</label>
                                <select name="salary_type" class="form-control">
                                    <option value="" disabled selected>Select Salary Type</option>
                                    <option>Daily</option>
                                    <option>Weekly</option>
                                    <option>Monthly</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Salary <span class="text-required">*</span></label>
                                <input type="number" name="salary" class="form-control" placeholder="Enter salary"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NEXT / PREV / SUBMIT BUTTONS -->
                <div class="text-center" style="margin:40px 0 20px;">
                    <button type="button" class="btn btn-warning pull-left" id="prevBtn"><i
                            class="fa fa-arrow-left"></i>
                        Previous</button>
                    <button type="button" class="btn btn-primary pull-right" id="nextBtn">Next <i
                            class="fa fa-arrow-right"></i></button>
                    <button type="submit" class="btn btn-success pull-right" id="submitBtn" style="display:none;"><i
                            class="fa fa-check"></i> Submit Application</button>
                </div>

            </div>
        </div>
    </section>
</form>

<?php include_once(VIEWPATH . 'inc/footer.php'); ?>