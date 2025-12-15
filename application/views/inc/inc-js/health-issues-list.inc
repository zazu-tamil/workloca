<script>
  jQuery(function ($) {
    $(document).on("click", ".edit_record", function () {
      let id = $(this).val();
      $.ajax({
        url: "<?php echo site_url('get-data'); ?>",
        type: "POST",
        data: { tbl: "health_issues_info", id: id },
        dataType: "json",
        success: function (d) {
          //console.log(d); // For debugging

          $("#edit_modal #health_issues_id").val(d.health_issues_id);
          $("#edit_modal #health_issues_name").val(d.health_issues_name);
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

    $(document).on("click", ".del_record", function () {
      let id = $(this).val();
      if (confirm("Are you sure you want to delete?")) {
        $.ajax({
          url: "<?php echo site_url('delete-record'); ?>",
          type: "POST",
          data: { tbl: "health_issues_info", id: id },
          success: function (d) {
            alert(d);
            location.reload();
          },
        });
      }
    });
  });
</script>
