<?php
include("php/views/head.php");
include("php/views/navbar.php");
include("php/views/header.php");
include("php/models/income_prods/income_prods_model.php");

$active_module = "income_prods";

$income_prods_model = new IncomeProds();
?>

<div class="container-fluid">

    <?php
    if (isset($_GET['submodule'])) {
        $submodule = $_GET['submodule'];
        switch ($submodule) {
            default:
                $include_file = 'php/views/income_products/income_prods.php';
                break;
        }
        include $include_file;
    } else {
        include("php/views/income_products/income_prods.php");
    }
    ?>
</div>

<script>
    $(".purchase-nav-link").addClass("active");
    $(".income-prods-nav-link").addClass("active");
    $(".purchase-nav-link").attr("aria-expanded", true);
    $(".coll-purchase-nav-link").show();
</script>
<?php
include("php/views/footer.php");
