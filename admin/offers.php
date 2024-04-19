<?php
include("php/views/head.php");
include("php/views/navbar.php");
include("php/views/header.php");
include("php/models/colabs/colabs_model.php");

$active_module="coalbs";

$colabs_model = new Colabs();
?>

<div class="container-fluid">

    <?php
    if (isset($_GET['submodule'])) {
        $submodule = $_GET['submodule'];
        switch ($submodule) {
            default:
                $include_file = 'php/views/offers/offers.php';
                break;
        }
        include $include_file;
    } else {
        include("php/views/offers/offers.php");
    }
    ?>
</div>

<script>
    $(".offers-nav-link").addClass("active");
    $(".offers-nav-link").addClass("active");
    $(".offers-nav-link").attr("aria-expanded", true);
    $(".coll-offers-nav-link").show();
</script>
<?php
include("php/views/footer.php");
