<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
  rel="stylesheet"
/>

<script>
  $(document).ready(function () {
    // Photo Preview
    $("#photoInput").change(function (e) {
      let file = e.target.files[0];
      if (file) {
        let reader = new FileReader();
        reader.onload = (e) => $("#previewImg").attr("src", e.target.result);
        reader.readAsDataURL(file);
      }
    });

    // Document Preview
    $(".docInput").change(function () {
      let box = $("#" + this.dataset.preview);
      let file = this.files[0];
      if (!file) {
        box.html("No File");
        return;
      }

      let ext = file.name.split(".").pop().toLowerCase();
      if (["jpg", "jpeg", "png", "gif"].includes(ext)) {
        let r = new FileReader();
        r.onload = (e) =>
          box.html(
            `<img src="${e.target.result}" style="max-width:100%;max-height:150px;">`
          );
        r.readAsDataURL(file);
      } else if (ext === "pdf") {
        box.html(
          `<img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" width="60"><br>PDF File>`
        );
      } else {
        box.html(`<b>${file.name}</b>`);
      }
    });

    // Safety Cert Toggle
    $("#safetyCertSelect").change(function () {
      $("#safetyCertUploadDiv").toggle(this.value === "Yes");
    });

    // TAB WIZARD
    let currentTab = 0;
    showTab(currentTab);

    function showTab(n) {
      $(".tab-pane").removeClass("active");
      $("#tab_" + (n + 1)).addClass("active");

      $("#registrationTabs li").removeClass("active");
      $("#registrationTabs li").eq(n).addClass("active");

      $("#prevBtn").toggle(n > 0);

      // Since we have 7 tabs → index 0 to 6
      if (n === 6) {
        // correct last tab index
        $("#nextBtn").hide();
        $("#submitBtn").show();
      } else {
        $("#nextBtn").show();
        $("#submitBtn").hide();
      }
    }

    $("#nextBtn").click(() => {
      if (currentTab < 6) {
        // because last index is 6
        showTab(++currentTab);
      }
    });

    $("#prevBtn").click(() => {
      if (currentTab > 0) {
        showTab(--currentTab);
      }
    });

    $("#registrationTabs a").click(function (e) {
      e.preventDefault();
      let idx = $(this).parent().index();
      currentTab = idx;
      showTab(idx);
    });
  });
</script>

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
  function reinitializeSelect2(el) {
    el.select2({
      width: "100%",
      placeholder: el.data("placeholder"),
      allowClear: true,
    });
  }

  $(document).ready(function () {
    // INITIALIZE SELECT2
    $(".select2").select2({
      width: "100%",
      placeholder: function () {
        return $(this).data("placeholder");
      },
      allowClear: true,
    });

    $(".select2-multiple").select2({
      width: "100%",
      placeholder: function () {
        return $(this).data("placeholder");
      },
      allowClear: true,
    });

    // ------------------------
    // STATE → DISTRICT
    // ------------------------
    $("#state_id").change(function () {
      let state = $(this).val();
      let $district = $("#district_id");
      let $area = $("#area_id");

      // clear dropdowns – no "Select" text inside options
      $district.empty().append("<option></option>");
      $area.empty().append("<option></option>");

      if (state) {
        $.ajax({
          url: "<?php echo site_url('employee/get_data'); ?>",
          type: "post",
          data: { tbl: "ge_state_based_district_list", id: state },
          success: function (d) {
            $.each(d, function (k, v) {
              $district.append(
                `<option value="${v.district_name}">${v.district_name}</option>`
              );
            });
            reinitializeSelect2($district);
          },
        });
      } else {
        reinitializeSelect2($district);
        reinitializeSelect2($area);
      }
    });

    // ------------------------
    // DISTRICT → AREA
    // ------------------------
    $("#district_id").change(function () {
      let district = $(this).val();
      let $area = $("#area_id");

      $area.empty().append("<option></option>");

      if (district) {
        $.ajax({
          url: "<?php echo site_url('employee/get_data'); ?>",
          type: "post",
          data: { tbl: "ge_district_based_area_list", id: district },
          success: function (d) {
            $.each(d, function (k, v) {
              $area.append(
                `<option value="${v.area_name}">${v.area_name}</option>`
              );
            });
            reinitializeSelect2($area);
          },
        });
      } else {
        reinitializeSelect2($area);
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
</script>
<script>
  $(document).ready(function () {
    $("#mobile").on("input", function () {
      let mobile = $(this).val();

      // Allow only numbers
      mobile = mobile.replace(/\D/g, "");
      $(this).val(mobile);

      // More than 10 digits
      if (mobile.length > 10) {
        alert("Mobile number cannot be more than 10 digits");
        $(this).val(mobile.substring(0, 10));
        return;
      }

      // When exactly 10 digits typed
      if (mobile.length === 10) {
        // Must start with 6/7/8/9
        let validPattern = /^[6-9]/;

        if (!validPattern.test(mobile)) {
          alert("Invalid mobile number! It must start with 6, 7, 8, or 9.");
          $(this).val("");
          $("#user_name").val("");
          $("#user_pwd").val("");
          return;
        }

        // Auto-set with last 5 digits
        let last5 = mobile.slice(-5);
        $("#user_name").val(last5);
        $("#user_pwd").val(last5);
      }
    });

    // On blur: Less than 10 digits
    $("#mobile").on("blur", function () {
      let mobile = $(this).val();

      if (mobile.length > 0 && mobile.length < 10) {
        alert("Mobile number must be exactly 10 digits");
        $(this).val("");
        $("#user_name").val("");
        $("#user_pwd").val("");
      }
    });
  });
</script>
