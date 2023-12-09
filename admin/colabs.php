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
                $include_file = 'php/views/colabs/colabs.php';
                break;
        }
        include $include_file;
    } else {
        include("php/views/colabs/colabs.php");
    }
    ?>
</div>

<script>
    $(".users-nav-link").addClass("active");
    $(".colabs-nav-link").addClass("active");
    $(".users-nav-link").attr("aria-expanded", true);
    $(".coll-users-nav-link").show();
</script>
<?php
include("php/views/footer.php");
