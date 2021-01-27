<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar a reparación</title>
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'].'/app-stock/head/head.php') ?>
<div class="container">
    <div class="card">
        <div class="card-header">
        <h2>Reparación</h2>
        </div>
        <div class="card-body">
        <div id="success-alert" role="alert">
        </div>
        <div id="danger-alert" role="alert">
        </div>
        <h4 class="card-title">Completa los datos para enviar a reparar un artículo</h4>
        <div class="row">
            <div class="col col-md-3 form-group">
                <strong><label for="">Nombre</label></strong>
                <br>
                <span class="article_name"></span>
            </div>
            <div class="col col-md-3 form-group">
                <strong><label for="">Marca</label></strong>
                <br>
                <span class="article_mark"></span>
            </div>
            <div class="col col-md-3 form-group">
                <strong><label for="">Detalle</label></strong>
                <br>
                <span class="article_detail"></span>
            </div>
            <div class="col col-md-3 form-group">
                <strong><label for="">Descripción</label></strong>
                <br>
                <span class="article_description"></span>
            </div>
        </div>
        <div class="row">
            <div class="col col-md-4 form-group">
                <strong><label for="">Cantidad</label></strong>
                <br>
                <span class="article_exist"></span>
            </div>
            <div class="col col-md-4 form-group">
                <strong><label for="">Identificador</label></strong>
                <select name="" id="move-codes" class="form-control">
                    <option value="">Seleccionar identificador</option>
                </select>
            </div>
            <div class="col col-md-4 form-group">
                <strong><label for="">Tipo de movimiento</label></strong>
                <select class="form-control" name="" id="type-move">
                    <option value="">Selecciona movimientos</option>
                    <option value="maintenance">Mantenimiento</option>
                    <option value="repair">Reparación</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col form-group">
                <strong><label for="">Descripción</label></strong>
                <textarea class="form-control" name="description" id="" rows="3"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col form-group">
                <button id="sendRepair" class="btn btn-primary float-right">Enviar</button>
            </div>
        </div>
        </div>
    </div>
</div>
<script>
var selectedDepots = JSON.parse(window.localStorage.getItem("selectedDepots"));
var idElement = JSON.parse(window.localStorage.getItem("idElement"));
var typeArticle = JSON.parse(window.localStorage.getItem("typeArticle"));
var selectedCode = "";
var selectedMove = "";
    $(document).ready(function () {
    console.log(selectedDepots);
    console.log(idElement);
    console.log(typeArticle);
    var urlItem = '/api-stock/public/index.php/stock/get/' + selectedDepots + '/' +  typeArticle + idElement;
    var urlCodesAdmin = '/api-stock/public/index.php/stock/codes/' + selectedDepots + '/' +  typeArticle + idElement + '/0';
    var urlCodesUser = '/api-stock/public/index.php/stock/codes/' + '1/' +  typeArticle + idElement + '1/' + selectedDepots;
    $.ajax({
        type: "GET",
        url: urlItem,
        dataType: "json",
        success: function (data) {
            console.log(data);
            $('.article_name').text(data.result[0].Producto);
            $('.article_mark').text(data.result[0].marca);
            $('.article_detail').text(data.result[0].detalle);
            $('.article_description').text(data.result[0].descripcion);
            $('.article_exist').text(data.result[0].existencia);
            if (data.result[0].existencia <= 0) {
                $('#sendRepair').prop('disabled', true);
            }
        }
    });
    $("#move-codes").change(function(){
        selectedCode = $(this).children("option:selected").val();
        console.log(selectedCode);
    });
    $("#type-move").change(function(){
        selectedMove = $(this).children("option:selected").val();
        console.log(selectedMove);
    });
    if (userprofile != 1) {
        $.ajax({
        type: "GET",
        url: urlCodesAdmin,
        dataType: "json",
        success: function (response) {
            console.log(response);
            $.each(response.result, function (i, item) { 
                var listCode = `
                    <option value="${item.identificador}">${item.identificador}</option>`;
                $('#move-codes').append(listCode);
            });
            }
        });
        $('#sendRepair').click(function (e) { 
            e.preventDefault();
            var url = '/api-stock/public/index.php/movements/insert/' + selectedDepots + '/' + typeArticle + selectedMove;
            var data = {
                id: idElement,
                identificador: selectedCode,
                descripcion: $("textarea[name=description]").val(),
                idusuario: userid
            };
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    $('#success-alert').addClass('alert alert-success')
                        .add('h6')
                        .text('Movimiento creado correctamente');
                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                        window.location.reload();
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    $('#danger-alert').addClass('alert alert-danger')
                            .add('h6')
                            .text('Error al crear movimiento');
                        $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#danger-alert").slideUp(500);
                    });
                }
            });
        });
    }else{
        $.ajax({
        type: "GET",
        url: urlCodesAdmin,
        dataType: "json",
        success: function (response) {
            console.log(response);
            $.each(response.result, function (i, item) { 
                var listCode = `
                    <option value="${item.identificador}">${item.identificador}</option>`;
                $('#move-codes').append(listCode);
            });
            }
        });
        $('#sendRepair').click(function (e) { 
            e.preventDefault();
            var url = '/api-stock/public/index.php/movements/insert/' + selectedDepots + '/' + typeArticle + selectedMove;
            var data = {
                id: idElement,
                identificador: selectedCode,
                descripcion: $("textarea[name=description]").val(),
                idusuario: userid
            };
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    $('#success-alert').addClass('alert alert-success')
                        .add('h6')
                        .text('Movimiento creado correctamente');
                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                        window.location.reload();
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    $('#danger-alert').addClass('alert alert-danger')
                            .add('h6')
                            .text('Error al crear movimiento');
                        $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#danger-alert").slideUp(500);
                    });
                }
            });
        });
    }
    });
</script>
</body>
</html>