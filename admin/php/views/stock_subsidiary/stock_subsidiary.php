<?php
$getAllSubsidiary = $stock_subsidiary_model->getAllSubsidiary();
?>
<link rel="stylesheet" href="assets/css/imgViewer.css">
<link rel="stylesheet" href="assets/css/quantityStyle.css">
<script src="assets/js/JsBarcode.all.min.js"></script>
<script src="js/functions/imageViewer.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">



<h1 class="h2">Stock de productos en sucursal</h1>
<div class="row">
    <div class="col-xxl-12 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0 border-0 card-header-space-between">
            </div>

            <div class="container">
                <div class="row g-4">
                    <div class="col-4">
                        <select class="form-select mb-3" aria-label="Default select example" id="slct_subsidiary">
                            <option disabled selected>Seleccione sucursal</option>
                            <?php foreach ($getAllSubsidiary as $subsidiary) : ?>
                                <option value="<?= $subsidiary->id_subsidiary ?>"><?= $subsidiary->subsidiary_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xxl-12 d-flex" style="display: none !important" id="divTable">
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

                    <table class="table align-middle table-edge table-nowrap mb-0 table-nowrap" id="tableStocks">
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
                                        Producto
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Marca
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Stock
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
<?php
/* include 'modals/modalNewIncome.php';
include 'modals/modalIncomeDetails.php'; */
?>

<script src="js/functions/stockSubsidiary.js"></script>