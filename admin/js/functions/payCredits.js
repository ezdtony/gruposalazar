var filtersConfig = {
  base_path: "vendor/tablefilter/dist/tablefilter/",
  paging: {
    results_per_page: ["Resultados: ", [10, 25, 50, 100]],
  },
  state: {
    types: ["local_storage"],
    filters: true,
    page_number: true,
    page_length: true,
    sort: true,
  },
  alternate_rows: true,
  btn_reset: true,
  rows_counter: true,
  loader: {
    html: '<div id="lblMsg"></div>',
    css_class: "myLoader",
  },
  status_bar: {
    target_id: "lblMsg",
    css_class: "myStatus",
  },
  col_0: "select",
  col_1: "none",
  col_3: "select",
  col_4: "select",
  col_5: "none",
  themes: [{ name: "skyblue" }],
  extensions: [
    {
      name: "sort",
    },
  ],
};
var tf = new TableFilter("tablePayCredits", filtersConfig);
tf.init();

$(document).on("click", ".payCreditDetail", function () {
  loading();

  var id_credit_purchase_detail = $(this).attr("data-id");

  console.log("save");

  $.ajax({
    url: "php/controllers/clients/clients_controller.php",
    method: "POST",
    data: {
      mod: "updatePaymentDet",
      id_credit_purchase_detail: id_credit_purchase_detail,
    },
  })
    .done(function (data) {
      var data = JSON.parse(data);
      console.log(data);
      if (data.response == true) {
        Swal.fire({
          title: data.message,
          icon: "success",
        }).then((result) => {
            loading();
          location.reload();
        });
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
