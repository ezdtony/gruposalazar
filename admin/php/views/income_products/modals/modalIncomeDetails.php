<div class="modal fade" tabindex="-1" id="orderIncomeDetailModal" role="dialog" aria-labelledby="orderIncomeDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title h4" id="orderIncomeDetailModalLabel">Detalles de la orden</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">

                    <table class="table align-middle table-edge table-nowrap mb-0 table-nowrap" id="tableOrderDetail">
                        <thead class="thead-light">
                            <tr>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        CANTIDAD
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        CÓDIGO PROD.
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        PRODUCTO
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        MARCA
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        PRECIO COMPRA
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                        PRECIO VENTA
                                    </a>
                                </th>
                                <!-- <th class="text-end pe-7 min-w-200px">
                                    <a href="javascript: void(0);" class="text-muted list-sort" data-sort="sales">
                                        Acciones
                                    </a>
                                </th> -->
                            </tr>
                        </thead>
                        <tbody class="list">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="container">
                <h4 id="lblTotalItems" data-total-income="0">0</h4>
                <h3>Total:</h3>
                <h4 id="lblTotalIncomeDetail" data-total-income="0">$0</h4>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="saveIncomeOrder"><i class="fa-solid fa-file-pdf"></i> Descargar</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>