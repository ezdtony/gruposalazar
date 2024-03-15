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
                $include_file = 'php/views/clients/colabs.php';
                break;
        }
        include $include_file;
    } else {
        include("php/views/clients/clients.php");
    }
    ?>
</div>

<script>
    $(".clients-mod-nav-link").addClass("active");
    $(".clients-nav-link").addClass("active");
    $(".clients-mod-nav-link").attr("aria-expanded", true);
    $(".coll-clients-mod-nav-link").show();
</script>
<?php
include("php/views/footer.php");
