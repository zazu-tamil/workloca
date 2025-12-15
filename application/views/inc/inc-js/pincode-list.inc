<script>
	jQuery(function ($) {
		// Edit Record
		$(".edit_record").click(function () {
			var recordId = $(this).val();
			$.ajax({
				url: "<?php echo site_url('get-data'); ?>",
				type: "POST",
				data: { tbl: "crit_pincode_info", id: recordId },  
				dataType: "json",
				success: function (d) {
					console.log("AJAX Success:", d); // Debug log

					if (d.success && d.data) { 
						$("#edit_modal #pincode_id").val(d.data.pincode_id);
						$("#edit_modal #state_name").val(d.data.state_name);
						$("#edit_modal #district_name").val(d.data.district_name);
						$("#edit_modal #pincode").val(d.data.pincode); 
						$(
							'#edit_modal input:radio[name="status"][value="' +
								d.data.status +
								'"]'
						).prop("checked", true); 
						$("#edit_modal").modal("show");
					} else {
						alert(d.message || "No record found.");
					}
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error:", xhr.responseText);
					alert("Error fetching data. See console.");
				},
			});
		});

		// Delete Record
		$(document).on("click", ".del_record", function () {
			if (confirm("Are you sure you want to delete this record?")) {
				var recordId = $(this).val();
				$.ajax({
					url: "<?php echo site_url('delete-record'); ?>",
					type: "POST",
					data: { tbl: "crit_pincode_info", id: recordId },
					dataType: "json",
					success: function (d) {
						if (d.success) {
							alert(d.message);
							location.reload();
						} else {
							alert(d.message || "Failed to delete record.");
						}
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", xhr.responseText);
						alert("Delete operation failed.");
					},
				});
			}
		});

		// State -> District Dropdown

		$("#frmsearch #srch_state_id").change(function () {
			$("#frmsearch #srch_district_id > option").remove();

			$.ajax({
				url: "<?php echo site_url('get-data');?>",
				type: "post",
				data: { tbl: "get_district_from_state_id", id: $(this).val() },
				success: function (d) {
					//  console.log(d);
					var opt1 = $("<option />").val("").text("All");
					$("#frmsearch #srch_district_id").append(opt1);

					$.each(d, function (key, info) {
						var opt1 = $("<option />");
						opt1.val(info.district_name);
						opt1.text(info.district_name);
						$("#frmsearch #srch_district_id").append(opt1);
					});
				},
			});
		});
	});
</script>
