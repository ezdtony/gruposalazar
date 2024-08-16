let token = null;
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
    var date = new Date(
      new Date().getTime() - new Date().getTimezoneOffset() * 60000
    ).toISOString();
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
      loading();
      $.ajax({
        url: "php/controllers/sales/sales_controller.php",
        method: "POST",
        data: {
          mod: "getSaleDataFactura",
          id_sale: id_sale,
        },
      })
        .done(function (data) {
          var data = JSON.parse(data);
          console.log(data);
          if (data.response == true) {
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
            CSD_Test =
              "MIIFsDCCA5igAwIBAgIUMzAwMDEwMDAwMDA1MDAwMDM0MTYwDQYJKoZIhvcNAQELBQAwggErMQ8wDQYDVQQDDAZBQyBVQVQxLjAsBgNVBAoMJVNFUlZJQ0lPIERFIEFETUlOSVNUUkFDSU9OIFRSSUJVVEFSSUExGjAYBgNVBAsMEVNBVC1JRVMgQXV0aG9yaXR5MSgwJgYJKoZIhvcNAQkBFhlvc2Nhci5tYXJ0aW5lekBzYXQuZ29iLm14MR0wGwYDVQQJDBQzcmEgY2VycmFkYSBkZSBjYWxpejEOMAwGA1UEEQwFMDYzNzAxCzAJBgNVBAYTAk1YMRkwFwYDVQQIDBBDSVVEQUQgREUgTUVYSUNPMREwDwYDVQQHDAhDT1lPQUNBTjERMA8GA1UELRMIMi41LjQuNDUxJTAjBgkqhkiG9w0BCQITFnJlc3BvbnNhYmxlOiBBQ0RNQS1TQVQwHhcNMjMwNTE4MTE0MzUxWhcNMjcwNTE4MTE0MzUxWjCB1zEnMCUGA1UEAxMeRVNDVUVMQSBLRU1QRVIgVVJHQVRFIFNBIERFIENWMScwJQYDVQQpEx5FU0NVRUxBIEtFTVBFUiBVUkdBVEUgU0EgREUgQ1YxJzAlBgNVBAoTHkVTQ1VFTEEgS0VNUEVSIFVSR0FURSBTQSBERSBDVjElMCMGA1UELRMcRUtVOTAwMzE3M0M5IC8gVkFEQTgwMDkyN0RKMzEeMBwGA1UEBRMVIC8gVkFEQTgwMDkyN0hTUlNSTDA1MRMwEQYDVQQLEwpTdWN1cnNhbCAxMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtmecO6n2GS0zL025gbHGQVxznPDICoXzR2uUngz4DqxVUC/w9cE6FxSiXm2ap8Gcjg7wmcZfm85EBaxCx/0J2u5CqnhzIoGCdhBPuhWQnIh5TLgj/X6uNquwZkKChbNe9aeFirU/JbyN7Egia9oKH9KZUsodiM/pWAH00PCtoKJ9OBcSHMq8Rqa3KKoBcfkg1ZrgueffwRLws9yOcRWLb02sDOPzGIm/jEFicVYt2Hw1qdRE5xmTZ7AGG0UHs+unkGjpCVeJ+BEBn0JPLWVvDKHZAQMj6s5Bku35+d/MyATkpOPsGT/VTnsouxekDfikJD1f7A1ZpJbqDpkJnss3vQIDAQABox0wGzAMBgNVHRMBAf8EAjAAMAsGA1UdDwQEAwIGwDANBgkqhkiG9w0BAQsFAAOCAgEAFaUgj5PqgvJigNMgtrdXZnbPfVBbukAbW4OGnUhNrA7SRAAfv2BSGk16PI0nBOr7qF2mItmBnjgEwk+DTv8Zr7w5qp7vleC6dIsZFNJoa6ZndrE/f7KO1CYruLXr5gwEkIyGfJ9NwyIagvHHMszzyHiSZIA850fWtbqtythpAliJ2jF35M5pNS+YTkRB+T6L/c6m00ymN3q9lT1rB03YywxrLreRSFZOSrbwWfg34EJbHfbFXpCSVYdJRfiVdvHnewN0r5fUlPtR9stQHyuqewzdkyb5jTTw02D2cUfL57vlPStBj7SEi3uOWvLrsiDnnCIxRMYJ2UA2ktDKHk+zWnsDmaeleSzonv2CHW42yXYPCvWi88oE1DJNYLNkIjua7MxAnkNZbScNw01A6zbLsZ3y8G6eEYnxSTRfwjd8EP4kdiHNJftm7Z4iRU7HOVh79/lRWB+gd171s3d/mI9kte3MRy6V8MMEMCAnMboGpaooYwgAmwclI2XZCczNWXfhaWe0ZS5PmytD/GDpXzkX0oEgY9K/uYo5V77NdZbGAjmyi8cE2B2ogvyaN2XfIInrZPgEffJ4AB7kFA2mwesdLOCh0BLD9itmCve3A1FGR4+stO2ANUoiI3w3Tv2yQSg4bjeDlJ08lXaaFCLW2peEXMXjQUk7fmpb5MNuOUTW6BE=";
            privateKeyTest =
              "MIIFDjBABgkqhkiG9w0BBQ0wMzAbBgkqhkiG9w0BBQwwDgQIAgEAAoIBAQACAggAMBQGCCqGSIb3DQMHBAgwggS/AgEAMASCBMh4EHl7aNSCaMDA1VlRoXCZ5UUmqErAbucoZQObOaLUEm+I+QZ7Y8Giupo+F1XWkLvAsdk/uZlJcTfKLJyJbJwsQYbSpLOCLataZ4O5MVnnmMbfG//NKJn9kSMvJQZhSwAwoGLYDm1ESGezrvZabgFJnoQv8Si1nAhVGTk9FkFBesxRzq07dmZYwFCnFSX4xt2fDHs1PMpQbeq83aL/PzLCce3kxbYSB5kQlzGtUYayiYXcu0cVRu228VwBLCD+2wTDDoCmRXtPesgrLKUR4WWWb5N2AqAU1mNDC+UEYsENAerOFXWnmwrcTAu5qyZ7GsBMTpipW4Dbou2yqQ0lpA/aB06n1kz1aL6mNqGPaJ+OqoFuc8Ugdhadd+MmjHfFzoI20SZ3b2geCsUMNCsAd6oXMsZdWm8lzjqCGWHFeol0ik/xHMQvuQkkeCsQ28PBxdnUgf7ZGer+TN+2ZLd2kvTBOk6pIVgy5yC6cZ+o1Tloql9hYGa6rT3xcMbXlW+9e5jM2MWXZliVW3ZhaPjptJFDbIfWxJPjz4QvKyJk0zok4muv13Iiwj2bCyefUTRz6psqI4cGaYm9JpscKO2RCJN8UluYGbbWmYQU+Int6LtZj/lv8p6xnVjWxYI+rBPdtkpfFYRp+MJiXjgPw5B6UGuoruv7+vHjOLHOotRo+RdjZt7NqL9dAJnl1Qb2jfW6+d7NYQSI/bAwxO0sk4taQIT6Gsu/8kfZOPC2xk9rphGqCSS/4q3Os0MMjA1bcJLyoWLp13pqhK6bmiiHw0BBXH4fbEp4xjSbpPx4tHXzbdn8oDsHKZkWh3pPC2J/nVl0k/yF1KDVowVtMDXE47k6TGVcBoqe8PDXCG9+vjRpzIidqNo5qebaUZu6riWMWzldz8x3Z/jLWXuDiM7/Yscn0Z2GIlfoeyz+GwP2eTdOw9EUedHjEQuJY32bq8LICimJ4Ht+zMJKUyhwVQyAER8byzQBwTYmYP5U0wdsyIFitphw+/IH8+v08Ia1iBLPQAeAvRfTTIFLCs8foyUrj5Zv2B/wTYIZy6ioUM+qADeXyo45uBLLqkN90Rf6kiTqDld78NxwsfyR5MxtJLVDFkmf2IMMJHTqSfhbi+7QJaC11OOUJTD0v9wo0X/oO5GvZhe0ZaGHnm9zqTopALuFEAxcaQlc4R81wjC4wrIrqWnbcl2dxiBtD73KW+wcC9ymsLf4I8BEmiN25lx/OUc1IHNyXZJYSFkEfaxCEZWKcnbiyf5sqFSSlEqZLc4lUPJFAoP6s1FHVcyO0odWqdadhRZLZC9RCzQgPlMRtji/OXy5phh7diOBZv5UYp5nb+MZ2NAB/eFXm2JLguxjvEstuvTDmZDUb6Uqv++RdhO5gvKf/AcwU38ifaHQ9uvRuDocYwVxZS2nr9rOwZ8nAh+P2o4e0tEXjxFKQGhxXYkn75H3hhfnFYjik/2qunHBBZfcdG148MaNP6DjX33M238T9Zw/GyGx00JMogr2pdP4JAErv9a5yt4YR41KGf8guSOUbOXVARw6+ybh7+meb7w4BeTlj3aZkv8tVGdfIt3lrwVnlbzhLjeQY6PplKp3/a5Kr5yM0T4wJoKQQ6v3vSNmrhpbuAtKxpMILe8CQoo=";
            CSDPasswordTest = "12345678a";
            dataEmisorTest = {
              RFC: "EKU9003173C9",
              NombreRazonSocial: "ESCUELA KEMPER URGATE",
              RegimenFiscal: "601",
              Direccion: [
                {
                  Calle: "Serapio Rendon 1",
                  NumeroExterior: "122",
                  NumeroInterior: "5",
                  Colonia: "San Rafael",
                  Localidad: "CDMX",
                  Municipio: "Cuauhtemoc",
                  Estado: "Ciudad de Mexico",
                  Pais: "Mexico",
                  CodigoPostal: "06470",
                },
              ],
            };
            CPTest = "06470";
            console.log(data);
            conceptsTest = data.concepts;
            order_code = data.order_code;
            id_order = data.id_order;

            CSD_Prod =
              "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";
            privateKeyProd =
              "MIIFDjBABgkqhkiG9w0BBQ0wMzAbBgkqhkiG9w0BBQwwDgQIAgEAAoIBAQACAggAMBQGCCqGSIb3DQMHBAgwggS+AgEAMASCBMi+c+YqmejJ5ouDbO7jOzwn2ujPFnRXo0WOANBkziYN+ek5f1DIgO0nEvcbOWfSul6/CtjSpIQHpyrrVXcjszXFPZgxiSESUPxed1Sfl46XBHTjp4D5PSrfvsWVH9XprBogeSM3VDp8pJWY2L2neh4+yHqgJZgs4ECKOehmXE/tR5RVYo53QQU1DG8BT1OTwQrLarFVimnAwydu7UsX7kIHNrUMtHVBUvfQHRO+Z0Iwlsk3sLoQtTjL/c64xkYlXfD1RJWnbLkRRqDGn7sOFqOt6LKINDDAtPGaj/qkDcQhNHemGum/rUUEuudLHTLi2ZExwePd6kLFObBHJab70AoaAt3v8a11GKuvP0PYbn0bvnupQbIeIgpMTQbJc+pO7X38htUfWTbD+bEGh88sL8QPLtwTogODSEQ1Tsr6iF51SGAKwJb30SvSIKVmm+mT5B7pG4vHzEwr+GVDoCDHY25Mx5gkEN9b3GdHXlRjxIWBvedcfwxHpvoCnMsf0zSH5JcFSTksycxWPSeD6bi8XVSUJrfj08Dxrfd8GuH4pZ6kw0BhQHzeqhxeymEEgcP/aCmgSiKoboKleUPkTODk57ueA63N8sOmVfWJIyXnzaLMxTvR8tqPjJcbHVmtfm/OPThS4MJq448uhmrptgjSvtZdXBxSHftmkq4CoIxRGU2/1OF5Hl9OsLC3cBrnA0bY+AumqxVzYYVTY90LHLo6X9UA+89GAdMEML586msQU0D0Vl1ZarFCveL35LBpMYnbjuHwn2dgHD6cP415SJxHjagacJgJ4tkte6wNCHOGUDsdqvJAA7oXGv4VMMnzigYbtVCRJCapUBezDP6Hyx/OCSO5rAgBHac+3tRVr4YfwRnk3P6TfX3AgP5dtED0p+UZjI+TxWhplM6zYxEUIr5y/LTkTlnkALdYiYymmU6TrCrlhIvAERSK25FcvVLuDUOasZ5HUG0Bxf+BxZGrKIPA3KM+eDAcRKAZCBNsAgXwHunAKzUp7z75W3Nmr2hR+2oJEVC9Ic7s6mVKxBvRDgLRZaQTJIav/ZRZvgExdcPinY7IlyZkzYeXsnV9KB2yZBGtR+xxYWXM9ca/Q9WaewjumINOKyq55J8WEP2uT4l+jUt7Et0EYbHu0QafcfG6QRAZHmastRJU+keYEJKmBIfeWC/1qzI/PEoMaWDfKNGvHY/J3nArpQLUFSzVyAGtWyiLdxdf4JvfzJ3ZBDzC37hDvX6WKacHjRLmTSAUCAkyZ8Ba+0jHe6CTR2J8VPZKzSBhKNK+GouKh1Yv8WcXbwQfgNQ3Cuu3Yvlod21/BpRE8TVAPL+yWT6GSaU/JIHKmWSgBiF7YT3+n2+tNaKpCBu0JXJaQrSwTkW3qxGQdu/OadTcUj4bWhYswJOfVmpZFF46XjTkeQrdM2qzNk6oS4jiAA4o4EaZbR/OREMx/9TmPd6UvZS6iemWpHp8j5XqpWRs4Z2BwPM6aekGJ46ocGIXSqZtSbjw0kwUjRjtWMIYnrhxxZV2SbWDTV7k0lBwg3Di9051/AhJW8Ydj4MNoodI5JRfWb6c7v+ZBBL07hABQVXknG9taDGPNVPM51n0ejQRr3vH5eOke1rEY1otaxPeGx9rIb5ypFcfv4k=";
            CSDPasswordProd = "Sellos.Sami21";
            dataEmisorProd = {
              RFC: "SAMI791007Q15",
              NombreRazonSocial: "IVAN SALAZAR MARTINEZ",
              RegimenFiscal: "612",
              Direccion: [
                {
                  Calle: "CUAUTEPEC",
                  NumeroExterior: "81",
                  NumeroInterior: "LOCAL 6",
                  Colonia: "JORGE NEGRETE",
                  Localidad: "CDMX",
                  Municipio: "GUSTAVO A MADERO",
                  Estado: "Ciudad de Mexico",
                  Pais: "Mexico",
                  CodigoPostal: "07280",
                },
              ],
            };
            CPProd = "07280";
            conceptsProd = data.concepts;

            logoBase64 = getLogoFactura();

            var bodyParms = {
              DatosGenerales: {
                Version: "4.0",
                CSD: CSD_Prod,
                LlavePrivada: privateKeyProd,
                CSDPassword: CSDPasswordProd,
                GeneraPDF: true,
                Logotipo: logoBase64,
                CFDI: "Factura",
                OpcionDecimales: "1",
                NumeroDecimales: "2",
                TipoCFDI: "Ingreso",
                EnviaEmail: true,
                ReceptorEmail: "micorreo@midominio.com",
                ReceptorCC: "",
                ReceptorCCO: "",
                EmailMensaje:
                  "prueba de envio y generacion de factura por rest api desde el servicio de timbrado de FacturoPorTi",
              },
              Encabezado: {
                CFDIsRelacionados: "",
                TipoRelacion: "04",
                Emisor: dataEmisorProd,
                Receptor: data_receptor,
                Fecha: date_fact,
                Serie: data.serie,
                Folio: data.folio,
                MetodoPago: "PUE",
                FormaPago: data.pay_sat,
                Moneda: "MXN",
                LugarExpedicion: CPProd,
                SubTotal: data.subtotal,
                Total: data.total,
              },
              Conceptos: conceptsProd,
            };

            consumeAPI(bodyParms, email_receptor, id_order, order_code);
            if (data.response == true) {
            } else {
              Swal.fire({
                title: data.message,
                icon: "error",
              });
            }
          } else {
            Swal.fire({
              title: "Error",
              text: data.message,
              icon: "error",
              showCancelButton: false,
              //confirmButtonColor: "#32a852",
              confirmButtonText: "Acepar",
            });
          }
        })
        .fail(function (message) {
          Swal.fire({
            title: "No se pudoo completar el proceso!",
            icon: "error",
          });
        });
    }

    /*   */
  });

  function consumeAPI(bodyParms, email_receptor, id_order, order_code) {
    loading();
    console.log("Consuming API");
    //createToken();
    //exit();
    // TEST URL "https://testapi.facturoporti.com.mx/token/crear?Usuario=PruebasTimbrado&Password=@Notiene1",
    const options = { method: "GET", headers: { accept: "application/json" } };

    token = getToken();
    console.log(token);
    const logo = getMainLogo();

    // URL de la API para obtener facturas
    //TEST URL "https://testapi.facturoporti.com.mx/servicios/timbrar/json";
    const apiUrl = "https://api.facturoporti.com.mx/servicios/timbrar/json";

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
          Swal.fire({
            title: "Error",
            text: "La factura no pudo ser generada correctamente, por favor verifique los datos fiscales ingresados e intente nuevamente.",
            icon: "error",
            showCancelButton: false,
            //confirmButtonColor: "#32a852",
            confirmButtonText: "Acepar",
          });
          throw new Error("Network response was not ok " + response.statusText);
        }
        return response.json(); // Parsear la respuesta a JSON
      })
      .then((data) => {
        console.log(data);
        const CFDI = data.cfdiTimbrado.respuesta.selloCFD.substring(0, 8);
        loading();
        $("#btnGenFact" + id_order).prop("disabled", true);
        $.ajax({
          url: "php/controllers/sales/sales_controller.php",
          method: "POST",
          data: {
            mod: "sendMailFactura",
            stringPDF: data.cfdiTimbrado.respuesta.pdf,
            stringXML: data.cfdiTimbrado.respuesta.cfdixml,
            CFDI: CFDI.toUpperCase(),
            order_code: order_code,
            email_receptor: email_receptor,
            id_order: id_order,
          },
        })
          .done(function (data) {
            Swal.close();
            var data = JSON.parse(data);
            console.log(data);
            if (data.response == true) {
              Swal.fire({
                title: "Factura generada",
                text: "La factura ha sido generada correctamente",
                icon: "success",
                showCancelButton: false,
                confirmButtonColor: "#32a852",
                confirmButtonText: "Acepar",
              }).then((result) => {
                loading();
                location.reload();
              });
            } else {
              errorToast(data.message);
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

        // Manejar los datos de la respuesta
        /*  console.log("Datos de facturas:", data.cfdiTimbrado.respuesta);
        base64ToPDF(
          data.cfdiTimbrado.respuesta.pdf,
          "FACTURA COMPRA " + order_code + " " + CFDI.toUpperCase()
        );
        base64ToXmlFile(data.cfdiTimbrado.respuesta.cfdixml, CFDI.toUpperCase());
        Swal.close(); */
      })
      .catch((error) => {
        // Manejar errores
        Swal.fire({
          title: "Error",
          text: "La factura no pudo ser generada correctamente, por favor verifique los datos ingresados e intente nuevamente.",
          icon: "error",
          showCancelButton: false,
          //confirmButtonColor: "#32a852",
          confirmButtonText: "Acepar",
        });
        console.error("Hubo un problema con la solicitud:", error);
      });
  }
  function createToken() {
    const options = { method: "GET", headers: { accept: "application/json" } };

    fetch(
      "https://api.facturoporti.com.mx/token/crear?Usuario=SAMI791007Q15&Password=5GQy8DZVA5wGy",
      options
    )
      .then((response) => response.json())
      .then(function (response) {
        token = response.token;
        console.log(token);
        return token;
      })
      .catch((err) => console.error(err));
  }
  function deleteToken() {
    const options = {
      method: "DELETE",
      headers: {
        accept: "application/json",
        "content-type": "application/*+json",
      },
      body: '{"usuario":"SAMI791007Q15","password":"5GQy8DZVA5wGy"}',
    };

    fetch("https://api.facturoporti.com.mx/token/borrar", options)
      .then((response) => response.json())
      .then((response) => console.log(response))
      .catch((err) => console.error(err));
  }
  function getToken() {
    return "eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1lIjoiNVB1NENJcEtaTmQvYkNwY2hVTU9lUT09IiwibmJmIjoxNzIzODMwMjU0LCJleHAiOjE3MjY0MjIyNTQsImlzcyI6IlNjYWZhbmRyYVNlcnZpY2lvcyIsImF1ZCI6IlNjYWZhbmRyYSBTZXJ2aWNpb3MiLCJJZEVtcHJlc2EiOiI1UHU0Q0lwS1pOZC9iQ3BjaFVNT2VRPT0iLCJJZFVzdWFyaW8iOiI2Q0lNZWtxTFYwQURqajBoYlY5SVBRPT0ifQ.FSIlhzlNp7Rj2L5pIJ0YIBAM9NzSIwHNoImM4eZKlLM";
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

  function base64ToXmlFile(base64String, fileName) {
    // Definir el contenido XML como una cadena
    const xmlContent = base64String;

    // Crear un Blob con el contenido XML
    const blob = new Blob([xmlContent], { type: "application/xml" });

    // Crear una URL para el Blob
    const url = URL.createObjectURL(blob);

    // Crear un enlace de descarga y hacer clic en él automáticamente
    const a = document.createElement("a");
    a.href = url;
    a.download = fileName + ".xml"; // Nombre del archivo de descarga
    document.body.appendChild(a);
    a.click();

    // Limpiar y liberar la URL del Blob
    URL.revokeObjectURL(url);
    document.body.removeChild(a);
  }
  function downloadXML() {}
  function getLogoFactura() {
    return "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";
  }

  $(document).on("change", "#selectState", function () {
    var id_estado = this.value;
    if (id_estado != "") {
      loading();
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
    }
  });
  $(document).on("change", "#selectClient", function () {
    var id_client = this.value;
    if (id_client != 0) {
      loading();
      $.ajax({
        url: "php/controllers/colabs/colab_controller.php",
        method: "POST",
        data: {
          mod: "getClientBillingInfo",
          id_client: id_client,
        },
      })
        .done(async function (data) {
          var data = JSON.parse(data);
          console.log(data);
          $("#selectCity").prop("disabled", false);
          if (data.response == true) {
            if (data.data.length > 0) {
              $("#razon_social").val(data.data[0].razon_social);
              $("#rfc").val(data.data[0].rfc);
              $("#email_receptor").val(data.data[0].email);
              $("#street").val(data.data[0].street);
              $("#ext_num").val(data.data[0].ext_number);
              $("#int_num").val(data.data[0].int_number);
              $("#colony").val(data.data[0].colony);
              $("#locality").val(data.data[0].locality);
              $("#zipcode").val(data.data[0].zip_code);
              $("#selectState").val(data.data[0].state).trigger("change");
              var x = await resolveAfter2Seconds(10);
              console.log(x); // 10
              $("#selectCity").val(data.data[0].city).trigger("change");
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
    } else {
      $("#selectCity").prop("disabled", true);
      $("#razon_social").val("");
      $("#rfc").val("");
      $("#email_receptor").val("");
      $("#street").val("");
      $("#ext_num").val("");
      $("#int_num").val("");
      $("#colony").val("");
      $("#locality").val("");
      $("#zipcode").val("");
      $("#selectState").val("").trigger("change");
    }
  });
  function resolveAfter2Seconds(x) {
    loading();
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve(x);
      }, 800);
      Swal.close();
    });
  }
  $("#selectState").select2({
    dropdownParent: $("#modalReceptorData"),
  });
  $("#selectCity").select2({
    dropdownParent: $("#modalReceptorData"),
  });
  $("#selectClient").select2({
    dropdownParent: $("#modalReceptorData"),
    width: "resolve",
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
