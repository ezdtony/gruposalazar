<!-- Modal -->
<div class="modal fade" id="setPayModal" tabindex="-1" aria-labelledby="setPayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="setPayModalLabel">Título del Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <!-- Cuerpo del modal -->
            <div class="modal-body">
                Seleccione el método de pago.
                <div class="mb-3">
                    <label class="form-label" for="slct-paymentMethodPay">Método de pago <span class="form-label-secondary"></span></label>
                    <select id="slct-paymentMethodPay" class="form-control">
                        <option selected disabled>Seleccione una opción</option>
                        <?php foreach ($getPaymentsMethods as $payment_method) : ?>
                            <?php if ($payment_method->id_payment_methods != 3 && $payment_method->id_payment_methods != 4): ?>
                                <option value="<?= $payment_method->id_payment_methods ?>"><?= $payment_method->payment_method_description ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!-- Pie del modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="updatePaymentPay">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>