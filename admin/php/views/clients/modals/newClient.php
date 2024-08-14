<div class="modal fade" id="newClientModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="newClientModalLabel">Registrar nuevo cliente</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <h4>Información basica</h4>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Nombre (s) <span class="legend-circle bg-danger"></span></label>
                                <input id="name_new_client" type="text" class="form-control obligatory" placeholder="Nombre (s)">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Apellidos <span class="legend-circle bg-danger"></span></label>
                                <input id="lastname_new_client" type="text" class="form-control obligatory" placeholder="Apellidos">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Correo electrónico <span class="legend-circle bg-danger"></span></label>
                                <input type="email" id="mail" class="form-control obligatory" placeholder="Correo electrónico personal">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Número telefónico <span class="legend-circle bg-danger"></span></label>
                                <input type="text" id="phone_number" class="form-control obligatory" placeholder="Número telefónico">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-4">
                                <!-- Label -->
                                <label class="form-label">
                                    Contraseña <span class="legend-circle bg-danger"></span>
                                </label>

                                <!-- Input -->
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" id="password" autocomplete="off" placeholder="Contraseña">

                                    <!-- <button type="button" class="input-group-text px-4 text-secondary link-primary" data-toggle-password=""></button> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Generar contraseña</label>
                            <button type="button" id="generate_password" class="btn btn-outline-info btn-rounded">Generar</button>
                        </div>
                    </div>


                    <form class="row g-3 needs-validation" novalidate>
                        <h4>Datos de facturación</h4>
                        <div class="col-md-12">
                            <label for="razon_social" class="form-label">Nombre o Razón Social *</label>
                            <input type="text" class="form-control" id="razon_social" placeholder="Nombre o Razón Social" required>
                        </div>
                        <div class="col-md-6">
                            <label for="rfc" class="form-label">RFC *</label>
                            <input type="text" class="form-control" id="rfc" placeholder="RFC" required>
                            <div class="valid-feedback">
                                RFC
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-4">
                                <label class="form-label">Calle *</label>
                                <input id="street" type="text" class="form-control obligatory" placeholder="Calle">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-4">
                                <label class="form-label">Número Ext. *</label>
                                <input id="ext_num" type="text" class="form-control obligatory" placeholder="Núm. Ext.">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-4">
                                <label class="form-label">Número Int.</label>
                                <input id="int_num" type="text" class="form-control obligatory" placeholder="Núm. Int.">
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="mb-4">
                                <label class="form-label">Colonia *</label>
                                <input id="colony" type="text" class="form-control obligatory" placeholder="Colonia">
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="mb-4">
                                <label class="form-label">Localidad *</label>
                                <input id="locality" type="text" class="form-control obligatory" placeholder="Localidad">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="mb-4">
                                <label class="form-label">C.P. *</label>
                                <input id="zipcode" type="text" class="form-control obligatory" placeholder="C.P.">
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Estado *</label><br>
                            <select class="form-select js-example-basic-single" id="selectStateBill" autocomplete="off">
                                <option disabled selected value="">Seleccione un estado...</option>
                                <?php foreach ($getSates as $state) : ?>
                                    <option value="<?= $state->id ?>"><?= $state->estado ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Municipio *</label><br>
                            <select disabled class="form-select js-example-basic-single" id="selectCityBill" autocomplete="off">
                                <option disabled selected value="">Seleccione un estado...</option>
                            </select>
                        </div>

                        <!--  <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="select-uso-cfdi">Uso CFDI *<span class="form-label-secondary"></span></label>
                                <select id="select-uso-cfdi" class="form-control">
                                    <option selected disabled>Seleccione una opción</option>
                                    <?php foreach ($usosCFDI as $uso_cfdi) : ?>
                                        <option value="<?= $uso_cfdi->c_UsoCFDI ?>"><?= $uso_cfdi->c_UsoCFDI ?> | <?= $uso_cfdi->descripcion ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="select-reg-fiscal">Régimen Fiscal *<span class="form-label-secondary"></span></label>
                                <select id="select-reg-fiscal" class="form-control">
                                    <option selected disabled>Seleccione una opción</option>
                                    <?php foreach ($getRegimenesFiscales as $reg_fiscal) : ?>
                                        <option value="<?= $reg_fiscal->c_RegimenFiscal ?>"><?= $reg_fiscal->c_RegimenFiscal ?> | <?= $reg_fiscal->descripcion ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div> -->
                    </form>

                    <!-- <div class="row">
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
                                <label class="form-label">Número Int.</label>
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
                            <label class="form-label">Estado<span class="legend-circle bg-danger"></span></label><br>
                            <select class="form-select js-example-basic-single" id="selectState" autocomplete="off">
                                <option disabled selected value="">Seleccione un estado...</option>
                                <?php foreach ($getSates as $state) : ?>
                                    <option value="<?= $state->id ?>"><?= $state->estado ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Municipio<span class="legend-circle bg-danger"></span></label><br>
                            <select disabled class="form-select js-example-basic-single" id="selectCity" autocomplete="off">
                                <option disabled selected value="">Seleccione un estado...</option>
                            </select>
                        </div>
                    </div> -->

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="closeModalNewUser">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnSaveNewClient">Registrar</button>
            </div>
        </div>
    </div>
</div>