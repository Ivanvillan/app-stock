<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de usuarios</title>
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
                <label for="inputSearch">Buscar usuario</label>
                <input type="search" class="form-control" placeholder="Buscador">
            </div>
        </div>
        <div class="row">
            <div class="col">
            <div id="update-alert" role="alert">
            </div>
            <div id="delete-alert" role="alert">
            </div>
            <table id="table-users" class="table table-hover">
                <thead>
                    <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Email</th>
                    <th scope="col">Perfil</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Eliminar</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Editar usuario</h5>
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
<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Eliminando usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            ¿Deseas eliminar este usuario?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary confirm-delete" data-dismiss="modal">Confirmar</button>
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
    jQuery.ajax({
        url: '/api-stock/public/index.php/users/get/all',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
        console.log(data);
        $.each(data.result, function (i, item) { 
            var row = `<tr idUser="${item.ID}" class="content">
                <td> ${item.nombre} </td> 
                <td> ${item.email} </td>
                <td> ${item.perfil} </td>
                <td><button id="edit" class="edit-user btn btn-warning" data-toggle="modal" data-target="#modal-edit">Editar</button></td>
                <td><button id="down" class="delete-user btn btn-danger" data-toggle="modal" data-target="#modal-delete">Eliminar</button></td>
                </tr>`;
        $('#table-users>tbody').append(row);
        });   
        $('.edit-user').click(function (e) {
            $('.div-edit').remove();
            $('.row-info').remove();
            e.preventDefault();
            var element = $(this)[0].parentElement.parentElement;
            var idElement = $(element).attr('idUser');
            var url = "/api-stock/public/index.php/users/getbyid/";
            $.ajax({
                type: "GET",
                url: url + idElement,
                dataType: "json",
                success: function (response) {
                    console.log(response.result);
                    var edit = `
                        <div class="form-group div-edit div-typeuser">
                            <label for="inputState">Tipo de accesos</label>
                            <select id="typeuser-edit" class="form-control">
                                <option value="">Seleccione tipo</option>
                                <option value="1">Administrador</option>
                                <option value="2">Usuario</option>
                            </select> 
                        </div>
                        <div class="form-group div-edit div-name">
                            <label for="" class="col-form-label">Nombre</label>
                            <input id="name-edit" type="text" class="form-control text-capitalize" value="${response.result[0].nombre}">
                        </div>
                        <div class="form-group div-edit div-email">
                            <label for="" class="col-form-label">Email</label>
                            <input id="email-edit" type="text" class="form-control" value="${response.result[0].email}"></input>
                        </div>
                        <div class="form-group div-edit div-passwrd">
                            <label for="" class="col-form-label">Contraseña</label>
                            <input id="passwrd-edit" type="password" class="form-control"></input>
                        </div>
                        `
                        $('.modal-body-edit').append(edit);
                }
            });
            $('.confirm-update').click(function (e) { 
                e.preventDefault();
                console.log(idElement);
                data = {
                nombre: $("input[id=name-edit]").val(),
                email: $("input[id=email-edit]").val(),
                contrasenia: $("input[id=passwrd-edit]").val(),
                perfil: $("select[id=typeuser-edit]").val(),
                id: idElement,
                };
                console.log(data);
                $.ajax({
                    type: "POST",
                    url: '/api-stock/public/index.php/users/update',
                    data: data,
                    success: function(data, status){
                        console.log(data + status);
                        $('#update-alert').addClass('alert alert-success')
                            .add('span')
                            .text(data.response);
                        $("#update-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#update-alert").slideUp(500);
                            $('span').remove();
                    });
                        location.reload();
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $('#update-alert').addClass('alert alert-danger')
                            .add('span')
                            .text('Error al actualizar usuario');
                        $("#update-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#update-alert").slideUp(500);
                            $('span').remove();
                    });
                    },
                    dataType: 'json'
                });
            });
        });
        $('.delete-user').click(function (e) { 
            e.preventDefault();
            var element = $(this)[0].parentElement.parentElement;
            var idElement = $(element).attr('idUser')
            console.log(idElement);
            console.log('idElement');
            $('.confirm-delete').click(function (e) { 
                e.preventDefault();
                var url = '/api-stock/public/index.php/users/delete';
                data = {
                    id: idElement
                };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'json',
                    success: function (response) {
                        $('#delete-alert').addClass('alert alert-success')
                            .add('span')
                            .text(response.response);
                        $("#delete-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#delete-alert").slideUp(500);
                            $('span').remove();
                            location.reload();
                    });
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $('#delete-alert').addClass('alert alert-danger')
                            .add('span')
                            .text('Error al eliminar usuario');
                        $("#delete-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#delete-alert").slideUp(500);
                            $('span').remove();
                    });
                    },
                });
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