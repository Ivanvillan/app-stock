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
                <div class="card addCodes">
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
                    <div class="card-body ">
                        <h4 class="card-title">Agregar identidicador</h4>
                        <div class="row">
                        <div class="col">
                            <div id="success-alert" role="alert">
                            </div>
                            <div id="danger-alert" role="alert">
                            </div>
                            <table id="table-addCodes" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Marca</th>
                                        <th>Detalle</th>
                                        <th>Descripcion</th>
                                        <th>Identidicador</th>
                                        <th>Stock</th>
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
                        <h4 class="card-title">Lista de identificadores del artículo</h4>
                        <table id="table-codes" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Identificador</th>
                                    <th>Disponible</th>
                                    <th>Estado</th>
                                    <th>Destino</th>
                                    <th>Liberar</th>
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
var selectedDepotsOrigen = JSON.parse(window.localStorage.getItem("selectedDepotsOrigen"));
var selectedDepotsDestino = JSON.parse(window.localStorage.getItem("selectedDepotsDestino"));
var idArticle = JSON.parse(window.localStorage.getItem("idArticle"));
var typeArticle = JSON.parse(window.localStorage.getItem("typeArticle"));
var codeInput = JSON.parse(window.localStorage.getItem("codeInput"));
var returnCode = JSON.parse(window.localStorage.getItem("returnCode"));
$(document).ready(function () {
    console.log(selectedDepotsDestino);
    console.log(idArticle);
    console.log(typeArticle);
    console.log(codeInput);
    var typeArt = typeArticle.split(' ')[1];
    switch (typeArt) {
        case 'Herramienta':
        typeArt = "tools/"
        break;
        case 'Equipo':
        typeArt = "hardware/"
        break;
        case 'Consumible':
        typeArt = "consumables/"
        break;
        case 'Indumentaria':
        typeArt = "dress/"
        break;
    }
    var urlItem = '/api-stock/public/index.php/stock/get/' + selectedDepotsOrigen + '/' +  typeArt + idArticle;
    var urlCodesAdmin = '/api-stock/public/index.php/stock/codes/' + selectedDepotsOrigen + '/' +  typeArt + idArticle + '/all';
    var urlCodesUser = '/api-stock/public/index.php/stock/codes/' + selectedDepotsOrigen + '/' +  typeArt + idArticle + '1/' + selectedDepotsDestino;
    $.ajax({
    type: "GET",
    url: urlItem,
    dataType: "json",
    success: function (data) {
        let rows = data.result;
        let html = [];
        for (let i=0; i < rows.length; i++){
            html.push(
            `<tr>
            <td> ${rows[i].Producto} </td> 
            <td> ${rows[i].marca} </td> 
            <td> ${rows[i].detalle} </td> 
            <td> ${rows[i].descripcion} </td>
            <td> ${rows[i].codigos} </td>
            <td> ${rows[i].existencia} </td>
            <td> <input type="text" name="code" class="code form-control" placeholder="Código"> </td>
            </tr>`
            );
        }    
        $('#table-addCodes>tbody').html(html.join(''));
    }
});
$('#backStock').click(function (e) { 
    e.preventDefault();
    window.history.back();
});
$('.sendCode').click(function () { 
    var typeArticleCodes = typeArt.split('/')[0];
    var codeInput = $('input[name=code]').val();
    var dataCode = {
        idalmacen: selectedDepotsOrigen,
        id: idArticle,
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
        if (returnCode == 1) {
        $('.addCodes').remove();
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
                    <tr>
                        <td>${item.identificador}</td>
                        <td>${item.disponible}</td>
                        <td>${item.estado}</td>
                        <td>${dest}</td>
                        <td><button id="liberateCode" class="liberateCode btn btn-warning">Enviar</button></td>
                    </tr>`;
                $('#table-codes>tbody').append(listCode);
                $('.liberateCode').click(function (e) { 
                    e.preventDefault();
                    var code = $(this)[0].parentElement.parentElement;
                    var idCode = $(code).attr('idCode');
                    $.ajax({
                        type: "POST",
                        url: "/api-stock/public/index.php/stock/code/liberate/" + idCode + '/' + selectedDepotsDestino,
                        dataType: "json",
                        success: function (response) {
                            $.ajax({
                                type: "POST",
                                url: "/api-stock/public/index.php/stock/code/attach/" + idCode + '/' + selectedDepotsDestino,
                                dataType: "json",
                                success: function (response) {
                                    console.log(response);
                                    console.log('autoattach');
                                    window.location.reload();
                                }
                            });
                        }
                    });
                });
            });
            }
        });
        }else{
        $('.addCodes').remove();
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
                    <tr>
                        <td>${item.identificador}</td>
                        <td>${item.disponible}</td>
                        <td>${item.estado}</td>
                        <td>${dest}</td>
                        <td><button id="inputCode" class="inputCode btn btn-warning">Ingresar</button></td>
                    </tr>`;
                $('#table-codes>tbody').append(listCode);
                $('.inputCode').click(function (e) { 
                    e.preventDefault();
                    var code = $(this)[0].parentElement.parentElement;
                    var idCode = $(code).attr('idCode');
                    $.ajax({
                        type: "POST",
                        url: "/api-stock/public/index.php/stock/code/attach/" + idCode + '/' + selectedDepotsDestino,
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            console.log('attach');
                            window.location.reload();
                        }
                    });
                });
            });
            }
        });
    }
    }else{
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
                    <tr idCode="${item.idCodigo}">
                        <td>${item.identificador}</td>
                        <td>${item.disponible}</td>
                        <td>${item.estado}</td>
                        <td>${dest}</td>
                        <td><button id="liberateCode" class="liberateCode btn btn-warning">Liberar</button></td>
                    </tr>`;
                $('#table-codes>tbody').append(listCode);
                $('.liberateCode').click(function (e) { 
                    e.preventDefault();
                    var code = $(this)[0].parentElement.parentElement;
                    var idCode = $(code).attr('idCode');
                    if (codeInput == 1) {
                        $.ajax({
                            type: "POST",
                            url: "/api-stock/public/index.php/stock/code/liberate/" + idCode + '/' + selectedDepotsDestino,
                            dataType: "json",
                            success: function (response) {
                                $.ajax({
                                    type: "POST",
                                    url: "/api-stock/public/index.php/stock/code/attach/" + idCode + '/' + selectedDepotsDestino,
                                    dataType: "json",
                                    success: function (response) {
                                        console.log(response);
                                        console.log('autoattach');
                                        window.location.reload();
                                    }
                                });
                            }
                        });
                    }else{
                        $.ajax({
                            type: "POST",
                            url: "/api-stock/public/index.php/stock/code/liberate/" + idCode + '/' + selectedDepotsDestino,
                            dataType: "json",
                            success: function (response) {
                                console.log(response);
                                window.location.reload();
                            }
                        });
                    }
                });
            });
            }
        });
    }
});
</script>
</body>
</html>