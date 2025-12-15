<script>
	jQuery(function ($) {
		$(".edit_record").click(function () {
			$.ajax({
				url: "<?php echo site_url('get-data');?>",
				type: "post",
				data: { tbl: "evnt_ticket_booking_info", id: $(this).val() },
				dataType: "json",
				success: function (d) {
					$("#edit_modal #ticket_booking_id").val(d.ticket_booking_id);
					$("#edit_modal #booking_date").val(d.booking_date);
					$("#edit_modal #a_name").val(d.a_name);
					$("#edit_modal #a_mobile").val(d.a_mobile);
					$("#edit_modal #a_email").val(d.a_email);
					$("#edit_modal #event_date").val(d.event_date);
					$("#edit_modal #no_of_tickets").val(d.no_of_tickets);
					$("#edit_modal #ticket_amount").val(d.ticket_amount);
					$("#edit_modal #ref_no").val(d.ref_no);
					$("#edit_modal #upi_ac_name").val(d.upi_ac_name);
                     $("#edit_modal #view_trans_doc").attr("href", "<?php echo base_url(''); ?>" + d.trans_doc);
					$(
						'#edit_modal input:radio[name="payment_status"][value="' +
							d.payment_status +
							'"]'
					).prop("checked", true);

					$(
						'#edit_modal input:radio[name="status"][value="' + d.status + '"]'
					).prop("checked", true);
				},
				error: function (xhr, status, error) {
					console.log("Error fetching record: " + error);
					alert("Failed to load record. Please try again.");
				},
			});
		});
	});
    $(document).on("click", ".del_record", function () {

    let id = $(this).val();

    if (confirm("Are you sure you want to delete?")) {

        $.ajax({
            url: "<?php echo site_url('delete-record'); ?>",
            type: "POST",
            data: {
                tbl: "evnt_ticket_booking_info",
                id: id
            },
            success: function (response) {
                alert(response);
                location.reload();
            },
            error: function (xhr, status, error) {
                console.log("Error: " + error);
                alert("Failed to delete record. Please try again.");
            }
        });
    }
});

</script>
