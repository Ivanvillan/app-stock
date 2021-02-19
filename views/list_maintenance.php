<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reparaciones</title>
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
                        <h2>Lista de mantenimientos</h2>
                    </div>
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
                    <div class="card-body">
                        <table id="table-maintenance" class="table table-hover">
                            <thead>
                                <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Marca</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Identificador</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Realizado</th>
                                <th scope="col">Actualizado</th>
                                <th scope="col">Finalizado</th>
                                <th scope="col">Finalizar</th>
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
                $.ajax({
                    type: "GET",
                    url: '/api-stock/public/index.php/movements/get/' + selectedDepots + '/all/maintenance/all',
                    dataType: "json",
                    success: function (data) {
                        let rows = data.result;
                        let html = [];
                        for (let i=0; i < rows.length; i++){
                            var realized = rows[i].Actualizado;
                            var ended = rows[i].Finalizado;
                            if (realized == null) {
                                realized = 'Sin actualizar';
                            } 
                            html.push(
                            `<tr class="tr-article content" idOrder="${rows[i].id}">
                                <td>${rows[i].Producto}</td> 
                                <td>${rows[i].marca}</td> 
                                <td>${rows[i].detalle}</td> 
                                <td>${rows[i].descripcion}</td>
                                <td>${rows[i].Identificador}</td>
                                <td>${rows[i].Descripcion}</td>
                                <td>${rows[i].Realizado}</td>
                                <td>${realized}</td>
                                <td>${rows[i].Finalizado}</td>
                                <td> <button class='finalize-maintenance btn btn-dark'> Finalizar </button> </td>
                            </tr>`
                            );
                            if (ended == "Sí") {
                                $('.finalize-maintenance').attr("disabled", true);
                            }
                        }    
                        $('#table-maintenance>tbody').html(html.join(''));
                        $('.finalize-maintenance').click(function (e) { 
                            e.preventDefault();
                            var element = $(this)[0].parentElement.parentElement;
                            var idElement = $(element).attr('idMaintenance')
                            $.ajax({
                                type: "POST",
                                url: "/api-stock/public/index.php/movements/close/" + idElement,
                                dataType: "json",
                                success: function (response) {
                                    console.log(response)
                                    window.location.reload();
                                }
                            });
                        });
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
                $.ajax({
                    type: "GET",
                    url: '/api-stock/public/index.php/movements/get/' + selectedDepots + '/all/maintenance/all',
                    dataType: "json",
                    success: function (data) {
                        let rows = data.result;
                        let html = [];
                        for (let i=0; i < rows.length; i++){
                            var realized = rows[i].Actualizado;
                            var ended = rows[i].Finalizado;
                            if (realized == null) {
                                realized = 'Sin actualizar';
                            } 
                            html.push(
                            `<tr class="tr-article content" idOrder="${rows[i].id}">
                                <td>${rows[i].Producto}</td> 
                                <td>${rows[i].marca}</td> 
                                <td>${rows[i].detalle}</td> 
                                <td>${rows[i].descripcion}</td>
                                <td>${rows[i].Identificador}</td>
                                <td>${rows[i].Descripcion}</td>
                                <td>${rows[i].Realizado}</td>
                                <td>${realized}</td>
                                <td>${rows[i].Finalizado}</td>
                                <td> <button class='finalize-maintenance btn btn-dark'> Finalizar </button> </td>
                            </tr>`
                            );
                            if (ended == "Sí") {
                                $('.finalize-maintenance').attr("disabled", true);
                            }
                        }  
                        $('.finalize-maintenance').click(function (e) { 
                            e.preventDefault();
                            var element = $(this)[0].parentElement.parentElement;
                            var idElement = $(element).attr('idMaintenance')
                            $.ajax({
                                type: "POST",
                                url: "/api-stock/public/index.php/movements/close/" + idElement,
                                dataType: "json",
                                success: function (response) {
                                    console.log(response)
                                    window.location.reload();
                                }
                            });
                        });
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