<div class="modal fade" id="editSuppliers" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editSuppliersLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="editSuppliersLabel">Editar producto</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Compañía: <span class="legend-circle bg-danger"></span></label>
                            <input id="edit_company" type="text" class="form-control obligatory" placeholder="Compañía">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Nombre del contácto: <span class="legend-circle bg-danger"></span></label>
                            <input id="edit_contact_name" type="text" class="form-control obligatory" placeholder="Nombre del contácto">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Correo del contácto: <span class="legend-circle bg-danger"></span></label>
                            <input id="edit_contact_mail" type="text" class="form-control obligatory" placeholder="Nombre del contácto">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Teléfono del contácto: <span class="legend-circle bg-danger"></span></label>
                            <input id="edit_contact_phone" type="text" class="form-control obligatory" placeholder="Nombre del contácto">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Dirección: <span class="legend-circle bg-danger"></span></label>
                            <input id="edit_contact_address" type="text" class="form-control obligatory" placeholder="Nombre del contácto">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary closeModalEditSupplier" data-bs-dismiss="modal">Cerrar</button>
                <!-- <button type="button" class="btn btn-primary" id="saveNewProduct">Guardar</button> -->
            </div>
        </div>
    </div>
</div>