<?php
include("php/views/head.php");
include("php/views/navbar.php");
include("php/views/header.php");
include("php/models/sales/sales_model.php");

$active_module = "sales";

$sales_model = new Sales();
?>

<div class="container-fluid">

    <?php
    if (isset($_GET['submodule'])) {
        $submodule = $_GET['submodule'];
        switch ($submodule) {
            default:
                $include_file = 'php/views/sales/facturar_venta.php';
                break;
        }
        include $include_file;
    } else {
        include("php/views/sales/facturar_venta.php");
    }
    ?>
</div>

<script>
    $(".sale-nav-link").addClass("active");
    $(".factura-nav-link").addClass("active");
    $(".sale-nav-link").attr("aria-expanded", true);
    $(".coll-sale-nav-link").show();
</script>
<?php
include("php/views/footer.php");
