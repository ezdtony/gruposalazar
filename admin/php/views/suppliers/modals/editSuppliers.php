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
                            <input id="edit_company" type="text" class="form-control obligatory inputSupplierInfo" column-name="supplier" placeholder="Compañía">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Nombre del contácto: <span class="legend-circle bg-danger"></span></label>
                            <input id="edit_contact_name" type="text" class="form-control obligatory inputSupplierInfo" column-name="contact_name" placeholder="Nombre del contácto">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Correo del contácto: <span class="legend-circle bg-danger"></span></label>
                            <input id="edit_contact_mail" type="text" class="form-control obligatory inputSupplierInfo" column-name="email_contact" placeholder="Nombre del contácto">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Teléfono del contácto: <span class="legend-circle bg-danger"></span></label>
                            <input id="edit_contact_phone" type="text" class="form-control obligatory inputSupplierInfo" column-name="cellphone_contact" placeholder="Nombre del contácto">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <div>
                                <label class="form-label" for="exampleFormControlTextarea1">Dirección de proveedor</label>
                                <textarea id="edit_contact_address" class="form-control" placeholder="Dirección de proveedor inputSupplierInfo" column-name="address_supplier" rows="4"></textarea>
                            </div>
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