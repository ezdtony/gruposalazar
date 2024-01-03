<?php
/* $getArticles = $articles_model->getAllArticles(); */
$getAllBrands = $articles_model->getAllBrands();
$getAllMU = $articles_model->getAllMU();
?>
<link rel="stylesheet" href="assets/css/imgViewer.css">
<script src="assets/js/JsBarcode.all.min.js"></script>
<script src="js/functions/imageViewer.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">



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

            <div class="container">
                <div class="row g-4">
                    <div class="col-auto">
                        <label for="numProducts" class="col-form-label"> Mostrar: </label>
                    </div>
                    <div class="col-auto">
                        <select name="numProducts" id="numProducts" class="form-select">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                            <option value="0">Todos</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label for="numProducts" class="col-form-label"> registros. </label>
                    </div>
                    <div class="col-6">
                    </div>
                    <div class="col-auto">
                        <label for="searchProd" class="col-form-label"> Buscar: </label>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="searchProd" name="searchProd" class="form-control" placeholder="...">
                    </div>
                </div>
                <br>

                <!-- Table -->
                <div class="table-responsive">

                    <table class="table align-middle table-edge table-nowrap mb-0 table-nowrap" id="tableProducts">
                        <thead class="thead-light">
                            <tr>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Código
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Código de Barras
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Imagen
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Producto
                                    </a>
                                </th>
                                <th class="text-end">
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="price">
                                        Precio de compra
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
                        </tbody>
                    </table>
                </div>
                <!-- / .table-responsive -->
                <br>
                <div class="row">
                    <div class="col-6">
                    <dt class="col-auto" id="lblTotal"></dt>
                    </div>

                    <div class="col-6" id="navPagination">
                    
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<script src="js/functions/articles.js"></script>
<script src="js/functions/loadArticles.js"></script>
<?php
include 'modals/modalNewArticle.php';
include 'modals/editModalNewArticle.php';
include 'modals/modalViewBarcode.php';
include 'modals/subsidiaryStocks.php';
include 'modals/imageViewer.php';

?>