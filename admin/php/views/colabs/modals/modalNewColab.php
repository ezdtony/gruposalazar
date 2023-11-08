<div class="modal fade" id="newColabModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newColabModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="newColabModalLabel">Registrar nuevo colaborador</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <h4>Información basica</h4>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Nombre (s) <span class="legend-circle bg-danger"></span></label>
                                <input id="name_new_colab" type="text" class="form-control obligatory" placeholder="Nombre (s)">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Apellidos <span class="legend-circle bg-danger"></span></label>
                                <input id="lastname_new_colab" type="text" class="form-control obligatory" placeholder="Apellidos">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Correo personal <span class="legend-circle bg-danger"></span></label>
                                <input type="email" id="personal_mail" class="form-control obligatory" placeholder="Correo electrónico personal">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Número telefónico <span class="legend-circle bg-danger"></span></label>
                                <input type="email" id="phone_number" class="form-control obligatory" placeholder="Número telefónico">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">CURP <span class="legend-circle bg-danger"></span></label>
                                <input type="email" id="curp" class="form-control obligatory" placeholder="CURP">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">RFC</label>
                                <input type="email" id="rfc" class="form-control" placeholder="RFC">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <h4>Información Adicional</h4>

                        <br><br>
                        <h5>Domicilio</h5>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Calle <span class="legend-circle bg-danger"></span></label>
                                <input id="street" type="text" class="form-control obligatory" placeholder="Calle">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-4">
                                <label class="form-label">Número Ext.<span class="legend-circle bg-danger"></span></label>
                                <input id="ext_num" type="text" class="form-control obligatory" placeholder="Núm. Ext.">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-4">
                                <label class="form-label">Número Int.<span class="legend-circle bg-danger"></span></label>
                                <input id="int_num" type="text" class="form-control obligatory" placeholder="Núm. Int.">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-4">
                                <label class="form-label">Colonia<span class="legend-circle bg-danger"></span></label>
                                <input id="colony" type="text" class="form-control obligatory" placeholder="Colonia">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-4">
                                <label class="form-label">C.P.<span class="legend-circle bg-danger"></span></label>
                                <input id="zipcode" type="text" class="form-control obligatory" placeholder="C.P.">
                            </div>
                        </div>
                        <div class="col-6">
                        <label class="form-label">Estado<span class="legend-circle bg-danger"></span></label>
                            <select class="form-select js-example-basic-single" id="selectState" autocomplete="off" >
                                <option disabled selected value="">Seleccione un estado...</option>
                                <option value="Thomas Edison">Thomas Edison</option>
                                <option value="John Doe">John Doe</option>
                                <option value="Nikola Tesla">Nikola Tesla</option>
                                <option value="Arnold Schwarzenegger">Arnold Schwarzenegger</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="mb-4">
                                <!-- Label -->
                                <label class="form-label">
                                    Password
                                </label>

                                <!-- Input -->
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" autocomplete="off" data-toggle-password-input="" placeholder="Your password">

                                    <button type="button" class="input-group-text px-4 text-secondary link-primary" data-toggle-password=""></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-4">

                                <!-- Label -->
                                <label class="form-label">
                                    Confirm password
                                </label>

                                <!-- Input -->
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" autocomplete="off" data-toggle-password-input="" placeholder="Your password again">

                                    <button type="button" class="input-group-text px-4 text-secondary link-primary" data-toggle-password=""></button>
                                </div>
                            </div>
                        </div>
                    </div> <!-- / .row -->

                    <div class="form-check">

                        <!-- Input -->
                        <input type="checkbox" class="form-check-input" id="agree">

                        <!-- Label -->
                        <label class="form-check-label" for="agree">
                            I agree with <a href="javascript: void(0);">Terms &amp; Conditions</a> and have understood <a href="javascript: void(0);">Privacy Policy</a>
                        </label>
                    </div>

                    <div class="row align-items-center text-center">
                        <div class="col-12">

                            <!-- Button -->
                            <button type="button" class="btn w-100 btn-primary mt-6 mb-2">Get started</button>
                        </div>

                        <div class="col-12">

                            <!-- Link -->
                            <small class="mb-0 text-muted">Already registered? <a href="./sign-in-basic.html" class="fw-semibold">Login</a></small>
                        </div>
                    </div> <!-- / .row -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Registrar</button>
            </div>
        </div>
    </div>
</div>