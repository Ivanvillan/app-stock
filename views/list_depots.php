<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de depositos</title>
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
            <div class="form-group my-4 col-md-4">
                <label for="inputSearch">Buscar deposito</label>
                <input type="search" class="form-control" placeholder="Buscador">
            </div>
        </div>
        <div class="row">
            <div class="col">
            <div id="update-alert" role="alert">
            </div>
            <div id="delete-alert" role="alert">
            </div>
            <table id="table-depots" class="table table-hover">
                <thead>
                    <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Ubicación</th>
                    <th scope="col">Responsable</th>
                    <th scope="col">Contrato</th>
                    <th scope="col">Empresa</th>
                    <th scope="col">Flujo</th>
                    <th scope="col">Resumen</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Baja</th>
                    <th scope="col">Alta</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                </table>                     
            </div>
        </div>             
    </div>
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar depósito</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body-edit">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary confirm-update" data-dismiss="modal">Confirmar</button>
            </div>
        </div>
    </div>
</div> 
<script>
    $(document).ready(function () {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    });
    $.ajax({
        url: '/api-stock/public/index.php/depots/get/all/all/all',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            let rows = data.result;
            let html = [];
            for (let i=0; i < rows.length; i++){
                var state = rows[i].estado;
                html.push(
                    `<tr class="content" idDepot="${rows[i].id}">
                        <td> ${rows[i].nombre} </td> 
                        <td> ${rows[i].estado} </td> 
                        <td> ${rows[i].ubicacion} </td> 
                        <td> ${rows[i].responsable} </td>
                        <td> ${rows[i].contrato} </td>
                        <td> ${rows[i].empresa} </td>
                        <td><button id="flow" class="flow btn btn-dark btn-sm" data-toggle="tooltip" data-placement="left" title="${item.Flujo}">Flujo</button></td>
                        <td><button id="resume" class="resume btn btn-dark btn-sm" data-toggle="tooltip" data-placement="left" title="${item.Resumen}">Resumen</button></td>
                        <td><button id="edit" class="edit-depot btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit">Editar</button></td>
                        <td>    <button class="down-depot btn btn-danger btn-sm">Baja</button>  </td>
                        <td>    <button class="up-depot btn btn-primary btn-sm">Alta</button>   </td>
                    </tr>`
                );
            }    
            $('#table-depots>tbody').html(html.join(''));   
            $('.edit-depot').click(function (e) {
                $('.div-edit').remove();
                $('.row-info').remove();
                e.preventDefault();
                var element = $(this)[0].parentElement.parentElement;
                var idElement = $(element).attr('idDepot');
                var url = "/api-stock/public/index.php/depots/getbyid/";
                $.ajax({
                    type: "GET",
                    url: url + idElement,
                    dataType: "json",
                    success: function (response) {
                        var edit = `
                            <div class="form-group div-edit div-name">
                                <label for="" class="col-form-label">Nombre</label>
                                <input id="name-edit" type="text" class="form-control text-capitalize" value="${response.result[0].nombre}">
                            </div>
                            <div class="form-group div-edit div-user">
                                <label for="inputState">Usuario</label>
                                <select id="user" class="form-control">
                                    <option value="">Seleccione usuario</option>
                                </select> 
                            </div>
                            <div class="form-group div-edit div-type">
                                <label for="inputState">Tipo de depósito</label>
                                <select id="type-depots" class="form-control">
                                    <option value="">Seleccione tipo de depósito</option>
                                    <option value="1">Almacen</option>
                                    <option value="2">Pañol</option>
                                    <option value="3">Carro</option>
                                    <option value="4">Casilla</option>
                                </select> 
                            </div>
                            <div class="form-group div-edit div-location">
                                <label for="" class="col-form-label">Ubicación</label>
                                <input id="location-edit" type="text" class="form-control text-capitalize" value="${response.result[0].ubicacion}"></input>
                            </div>
                            <div class="form-group div-edit div-contract">
                                <label for="" class="col-form-label">Contrato</label>
                                <input id="contract-edit" type="text" class="form-control text-capitalize" value="${response.result[0].contrato}"></input>
                            </div>
                            <div class="form-group div-edit div-company">
                                <label for="" class="col-form-label">Empresa</label>
                                <input id="company-edit" type="text" class="form-control text-capitalize" value="${response.result[0].empresa}"></input>
                            </div>
                            `
                            $('.modal-body-edit').append(edit);
                            $.ajax({
                            type: "GET",
                            url: "/api-stock/public/index.php/users/get/all",
                            dataType: "json",
                            success: function (response) {
                                console.log(response)
                                $.each(response.result, function(index, item) {
                                    var listSubtype = `<option value="${item.ID}">${item.nombre}</option>`;
                                    $('#user').append(listSubtype);      
                                });
                            }
                        });
                    }
                });
                $('.confirm-update').click(function (e) { 
                    e.preventDefault();
                    console.log(idElement);
                    data = {
                    id: idElement,
                    nombre: $("input[id=name-edit]").val(),
                    ubicacion: $("input[id=location-edit]").val(),
                    usuario: $("select[id=user]").val(),
                    contrato: $("input[id=contract-edit]").val(),
                    empresa: $("input[id=company-edit]").val(),
                    color: '#ffffff',
                    tipo: $("select[id=type-depots]").val(),
                    };
                    console.log(data);
                    $.ajax({
                        type: "POST",
                        url: '/api-stock/public/index.php/depots/addorupdate',
                        data: data,
                        success: function(data, status){
                            console.log(data + status);
                            $('#update-alert').addClass('alert alert-success')
                                .add('span')
                                .text('Depósito actualizado correctamente');
                            $("#update-alert").fadeTo(2000, 500).slideUp(500, function(){
                                $("#update-alert").slideUp(500);
                                $('span').remove();
                        });
                            location.reload();
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $('#update-alert').addClass('alert alert-danger')
                                .add('span')
                                .text('Error al actualizar depósito');
                            $("#update-alert").fadeTo(2000, 500).slideUp(500, function(){
                                $("#update-alert").slideUp(500);
                                $('span').remove();
                        });
                        },
                        dataType: 'json'
                    });
                });
            }); 
            $('.down-depot').click(function (e) { 
                e.preventDefault();
                var element = $(this)[0].parentElement.parentElement;
                var idElement = $(element).attr('idDepot');
                var url = '/api-stock/public/index.php/depots/down/' + idElement;
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: 'json',
                    success: function (response) {
                        $('#delete-alert').addClass('alert alert-success')
                            .add('span')
                            .text('Depósito deshabilitado');
                        $("#delete-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#delete-alert").slideUp(500);
                            $('span').remove();
                            location.reload();
                    });
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $('#delete-alert').addClass('alert alert-danger')
                            .add('span')
                            .text('Error al deshabilitar depósito');
                        $("#delete-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#delete-alert").slideUp(500);
                            $('span').remove();
                    });
                    },
                });
            });       
            $('.up-depot').click(function (e) { 
                e.preventDefault();
                var element = $(this)[0].parentElement.parentElement;
                var idElement = $(element).attr('idDepot');
                var url = '/api-stock/public/index.php/depots/up/' + idElement;
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: 'json',
                    success: function (response) {
                        $('#delete-alert').addClass('alert alert-success')
                            .add('span')
                            .text('Depósito habilitado');
                        $("#delete-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#delete-alert").slideUp(500);
                            $('span').remove();
                            location.reload();
                    });
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $('#delete-alert').addClass('alert alert-danger')
                            .add('span')
                            .text('Error al habilitar depósito');
                        $("#delete-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#delete-alert").slideUp(500);
                            $('span').remove();
                    });
                    },
                });
            });       
        },
        error: function(xhr, textStatus, errorThrown) {
        //called when there is an error
        }
    });
    $("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
        $(".content").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>
</body>
</html>
<!-- <td> <div style="background:${item.color}; width: 25px; height: 25px;"></div></td>  -->