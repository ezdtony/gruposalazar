<?php
$getCollaborators = $sales_model->getAllClientsCredits();
$getAllClients = $sales_model->getAllClients();
?>


<div class="row">
    <div class="card col-xxl-12 d-flex">
        <div class="card-body">
            <h6 class="card-subtitle mb-1 text-muted text-uppercase">GRUPO SALAZAR</h6>
            <h2 class="card-title">Nueva Venta</h2>
            <div class="row">
                <div class="card col-xxl-5 d-flex">
                    <div class="card-body">
                        <h4 class="card-title">Cliente</h4>
                        <div class="mb-3">
                            <label class="form-label" for="exampleFormControlSelect1">Seleccione un cliente <span class="form-label-secondary">(Opcional)</span></label>
                            <select id="exampleFormControlSelect1" class="form-control js-example-basic-single">
                                <option>Seleccione una opción</option>
                                <?php foreach ($getAllClients as $clients) : ?>
                                    <option value="<?= $clients->id_clients ?>"><?= $clients->name ?> <?= $clients->lastname ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <br>
                        <h4 class="card-title">Datos de producto</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="search_prod" placeholder="Código de barras" aria-label="Código de barras" aria-describedby="basic-addon1">
                                    <span class="input-group-text btn btn-info" id="basic-addon1"><i class="fa-solid fa-barcode"></i></span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="prod_name">Nombre de producto</label>
                                    <input type="text" disabled id="prod_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-md-6">
                                    <label class="form-label" for="prod_price">Precio unitario</label>
                                    <input type="text" disabled id="prod_price" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="prod_quantity">Cantidad:</label>
                                    <input type="text" id="prod_quantity" class="form-control" disabled>
                                    <h6 id="prod_stock">Stock: -</h6>
                                </div>
                                <div class="col-md-10">
                                    <label class="form-label" for="prod_subtotal">Subtotal</label>
                                    <input type="text" disabled id="prod_subtotal" class="form-control">
                                </div>
                                <div class="col-md-8">
                                    <br>
                                    <br>
                                    <button type="button" class="btn btn-primary" id="addProd">Agregar producto</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card col-xxl-7 d-flex">
                    <div class="card-body">
                        <h2 class="card-title">Productos</h2>
                        <table class="table table-striped" id="tableSale">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Código</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Precio U.</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="js/functions/newSale.js"></script>
<?php
/* include 'modals/newClientCredit.php'; */
?>