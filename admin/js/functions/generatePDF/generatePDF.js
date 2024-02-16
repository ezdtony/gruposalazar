async function generateOrderIncomePDF(data) {
  window.jsPDF = window.jspdf.jsPDF;
  var doc = new jsPDF("portrait");
  var font = getFont();
  doc.addFileToVFS("assets/fonts/VarelaRound-Regular.ttf", font);
  doc.addFont(
    "assets/fonts/VarelaRound-Regular.ttf",
    "VarelaRound-Regular",
    "normal"
  );

  var sbj_final = 0;
  var order_code = data.info_order[0].order_code;
  var subsidiary_name = data.info_order[0].subsidiary_name;
  var date_register = data.info_order[0].date_register;
  var subsidiary_name = data.info_order[0].order_code;
  var username = data.info_order[0].username;
  var status_description = data.info_order[0].status_description;
  //--- --- ---//
  //--- --- ---//
  var table_titles = [
    "Cant.",
    "Código Prod.",
    "Producto",
    "Marca",
    "Precio Compra",
    "Precio Venta",
  ];
  products = [];

  for (let prod = 0; prod < data.order_breakdown.length; prod++) {
    var data_product = [
        data.order_breakdown[prod].quantity,
        data.order_breakdown[prod].product_code,
        data.order_breakdown[prod].product_name,
        data.order_breakdown[prod].brand,
        parseFloat(data.order_breakdown[prod].purchase_price).toFixed(2),
        parseFloat(data.order_breakdown[prod].price).toFixed(2),
    ];

    products.push(data_product);
  }

  lastPositions = 25;

  doc.autoTable({
    theme: "plain",
    startY: lastPositions,
    tableWidth: 180,
    margin: {
      left: 8,
    },
    headStyles: {
      halign: "left",
      valign: "middle",
      font: "VarelaRound-Regular",
      fillColor: [255, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 10,
    },
    bodyStyles: {
      font: "VarelaRound-Regular",
      fillColor: [255, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 13,
    },
    columnStyles: {
      0: {
        cellWidth: 180,
      },
    },
    body: [
      [
        {
          content: "DETALLES DE ÓRDEN DE ENTRADA DE MATERIAL",
          styles: { halign: "center" },
        },
      ],
    ],
  });
  lastPositions = doc.lastAutoTable.finalY + 10;

  doc.autoTable({
    theme: "plain",
    startY: lastPositions,
    tableWidth: 180,
    margin: {
      left: 8,
    },
    headStyles: {
      halign: "left",
      valign: "middle",
      font: "VarelaRound-Regular",
      fillColor: [43, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 10,
    },
    bodyStyles: {
      font: "VarelaRound-Regular",
      fillColor: [255, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 10,
    },
    columnStyles: {
      0: {
        cellWidth: 180,
      },
    },
    body: [
      [
        {
          content:
            "Código de órden: " +
            order_code +
            "\n Sucursal: " +
            subsidiary_name +
            "\n Fecha de registro: " +
            date_register +
            "\n Usuario que registró: " +
            username +
            "\n Status: " +
            status_description,
          styles: { halign: "left" },
        },
      ],
    ],
  });
  lastPositions = doc.lastAutoTable.finalY + 7;

  doc.autoTable({
    theme: "striped",
    startY: lastPositions,
    tableWidth: 180,
    margin: {
      left: 8,
    },
    headStyles: {
      halign: "left",
      valign: "middle",
      font: "VarelaRound-Regular",
      fillColor: [44, 69, 191],
      textColor: [255, 255, 255],
      fontSize: 10,
    },
    bodyStyles: {
      font: "VarelaRound-Regular",
      fillColor: [255, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 10,
    },
    head: [table_titles],
    body: products,
  });
  //--- --- ---//
  lastPositions = doc.lastAutoTable.finalY + 10;

  doc.text(118, lastPositions, "Total de articulos: "+data.total_items);
  lastPositions = lastPositions + 8;
  doc.text(118, lastPositions, "Costo total: $ "+data.total_order);


  doc.addImage(getMainLogo(), "png", 8, 5, 40, 20);
  doc.save("ORDEN " + order_code+".pdf");
  Swal.close();
  await timer(2000);

  //--- --- ---//
}

function timer(ms) {
  return new Promise((res) => setTimeout(res, ms));
}
