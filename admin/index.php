<?php
include("php/views/head.php");
include("php/views/navbar.php");
include("php/views/header.php");
?>
<div class="container-fluid">
<?
include "php/controllers/login.php";
if (!isset($_SESSION['user'])) {
    header('Location: logIn.php?');
} 
?>
    <?php
    if (isset($_GET['submodule'])) {
        $submodule = $_GET['submodule'];
        switch ($submodule) {
            case 'sitios':
                $include_file = 'php/views/accesos/sitios.php';
                break;
            default:
                $include_file = 'php/views/start_body.php';
                break;
        }
        include $include_file;
    } else {
        include("php/views/start_body.php");
    }
    ?>
</div>
<?php
include("php/views/footer.php");
