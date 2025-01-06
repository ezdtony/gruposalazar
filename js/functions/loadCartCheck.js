document.addEventListener("DOMContentLoaded", function () {
  // Cargar carrito desde sessionStorage
  const cart = JSON.parse(sessionStorage.getItem("cart_shop")) || [];
  const cartBody = document.getElementById("cart-body");
  const cartItemCount = document.getElementById("cart-item-count");
  const cartTotal = document.getElementById("cart-total");

  function updateCart() {
    cartBody.innerHTML = ""; // Limpiar el cuerpo de la tabla
    let total = 0;

    cart.forEach((item, index) => {
      // Crear fila de producto
      const row = document.createElement("tr");
      row.classList.add("bg-light");

      row.innerHTML = `
                <td class="product-name align-middle">
                    <h5 class="h5 text-black">${item.product_name}</h5>
                </td>
                <td class="product-price align-middle">$${parseFloat(
                  item.price
                ).toFixed(2)}</td>
                <td class="product-quantity align-middle">
                    <div class="input-group mb-3 d-flex align-items-center" style="max-width: 120px;">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-primary decrease" type="button" data-index="${index}">−</button>
                        </div>
                        <input type="text" class="form-control text-center" value="${
                          item.quantity
                        }" disabled>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary increase" type="button" data-index="${index}">+</button>
                        </div>
                    </div>
                </td>
                <td class="product-total align-middle">$${(
                  item.price * item.quantity
                ).toFixed(2)}</td>
                <td class="product-remove align-middle">
                    <button class="btn btn-danger btn-sm remove-item" data-index="${index}"><i class="fas fa-trash"></i></button>
                </td>
            `;
      cartBody.appendChild(row);

      // Calcular el total
      total += parseFloat(item.price) * item.quantity;
    });

    // Actualizar total
    cartItemCount.textContent = cart.length;
    cartTotal.textContent = `$${total.toFixed(2)}`;
  }

  // Llamar a la función para cargar el carrito al inicio
  updateCart();

  // Event listener para botones de aumentar/disminuir
  document.addEventListener("click", function (event) {
    if (
      event.target.classList.contains("increase") ||
      event.target.classList.contains("decrease")
    ) {
      const index = event.target.getAttribute("data-index");
      const stock = parseInt(cart[index].stock);

      if (
        event.target.classList.contains("increase") &&
        cart[index].quantity < stock
      ) {
        cart[index].quantity++;
      } else if (
        event.target.classList.contains("decrease") &&
        cart[index].quantity > 1
      ) {
        cart[index].quantity--;
      }

      sessionStorage.setItem("cart_shop", JSON.stringify(cart));
      updateCart(); // Actualizar la tabla y el resumen sin recargar la página
    }
  });
});
