<script src="https://www.paypal.com/sdk/js?client-id=AaVkSbvZ9BICdJk-32C_9ExWnrb60kJudGJAh6Npb0466E0THZiKsdTGH4QohPBPmex7lGH25iJX0yUI&currency=MXN"></script>

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Verificar datos</h1>
                </div>
            </div>
            <div class="col-lg-7">

            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="untree_co-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="border p-4 rounded" role="alert">
                    Returning customer? <a href="#">Click here</a> to login
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
                <h2 class="h3 mb-3 text-black">Detalles de Órden</h2>
                <div class="p-3 p-lg-5 border bg-white">
                    <div class="form-group">
                        <label for="c_country" class="text-black">País <span class="text-danger">*</span></label>
                        <select id="c_country" disabled class="form-control">
                            <option value="1" selected>México</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="c_fname" class="text-black">Nombre(s) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="c_fname" name="c_fname">
                        </div>
                        <div class="col-md-6">
                            <label for="c_lname" class="text-black">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="c_lname" name="c_lname">
                        </div>
                    </div>

                    <div class="form-group row mb-5">
                        <div class="col-md-6">
                            <label for="c_email_address" class="text-black">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="c_email_address" name="c_email_address">
                        </div>
                        <div class="col-md-6">
                            <label for="c_phone" class="text-black">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="c_phone" name="c_phone" placeholder="Teléfono">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="c_order_notes" class="text-black">Notas de orden</label>
                        <textarea name="c_order_notes" id="c_order_notes" cols="30" rows="5" class="form-control" placeholder="Escriba notas adicionales a considerar..."></textarea>
                    </div>
                    <br>
                    <h6 class="h5 mb-3 text-black">Método de entrega</h6>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-label">Seleccione un método de entrega <span class="text-danger">*</span></label><br>
                            <select class="form-select" id="shippingMethod">
                                <option disabled selected value="">Seleccione una opción...</option>
                                <option value="1">Entrega en sucursal</option>
                                <option value="2">Envío a domicilio</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <!--  <div class="form-group row">
                        <div class="col-md-12">
                            <label for="c_companyname" class="text-black">Company Name </label>
                            <input type="text" class="form-control" id="c_companyname" name="c_companyname">
                        </div>
                    </div> -->
                    <div id="divHomeDelivery" style="display:none">
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

                    <div id="divSubsidiaryDelivery" style="display:none">
                        <label class="form-label">Seleccione una sucursal <span class="text-danger">*</span></label><br>
                        <select class="form-select" id="shippingSubsidiary">
                            <option disabled selected>Seleccione una opción...</option>
                            <?php foreach ($getAllSubsidiary as $subsidiary) : ?>
                                <option value="<?= $subsidiary->id_subsidiary ?>"><?= $subsidiary->subsidiary_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <!--  <div class="form-group">
                        <label for="c_create_account" class="text-black" data-bs-toggle="collapse" href="#create_an_account" role="button" aria-expanded="false" aria-controls="create_an_account"><input type="checkbox" value="1" id="c_create_account"> Create an account?</label>
                        <div class="collapse" id="create_an_account">
                            <div class="py-2 mb-4">
                                <p class="mb-3">Create an account by entering the information below. If you are a returning customer please login at the top of the page.</p>
                                <div class="form-group">
                                    <label for="c_account_password" class="text-black">Account Password</label>
                                    <input type="email" class="form-control" id="c_account_password" name="c_account_password" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
 -->

                    <!--  <div class="form-group">
                        <label for="c_ship_different_address" class="text-black" data-bs-toggle="collapse" href="#ship_different_address" role="button" aria-expanded="false" aria-controls="ship_different_address"><input type="checkbox" value="1" id="c_ship_different_address"> Enviar a una dire</label>
                        <div class="collapse" id="ship_different_address">
                            <div class="py-2">

                                <div class="form-group">
                                    <label for="c_diff_country" class="text-black">Country <span class="text-danger">*</span></label>
                                    <select id="c_diff_country" class="form-control">
                                        <option value="1">Select a country</option>
                                        <option value="2">bangladesh</option>
                                        <option value="3">Algeria</option>
                                        <option value="4">Afghanistan</option>
                                        <option value="5">Ghana</option>
                                        <option value="6">Albania</option>
                                        <option value="7">Bahrain</option>
                                        <option value="8">Colombia</option>
                                        <option value="9">Dominican Republic</option>
                                    </select>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="c_diff_fname" class="text-black">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="c_diff_fname" name="c_diff_fname">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="c_diff_lname" class="text-black">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="c_diff_lname" name="c_diff_lname">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="c_diff_companyname" class="text-black">Company Name </label>
                                        <input type="text" class="form-control" id="c_diff_companyname" name="c_diff_companyname">
                                    </div>
                                </div>

                                <div class="form-group row  mb-3">
                                    <div class="col-md-12">
                                        <label for="c_diff_address" class="text-black">Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="c_diff_address" name="c_diff_address" placeholder="Street address">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Apartment, suite, unit etc. (optional)">
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="c_diff_state_country" class="text-black">State / Country <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="c_diff_state_country" name="c_diff_state_country">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="c_diff_postal_zip" class="text-black">Posta / Zip <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="c_diff_postal_zip" name="c_diff_postal_zip">
                                    </div>
                                </div>

                                <div class="form-group row mb-5">
                                    <div class="col-md-6">
                                        <label for="c_diff_email_address" class="text-black">Email Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="c_diff_email_address" name="c_diff_email_address">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="c_diff_phone" class="text-black">Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="c_diff_phone" name="c_diff_phone" placeholder="Phone Number">
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div> -->


                    <br>
                    <button type="button" class="btn btn-primary" id="checkUserDataCheckout">Validar datos</button>

                </div>
            </div>
            <div class="col-md-6">

                <!--  <div class="row mb-5">
                    <div class="col-md-12">
                        <h2 class="h3 mb-3 text-black">Coupon Code</h2>
                        <div class="p-3 p-lg-5 border bg-white">

                            <label for="c_code" class="text-black mb-3">Enter your coupon code if you have one</label>
                            <div class="input-group w-75 couponcode-wrap">
                                <input type="text" class="form-control me-2" id="c_code" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-black btn-sm" type="button" id="button-addon2">Apply</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div> -->

                <div class="row mb-5">
                    <div class="col-md-12">
                        <h2 class="h3 mb-3 text-black">Su orden</h2>
                        <div class="p-3 p-lg-5 border bg-white">
                            <table class="table site-block-order-table mb-5 orderTable">
                                <thead>
                                    <th>Producto</th>
                                    <th>Total</th>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Subtotal</strong></td>
                                        <td class="text-black tdTotal"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Total de compra</strong></td>
                                        <td class="text-black font-weight-bold tdTotal"><strong></strong></td>
                                    </tr>
                                </tfoot>
                            </table>


                            <h6 class="h5 mb-3 text-black">Método de pago</h6>

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
                            <div id="paypal-button-container"></div>

                            <!-- <div class="border p-3 mb-3">
                                <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse" href="#collapsebank" role="button" aria-expanded="false" aria-controls="collapsebank">Transferencia Interbancaria (SPEI)</a></h3>

                                <div class="collapse" id="collapsebank">
                                    <div class="py-2">
                                        <p class="mb-0">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border p-3 mb-3">
                                <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse" href="#collapsecheque" role="button" aria-expanded="false" aria-controls="collapsecheque">Efectivo</a></h3>

                                <div class="collapse" id="collapsecheque">
                                    <div class="py-2">
                                        <p class="mb-0">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border p-3 mb-5">
                                <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse" href="#collapsepaypal" role="button" aria-expanded="false" aria-controls="collapsepaypal">PayPal</a></h3>

                                <div class="collapse" id="collapsepaypal">
                                    <div class="py-2">
                                        <p class="mb-0">Realice su pago directamente en nuestra cuenta bancaria. Utilice su ID de pedido como referencia de pago. Su pedido no se procesado hasta que los fondos se hayan liquidado en nuestra cuenta.</p>
                                    </div>
                                </div>
                            </div> -->

                            <!-- <div class="form-group">
                                <button class="btn btn-black btn-lg py-3 btn-block" onclick="window.location='thankyou.html'">Realizar pago</button>
                            </div> -->

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- </form> -->
    </div>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        emailjs.init({
            publicKey: "4Fx6lU8V_sm8_3t6R",
        });
    });
</script>
<script src="js/functions/cartFunctions.js"></script>
<script src="js/functions/loadCartCheckout.js"></script>