$(document).ready(function () {
  let limitSales = 10;
  let searchInput = "";
  let actualPage = 1;
  loadSalesHistory(limitSales, searchInput, actualPage);

  $(document).on("change", "#numProducts", function (event) {
    loading();
    limitSales = $(this).val();

    loadSalesHistory(limitSales, searchInput, actualPage);
    //--- --- ---//
  });
  $(document).on("click", ".changePage", function (event) {
    loading();
    actualPage = $(this).text();

    loadSalesHistory(limitSales, searchInput, actualPage);
    //--- --- ---//
  });

  $(document).on("keyup", "#searchProd", function (e) {
    console.log(e.which);
    if (e.which == 13) {
      loading();
      searchInput = $(this).val();

      loadSalesHistory(limitSales, searchInput, actualPage);
      return false;
    }
    //--- --- ---//
  });

  function loadSalesHistory(limitSales, searchInput, actualPage) {
    if (actualPage != null) {
      actualPage = actualPage;
    }
    //console.log(actualPage);

    $.ajax({
      url: "php/controllers/sales/sales_controller.php",
      method: "POST",
      data: {
        mod: "getSalesTableFactura",
        limit: limitSales,
        searchInput: searchInput,
        actualPage: actualPage,
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        //console.log(data);
        if (data.response == true) {
          $("#tableSales > tbody").html(data.html);
          $("#lblTotal").html(
            "Mostrando " +
              data.totalFiltered +
              " de un total de  " +
              data.totalProds +
              " registros"
          );
          $("#navPagination").html(data.paginationNav);

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
  }

  $(document).on("click", ".getSaleDetail", function () {
    loading();

    var id_sale = $(this).attr("data-id-order");
    $.ajax({
      url: "php/controllers/sales/sales_controller.php",
      method: "POST",
      data: {
        mod: "getSaleDetail",
        id_sale: id_sale,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        console.log(data);
        Swal.close();
        if (data.response == true) {
          $(".body-detail-sale").html(data.html);
        } else {
          Swal.fire({
            title: data.message,
            icon: "error",
          });
        }
      })
      .fail(function (message) {
        Swal.fire({
          title: "No se pudoo completar el proceso!",
          icon: "error",
        });
      });
  });
  $(document).on("click", ".generateFactura", function () {
    loading();

    var id_sale = $(this).attr("data-id-order");
    $("#btnGenerarFactura").attr("data-id-order", id_sale);
    Swal.close();
  });

  $(document).on("click", "#btnGenerarFactura", function () {
    var date = new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString();
    date_fact = date.slice(0, -5);
    console.log(date_fact);
    loading();
    var id_sale = $(this).attr("data-id-order");

    var razon_social = $("#razon_social").val();
    var rfc = $("#rfc").val();
    var email_receptor = $("#email_receptor").val();
    var street = $("#street").val();
    var ext_num = $("#ext_num").val();
    var int_num = $("#int_num").val();
    var colony = $("#colony").val();
    var locality = $("#locality").val();
    var zipcode = $("#zipcode").val();
    var selectState = $("#selectState").val();
    var selectCity = $("#selectCity").val();
    var uso_cfdi = $("#select-uso-cfdi").val();
    var reg_fiscal = $("#select-reg-fiscal").val();

    if (
      razon_social == "" ||
      razon_social == null ||
      razon_social == undefined ||
      rfc == "" ||
      rfc == null ||
      rfc == undefined ||
      email_receptor == "" ||
      email_receptor == null ||
      email_receptor == undefined ||
      street == "" ||
      street == null ||
      street == undefined ||
      ext_num == "" ||
      ext_num == null ||
      ext_num == undefined ||
      int_num == "" ||
      int_num == null ||
      int_num == undefined ||
      colony == "" ||
      colony == null ||
      colony == undefined ||
      locality == "" ||
      locality == null ||
      locality == undefined ||
      zipcode == "" ||
      zipcode == null ||
      zipcode == undefined ||
      selectState == undefined ||
      selectState == null ||
      selectState == undefined ||
      selectCity == undefined ||
      selectCity == null ||
      selectCity == undefined ||
      uso_cfdi == null ||
      uso_cfdi == null ||
      uso_cfdi == undefined ||
      reg_fiscal == null ||
      reg_fiscal == null ||
      reg_fiscal == undefined
    ) {
      Swal.fire({
        title: "Atención!",
        text: "Debe ingresar todos los datos para poder generar correctamente la factura!!",
        icon: "error",
      });
    } else {
     /*  $.ajax({
        url: "php/controllers/sales/sales_controller.php",
        method: "POST",
        data: {
          mod: "getSaleDetailFactura",
          id_sale: id_sale,
        },
      })
        .done(function (data) {
          var data = JSON.parse(data);
          console.log(data);
          Swal.close();
          if (data.response == true) {
            $(".body-detail-sale").html(data.html);
          } else {
            Swal.fire({
              title: data.message,
              icon: "error",
            });
          }
        })
        .fail(function (message) {
          Swal.fire({
            title: "No se pudoo completar el proceso!",
            icon: "error",
          });
        });
 */
        

      var data_receptor = {
        RFC: rfc,
        NombreRazonSocial: razon_social,
        UsoCFDI: uso_cfdi,
        DomicilioFiscalReceptor: zipcode,
        RegimenFiscal: reg_fiscal,
        Direccion: {
          Calle: street,
          NumeroExterior: ext_num,
          NumeroInterior: int_num,
          Colonia: colony,
          Localidad: locality,
          Municipio: selectCity,
          Estado: selectState,
          Pais: "Mexico",
          CodigoPostal: zipcode,
        },
      };


      var bodyParms = {
        "DatosGenerales": {
          "Version": "4.0",
          "CSD": "MIIFsDCCA5igAwIBAgIUMzAwMDEwMDAwMDA1MDAwMDM0MTYwDQYJKoZIhvcNAQELBQAwggErMQ8wDQYDVQQDDAZBQyBVQVQxLjAsBgNVBAoMJVNFUlZJQ0lPIERFIEFETUlOSVNUUkFDSU9OIFRSSUJVVEFSSUExGjAYBgNVBAsMEVNBVC1JRVMgQXV0aG9yaXR5MSgwJgYJKoZIhvcNAQkBFhlvc2Nhci5tYXJ0aW5lekBzYXQuZ29iLm14MR0wGwYDVQQJDBQzcmEgY2VycmFkYSBkZSBjYWxpejEOMAwGA1UEEQwFMDYzNzAxCzAJBgNVBAYTAk1YMRkwFwYDVQQIDBBDSVVEQUQgREUgTUVYSUNPMREwDwYDVQQHDAhDT1lPQUNBTjERMA8GA1UELRMIMi41LjQuNDUxJTAjBgkqhkiG9w0BCQITFnJlc3BvbnNhYmxlOiBBQ0RNQS1TQVQwHhcNMjMwNTE4MTE0MzUxWhcNMjcwNTE4MTE0MzUxWjCB1zEnMCUGA1UEAxMeRVNDVUVMQSBLRU1QRVIgVVJHQVRFIFNBIERFIENWMScwJQYDVQQpEx5FU0NVRUxBIEtFTVBFUiBVUkdBVEUgU0EgREUgQ1YxJzAlBgNVBAoTHkVTQ1VFTEEgS0VNUEVSIFVSR0FURSBTQSBERSBDVjElMCMGA1UELRMcRUtVOTAwMzE3M0M5IC8gVkFEQTgwMDkyN0RKMzEeMBwGA1UEBRMVIC8gVkFEQTgwMDkyN0hTUlNSTDA1MRMwEQYDVQQLEwpTdWN1cnNhbCAxMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtmecO6n2GS0zL025gbHGQVxznPDICoXzR2uUngz4DqxVUC/w9cE6FxSiXm2ap8Gcjg7wmcZfm85EBaxCx/0J2u5CqnhzIoGCdhBPuhWQnIh5TLgj/X6uNquwZkKChbNe9aeFirU/JbyN7Egia9oKH9KZUsodiM/pWAH00PCtoKJ9OBcSHMq8Rqa3KKoBcfkg1ZrgueffwRLws9yOcRWLb02sDOPzGIm/jEFicVYt2Hw1qdRE5xmTZ7AGG0UHs+unkGjpCVeJ+BEBn0JPLWVvDKHZAQMj6s5Bku35+d/MyATkpOPsGT/VTnsouxekDfikJD1f7A1ZpJbqDpkJnss3vQIDAQABox0wGzAMBgNVHRMBAf8EAjAAMAsGA1UdDwQEAwIGwDANBgkqhkiG9w0BAQsFAAOCAgEAFaUgj5PqgvJigNMgtrdXZnbPfVBbukAbW4OGnUhNrA7SRAAfv2BSGk16PI0nBOr7qF2mItmBnjgEwk+DTv8Zr7w5qp7vleC6dIsZFNJoa6ZndrE/f7KO1CYruLXr5gwEkIyGfJ9NwyIagvHHMszzyHiSZIA850fWtbqtythpAliJ2jF35M5pNS+YTkRB+T6L/c6m00ymN3q9lT1rB03YywxrLreRSFZOSrbwWfg34EJbHfbFXpCSVYdJRfiVdvHnewN0r5fUlPtR9stQHyuqewzdkyb5jTTw02D2cUfL57vlPStBj7SEi3uOWvLrsiDnnCIxRMYJ2UA2ktDKHk+zWnsDmaeleSzonv2CHW42yXYPCvWi88oE1DJNYLNkIjua7MxAnkNZbScNw01A6zbLsZ3y8G6eEYnxSTRfwjd8EP4kdiHNJftm7Z4iRU7HOVh79/lRWB+gd171s3d/mI9kte3MRy6V8MMEMCAnMboGpaooYwgAmwclI2XZCczNWXfhaWe0ZS5PmytD/GDpXzkX0oEgY9K/uYo5V77NdZbGAjmyi8cE2B2ogvyaN2XfIInrZPgEffJ4AB7kFA2mwesdLOCh0BLD9itmCve3A1FGR4+stO2ANUoiI3w3Tv2yQSg4bjeDlJ08lXaaFCLW2peEXMXjQUk7fmpb5MNuOUTW6BE=",
          "LlavePrivada": "MIIFDjBABgkqhkiG9w0BBQ0wMzAbBgkqhkiG9w0BBQwwDgQIAgEAAoIBAQACAggAMBQGCCqGSIb3DQMHBAgwggS/AgEAMASCBMh4EHl7aNSCaMDA1VlRoXCZ5UUmqErAbucoZQObOaLUEm+I+QZ7Y8Giupo+F1XWkLvAsdk/uZlJcTfKLJyJbJwsQYbSpLOCLataZ4O5MVnnmMbfG//NKJn9kSMvJQZhSwAwoGLYDm1ESGezrvZabgFJnoQv8Si1nAhVGTk9FkFBesxRzq07dmZYwFCnFSX4xt2fDHs1PMpQbeq83aL/PzLCce3kxbYSB5kQlzGtUYayiYXcu0cVRu228VwBLCD+2wTDDoCmRXtPesgrLKUR4WWWb5N2AqAU1mNDC+UEYsENAerOFXWnmwrcTAu5qyZ7GsBMTpipW4Dbou2yqQ0lpA/aB06n1kz1aL6mNqGPaJ+OqoFuc8Ugdhadd+MmjHfFzoI20SZ3b2geCsUMNCsAd6oXMsZdWm8lzjqCGWHFeol0ik/xHMQvuQkkeCsQ28PBxdnUgf7ZGer+TN+2ZLd2kvTBOk6pIVgy5yC6cZ+o1Tloql9hYGa6rT3xcMbXlW+9e5jM2MWXZliVW3ZhaPjptJFDbIfWxJPjz4QvKyJk0zok4muv13Iiwj2bCyefUTRz6psqI4cGaYm9JpscKO2RCJN8UluYGbbWmYQU+Int6LtZj/lv8p6xnVjWxYI+rBPdtkpfFYRp+MJiXjgPw5B6UGuoruv7+vHjOLHOotRo+RdjZt7NqL9dAJnl1Qb2jfW6+d7NYQSI/bAwxO0sk4taQIT6Gsu/8kfZOPC2xk9rphGqCSS/4q3Os0MMjA1bcJLyoWLp13pqhK6bmiiHw0BBXH4fbEp4xjSbpPx4tHXzbdn8oDsHKZkWh3pPC2J/nVl0k/yF1KDVowVtMDXE47k6TGVcBoqe8PDXCG9+vjRpzIidqNo5qebaUZu6riWMWzldz8x3Z/jLWXuDiM7/Yscn0Z2GIlfoeyz+GwP2eTdOw9EUedHjEQuJY32bq8LICimJ4Ht+zMJKUyhwVQyAER8byzQBwTYmYP5U0wdsyIFitphw+/IH8+v08Ia1iBLPQAeAvRfTTIFLCs8foyUrj5Zv2B/wTYIZy6ioUM+qADeXyo45uBLLqkN90Rf6kiTqDld78NxwsfyR5MxtJLVDFkmf2IMMJHTqSfhbi+7QJaC11OOUJTD0v9wo0X/oO5GvZhe0ZaGHnm9zqTopALuFEAxcaQlc4R81wjC4wrIrqWnbcl2dxiBtD73KW+wcC9ymsLf4I8BEmiN25lx/OUc1IHNyXZJYSFkEfaxCEZWKcnbiyf5sqFSSlEqZLc4lUPJFAoP6s1FHVcyO0odWqdadhRZLZC9RCzQgPlMRtji/OXy5phh7diOBZv5UYp5nb+MZ2NAB/eFXm2JLguxjvEstuvTDmZDUb6Uqv++RdhO5gvKf/AcwU38ifaHQ9uvRuDocYwVxZS2nr9rOwZ8nAh+P2o4e0tEXjxFKQGhxXYkn75H3hhfnFYjik/2qunHBBZfcdG148MaNP6DjX33M238T9Zw/GyGx00JMogr2pdP4JAErv9a5yt4YR41KGf8guSOUbOXVARw6+ybh7+meb7w4BeTlj3aZkv8tVGdfIt3lrwVnlbzhLjeQY6PplKp3/a5Kr5yM0T4wJoKQQ6v3vSNmrhpbuAtKxpMILe8CQoo=",
          "CSDPassword": "12345678a",
          "GeneraPDF": true,
          "Logotipo": "",
          "CFDI": "Factura",
          "OpcionDecimales": "1",
          "NumeroDecimales": "2",
          "TipoCFDI": "Ingreso",
          "EnviaEmail": true,
          "ReceptorEmail": "micorreo@midominio.com",
          "ReceptorCC": "",
          "ReceptorCCO": "",
          "EmailMensaje": "prueba de envio y generacion de factura por rest api desde el servicio de timbrado de FacturoPorTi"
        },
        "Encabezado": {
          "CFDIsRelacionados":"",
          "TipoRelacion":"04",
          "Emisor": {
            "RFC": "EKU9003173C9",
            "NombreRazonSocial": "ESCUELA KEMPER URGATE",
            "RegimenFiscal": "601",
            "Direccion": [
              {
                "Calle": "Serapio Rendon 1",
                "NumeroExterior": "122",
                "NumeroInterior": "5",
                "Colonia": "San Rafael",
                "Localidad": "CDMX",
                "Municipio": "Cuauhtemoc",
                "Estado": "Ciudad de Mexico",
                "Pais": "Mexico",
                "CodigoPostal": "06470"
              }
            ]
          },
          "Receptor": data_receptor,
          "Fecha": date_fact,
          "Serie": "AB",
          "Folio": "102",
          "MetodoPago": "PUE",
          "FormaPago": "01",
          "Moneda": "MXN",
          "LugarExpedicion": "06470",
          "SubTotal": "100.00",
          "Total": "116"
        },
        "Conceptos": [
          {
            "Cantidad": "1",
            "CodigoUnidad": "E48",
            "Unidad": "Servicio",
            "CodigoProducto": "84111506",
            "Producto": "Timbres de Facturacion",
            "PrecioUnitario": "100",
            "Importe": "100",
            "ObjetoDeImpuesto":"02",
            "Impuestos": [
              {
                "TipoImpuesto": "1",
                "Impuesto": "2",
                "Factor": "1",
                "Base": "100",
                "Tasa": "0.160000",
                "ImpuestoImporte": "16"
              }
            ]
          }
        ]
      };

      consumeAPI(bodyParms);
    }

    /*   */
  });

  function consumeAPI(bodyParms) {
    loading();
    console.log("Consuming API");

    const options = { method: "GET", headers: { accept: "application/json" } };
    var token = "";
    fetch(
      "https://testapi.facturoporti.com.mx/token/crear?Usuario=PruebasTimbrado&Password=@Notiene1",
      options
    )
      .then((response) => response.json())
      .then(function (response) {
        token = response.token;
        console.log(token);
        const logo = getMainLogo();

        // URL de la API para obtener facturas
        const apiUrl =
          "https://testapi.facturoporti.com.mx/servicios/timbrar/json";

        // Token de autenticación (suponiendo que uses un token de API para autenticarte)
        const apiToken = token;

        const bodyParams = bodyParms;
        // Configuración de la solicitud
        const requestOptions = {
          method: "POST", // Método HTTP
          headers: {
            Authorization: `Bearer ${apiToken}`, // Token de autenticación
            "Content-Type": "application/json", // Tipo de contenido
          },
          body: JSON.stringify(bodyParams), // Convertir los parámetros a formato JSON
        };

        // Realizar la solicitud
        fetch(apiUrl, requestOptions)
          .then((response) => {
            if (!response.ok) {
              // Manejar errores de respuesta HTTP
              throw new Error(
                "Network response was not ok " + response.statusText
              );
            }
            return response.json(); // Parsear la respuesta a JSON
          })
          .then((data) => {
            // Manejar los datos de la respuesta
            console.log("Datos de facturas:", data.cfdiTimbrado.respuesta.pdf);
            base64ToPDF(data.cfdiTimbrado.respuesta.pdf, "FACTURA COMPRA");
            Swal.close();
          })
          .catch((error) => {
            // Manejar errores
            console.error("Hubo un problema con la solicitud:", error);
          });
      })
      .catch((err) => console.error(err));
  }

  function base64ToPDF(base64String, fileName) {
    // Convertir la cadena base64 a un Uint8Array
    const byteCharacters = atob(base64String);
    const byteNumbers = new Array(byteCharacters.length);
    for (let i = 0; i < byteCharacters.length; i++) {
      byteNumbers[i] = byteCharacters.charCodeAt(i);
    }
    const byteArray = new Uint8Array(byteNumbers);

    // Crear un Blob a partir del Uint8Array
    const blob = new Blob([byteArray], { type: "application/pdf" });

    // Crear un enlace de descarga
    const link = document.createElement("a");
    link.href = window.URL.createObjectURL(blob);
    link.download = fileName;
    document.body.appendChild(link);

    // Hacer clic en el enlace para iniciar la descarga
    link.click();

    // Eliminar el enlace del DOM
    document.body.removeChild(link);
  }

  $(document).on("change", "#selectState", function () {
    loading();
    var id_estado = this.value;
    $.ajax({
      url: "php/controllers/colabs/colab_controller.php",
      method: "POST",
      data: {
        mod: "getMunicipios",
        id_estado: id_estado,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        console.log(data);
        $("#selectCity").prop("disabled", false);
        if (data.response == true) {
          $("#selectCity").empty();
          $("#selectCity").append(
            '<option value="">Seleccione un municipio</option>'
          );
          for (var i = 0; i < data.data.length; i++) {
            $("#selectCity").append(
              '<option value="' +
                data.data[i].id +
                '">' +
                data.data[i].municipio +
                "</option>"
            );
          }
          Swal.close();
        } else {
          Swal.fire({
            icon: "error",
            title: "Verifique los datos ingresados",
          });
        }

        //--- --- ---//
        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
  });

  $("#selectState").select2({
    dropdownParent: $("#modalReceptorData"),
  });
  $("#selectCity").select2({
    dropdownParent: $("#modalReceptorData"),
  });
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
function doneToast(text) {
  Toastify({
    text: text,
    duration: 3000,
    destination: "https://github.com/apvarun/toastify-js",
    newWindow: true,
    close: true,
    gravity: "top", // `top` or `bottom`
    position: "right", // `left`, `center` or `right`
    stopOnFocus: true, // Prevents dismissing of toast on hover
    style: {
      background: "#00b09b",
      //background: "linear-gradient(to right, #00b09b, #96c93d)",
    },
    onClick: function () {}, // Callback after click
  }).showToast();
}
function errorToast(text) {
  Toastify({
    text: text,
    duration: 3000,
    destination: "https://github.com/apvarun/toastify-js",
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
