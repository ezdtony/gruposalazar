<?php
include("php/views/head.php");
include("php/views/navbar.php");
include("php/views/header.php");
include("php/models/suppliers/suppliers_model.php");

$active_module = "articles";

$articles_model = new Suppliers();
?>

<div class="container-fluid">

    <?php
    if (isset($_GET['submodule'])) {
        $submodule = $_GET['submodule'];
        switch ($submodule) {
            default:
                $include_file = 'php/views/suppliers/suppliers.php';
                break;
        }
        include $include_file;
    } else {
        include("php/views/suppliers/suppliers.php");
    }
    ?>
</div>

<script>
    $(".purchase-nav-link").addClass("active");
    $(".suppliers-nav-link").addClass("active");
    $(".purchase-nav-link").attr("aria-expanded", true);
    $(".coll-purchase-nav-link").show();
</script>
<?php
include("php/views/footer.php");
