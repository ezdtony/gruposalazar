<div class="modal fade" id="modalReceptorData" tabindex="-1" aria-labelledby="modalReceptorDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalReceptorDataLabel">GENERAR REP | Datos del Receptor</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form class="row g-3 needs-validation" novalidate>
                    <div class="col-12">
                        <label class="form-label">Cliente *</label><br>
                        <select class="form-select js-example-basic-single" style="width: 75%" id="selectClient" autocomplete="off">
                            <option value="0" selected value="">Cliente sin registrar</option>
                            <?php foreach ($getAllClientsBilling as $client) : ?>
                                <option value="<?= $client->id_clients ?>"><?= $client->rfc ?> | <?= $client->razon_social ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- <div class="col-md-12">
                        <label for="razon_social" class="form-label">CFDI RELACIONADO *</label>
                        <input type="text" class="form-control" id="cfdi_rel" placeholder="CFDI RELACIONADO" required disabled>
                    </div> -->

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
                    <div class="col-md-6">
                        <label for="email_receptor" class="form-label">Correo Electrónico</label>
                        <input type="text" class="form-control" id="email_receptor" placeholder="Correo Electrónico" required>
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
                        <select class="form-select js-example-basic-single" id="selectState" autocomplete="off">
                            <option disabled selected value="">Seleccione un estado...</option>
                            <?php foreach ($getSates as $state) : ?>
                                <option value="<?= $state->id ?>"><?= $state->estado ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Municipio *</label><br>
                        <select disabled class="form-select js-example-basic-single" id="selectCity" autocomplete="off">
                            <option disabled selected value="">Seleccione un estado...</option>
                        </select>
                    </div>

                    <div class="col-md-6">
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
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnGenerarFactura">Generar REP</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>