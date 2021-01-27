<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo envío de stock</title>
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
                        <h3>Marcar los articulos que desea devolver a un almacén central</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputState">Elige el pañol que devuelve los artículos</label>
                            <div class="form-group ">
                                <select class="form-control" name="" id="select-depots">
                                    <option value="0">Selecciona el pañol</option>
                                </select>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="form-group col col-md-3">
                                <select class="form-control list-types" name="" id="select-type" onChange="filterText()">
                                <option value="all">Todos los artículos</option>
                                <option value="Herramienta">Herramientas</option>
                                <option value="Equipo">Equipos</option>
                                <option value="Consumible">Consumibles</option>
                                <option value="Indumentaria">Indumentaria</option>
                                </select>
                            </div>
                            <div id="select-tool" class="form-group mb-2 col-md-3 d-none d-select">
                                <select id="subtypes-tool" class="form-control subtypes" onChange="filterText2()">
                                    <option value="Herramienta">Todas las herramientas</option>
                                </select>
                            </div>
                            <div id="select-hardware" class="form-group mb-2 col-md-3 d-none d-select">
                                <select id="subtypes-hardware" class="form-control subtypes" onChange="filterText3()">
                                    <option value="Equipo">Todos los equipos</option>
                                </select>
                            </div>
                            <div id="select-consumable" class="form-group mb-2 col-md-3 d-none d-select">
                                <select id="subtypes-consumables" class="form-control subtypes" onChange="filterText4()">
                                    <option value="Consumible">Todos los consumibles</option>
                                </select>
                            </div>
                            <div id="select-dress" class="form-group mb-2 col-md-3 d-none d-select">
                                <select id="subtypes-dress" class="form-control subtypes" onChange="filterText5()">
                                    <option value="Indumentaria">Todas las indumentarias</option>
                                </select>
                            </div>
                            <div class="col form-group  col-md-3">
                                <input type="search" id="search" class="form-control" placeholder="Buscador">
                            </div>
                            <div class="col col-md-3 offset-md-3">
                            <button id="addArticles" class="btn btn-primary float-right">Confirmar</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <table id="table-articles" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Marca</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Existencia</th>
                                            <th scope="col">Identificadores</th>
                                            <th scope="col">Elegir</th>
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
        </div>
    </div>
<script>
$(document).ready(function () {
    var selectedDepots = "";
    $("#select-depots").change(function(){
        selectedDepots = $(this).children("option:selected").val();
    });
    $.ajax({
            url: '/api-stock/public/index.php/depots/get/user/' + userid,
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
                $("#table-articles>tbody").load(" #table-articles>tbody");
            $.ajax({
                type: "GET",
                url: "/api-stock/public/index.php/stock/get/" + selectedDepots + '/all',
                dataType: "json",
                success: function (data) {
                    let rows = data.result;
                    let html = [];
                    for (let i=0; i < rows.length; i++){
                        html.push(
                        `<tr class="content" idArticle="${rows[i].idProducto}">
                            <td> ${rows[i].Producto} </td> 
                            <td> ${rows[i].marca} </td> 
                            <td> ${rows[i].descripcion} </td>
                            <td class="typeArticle d-none">${rows[i].tipo}</td>
                            <td> ${rows[i].existencia} </td>
                            <td> ${rows[i].codigos} </td>
                            <td> <input type="checkbox" name="selector[]" class="form-check-input" id="article-check" value=""> </td>
                        </tr>`
                        );
                    }    
                    $('#table-articles>tbody').html(html.join(''));
                    // console.log(data);
                    // $.each(data.result, function (i, item) { 
                    // var row = `<tr class="content" idArticle="${item.idProducto}">
                    //     <td> ${item.Producto} </td> 
                    //     <td> ${item.marca} </td> 
                    //     <td> ${item.detalle} </td> 
                    //     <td> ${item.descripcion} </td>
                    //     <td class="typeArticle d-none">${item.tipo}</td>
                    //     <td id="exist">${item.existencia}</td>
                    //     <td> ${item.codigos} </td>
                    //     <td> <input type="checkbox" name="selector[]" class="form-check-input" id="article-check" value=""> </td>
                    //     </tr>`;
                    // $('#table-articles>tbody').append(row);
                    // });
                    $('input[id="article-check"]').click(function () { 
                        var typeArticle = "";
                        var result = {items: []};
                        var i = 0;
                        $("input[type=checkbox]:checked").each(function(){
                            var article = $(this)[0].parentElement.parentElement;
                            var idArticle = $(article).attr('idArticle');
                            typeArticle = $(this).parent().parent().find('td').eq(4).html();
                            switch (typeArticle) {
                            case 'Herramienta':
                                typeArticle = "tools/"
                                break;
                                case 'Equipo':
                                typeArticle = "hardware/"
                                break;
                                case 'Consumible':
                                typeArticle = "consumables/"
                                break;
                                case 'Indumentaria':
                                typeArticle = "dress/"
                                break;
                        }
                            result.items[i] = typeArticle + idArticle;
                            i++;
                    });
                    console.log(result);
                        $('#addArticles').click(function () { 
                            window.localStorage.setItem("result", JSON.stringify(result));
                            window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
                            window.location.href ='../views/confirm_returnStock.php';
                        });
                    });
                }
            });
        }
        });
        $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/products/subtypes/tools",
        dataType: "json",
        success: function (response) {
            console.log(response)
            $.each(response.result, function(index, item) {
                var listSubtype = `<option value="${item.nombre}">${item.nombre}</option>`;
                $('#subtypes-tool').append(listSubtype);      
            });
        }
    });
    $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/products/subtypes/hardware",
        dataType: "json",
        success: function (response) {
            console.log(response)
            $.each(response.result, function(index, item) {
                var listSubtype = `<option value="${item.nombre}">${item.nombre}</option>`;
                $('#subtypes-hardware').append(listSubtype);      
            });
        }
    });
    $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/products/subtypes/consumables",
        dataType: "json",
        success: function (response) {
            console.log(response)
            $.each(response.result, function(index, item) {
                var listSubtype = `<option value="${item.nombre}">${item.nombre}</option>`;
                $('#subtypes-consumables').append(listSubtype);      
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
                $('#subtypes-dress').append(listSubtype);      
            });
        }
    });
    $('.list-types').change(function (e) { 
        console.log($('.list-types').val());
        if ($('.list-types').val() == 'Herramienta'){
            $('.d-select').removeClass('d-block');
            $('#select-tool').addClass('d-block');
        };
        if ($('.list-types').val() == 'Equipo'){
            $('.d-select').removeClass('d-block');
            $('#select-hardware').addClass('d-block');
        };
        if ($('.list-types').val() == 'Consumible'){
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
});
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
        $('.subtypes').val('all');
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