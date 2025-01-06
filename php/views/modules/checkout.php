<?php $getAllSubsidiary = $prods_model->getAllSubsidiary();
$getSates = $prods_model->getStates();
?>
<script src="https://www.paypal.com/sdk/js?client-id=AaVkSbvZ9BICdJk-32C_9ExWnrb60kJudGJAh6Npb0466E0THZiKsdTGH4QohPBPmex7lGH25iJX0yUI&currency=MXN"></script>
<div class="container py-5">
    <h2 class="mb-4 text-primary" style="color: #4287f5 !important">Proceso de Pago</h2>
    <form id="payment-form">
        <div class="row">
            <!-- Información de Facturación -->
            <div class="col-lg-6">
                <h4 class="text-dark mb-3">Información general</h4>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="name" class="font-weight-bold">Nombre(s)</label>
                        <input type="text" id="name" class="form-control rounded-lg p-3" placeholder="Ingrese su nombre completo" required>
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="font-weight-bold">Apellidos</label>
                        <input type="text" id="lastname" class="form-control rounded-lg p-3" placeholder="Ingrese su nombre completo" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="name" class="font-weight-bold">Correo electrónico</label>
                        <input type="mail" id="mail" class="form-control rounded-lg p-3" placeholder="Ingrese su nombre completo" required>
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="font-weight-bold">Teléfono</label>
                        <input type="text" id="cellphone" class="form-control rounded-lg p-3" placeholder="Ingrese su nombre completo" required>
                    </div>

                    <div class="form-group">
                        <label for="order-notes" class="font-weight-bold">Notas del pedido y/o entrega</label>
                        <textarea id="order-notes" class="form-control rounded-lg p-3" rows="4" placeholder="Ingrese notas adicionales para el pedido, por ejemplo, instrucciones de entrega."></textarea>
                    </div>
                </div>
                <br>
                <h4>Método de entrega</h4>

                <select class="form-select" id="shippingMethod">
                    <option disabled="" selected="" value="">Seleccione una opción...</option>
                    <option value="1">Entrega en sucursal</option>
                    <option value="2">Envío a domicilio</option>
                </select>


                <div id="divHomeDelivery">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="c_address" class="text-black">Dirección <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="c_address" name="c_address" placeholder="Calle y número">
                        </div>
                        <div class="col-md-6">
                            <label for="c_address" class="text-black">Colonia <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="c_colony" name="c_colony" placeholder="Colonia">
                        </div>
                        <div class="col-md-6">
                            <label for="c_address" class="text-black">Código Postal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="c_zip_code" name="c_zip_code" placeholder="Código Postal">
                        </div>
                    </div>

                    <!-- <div class="form-group mt-3">
                        <input type="text" class="form-control" placeholder="Apartment, suite, unit etc. (optional)">
                    </div> -->



                    <div class="form-group row">
                        <!-- <div class="col-md-6">
                            <label class="form-label">Sucursal de entrega <span class="text-danger">*</span></label><br>
                            <select class="form-select js-example-basic-single" id="selectState" autocomplete="off">
                                <option disabled selected value="">Seleccione una sucursal...</option>
                                <?php foreach ($getSates as $state) : ?>
                                    <option value="<?= $state->id ?>"><?= $state->estado ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> -->

                        <div class="col-md-6">
                            <label class="form-label">Estado<span class="text-danger">*</span></label><br>
                            <select class="form-select js-example-basic-single" id="selectState" autocomplete="off">
                                <option disabled selected value="">Seleccione un estado...</option>
                                <?php foreach ($getSates as $state) : ?>
                                    <option value="<?= $state->id ?>"><?= $state->estado ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Municipio<span class="text-danger">*</span></label><br>
                            <select disabled class="form-select js-example-basic-single" id="selectCity" autocomplete="off">
                                <option disabled selected value="">Seleccione un estado...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="divSubsidiaryDelivery">
                    <label class="form-label">Seleccione una sucursal <span class="text-danger">*</span></label><br>
                    <select class="form-select" id="shippingSubsidiary">
                        <option disabled selected value="">Seleccione una opción...</option>
                        <?php foreach ($getAllSubsidiary as $subsidiary) : ?>
                            <option value="<?= $subsidiary->id_subsidiary ?>"><?= $subsidiary->subsidiary_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <h4 class="h5 mb-3 text-black">Método de pago</h4>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="form-label">Seleccione un método de pago <span class="text-danger">*</span></label><br>
                        <select class="form-select" id="paymentMethod" disabled>
                            <option disabled selected value="">Seleccione una opción...</option>
                            <option value="1">Pago en sucursal</option>
                            <option value="2">PAYPAL / TDC / TDD</option>
                        </select>
                    </div>
                </div>
                <br>
                <br>
                <button type="submit" class="btn btn-dark w-100 py-3 mt-4 rounded-lg" id="processPayment">Procesar datos</button>
                <br>
                <br>
                <div id="paypal-button-container"></div>

            </div>


            <!-- Resumen de la Orden -->
            <div class="col-lg-6">
                <h4 class="text-dark mb-3">Su Orden</h4>
                <div class="p-3 p-lg-5 bg-light border rounded-lg">
                    <table class="table site-block-order-table mb-5 orderTable">
                        <thead class="bg-dark text-light">
                            <tr>
                                <th>Producto</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items-summary">
                            <!-- Aquí se cargarán dinámicamente los productos -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-black font-weight-bold"><strong>Subtotal</strong></td>
                                <td class="text-black tdTotal" id="subtotal">0.00</td>
                            </tr>
                            <tr>
                                <td class="text-black font-weight-bold"><strong>Total de compra</strong></td>
                                <td class="text-black font-weight-bold tdTotal" id="total">0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div id="paypal-button-container"></div>
                </div>
            </div>
        </div>
        
    </form>
</div>

<style>
    .form-control {
        border: 2px solid #ced4da;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
    }

    .btn-dark {
        background-color: #343a40;
        border-color: #343a40;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .btn-dark:hover {
        background-color: #23272b;
        border-color: #1d2124;
    }

    .orderTable th,
    .orderTable td {
        padding: 15px;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .rounded-lg {
        border-radius: 12px;
    }

    .form-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        border: 2px solid #ced4da;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: #ffffff;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
    }

    .form-select:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
    }

    .is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
</style>

<script src="js/functions/loadCheckOut.js"></script>