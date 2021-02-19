<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle</title>
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
                        <h2>Detalle de pedido</h2>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col form-group col-md-6">
                                <div class="btn btn-danger export-pdf">Exportar en PDF</div>
                                <div class="btn btn-success export-excel">Exportar en Excel</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col d-none delete-order-col">
                                <div class="mb-2"><button class="btn btn-danger delete-order">Eliminar solicitud</button></div>
                            </div>
                            <div class="col col-md-4">
                                <div class="form-group select-state">
                                    <select id="selectState" class="form-control">
                                        <option>Estado de solicitud</option>
                                        <option value="3">Finalizada</option>
                                        <option value="4">Finalizada Parcialmente</option>
                                        <option value="5">Finalizada con Modificaciones</option>
                                        <option value="6">Rechazada</option>
                                    </select> 
                                </div>
                            </div>
                            <div class="col col-md-1"><button class="btn btn-primary orderState">Confirmar</button></div>
                        </div>
                        <div id="success-alert" role="alert">
                        </div>
                        <div id="danger-alert" role="alert">
                        </div>
                        <table id="table-products" class="table table-hover">
                            <thead>
                                <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Marca</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Descripción</th>
                                <th scope="col" class="d-none">Tipo</th>
                                <th scope="col">Solicitados</th>
                                <th scope="col">Enviados</th>
                                <th scope="col" class="button-quit">Quitar</th>
                                <th scope="col" class="button-quit">Agregar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="col col-md-1 float-right mr-4"><button class="btn btn-primary finalize-order">Recibida</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://unpkg.com/jspdf@1.5.3/dist/jspdf.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable@3.5.3/dist/jspdf.plugin.autotable.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script>
var order = JSON.parse(window.localStorage.getItem("order"));
var state = JSON.parse(window.localStorage.getItem("state"));
var selectedDepots = JSON.parse(window.localStorage.getItem("selectedDepots"));
var selectedState = "";
$(document).ready(function () {
    console.log(order);
    console.log(state);
    console.log(selectedDepots);
    var url = '/api-stock/public/index.php/stock/order/details/';
    var dataTypeArt = "";
    var dataCant = ""; 
    var idDetPed = "";
    var article = "";
    var idProduct = "";
    $("#selectState").change(function(){
        selectedState = $(this).children("option:selected").val();
        console.log(selectedState);
    });
    $.ajax({
        type: "GET",
        url: url + order,
        dataType: "json",
        success: function (data) {
            console.log(data);
            let rows = data.result;
            let html = [];
            for (let i=0; i < rows.length; i++){
                var cantRealInput = rows[i].CantidadRealEnviada;
                if (cantRealInput == null) {
                    cantRealInput = 0;
                }else{
                    cantRealInput = rows[i].CantidadRealEnviada;
                }
                html.push(
                `<tr class="content" idDetail="${rows[i].id}">
                <td>${rows[i].Producto}</td> 
                <td>${rows[i].marca}</td> 
                <td>${rows[i].descripcion}</td> 
                <td>${rows[i].detalle}</td> 
                <td class="d-none">${rows[i].tipo}</td>
                <td>${rows[i].cantidad}</td>
                <td>${cantRealInput}</td>
                <td class="d-none">${rows[i].idProducto}</td>
                <td><button class='btn btn-danger btn-sm cancel-item'><i class="fa fa-window-close"></i></button></td>
                <td><button class='btn btn-dark btn-sm confirm-order'><i class="fas fa-sign-in-alt"></button></td>
                </tr>`
                );    
                // <td> <input type="number" class="cant form-control" placeholder="Cantidad"> </td>
            $('#table-products>tbody').html(html.join(''));
            }
            if (state == ' RECIBIDA ' || state == ' RECHAZADA ') {
                $('.cancel-item').remove();
                $('.button-quit').remove();
                $('.delete-order').remove();
                $('.select-state').remove();
                $('.confirm-order').remove();
                $('.orderState').remove();
                $('.finalize-order').prop('disabled', true);
            }
            if (userprofile == 1 && state == ' FINALIZADA ' || state == ' FINALIZADA CON MODIFICACIÓN ') {
                $('.cancel-item').remove();
                $('.button-quit').remove();
                $('.delete-order').remove();
                $('.select-state').remove();
                $('.confirm-order').remove();
                $('.orderState').remove();
            }
            if (userprofile == 1) {
                $('.delete-order').remove();
                $('.finalize-order').remove();
            }else{
                $('.select-state').remove();
                $('.orderState').remove();
                $('.delete-order-col').removeClass('d-none');
            }   
            if (userprofile == 1 && state == ' INICIADA ' ) {
                $('.confirm-order').prop('disabled', false);
            }
            if (userprofile == 1 && state == ' FINALIZADA PARCIALMENTE ' ) {
                $('.confirm-order').prop('disabled', false);
            }
            if (userprofile == 1 && state == ' FINALIZADA ' || state == ' FINALIZADA CON MODIFICACIÓN ') {
                $('.confirm-order').prop('disabled', true);
                $('.delete-order').remove();
                $('.button-quit').remove();
                $('.select-state').prop('disabled', true);
                $('.orderState').prop('disabled', true);
            }
            $('.orderState').click(function (e) { 
                e.preventDefault();
                console.log('click');
                if (state == ' INICIADA ' || state == ' FINALIZADA PARCIALMENTE ') {
                    var urlState = '/api-stock/public/index.php/stock/order/state/' + selectedState + '/' + order; 
                    $.ajax({
                        type: "POST",
                        url: urlState,
                        dataType: "json",
                        success: function (response) {  
                    $('#success-alert').addClass('alert alert-success')
                                .add('span')
                                .text('Estado actualizado');
                            $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                                $("#success-alert").slideUp(500);
                                history.back();
                            });
                        }
                    });
                }else{
                    console.log('a');
                }
            });
            $('.finalize-order').click(function (e) { 
                e.preventDefault();
                if (state != ' RECIBIDA ') {
                var urlState = '/api-stock/public/index.php/stock/order/state/' + '2' + '/' + order; 
                $.ajax({
                    type: "POST",
                    url: urlState,
                    dataType: "json",
                    success: function (response) {  
                $('#success-alert').addClass('alert alert-success')
                            .add('span')
                            .text('Estado actualizado');
                        $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#success-alert").slideUp(500);
                            history.back();
                        });
                    }
                });
                }
            });
            $('.confirm-order').click(function (e) { 
            e.preventDefault();
            dataCant = $(this).parent().parent().find('td').eq(5).html();
            cantReal = $(this).parent().parent().find('td').eq(6).html();
            idProduct = $(this).parent().parent().find('td').eq(7).html();
            article = $(this)[0].parentElement.parentElement;
            idDetPed = $(article).attr('idDetail');
            dataTypes = $(this).parent().parent().find('td').eq(4).html();
            switch (dataTypes) {
            case 'Herramienta':
                dataTypes = "tools/"
                break;
                case 'Equipo':
                dataTypes = "hardware/"
                break;
                case 'Consumible':
                dataTypes = "consumables/"
                break;
                case 'Indumentaria':
                dataTypes = "dress/"
                break;
            }
            console.log(dataTypeArt)
            console.log(dataCant);
            console.log(selectedDepots);
            window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
            window.localStorage.setItem("idProduct", JSON.stringify(idProduct));
            window.localStorage.setItem("dataTypeArt", JSON.stringify(dataTypes));
            window.localStorage.setItem("dataCant", JSON.stringify(dataCant));
            window.localStorage.setItem("order", JSON.stringify(order));
            window.localStorage.setItem("idDetPed", JSON.stringify(idDetPed));
            window.localStorage.setItem("cantReal", JSON.stringify(cantReal));
            window.localStorage.setItem("state", JSON.stringify(state));
            window.location.href ='../views/successNote.php';
        });
        $('.delete-order').click(function (e) { 
            e.preventDefault();
            console.log(order);
            $.ajax({
                type: "POST",
                url: '/api-stock/public/index.php/stock/order/cancel/order/' + order,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    window.location.href = 'list_notes.php';;
                }
            });
        });
        $('.cancel-item').click(function (e) { 
            e.preventDefault();
            var element = $(this)[0].parentElement.parentElement;
            var idItem = $(element).attr('idDetail');
            console.log(idItem);
            $.ajax({
                type: "POST",
                url: '/api-stock/public/index.php/stock/order/cancel/detail/' + idItem,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    window.location.reload();
                }
            });
        });
        }
    });
    $('.export-pdf').click(function(event) {
      var doc = new jsPDF()
      doc.autoTable({ html: '#table-products' })
      doc.save("TablaDetalleNota" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".pdf")
    });
    $('.export-excel').click(function(event) {
        var table = $('#table-products');
        if(table && table.length){

        $(table).table2excel({
        //exclude: ".noExl",
        name: "TablaDetalleNota",
        filename: "TablaDetalleNota" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
        fileext: ".xls",
        exclude_img: true,
        exclude_links: true,
        exclude_inputs: true,
        preserveColors: false
        });
        }
    });
});
</script>
</body>
</html>