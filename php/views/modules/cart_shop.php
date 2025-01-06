<div class="container py-5">
    <h2 class="mb-4 text-primary" style="color: #4287f5 !important">Vista Previa del Carrito</h2>
    <h3 class="mb-4 text-primary" style="color: #4287f5 !important">¿No olvidas nada?</h3>
    <div class="row">
        <div class="col-lg-8">
            <div class="site-blocks-table">
                <table class="table tableCart">
                    <thead class="bg-dark text-light">
                        <tr>
                            <th class="product-name">Producto</th>
                            <th class="product-price">Precio</th>
                            <th class="product-quantity">Cantidad</th>
                            <th class="product-total">Total</th>
                            <th class="product-remove">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="cart-body">
                        <!-- Los productos se cargarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="cart-summary bg-light p-4 rounded">
                <h5 class="text-primary" style="color: #4287f5 !important">Total Carrito</h5>
                <ul class="list-unstyled">
                    <li class="d-flex justify-content-between">
                        <span>Articulos en carrito</span>
                        <span id="cart-item-count">0</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Total</span>
                        <strong id="cart-total">$0.00</strong>
                    </li>
                </ul>
                <button class="btn btn-dark w-100" onclick="window.location='checkout.php'">Proceder al Pago</button>
            </div>
        </div>
    </div>
</div>

<script src="js/functions/loadCartCheck.js"></script>