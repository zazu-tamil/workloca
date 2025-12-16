<?php include_once(VIEWPATH . 'inc/header.php');

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

?>

<section class="content-header">
    <h1><?php echo $title; ?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-cubes"></i> Ticket</a></li>
        <li class="active"><?php echo $title; ?></li>
    </ol>
</section>
<form method="post" action="<?php echo site_url('employee-details-add'); ?>" id="frmsearch"
    enctype="multipart/form-data">
    <input type="hidden" name="mode" id="" value="Add">
    <section class="content">


        <div class="box box-info no-print">
            <div class="box-header with-border">
                <h3 class="box-title text-white">Worker Details</h3>
            </div>

            <div class="box-body">

                <div class="container-fluid">
                    <div class="row">

                        <!-- 1 Full Name -->
                        <div class="form-group col-md-3">
                            <label>Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" required
                                placeholder="Enter full name">
                        </div>

                        <!-- 2 Father / Guardian Name -->
                        <div class="form-group col-md-3">
                            <label>Father’s / Guardian Name</label>
                            <input type="text" name="guardian_name" class="form-control"
                                placeholder="Enter father/guardian name">
                        </div>

                        <!-- 3 DOB -->
                        <div class="form-group col-md-3">
                            <label>Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="dob" class="form-control" required>
                        </div>

                        <!-- 4 Gender -->
                        <div class="form-group col-md-3">
                            <label>Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="">Select gender</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Others</option>
                            </select>
                        </div>

                        <!-- 5 Marital Status -->
                        <div class="form-group col-md-3">
                            <label>Marital Status</label>
                            <select name="marital_status" class="form-control">
                                <option value="">Select marital status</option>
                                <option>Single</option>
                                <option>Married</option>
                                <option>Divorced</option>
                                <option>Widowed</option>
                            </select>
                        </div>

                        <!-- 6 Primary Mobile -->
                        <div class="form-group col-md-3">
                            <label>Mobile Number (Primary) <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_primary" class="form-control " id="mobile" maxlength="10"
                                required placeholder="Enter primary mobile number">
                        </div>

                        <!-- 7 Alternate Mobile -->
                        <div class="form-group col-md-3">
                            <label>Alternate Mobile Number</label>
                            <input type="text" name="mobile_alt" class="form-control" maxlength="10"
                                placeholder="Alternate number (optional)">
                        </div>

                        <!-- 8 WhatsApp Number -->
                        <div class="form-group col-md-3">
                            <label>WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" class="form-control" maxlength="10"
                                placeholder="Enter WhatsApp number">
                        </div>

                        <!-- 9 Current Address -->
                        <div class="form-group col-md-6">
                            <label>Current Address <span class="text-danger">*</span></label>
                            <textarea name="current_address" class="form-control" rows="2"
                                placeholder="Enter current address" required></textarea>
                        </div>

                        <!-- 10 Permanent Address -->
                        <div class="form-group col-md-6">
                            <label>Permanent Address</label>
                            <textarea name="permanent_address" class="form-control" rows="2"
                                placeholder="Enter permanent address"></textarea>
                        </div>

                        <!-- 11 Aadhar -->
                        <div class="form-group col-md-3">
                            <label>Aadhar Number <span class="text-danger">*</span> </label>
                            <input type="text" name="aadhar_number" class="form-control" maxlength="12"
                                placeholder="Enter Aadhar number" required>
                        </div>

                        <!-- 12 PAN -->
                        <div class="form-group col-md-3">
                            <label>PAN Number <small>(optional)</small></label>
                            <input type="text" name="pan_number" class="form-control" maxlength="10"
                                placeholder="Enter PAN number">
                        </div>

                        <!-- 14 Emergency Contact Name -->
                        <div class="form-group col-md-3">
                            <label>Emergency Contact Name</label>
                            <input type="text" name="emergency_contact_name" class="form-control"
                                placeholder="Enter emergency contact name">
                        </div>

                        <!-- 15 Emergency Contact Number -->
                        <div class="form-group col-md-3">
                            <label>Emergency Contact Number</label>
                            <input type="text" name="emergency_contact_number" class="form-control" maxlength="10"
                                placeholder="Enter emergency number">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Select Supervisor</label>
                            <!-- <?php echo form_dropdown('supervisor_id', $supervisor_opt, '', 'class="form-control select2" id="supervisor_id" '); ?> -->
                        </div>

                        <div class="form-group col-md-3">
                            <label>Passport-size Photo (Live Capture)</label><br>
                            <input type="file" id="photoInput" name="photo" accept="image/*" capture="camera"
                                class="form-control">
                        </div>

                        <!-- Preview -->
                        <div class="form-group col-md-9 d-flex align-items-start">
                            <div id="imagePreview" style="
                                margin-top:10px;
                                width:150px;
                                height:150px;
                                border:2px dashed #ccc;
                                overflow:hidden;
                                background:#f1f1f1;
                                display:flex;
                                justify-content:center;
                                align-items:center;
                            ">
                                <img id="previewImg" src="<?php echo base_url('asset/images/images.jpg'); ?>"
                                    alt="Preview" style="width:100%; height:100%; object-fit:cover;">
                            </div>
                        </div>


                    </div>
                </div>


            </div>
        </div>

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Worker Registration – Full Details</h3>
            </div>

            <div class="box-body">


                <ul class="nav nav-tabs" id="registrationTabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">1. Workman</a></li>
                    <li><a href="#tab_2" data-toggle="tab">2. Salery</a></li>
                    <li><a href="#tab_3" data-toggle="tab">3.Family</a></li>
                    <li><a href="#tab_4" data-toggle="tab">4. Health</a></li>
                    <li><a href="#tab_5" data-toggle="tab">5. Sports</a></li>
                    <li><a href="#tab_6" data-toggle="tab">6. Banking</a></li>
                    <li><a href="#tab_7" data-toggle="tab">7. Verification</a></li>
                    <li><a href="#tab_8" data-toggle="tab">8. Portal</a></li>
                    <li><a href="#tab_9" data-toggle="tab">9. Talent</a></li>
                    <li><a href="#tab_10" data-toggle="tab">10. Business</a></li>

                </ul>
                <div class="tab-content" style="padding-top:20px;">
                    <style>
                        .btn_add {
                            padding: 4px 20px !important;
                        }
                    </style>

                    <div class="tab-pane active" id="tab_1">
                        <h4>Work / Employment Details</h4>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Employee Category <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <?php
                                    echo form_dropdown(
                                        'employee_category_id',
                                        $employee_category_opt,
                                        '',
                                        'class="form-control select2 employee-category-dd btn_add btn-sm" id="employee_category_id" required'
                                    );
                                    ?>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#add_employee_category_modal">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Employee Skill <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <?php
                                    echo form_dropdown(
                                        'employee_skill_id',
                                        ['' => 'Select Employee Skill'],
                                        '',
                                        'class="form-control select2 employee-skill-dd btn_add" id="employee_skill_id" required'
                                    );
                                    ?>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#add_employee_skill">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>If Others, specify</label>
                                <input type="text" name="skill_other" class="form-control"
                                    placeholder="Enter other skill">
                            </div>

                        </div>
                        <div class="row">

                            <div class="form-group col-md-4">
                                <label>Experience Level</label>
                                <select name="exp_level" class="form-control">
                                    <option value="" disabled selected>Select Experience Level</option>
                                    <option>Beginner (0–1 yr)</option>
                                    <option>Intermediate (1–3 yrs)</option>
                                    <option>Experienced (3+ yrs)</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Total Years of Experience</label>
                                <input type="number" name="total_exp" class="form-control"
                                    placeholder="Enter years of experience">
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
                                <label>Salary</label>
                                <input type="number" name="salary" class="form-control" placeholder="Enter salary"
                                    required>
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
                                    <select id="area_id" name="pref_location[]" class="form-control select2-multiple"
                                        multiple data-placeholder="Select Area" required="true">
                                        <?php foreach ($area_opt as $key => $val) { ?>
                                            <option value="<?= $key ?>"><?= $val ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>If Others, specify</label>
                                <input type="text" name="location_other" class="form-control"
                                    placeholder="Enter other location">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Past Work Certifications</label>
                                <input type="file" name="cert_file[]" id="certFile" class="form-control"
                                    accept="image/*,application/pdf" multiple>
                            </div>

                            <div class="form-group col-md-4">
                                <div id="certPreview" class="d-flex flex-wrap" style="margin-top:10px; gap:10px;"></div>
                            </div>


                        </div>
                    </div>
                    <div class="tab-pane" id="tab_2">
                        <h4>Part Time</h4>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Work Time Preference</label>
                                <select name="work_time" id="work_time" class="form-control">
                                    <option value="" disabled selected>Select Work Time</option>
                                    <option>Part Time</option>
                                    <option>Full Time</option>
                                    <option>Half Day</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="form-label" for="work_time_start">Time Start</label>
                                <input type="time" name="work_time_start" id="work_time_start" class="form-control">
                            </div>

                            <div class="form-group col-md-4">
                                <label class="form-label" for="work_time_end">Time End</label>
                                <input type="time" name="work_time_end" class="form-control" id="work_time_end">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: FAMILY -->
                    <div class="tab-pane" id="tab_3">
                        <h4>Family Details <a class="pull-right btn btn-primary btn-sm" id="famil_add"><i
                                    class="fa fa-plus"></i> Add</a></h4>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>DOB</th>
                                            <th>Education</th>
                                            <th>Relationship</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="famil_append"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                    <!-- TAB 3: HEALTH -->
                    <div class="tab-pane" id="tab_4">
                        <h4>Health Information</h4>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <h3>
                                    Existing Health Issues
                                    <button type="button" class="btn btn-primary pull-right" data-toggle="modal"
                                        data-target="#health_issues_modal">
                                        <i class="fa fa-plus"></i> &nbsp; Add Health Issues
                                    </button>
                                </h3>
                                <br>

                                <div id="healthIssuesWrapper">
                                    <?php foreach ($health_issues as $issue) { ?>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="health_issues_id[]"
                                                value="<?php echo $issue['health_issues_id']; ?>">
                                            <?php echo $issue['health_issues_name']; ?>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>


                            <div class="form-group col-md-4">
                                <label>If Others, specify</label>
                                <input type="text" name="health_other" class="form-control"
                                    placeholder="Enter other health issue">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Physical Disability</label>
                                <select name="disability" class="form-control">
                                    <option value="" disabled selected>Select Disability Status</option>
                                    <option>No</option>
                                    <option>Yes</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Disability Details</label>
                                <select id="disability_id" name="disability_id[]" class="form-control select2-multiple"
                                    multiple data-placeholder="Select Disability">

                                    <?php foreach ($disabilityes_opt as $key => $val) { ?>
                                        <?php if ($key != '') { ?>
                                            <option value="<?= $key ?>"><?= $val ?></option>
                                        <?php } ?>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Allergies</label>
                                <select name="allergy" class="form-control">
                                    <option value="" disabled selected>Select Allergy Status</option>
                                    <option>No</option>
                                    <option>Yes</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Allergy Details</label>
                                <input type="text" name="allergy_details" class="form-control"
                                    placeholder="Enter allergy details">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Smoking Habits</label>
                                <select name="smoking" class="form-control">
                                    <option value="" disabled selected>Select Smoking Habit</option>
                                    <option>No</option>
                                    <option>Yes</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Alcohol Habits</label>
                                <select name="alcohol" class="form-control">
                                    <option value="" disabled selected>Select Alcohol Habit</option>
                                    <option>No</option>
                                    <option>Yes</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Fitness Level</label>
                                <select name="fitness" class="form-control">
                                    <option value="" disabled selected>Select Fitness Level</option>
                                    <option>Good</option>
                                    <option>Moderate</option>
                                    <option>Needs Support</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: SPORTS -->
                    <div class="tab-pane" id="tab_5">
                        <h4>Sports / Interest Details</h4>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <h3>
                                    Sports Interested In
                                    <button type="button" class="btn btn-xs btn-primary pull-right" data-toggle="modal"
                                        data-target="#add_sports_modal">
                                        <i class="fa fa-plus"></i> &nbsp; Sports Interested
                                    </button>
                                </h3>
                                <br>

                                <div id="sportsWrapper">
                                    <?php foreach ($sports_list as $sports) { ?>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="sports_list_id[]"
                                                value="<?php echo $sports['sports_list_id']; ?>">
                                            <?php echo $sports['sports_name']; ?>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>


                            <div class="form-group col-md-12">
                                <label>If Others, specify</label>
                                <input type="text" name="sports_other" class="form-control"
                                    placeholder="Enter your other specify">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Participation Level</label>
                                <select class="form-control" name="sport_level">
                                    <option>Beginner</option>
                                    <option>Regular Player</option>
                                    <option>District Level</option>
                                    <option>State/National Level</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <h3>
                                    Hobbies
                                    <button type="button" class="btn btn-xs btn-primary pull-right" data-toggle="modal"
                                        data-target="#add_hobbies_modal">
                                        <i class="fa fa-plus"></i> &nbsp; Add Hobbies
                                    </button>
                                </h3>
                                <br>

                                <div id="hobbiesWrapper">
                                    <?php foreach ($hobbies_list as $hobby) { ?>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="hobbies_list_id[]"
                                                value="<?php echo $hobby['hobbies_list_id']; ?>">
                                            <?php echo $hobby['hobbies_name']; ?>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>



                            <div class="form-group col-md-12">
                                <label>If Others, specify</label>
                                <input type="text" name="hobby_other" class="form-control"
                                    placeholder="Enter your other specify">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 5: BANKING -->
                    <div class="tab-pane" id="tab_6">
                        <h4>Banking / Wallet Details</h4>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Account Holder Name</label>
                                <input type="text" name="acc_holder" class="form-control"
                                    placeholder="Account Holder Name">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Account Number</label>
                                <input type="text" name="acc_number" class="form-control" placeholder="Account Number">
                            </div>

                            <div class="form-group col-md-6">
                                <label>IFSC Code</label>
                                <input type="text" name="ifsc" class="form-control" placeholder="Enter your IFSC Code">
                            </div>

                            <div class="form-group col-md-6">
                                <label>UPI ID</label>
                                <input type="text" name="upi" class="form-control" placeholder="Enter your UPI ID">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 6: VERIFICATION -->
                    <div class="tab-pane" id="tab_7">
                        <h4>Verification Documents</h4>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Aadhar Upload</label>
                                <input type="file" name="upload_aadhar" class="form-control docInput"
                                    data-preview="prev_aadhar">
                                <div id="prev_aadhar" class="doc-preview-box">No File Selected</div>
                            </div>

                            <!-- <div class="form-group col-md-6">
                                <label>PAN Upload</label>
                                <input type="file" name="upload_pan" class="form-control docInput"
                                    data-preview="prev_pan">
                                <div id="prev_pan" class="doc-preview-box">No File Selected</div>
                            </div> -->

                            <div class="form-group col-md-6">
                                <label>Address Proof Upload</label>
                                <input type="file" name="upload_address" class="form-control docInput"
                                    data-preview="prev_address">
                                <div id="prev_address" class="doc-preview-box">No File Selected</div>
                            </div>

                            <!-- <div class="form-group col-md-6">
                                <label>Skill Certificate Upload</label>
                                <input type="file" name="upload_skill" class="form-control docInput"
                                    data-preview="prev_skill">
                                <div id="prev_skill" class="doc-preview-box">No File Selected</div>
                            </div> -->

                            <div class="form-group col-md-6">
                                <label>Social Activity (NGO)</label>
                                <select name="ngo_interest" class="form-control" required>
                                    <option>Interested</option>
                                    <option>Not Interested</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Political Party Meetings</label>
                                <select name="political_interest" class="form-control" required>
                                    <option>Interested</option>
                                    <option>Not Interested</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_8">
                        <h4>Portal Access</h4>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Username<span class="text-required">*</span></label>
                                <input type="text" name="user_name" id="user_name" class="form-control" required
                                    placeholder="Enter User Name">
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


                    <!-- TAB 9: TALENT -->
                    <div class="tab-pane" id="tab_9">

                        <div class="">
                            <h3 class="box-title">
                                Talent Details
                                <div class="  pull-right">
                                    <button type="button" class="btn btn-primary btn-sm" id="talent_add">
                                        <i class="fa fa-plus"></i> Add row
                                    </button>

                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#add_talent_modal">
                                        <i class="fa fa-plus"></i> Add Talent
                                    </button>
                                </div>
                            </h3>


                        </div>

                        <div id="talent_append"></div>

                    </div>


                    <!-- TAB 9: TALENT -->
                    <div class="tab-pane" id="tab_10">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Select Business <span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <?php
                                    echo form_dropdown(
                                        'business_id',
                                        $business_opt,
                                        '',
                                        'class="form-control select2" id="business_id" required'
                                    );
                                    ?>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#add_business_modal">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>


                            <div class="col-md-4" style="margin-bottom: 15px !important">
                                <label class="d-md-none fw-bold">Select Experience</label>
                                <select name="exper_year" class="form-control select2">
                                    <option value="" selected>Select business</option>
                                    <option value="1">1 Years</option>
                                    <option value="2">2 Years</option>
                                    <option value="3">3 Years</option>
                                    <option value="4">4 Years</option>
                                    <option value="5">5 Years</option>
                                    <option value="6">6 Years</option>
                                    <option value="7">7 Years</option>
                                    <option value="8">8 Years</option>
                                    <option value="9">9 Years</option>
                                    <option value="10">10 Years</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>State</label>
                                    <select id="business_state_id" name="business_state_id" class="form-control select2"
                                        data-placeholder="Select State">
                                        <option></option>
                                        <?php foreach ($state_opt as $key => $val) { ?>
                                            <option value="<?= $key ?>"><?= $val ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>District</label>
                                    <select id="business_district_id" name="business_district_id"
                                        class="form-control select2" data-placeholder="Select District">
                                        <option></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Area</label>
                                    <select id="business_area_id" name="business_location[]"
                                        class="form-control select2-multiple" multiple data-placeholder="Select Area">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="tab-navigation clearfix">
                    <div class="pull-left">
                        <button type="button" class="btn btn-primary" id="prevBtn" style="display: none;">
                            <i class="fa fa-arrow-left"></i> Previous
                        </button>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary" id="nextBtn">
                            Next <i class="fa fa-arrow-right"></i>
                        </button>
                        <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                            <i class="fa fa-check"></i> Submit
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </section>
</form>


<?php include_once(VIEWPATH . 'inc/footer.php'); ?>
<div class="modal fade" id="add_business_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">

    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <form method="post" action="" id="frmadd_business_modal" enctype="multipart/form-data">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title">Add Business</h3>
                    <input type="hidden" name="mode" value="Add Business">
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Business Name <span class="text-danger">*</span></label>
                        <input type="text" name="business_name" id="business_name" class="form-control"
                            placeholder="Enter Business Name">
                        <small class="text-danger d-none" id="business_error"></small>
                    </div>

                    <div class="form-group">
                        <label>Status</label><br>
                        <label>
                            <input type="radio" name="status" value="Active" checked> Active
                        </label>&nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="radio" name="status" value="InActive"> InActive
                        </label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <input type="submit" name="btn_add_business_modal" value="Save" class="btn btn-primary">
                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="add_sports_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">

    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <form method="post" action="" id="frmadd_sports_modal" enctype="multipart/form-data">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title">Add Sports</h3>
                    <input type="hidden" name="mode" value="Add Sports">
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Sports Name <span class="text-danger">*</span></label>
                        <input type="text" name="sports_name" id="sports_name" class="form-control"
                            placeholder="Enter Business Name">
                        <small class="text-danger d-none" id="sports_error"></small>
                    </div>

                    <div class="form-group">
                        <label>Status</label><br>
                        <label>
                            <input type="radio" name="status" value="Active" checked> Active
                        </label>&nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="radio" name="status" value="InActive"> InActive
                        </label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <input type="button" name="btn_add_sports_modal" value="Save" class="btn btn-primary">
                </div>

            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="add_hobbies_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <form id="frmadd_hobbies_modal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Add Hobbies</h3>
                    <input type="hidden" name="mode" value="Add Hobbies">
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Hobbies Name <span class="text-danger">*</span></label>
                        <input type="text" name="hobbies_name" id="hobbies_name" class="form-control"
                            placeholder="Enter Hobbies">
                        <small class="text-danger d-none" id="hobbies_error"></small>
                    </div>

                    <div class="form-group">
                        <label>Status</label><br>
                        <label>
                            <input type="radio" name="status" value="Active" checked> Active
                        </label>&nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="radio" name="status" value="InActive"> InActive
                        </label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <input type="button" name="btn_add_hobbies_modal" value="Save" class="btn btn-primary">
                </div>

            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="health_issues_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <form id="frmadd_health_issues_modal">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Add Health Issue</h3>
                    <input type="hidden" name="mode" value="Add Health Issues">
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Health Issue <span class="text-danger">*</span></label>
                        <input type="text" name="health_issues_name" id="health_issues_name" class="form-control"
                            placeholder="Enter health issue">
                        <small class="text-danger d-none" id="health_issues_error"></small>
                    </div>

                    <div class="form-group">
                        <label>Status</label><br>
                        <label>
                            <input type="radio" name="status" value="Active" checked> Active
                        </label>&nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="radio" name="status" value="InActive"> InActive
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <input type="button" name="btn_add_health_issues_modal" value="Save" class="btn btn-primary">
                </div>

            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="add_talent_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="frmadd_add_talent_modal">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Add Talent</h3>
                    <input type="hidden" name="mode" value="Add Talents">
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Talent Name <span class="text-danger">*</span></label>
                        <input type="text" name="talent_name" id="talent_name" class="form-control"
                            placeholder="Enter your talent">
                        <small class="text-danger d-none" id="talent_error"></small>
                    </div>
                    <div class="form-group">
                        <label>Status</label><br>
                        <label>
                            <input type="radio" name="status" value="Active" checked> Active
                        </label>&nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="radio" name="status" value="InActive"> InActive
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn_add_talent_modal">
                        Save
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="add_employee_category_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="frmadd_add_employee_category_modal">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Add Category</h3>
                    <input type="hidden" name="mode" value="Add Category">
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="category_name" id="category_name" class="form-control"
                            placeholder="Enter your category">
                        <small class="text-danger d-none" id="category_error"></small>
                    </div>
                    <div class="form-group">
                        <label>Status</label><br>
                        <label>
                            <input type="radio" name="status" value="Active" checked> Active
                        </label>&nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="radio" name="status" value="InActive"> InActive
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn_add_employee_category_modal">
                        Save
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="add_employee_skill">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="frmadd_add_employee_skill">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Add Skill</h3>
                    <input type="hidden" name="mode" value="Add Skill">
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Category <span class="text-danger">*</span></label>
                        <?php
                        echo form_dropdown(
                            'employee_category_id',
                            $employee_category_opt,
                            '',
                            'class="form-control select2" id="employee_category_id_skill" required'
                        );
                        ?>
                    </div>

                    <!-- Skill Name -->
                    <div class="form-group">
                        <label>Skill Name <span class="text-danger">*</span></label>
                        <input type="text" id="skill_name" class="form-control" placeholder="Enter skill name">
                        <small class="text-danger d-none" id="skill_error"></small>
                    </div>
                    <div class="form-group">
                        <label>Status</label><br>
                        <label>
                            <input type="radio" name="status" value="Active" checked> Active
                        </label>&nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="radio" name="status" value="InActive"> InActive
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn_add_employee_skill">
                        Save
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        addTalentRow();

        $("#talent_add").on("click", function () {
            addTalentRow();
        });

        $(document).on("click", ".talent_del", function () {
            $(this).closest(".talent-row").remove();
        });

        function addTalentRow() {
            let row = ` 
                <div class="row talent-row align-items-start mb-3">

                    <!-- Talent -->
                    <div class="col-12 col-md-4 mb-2" style="margin-bottom: 15px !important">
                        <label class="d-md-none fw-bold">Select Talent</label>
                        <select name="talent_id[]" class="form-control select2">
                            <option value="">Select Talent</option>
                            <?php foreach ($talents_opt as $key => $val) {
                                if ($key != '') { ?>
                                    <option value="<?= $key ?>"><?= $val ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>

                    <!-- Volunteered -->
                    <div class="col-12 col-md-4 mb-2" style="margin-bottom: 15px !important">
                        <label class="d-md-none fw-bold">Select Volunteered</label>
                        <select name="volunteered_interest_id[]" class="form-control select2">
                            <option value="">Select Volunteered</option>
                            <?php foreach ($volunteered_interest_opt as $key => $val) {
                                if ($key != '') { ?>
                                    <option value="<?= $key ?>"><?= $val ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="col-12 col-md-3 mb-2" style="margin-bottom: 15px !important">
                        <label class="d-md-none fw-bold">Description</label>
                        <textarea name="talent_description[]" 
                            class="form-control" rows="2"
                            placeholder="Enter description"></textarea>
                    </div>

                    <!-- Action -->
                    <div class="col-12 col-md-1 text-center" style="margin-bottom: 15px !important">
                        <label class="d-md-none fw-bold">Action</label><br>
                        <button type="button" class="btn btn-danger btn-sm talent_del mt-1">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>

                </div>
            `;

            $("#talent_append").append(row);

            // Initialize Select2 ONLY for last added row
            $("#talent_append .talent-row:last .select2").select2({
                width: '100%'
            });
        }
    });
</script>

<script>
    $(document).on('click', 'input[name="btn_add_sports_modal"]', function (e) {
        e.preventDefault();

        var sports_name = $('#sports_name').val().trim();

        if (sports_name === '') {
            $('#sports_error')
                .text('Sports name is required')
                .removeClass('d-none');
            return;
        } else {
            $('#sports_error').addClass('d-none');
        }

        var formData = $('#frmadd_sports_modal').serialize();

        $.ajax({
            url: "<?php echo site_url('employee/ajax_add_master_inline'); ?>",
            type: "POST",
            data: formData,
            dataType: "json",
            beforeSend: function () {
                $('input[name="btn_add_sports_modal"]')
                    .val('Saving...')
                    .prop('disabled', true);
            },
            success: function (response) {

                if (response.status === 'success') {

                    // Create checkbox dynamically
                    var checkboxHtml = `
                    <label class="checkbox-inline">
                        <input type="checkbox"
                               name="sports_list_id[]"
                               value="${response.id}"
                               checked>
                        ${response.name}
                    </label>
                `;

                    // Append to sports wrapper
                    $('#sportsWrapper').append(checkboxHtml);

                    // Reset form
                    $('#frmadd_sports_modal')[0].reset();

                    // Close modal
                    $('#add_sports_modal').modal('hide');

                    alert(response.message);
                } else {
                    alert('Failed to add sport');
                }
            },
            error: function () {
                alert('AJAX Error');
            },
            complete: function () {
                $('input[name="btn_add_sports_modal"]')
                    .val('Save')
                    .prop('disabled', false);
            }
        });
    });
    $(document).on('click', 'input[name="btn_add_hobbies_modal"]', function (e) {
        e.preventDefault();

        var hobbies_name = $('#hobbies_name').val().trim();

        if (hobbies_name === '') {
            $('#hobbies_error')
                .text('Hobbies name is required')
                .removeClass('d-none');
            return;
        } else {
            $('#hobbies_error').addClass('d-none');
        }

        var formData = $('#frmadd_hobbies_modal').serialize();

        $.ajax({
            url: "<?php echo site_url('employee/ajax_add_master_inline'); ?>",
            type: "POST",
            data: formData,
            dataType: "json",
            beforeSend: function () {
                $('input[name="btn_add_hobbies_modal"]')
                    .val('Saving...')
                    .prop('disabled', true);
            },
            success: function (response) {

                if (response.status === 'success') {

                    // Create checkbox dynamically
                    var checkboxHtml = `
                    <label class="checkbox-inline">
                        <input type="checkbox"
                               name="hobbies_list_id[]"
                               value="${response.id}"
                               checked>
                        ${response.name}
                    </label>
                `;

                    // Append to hobbies wrapper
                    $('#hobbiesWrapper').append(checkboxHtml);

                    // Reset form
                    $('#frmadd_hobbies_modal')[0].reset();

                    // Close modal
                    $('#add_hobbies_modal').modal('hide');

                    alert(response.message);
                } else {
                    alert('Failed to add hobby');
                }
            },
            error: function () {
                alert('AJAX Error');
            },
            complete: function () {
                $('input[name="btn_add_hobbies_modal"]')
                    .val('Save')
                    .prop('disabled', false);
            }
        });
    });

    $(document).on('click', 'input[name="btn_add_business_modal"]', function (e) {
        e.preventDefault();

        var formData = $('#frmadd_business_modal').serialize();

        $.ajax({
            url: "<?php echo site_url('employee/ajax_add_master_inline'); ?>",
            type: "POST",
            data: formData,
            dataType: "json",
            beforeSend: function () {
                $('input[name="btn_add_business_modal"]')
                    .val('Saving...')
                    .prop('disabled', true);
            },
            success: function (response) {

                if (response.status === 'success') {

                    // Create new option
                    var newOption = new Option(
                        response.name,
                        response.id,
                        true,
                        true
                    );

                    // Append & select
                    $('#business_id')
                        .append(newOption)
                        .trigger('change');

                    // Reset form
                    $('#frmadd_business_modal')[0].reset();

                    // Close modal
                    $('#add_business_modal').modal('hide');

                    alert(response.message);
                } else {
                    alert('Failed to add business');
                }
            },
            error: function (xhr) {
                alert('AJAX Error');
            },
            complete: function () {
                $('input[name="btn_add_business_modal"]')
                    .val('Save')
                    .prop('disabled', false);
            }
        });
    });

    $(document).on('click', 'input[name="btn_add_health_issues_modal"]', function (e) {
        e.preventDefault();

        var issue = $('#health_issues_name').val().trim();
        if (issue === '') {
            $('#health_issues_error').text('Required').removeClass('d-none');
            return;
        }

        $.ajax({
            url: "<?php echo site_url('employee/ajax_add_master_inline'); ?>",
            type: "POST",
            data: $('#frmadd_health_issues_modal').serialize(),
            dataType: "json",
            success: function (res) {
                console.log(res);

                if (res.status === 'success') {
                    $('#healthIssuesWrapper').append(
                        `<label class="checkbox-inline">
                        <input type="checkbox" name="health_issues_id[]" value="${res.id}" checked>
                        ${res.name}
                    </label>`
                    );

                    $('#frmadd_health_issues_modal')[0].reset();
                    $('#health_issues_modal').modal('hide');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('AJAX Error – check console');
            }
        });
    });

</script>
<script>
    $(document).on('click', '#btn_add_talent_modal', function (e) {
        e.preventDefault();

        var talent_name = $('#talent_name').val().trim();
        var $btn = $('#btn_add_talent_modal');

        if (talent_name === '') {
            $('#talent_error').text('Talent name is required').removeClass('d-none');
            return;
        } else {
            $('#talent_error').addClass('d-none');
        }

        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

        $.ajax({
            url: "<?php echo site_url('employee/ajax_add_master_inline'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                mode: 'Add Talents',
                talent_name: talent_name,
                [csrfName]: csrfHash
            },

            beforeSend: function () {
                $btn.text('Saving...').prop('disabled', true);
            },

            success: function (response) {
                if (response.status === 'success') {

                    // 🔥 Append to TALENT dropdowns
                    $('select[name="talent_id[]"]').each(function () {
                        $(this).append(
                            new Option(response.name, response.id, false, false)
                        );
                    });

                    // 🔥 Append SAME option to VOLUNTEERED dropdowns
                    $('select[name="volunteered_interest_id[]"]').each(function () {
                        $(this).append(
                            new Option(response.name, response.id, false, false)
                        );
                    });

                    // 🔥 Auto-select SAME value in LAST ROW
                    $('select[name="talent_id[]"]').last()
                        .val(response.id).trigger('change');

                    $('select[name="volunteered_interest_id[]"]').last()
                        .val(response.id).trigger('change');

                    $('#frmadd_add_talent_modal')[0].reset();
                    $('#add_talent_modal').modal('hide');

                    alert(response.message);
                }
            },

            error: function (xhr) {
                console.log(xhr.responseText);
                alert('AJAX Error - see console');
            },

            complete: function () {
                $btn.text('Save').prop('disabled', false);
            }
        });
    });

</script>
<script>
    $(document).on('click', '#btn_add_employee_category_modal', function (e) {
        e.preventDefault();

        var category_name = $('#category_name').val().trim();
        var $btn = $('#btn_add_employee_category_modal');

        if (category_name === '') {
            $('#category_error').text('Category name is required').removeClass('d-none');
            return;
        } else {
            $('#category_error').addClass('d-none');
        }

        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

        $.ajax({
            url: "<?php echo site_url('employee/ajax_add_master_inline'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                mode: 'Add Category',            // ✅ FIXED
                category_name: category_name,    // ✅ FIXED
                [csrfName]: csrfHash
            },

            beforeSend: function () {
                $btn.text('Saving...').prop('disabled', true);
            },

            success: function (response) {
                if (response.status === 'success') {

                    var $categorySelect = $('#employee_category_id');

                    var $modal_categorySelect = $('#frmadd_add_employee_skill #employee_category_id_skill');

                    // 🔥 Append new option
                    $categorySelect.append(
                        new Option(response.name, response.id, true, true)
                    );

                    // 🔥 Refresh Select2
                    $categorySelect.trigger('change');

                    // 🔥 Append new option
                    $modal_categorySelect.append(
                        new Option(response.name, response.id, true, true)
                    );

                    // 🔥 Refresh Select2
                    $modal_categorySelect.trigger('change');

                    // Reset + Close modal
                    $('#frmadd_add_employee_category_modal')[0].reset();
                    $('#add_employee_category_modal').modal('hide');

                    alert(response.message);
                }
            },

            error: function (xhr) {
                console.log(xhr.responseText);
                alert('AJAX Error - see console');
            },

            complete: function () {
                $btn.text('Save').prop('disabled', false);
            }
        });
    });
</script>

<script>
$(document).on('click', '#btn_add_employee_skill', function (e) {
    e.preventDefault();

    var skill_name  = $('#skill_name').val().trim();
    var category_id = $('#employee_category_id').val();
    var $btn = $('#btn_add_employee_skill');

    if (skill_name === '') {
        $('#skill_error').text('Skill name is required').removeClass('d-none');
        return;
    } else {
        $('#skill_error').addClass('d-none');
    }

    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

    $.ajax({
        url: "<?php echo site_url('employee/ajax_add_master_inline'); ?>",
        type: "POST",
        dataType: "json",
        data: {
            mode: 'Add Skill',
            skill_name: skill_name,
            employee_category_id: category_id,
            [csrfName]: csrfHash
        },

        beforeSend: function () {
            $btn.text('Saving...').prop('disabled', true);
        },

        success: function (response) {
            if (response.status === 'success') {

                // 🔥 Append to SKILL dropdown (CHANGE ID AS PER YOUR FORM)
                var $skillSelect = $('#employee_skill_id');

                if ($skillSelect.length) {
                    $skillSelect.append(
                        new Option(response.name, response.id, true, true)
                    ).trigger('change');
                }

                $('#frmadd_add_employee_skill')[0].reset();
                $('#add_employee_skill').modal('hide');

                alert(response.message);
            }
        },

        error: function (xhr) {
            console.log(xhr.responseText);
            alert('AJAX Error - see console');
        },

        complete: function () {
            $btn.text('Save').prop('disabled', false);
        }
    });
});
</script>
