<?php
include("php/views/head.php");
include("php/views/navbar.php");
include("php/views/header.php");
include("php/models/stock_subsidiary/stock_subsidiary_model.php");

$active_module = "stock_subsidiary";

$stock_subsidiary_model = new stockSubsidiary();
?>

<div class="container-fluid">

    <?php
    if (isset($_GET['submodule'])) {
        $submodule = $_GET['submodule'];
        switch ($submodule) {
            default:
                $include_file = 'php/views/stock_subsidiary/stock_subsidiary.php';
                break;
        }
        include $include_file;
    } else {
        include("php/views/stock_subsidiary/stock_subsidiary.php");
    }
    ?>
</div>

<script>
    $(".subsidiary-stock-nav-link").addClass("active");
    $(".subsidiary-stock-nav-link").attr("aria-expanded", true);
    $(".inventory-nav-link").addClass("active");
    $(".coll-inventory-nav-link").show();
</script>
<?php
include("php/views/footer.php");
