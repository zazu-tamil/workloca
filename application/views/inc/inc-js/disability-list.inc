<script>
  jQuery(function ($) {
    $(document).on("click", ".edit_record", function () {
      let id = $(this).val();
      $.ajax({
        url: "<?php echo site_url('get-data'); ?>",
        type: "POST",
        data: { tbl: "disability_info", id: id },
        dataType: "json",
        success: function (d) {
          //console.log(d); // For debugging

          // Populate edit modal fields
          $("#edit_modal #disability_id").val(d.disability_id);
          $("#edit_modal #disability_name").val(d.disability_name); 
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
          data: { tbl: "disability_info", id: id },
          success: function (d) {
            alert(d);
            location.reload();
          },
        });
      }
    });
  });
</script>
