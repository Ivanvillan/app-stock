<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimiento de articulos</title>
    <style type="text/css"> 
        thead tr th { 
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/app-stock/head/head.php') ?>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3>Lista de artículos seleccionados</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col col-md-6 card-title">
                                <h4>Ingreso de stock</h4>
                            </div>
                            <div class="col offset-md-2 form-group float-right">
                                <div class="btn btn-danger export-pdf">Exportar en PDF</div>
                                <div class="btn btn-success export-excel">Exportar en Excel</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-md-4 mr-2 mb-2 isBuy">
                                <div class="card card-buy">
                                    <div class="card-header">
                                        <h5 class="buy-2">Actualizar precio en todas las ubicaciones</h5>
                                        <label class="form-check-label float-right">
                                            <input type="checkbox" class="form-check-input buy" name="" id="buy" value="checkedValue">
                                            Si
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <button id="addStock" class="btn btn-primary float-right" onClick="confirmReturn()">Enviar</button>
                            </div>
                        </div>
                    </div>
                    <div id="success-alert" role="alert">
                    </div>
                    <div id="danger-alert" role="alert">
                    </div>
                    <div class="row">
                        <div class="col">
                            <table id="table-articles-toMove" class="table table-hover">
                                <thead>
                                    <tr id="tr-toMove">
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Marca</th>
                                        <th scope="col">Descripcion</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Observación</th>
                                        <th scope="col" class="col-price d-none">Precio</th>
                                    </tr>
                                </thead>
                                <form id="form-data">
                                    <tbody>
                                    </tbody>
                                </form>
                            </table>                     
                        </div>
                    </div>
                    <iframe id="txtArea1" style="display:none"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/jspdf@1.5.3/dist/jspdf.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable@3.5.3/dist/jspdf.plugin.autotable.js"></script>
<script>
    var result = JSON.parse(window.localStorage.getItem("result"));
    var selectedDepotsOrigen = JSON.parse(window.localStorage.getItem("selectedDepotsOrigen"));
    var selectedDepotsDestino = JSON.parse(window.localStorage.getItem("selectedDepotsDestino"));
    $(document).ready(function () {
        console.log(result)
        listToMove();
        $.fn.hasAttr = function(name) {  
            return this.attr(name) !== undefined;
        };
        buyChecked();
        $('.export-pdf').click(function(event) {
          var doc = new jsPDF()
          doc.autoTable({ html: '#table-articles-toMove' })
          doc.save('Articulos-a-mover.pdf')
        });
        $('.export-excel').click(function(event) {
            var table = $('#table-articles-toMove');
            if(table && table.length){

            $(table).table2excel({
                //exclude: ".noExl",
                name: "TablaArticulosAMover",
                filename: "TablaArticulosAMover" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
                fileext: ".xls",
                exclude_img: true,
                exclude_links: true,
                exclude_inputs: true,
                preserveColors: false
            });
            }
        });
    });
    function buyChecked(){
        $('#buy').change(function() {
            // this will contain a reference to the checkbox   
            if (this.checked) {
                $('.price').addClass('d-block');
                $('.col-price').addClass('d-block');
            } else {
                $('.price').removeClass('d-block');
                $('.col-price').removeClass('d-block');
            }
        });
    }
    function confirmReturn(){
        var cantArray = new Array();
        $('.cant').each(function(){
            cantArray.push($(this).val());
        });
        var priceArray = new Array();
        $('.price').each(function(){
            priceArray.push($(this).val());
        });
        var observationArray = new Array();
        $('.observation').each(function(){
            observationArray.push($(this).val());
        });
        result.items.forEach(function (result, index){
            var url = "/api-stock/public/index.php/stock/register/";
            var typeArticle = result.split('/')[0] + '/';
            var typeArticleCodes = result.split('/')[0];
            var idArticle = result.split('/')[1];
            var typeMove = 'output';
            var dataBuy = {
                id: idArticle,
                precio: priceArray[index],
                cantidad: cantArray[index],
                control: 0,
                observaciones: observationArray[index],
                idDestino: selectedDepotsDestino,
                usuario: userid,
            };
            $.ajax({
              url: url + selectedDepotsOrigen + '/' + typeArticle + typeMove,
              type: 'POST',
              dataType: 'json',
              data: dataBuy,
              success: function(data, textStatus, xhr) {
                    $('#success-alert').addClass('alert alert-success')
                    .add('span')
                    .text('Stock enviado');
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#success-alert").slideUp(500);
                    window.location.href ='../views/returnStock.php';
                });
              },
              error: function(xhr, textStatus, errorThrown) {
                    $('#danger-alert').addClass('alert alert-danger')
                        .add('span')
                        .text('Error al enviar stock');
                    $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#danger-alert").slideUp(500);
                });
              }
            });
        });
    }
    function listToMove(){
        result.items.forEach(function (result, index){
        var url = "/api-stock/public/index.php/stock/get/" + selectedDepotsOrigen + '/';
        $.ajax({
            type: "GET",
            url: url + result,
            dataType: "json",
            success: function (response) {
                console.log(response.result);
                    var stock = response.result[0].existencia;
                    var priceItem = response.result[0].precio;
                    if (stock == null || 0 || undefined) {
                        stock = 'Sin stock';
                    }else{
                        stock = response.result[0].existencia;
                    }
                    if (priceItem == null || 0 || undefined) {
                        priceItem = 0;
                    }else{
                        priceItem = response.result[0].precio;
                    }
                    var row = `<tr class="tr-article" idArticle="${response.result[0].idProducto}">
                    <td>${response.result[0].Producto}</td> 
                    <td>${response.result[0].marca}</td> 
                    <td>${response.result[0].descripcion}</td>
                    <td class="d-none"> ${response.result[0].tipo} </td>
                    <td>${stock}</td>
                    <td> <input type="number" name="cant[]" value="" class="cant form-control cant" placeholder="Cant."> </td>
                    <td> <input type="text" name="observation[]" class="observation form-control observation" placeholder="Observacion"> </td> 
                    <td> <input type="number" name="price[]" value="${priceItem}" class="price form-control d-none" placeholder="Precio"> </td>
                    <td> <button class="codes-liberate btn btn-dark">Ident.</button> </td>
                    </tr>`;
                $('#table-articles-toMove>tbody').append(row);
                $('.codes-liberate').click(function (e) { 
                    e.preventDefault();
                    var returnCode = 1;
                    var article = $(this)[0].parentElement.parentElement;
                    var idArticle = $(article).attr('idArticle');
                    var typeArticle = $(this).parent().parent().find('td').eq(3).html();
                    window.localStorage.setItem("typeArticle", JSON.stringify(typeArticle));
                    window.localStorage.setItem("idArticle", JSON.stringify(idArticle));
                    window.localStorage.setItem("returnCode", JSON.stringify(returnCode));
                    window.localStorage.setItem("selectedDepotsOrigen", JSON.stringify(selectedDepotsOrigen));
                    window.localStorage.setItem("selectedDepotsDestino", JSON.stringify(selectedDepotsDestino));
                    window.location.href ='../views/code_liberate.php';
                });
            }
        });
    });
         // <td> <button class='btn btn-danger btn-sm' onClick="removeArticle()"> <i class='fas fa-trash'> </i> </button> </td>
    }
</script>
</body>
</html>