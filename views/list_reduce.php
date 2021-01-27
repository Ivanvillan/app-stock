<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas</title>
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
                        <h2>Lista de bajas</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group my-2 col-md-4 select-depots">
                            <label for="inputState">Lista de pañoles</label>
                            <div class="form-group ">
                                <select class="form-control" name="" id="select-depots">
                                    <option value="">Selecciona un pañol</option>
                                </select>
                            </div>
                        </div>
                        <div class="col form-group col col-md-4">
                            <input type="search" id="search" class="form-control" placeholder="Buscador">
                        </div>
                        <table id="table-down" class="table table-hover">
                            <thead>
                                <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Marca</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Observación</th>
                                <th scope="col">Realizado</th>
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
    $("#select-depots").change(function(){
        selectedDepots = $(this).children("option:selected").val();
    });
    if (userprofile != 1) {
        $.ajax({
            url: '/api-stock/public/index.php/depots/get/user/' + userid,
            type: 'GET',
            dataType: 'json',
            success: function(data, textStatus, xhr) {
                console.log(data)
            $.each(data.result, function(index, item) {
                var depots = `<option value="${item.id}">${item.nombre}</option>`;
                $('#select-depots').append(depots);      
            });
            },
            error: function(xhr, textStatus, errorThrown) {
            }
        });
        $('#select-depots').change(function (e) {
            if ($('select[id=select-depots]').val() == $(this).children("option:selected").val()) {
            $("#table-down>tbody").load(" #table-down>tbody");
                $.ajax({
                type: "GET",
                url: '/api-stock/public/index.php/stock/report/' + selectedDepots + '/all/output/low',
                dataType: "json",
                success: function (data) {
                    let rows = data.result;
                        let html = [];
                        for (let i=0; i < rows.length; i++){
                            html.push(
                        `<tr class="content" idDown="${rows[i].idRow}">
                            <td> ${rows[i].Producto} </td> 
                            <td> ${rows[i].marca} </td> 
                            <td> ${rows[i].detalle} </td> 
                            <td> ${rows[i].descripcion} </td>
                            <td> ${rows[i].Cantidad} </td>
                            <td> ${rows[i].observaciones} </td>
                            <td> ${rows[i].fecha} </td>
                        </tr>`
                            );
                        }    
                        $('#table-down>tbody').html(html.join(''));
                }
            });
        }
    });
    }else{
        $.ajax({
            url: '/api-stock/public/index.php/depots/get/all/all/all',
            type: 'GET',
            dataType: 'json',
            success: function(data, textStatus, xhr) {
                console.log(data)
            $.each(data.result, function(index, item) {
                var users = `<option value="${item.id}">${item.nombre}</option>`;
                $('#select-depots').append(users);      
            });
            },
            error: function(xhr, textStatus, errorThrown) {
            }
        });
        $('#select-depots').change(function (e) {
            if ($('select[id=select-depots]').val() == $(this).children("option:selected").val()) {
            $("#table-down>tbody").load(" #table-down>tbody");
                $.ajax({
                type: "GET",
                url: '/api-stock/public/index.php/stock/report/' + selectedDepots + '/all/output/low',
                dataType: "json",
                success: function (data) {
                    let rows = data.result;
                    let html = [];
                    for (let i=0; i < rows.length; i++){
                        html.push(
                    `<tr class="content" idDown="${rows[i].idRow}">
                        <td> ${rows[i].Producto} </td> 
                        <td> ${rows[i].marca} </td> 
                        <td> ${rows[i].detalle} </td> 
                        <td> ${rows[i].descripcion} </td>
                        <td> ${rows[i].Cantidad} </td>
                        <td> ${rows[i].observaciones} </td>
                        <td> ${rows[i].fecha} </td>
                    </tr>`
                        );
                    }    
                    $('#table-down>tbody').html(html.join(''));
                }
            });
        }
    });
    }
    $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
            $(".content").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
});
</script>
</body>
</html>