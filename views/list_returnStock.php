<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envíos e ingresos</title>
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
                    <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h3>Stock devuelto</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row my-2">
                                <div class="form-group col col-md-4">
                                    <select class="form-control list-depots" name="" id="select-depots">
                                    <option value="">Selecciona un depósito</option>
                                    </select>
                                </div>
                            </div>
                        <div id="success-alert" role="alert">
                        </div>
                        <div id="danger-alert" role="alert">
                        </div>
                        <table id="table-sends" class="table table-sm table-hover">
                            <thead>
                                <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Marca</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Precio</th>
                                <!-- <th scope="col">Identificador</th> -->
                                <th scope="col">Origen</th>
                                <th scope="col">Observación</th>
                                <!-- <th scope="col">Finalizado</th> -->
                                <th scope="col">Realizado</th>
                                <!-- <th scope="col">Actualizado</th> -->
                                <th class="col-input" scope="col">Ingresar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
var selectedDepots = "";
    $(document).ready(function () {
        var dataTypes = "";
        var priceItem = 0;
        var cantItem = 0;
        $.ajax({
            url: '/api-stock/public/index.php/depots/get/all/all/all',
            type: 'GET',
            dataType: 'json',
            success: function(data, textStatus, xhr) {
                console.log(data)
            $.each(data.result, function(index, item) {
                var depotsList = `<option value="${item.id}">${item.nombre}</option>`;
                $('#select-depots').append(depotsList);      
            });
            },
            error: function(xhr, textStatus, errorThrown) {
            }
        });
        $("#select-depots").change(function(){
            selectedDepots = $(this).children("option:selected").val();
        });
        $('#select-depots').change(function (e) { 
                if ($('select[id=select-depots]').val() == $(this).children("option:selected").val()) {
                $.ajax({
                    type: "GET",
                    url: '/api-stock/public/index.php/stock/sent/all/' + selectedDepots,
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        let rows = data.result;
                        let html = [];
                        for (let i=0; i < rows.length; i++){
                            html.push(
                        `<tr class="content" idSends="${rows[i].id}" idItem="${rows[i].idProducto}">
                            <td> ${rows[i].Producto} </td> 
                            <td> ${rows[i].marca} </td> 
                            <td> ${rows[i].detalle} </td> 
                            <td class="d-none"> ${rows[i].tipo} </td> 
                            <td> ${rows[i].descripcion} </td>
                            <td> ${rows[i].cantidad} </td>
                            <td> ${rows[i].precio} </td>
                            <td> ${rows[i].Deposito} </td>
                            <td> ${rows[i].observaciones} </td>
                            <td> ${rows[i].realizado} </td>
                            <td> <button class='finalize-sends btn btn-dark'> Ingresar </button> </td>
                            </tr>`
                            );
                        }    
                        $('#table-sends>tbody').html(html.join(''));
                        $('.finalize-sends').click(function (e) { 
                            e.preventDefault();
                            cantItem = $(this).parent().parent().find('td').eq(5).html();
                            priceItem = $(this).parent().parent().find('td').eq(6).html();
                            dataTypes = $(this).parent().parent().find('td').eq(3).html().split(' ')[1];
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
                            var element = $(this)[0].parentElement.parentElement;
                            var idSend = $(element).attr('idSends');
                            var idElement = $(element).attr('idItem');
                            var url = "/api-stock/public/index.php/stock/register/";
                            var depot = selectedDepots + '/'
                            var typeMove = 'input';
                            var data = {
                                id: idElement,
                                precio: priceItem,
                                cantidad: cantItem,
                                control: 0,
                                observaciones: 'Ingreso de Stock devuelto',
                                usuario: userid,
                            };
                            $.ajax({
                            url: url + depot + dataTypes + typeMove,
                            type: 'POST',
                            dataType: 'json',
                            data: data,
                            success: function(data, textStatus, xhr) {
                                    $('#success-alert').addClass('alert alert-success')
                                    .add('span')
                                    .text('Artículo sumado al stock');
                                $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#success-alert").slideUp(500);
                                    $.ajax({
                                        type: "POST",
                                        url: "/api-stock/public/index.php/stock/close/" + idSend,
                                        dataType: "json",
                                        success: function (response) {
                                            console.log(response)
                                            window.location.reload();
                                        }
                                    });
                                });
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                    $('#danger-alert').addClass('alert alert-danger')
                                        .add('span')
                                        .text('Error al sumar artículo');
                                    $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#danger-alert").slideUp(500);
                                });
                            }
                            });
                        });
                    }
                });
            }
        });
    });
</script>
</body>
</html>