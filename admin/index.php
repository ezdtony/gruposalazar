<?php
include("php/views/head.php");
include("php/views/navbar.php");
include("php/views/header.php");
?>
<div class="container-fluid">

    <?php
    if (isset($_GET['submodule'])) {
        $submodule = $_GET['submodule'];
        switch ($submodule) {
            default:
                $include_file = 'php/views/index/start_body.php';
                break;
        }
        include $include_file;
    } else {
        include("php/views/index/start_body.php");
    }
    ?>
</div>
<script>
    $(".index-nav-link").addClass("active");
</script>
<?php
include("php/views/footer.php");
