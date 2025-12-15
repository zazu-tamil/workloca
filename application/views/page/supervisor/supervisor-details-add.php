<?php include_once(VIEWPATH . 'inc/header.php');

// echo '<pre>';
// print_r($terms_and_conditions);
// echo '</pre>';

?>

<section class="content-header">
    <h1><?php echo $title; ?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-users"></i> Supervisor</a></li>
        <li class="active"><?php echo $title; ?></li>
    </ol>
</section>

<!-- MAIN FORM - ONLY ONE FORM -->
<form method="post" action="<?php echo site_url('supervisor-details-add'); ?>" id="frmsupervisor"
    enctype="multipart/form-data">
    <input type="hidden" name="mode" id="" value="Add">
    <section class="content">

        <!-- PERSONAL DETAILS -->
        <div class="box box-info no-print">
            <div class="box-header with-border" style="background: linear-gradient(135deg, #4f46e5, #1e1b4b);">
                <h3 class="box-title text-white">Personal Details</h3>
            </div>
            <div class="box-body">
                <div class="container-fluid">
                    <div class="row">

                        <div class="form-group col-md-3">
                            <label>Full Name <span class="text-required">*</span></label>
                            <input type="text" name="full_name" class="form-control" required
                                placeholder="Enter full name">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Father's / Mother's Name</label>
                            <input type="text" name="parent_name" class="form-control"
                                placeholder="Enter father/mother name">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Date of Birth <span class="text-required">*</span></label>
                            <input type="date" name="dob" class="form-control" required>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Gender <span class="text-required">*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="">Select gender</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Marital Status </label>
                            <select name="marital_status" class="form-control">
                                <option value="">Select marital status</option>
                                <option>Single</option>
                                <option>Married</option>
                                <option>Other</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Mobile Number <span class="text-required">*</span></label>
                            <input type="text" name="mobile" id="mobile" class="form-control" maxlength="10" required
                                placeholder="Enter mobile number">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Alternative Mobile</label>
                            <input type="text" name="mobile_alt" class="form-control" maxlength="10"
                                placeholder="Alternate number (optional)">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Email </label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email address">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Emergency Contact</label>
                            <input type="text" name="emergency_contact" class="form-control" maxlength="10"
                                placeholder="Emergency number">
                        </div>

                        <div class="col-md-3">
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

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>District</label>
                                <select id="district_id" name="district_id" class="form-control select2"
                                    data-placeholder="Select District" required="true">
                                    <option></option>

                                </select>
                            </div>
                        </div>
                        <!-- Photo Upload -->
                        <div class="form-group col-md-3">
                            <label>Passport-size Photo (Live) </label><br>
                            <input type="file" id="photoInput" name="photo" accept="image/*" capture="camera"
                                class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Current Address <span class="text-required">*</span></label>
                            <textarea name="current_address" class="form-control" rows="2" required
                                placeholder="Current address"></textarea>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Permanent Address </label>
                            <textarea name="permanent_address" class="form-control" rows="2"
                                placeholder="Permanent address"></textarea>
                        </div>

                        <div class="form-group col-md-9">
                            <div id="imagePreview"
                                style="margin-top:10px;width:150px;height:150px;border:2px dashed #ccc;background:#f8f9fa;display:flex;justify-content:center;align-items:center;overflow:hidden;border-radius:8px;">
                                <img id="previewImg" src="<?php echo base_url('asset/images/images.jpg'); ?>"
                                    style="width:100%;height:auto;object-fit:cover;">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- TABBED REGISTRATION FORM -->
        <div class="box box-info">
            <div class="box-header with-border" style="background: linear-gradient(135deg, #4f46e5, #1e1b4b);">
                <h3 class="box-title text-white">Supervisor Registration – Full Details</h3>
            </div>

            <div class="box-body">

                <ul class="nav nav-tabs" id="registrationTabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tab_1" data-toggle="tab">1. Employee</a></li>
                    <li role="presentation"><a href="#tab_2" data-toggle="tab">2. Skills & Lang</a></li>
                    <li role="presentation"><a href="#tab_3" data-toggle="tab">3. Work Location</a></li>
                    <li role="presentation"><a href="#tab_4" data-toggle="tab">4. Documents</a></li>
                    <li role="presentation"><a href="#tab_5" data-toggle="tab">5. Bank</a></li>
                    <li role="presentation"><a href="#tab_6" data-toggle="tab">6. Portal</a></li>
                    <li role="presentation"><a href="#tab_7" data-toggle="tab">7. Declaration</a></li>
                </ul>

                <div class="tab-content" style="padding:25px 15px; background:#fafafa; border-radius:0 0 10px 10px;">

                    <!-- TAB 1 -->
                    <div role="tabpanel" class="tab-pane active" id="tab_1">
                        <h4>Employee Details</h4>
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

                    <div role="tabpanel" class="tab-pane" id="tab_2">
                        <h4>Skills & Languages</h4>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Skilled Categories</label>
                                <select id="employee_skill_id" name="employee_skill_id[]"
                                    class="form-control select2-multiple" multiple data-placeholder="Select Skills">

                                    <?php foreach ($employee_skill_opt as $key => $val) { ?>
                                        <?php if ($key != '') { ?>
                                            <option value="<?= $key ?>"><?= $val ?></option>
                                        <?php } ?>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Industry<span class="text-required">*</span></label>
                                <select id="industry_id" name="industry_id" class="form-control select2"
                                    data-placeholder="Select Industry">

                                    <?php foreach ($industry_opt as $key => $val) { ?>
                                        <?php if ($key != '') { ?>
                                            <option value="<?= $key ?>"><?= $val ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>

                            </div>

                            <div class="form-group col-md-4">
                                <label>Languages Known </label>
                                <select id="language_id" name="language_id[]" class="form-control select2-multiple"
                                    multiple data-placeholder="Select Skills">

                                    <?php foreach ($language_opt as $key => $val) { ?>
                                        <?php if ($key != '') { ?>
                                            <option value="<?= $key ?>"><?= $val ?></option>
                                        <?php } ?>
                                    <?php } ?>

                                </select>
                            </div>



                        </div>
                    </div>


                    <div role="tabpanel" class="tab-pane" id="tab_3">
                        <h4>Work Location</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Preferred Location </label>
                                    <select id="area_id" name="pref_location[]" class="form-control select2-multiple"
                                        multiple data-placeholder="Select Area" required="true">
                                        <?php foreach ($area_opt as $key => $val) { ?>
                                            <option value="<?= $key ?>"><?= $val ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4"><label>Availability<span
                                        class="text-required">*</span></label>
                                <select name="availability" class="form-control">
                                    <option value="">Select</option>
                                    <option>Immediately</option>
                                    <option>1 Week</option>
                                    <option>1 Month</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4"><label>Shift<span class="text-required">*</span></label>
                                <select name="shift_preference" class="form-control">
                                    <option value="">Select</option>
                                    <option>General</option>
                                    <option>Morning</option>
                                    <option>Night</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div role="tabpanel" class="tab-pane" id="tab_4">
                        <h4>Document Uploads</h4>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Aadhaar<span class="text-required">*</span></label>
                                <input type="file" name="upload_aadhaar" class="form-control docInput"
                                    data-preview="prev_aadhaar" accept="image/*,application/pdf">
                                <div id="prev_aadhaar" class="doc-preview-box">No File</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>PAN<span class="text-required">*</span></label>
                                <input type="file" name="upload_pan" class="form-control docInput"
                                    data-preview="prev_pan" accept="image/*,application/pdf">
                                <div id="prev_pan" class="doc-preview-box">No File</div>
                            </div>
                            <!-- <div class="form-group col-md-6">
                                <label>Experience Cert.</label>
                                <input type="file" name="upload_experience" class="form-control docInput"
                                    data-preview="prev_experience" accept="image/*,application/pdf">
                                <div id="prev_experience" class="doc-preview-box">No File</div>
                            </div> -->
                            <!-- <div class="form-group col-md-6">
                                <label>Bank Passbook<span class="text-required">*</span></label>
                                <input type="file" name="upload_passbook" class="form-control docInput"
                                    data-preview="prev_passbook" required accept="image/*,application/pdf">
                                <div id="prev_passbook" class="doc-preview-box">No File</div>
                            </div> -->
                        </div>
                    </div>


                    <div role="tabpanel" class="tab-pane" id="tab_5">
                        <h4>Banking Info</h4>
                        <div class="row">
                            <div class="form-group col-md-6"><label>Bank Name<span
                                        class="text-required">*</span></label>
                                <select name="bank_name" class="form-control">
                                    <option value="">Select Bank</option>
                                    <option>SBI</option>
                                    <option>HDFC</option>
                                    <option>ICICI</option>
                                    <option>Axis</option>
                                    <option>Others</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Holder Name<span class="text-required">*</span></label>
                                <input type="text" name="acc_holder_name" class="form-control"
                                    placeholder="Enter Account Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label>A/c Number<span class="text-required">*</span></label>
                                <input type="text" name="account_number" class="form-control"
                                    placeholder="Enter A/c Number">
                            </div>
                            <div class="form-group col-md-4">
                                <label>IFSC<span class="text-required">*</span></label>
                                <input type="text" name="ifsc_code" class="form-control" placeholder="Enter IFSC Code">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Branch<span class="text-required">*</span></label>
                                <input type="text" name="branch" class="form-control" placeholder="Enter Branch Name">
                            </div>
                        </div>
                    </div>


                    <div role="tabpanel" class="tab-pane" id="tab_6">
                        <h4>Portal Access</h4>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Username<span class="text-required">*</span></label>
                                <input type="text" name="user_name" id="user_name" class="form-control"
                                    placeholder="Enter User Name" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Password <span class="text-red">*</span></label>

                                <div class="input-group">
                                    <input type="password" name="user_pwd" id="user_pwd" class="form-control"
                                        placeholder="Enter password" required>

                                    <span class="input-group-btn">
                                        <button class="btn btn-primary_2 btn-sm" type="button" id="togglePassword">
                                            <i class="fa fa-eye-slash" id="eyeIcon"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <style>
                                .btn-primary_2 {
                                    background: #4f46e5 !important;
                                    border-color: #4f46e5 !important;
                                    padding: 7px 20px !important;
                                    border-radius: 6px !important;
                                    font-weight: 600 !important;
                                    color: #fff !important;
                                }
                            </style>

                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="tab_7">
                        <div class="row">
                            <div class="col-md-12">

                                <!-- Main Heading -->
                                <h4 class="text-center mb-4"><strong>Declarations & Consents</strong></h4>

                                <!-- Terms & Conditions -->
                                <div class="form-group">
                                    <label class="d-flex align-items-start">
                                        <input type="checkbox" name="terms_accepted" required class="mt-1 mr-2" checked  value="<?php echo $terms_and_conditions['terms_accepted']; ?>">
                                        <strong>I accept the Terms & Conditions</strong>
                                    </label>
                                    <p class="text-muted ml-4">
                                        <?php echo $terms_and_conditions['terms_accepted']; ?>
                                    </p>
                                </div>

                                <hr>

                                <!-- NDA Agreement -->
                                <div class="form-group">
                                    <label class="d-flex align-items-start">
                                        <input type="checkbox" name="nda_agreement" required class="mt-1 mr-2" checked value="<?php echo $terms_and_conditions['nda_agreement']; ?>"> 
                                        <strong>NDA Agreement</strong>
                                    </label>
                                    <p class="text-muted ml-4">
                                        <?php echo $terms_and_conditions['nda_agreement']; ?>
                                    </p>
                                </div>

                                <hr>

                                <!-- Background Check -->
                                <div class="form-group">
                                    <label class="d-flex align-items-start">
                                        <input type="checkbox" name="background_check" required class="mt-1 mr-2" checked value="<?php echo $terms_and_conditions['background_check']; ?>"> 
                                        <strong>Background Check Consent</strong>
                                    </label>
                                    <p class="text-muted ml-4">
                                        <?php echo $terms_and_conditions['background_check']; ?>
                                    </p>
                                </div>
 
                              
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