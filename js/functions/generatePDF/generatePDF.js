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
          styles: { borders: "b" },
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

  doc.text(118, lastPositions, "Total de articulos: " + data.total_items);
  lastPositions = lastPositions + 8;
  doc.text(118, lastPositions, "Costo total: $ " + data.total_order);

  doc.addImage(getMainLogo(), "png", 8, 5, 40, 20);
  doc.save("ORDEN " + order_code + ".pdf");
  Swal.close();
  await timer(2000);

  //--- --- ---//
}
async function printSaleTicket(data) {
  console.log(data);
  window.jsPDF = window.jspdf.jsPDF;
  var size = (data.prodsOrder.length * 5)+100;
  var doc = new jsPDF("p", "mm", [57, size]);
  var font = getFont();
  doc.addFileToVFS("assets/fonts/VarelaRound-Regular.ttf", font);
  doc.addFont(
    "assets/fonts/VarelaRound-Regular.ttf",
    "VarelaRound-Regular",
    "normal"
  );

  var sbj_final = 0;
  var order_code = data.orderDetails[0].order_code;
  var subsidiary_name = data.orderDetails[0].subsidiary_name;
  var username = data.orderDetails[0].short_name;
  //var status_description = data.orderDetails[0].status_description;
  var ammount = data.orderDetails[0].ammount;
  var order_date = data.orderDetails[0].order_date;

  var subs_phone = data.getSubsidiaryInfo[0].subsidiary_phone;
  var subsidiary_address = data.getSubsidiaryInfo[0].street + ' ' +
  data.getSubsidiaryInfo[0].ext_number + ', Col.' + 
  data.getSubsidiaryInfo[0].colony + ', ' + 
  data.getSubsidiaryInfo[0].delegation + ', CP. ' + 
  data.getSubsidiaryInfo[0].postal_code + ' \n' + 
  data.getSubsidiaryInfo[0].state + '.' 
  ;
  //--- --- ---//
  //--- --- ---//
  var table_titles = ["Cant.", "Descripción", "Precio", "Importe"];
  products = [];

  for (let prod = 0; prod < data.prodsOrder.length; prod++) {
    var data_product = [
      data.prodsOrder[prod].quantity,
      data.prodsOrder[prod].product_name,
      parseFloat(data.prodsOrder[prod].price).toFixed(2),
      parseFloat(data.prodsOrder[prod].prod_import).toFixed(2),
    ];

    products.push(data_product);
  }

  lastPositions = 14;

  doc.autoTable({
    theme: "plain",
    startY: lastPositions,
    tableWidth: 47,
    margin: {
      left: 5,
    },
    headStyles: {
      halign: "left",
      valign: "middle",
      fillColor: [255, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 10,
    },
    bodyStyles: {
      fillColor: [255, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 4,
      halign: "center",
      valign: "middle",
    },
    body: [
      [
        {
          content: "DISTRIBUIDOR AUTORIZADO SAYER LACK",
          styles: { borders: "b" },
        },
      ],
    ],
  });
  lastPositions = doc.lastAutoTable.finalY + 2;

  doc.autoTable({
    theme: "plain",
    startY: lastPositions,
    tableWidth: 47,
    margin: {
      left: 5,
    },
    headStyles: {
      halign: "left",
      valign: "middle",
      fillColor: [43, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 10,
    },
    bodyStyles: {
      fillColor: [255, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 4,
    },
    body: [
      [
        {
          content:
            "Código de órden: " +
            order_code +
            "\nSucursal: " +
            subsidiary_name +
            "\nFecha de registro: " +
            order_date +
            "\nLe atendió: " +
            username,
          styles: { halign: "left" },
        },
      ],
    ],
  });
  lastPositions = doc.lastAutoTable.finalY + 3;
  
  doc.autoTable({
    theme: "plain",
    startY: lastPositions,
    tableWidth: 47,
    margin: {
      left: 5,
    },
    headStyles: {
      halign: "left",
      valign: "middle",
      fillColor: [255, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 5,
    },
    bodyStyles: {
      fillColor: [255, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 4,
    },
    head: [table_titles],
    body: products,
  });
  //--- --- ---//
  lastPositions = doc.lastAutoTable.finalY + 4;

  doc.setFontSize(6);

  doc.text(30, lastPositions, "Número de productos: "+data.prodsOrder.length);
  lastPositions = lastPositions + 3;
  doc.text(30, lastPositions, "Total: $ "+ammount);

  lastPositions = doc.lastAutoTable.finalY + 20;

  doc.autoTable({
    theme: "plain",
    startY: lastPositions,
    tableWidth: 35,
    margin: {
      left: 10,
    },
    headStyles: {
      halign: "center",
      valign: "middle",
      fillColor: [43, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 10,
    },
    bodyStyles: {
      fillColor: [255, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 5,
      halign: "center",
      valign: "middle",
    },
    body: [
      [
        {
          content:
          subsidiary_address +
            "\nTel. : " +
            subs_phone +
            "\n \nVisita nuestra tienda en linea en:" +
            "\n \nwww.gruposalazar.com.mx"
            ,
          styles: { halign: "center" },
        },
      ],
    ],
  });
  lastPositions = doc.lastAutoTable.finalY + 3;


  doc.addImage(getMainLogo(), "png", 13, 3, 30, 15);
  
  doc.autoPrint();
window.open(doc.output('bloburl'), '_blank');
  Swal.close();
  await timer(2000);

  //--- --- ---//
}

function timer(ms) {
  return new Promise((res) => setTimeout(res, ms));
}
