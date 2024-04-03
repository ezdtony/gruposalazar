<?php
include("php/views/head.php");
include("php/views/navbar.php");
include("php/views/header.php");

include("php/models/index/index_model.php");

$index_model = new IndexModel();
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
