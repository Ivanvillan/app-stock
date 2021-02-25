<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baja de Stock</title>
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'].'/app-stock/head/head.php') ?>
<div class="container">
    <div class="card">
        <div class="card-header">
        <h2>Baja de artículo</h2>
        </div>
        <div class="card-body">
        <div id="success-alert" role="alert">
        </div>
        <div id="danger-alert" role="alert">
        </div>
        <h4 class="card-title">Completa los datos para crear la baja de artículo</h4>
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
                <strong><label for="">Cantidad de baja</label></strong>
                <input type="text" name="cant" class="cant form-control">
            </div>
            <div class="col col-md-4 form-group">
                <strong><label for="">Identificador</label></strong>
                <select name="" id="move-codes" class="form-control">
                    <option value="">Seleccionar identificador</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col form-group">
                <strong><label for="">Descripción</label></strong>
                <textarea id="description-area" class="form-control" name="" id="" rows="3"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col form-group">
                <button id="sendReduce" class="btn btn-primary float-right">Enviar</button>
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
    $(document).ready(function () {
    console.log(selectedDepots);
    console.log(idElement);
    console.log(typeArticle);
    var urlItem = '/api-stock/public/index.php/stock/get/' + selectedDepots + '/' +  typeArticle + idElement;
    var urlCodesAdmin = '/api-stock/public/index.php/stock/codes/' + selectedDepots + '/' +  typeArticle + idElement + '/0';
    var urlCodesUser = '/api-stock/public/index.php/stock/codes/' + '1/' +  typeArticle + idElement + '1/' + selectedDepots;
    var urlItem = '/api-stock/public/index.php/stock/get/' + selectedDepots + '/' +  typeArticle + idElement;
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
            $('.cant').attr({
                "max" : data.result[0].existencia,        // substitute your own
                "min" : 1          // values (or variables) here
            });
            $('.cant').change(function() {
                var max = parseInt($(this).attr('max'));
                var min = parseInt($(this).attr('min'));
                if ($(this).val() > max)
                {
                    $(this).val(max);
                }
                else if ($(this).val() < min)
                {
                    $(this).val(min);
                }       
            });
            if (data.result[0].existencia <= 0) {
                $('#sendReduce').prop('disabled', true);
            }
        }
    });
    if (userprofile != 1) {
        $.ajax({
        type: "GET",
        url: urlCodesUser,
        dataType: "json",
        success: function (response) {
            console.log(response.result);
            $.each(response.result, function (i, item) { 
                var identUse = item.identificador;
                if (item.disponible != "SÍ") {
                    identUse = identUse + " (Utilizado)"
                }
                var listCode = `
                    <option value="${item.identificador}">${identUse}</option>`;
                $('#move-codes').append(listCode);
            });
            }
        });
        $('#sendReduce').click(function (e) { 
            e.preventDefault();
            var url = '/api-stock/public/index.php/movements/insert/' + selectedDepots + '/' + typeArticle + selectedMove;
            var data = {
                id: idElement,
                identificador: selectedCode,
                observaciones: $("#description-area").val(),
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
                        .text('Baja hecha correctamente');
                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                        window.location.reload();
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    $('#danger-alert').addClass('alert alert-danger')
                            .add('h6')
                            .text('Error al crear baja');
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
                var identUse = item.identificador;
                if (item.disponible != "SÍ") {
                    identUse = identUse + " (Utilizado)"
                }
                var listCode = `
                    <option value="${item.identificador}">${identUse}</option>`;
                $('#move-codes').append(listCode);
            });
            }
        });
        $('#sendReduce').click(function (e) { 
            e.preventDefault();
            var url = '/api-stock/public/index.php/stock/register/' + selectedDepots + '/' + typeArticle + 'output';
            var data = {
                id: idElement,
                cantidad: $("input[name=cant]").val(),
                control: 0,
                observaciones: $("#description-area").val(),
                usuario: userid,
                baja: 1
            };
            console.log(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    $('#success-alert').addClass('alert alert-success')
                        .add('h6')
                        .text('Baja hecha correctamente');
                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                        window.location.reload();
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    $('#danger-alert').addClass('alert alert-danger')
                            .add('h6')
                            .text('Error al crear baja');
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