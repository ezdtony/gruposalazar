<?php
include("php/models/prods/prods_model.php");
$prods_model = new Products();
$all_articles = $prods_model->getAllArticles();

?>
<header>
    <div class="container-fluid">
        <div class="row py-3 border-bottom">

            <div class="col-sm-4 col-lg-3 text-center text-sm-start">
                <div class="main-logo">
                    <a href="index.php">
                        <img src="images/navbar_logo_lg.png" height="30px" alt="logo" class="img-fluid">
                    </a>
                </div>
            </div>

            <div class="col-sm-6 offset-sm-2 offset-md-0 col-lg-5 d-none d-lg-block">
                <div class="search-bar row bg-light p-2 my-2 rounded-4">
                    <div class="col-11 col-md-11">
                        <form id="search-form" class="text-center">
                            <?php if (isset($_GET['search'])): ?>
                                <?php if ($_GET['search'] != ''): ?>
                                    <input type="text" id="search-input" value="<?= $_GET['search'] ?>" class="form-control border-0 bg-transparent" placeholder="Buscar entre nuestros <?= count($all_articles) ?> productos disponibles" />
                                <?php else: ?>
                                    <input type="text" id="search-input" class="form-control border-0 bg-transparent" placeholder="Buscar entre nuestros <?= count($all_articles) ?> productos disponibles" />
                                <?php endif; ?>
                            <?php else: ?>
                                <input type="text" id="search-input" class="form-control border-0 bg-transparent" placeholder="Buscar entre nuestros <?= count($all_articles) ?> productos disponibles" />
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="col-1">
                        <button type="button" id="searchButton" class="btn btn-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>


            <div class="col-sm-8 col-lg-4 d-flex justify-content-end gap-5 align-items-center mt-4 mt-sm-0 justify-content-center justify-content-sm-end">
                <div class="support-box text-end d-none d-xl-block">
                    <span class="fs-6 text-muted">¿Ayuda?</span>
                    <h5 class="mb-0">Llámanos al: <br> 55 5039 2516</h5>
                </div>

                <ul class="d-flex justify-content-end list-unstyled m-0">
                    <!--  <li>
                        <a href="#" class="rounded-circle bg-light p-2 mx-1">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#user"></use>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="rounded-circle bg-light p-2 mx-1">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#heart"></use>
                            </svg>
                        </a>
                    </li> -->
                    <li class="d-lg-none">
                        <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#cart"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="d-lg-none">
                        <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSearch" aria-controls="offcanvasSearch">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#search"></use>
                            </svg>
                        </a>
                    </li>
                </ul>

                <div class="cart text-end d-none d-lg-block dropdown">
                    <button class="border-0 bg-transparent d-flex flex-column gap-2 lh-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                        <span class="fs-6 text-muted dropdown-toggle">Carrito</span>
                        <span class="cart-total fs-5 fw-bold" id="lblTotalCartShopMain">$0.00</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        <div class="row py-3">
            <div class="d-flex  justify-content-center justify-content-sm-between align-items-center">
                <nav class="main-menu d-flex navbar navbar-expand-lg">

                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                        aria-controls="offcanvasNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">

                        <div class="offcanvas-header justify-content-center">
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>

                        <div class="offcanvas-body">

                            <select class="filter-categories border-0 mb-0 me-5" id="slct-category">
                                <option selected disabled>Comprar por categoría</option>
                                <?php
                                $all_categories = $prods_model->getAllCategories();
                                ?>

                                <?php foreach ($all_categories as $category): ?>
                                    <?php if (isset($_GET['cat'])) : ?>
                                        <?php if ($_GET['cat'] == $category->id_categories) : ?>
                                            <option selected value="<?= $category->id_categories; ?>"><?= $category->categories_description; ?></option>
                                        <?php else: ?>
                                            <option value="<?= $category->id_categories; ?>"><?= $category->categories_description; ?></option>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <option value="<?= $category->id_categories; ?>"><?= $category->categories_description; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>

                            <select class="filter-categories border-0 mb-0 me-3" id="slct-brand">
                                <option selected disabled>Marcas</option>
                                <?php
                                $all_brands = $prods_model->getAllBrands();
                                ?>

                                <?php foreach ($all_brands as $brand): ?>
                                    <?php if (isset($_GET['brand'])) : ?>
                                        <?php if ($_GET['brand'] == $brand->id_brands) : ?>
                                            <option selected value="<?= $brand->id_brands; ?>"><?= $brand->brand; ?></option>
                                        <?php else: ?>
                                            <option value="<?= $brand->id_brands; ?>"><?= $brand->brand; ?></option>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <option value="<?= $brand->id_brands; ?>"><?= $brand->brand; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>

                            <ul class="navbar-nav justify-content-end menu-list list-unstyled d-flex gap-md-3 mb-0">
                                <li class="nav-item active">
                                    <a href="all_products.php" class="nav-link">Todos los productos</a>
                                </li>
                                <li class="nav-item active">
                                    <a href="offerProducts.php" class="nav-link">Ofertas</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="brand_products.php?filter=brand&brand=1" class="nav-link">Productos Sayer</a>
                                </li>
                                <li class="nav-item">
                                    <a href="category_products.php?filter=category&cat=27" class="nav-link">Pinturas</a>
                                </li>
                                <li class="nav-item">
                                    <a href="category_products.php?filter=category&cat=22" class="nav-link">Esmaltes</a>
                                </li>
                                <!-- <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" role="button" id="pages" data-bs-toggle="dropdown" aria-expanded="false">Pages</a>
                                    <ul class="dropdown-menu" aria-labelledby="pages">
                                        <li><a href="index.html" class="dropdown-item">About Us </a></li>
                                        <li><a href="index.html" class="dropdown-item">Shop </a></li>
                                        <li><a href="index.html" class="dropdown-item">Single Product </a></li>
                                        <li><a href="index.html" class="dropdown-item">Cart </a></li>
                                        <li><a href="index.html" class="dropdown-item">Checkout </a></li>
                                        <li><a href="index.html" class="dropdown-item">Blog </a></li>
                                        <li><a href="index.html" class="dropdown-item">Single Post </a></li>
                                        <li><a href="index.html" class="dropdown-item">Styles </a></li>
                                        <li><a href="index.html" class="dropdown-item">Contact </a></li>
                                        <li><a href="index.html" class="dropdown-item">Thank You </a></li>
                                        <li><a href="index.html" class="dropdown-item">My Account </a></li>
                                        <li><a href="index.html" class="dropdown-item">404 Error </a></li>
                                    </ul>
                                </li> -->
                                <li class="nav-item">
                                    <a href="category_products.php?filter=category&cat=26" class="nav-link">Impermeabilizantes</a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a href="#sale" class="nav-link">Sale</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#blog" class="nav-link">Blog</a>
                                </li> -->
                            </ul>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</header>