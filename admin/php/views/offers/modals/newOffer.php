<div class="modal fade" id="newOffer" tabindex="-1" role="dialog" aria-labelledby="newOfferTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="newOfferTitle">Nueva oferta</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <br>
                    <h4 style="margin-top:20px">Registrar nueva oferta</h4>

                    <br><br>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Nombre de oferta <span class="legend-circle bg-danger"></span></label>
                            <input type="text" id="offer_name" class="form-control obligatory" placeholder="Nombre de oferta">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Porcentaje de descuento <span class="legend-circle bg-danger"></span></label>
                            <input type="number" id="percentage" class="form-control obligatory" placeholder="Porcentaje de descuento">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Efectivo de descuento <span class="legend-circle bg-danger"></span></label>
                            <input type="number" id="money_discount" class="form-control obligatory" placeholder="Efectivo de descuento" value="0">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Compra mínima <span class="legend-circle bg-danger"></span></label>
                            <input type="number" id="min_ammount" class="form-control obligatory" placeholder="Efectivo de descuento" value="0">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Inicio oferta: <span class="legend-circle bg-danger"></span></label>
                            <input type="date" id="init_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Fin oferta: <span class="legend-circle bg-danger"></span></label>
                            <input type="date" id="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-12">
                        <div>
                            <label class="form-label" for="offer_details">Detalles adicionales:</label>
                            <textarea id="offer_details" class="form-control" placeholder="Más detalles de la oferta" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary saveOffer">Guardar oferta</button>
            </div>
        </div>
    </div>
</div>