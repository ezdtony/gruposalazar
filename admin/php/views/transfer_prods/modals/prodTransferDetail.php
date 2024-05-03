<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" id="modalInfotransfer" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title h4" id="myExtraLargeModalLabel">Información de traspaso</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="container">
                <h3 hidden>Total:</h3>
                <h4 hidden id="lblTotalIncome" data-total-income="0">$0</h4>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-responsive" id="tableDetailTransfer">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Producto</th>
                                <th scope="col">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-light closeModalNewIncome" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" disabled class="btn btn-primary" id="saveNewTrasnfer">Registrar</button>
            </div>
        </div>
    </div>
</div>