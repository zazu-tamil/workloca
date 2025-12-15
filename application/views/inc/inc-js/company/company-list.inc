<script>
  jQuery(function ($) {
    $(document).on("click", ".del_record", function () {
      let id = $(this).val();
      if (confirm("Are you sure you want to delete?")) {
        $.ajax({
          url: "<?php echo site_url('company/delete_record'); ?>",
          type: "POST",
          data: { tbl: "company_info", id: id },
          success: function (d) {
            alert(d);
            location.reload();
          },
        });
      }
    });
  });
</script>
