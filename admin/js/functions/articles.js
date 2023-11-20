$(document).ready(function () {
  $(document).on("click", "#saveNewProduct", function () {
    var prod_code = $("#prod_code").val().trim();
    var prod_name = $("#prod_name").val().trim();
    var prod_brand = $("#prod_brand option:selected").val();
    var prod_sku = $("#prod_sku").val();
    var prod_barcode = $("#prod_barcode").val();
    var prod_meassure = $("#prod_meassure option:selected").val();
    var prod_purchase_price = $("#prod_purchase_price").val();
    var prod_price = $("#prod_price").val();
    var prod_bulk = 0;
    if ($("#prod_bulk").is(":checked")) {
      prod_bulk = 1;
    }
    var prod_stock = $("#prod_stock").val();
    var prod_min_stock = $("#prod_min_stock").val();
    var prod_max_stock = $("#prod_max_stock").val();
    var prod_description = $("#prod_description").val();
    const prod_image = document.querySelector("#prod_image");

    console.log("prod_code: " + prod_code);
    console.log("prod_name: " + prod_name);
    console.log("prod_brand: " + prod_brand);
    console.log("prod_sku: " + prod_sku);
    console.log("prod_barcode: " + prod_barcode);
    console.log("prod_meassure: " + prod_meassure);
    console.log("prod_purchase_price: " + prod_purchase_price);
    console.log("prod_price: " + prod_price);
    console.log("prod_bulk: " + prod_bulk);
    console.log("prod_stock: " + prod_stock);
    console.log("prod_min_stock: " + prod_min_stock);
    console.log("prod_max_stock: " + prod_max_stock);
    console.log("prod_description: " + prod_description);
    console.log("prod_image: " + prod_image.files[0]);
    if (
      prod_code != null &&
      prod_code != "" &&
      prod_code != undefined &&
      prod_name != null &&
      prod_name != "" &&
      prod_name != undefined &&
      prod_brand != null &&
      prod_brand != "" &&
      prod_brand != undefined &&
      prod_sku != null &&
      prod_sku != "" &&
      prod_sku != undefined &&
      prod_barcode != null &&
      prod_barcode != "" &&
      prod_barcode != undefined &&
      prod_meassure != null &&
      prod_meassure != "" &&
      prod_meassure != undefined &&
      prod_purchase_price != null &&
      prod_purchase_price != "" &&
      prod_purchase_price != undefined &&
      prod_price != null &&
      prod_price != "" &&
      prod_price != undefined &&
      prod_stock != null &&
      prod_stock != "" &&
      prod_stock != undefined &&
      prod_min_stock != null &&
      prod_min_stock != "" &&
      prod_min_stock != undefined &&
      prod_max_stock != null &&
      prod_max_stock != "" &&
      prod_max_stock != undefined &&
      prod_description != null &&
      prod_description != "" &&
      prod_description != undefined &&
      prod_image.files.length > 0
    ) {
      saveNewProd();
    } else {
      Swal.fire({
        title: "No puede dejar campos obligatorios vacíos!!!",
        icon: "error",
      });
    }
  });

  $(document).on("click", ".btnGenerateBarcode", function () {
    var product_barcode = $(this).attr("data-barcode");
    JsBarcode("#barcodeImg", product_barcode, { format: "itf14" });

    /* printBarcode(); */
  });

  function saveNewProd() {
    var prod_code = $("#prod_code").val().trim();
    var prod_name = $("#prod_name").val().trim();
    var prod_brand = $("#prod_brand option:selected").val();
    var prod_sku = $("#prod_sku").val();
    var prod_barcode = $("#prod_barcode").val();
    var prod_meassure = $("#prod_meassure option:selected").val();
    var prod_purchase_price = $("#prod_purchase_price").val();
    var prod_price = $("#prod_price").val();
    var prod_bulk = 0;
    if ($("#prod_bulk").is(":checked")) {
      prod_bulk = 1;
    }
    var prod_stock = $("#prod_stock").val();
    var prod_min_stock = $("#prod_min_stock").val();
    var prod_max_stock = $("#prod_max_stock").val();
    var prod_description = $("#prod_description").val();
    const prod_image = document.querySelector("#prod_image");

    if (
      prod_code != null &&
      prod_code != "" &&
      prod_code != undefined &&
      prod_name != null &&
      prod_name != "" &&
      prod_name != undefined &&
      prod_brand != null &&
      prod_brand != "" &&
      prod_brand != undefined &&
      prod_sku != null &&
      prod_sku != "" &&
      prod_sku != undefined &&
      prod_barcode != null &&
      prod_barcode != "" &&
      prod_barcode != undefined &&
      prod_meassure != null &&
      prod_meassure != "" &&
      prod_meassure != undefined &&
      prod_purchase_price != null &&
      prod_purchase_price != "" &&
      prod_purchase_price != undefined &&
      prod_price != null &&
      prod_price != "" &&
      prod_price != undefined &&
      prod_stock != null &&
      prod_stock != "" &&
      prod_stock != undefined &&
      prod_min_stock != null &&
      prod_min_stock != "" &&
      prod_min_stock != undefined &&
      prod_max_stock != null &&
      prod_max_stock != "" &&
      prod_max_stock != undefined &&
      prod_description != null &&
      prod_description != "" &&
      prod_description != undefined &&
      prod_image.files.length > 0
    ) {
      let formData = new FormData();
      formData.append("prod_image", prod_image.files[0]);
      formData.append("prod_code", prod_code);
      formData.append("prod_name", prod_name);
      formData.append("prod_brand", prod_brand);
      formData.append("prod_sku", prod_sku);
      formData.append("prod_barcode", prod_barcode);
      formData.append("prod_meassure", prod_meassure);
      formData.append("prod_price", prod_price);
      formData.append("prod_purchase_price", prod_purchase_price);
      formData.append("prod_bulk", prod_bulk);
      formData.append("prod_stock", prod_stock);
      formData.append("prod_min_stock", prod_min_stock);
      formData.append("prod_max_stock", prod_max_stock);
      formData.append("prod_description", prod_description);
      formData.append("prod_image", prod_image);

      fetch("php/controllers/articles/articles_controller.php", {
        method: "POST",
        function: "saveNewProd",
        body: formData,
      })
        .then((respuesta) => respuesta.json())
        .then((decodificado) => {
          loading();
          Swal.fire({
            icon: "success",
            title: "Éxito",
            text: "Registro guardado exitosamente",
            timer: 3000,
          }).then((result) => {
            loading();
            location.reload();
          });
        });
    }
  }

  function printBarcode() {
    // printJS('printable', 'html')

    let printFrame = document.createElement("iframe");
    let printableElement = document.getElementById("barcodeImg");
    //
    // // printframe.setattribute("style", "visibility: hidden; height: 0; width: 0; position: absolute;")
    printFrame.setAttribute("id", "printjs");
    printFrame.srcdoc =
      "<html><head><title>document</title></head><body style='margin: 0;'>" +
      printableElement.outerHTML +
      "<style>@page { size: A4; } #barcodeImg { margin-left: 2.85cm; width: 1.6cm; height: 0.1cm; } #barcodeImg .barcode { width: 100%; }</style> </body></html>";

    document.body.appendChild(printFrame);

    let iframeElement = document.getElementById("printjs");
    iframeElement.focus();
    iframeElement.contentWindow.print();
    //
    // printframe.contentwindow.print()
    //
    // my_window = window.open('', 'mywindow', 'status=1,width=350,height=150');
    // my_window.document.write('<html><head><title>Print Me</title></head>');
    // my_window.document.write('<body onafterprint="self.close()">');
    // my_window.document.write(printablEelement.innerHTML);
    // my_window.document.write('</body></html>');
    // my_window.print();
  }

  $(".js-example-basic-single").select2();

  $("#prod_brand").select2({
    dropdownParent: $("#modalNewArticle"),
  });
  $("#prod_meassure").select2({
    dropdownParent: $("#modalNewArticle"),
  });
});
