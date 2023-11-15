<?php
include("php/views/head.php");
include("php/views/navbar.php");
include("php/views/header.php");
include("php/models/articles/articles_model.php");

$active_module="articles";

$articles_model = new Articles();
?>

<div class="container-fluid">

    <?php
    if (isset($_GET['submodule'])) {
        $submodule = $_GET['submodule'];
        switch ($submodule) {
            default:
                $include_file = 'php/views/articles/articles.php';
                break;
        }
        include $include_file;
    } else {
        include("php/views/articles/articles.php");
    }
    ?>
</div>

<script>
    $(".articles-nav-link").addClass("active");
</script>
<?php
include("php/views/footer.php");
