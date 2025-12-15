<script>
  $(document).ready(function () {
    $("#photoInput").on("change", function (e) {
      const file = e.target.files[0];

      if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
          $("#previewImg").attr("src", event.target.result);
        };
        reader.readAsDataURL(file);
      } else {
        $("#previewImg").attr(
          "src",
          "<?php echo base_url('asset/images/images.jpg'); ?>"
        );
      }
    });
  });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
  rel="stylesheet"
/>
<style>
  .doc-preview-box {
    width: 100%;
    height: 160px;
    border: 2px dashed #ccc;
    background: #f8f9fa;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 10px;
    border-radius: 8px;
    text-align: center;
    color: #666;
  }
  .btn-lg {
    padding: 12px 30px !important;
  }
</style>

<style>
  .text-required {
    color: red !important;
  }
  /* Main Box */
  .box.box-info {
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    background: #ffffff;
    overflow: hidden;
  }

  /* Header */
  .box-header {
    background: linear-gradient(135deg, #4f46e5, #1e1b4b);
    padding: 12px 20px;
    color: #fff !important;
  }

  .box-header h3 {
    margin: 0;
    font-weight: 600;
    font-size: 20px;
  }

  /* Tabs */
  .nav-tabs > li > a {
    font-weight: 600;
    padding: 10px 18px;
    border-radius: 6px 6px 0 0;
  }

  .nav-tabs > li.active > a {
    background: #4f46e5 !important;
    color: #fff !important;
    border: none;
  }

  .nav-tabs > li > a:hover {
    background: #6366f1;
    color: #fff;
  }

  /* Tab Content */
  .tab-content {
    background: #fafafa;
    padding: 20px;
    border-radius: 0 0 10px 10px;
  }

  /* Form Groups */
  .form-group label {
    font-weight: 600;
    margin-bottom: 4px;
  }

  .form-control {
    border-radius: 6px;
    border: 1px solid #d1d5db;
    padding: 8px 10px;
    transition: 0.2s;
  }

  .form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 1px #4f46e5;
  }

  /* Preview Box */
  .doc-preview-box {
    width: 100%;
    height: 160px;
    border: 2px dashed #cbd5e1;
    background: #f8fafc;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 13px;
    color: #6b7280;
    text-align: center;
    transition: 0.3s ease;
    margin-top: 10px;
  }

  .doc-preview-box img {
    max-width: 100%;
    max-height: 100%;
  }

  .box-title .text-white {
    color: #fff !important;
  }

  /* Submit Button */
  .btn-primary {
    background: #4f46e5;
    border-color: #4f46e5;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 600;
  }

  .btn-primary:hover {
    background: #4338ca;
  }

  .btn-success {
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 600;
  }

  /* ------- Select2 Multiple Dropdown Styling ------- */

  /* Outer box of Select2 */
  .select2-container--default .select2-selection--multiple {
    border-radius: 6px !important;
    border: 1px solid #d1d5db !important;
    padding: 4px 8px !important;
    min-height: 35px !important;
    display: flex !important;
    align-items: center !important;
    flex-wrap: wrap !important;
  }

  /* Focus border effect */
  .select2-container--default.select2-container--focus
    .select2-selection--multiple {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 1px #4f46e5 !important;
  }

  .select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 34px !important;
    user-select: none;
    -webkit-user-select: none;
  }

  /* Selected items (tags) */
  .select2-container--default
    .select2-selection--multiple
    .select2-selection__choice {
    background-color: #4f46e5 !important;
    border: 1px solid #4338ca !important;
    color: #fff !important;
    padding: 2px 8px !important;
    margin-top: 4px !important;
    border-radius: 6px !important;
    font-size: 13px !important;
  }

  /* "X" Remove button inside tag */
  .select2-container--default
    .select2-selection--multiple
    .select2-selection__choice__remove {
    color: #fff !important;
    margin-right: 6px !important;
    font-size: 14px !important;
  }

  .select2-container--default
    .select2-selection--multiple
    .select2-selection__choice__remove:hover {
    color: #ffeaea !important;
  }

  /* Placeholder fix for empty input */
  .select2-container--default .select2-search--inline .select2-search__field {
    font-size: 14px !important;
    margin-top: 4px !important;
  }
</style>

<script>
  document.getElementById("certFile").addEventListener("change", function (e) {
    const preview = document.getElementById("certPreview");
    preview.innerHTML = ""; // clear previous previews
    const files = e.target.files;

    Array.from(files).forEach((file) => {
      const ext = file.name.split(".").pop().toLowerCase();
      const reader = new FileReader();
      const box = document.createElement("div");
      box.style =
        "position:relative; width:120px; height:120px; border:1px solid #ccc; display:flex; align-items:center; justify-content:center; overflow:hidden; border-radius:5px; margin-right:10px; margin-bottom:10px;";

      if (["jpg", "jpeg", "png", "gif"].includes(ext)) {
        reader.onload = function (e) {
          const img = document.createElement("img");
          img.src = e.target.result;
          img.style = "width:100%; height:100%; object-fit:cover;";
          box.appendChild(img);
        };
        reader.readAsDataURL(file);
      } else if (ext === "pdf") {
        const iframe = document.createElement("iframe");
        iframe.src = URL.createObjectURL(file);
        iframe.style = "width:100%; height:100%; border:none;";
        box.appendChild(iframe);
      } else {
        const unknown = document.createElement("div");
        unknown.textContent = "❓";
        unknown.style = "font-size:24px;";
        box.appendChild(unknown);
      }

      preview.appendChild(box);
    });
  });
</script>

<script>
  document.querySelectorAll(".docInput").forEach((input) => {
    input.addEventListener("change", function () {
      let previewBox = document.getElementById(this.dataset.preview);
      let file = this.files[0];

      if (!file) {
        previewBox.innerHTML = "No File Selected";
        return;
      }

      let ext = file.name.split(".").pop().toLowerCase();

      if (["jpg", "jpeg", "png", "gif"].includes(ext)) {
        let reader = new FileReader();
        reader.onload = (e) => {
          previewBox.innerHTML = `<img src="${e.target.result}">`;
        };
        reader.readAsDataURL(file);
      } else if (ext === "pdf") {
        previewBox.innerHTML = `
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" width="50">
                <div>PDF File Selected</div>
            `;
      } else {
        previewBox.innerHTML = `<b>${file.name}</b><br>Unsupported preview format`;
      }
    });
  });
</script>

<script>
  $(document).ready(function () {
    // Tab navigation variables
    let currentTab = 0;
    const tabs = $("#registrationTabs li");
    const totalTabs = tabs.length;

    // Function to show specific tab
    function showTab(n) {
      tabs.eq(n).find("a").tab("show");

      // Update button visibility
      if (n === 0) {
        $("#prevBtn").hide();
      } else {
        $("#prevBtn").show();
      }

      if (n === totalTabs - 1) {
        $("#nextBtn").hide();
        $("#submitBtn").show();
      } else {
        $("#nextBtn").show();
        $("#submitBtn").hide();
      }

      currentTab = n;
    }

    // Next button click
    $("#nextBtn").click(function () {
      if (currentTab < totalTabs - 1) {
        showTab(currentTab + 1);
      }
    });

    // Previous button click
    $("#prevBtn").click(function () {
      if (currentTab > 0) {
        showTab(currentTab - 1);
      }
    });

    // Tab click event
    tabs.find("a").click(function () {
      const index = tabs.index($(this).parent());
      showTab(index);
    });

    $(document).ready(function () {
      function loadEmployeeSkills(category_id, selected_skill_id = "") {
        $.ajax({
          url: "<?php echo site_url('employee/get_data'); ?>",
          type: "post",
          data: { tbl: "get-employee-skill-list", id: category_id },
          success: function (d) {
            $("#employee_skill_id").empty();

            $("#employee_skill_id").append(
              $("<option />").val("").text("Select Employee Skill")
            );

            $.each(d, function (key, info) {
              var opt = $("<option />");
              opt.val(info.employee_skill_id).text(info.skill_name);

              // **EDIT MODE SELECT**
              if (
                selected_skill_id &&
                selected_skill_id == info.employee_skill_id
              ) {
                opt.attr("selected", "selected");
              }

              $("#employee_skill_id").append(opt);
            });
          },
        });
      }

      // ON CHANGE CATEGORY
      $("#employee_category_id").change(function () {
        var category_id = $(this).val();
        loadEmployeeSkills(category_id);
      });

      // ON PAGE LOAD → for EDIT mode auto-load skills
      var edit_category_id = $("#employee_category_id").val();
      var edit_skill_id = $("#edit_employee_skill_id").val();

      if (edit_category_id) {
        loadEmployeeSkills(edit_category_id, edit_skill_id);
      }
    });
  });
  $(document).ready(function () {
    // Add new family row
    $("#famil_add").click(function () {
      // Remove "no records" message if it exists
      $("#no_family_record").remove();

      var newRow = `
            <tr>
                <td>
                    <input type="hidden" name="family_id[]" value="">
                    <input type="text" name="name[]" class="form-control" placeholder="Name">
                </td>
                <td>
                    <input type="date" name="family_dob[]" class="form-control">
                </td>
                <td>
                    <input type="text" name="education[]" class="form-control" placeholder="Education">
                </td>
                <td>
                    <select name="relationship[]" class="form-control">
                        <option value="">Select</option>
                        <option value="Husband">Husband</option>
                        <option value="Wife">Wife</option>
                        <option value="Son">Son</option>
                        <option value="Daughter">Daughter</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm famil_del">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
      $("#famil_append").append(newRow);
    });
  });
  $(document).on("click", ".famil_del", function () {
    let btn = $(this);
    let id = btn.val();

    //alert(btn);

    if (confirm("Are you sure you want to delete?")) {
      // If record exists in DB → update status = Delete
      if (id != "" && id != "0") {
        $.ajax({
          url: "<?php echo site_url('delete-record'); ?>",
          type: "POST",
          data: { tbl: "employee_family_info", id: id },
          success: function (response) {
            // Remove row from HTML table
            btn.closest("tr").remove();

            // If table empty → show message
            if ($("#famil_append tr").length === 0) {
              $("#famil_append").append(
                '<tr id="no_family_record"><td colspan="5" class="text-center">No family members added yet.</td></tr>'
              );
            }

            alert(response);
          },
        });
      } else {
        // Row added in frontend only — no DB record
        btn.closest("tr").remove();

        if ($("#famil_append tr").length === 0) {
          $("#famil_append").append(
            '<tr id="no_family_record"><td colspan="5" class="text-center">No family members added yet.</td></tr>'
          );
        }
      }
    }
  });
</script>

<script>
  function reinitializeSelect2(el) {
      el.select2({
          width: "100%",
          placeholder: el.data("placeholder"),
          allowClear: true,
      });
  }

  $(document).ready(function () {

      // INITIALIZE SELECT2
      $(".select2, .select2-multiple").select2({
          width: "100%",
          placeholder: function () {
              return $(this).data("placeholder");
          },
          allowClear: true,
      });

      // ====== EDIT MODE VALUES ======
      let edit_state    = "<?= $record_list['state_id'] ?? '' ?>";
      let edit_district = "<?= $record_list['district_id'] ?? '' ?>";
      let edit_area     = <?= json_encode(!empty($record_list['pref_location']) ? explode(',', $record_list['pref_location']) : []) ?>;

      if(edit_state !== "") {
          $("#state_id").val(edit_state).trigger("change");

          // Load district list for selected state
          $.ajax({
              url: "<?= site_url('employee/get_data'); ?>",
              type: "POST",
              data: { tbl: "ge_state_based_district_list", id: edit_state },
              success: function(d) {
                  let $district = $("#district_id");
                  $district.empty().append("<option></option>");
                  $.each(d, function(k,v){
                      $district.append(`<option value="${v.district_name}">${v.district_name}</option>`);
                  });
                  if(edit_district) $district.val(edit_district).trigger("change");
                  reinitializeSelect2($district);

                  // Load area list for selected district
                  if(edit_district) {
                      $.ajax({
                          url: "<?= site_url('employee/get_data'); ?>",
                          type: "POST",
                          data: { tbl: "ge_district_based_area_list", id: edit_district },
                          success: function(d2) {
                              let $area = $("#area_id");
                              $area.empty().append("<option></option>");
                              $.each(d2, function(k,v){
                                  $area.append(`<option value="${v.area_name}">${v.area_name}</option>`);
                              });
                              if(edit_area.length > 0) $area.val(edit_area).trigger("change");
                              reinitializeSelect2($area);
                          }
                      });
                  }
              }
          });
      }

      // ------------------------
      // STATE → DISTRICT
      // ------------------------
      $("#state_id").change(function () {
          let state = $(this).val();
          let $district = $("#district_id");
          let $area = $("#area_id");

          $district.empty().append("<option></option>");
          $area.empty().append("<option></option>");
          reinitializeSelect2($district);
          reinitializeSelect2($area);

          if(state) {
              $.ajax({
                  url: "<?= site_url('employee/get_data'); ?>",
                  type: "post",
                  data: { tbl: "ge_state_based_district_list", id: state },
                  success: function(d) {
                      $.each(d, function(k,v){
                          $district.append(`<option value="${v.district_name}">${v.district_name}</option>`);
                      });
                      reinitializeSelect2($district);
                  }
              });
          }
      });

      // ------------------------
      // DISTRICT → AREA
      // ------------------------
      $("#district_id").change(function () {
          let district = $(this).val();
          let $area = $("#area_id");
          $area.empty().append("<option></option>");
          reinitializeSelect2($area);

          if(district) {
              $.ajax({
                  url: "<?= site_url('employee/get_data'); ?>",
                  type: "post",
                  data: { tbl: "ge_district_based_area_list", id: district },
                  success: function(d) {
                      $.each(d, function(k,v){
                          $area.append(`<option value="${v.area_name}">${v.area_name}</option>`);
                      });
                      reinitializeSelect2($area);
                  }
              });
          }
      });

  });
</script>

<script>
  document
    .getElementById("togglePassword")
    .addEventListener("click", function () {
      var pwd = document.getElementById("user_pwd");
      var icon = document.getElementById("eyeIcon");

      if (pwd.type === "password") {
        pwd.type = "text";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      } else {
        pwd.type = "password";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      }
    });

  $("#disability_id").select2({
    placeholder: "Select Disability",
    allowClear: true,
  });
</script>

<script>
    function reinitializeSelect2(el) {
        el.select2("destroy").select2({
            width: "100%",
            placeholder: el.data("placeholder"),
            allowClear: true,
        });
    }

    $(document).ready(function () {

        // =========================
        // INIT SELECT2
        // =========================
        $(".select2, .select2-multiple").select2({
            width: "100%",
            placeholder: function () {
                return $(this).data("placeholder");
            },
            allowClear: true,
        });

        // =========================
        // EDIT MODE VALUES
        // =========================
        let edit_state    = "<?= $business_list['business_state_id'] ?? '' ?>";
        let edit_district = "<?= $business_list['business_district_id'] ?? '' ?>";
        let edit_area     = <?= json_encode(!empty($business_list['business_location']) ? explode(',', $business_list['business_location']) : []) ?>;


        if (edit_state !== "") {

      $("#business_state_id").val(edit_state).trigger("change");

      // ===== LOAD DISTRICT =====
      $.ajax({
          url: "<?= site_url('employee/get_data'); ?>",
          type: "POST",
          dataType: "json",
          data: {
              tbl: "ge_state_based_district_list",
              id: edit_state
          },
          success: function (res) {

              let $district = $("#business_district_id");
              $district.empty().append("<option></option>");

              $.each(res, function (k, v) {
                  $district.append(
                      `<option value="${v.district_name}">${v.district_name}</option>`
                  );
              });

              $district.val(edit_district).trigger("change");
              reinitializeSelect2($district);

              // ===== LOAD AREA =====
              $.ajax({
                  url: "<?= site_url('employee/get_data'); ?>",
                  type: "POST",
                  dataType: "json",
                  data: {
                      tbl: "ge_district_based_area_list",
                      id: edit_district
                  },
                  success: function (res2) {

                      let $area = $("#business_area_id");
                      $area.empty();

                      $.each(res2, function (k, v) {
                          $area.append(
                              `<option value="${v.area_name}">${v.area_name}</option>`
                          );
                      });

                      $area.val(edit_area).trigger("change");
                      reinitializeSelect2($area);
                  }
              });
          }
      });
  }


        $("#business_state_id").on("change", function () {
            let state = $(this).val();
            let $district = $("#business_district_id");
            let $area = $("#business_area_id");

            $district.empty().append("<option></option>");
            $area.empty();

            reinitializeSelect2($district);
            reinitializeSelect2($area);

            if (state) {
                $.ajax({
                    url: "<?= site_url('employee/get_data'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        tbl: "ge_state_based_district_list",
                        id: state
                    },
                    success: function (res) {
                        $.each(res, function (k, v) {
                            $district.append(
                                `<option value="${v.district_name}">${v.district_name}</option>`
                            );
                        });
                        reinitializeSelect2($district);
                    }
                });
            }
        });

        // =========================
        // DISTRICT → AREA
        // =========================
        $("#business_district_id").on("change", function () {
            let district = $(this).val();
            let $area = $("#business_area_id");

            $area.empty();
            reinitializeSelect2($area);

            if (district) {
                $.ajax({
                    url: "<?= site_url('employee/get_data'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        tbl: "ge_district_based_area_list",
                        id: district
                    },
                    success: function (res) {
                        $.each(res, function (k, v) {
                            $area.append(
                                `<option value="${v.area_name}">${v.area_name}</option>`
                            );
                        });
                        reinitializeSelect2($area);
                    }
                });
            }
        });

    });
</script>
<script>
  $(document).ready(function () {
    // ADD ROW
    $("#talent_add").click(function () {
      let newRow = `
            <div class="row talent-row align-items-start mb-3">

                <input type="hidden" name="employee_talent_id[]" value="">

                <div class="col-12 col-md-4 mb-2">
                    <select name="talent_id[]" class="form-control select2">
                        <option value="">Select Talent</option>
                        <?php foreach ($talents_opt as $key => $val) {
                            if ($key != '') { ?>
                                <option value="<?= $key ?>"><?= $val ?></option>
                        <?php }} ?>
                    </select>
                </div>

                <div class="col-12 col-md-4 mb-2">
                    <select name="volunteered_interest_id[]" class="form-control select2">
                        <option value="">Select Volunteered</option>
                        <?php foreach ($volunteered_interest_opt as $key => $val) {
                            if ($key != '') { ?>
                                <option value="<?= $key ?>"><?= $val ?></option>
                        <?php }} ?>
                    </select>
                </div>

                <div class="col-12 col-md-3 mb-2">
                    <textarea name="talent_description[]" class="form-control"
                              rows="2" placeholder="Enter description"></textarea>
                </div>

                <div class="col-12 col-md-1 text-center">
                    <button type="button" class="btn btn-danger btn-sm talent_del mt-1" value="">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

      $("#talent_append").append(newRow);
      $(".select2").select2({ width: "100%" });
    });
  });

  // DELETE
  $(document).on("click", ".talent_del", function () {
    let btn = $(this);
    let id = btn.val();

    if (confirm("Are you sure you want to delete?")) {
      if (id !== "") {
        $.ajax({
          url: "<?= site_url('delete-record'); ?>",
          type: "POST",
          data: { tbl: "employee_talent_info", id: id },
          success: function (res) {
            btn.closest(".talent-row").remove();
            alert(res);
          },
        });
      } else {
        btn.closest(".talent-row").remove();
      }
    }
  });
</script>
