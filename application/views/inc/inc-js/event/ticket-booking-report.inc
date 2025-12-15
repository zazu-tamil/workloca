<script src="https://rawgit.com/Snack-X/excelexport.js/master/excelexport.js"></script>

<script>
	jQuery(function ($) {
		$(".dl-xls").click(function (e) {
			e.preventDefault();

			var fileName = $(this).attr("data") || "export.xls";

			var ee = excelExport("content-table").parseToCSV().parseToXLS(fileName);

			var uri = ee.getXLSDataURI();

			// Create a temporary <a> to force download
			var link = document.createElement("a");
			link.href = uri;
			link.download = fileName;
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
		});
	});
</script>
