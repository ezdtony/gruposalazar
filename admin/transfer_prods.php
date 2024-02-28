<?php
include("php/views/head.php");
include("php/views/navbar.php");
include("php/views/header.php");
include("php/models/stock_subsidiary/stock_subsidiary_model.php");

$active_module = "transfer_prods";

$stock_subsidiary_model = new stockSubsidiary();
?>

<div class="container-fluid">

    <?php
    if (isset($_GET['submodule'])) {
        $submodule = $_GET['submodule'];
        switch ($submodule) {
            default:
                $include_file = 'php/views/transfer_prods/transfer_prods.php';
                break;
        }
        include $include_file;
    } else {
        include("php/views/transfer_prods/transfer_prods.php");
    }
    ?>
</div>

<script>
    $(".transfer-prods-nav-link").addClass("active");
    $(".transfer-prods-nav-link").attr("aria-expanded", true);
    $(".inventory-nav-link").addClass("active");
    $(".coll-inventory-nav-link").show();
</script>
<?php
include("php/views/footer.php");
