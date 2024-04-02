<div class="modal fade" id="saveNewSaleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="saveNewSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="saveNewSaleModalLabel">Guardar venta</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="card col-xxl-12 d-flex">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-1 text-muted text-uppercase">GRUPO SALAZAR</h6>
                            <h2 class="card-title">Cobrar y guardar venta</h2>
                            <div class="row">
                                <div class="card col-xxl-12 d-flex">
                                    <div class="card-body">
                                        <h3 class="card-title">Método de pago</h3>
                                        <div class="mb-3">
                                            <?php foreach ($getPaymentsMethods as $pay_method) : ?>
                                                <div class="card border-0 text-bg-primary mb-3 btn paymentMethodCard" data-id-payment-method="<?= $pay_method->id_payment_methods ?>" id="methodCard<?= $pay_method->id_payment_methods ?>" style="max-width:auto;  display:inline-block">
                                                    <div class="card-body">
                                                        <h3 class="card-title"><?= $pay_method->payment_method_description ?></h3>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <br>
                                        <div class="mb-3" id="divCashMethod" style="display:none">
                                            <h3 class="card-title" id="lblTotalSalePayment" data-total="0">Total: </h3>
                                            <div class="col-md-7">
                                                <label class="form-label" for="prod_price">Efectivo recibido:</label>
                                                <input type="text" id="recipt_cash" class="form-control form-control-lg" placeholder="Ingrese una cantidad">
                                            </div>
                                            <br>
                                            <h3 class="card-title" id="lblCashExchange" data-total="0">Cambio: </h3>

                                            <button type="button" class="btn btn-success" disabled id="btnSaveSaleCash">Imprimir ticket y guardar venta</button>
                                        </div>
                                        <div class="mb-3" id="divCreditMethod" style="display:none">
                                            <h3 class="card-title" id="lblTotalSalePayment" data-total="0">Total: </h3>
                                            <h2>Credito Salazar</h2>
                                            <div class="col-md-7">
                                                <label class="form-label" for="prod_price">Efectivo recibido:</label>
                                                <input type="text" id="recipt_cash" class="form-control form-control-lg" placeholder="Ingrese una cantidad">
                                            </div>
                                            <br>
                                            <h3 class="card-title" id="lblCashExchange" data-total="0">Cambio: </h3>

                                            <button type="button" class="btn btn-success" disabled id="btnSaveSaleCash">Imprimir ticket y guardar venta</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>