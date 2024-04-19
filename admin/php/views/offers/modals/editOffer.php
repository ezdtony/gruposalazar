<div class="modal fade" id="editOffer" tabindex="-1" role="dialog" aria-labelledby="editOfferTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="editOfferTitle">Nueva oferta</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <br>
                    <h4 style="margin-top:20px">Editar oferta</h4>

                    <br><br>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Nombre de oferta <span class="legend-circle bg-danger"></span></label>
                            <input column-name="offer_name" type="text" id="edit_offer_name" class="form-control input_edit_offer obligatory" placeholder="Editar Nombre de oferta">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Porcentaje de descuento <span class="legend-circle bg-danger"></span></label>
                            <input column-name="percentage" type="number" id="edit_percentage" class="form-control input_edit_offer obligatory" placeholder="Editar Porcentaje de descuento">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Efectivo de descuento <span class="legend-circle bg-danger"></span></label>
                            <input column-name="money_discount" type="number" id="edit_money_discount" class="form-control input_edit_offer obligatory" placeholder="Editar Efectivo de descuento">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Compra mínima <span class="legend-circle bg-danger"></span></label>
                            <input column-name="min_ammount" type="number" id="edit_min_ammount" class="form-control input_edit_offer obligatory" placeholder="Editar Efectivo de descuento">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Editar Inicio oferta: <span class="legend-circle bg-danger"></span></label>
                            <input column-name="start_date" type="date" id="edit_init_date" class="form-control input_edit_offer">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Fin Editar oferta: <span class="legend-circle bg-danger"></span></label>
                            <input column-name="end_date" type="date" id="edit_end_date" class="form-control input_edit_offer">
                        </div>
                    </div>
                    <div class="col-12">
                        <div>
                            <label class="form-label" for="offer_details">Editar Detalles adicionales:</label>
                            <textarea column-name="offer_details" id="edit_offer_details" class="form-control input_edit_offer" placeholder="Más detalles de la oferta" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                            <div class="col-md-8">
                                <label for="validationValidFileInput1">Imagen:</label>
                                <input type="file" id="edit_prod_image" class="form-control">
                                <h6>Elegir otra imagen</h6>
                            </div>
                        </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btnCloseEditOffer" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>