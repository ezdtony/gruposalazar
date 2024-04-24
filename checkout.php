<?php include 'php/views/header.php'; ?>
		<?php include 'php/views/navbar.php'; ?>
		<?php include("php/models/prods/prods_model.php");
		$prods_model = new Articles;
		$getSates = $prods_model->getStates();
		?>
		
		<?php include 'php/views/shop/checkout_body.php'; ?>
		<?php include 'php/views/foot.php'; ?>