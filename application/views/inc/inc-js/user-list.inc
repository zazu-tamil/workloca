<script>
  jQuery(function ($) {
    // ===============================
    //  EDIT RECORD - FETCH DATA
    // ===============================
    $(document).on("click", ".edit_record", function () {
      let id = $(this).val();
      $.ajax({
        url: "<?php echo site_url('get-data'); ?>",
        type: "POST",
        data: { tbl: "user_login_info", id: id },
        dataType: "json",
        success: function (d) {
          //console.log(d); // For debugging

          // Populate edit modal fields
          $("#edit_modal #user_id").val(d.user_id);
           $("#edit_modal #user_name").val(d.user_name);
          $("#edit_modal #user_pwd").val(d.user_pwd);
          $("#edit_modal input:radio[value='" + d.status + "']").prop(
            "checked",
            true
          );

          // Show modal
          $("#edit_modal").modal("show");
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", xhr.responseText);
        },
      });
    });

    // ===============================
    //  DELETE RECORD
    // ===============================
    $(document).on("click", ".del_record", function () {
      let id = $(this).val();
      if (confirm("Are you sure you want to delete?")) {
        $.ajax({
          url: "<?php echo site_url('delete-record'); ?>",
          type: "POST",
          data: { tbl: "user_login_info", id: id },
          success: function (d) {
            alert(d);
            location.reload();
          },
        });
      }
    });

    // ===============================
    //  PASSWORD TOGGLE FUNCTION (Works for both Add & Edit modals)
    // ===============================
    $(document).on("click", ".togglePassword", function () {
      const inputGroup = $(this).closest(".input-group");
      const passwordField = inputGroup.find(
        "input[type='password'], input[type='text']"
      );
      const eyeIcon = $(this).find("i");

      const isPassword = passwordField.attr("type") === "password";
      passwordField.attr("type", isPassword ? "text" : "password");

      // Toggle icons
      eyeIcon.toggleClass("fa-eye fa-eye-slash");
    });
  });
</script>
