<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indumentarias</title>
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
        <div class="form-group mb-2 col-md-4">
            <label for="inputState">Selecciona tipo</label>
            <select id="select-articles" onchange="location = this.value;" class="form-control">
                <option>Selecciona tipo</option>
                <option value="list_tool.php">Herramientas</option>
                <option value="list_hardware.php">Equipos</option>
                <option value="list_consumable.php">Consumibles</option>
                <option value="list_dress.php">Indumentaria</option>
            </select> 
        </div>
        <div class="form-group mb-2 col-md-4">
            <label for="inputState">Selecciona un subtipo</label>
            <select id="subtype-dress" class="form-control" onChange="filterText()">
                <option value="all">Todos los subtipos</option>
            </select> 
        </div>
        <div class="col form-group mb-2 col-md-4">
            <label for="inputSearch">Buscar articulo</label>
            <input type="search" id="search" class="form-control" placeholder="Buscador">
        </div>
    </div>
    <div class="row">
        <div class="col form-group col-md-6">
            <div class="btn btn-danger export-pdf">Exportar en PDF</div>
            <div class="btn btn-success export-excel">Exportar en Excel</div>
        </div>
    </div>
    <div id="update-alert" role="alert">
    </div>
    <div id="delete-alert" role="alert">
    </div>
    <div class="row">
        <div class="col">
            <h3>Indumentaria</h3>
            <table id="table-dress" class="table table-hover">
                <thead>
                    <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Detalle</th>
                    <th scope="col">Ver</th>
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
<div class="modal fade" id="modal-info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Información de artículo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body-info">
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar artículo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body modal-body-edit">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-confirm">Confirmar</button>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Eliminando artículo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            ¿Deseas eliminar este artículo?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary confirm-delete" data-dismiss="modal">Confirmar</button>
        </div>
        </div>
    </div>
</div> 
<div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar artículo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            ¿Confirma que ha indicado un subtipo al artículo?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary confirm-update" data-dismiss="modal">Confirmar</button>
        </div>
        </div>
    </div>
</div> 
</div>
<script src="https://unpkg.com/jspdf@1.5.3/dist/jspdf.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable@3.5.3/dist/jspdf.plugin.autotable.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script>
$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/products/get/dress/0",
        dataType: "json",
        success: function (data) {
            let rows = data.result;
                let html = [];
                for (let i=0; i < rows.length; i++){
                    html.push(
                 `<tr class="content" idDress="${rows[i].id}">
                    <td class="nameDress"> ${rows[i].nombre} </td> 
                    <td class="nameDress"> ${rows[i].marca} </td> 
                    <td class="nameDress"> ${rows[i].detalle} </td> 
                    <td class="nameDress"> ${rows[i].descripcion} </td>
                    <td> <button class='info-dress btn btn-info' data-toggle="modal" data-target="#modal-info"> <i class='fas fa-file-alt'> </i> </button> </td>
                    <td> <button class='edit-dress btn btn-warning'  data-toggle="modal" data-target="#modal-edit"> <i class='fas fa-edit'> </i> </button> </td>
                    <td> <button class='delete-dress btn btn-danger' data-toggle="modal" data-target="#modal-delete"> <i class='fas fa-trash <'> </i> </button> </td>
                    </tr>`
                    );
                }    
            $('#table-dress>tbody').html(html.join(''));
            $('.info-dress').click(function (e) { 
                $('.div-edit').remove();
                $('.row-info').remove();
                e.preventDefault();
                var element = $(this)[0].parentElement.parentElement;
                var idElement = $(element).attr('idDress')
                var url = "/api-stock/public/index.php/products/getbyid/dress/"
                $.ajax({
                    type: "GET",
                    url: url + idElement,
                    dataType: "json",
                    success: function (response) {
                        var movement = response.result[0].movimientos;
                        if (movement == null) {
                            movement = "Sin movimientos"
                        }else{
                            movement = response.result[0].movimientos;
                        }
                        var es = response.result[0].StockResumen;
                        if (es == null) {
                            es = "Sin E/S"
                        }else{
                            es = response.result[0].StockResumen;
                        }
                        var stock = response.result[0].Existencia;
                        if (stock == null) {
                            stock = "Sin stock"
                        }
                        console.log(response.result[0]);
                        var info = `
                                <div class="row my-2 row-info row-move">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Movimientos</h5>
                                            </div>
                                            <div class="card-body">
                                                <span id="move-info">${movement}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-2 row-info row-stock">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Entradas y salidas</h5>
                                            </div>
                                            <div class="card-body">
                                                <span id="stock-info">${es}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-2 row-info row-stock">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Stock</h5>
                                            </div>
                                            <div class="card-body">
                                                <span id="stock-info">${stock}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                        $('.modal-body').append(info);
                    }
                });
            });
            $('.edit-dress').click(function (e) { 
                $('.div-edit').remove();
                $('.row-info').remove();
                e.preventDefault();
                var element = $(this)[0].parentElement.parentElement;
                var idElement = $(element).attr('idDress')
                var url = "/api-stock/public/index.php/products/getbyid/dress/"
                $.ajax({
                    type: "GET",
                    url: url + idElement,
                    dataType: "json",
                    success: function (response) {
                        var waistDress = response.result[0]["descripcion"];
                        waistDress = waistDress.replace(/[^0-9]/gi, '');
                        var numberDress = parseInt(waistDress, 10);
                        var edit = `
                            <div class="form-group div-edit div-type-dress">
                                <label for="inputState">Tipo de indumentaria</label>
                                <select id="subtype-edit" class="form-control">
                                    <option value="0" selected>Seleccione subtipo</option>
                                </select> 
                            </div>
                            <div class="form-group div-edit div-name">
                                <label for="" class="col-form-label">Nombre</label>
                                <input id="name-edit" type="text" class="form-control text-capitalize" value="${response.result[0].nombre}">
                            </div>
                            <div class="form-group div-edit div-mark">
                                <label for="" class="col-form-label">Marca</label>
                                <input id="mark-edit" type="text" class="form-control text-capitalize" value="${response.result[0].marca}"></input>
                            </div>
                            <div class="form-group div-edit div-model">
                                <label for="" class="col-form-label">Detalle</label>
                                <input id="detail-edit" type="text" class="form-control text-capitalize" value="${response.result[0].detalle}"></input>
                            </div>
                            <div class="form-group div-edit div-waist">
                                <label for="" class="col-form-label">Talle</label>
                                <input id="waist-edit" type="text" class="form-control text-capitalize" value="${numberDress}"></input>
                            </div>
                            `;
                            $('.modal-body-edit').append(edit);
                            $.ajax({
                            type: "GET",
                            url: "/api-stock/public/index.php/products/subtypes/dress",
                            dataType: "json",
                            success: function (response) {
                                console.log(response)
                                $.each(response.result, function(index, item) {
                                    var listSubtype = `<option value="${item.id}">${item.nombre}</option>`;
                                    $('#subtype-edit').append(listSubtype);      
                                });
                            }
                        });
                    }
                });
                $('.confirm-update').click(function (e) { 
                    e.preventDefault();
                    data = {
                        id: idElement,
                        nombre: $("input[id=name-edit]").val(),
                        marca: $("input[id=mark-edit]").val(),
                        talle: $("input[id=waist-edit]").val(),
                        detalle: $("input[id=detail-edit]").val(),
                        tipo: $("select[id=subtype-edit]").val(),
                        codigo: $("input[id=code-edit]").val(),
                    };
                    console.log(data);
                    $.ajax({
                        type: "POST",
                        url: '/api-stock/public/index.php/products/addorupdate/dress',
                        data: data,
                    success: function(data, status){
                        console.log(data + status);
                        $('#update-alert').addClass('alert alert-success')
                            .add('span')
                            .text('Artículo actualizado correctamente');
                        $("#update-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#update-alert").slideUp(500);
                            $('span').remove();
                    });
                            location.reload();
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $('#update-alert').addClass('alert alert-danger')
                            .add('span')
                            .text('Error al actualizar artículo');
                        $("#update-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#update-alert").slideUp(500);
                            $('span').remove();
                    });
                    },
                    dataType: 'json'
                });
                });
            });
            $('.delete-dress').click(function (e) { 
                $('.div-edit').remove();
                $('.row-info').remove();
                e.preventDefault();
                var element = $(this)[0].parentElement.parentElement;
                var idElement = $(element).attr('idDress')
                $('.confirm-delete').click(function (e) { 
                    e.preventDefault();
                    var url = "/api-stock/public/index.php/products/drop/dress/";
                    $.ajax({
                        type: "POST",
                        url: url + idElement,
                        dataType: 'json',
                        success: function (response) {
                            if (response.message != "El Producto posee movimientos asociados") {
                                $('#delete-alert').addClass('alert alert-success')
                                .add('span')
                                .text('Artículo eliminado correctamente');
                                $("#delete-alert").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#delete-alert").slideUp(500);
                                    $('span').remove();
                                });
                                location.reload();
                            }else{
                                $('#delete-alert').addClass('alert alert-warning')
                                .add('span')
                                .text(response.message);
                                $("#delete-alert").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#delete-alert").slideUp(500);
                                    $('span').remove();
                                });
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $('#delete-alert').addClass('alert alert-danger')
                                .add('span')
                                .text('Error al eliminar artículo');
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
    $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/products/subtypes/dress",
        dataType: "json",
        success: function (response) {
            console.log(response)
            $.each(response.result, function(index, item) {
                var listSubtype = `<option value="${item.nombre}">${item.nombre}</option>`;
                $('#subtype-dress').append(listSubtype);      
            });
        }
    });
    $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
            $(".content").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    $('.export-pdf').click(function(event) {
          var doc = new jsPDF()
          doc.autoTable({ html: '.table' })
          doc.save("Indumentaria" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".pdf")
        });
    $('.export-excel').click(function(event) {
        var table = $('.table');
        if(table && table.length){

        $(table).table2excel({
        //exclude: ".noExl",
        name: "Indumentaria",
        filename: "Indumentaria" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
        fileext: ".xls",
        exclude_img: true,
        exclude_links: true,
        exclude_inputs: true,
        preserveColors: false
        });
        }
    });
});
function filterText(){  
    var rex = new RegExp($('#subtype-dress').val());
    if(rex =="/all/"){clearFilter()}else{
            $('.content').hide();
            $('.content').filter(function() {
            return rex.test($(this).text());
            }).show();
        }
    }
    
function clearFilter(){
    $('#subtype-dress').val('all');
    $('.content').show();
}
</script>
</body>
</html>