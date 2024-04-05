$(document).ready(function () {
  getDoughnutData();
  function getDoughnutData() {
    $.ajax({
      url: "php/controllers/sales/sales_controller.php",
      method: "POST",
      data: {
        mod: "getSalesStatus",
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          var labels = [];
          var values = [];
          for (let i = 0; i < data.data.length; i++) {
            const label = data.data[i].admin_status_description;
            const value = data.data[i].status_quantity;
            labels.push(label);
            values.push(value);
          }
          showDoughnutGraphic(labels, values);
        }

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

  getLineData();
  function getLineData() {
    $.ajax({
      url: "php/controllers/sales/sales_controller.php",
      method: "POST",
      data: {
        mod: "getSalesMonth",
      },
    })
      .done(function (data) {
        Swal.close();
        var data = JSON.parse(data);
        console.log(data);
        if (data.response == true) {
          var labels = [];
          var values = [];
          var months = [
            '',
            'Ene.',
            'Feb.',
            'Mar.',
            'Abr.',
            'May.',
            'Jun.',
            'Jul.',
            'Ago.',
            'Sep.',
            'Oct.',
            'Nov.',
            'Dic.'
          ];
          for (let i = 0; i < data.data.length; i++) {
            var month = data.data[i].month_sale;
            var label = months[month];
            const value = data.data[i].ammount_prod;
            labels.push(label);
            values.push(value);
          }
          showLineGraphic(labels, values);
        }

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

  function showDoughnutGraphic(labels, values) {
    const ctx = document.getElementById("graficaDona");

    /* const config = {
          type: 'doughnut',
          data: data,
        }; */
    const data = {
      labels: labels,
      datasets: [
        {
          label: "Estatus de ventas",
          data: values,
          backgroundColor: [
            "rgb(255, 99, 132)",
            "rgb(50, 168, 82)",
            "rgb(255, 205, 86)",
          ],
          hoverOffset: 4,
        },
      ],
    };

    new Chart(ctx, {
      type: "doughnut",
      data: data,
      borderWidth: 20,
    });
  }

  function showLineGraphic(labels, values) {

    var ctx = document.getElementById("graficaLine");
    var data = {
      labels: labels,
      datasets: [
        {
          label: "Ventas totales",
          data: values,
          fill: false,
          borderColor: "rgb(75, 192, 192)",
          tension: .3,
        },
      ],
    };

    new Chart(ctx, {
      type: "line",
      data: data,
    });
  }
});
