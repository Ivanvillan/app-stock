<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadisticas de consumibles</title>
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
        <div class="col form-group col-md-6">
            <div class="btn btn-danger export-pdf">Exportar en PDF</div>
            <div class="btn btn-success export-excel">Exportar en Excel</div>
        </div>
        <div class="col form-group col-md-3 offset-md-3">
            <button onClick="window.history.back()" class="btn btn-link float-right">Volver</button>
        </div>
    </div>
    <div class="row">
        <div class="form-group mb-2 col-md-3">
            <label for="inputState">Depósito</label>
            <select id="depot" class="form-control" onChange="filterText2()">
                <option value="all">Todos los depósitos</option>
            </select> 
        </div>
        <div class="form-group mb-2 col-md-3">
            <label for="inputState">Subtipos</label>
            <select id="subtype-consumable" class="form-control" onChange="filterText()">
                <option value="all">Todos los subtipos</option>
            </select> 
        </div>
        <div class="form-group mb-2 col-md-3">
            <label for="inputState">Tipo de movimiento</label>
            <select id="type-movement" class="form-control" onChange="filterText3()">
                <option value="all">Todos los movimientos</option>
                <option value="INGRESO">Ingreso</option>
                <option value="EGRESO">Egreso</option>
            </select> 
        </div>
        <div class="col form-group mb-2 col-md-3">
            <label for="inputSearch">Buscar</label>
            <input type="search" id="search" class="form-control" placeholder="Buscador">
        </div>
    </div>
    <div id="update-alert" role="alert">
    </div>
    <div id="delete-alert" role="alert">
    </div>
    <div class="row">
        <div class="col">
            <h3>Artículos consumidos</h3>
            <table id="table-consumables-movements" class="table table-hover">
                <thead>
                    <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Subtipo</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Porcentaje</th>
                    <th scope="col">Movimiento</th>
                    <th scope="col">Pañol</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table> 
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
        url: "/api-stock/public/index.php/statistics/consumables/all/all/all",
        dataType: "json",
        success: function (data) {
            let rows = data.result;
                let html = [];
                for (let i=0; i < rows.length; i++){
                    var uppDepot = rows[i].nombre.toUpperCase();
                    html.push(
                 `<tr class="content">
                    <td> ${rows[i].producto} </td> 
                    <td> ${rows[i].subtipo} </td> 
                    <td> ${rows[i].cantidad} </td> 
                    <td> ${rows[i].porcentaje} </td>
                    <td> ${rows[i].movimiento} </td>
                    <td> ${uppDepot} </td>
                    </tr>`
                    );
                }    
            $('#table-consumables>tbody').html(html.join(''));
        }
    });
    $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/products/subtypes/consumables",
        dataType: "json",
        success: function (response) {
            console.log(response)
            $.each(response.result, function(index, item) {
                var uppName = item.nombre.toUpperCase();
                var listSubtype = `<option value="${uppName}">${item.nombre}</option>`;
                $('#subtype-consumable').append(listSubtype);      
            });
        }
    });
    $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/depots/get/all/all/all",
        dataType: "json",
        success: function (response) {
            console.log(response)
            $.each(response.result, function(index, item) {
                var listDepot = `<option value="${item.nombre}">${item.nombre}</option>`;
                $('#depot').append(listDepot);      
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
          doc.save("EstadisticaConsumibles" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".pdf")
        });
    $('.export-excel').click(function(event) {
        var table = $('.table');
        if(table && table.length){

        $(table).table2excel({
        //exclude: ".noExl",
        name: "EstadisticaConsumibles",
        filename: "EstadisticaConsumibles" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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
    var rex = new RegExp($('#subtype-consumable').val());
    if(rex =="/all/"){clearFilter()}else{
            $('.content').hide();
            $('.content').filter(function() {
            return rex.test($(this).text());
            }).show();
        }
    }
    
function clearFilter(){
    $('#subtype-consumable').val('all');
    $('.content').show();
}
function filterText2(){  
    var rex = new RegExp($('#depot').val());
    if(rex =="/all/"){clearFilter2()}else{
            $('.content').hide();
            $('.content').filter(function() {
            return rex.test($(this).text());
            }).show();
        }
    }
    
function clearFilter2(){
    $('#depot').val('all');
    $('.content').show();
}
function filterText3(){  
    var rex = new RegExp($('#type-movement').val());
    if(rex =="/all/"){clearFilter3()}else{
            $('.content').hide();
            $('.content').filter(function() {
            return rex.test($(this).text());
            }).show();
        }
    }
    
function clearFilter3(){
    $('#type-movement').val('all');
    $('.content').show();
}
</script>
</body>
</html>