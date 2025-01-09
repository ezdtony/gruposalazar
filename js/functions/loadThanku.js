document.addEventListener("DOMContentLoaded", function () {
  // Cargar datos de la venta desde sessionStorage
  const cart = JSON.parse(sessionStorage.getItem("cart_shop")) || [];
  const orderCode = sessionStorage.getItem("order_code") || "N/A";
  const totalSale = sessionStorage.getItem("total_sale") || "0.00";

  // Insertar el código de la venta y el total
  document.getElementById("orderCode").innerText = orderCode;
  document.getElementById("totalSale").innerText = `$${parseFloat(
    totalSale
  ).toFixed(2)}`;

  // Generar la tabla de productos
  const orderDetailsTable = document.getElementById("orderDetails");
  let orderRows = "";

  cart.forEach((item) => {
    const itemTotal = (
      parseFloat(item.price) * parseInt(item.quantity)
    ).toFixed(2);
    orderRows += `
        <tr>
          <td>${item.product_name}</td>
          <td>${item.quantity}</td>
          <td>$${itemTotal}</td>
        </tr>
      `;
  });

  orderDetailsTable.innerHTML = orderRows;

  // Vaciar el carrito y los datos relacionados
  sessionStorage.removeItem("cart_shop");
  sessionStorage.removeItem("order_code");
  sessionStorage.removeItem("total_sale");

  // Redirigir al inicio al volver a la tienda
  document.getElementById("backToStore").addEventListener("click", function () {
    window.location.href = "index.php";
  });
});
