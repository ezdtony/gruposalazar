$(document).ready(function () {
  $(document).on("click", ".btnGenerateBarcode", function () {
    var product_barcode = $(this).attr("data-barcode");
    JsBarcode("#barcodeImg", product_barcode, {format: "itf14"});
    
    /* printBarcode(); */
  });

  function printBarcode() {
    // printJS('printable', 'html')

    let printFrame = document.createElement("iframe")
    let printableElement = document.getElementById("barcodeImg")
    //
    // // printframe.setattribute("style", "visibility: hidden; height: 0; width: 0; position: absolute;")
    printFrame.setAttribute("id", "printjs")
    printFrame.srcdoc = "<html><head><title>document</title></head><body style='margin: 0;'>" +
      printableElement.outerHTML + "<style>@page { size: A4; } #barcodeImg { margin-left: 2.85cm; width: 1.6cm; height: 0.1cm; } #barcodeImg .barcode { width: 100%; }</style> </body></html>"

    document.body.appendChild(printFrame)

    let iframeElement = document.getElementById("printjs")
    iframeElement.focus()
    iframeElement.contentWindow.print()
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
});
