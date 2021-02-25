<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso de stock</title>
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
                        <h3>Seleccione los artículos</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-2">
            <div class="form-group col col-md-3">
                <select class="form-control list-types" name="" id="select-type" onChange="filterText()">
                <option value="all">Todos los artículos</option>
                <option value="Herramientas">Herramientas</option>
                <option value="Equipos">Equipos</option>
                <option value="Consumibles">Consumibles</option>
                <option value="Indumentaria">Indumentaria</option>
                </select>
            </div>
            <div id="select-tool" class="form-group mb-2 col-md-3 d-none d-select">
                <select id="subtypes-tool" class="form-control subtypes" onChange="filterText2()">
                    <option value="Herramientas">Todas las herramientas</option>
                </select>
            </div>
            <div id="select-hardware" class="form-group mb-2 col-md-3 d-none d-select">
                <select id="subtypes-hardware" class="form-control subtypes" onChange="filterText3()">
                    <option value="Equipos">Todos los equipos</option>
                </select>
            </div>
            <div id="select-consumable" class="form-group mb-2 col-md-3 d-none d-select">
                <select id="subtypes-consumables" class="form-control subtypes" onChange="filterText4()">
                    <option value="Consumibles">Todos los consumibles</option>
                </select>
            </div>
            <div id="select-dress" class="form-group mb-2 col-md-3 d-none d-select">
                <select id="subtypes-dress" class="form-control subtypes" onChange="filterText5()">
                    <option value="Indumentaria">Todas las indumentarias</option>
                </select>
            </div>
            <div class="col form-group col col-md-3">
                <input type="search" id="search" class="form-control" placeholder="Buscador">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col form-group col-md-6">
                <div class="btn btn-danger export-pdf">Exportar en PDF</div>
                <div class="btn btn-success export-excel">Exportar en Excel</div>
            </div>
        </div>
        <div id="success-alert" role="alert">
        </div>
        <div id="danger-alert" role="alert">
        </div>
        <div class="row">
            <div class="col">
                <table id="table-articles" class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Detalle</th>
                            <th scope="col">Descripción</th>
                            <th scope="col" class="d-none">Tipo</th>
                            <th scope="col">Cant</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>                     
            </div>
        </div>
        <div class="modal fade" id="modal-cant" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar artículo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="row mb-2">
                            <div class="form-group col">
                                <select class="form-control list-depots select-depots" id="select-depots">
                                    <option value="0">Selecciona un depósito</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <input type="number" class="form-control cant" placeholder="Cantidad">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col ml-4 mt-2 col-md-1">
                                <input type="checkbox" class="form-check-input" name="" id="price" value="checkedValue">
                            </div>
                            <div class="col col-md-10 offset-md-1">
                                <input type="number" class="form-control price" placeholder="Agregar precio" disabled>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-muted" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-dark" data-dismiss="modal" onClick="sendArticle();">Enviar</button>
                </div>
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
    priceChecked();
    $("#select-depots").change(function(){
        selectedDepots = $(this).children("option:selected").val();
    });
    $.ajax({
        url: '/api-stock/public/index.php/depots/get/1/all/all',
        type: 'GET',
        dataType: 'json',
        success: function(data, textStatus, xhr) {
            let rows = data.result;
            let html = [];
            for (let i=0; i < rows.length; i++){
                html.push(
                    `<option value="${rows[i].id}">${rows[i].nombre}</option>`
                );
            }    
            $('.select-depots').append(html.join(''));
            console.log(data)
        },
        error: function(xhr, textStatus, errorThrown) {
        }
    });
    $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/products/get/all/all",
        dataType: "json",
        success: function (data) {
            let rows = data.result;
            let html = [];
            for (let i=0; i < rows.length; i++){
                html.push(
                `<tr class="content" idArticle="${rows[i].id}">
                <td> ${rows[i].nombre} </td> 
                <td> ${rows[i].marca} </td> 
                <td> ${rows[i].detalle} </td> 
                <td> ${rows[i].descripcion} </td>
                <td class="typeArticle d-none">${rows[i].tipo}</td>
                <td> <button class='sendPrice btn btn-success' data-toggle="modal" data-target="#modal-cant"> <i class="fas fa-check"></i> </button> </td>
                </tr>`
                );
            }    
                // <td> <input type="number" class="cant form-control" placeholder="Cantidad"> </td>
            $('#table-articles>tbody').html(html.join(''));
            // $(".cant").change(function(){
            //     cant = $(this).val();
            // });
            $('.sendPrice').click(function (e) { 
                e.preventDefault();
                article = $(this)[0].parentElement.parentElement;
                idArticle = $(article).attr('idArticle');
                typeArticle = $(this).parent().parent().find('td').eq(4).html();
                    switch (typeArticle) {
                    case 'Herramientas':
                        typeArticle = "tools/"
                        break;
                        case 'Equipos':
                        typeArticle = "hardware/"
                        break;
                        case 'Consumibles':
                        typeArticle = "consumables/"
                        break;
                        case 'Indumentaria':
                        typeArticle = "dress/"
                        break;
                }
            });
        }
    });
    $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/products/subtypes/tools",
        dataType: "json",
        success: function (data) {
            let rows = data.result;
            let html = [];
            for (let i=0; i < rows.length; i++){
                html.push(
                    `<option value="${rows[i].nombre}">${rows[i].nombre}</option>`
                );
            }    
            $('#subtypes-tool').html(html.join(''));
        }
    });
    $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/products/subtypes/hardware",
        dataType: "json",
        success: function (data) {
            let rows = data.result;
            let html = [];
            for (let i=0; i < rows.length; i++){
                html.push(
                    `<option value="${rows[i].nombre}">${rows[i].nombre}</option>`
                );
            }    
            $('#subtypes-hardware').html(html.join(''));
        }
    });
    $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/products/subtypes/consumables",
        dataType: "json",
        success: function (data) {
            let rows = data.result;
            let html = [];
            for (let i=0; i < rows.length; i++){
                html.push(
                    `<option value="${rows[i].nombre}">${rows[i].nombre}</option>`
                );
            }
            $('#subtypes-consumables').html(html.join(''));
        }
    });
    $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/products/subtypes/dress",
        dataType: "json",
        success: function (data) {
            let rows = data.result;
            let html = [];
            for (let i=0; i < rows.length; i++){
                html.push(
                    `<option value="${rows[i].nombre}">${rows[i].nombre}</option>`
                );
            }
            $('#subtypes-dress').html(html.join(''));
        }
    });
    $('.list-types').change(function (e) { 
        console.log($('.list-types').val());
        if ($('.list-types').val() == 'Herramientas'){
            $('.d-select').removeClass('d-block');
            $('#select-tool').addClass('d-block');
        };
        if ($('.list-types').val() == 'Equipos'){
            $('.d-select').removeClass('d-block');
            $('#select-hardware').addClass('d-block');
        };
        if ($('.list-types').val() == 'Consumibles'){
            $('.d-select').removeClass('d-block');
            $('#select-consumable').addClass('d-block');
        };
        if ($('.list-types').val() == 'Indumentaria'){
            $('.d-select').removeClass('d-block');
            $('#select-dress').addClass('d-block');
        };
        if ($('.list-types').val() == 'all'){
            $('.d-select').removeClass('d-block');
            $('.subtypes').val() == 'all';
        };
    });
    $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
            $(".content").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    $('.export-pdf').click(function(event) {
      var doc = new jsPDF()
      doc.autoTable({ html: '#table-articles' })
      doc.save("TablaMovimientos" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".pdf")
    });
    $('.export-excel').click(function(event) {
        var table = $('#table-articles');
        if(table && table.length){

        $(table).table2excel({
        //exclude: ".noExl",
        name: "TablaMovimientos",
        filename: "TablaMovimientos" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
        fileext: ".xls",
        exclude_img: true,
        exclude_links: true,
        exclude_inputs: true,
        preserveColors: false
        });
        }
    });
});
function priceChecked(){
    $('#price').change(function() {
        // this will contain a reference to the checkbox   
        if (this.checked) {
            $('.price').prop("disabled", false);
        } else {
            $('.price').prop("disabled", true);
        }
    });
}

function sendArticle(){
    if ($('#price').is(':checked')){
        var url = "/api-stock/public/index.php/stock/register/";
        var depot = selectedDepots + '/';
        var typeMove = 'input';
        var cant = $('.cant').val();
        var price = $('.price').val();
        var dataBuy = {
            id: idArticle,
            precio: price,
            cantidad: cant,
            control: 1,
            observaciones: 'Sin observaciones',
            usuario: userid,
        };
        $.ajax({
            url: url + depot + typeArticle + typeMove,
            type: 'POST',
            dataType: 'json',
            data: dataBuy,
            success: function(data, textStatus, xhr) {
                    $('#success-alert').addClass('alert alert-success')
                    .add('h6')
                    .text('Artículo sumado al stock con precio');
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#success-alert").slideUp(500);
                    location.reload();
                });
            },
            error: function(xhr, textStatus, errorThrown) {
                    $('#danger-alert').addClass('alert alert-danger')
                        .add('h6')
                        .text('Error al sumar artículo');
                    $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#danger-alert").slideUp(500);
                });
            }
        });
    }else{
        var url = "/api-stock/public/index.php/stock/register/";
        var depot = selectedDepots + '/';
        var typeMove = 'input';
        var cant = $('.cant').val();
        var price = $('.price').val();
        var dataCant = {
            id: idArticle,
            precio: 0,
            cantidad: cant,
            control: 0,
            observaciones: 'Sin observaciones',
            usuario: userid,
        };
        $.ajax({
            url: url + depot + typeArticle + typeMove,
            type: 'POST',
            dataType: 'json',
            data: dataCant,
            success: function(data, textStatus, xhr) {
            $('#success-alert').addClass('alert alert-success')
            .add('h6')
            .text('Artículo sumado al stock con cantidad');
            $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                $("#success-alert").slideUp(500);
                location.reload();
            });
            },
            error: function(xhr, textStatus, errorThrown) {
                $('#danger-alert').addClass('alert alert-danger')
                    .add('h6')
                    .text('Error al sumar artículo');
                $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                $("#danger-alert").slideUp(500);
            });
            }
        });
    }
}

function filterText(){  
    var rex = new RegExp($('#select-type').val());
    if(rex =="/all/"){clearFilter()}else{
        $('.content').hide();
        $('.content').filter(function() {
        return rex.test($(this).text());
        }).show();
	    }
	}
	
    function clearFilter(){
		$('#select-type').val('all');
		$('.content').show();
	}
    function filterText2(){  
    var rex = new RegExp($('#subtypes-tool').val());
    if(rex =="/all/"){clearFilter2()}else{
        $('.content').hide();
        $('.content').filter(function() {
        return rex.test($(this).text());
        }).show();
        }
    }
    
    function clearFilter2(){
        $('#subtypes-tool').val('all');
        $('.content').show();
    }
    function filterText3(){  
    var rex = new RegExp($('#subtypes-hardware').val());
    if(rex =="/all/"){clearFilter2()}else{
        $('.content').hide();
        $('.content').filter(function() {
        return rex.test($(this).text());
        }).show();
        }
    }
    
    function clearFilter3(){
        $('#subtypes-hardware').val('all');
        $('.content').show();
    }
    function filterText4(){  
    var rex = new RegExp($('#subtypes-consumables').val());
    if(rex =="/all/"){clearFilter2()}else{
        $('.content').hide();
        $('.content').filter(function() {
        return rex.test($(this).text());
        }).show();
        }
    }
    
    function clearFilter4(){
        $('#subtypes-hardware').val('all');
        $('.content').show();
    }
    function filterText5(){  
    var rex = new RegExp($('#subtypes-dress').val());
    if(rex =="/all/"){clearFilter2()}else{
        $('.content').hide();
        $('.content').filter(function() {
        return rex.test($(this).text());
        }).show();
        }
    }
    
    function clearFilter5(){
        $('#subtypes-dress').val('all');
        $('.content').show();
    }
</script>
</body>
</html>