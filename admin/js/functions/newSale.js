$(document).ready(function () {
  $("#search_prod").on("keypress", function (event) {
    if (event.which == 13 && !event.shiftKey) {
      loading();
      var searchProd = $(this).val();
      $("#addProd").attr("data-barcode", searchProd);

      $.ajax({
        url: "php/controllers/sales/sales_controller.php",
        method: "POST",
        data: {
          mod: "searchProduct",
          searchProd: searchProd,
        },
      })
        .done(function (data) {
          Swal.close();
          var data = JSON.parse(data);
          console.log(data);
          if (data.response == true) {
            $("#prod_quantity").attr("disabled", false);
            $("#prod_name").val(data.prod_data[0].product_name);
            $("#prod_price").val(data.prod_data[0].price);
            $("#prod_stock").text("Stock: 4");
            Swal.close();
            /* doneToast(data.message); */
          } else {
            errorToast("Ocurrió un error");
          }

          //--- --- ---//
          //--- --- ---//
        })
        .fail(function (message) {
          Swal.close();
          var myToast = Toastify({
            text: data.message,
            duration: 3000,
          });
          myToast.showToast();
        });

      $(this).val("");
      event.preventDefault();
    }
  });
  $("#prod_quantity").on("keypress", function (event) {
    if (event.which == 13 && !event.shiftKey) {
      loading();
      var prod_quantity = $(this).val();
      var price = parseFloat($("#prod_price").val());
      var subtotal = prod_quantity * price;
      $("#prod_subtotal").val(subtotal);
      Swal.close();

      event.preventDefault();
    }
  });

  $("#addProd").on("click", function (event) {
    loading();
    var barcode = $(this).attr("data-barcode");
    var prod_description = $("#prod_name").val();
    var price = parseFloat($("#prod_price").val());
    var prod_quantity = $("#prod_quantity").val();
    var subtotal = $("#prod_subtotal").val();

    var html = "";
    var row_num = $("#tableSale").find('tr').length;
    row_num-1;
    html += " <tr> ";
    html += '    <th scope="row">'+row_num+'</th>';
    html += "    <td>"+barcode+"</td>";
    html += "    <td>"+prod_description+"</td>";
    html += "    <td>"+price+"</td>";
    html += "    <td>"+prod_quantity+"</td>";
    html += "    <td>"+subtotal+"</td>";
    html += "    <td>"+subtotal+"</td>";
    html += "</tr>";
    console.log(html);
    $("#tableSale > tbody").append(html);

    Swal.close();
  });

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

  $(".js-example-basic-single").select2();
});
