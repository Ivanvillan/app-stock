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
                        <div class="float-right mb-2"><button class="btn btn-danger delete-order">Eliminar solicitud</button></div>
                        <table id="table-products" class="table table-hover">
                            <thead>
                                <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Marca</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Descripción</th>
                                <th scope="col" class="d-none">Tipo</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col" class="button-quit">Quitar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="float-right"><button class="btn btn-primary confirm-order">Confirmar</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
var order = JSON.parse(window.localStorage.getItem("order"));
var state = JSON.parse(window.localStorage.getItem("state"));
var selectedDepots = JSON.parse(window.localStorage.getItem("selectedDepots"));
$(document).ready(function () {
    console.log(order);
    console.log(state);
    console.log(selectedDepots);
    var url = '/api-stock/public/index.php/stock/order/details/';
    var dataTypeArt = new Array();
    var dataCant = new Array(); 
    var idDetPed = new Array();
    var cantReal = new Array();
    $.ajax({
        type: "GET",
        url: url + order,
        dataType: "json",
        success: function (data) {
            console.log(data);
            $.each(data.result, function (index, dataElement) { 
                dataTypes = dataElement.tipo
                switch (dataTypes) {
                case 'Herramienta':
                    dataTypes = "tools"
                    break;
                    case 'Equipo':
                    dataTypes = "hardware"
                    break;
                    case 'Consumible':
                    dataTypes = "consumables"
                    break;
                    case 'Indumentaria':
                    dataTypes = "dress"
                    break;
                }
                dataTypeArt.push(dataTypes + '/' + dataElement.idProducto);
                dataCant.push(dataElement.cantidad);
                idDetPed.push(dataElement.id);
                cantReal.push(dataElement.CantidadRealEnviada);
                    var info = `
                        <tr idDetail="${dataElement.id}">
                            <td>${dataElement.Producto}</td>
                            <td>${dataElement.marca}</td>
                            <td>${dataElement.descripcion}</td>
                            <td>${dataElement.detalle}</td>
                            <td class="d-none">${dataElement.tipo}</td>
                            <td>${dataElement.cantidad}</td>
                            <td><button class='btn btn-danger btn-sm cancel-item'> <i class="fa fa-window-close"> </i> </button></td>
                        </tr>
                        `;
                    $('table>tbody').append(info);
                    if (state == ' RECIBIDA ' || state == ' RECHAZADA ') {
                        $('.cancel-item').remove();
                        $('.button-quit').remove();
                        $('.delete-order').remove();
                        $('.confirm-order').prop('disabled', true);
                    }
                    if (userprofile == 1 && state == ' FINALIZADA ' || state == ' FINALIZADA CON MODIFICACIÓN ') {
                        $('.cancel-item').remove();
                        $('.button-quit').remove();
                        $('.delete-order').remove();
                    }
                    if (userprofile == 1) {
                        $('.delete-order').remove();
                    }
                    if (userprofile == 1 && state == ' INICIADA ' ) {
                        $('.confirm-order').prop('disabled', false);
                    }
                    if (userprofile == 1 && state == ' FINALIZADA ' || state == ' FINALIZADA CON MODIFICACIÓN ') {
                        $('.confirm-order').prop('disabled', true);
                        $('.delete-order').remove();
                        $('.button-quit').remove();
                    }
            });
            $('.confirm-order').click(function (e) { 
            e.preventDefault();
            console.log(dataTypeArt)
            console.log(dataCant);
            console.log(selectedDepots);
            window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
            window.localStorage.setItem("dataTypeArt", JSON.stringify(dataTypeArt));
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
});
</script>
</body>
</html>