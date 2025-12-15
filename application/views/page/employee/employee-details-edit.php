<?php include_once(VIEWPATH . 'inc/header.php');

// echo '<pre>';
// print_r($business_list);
// echo '</pre>';

?>

<section class="content-header">
    <h1><?php echo $title; ?></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-cubes"></i> Ticket</a></li>
        <li class="active"><?php echo $title; ?></li>
    </ol>
</section>
<form method="post" action="" id="frmsearch" enctype="multipart/form-data">
    <input type="hidden" name="mode" id="" value="Edit">
    <input type="hidden" name="employee_id" id="" value="<?php echo $record_list['employee_id']; ?>">
    <input type="hidden" name="user_id" id="" value="<?php echo $login_details['user_id']; ?>">
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
                                placeholder="Enter full name" value="<?php echo $record_list['full_name']; ?>">
                        </div>

                        <!-- 2 Father / Guardian Name -->
                        <div class="form-group col-md-3">
                            <label>Father’s / Guardian Name</label>
                            <input type="text" name="guardian_name" class="form-control"
                                placeholder="Enter father/guardian name"
                                value="<?php echo $record_list['guardian_name']; ?>">
                        </div>

                        <!-- 3 DOB -->
                        <div class="form-group col-md-3">
                            <label>Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="dob" class="form-control" required
                                value="<?php echo $record_list['dob']; ?>">
                        </div>

                        <!-- 4 Gender -->
                        <div class="form-group col-md-3">
                            <label>Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="">Select gender</option>
                                <option value="Male" <?php echo ($record_list['gender'] == 'Male') ? 'selected' : ''; ?>>
                                    Male</option>
                                <option value="Female" <?php echo ($record_list['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo ($record_list['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <!-- 5 Marital Status -->
                        <div class="form-group col-md-3">
                            <label>Marital Status</label>
                            <select name="marital_status" class="form-control">
                                <option value="">Select marital status</option>

                                <option value="Single" <?php echo ($record_list['marital_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                                <option value="Married" <?php echo ($record_list['marital_status'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                                <option value="Divorced" <?php echo ($record_list['marital_status'] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                                <option value="Widowed" <?php echo ($record_list['marital_status'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                            </select>
                        </div>


                        <!-- 6 Primary Mobile -->
                        <div class="form-group col-md-3">
                            <label>Mobile Number (Primary) <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_primary" class="form-control" maxlength="10" required
                                placeholder="Enter primary mobile number"
                                value="<?php echo $record_list['mobile_primary']; ?>">
                        </div>

                        <!-- 7 Alternate Mobile -->
                        <div class="form-group col-md-3">
                            <label>Alternate Mobile Number</label>
                            <input type="text" name="mobile_alt" class="form-control" maxlength="10"
                                placeholder="Alternate number (optional)"
                                value="<?php echo $record_list['mobile_alt']; ?>">
                        </div>

                        <!-- 8 WhatsApp Number -->
                        <div class="form-group col-md-3">
                            <label>WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" class="form-control" maxlength="10"
                                placeholder="Enter WhatsApp number"
                                value="<?php echo $record_list['whatsapp_number']; ?>">
                        </div>

                        <!-- 9 Current Address -->
                        <div class="form-group col-md-6">
                            <label>Current Address</label>
                            <textarea name="current_address" class="form-control" rows="2"
                                placeholder="Enter current address"><?php echo nl2br($record_list['current_address']); ?></textarea>
                        </div>

                        <!-- 10 Permanent Address -->
                        <div class="form-group col-md-6">
                            <label>Permanent Address</label>
                            <textarea name="permanent_address" class="form-control" rows="2"
                                placeholder="Enter permanent address"><?php echo nl2br($record_list['permanent_address']); ?></textarea>
                        </div>

                        <!-- 11 Aadhar -->
                        <div class="form-group col-md-3">
                            <label>Aadhar Number</label>
                            <input type="text" name="aadhar_number" class="form-control" maxlength="12"
                                placeholder="Enter Aadhar number" value="<?php echo $record_list['aadhar_number']; ?>">
                        </div>

                        <!-- 12 PAN -->
                        <div class="form-group col-md-3">
                            <label>PAN Number <small>(optional)</small></label>
                            <input type="text" name="pan_number" class="form-control" maxlength="10"
                                placeholder="Enter PAN number" value="<?php echo $record_list['pan_number']; ?>">
                        </div>

                        <!-- 14 Emergency Contact Name -->
                        <div class="form-group col-md-3">
                            <label>Emergency Contact Name</label>
                            <input type="text" name="emergency_contact_name" class="form-control"
                                placeholder="Enter emergency contact name"
                                value="<?php echo $record_list['emergency_contact_name']; ?>">
                        </div>

                        <!-- 15 Emergency Contact Number -->
                        <div class="form-group col-md-3">
                            <label>Emergency Contact Number</label>
                            <input type="text" name="emergency_contact_number" class="form-control" maxlength="10"
                                placeholder="Enter emergency number"
                                value="<?php echo $record_list['emergency_contact_number']; ?>">
                        </div>

                        <!-- 13 Photo -->
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
                                <img id="previewImg" src="<?php
                                echo !empty($record_list['photo'])
                                    ? base_url($record_list['photo'])
                                    : base_url('asset/images/images.jpg');
                                ?>" alt="Preview" style="width:100%; height:100%; object-fit:cover;">
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
                    <li><a href="#tab_3" data-toggle="tab">3. Family</a></li>
                    <li><a href="#tab_4" data-toggle="tab">4. Health</a></li>
                    <li><a href="#tab_5" data-toggle="tab">5. Sports</a></li>
                    <li><a href="#tab_6" data-toggle="tab">6.Banking</a></li>
                    <li><a href="#tab_7" data-toggle="tab">7. Verification</a></li>
                    <li><a href="#tab_8" data-toggle="tab">8. Portal</a></li>
                    <li><a href="#tab_9" data-toggle="tab">9. Talent</a></li>
                    <li><a href="#tab_10" data-toggle="tab">10. Business</a></li>
                </ul>
                <div class="tab-content" style="padding-top:20px;">

                    <!-- TAB 1: WORK / EMPLOYMENT -->
                    <div class="tab-pane active" id="tab_1">
                        <h4>Work / Employment Details</h4>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Employee Category <span style="color: red;">*</span></label>
                                <?php echo form_dropdown(
                                    'employee_category_id',
                                    $employee_category_opt,
                                    $record_list['employee_category_id'],   // works for Add & Edit
                                    'class="form-control" id="employee_category_id" required'
                                ); ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Employee Skill <span style="color: red;">*</span></label>
                                <?php
                                echo form_dropdown(
                                    'employee_skill_id',
                                    array('' => 'Select Employee Skill'),
                                    $record_list['employee_skill_id'], // for edit mode selected value
                                    'class="form-control form-select" id="employee_skill_id" required'
                                );
                                ?>
                            </div>

                            <!-- Hidden field for edit mode -->
                            <input type="hidden" id="edit_employee_skill_id"
                                value="<?php echo $record_list['employee_skill_id']; ?>">



                            <div class="form-group col-md-4">
                                <label>If Others, specify</label>
                                <input type="text" name="skill_other" class="form-control"
                                    placeholder="Enter other skill" value="<?php echo $record_list['skill_other']; ?>">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Experience Level</label>
                                <?php
                                $exp_level_opt = [
                                    '' => 'Select Experience Level',
                                    'Beginner (0–1 yr)' => 'Beginner (0–1 yr)',
                                    'Intermediate (1–3 yrs)' => 'Intermediate (1–3 yrs)',
                                    'Experienced (3+ yrs)' => 'Experienced (3+ yrs)'
                                ];
                                echo form_dropdown(
                                    'exp_level',
                                    $exp_level_opt,
                                    $record_list['exp_level'],
                                    'class="form-control"'
                                );
                                ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Total Years of Experience</label>
                                <input type="number" name="total_exp" class="form-control"
                                    placeholder="Enter years of experience">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Previous Company / Contractor</label>
                                <input type="text" name="prev_company" class="form-control"
                                    placeholder="Enter previous company">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Salary</label>
                                <input type="number" name="salary" class="form-control" placeholder="Enter salary"
                                    value="<?php echo $record_list['salary']; ?>">
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
                                    placeholder="Enter other location"
                                    value="<?php echo $record_list['location_other']; ?>">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Work Identification Number</label>
                                <input type="text" name="work_id" class="form-control" readonly
                                    value="<?php echo $record_list['full_name']; ?>">
                            </div>

                            <style>
                                .cert-box {
                                    width: 180px;
                                    height: 180px;
                                    border: 2px dashed #ccc;
                                    background: #f9f9f9;
                                    padding: 5px;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    flex-direction: column;
                                }

                                .cert-img {
                                    max-width: 100%;
                                    max-height: 100%;
                                }

                                .cert-iframe {
                                    width: 100%;
                                    height: 100%;
                                    border: none;
                                }

                                .cert-icon {
                                    font-size: 40px;
                                }
                            </style>
                            <div class="form-group col-md-4">
                                <label>Past Work Certifications</label>
                                <input type="file" name="cert_file[]" id="certFile" class="form-control"
                                    accept="image/*,application/pdf" multiple>
                            </div>

                            <div class="form-group col-md-12">
                                <div id="certPreview" class="d-flex flex-wrap row" style="margin-top:10px; gap:10px;">
                                    <?php
                                    if (!empty($record_list['cert_file'])) {
                                        $files = explode(',', $record_list['cert_file']); // existing files
                                        foreach ($files as $file) {
                                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                            $filePath = base_url($file);
                                            ?>
                                            <div class="col-md-3">
                                                <div class="cert-box"
                                                    style="position:relative; width:120px; height:120px; border:1px solid #ccc; display:flex; align-items:center; justify-content:center; overflow:hidden; border-radius:5px;">
                                                    <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                                                        <img src="<?= $filePath ?>"
                                                            style="width:100%; height:100%; object-fit:cover;">
                                                    <?php } elseif ($ext == 'pdf') { ?>
                                                        <iframe src="<?= $filePath ?>"
                                                            style="width:100%; height:100%; border:none;"></iframe>
                                                    <?php } else { ?>
                                                        <div style="font-size:24px;">❓</div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="tab-pane" id="tab_2">
                        <h4>Part Time</h4>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Work Time Preference</label>

                                <?php
                                $work_time = isset($record_list['work_time']) ? $record_list['work_time'] : '';
                                ?>

                                <select name="work_time" class="form-control">
                                    <option value="">Select</option>

                                    <option value="Full Time" <?= ($work_time == 'Full Time') ? 'selected' : ''; ?>>
                                        Full Time
                                    </option>

                                    <option value="Part Time" <?= ($work_time == 'Part Time') ? 'selected' : ''; ?>>
                                        Part Time
                                    </option>

                                    <option value="Freelance" <?= ($work_time == 'Freelance') ? 'selected' : ''; ?>>
                                        Freelance
                                    </option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="form-label" for="work_time_start">Time Start</label>

                                <?php
                                $work_time_start = isset($record_list['work_time_start']) ? $record_list['work_time_start'] : '';
                                ?>

                                <input type="time" name="work_time_start" id="work_time_start" class="form-control"
                                    value="<?= $work_time_start ?>">
                            </div>


                            <div class="form-group col-md-4">
                                <label class="form-label" for="work_time_end">Time End</label>
                                <input type="time" name="work_time_end" class="form-control" id="work_time_end"
                                    value="<?php echo $record_list['work_time_end']; ?>">
                            </div>
                        </div>
                    </div>
 
                    <div class="tab-pane" id="tab_3">
                        <h4>Family Details
                            <a class="pull-right btn btn-primary btn-sm" id="famil_add">
                                <i class="fa fa-plus"></i> Add
                            </a>
                        </h4>
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
                                    <tbody id="famil_append">
                                        <?php if (!empty($family_list)) { ?>
                                            <?php foreach ($family_list as $family) { ?>
                                                <tr>
                                                    <input type="hidden" name="family_id[]" value="<?= $family['family_id'] ?>">

                                                    <td>
                                                        <!-- Hidden field to track record ID -->
                                                        <input type="hidden" name="family_id[]"
                                                            value="<?= htmlspecialchars($family['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        <input type="text" name="name[]"
                                                            value="<?= htmlspecialchars($family['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                            class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="date" name="family_dob[]"
                                                            value="<?= !empty($family['family_dob']) ? date('Y-m-d', strtotime($family['family_dob'])) : '' ?>"
                                                            class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="education[]"
                                                            value="<?= htmlspecialchars($family['education'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                            class="form-control">
                                                    </td>
                                                    <td>
                                                        <select name="relationship[]" class="form-control">
                                                            <option value="">Select</option>
                                                            <?php
                                                            $relationships = ['Husband', 'Wife', 'Son', 'Daughter'];
                                                            $selected_rel = $family['relationship'] ?? '';
                                                            foreach ($relationships as $rel) {
                                                                $selected = ($selected_rel == $rel) ? 'selected' : '';
                                                                echo '<option value="' . htmlspecialchars($rel, ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($rel, ENT_QUOTES, 'UTF-8') . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm famil_del del_record"
                                                            value="<?php echo $family['family_id']; ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr id="no_family_record">
                                                <td colspan="5" class="text-center">No family members added yet.</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: HEALTH -->
                    <div class="tab-pane" id="tab_4">
                        <h4>Health Information</h4>
                        <div class="row">
                            <?php
                            // Suppose $record['health_issues_id'] = "2,3,5"
                            $selected_issues = explode(',', $record_list['health_issues_id']);
                            ?>
                            <div class="form-group col-md-12">
                                <label>Existing Health Issues</label><br>
                                <?php foreach ($health_issues as $issue) {
                                    $checked = in_array($issue['health_issues_id'], $selected_issues) ? 'checked' : '';
                                    ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="health_issues_id[]"
                                            value="<?php echo $issue['health_issues_id']; ?>" <?php echo $checked; ?>>
                                        <?php echo $issue['health_issues_name']; ?>
                                    </label>
                                <?php } ?>
                            </div>



                            <div class="form-group col-md-4">
                                <label>If Others, specify</label>
                                <input type="text" name="health_other" class="form-control"
                                    placeholder="Enter other health issue"
                                    value="<?php echo $record_list['health_other']; ?>">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Physical Disability</label>
                                <select name="disability" class="form-control">
                                    <option value="" disabled>Select Disability Status</option>
                                    <option value="No" <?php echo ($record_list['disability'] == 'No') ? 'selected' : ''; ?>>No</option>
                                    <option value="Yes" <?php echo ($record_list['disability'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                </select>
                            </div>

                            <?php
                            // Convert CSV string into array
                            $selected_disabilities = explode(',', $record_list['disability_id']);
                            ?>

                            <div class="form-group col-md-4">
                                <label>Disability Details</label>

                                <select id="disability_id" name="disability_id[]" class="form-control select2-multiple"
                                    multiple data-placeholder="Select Disability">

                                    <?php foreach ($disabilityes_opt as $key => $val) { ?>
                                        <?php if ($key != '') { ?>
                                            <option value="<?= $key ?>" <?= in_array($key, $selected_disabilities) ? 'selected' : '' ?>>
                                                <?= $val ?>
                                            </option>
                                        <?php } ?>
                                    <?php } ?>

                                </select>
                            </div>


                            <div class="form-group col-md-4">
                                <label>Allergies</label>
                                <select name="allergy" class="form-control">
                                    <option value="" disabled>Select Allergy Status</option>
                                    <option value="No" <?php echo ($record_list['allergy'] == 'No') ? 'selected' : ''; ?>>
                                        No</option>
                                    <option value="Yes" <?php echo ($record_list['allergy'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Allergy Details</label>
                                <input type="text" name="allergy_details" class="form-control"
                                    placeholder="Enter allergy details"
                                    value="<?php echo $record_list['allergy_details']; ?>">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Smoking Habits</label>
                                <select name="smoking" class="form-control">
                                    <option value="" disabled>Select Smoking Habit</option>
                                    <option value="No" <?php echo ($record_list['smoking'] == 'No') ? 'selected' : ''; ?>>
                                        No</option>
                                    <option value="Yes" <?php echo ($record_list['smoking'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Alcohol Habits</label>
                                <select name="alcohol" class="form-control">
                                    <option value="" disabled>Select Alcohol Habit</option>
                                    <option value="No" <?php echo ($record_list['alcohol'] == 'No') ? 'selected' : ''; ?>>
                                        No</option>
                                    <option value="Yes" <?php echo ($record_list['alcohol'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Fitness Level</label>
                                <select name="fitness" class="form-control">
                                    <option value="" disabled>Select Fitness Level</option>
                                    <option value="Good" <?php echo ($record_list['fitness'] == 'Good') ? 'selected' : ''; ?>>Good</option>
                                    <option value="Moderate" <?php echo ($record_list['fitness'] == 'Moderate') ? 'selected' : ''; ?>>Moderate</option>
                                    <option value="Needs Support" <?php echo ($record_list['fitness'] == 'Needs Support') ? 'selected' : ''; ?>>Needs Support</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- TAB 4: SPORTS -->
                    <div class="tab-pane" id="tab_5">
                        <h4>Sports / Interest Details</h4>
                        <div class="row">
                            <?php
                            // Suppose $record_list['sports_list_id'] = "2,3,5"
                            $selected_sports = explode(',', $record_list['sports_list_id']);
                            ?>
                            <div class="form-group col-md-12">
                                <label>Sports Interested In</label><br>
                                <?php foreach ($sports_list as $sports) {
                                    $checked = in_array($sports['sports_list_id'], $selected_sports) ? 'checked' : ''; ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="sports_list_id[]"
                                            value="<?php echo $sports['sports_list_id']; ?>" <?php echo $checked; ?>>
                                        <?php echo $sports['sports_name']; ?>
                                    </label>
                                <?php } ?>
                            </div>


                            <div class="form-group col-md-12">
                                <label>If Others, specify</label>
                                <input type="text" name="sports_other" class="form-control"
                                    placeholder="Enter your other specify"
                                    value="<?php echo $record_list['sports_other']; ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Participation Level</label>
                                <select class="form-control" name="sport_level">
                                    <option value="Beginner" <?php echo ($record_list['sport_level'] == 'Beginner') ? 'selected' : ''; ?>>Beginner</option>
                                    <option value="Regular Player" <?php echo ($record_list['sport_level'] == 'Regular Player') ? 'selected' : ''; ?>>Regular Player</option>
                                    <option value="District Level" <?php echo ($record_list['sport_level'] == 'District Level') ? 'selected' : ''; ?>>District Level</option>
                                    <option value="State/National Level" <?php echo ($record_list['sport_level'] == 'State/National Level') ? 'selected' : ''; ?>>
                                        State/National Level</option>
                                </select>
                            </div>


                            <?php
                            // Suppose $record_list['hobbies_list_id'] = "1,3,5"
                            $selected_hobbies = explode(',', $record_list['hobbies_list_id']);
                            ?>
                            <div class="form-group col-md-12">
                                <label>Hobbies</label><br>
                                <?php foreach ($hobbies_list as $hobbies) {
                                    $checked = in_array($hobbies['hobbies_list_id'], $selected_hobbies) ? 'checked' : ''; ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="hobbies_list_id[]"
                                            value="<?php echo $hobbies['hobbies_list_id']; ?>" <?php echo $checked; ?>>
                                        <?php echo $hobbies['hobbies_name']; ?>
                                    </label>
                                <?php } ?>
                            </div>

                            <div class="form-group col-md-12">
                                <label>If Others, specify</label>
                                <input type="text" name="hobby_other" class="form-control"
                                    placeholder="Enter your other specify"
                                    value="<?php echo $record_list['hobby_other']; ?>">
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
                                    placeholder="Account Holder Name" value="<?php echo $record_list['acc_holder']; ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Account Number</label>
                                <input type="text" name="acc_number" class="form-control" placeholder="Account Number"
                                    value="<?php echo $record_list['acc_number']; ?> ">
                            </div>

                            <div class="form-group col-md-6">
                                <label>IFSC Code</label>
                                <input type="text" name="ifsc" class="form-control" placeholder="Enter your IFSC Code"
                                    value="<?php echo $record_list['ifsc']; ?> ">
                            </div>

                            <div class="form-group col-md-6">
                                <label>UPI ID</label>
                                <input type="text" name="upi" class="form-control" placeholder="Enter your UPI ID"
                                    value="<?php echo $record_list['upi']; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 6: VERIFICATION -->
                    <div class="tab-pane" id="tab_7">
                        <h4>Verification Documents</h4>
                        <div class="row">

                            <div class="row">

                                <!-- AADHAR -->
                                <div class="form-group col-md-6">
                                    <label>Aadhar Upload</label>
                                    <input type="file" name="upload_aadhar" class="form-control docInput"
                                        data-preview="prev_aadhar">
                                    <div id="prev_aadhar" class="doc-preview-box">
                                        <?php if (!empty($record_list['upload_aadhar'])) {
                                            $ext = strtolower(pathinfo($record_list['upload_aadhar'], PATHINFO_EXTENSION));
                                            $filePath = base_url('' . $record_list['upload_aadhar']);

                                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                                                <img src="<?= $filePath ?>">
                                            <?php } elseif ($ext == 'pdf') { ?>
                                                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" width="50">
                                                <div>PDF Uploaded</div>
                                            <?php } else { ?>
                                                <b><?= $record_list['upload_aadhar'] ?></b>
                                            <?php }
                                        } else {
                                            echo "No File Selected";
                                        } ?>
                                    </div>
                                </div>

                                <!-- PAN 
                                <div class="form-group col-md-6">
                                    <label>PAN Upload</label>
                                    <input type="file" name="upload_pan" class="form-control docInput"
                                        data-preview="prev_pan">
                                    <div id="prev_pan" class="doc-preview-box">
                                        <?php if (!empty($record_list['upload_pan'])) {
                                            $ext = strtolower(pathinfo($record_list['upload_pan'], PATHINFO_EXTENSION));
                                            $filePath = base_url('' . $record_list['upload_pan']);

                                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                                                <img src="<?= $filePath ?>">
                                            <?php } elseif ($ext == 'pdf') { ?>
                                                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" width="50">
                                                <div>PDF Uploaded</div>
                                            <?php } else { ?>
                                                <b><?= $record_list['upload_pan'] ?></b>
                                            <?php }
                                        } else {
                                            echo "No File Selected";
                                        } ?>
                                    </div>
                                </div>-->

                                <!-- ADDRESS -->
                                <div class="form-group col-md-6">
                                    <label>Address Proof Upload</label>
                                    <input type="file" name="upload_address" class="form-control docInput"
                                        data-preview="prev_address">
                                    <div id="prev_address" class="doc-preview-box">
                                        <?php if (!empty($record_list['upload_address'])) {
                                            $ext = strtolower(pathinfo($record_list['upload_address'], PATHINFO_EXTENSION));
                                            $filePath = base_url('' . $record_list['upload_address']);

                                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                                                <img src="<?= $filePath ?>">
                                            <?php } elseif ($ext == 'pdf') { ?>
                                                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" width="50">
                                                <div>PDF Uploaded</div>
                                            <?php } else { ?>
                                                <b><?= $record_list['upload_address'] ?></b>
                                            <?php }
                                        } else {
                                            echo "No File Selected";
                                        } ?>
                                    </div>
                                </div>

                                <!-- SKILL 
                                <div class="form-group col-md-6">
                                    <label>Skill Certificate Upload</label>
                                    <input type="file" name="upload_skill" class="form-control docInput"
                                        data-preview="prev_skill">
                                    <div id="prev_skill" class="doc-preview-box">
                                        <?php if (!empty($record_list['upload_skill'])) {
                                            $ext = strtolower(pathinfo($record_list['upload_skill'], PATHINFO_EXTENSION));
                                            $filePath = base_url('' . $record_list['upload_skill']);

                                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                                                <img src="<?= $filePath ?>">
                                            <?php } elseif ($ext == 'pdf') { ?>
                                                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" width="50">
                                                <div>PDF Uploaded</div>
                                            <?php } else { ?>
                                                <b><?= $record_list['upload_skill'] ?></b>
                                            <?php }
                                        } else {
                                            echo "No File Selected";
                                        } ?>
                                    </div>
                                </div>  --->

                            </div>


                            <!-- Social Activity -->
                            <div class="form-group col-md-6">
                                <label>Social Activity (NGO)</label>
                                <select name="ngo_interest" class="form-control" required>
                                    <option <?= ($record_list['ngo_interest'] == 'Interested') ? 'selected' : '' ?>>
                                        Interested
                                    </option>
                                    <option <?= ($record_list['ngo_interest'] == 'Not Interested') ? 'selected' : '' ?>>Not
                                        Interested</option>
                                </select>
                            </div>

                            <!-- Political Interest -->
                            <div class="form-group col-md-6">
                                <label>Political Party Meetings</label>
                                <select name="political_interest" class="form-control" required>
                                    <option <?= ($record_list['political_interest'] == 'Interested') ? 'selected' : '' ?>>
                                        Interested</option>
                                    <option <?= ($record_list['political_interest'] == 'Not Interested') ? 'selected' : '' ?>>
                                        Not Interested</option>
                                </select>
                            </div>

                        </div>

                    </div>

                    <div class="tab-pane" id="tab_8">
                        <h4>Portal Access</h4>
                        <div class="row">

                            <div class="form-group col-md-4">
                                <label>Username<span class="text-required">*</span></label>
                                <input type="text" name="user_name" class="form-control" required
                                    placeholder="Enter User Name" value="<?php echo $login_details['user_name']; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Password <span class="text-red">*</span></label>

                                <div class="input-group">
                                    <input type="password" name="user_pwd" id="user_pwd" class="form-control"
                                        placeholder="Enter password" required
                                        value="<?php echo $login_details['user_pwd']; ?>">

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
 
                   <div class="tab-pane" id="tab_9">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">
                                Talent Details
                                <button type="button" class="btn btn-primary btn-sm pull-right" id="talent_add">
                                    <i class="fa fa-plus"></i> Add
                                </button>
                            </h4>
                        </div>

                        <div id="talent_append">

                            <?php if (!empty($talent_list)) { ?>
                                <?php foreach ($talent_list as $talent) { ?>

                                    <div class="row talent-row align-items-start mb-3">

                                        <!-- Hidden ID -->
                                        <input type="hidden" name="employee_talent_id[]" value="<?= $talent['employee_talent_id']; ?>">

                                        <!-- Talent -->
                                        <div class="col-12 col-md-4 mb-2">
                                            <label class="d-md-none fw-bold">Select Talent</label>
                                            <select name="talent_id[]" class="form-control select2">
                                                <option value="">Select Talent</option>
                                                <?php foreach ($talents_opt as $key => $val) {
                                                    if ($key != '') {
                                                        $selected = ($talent['talent_id'] == $key) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?= $key ?>" <?= $selected ?>><?= $val ?></option>
                                                    <?php }
                                                } ?>
                                            </select>
                                        </div>

                                        <!-- Volunteered -->
                                        <div class="col-12 col-md-4 mb-2">
                                            <label class="d-md-none fw-bold">Select Volunteered</label>
                                            <select name="volunteered_interest_id[]" class="form-control select2">
                                                <option value="">Select Volunteered</option>
                                                <?php foreach ($volunteered_interest_opt as $key => $val) {
                                                    if ($key != '') {
                                                        $selected = ($talent['volunteered_interest_id'] == $key) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?= $key ?>" <?= $selected ?>><?= $val ?></option>
                                                    <?php }
                                                } ?>
                                            </select>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-12 col-md-3 mb-2">
                                            <label class="d-md-none fw-bold">Description</label>
                                            <textarea name="talent_description[]" class="form-control"
                                                    rows="2"><?= $talent['talent_description']; ?></textarea>
                                        </div>

                                        <!-- Action -->
                                        <div class="col-12 col-md-1 text-center">
                                            <label class="d-md-none fw-bold">Action</label><br>
                                            <button type="button"
                                                    class="btn btn-danger btn-sm talent_del mt-1"
                                                    value="<?= $talent['employee_talent_id']; ?>">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>

                                    </div>

                                <?php } ?>
                            <?php } ?>

                        </div>
                    </div>

                    <!-- TAB 9: TALENT -->
                    <div class="tab-pane" id="tab_10">
                        <div class="row">
                           <div class="col-md-4" style="margin-bottom: 15px !important">
                                <label class="d-md-none fw-bold">Select Business</label>

                                <select name="business_id" class="form-control select2">
                                    <option value="">Select business</option>

                                    <?php foreach ($business_opt as $key => $val) { 
                                        if ($key != '') { 
                                            $selected = (!empty($business_list['business_id']) && $business_list['business_id'] == $key) ? 'selected' : '';?>
                                        <option value="<?= $key ?>" <?= $selected ?>><?= $val ?></option>
                                    <?php } } ?>
                                </select>
                            </div>

                            <div class="col-md-4" style="margin-bottom: 15px !important">
                                <label class="d-md-none fw-bold">Select Experience</label>

                                <select name="exper_year" class="form-control select2">
                                    <option value="">Select Experience</option>

                                    <?php for ($i = 1; $i <= 10; $i++) { 
                                        $selected = (!empty($business_list['exper_year']) && $business_list['exper_year'] == $i) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $i ?>" <?= $selected ?>>
                                            <?= $i ?> Year<?= ($i > 1) ? 's' : '' ?>
                                        </option>
                                    <?php } ?>
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

                <!-- NAVIGATION BUTTONS -->
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
