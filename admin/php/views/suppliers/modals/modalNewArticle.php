<div class="modal fade" id="modalNewSupplier" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalNewSupplierLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalNewSupplierLabel">Registrar nuevo proveedores</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <!-- <h4>Información general</h4> -->
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Compañía: <span class="legend-circle bg-danger"></span></label>
                                <input id="prod_code" type="text" class="form-control obligatory" placeholder="Compañía">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Nombre del contácto: <span class="legend-circle bg-danger"></span></label>
                                <input id="prod_name" type="text" class="form-control obligatory" placeholder="Nombre del contácto">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Correo del contácto: <span class="legend-circle bg-danger"></span></label>
                                <input id="prod_name" type="text" class="form-control obligatory" placeholder="Nombre del contácto">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Teléfono del contácto: <span class="legend-circle bg-danger"></span></label>
                                <input id="prod_name" type="text" class="form-control obligatory" placeholder="Nombre del contácto">
                            </div>
                        </div>
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
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveNewProduct">Guardar</button>
            </div>
        </div>
    </div>
</div>