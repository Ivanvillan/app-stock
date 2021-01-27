<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identificadores</title>
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
                <div class="card newCodeCard">
                    <div class="card-header">
                    <div class="row">
                        <div class="col col-md-6">
                            <h4>Identificadores</h4>
                        </div>
                        <div class="col">
                            <button id="backStock" class="btn btn-link float-right">Volver</button>
                        </div>
                    </div>
                    </div>
                    <div class="card-body addCodes">
                        <h4 class="card-title">Agregar identificador</h4>
                        <div class="row">
                        <div class="col">
                            <div id="success-alert" role="alert">
                            </div>
                            <div id="danger-alert" role="alert">
                            </div>
                            <div id="update-alert" role="alert">
                            </div>
                            <div id="delete-alert" role="alert">
                            </div>
                            <table id="table-addCodes" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Marca</th>
                                        <th>Detalle</th>
                                        <th>Identificador</th>
                                        <th>Stock</th>
                                        <th>Identificador</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <button id="sendCode" class="sendCode btn btn-success btn-sm float-right">Agregar</button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title title-userCode">Lista de identificadores del artículo</h4>
                        <table id="table-codes" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Identificador</th>
                                    <th>Disponible</th>
                                    <th>Estado</th>
                                    <th>Destino</th>
                                    <th class="col-edit">Editar</th>
                                    <th class="col-delete">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-list-userCodes mt-2">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Lista de identificadores del artículo</h4>
                        <table id="table-codes-user" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Identificador</th>
                                    <th>Disponible</th>
                                    <th>Estado</th>
                                    <th>Destino</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
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
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar identificador</h5>
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
            <h5 class="modal-title" id="exampleModalLabel">Eliminando identificador</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            ¿Deseas eliminar este identificador?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary confirm-delete" data-dismiss="modal">Confirmar</button>
        </div>
        </div>
    </div>
</div>
<script>
var selectedDepots = JSON.parse(window.localStorage.getItem("selectedDepots"));
var idElement = JSON.parse(window.localStorage.getItem("idElement"));
var typeArticle = JSON.parse(window.localStorage.getItem("typeArticle"));
$(document).ready(function () {
    console.log(selectedDepots);
    console.log(idElement);
    console.log(typeArticle);
    var urlItem = '/api-stock/public/index.php/stock/get/' + selectedDepots + '/' +  typeArticle + idElement;
    var urlCodesAdmin = '/api-stock/public/index.php/stock/codes/' + selectedDepots + '/' +  typeArticle + idElement + '/all';
    var urlCodesUser = '/api-stock/public/index.php/stock/codes/' + selectedDepots + '/' +  typeArticle + idElement + '/all';
    var urlInputsCodesUser = '/api-stock/public/index.php/stock/codes/' + '1/' +  typeArticle + idElement + '/1/' + selectedDepots;
    $.ajax({
    type: "GET",
    url: urlItem,
    dataType: "json",
    success: function (response) {
    var row = `<tr>
        <td> ${response.result[0].Producto} </td> 
        <td> ${response.result[0].marca} </td>
        <td> ${response.result[0].descripcion} </td>
        <td> ${response.result[0].codigos} </td>
        <td> ${response.result[0].existencia} </td>
        <td> <input type="text" name="code" class="code form-control" placeholder="Código"> </td>
        </tr>`;
    $('#table-addCodes>tbody').append(row);
    }
});
$('#backStock').click(function (e) { 
    e.preventDefault();
    window.history.back();
});
$('.sendCode').click(function () { 
    var typeArticleCodes = typeArticle.split('/')[0];
    var codeInput = $('input[name=code]').val();
    var dataCode = {
        idalmacen: selectedDepots,
        id: idElement,
        tipo: typeArticleCodes,
        identificador: codeInput,
    };
    console.log(dataCode)
    $.ajax({
        type: "POST",
        url: "/api-stock/public/index.php/products/codes/add",
        data: dataCode,
        dataType: "json",
        success: function (response) {
            console.log(response);
            $('#success-alert').addClass('alert alert-success')
            .add('span')
            .text('Código agregado');
        $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
            $('input[name=code]').val('')
            $("#success-alert").slideUp(500);
            $('span').remove();
            location.reload();
        });
        },
        error: function(xhr, textStatus, errorThrown) {
            $('#danger-alert').addClass('alert alert-danger')
                .add('span')
                .text('Error al agregar código');
            $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
            $("#danger-alert").slideUp(500);
        });
        }
    });
});
if (userprofile != 1) {
        $('.newCodeCard').remove();
        $('.col-edit').text('Ingresar');
        $('.title-userCode').text('Identificadores a ingresar');
        $('.col-delete').remove();
        $.ajax({
        type: "GET",
        url: urlInputsCodesUser,
        dataType: "json",
        success: function (response) {
            console.log(response);
            $.each(response.result, function (i, item) { 
                var dest = item.destino;
                if (dest == null) {
                    dest = 'Sin destino';
                }
                var listCode = `
                    <tr idCode="${item.idCodigo}">
                        <td>${item.identificador}</td>
                        <td>${item.disponible}</td>
                        <td>${item.estado}</td>
                        <td>${dest}</td>
                        <td><button id="inputCode" class="inputCode btn btn-dark">Ingresar</button></td>
                    </tr>`;
                $('#table-codes>tbody').append(listCode);
            });
            $('.inputCode').click(function (e) { 
                e.preventDefault();
                var element = $(this)[0].parentElement.parentElement;
                var idCode = $(element).attr('idCode');
                $.ajax({
                    type: "POST",
                    url: "/api-stock/public/index.php/stock/code/attach/" + idCode + '/' + selectedDepots,
                    dataType: "json",
                    success: function (response) {
                        window.location.reload();
                    }
                });
            });
            }
        });
        $.ajax({
        type: "GET",
        url: urlCodesUser,
        dataType: "json",
        success: function (response) {
            console.log(response);
            $.each(response.result, function (i, item) { 
                var dest = item.destino;
                if (dest == null) {
                    dest = 'Sin destino';
                }
                var listCode = `
                    <tr idCodeAction=${item.idCodigo}>
                        <td class="d-none">${item.idProducto}</td>
                        <td>${item.identificador}</td>
                        <td>${item.disponible}</td>
                        <td>${item.estado}</td>
                        <td>${dest}</td>
                        <td><button id="editCode" class="edit-code btn btn-warning" data-toggle="modal" data-target="#modal-edit"><i class='fas fa-edit'> </i></button></td>
                        <td><button id="deleteCode" class="delete-code btn btn-danger" data-toggle="modal" data-target="#modal-delete"><i class='fas fa-trash'> </i></button></td>
                    </tr>`;
                $('#table-codes-user>tbody').append(listCode);
            });
            $('.edit-code').click(function (e) {
                $('.div-edit').remove();
                $('.row-info').remove();
                e.preventDefault();
                var element = $(this)[0].parentElement.parentElement;
                var idElement = $(element).attr('idCodeAction');
                var idProduct = $(this).parent().parent().find('td').eq(0).html();
                var ident = $(this).parent().parent().find('td').eq(1).html();
                    var edit = `
                        <div class="form-group div-edit div-ident">
                            <label for="" class="col-form-label">Nombre</label>
                            <input id="ident-edit" type="text" class="form-control text-capitalize" value="${ident}">
                        </div>
                        <div class="form-group div-edit div-allow">
                            <label for="inputState">Habilitado</label>
                            <select id="allow-edit" class="form-control">
                                <option value="">Seleccione opción</option>
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select> 
                        </div>
                        `
                        $('.modal-body-edit').append(edit);
                    $('.confirm-update').click(function (e) { 
                        e.preventDefault();
                        console.log(idElement);
                        data = {
                        id: idProduct,
                        tipo: typeArticle.split('/')[0],
                        identificador: $("input[id=ident-edit]").val(),
                        habilitado: $("select[id=allow-edit]").val(),
                        codigo: idElement,
                        };
                        console.log(data);
                        $.ajax({
                            type: "POST",
                            url: '/api-stock/public/index.php/codes/update',
                            data: data,
                            success: function(data, status){
                                console.log(data + status);
                                $('#update-alert').addClass('alert alert-success')
                                    .add('span')
                                    .text('Identificador actualizado');
                                $("#update-alert").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#update-alert").slideUp(500);
                                    $('span').remove();
                            });
                                location.reload();
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                $('#update-alert').addClass('alert alert-danger')
                                    .add('span')
                                    .text('Error al actualizar identificador');
                                $("#update-alert").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#update-alert").slideUp(500);
                                    $('span').remove();
                            });
                            },
                            dataType: 'json'
                        });
                    });
                });
                $('.delete-code').click(function (e) { 
                    e.preventDefault();
                    var element = $(this)[0].parentElement.parentElement;
                    var idElement = $(element).attr('idCodeAction')
                    console.log(idElement);
                    console.log('idElement');
                    $('.confirm-delete').click(function (e) { 
                        e.preventDefault();
                        var url = '/api-stock/public/index.php/codes/delete';
                        data = {
                            codigo: idElement
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
            }
        });
    }else{
        $('.row-list-userCodes').remove();
        $.ajax({
        type: "GET",
        url: urlCodesAdmin,
        dataType: "json",
        success: function (response) {
            console.log(response);
            $.each(response.result, function (i, item) { 
                var dest = item.destino;
                if (dest == null) {
                    dest = 'Sin destino';
                }
                var listCode = `
                    <tr idCodeAction=${item.idCodigo}>
                        <td class="d-none">${item.idProducto}</td>
                        <td>${item.identificador}</td>
                        <td>${item.disponible}</td>
                        <td>${item.estado}</td>
                        <td>${dest}</td>
                        <td><button id="editCode" class="edit-code btn btn-warning" data-toggle="modal" data-target="#modal-edit"><i class='fas fa-edit'> </i></button></td>
                        <td><button id="deleteCode" class="delete-code btn btn-danger" data-toggle="modal" data-target="#modal-delete"><i class='fas fa-trash'> </i></button></td>
                    </tr>`;
                $('#table-codes>tbody').append(listCode);
            });
            $('.edit-code').click(function (e) {
                $('.div-edit').remove();
                $('.row-info').remove();
                e.preventDefault();
                var element = $(this)[0].parentElement.parentElement;
                var idElement = $(element).attr('idCodeAction');
                console.log(idElement);
                var idProduct = $(this).parent().parent().find('td').eq(0).html();
                var ident = $(this).parent().parent().find('td').eq(1).html();
                    var edit = `
                        <div class="form-group div-edit div-ident">
                            <label for="" class="col-form-label">Identificador</label>
                            <input id="ident-edit" type="text" class="form-control text-capitalize" value="${ident}">
                        </div>
                        <div class="form-group div-edit div-allow">
                            <label for="inputState">Habilitado</label>
                            <select id="allow-edit" class="form-control">
                                <option value="">Seleccione opción</option>
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select> 
                        </div>
                        `
                        $('.modal-body-edit').append(edit);
                    $('.confirm-update').click(function (e) { 
                        e.preventDefault();
                        console.log(idElement);
                        data = {
                        id: idProduct,
                        tipo: typeArticle.split('/')[0],
                        identificador: $("input[id=ident-edit]").val(),
                        habilitado: $("select[id=allow-edit]").val(),
                        codigo: idElement,
                        };
                        console.log(data);
                        $.ajax({
                            type: "POST",
                            url: '/api-stock/public/index.php/products/codes/update',
                            data: data,
                            success: function(data, status){
                                console.log(data + status);
                                $('#update-alert').addClass('alert alert-success')
                                    .add('span')
                                    .text('Identificador actualizado');
                                $("#update-alert").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#update-alert").slideUp(500);
                                    $('span').remove();
                                    location.reload();
                            });
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                $('#update-alert').addClass('alert alert-danger')
                                    .add('span')
                                    .text('Error al actualizar identificador');
                                $("#update-alert").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#update-alert").slideUp(500);
                                    $('span').remove();
                            });
                            },
                            dataType: 'json'
                        });
                    });
                });
                $('.delete-code').click(function (e) { 
                    e.preventDefault();
                    var element = $(this)[0].parentElement.parentElement;
                    var idElement = $(element).attr('idCodeAction')
                    console.log(idElement);
                    console.log('idElement');
                    $('.confirm-delete').click(function (e) { 
                        e.preventDefault();
                        var url = '/api-stock/public/index.php/products/codes/delete';
                        data = {
                            codigo: idElement
                        };
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: data,
                            dataType: 'json',
                            success: function (response) {
                                $('#delete-alert').addClass('alert alert-success')
                                    .add('span')
                                    .text('Identificador eliminado');
                                $("#delete-alert").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#delete-alert").slideUp(500);
                                    $('span').remove();
                                    location.reload();
                            });
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                $('#delete-alert').addClass('alert alert-danger')
                                    .add('span')
                                    .text('Error al eliminar identificador');
                                $("#delete-alert").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#delete-alert").slideUp(500);
                                    $('span').remove();
                            });
                            },
                        });
                    });
                }); 
            }
        });
    }
});
</script>
</body>
</html>