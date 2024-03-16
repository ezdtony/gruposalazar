<div class="modal fade" id="newClientCredit" tabindex="-1" role="dialog" aria-labelledby="newClientCreditTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="newClientCreditTitle">Nuevo crédito cliente</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <br>
                    <h4 style="margin-top:20px">Información de Acceso y Puesto</h4>

                    <br><br>
                    <div class="col-12">
                        <label class="form-label">Cliente<span class="legend-circle bg-danger"></span></label><br>
                        <select class="form-select js-example-basic-single" id="selectClient" autocomplete="off">
                            <option disabled selected value="">Seleccione una cliente...</option>
                            <?php foreach ($getAllClients as $clients) : ?>
                                <option value="<?= $clients->id_clients ?>"><?= $clients->name ?> <?= $clients->lastname ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Monto de crédito <span class="legend-circle bg-danger"></span></label>
                            <input type="number" id="credit_ammount" class="form-control obligatory" placeholder="Monto de crédito">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary saveCreditClient">Guardar crédito</button>
            </div>
        </div>
    </div>
</div>