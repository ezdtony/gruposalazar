$(document).ready(function () {
  console.log("ready main function");

  if (sessionStorage.getItem("cart_shop")) {
    var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop"));
    //    console.log(cart_shop);
    $(".cartSizeTxt").text(cart_shop.length);
    $("#lblTotalCartShopMain").text("$" + sessionStorage.getItem("total_sale"));
  } else {
    sessionStorage.setItem("cart_shop", JSON.stringify([]));
    sessionStorage.setItem("total_sale", 0);
  }

  $(document).on("click", ".addProdToCart", async function (event) {
    var cart_shop = JSON.parse(sessionStorage.getItem("cart_shop")) || [];

    var id_product = $(this).attr("data-id-product");
    var price = $(this).attr("data-product-price");
    var stock = $(this).attr("data-stock");
    var product_name = $(this).attr("data-product-name");
    var quantity = $("#quantity-prod-" + id_product).val();

    let add_prod = 1;

    // Verificar si el producto ya está en el carrito
    await cart_shop.forEach(function (cart_item, index) {
      if (cart_item.id_product == id_product) {
        add_prod = 0; // El producto ya existe en el carrito
      }
    });

    // Si el producto no está en el carrito, agregarlo
    if (add_prod) {
      let arr_prod_cart = {
        id_product: id_product,
        quantity: quantity,
        price: price,
        stock: stock,
        product_name: product_name,
      };

      cart_shop.push(arr_prod_cart);
      $(".cartSizeTxt").text(cart_shop.length); // Actualizar el contador de productos

      // Actualizar el total de la venta
      let total_sale = sessionStorage.getItem("total_sale") || 0;
      total_sale =
        Math.round(
          (parseFloat(total_sale) + parseFloat(price) * parseFloat(quantity)) *
            100
        ) / 100;

      sessionStorage.setItem("total_sale", total_sale);
      sessionStorage.setItem("cart_shop", JSON.stringify(cart_shop));

      $("#lblTotalCartShopMain").text("$" + total_sale); // Actualizar el total

      // Actualizar el contenido del carrito en el offcanvas
      updateCartOffcanvas(cart_shop);

      doneToast("Añadido al carrito"); // Mostrar mensaje de éxito
    } else {
      errorToast("Este producto ya está en el carrito"); // Mostrar mensaje de error
    }
  });

  // Función para actualizar el contenido del carrito en el offcanvas
  function updateCartOffcanvas(cart_shop) {
    let totalPrice = 0;
    let totalQuantity = 0;
    let cartContent = "";

    // Verificar si hay productos en el carrito
    if (cart_shop.length > 0) {
      cart_shop.forEach((item, index) => {
        const subtotal = parseFloat(item.quantity) * parseFloat(item.price);
        totalPrice += subtotal;
        totalQuantity += parseInt(item.quantity);

        cartContent += `
        <li class="cart-item list-group-item d-flex align-items-center justify-content-between lh-sm">
          <!-- Botón para eliminar producto -->
          <button class="btn btn-danger btn-sm removeFromCart" data-index="${index}">
            <i class="fas fa-trash-alt"></i>
          </button>
              
          <div class="d-flex flex-column">
            <h6 class="my-0">${item.product_name || "Producto sin nombre"}</h6>
            <small class="text-body-secondary">Cantidad: ${
              item.quantity
            }</small>
          </div>
              
          <!-- Precio del producto -->
          <span class="text-body-secondary">$${item.price}</span>
        </li>

        `;
      });

      // Añadir el total al final
      cartContent += `
        <li class="list-group-item d-flex justify-content-between">
            <span>Total (MXN)</span>
            <strong>$${totalPrice.toFixed(2)}</strong>
        </li>
      `;

      // Actualizar el contenido del carrito en el offcanvas
      $(".offcanvas-body .list-group").html(cartContent);
      $(".badge.bg-primary").text(totalQuantity); // Actualizar contador en el icono del carrito
    } else {
      // Si el carrito está vacío, mostrar mensaje
      $(".offcanvas-body .list-group").html(
        '<li class="list-group-item">Tu carrito está vacío</li>'
      );
      $(".badge.bg-primary").text("0"); // Mostrar 0 si no hay productos
    }
  }

  $(document).on("click", ".removeFromCart", function () {
    // Obtener el índice del producto a eliminar
    const index = $(this).data("index");

    // Obtener el carrito actual
    let cart_shop = JSON.parse(sessionStorage.getItem("cart_shop")) || [];

    // Verificar si el índice es válido antes de proceder
    if (index >= 0 && index < cart_shop.length) {
      // Obtener el producto que se va a eliminar para calcular el total
      const productToRemove = cart_shop[index];

      // Calcular el total antes de eliminar el producto
      let total_sale = sessionStorage.getItem("total_sale") || 0;
      total_sale =
        Math.round(
          (parseFloat(total_sale) -
            parseFloat(productToRemove.price) *
              parseFloat(productToRemove.quantity)) *
            100
        ) / 100;

      // Eliminar el producto del carrito
      cart_shop.splice(index, 1);

      // Actualizar el carrito en sessionStorage
      sessionStorage.setItem("cart_shop", JSON.stringify(cart_shop));
      sessionStorage.setItem("total_sale", total_sale);

      // Actualizar el total en el carrito
      $("#lblTotalCartShopMain").text("$" + total_sale); // Actualizar el total

      // Volver a mostrar el carrito actualizado
      updateCartOffcanvas(cart_shop);
      doneToast("Producto eliminado del carrito"); // Mostrar mensaje de éxito
    } else {
      errorToast("No se pudo eliminar el producto"); // Mensaje de error si el índice no es válido
    }
  });

  $(document).on("change", "#slct-brand", function (event) {
    loading();
    let id_brand = $(this).val();
    window.location.href = `brand_products.php?filter=brand&brand=${id_brand}`;
  });

  $(document).on("change", "#slct-category", function (event) {
    loading();
    let id_category = $(this).val();

    window.location.href = `category_products.php?filter=category&cat=${id_category}`;
  });
  $(document).on("click", "#goToCheckCart", function () {
    loading();
    window.location.href = `cart_shop.php`;
  });
  

  // Obtén el formulario y el campo de entrada
  const search_form = $("#search-form");
  const searchInput = $("#search-input");
  const searchButton = $("#searchButton");

  // Evento al enviar el formulario
  search_form.on("submit", function (event) {
    loading();
    event.preventDefault();
    console.log("Texto ingresado en la búsqueda: " + searchInput.val());
    let searchValue = searchInput.val();
    if (searchValue.length > 0) {
      window.location.href = `all_products.php?search=${searchValue}`;
    }
  });

  // Evento al hacer click en el icono de búsqueda (ahora en el botón)
  searchButton.on("click", function () {
    loading();
    console.log("Texto ingresado en la búsqueda: " + searchInput.val());

    let searchValue = searchInput.val();
    if (searchValue.length > 0) {
      window.location.href = `all_products.php?search=${searchValue}`;
    }
  });

  // Verificar si el carrito existe en sessionStorage
  const cart = JSON.parse(sessionStorage.getItem("cart_shop")) || [];

  // Función para validar la estructura de los productos en el carrito
  function isValidCartItem(item) {
    return (
      item &&
      item.hasOwnProperty("id_product") &&
      item.hasOwnProperty("quantity") &&
      item.hasOwnProperty("price") &&
      !isNaN(item.quantity) &&
      !isNaN(item.price)
    );
  }
  // Verificar si hay productos en el carrito
  if (cart.length > 0) {
    let totalPrice = 0;
    let totalQuantity = 0;
    let cartContent = "";

    // Filtrar solo los productos con la estructura válida
    const validCartItems = cart.filter(isValidCartItem);

    // Si hay productos válidos, generamos el contenido HTML
    if (validCartItems.length > 0) {
      validCartItems.forEach((item, index) => {
        const subtotal = parseFloat(item.quantity) * parseFloat(item.price);
        totalPrice += subtotal;
        totalQuantity += parseInt(item.quantity);

        cartContent += `
        <li class="cart-item list-group-item d-flex align-items-center justify-content-between lh-sm">
          <!-- Botón para eliminar producto -->
          <button class="btn btn-danger btn-sm removeFromCart" data-index="${index}">
            <i class="fas fa-trash-alt"></i>
          </button>

          <div class="d-flex flex-column">
            <h6 class="my-0">${item.product_name || "Producto sin nombre"}</h6>
            <small class="text-body-secondary">Cantidad: ${
              item.quantity
            }</small>
          </div>

          <!-- Precio del producto -->
          <span class="text-body-secondary">$${item.price}</span>
        </li>

        `;
      });

      // Añadir el total al final
      cartContent += `
      <li class="list-group-item d-flex justify-content-between">
          <span>Total (MXN)</span>
          <strong>$${totalPrice.toFixed(2)}</strong>
      </li>
    `;

      // Actualiza el contenido del carrito en el offcanvas
      $(".offcanvas-body .list-group").html(cartContent);

      // Actualiza el contador de productos en el carrito
      $(".badge.bg-primary").text(totalQuantity);
    } else {
      // Si no hay productos válidos, muestra un mensaje de error
      $(".offcanvas-body .list-group").html(
        '<li class="list-group-item">Carrito contiene productos inválidos o faltantes</li>'
      );
      $(".badge.bg-primary").text("0");
    }
  } else {
    // Si el carrito está vacío, muestra un mensaje
    $(".offcanvas-body .list-group").html(
      '<li class="list-group-item">Tu carrito está vacío</li>'
    );
    $(".badge.bg-primary").text("0");
  }

  function doneToast(text) {
    Toastify({
      text: text,
      duration: 3000,
      destination: "cart_shop.php",
      newWindow: true,
      close: true,
      gravity: "top", // `top` or `bottom`
      position: "right", // `left`, `center` or `right`
      stopOnFocus: true, // Prevents dismissing of toast on hover
      style: {
        background: "#05b025",
        //background: "linear-gradient(to right, #00b09b, #96c93d)",
      },
      onClick: function () {}, // Callback after click
    }).showToast();
  }

  function errorToast(text) {
    Toastify({
      text: text,
      duration: 3000,
      newWindow: true,
      close: true,
      gravity: "top", // `top` or `bottom`
      position: "left", // `left`, `center` or `right`
      stopOnFocus: true, // Prevents dismissing of toast on hover
      style: {
        background: "#ff3333",
        //background: "linear-gradient(to right, #00b09b, #96c93d)",
      },
      onClick: function () {}, // Callback after click
    }).showToast();
  }

  function loading() {
    Swal.fire({
      title: "Cargando...",
      html: '<img src="images/paint-loading-2.gif" width="300" height="175">',
      allowOutsideClick: false,
      allowEscapeKey: false,
      showCloseButton: false,
      showCancelButton: false,
      showConfirmButton: false,
    });
  }
});
