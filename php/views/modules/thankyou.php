<main class="thank-you-content">
    <div class="container text-center">
        <h1>¡Gracias por tu compra!</h1>
        <p class="lead">Tu pedido ha sido confirmado exitosamente.</p>

        <!-- Resumen del pedido -->
        <section class="order-summary my-5">
            <div class="container">
                <h2>Resumen del pedido</h2>
                <p><strong>Código de la venta:</strong> <span id="orderCode"></span></p>
                <table class="order-table table table-center">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="orderDetails">
                        <!-- Detalles generados dinámicamente -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>Total:</strong></td>
                            <td><strong id="totalSale"></strong></td>
                        </tr>
                    </tfoot>
                </table>
                <p>Si necesitas más información sobre tu compra, contáctanos en <a href="mailto:soporte@gruposalazar.com.mx">soporte@gruposalazar.com.mx</a>.</p>
            </div>
        </section>
        <p>En breve recibirás un correo con los detalles de tu compra y el estado del envío.</p>
        <a href="index.php" class="btn btn-primary mt-3" id="backToStore">Volver a la tienda</a>
    </div>
</main>
<script src="js/functions/loadThanku.js"></script>