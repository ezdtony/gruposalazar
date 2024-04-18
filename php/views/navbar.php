<?php include("php/models/navbar_model.php");
$prods_model = new Navbar;
?>
<link rel="stylesheet" href="css/navBarStyle.css">
<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

    <div class="container">
        <a class="navbar-brand" href="index.html">Grupo Salazar<span>.</span></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Incio</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Tienda </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" style="color:black !important" href="shop.php">Todos los productos</a></li>
                        <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" style="color:black !important">Marcas</a>
                            <ul class="dropdown-menu">
                            
                            <?php $getBrands = $prods_model->getBrands(); ?>
                                <?php foreach ($getBrands as $brands) : ?>
                                    <li><a class="dropdown-item" style="color:black !important" href="?parms=filtered&filter=<?=$brands->brand?>&mod=brands"><?=$brands->brand?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" style="color:black !important">Categorias</a>
                            <ul class="dropdown-menu">
                                <?php $getCategories = $prods_model->getCategories(); ?>
                                <?php foreach ($getCategories as $categories) : ?>
                                    <li><a class="dropdown-item" style="color:black !important" href="?parms=filtered&filter=<?=$categories->categories_description?>&mod=categories"><?=$categories->categories_description?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                       <!--  <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" style="color:black !important">Ofertas</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" style="color:black !important" href="#">Submenu</a></li>
                                <li><a class="dropdown-item" style="color:black !important" href="#">Submenu0</a></li>
                                  <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Submenu 1</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Subsubmenu1</a></li>
                                        <li><a class="dropdown-item" href="#">Subsubmenu1</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Submenu 2</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Subsubmenu2</a></li>
                                        <li><a class="dropdown-item" href="#">Subsubmenu2</a></li>
                                    </ul>
                                </li> 
                            </ul>
                        </li> -->
                    </ul>
                </li>

                <!-- <li><a class="nav-link" href="about.html">About us</a></li> -->
                <!-- <li><a class="nav-link" href="services.html">Services</a></li> -->
                <!-- <li><a class="nav-link" href="blog.html">Blog</a></li> -->
                <li><a class="nav-link" href="contact.php">Contáctanos</a></li>
            </ul>

            <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                <li><a class="nav-link" href="#"><img src="images/user.svg"></a></li>
                <li><a class="nav-link" href="cart.html"><img src="images/cart.svg"></a></li>
            </ul>
        </div>
    </div>

</nav>