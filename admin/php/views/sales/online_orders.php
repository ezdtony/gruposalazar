<?php
$getAllSubsidiary = $sales_model->getAllSubsidiary();
$getPaymentsMethods = $sales_model->getPaymentMethods();
?>


<div class="row">
    <div class="col-xxl-12 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0 border-0 card-header-space-between">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    Ventas en linea registradas
                </h2>

                <!-- Link -->
                <!-- <a class="small fw-bold" style="cursor: pointer" href="new_sale.php">
                    Registrar venta
                </a> -->
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

                    <table class="table align-middle table-edge table-striped table-nowrap mb-0 table-nowrap" id="tableSales">
                        <thead class="thead-light">
                            <tr>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        #
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Fecha
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Cantidad
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Satus
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        Método de pago
                                    </a>
                                </th>
                                <th class="text-end">
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="price">
                                        Detalle
                                    </a>
                                </th>
                                <th class="text-end">
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="price">
                                        Lista
                                    </a>
                                </th>
                                <th class="text-end">
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="price">
                                        Entregada
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
<script src="js/functions/newSale.js"></script>
<script src="js/functions/loadOnlineSales.js"></script>
<?php
include 'modals/saleDetail.php';
?>