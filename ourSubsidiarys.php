<?php include 'php/views/header.php'; ?>
		<?php include 'php/views/navbar.php'; ?>
		<?php include("php/models/prods/prods_model.php");
        $prods_model = new Articles;
        $getAllSubsidiary = $prods_model->getAllSubsidiaryAdress();
        ?>
		
		<?php include 'php/views/where_shop/subsidiarys.php'; ?>
		<?php include 'php/views/foot.php'; ?>