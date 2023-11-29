<?php
$getArticles = $articles_model->getAllArticles();
$getAllBrands = $articles_model->getAllBrands();
$getAllMU = $articles_model->getAllMU();
/* $getSates = $articles_model->getAllStates();
$getSubsidiary = $articles_model->getSubsidiary();
$getPositions = $articles_model->getPositions(); */
?>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<h1 class="h2">Administración de Productos</h1>

<div class="row">
    <div class="col-xxl-12 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0 border-0 card-header-space-between">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    Todos los productos
                </h2>

                <!-- Link -->
                <a class="small fw-bold" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#modalNewArticle">
                    Registrar producto
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table align-middle table-edge table-nowrap mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>
                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                    Código / SKU
                                </a>
                            </th>
                            <th>
                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                    Producto
                                </a>
                            </th>
                            <th class="text-end">
                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="price">
                                    Precio unitario
                                </a>
                            </th>
                            <!-- <th class="text-end">
                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="quantity">
                                    Stock
                                </a>
                            </th> -->
                            <th class="text-end">
                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="barcode">
                                    Código de Barras
                                </a>
                            </th>
                            <th class="text-end pe-7 min-w-200px">
                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="sales">
                                    Ventas
                                </a>
                            </th>
                            <th class="text-end pe-7 min-w-200px">
                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="sales">
                                    Acciones
                                </a>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="list">
                        <?php foreach ($getArticles as $article) : ?>
                            <?php
                            $percentage = number_format((($article->stock / $article->ideal_stock) * 100), 0);
                            ?>
                            <tr>
                                <td class="name fw-bold">
                                    <?= $article->product_short_name ?> / <?= $article->product_code ?>
                                </td>
                                <td class="name fw-bold">

                                    <div class="avatar avatar-sm" style="margin-right:10px">
                                        <img src="<?= $article->thumbnail ?>" alt="..." class="avatar-img">
                                    </div>
                                    <?= $article->product_name ?>
                                </td>
                                <td class="price text-end">
                                    $<?= number_format($article->price, 2, '.') ?>
                                </td>
                               <!--  <td class="quantity text-end">
                                    <?= $article->stock ?>
                                </td> -->
                                <td class="barcode fw-bold text-center">
                                    <button type="button" data-barcode="<?= $article->product_barcode ?>" class="btn btn-secondary btnGenerateBarcode" data-bs-toggle="modal" data-bs-target="#viewBarcode">
                                        <i class="fa-solid fa-barcode"></i>
                                    </button>
                                </td>
                                <td class="sales" data-sales="81">
                                    <div class="d-flex justify-content-between align-items-center text-center">
                                        <div class="progress d-flex flex-grow-1">
                                            <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%" aria-valuenow="<?= $article->stock ?>" aria-valuemin="0" aria-valuemax="<?= $article->ideal_stock ?>"></div>
                                        </div>
                                        <span class="ms-3 text-muted"><?= $percentage ?>%</span>
                                    </div>
                                    <button title="Ver stock en sucursales"  data-bs-toggle="modal" data-bs-target="#subsidiaryStocks" data-product-name="<?= $article->product_short_name ?> / <?= $article->product_code ?> | <?= $article->product_name ?>" data-id-product="<?= $article->id_prducts ?>" type="button" class="btn btn-info btn-sm btnSeeStockSubsidiary"><i class="fa-solid fa-cubes"></i></button>
                                </td>
                                <td class="barcode fw-bold text-center">
                                    <div class="dropdown">
                                        <a href="javascript: void(0);" class="dropdown-toggle no-arrow text-secondary" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="14" width="14">
                                                <g>
                                                    <circle cx="12" cy="3.25" r="3.25" style="fill: currentColor"></circle>
                                                    <circle cx="12" cy="12" r="3.25" style="fill: currentColor"></circle>
                                                    <circle cx="12" cy="20.75" r="3.25" style="fill: currentColor"></circle>
                                                </g>
                                            </svg>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a  data-id-product="<?= $article->id_prducts ?>" class="dropdown-item editProduct"data-bs-toggle="modal" data-bs-target="#modalEditArticle" >
                                                Editar
                                            </a>
                                           <!--  <a href="javascript: void(0);" class="dropdown-item">
                                                Editar stock en sucursales
                                            </a> -->
                                            <a href="javascript: void(0);" class="dropdown-item" style="color:red !important;">
                                                Borrar
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- / .table-responsive -->
        </div>
    </div>
</div>
<script src="js/functions/articles.js"></script>
<?php
include 'modals/modalNewArticle.php';
include 'modals/editModalNewArticle.php';
include 'modals/modalViewBarcode.php';
include 'modals/subsidiaryStocks.php';

?>