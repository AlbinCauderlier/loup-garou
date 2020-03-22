<!DOCTYPE html>
<html lang="en">
<head>
	<title>Forgotten password - Loups Garous</title>
	<?php
		include_once("common/head.php");
	?>
</head>
<body id="top">
	<section class="py-5">
		<div class="container py-5 text-center">
			<h2 class="pb-4">
				<img src="<?=ROOT_URL?>/images/iris-logo2.png" style="max-width: 100px;" alt="Loups Garous"/>
			<div class="row">
				<div class="col-md-6 mx-auto">
		            <div class="card shadow p-5">
						<form id="forgotten-password" action="/controller/authentication/forgotten-password.php">
			                <?php
			                  include_once("common/forms/form-forgotten-password.php");
			                ?>
	  				    </form>
		            </div>
				</div>
			</div>
		</div>
	</section>
	<script type="text/javascript" src="/vendors/jquery-3.4.0.min.js"></script>
	<script type="text/javascript" src="/vendors/bootstrap-4.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/vendors/animsition/animsition.min.js"></script>

	<script type="text/javascript" src="/js/feather.min.js"></script>

	<script type="text/javascript" src="/vendors/DataTables/datatables.min.js"></script>
	<script type="text/javascript" src="/vendors/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="/vendors/DataTables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="/vendors/DataTables/Buttons-1.5.6/js/buttons.bootstrap4.min.js"></script>
	<script type="text/javascript" src="/vendors/DataTables/Buttons-1.5.6/js/buttons.flash.min.js"></script>
	<script type="text/javascript" src="/vendors/DataTables/JSZip-2.5.0/jszip.min.js"></script>
	<script type="text/javascript" src="/vendors/DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="/vendors/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
	<script type="text/javascript" src="/vendors/DataTables/Buttons-1.5.6/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="/vendors/DataTables/Buttons-1.5.6/js/buttons.print.min.js"></script>

	<script type="text/javascript" src="/js/main.js"></script>
</body>
</html>
