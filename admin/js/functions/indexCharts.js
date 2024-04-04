$(document).ready(function () {
  const ctx = document.getElementById("myChart");

  /* const config = {
    type: 'doughnut',
    data: data,
  }; */
  const data = {
    labels: ["Pendientes", "Completas", "Canceladas"],
    datasets: [
      {
        label: "My First Dataset",
        data: [300, 50, 100],
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
});
