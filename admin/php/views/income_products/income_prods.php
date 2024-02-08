<?php
$getAllSubsidiary = $income_prods_model->getAllSubsidiary();
?>
<link rel="stylesheet" href="assets/css/imgViewer.css">
<link rel="stylesheet" href="assets/css/quantityStyle.css">
<script src="assets/js/JsBarcode.all.min.js"></script>
<script src="js/functions/imageViewer.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">



<h1 class="h2">Entrada de mercancía</h1>

<div class="row">
    <div class="col-xxl-12 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0 border-0 card-header-space-between">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    Órdenes de entrada
                </h2>

                <!-- Link -->
                <a class="small fw-bold" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#modalNewIncome">
                    Registrar nueva órden
                </a>
            </div>

            <div class="container">
                <div class="row g-4">
                    <div class="col-auto">
                        <label for="numOrders" class="col-form-label"> Mostrar: </label>
                    </div>
                    <div class="col-auto">
                        <select name="numOrders" id="numOrders" class="form-select">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="0">Todos</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label for="numOrders" class="col-form-label"> registros. </label>
                    </div>
                    <div class="col-6">
                    </div>
                    <div class="col-auto">
                        <label for="searchOrder" class="col-form-label"> Buscar: </label>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="searchOrder" name="searchOrder" class="form-control" placeholder="...">
                    </div>
                </div>
                <br>

                <!-- Table -->
                <div class="table-responsive">

                    <table class="table align-middle table-edge table-nowrap mb-0 table-nowrap" id="tableOrdersIncome">
                        <thead class="thead-light">
                            <tr>
                            <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Código de orden
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Sucursal
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Fecha de registro
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Usuario que registró
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Status
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Desglose
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
<?php
include 'modals/modalNewIncome.php';
include 'modals/modalIncomeDetails.php';
?>

<script src="js/functions/incomeProducts.js"></script>
<script src="js/functions/loadIncomeOrders.js"></script>