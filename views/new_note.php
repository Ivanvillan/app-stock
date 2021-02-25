<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva nota de pedido</title>
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/app-stock/head/head.php') ?>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3>Marcar los articulos que desea solicitar</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputState">Elige el deposito origen</label>
                            <div class="form-group ">
                                <select class="form-control" name="" id="select-depotsOrigen">
                                    <option value="0">Origen</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputState">Elige el deposito destino</label>
                            <div class="form-group ">
                                <select class="form-control" name="" id="select-depotsDestino">
                                    <option value="0">Destino</option>
                                </select>
                            </div>
                        </div>
                        </div>
                        <div class="row">
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
                            <div class="col form-group  col-md-3">
                                <input type="search" id="search" class="form-control" placeholder="Buscador">
                            </div>
                            <div class="col col-md-4 offset-md-2">
                            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-observation">Confirmar</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                            <div id="success-alert" role="alert">
                            </div>
                            <div id="danger-alert" role="alert">
                            </div>
                                <table id="table-articles" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Marca</th>
                                            <th scope="col">Detalle</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Cant.</th>
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
    <div class="modal fade" id="modal-observation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Desea solicitar un articulo que no está en el listado?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col">
                        <textarea class="form-control observation col-md-6" rows="3" row="3" placeholder="Solicitud"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-muted sendNote" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-dark sendNote" data-dismiss="modal">Enviar</button>
            </div>
            </div>
        </div>
    </div>
<script>
var list = [];
$(document).ready(function () {
    var selectedDepotsOrigen = "";
    var selectedDepotsDestino = "";
    $("#select-depotsOrigen").change(function(){
        selectedDepotsOrigen = $(this).children("option:selected").val();
    });
    $("#select-depotsDestino").change(function(){
        selectedDepotsDestino = $(this).children("option:selected").val();
    });
    $.ajax({
            url: '/api-stock/public/index.php/depots/get/user/' + userid,
            type: 'GET',
            dataType: 'json',
            success: function(data, textStatus, xhr) {
                console.log(data)
            $.each(data.result, function(index, item) {
                var users = `<option value="${item.id}">${item.nombre}</option>`;
                $('#select-depotsOrigen').append(users);      
            });
            },
            error: function(xhr, textStatus, errorThrown) {
            }
        });
    $.ajax({
            url: '/api-stock/public/index.php/depots/get/1/all/all',
            type: 'GET',
            dataType: 'json',
            success: function(data, textStatus, xhr) {
                console.log(data)
            $.each(data.result, function(index, item) {
                var users = `<option value="${item.id}">${item.nombre}</option>`;
                $('#select-depotsDestino').append(users);      
            });
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
                <td> <input type="number" class="cant form-control" placeholder="Cantidad"> </td>
                <td> <input type="checkbox" name="selector[]" class="form-check-input" id="article-check" value=""> </td>
                </tr>`
                );
            }    
            $('#table-articles>tbody').html(html.join(''));
            $('input[id="article-check"]').click(function () { 
            if(selectedDepotsOrigen.length == 0 || selectedDepotsDestino.length == 0){
                    alert('Seleccione ambos depositos');
                    $('input[type=checkbox]').prop('checked', false)
                } else{
                    var typeArticle = "";
                    var url = "/api-stock/public/index.php/products/getbyid/"
                    var i = 0;
                    var cant = 0;
                    $("input[type=checkbox]:checked").each(function(){
                        var article = $(this)[0].parentElement.parentElement;
                        var idArticle = $(article).attr('idArticle');
                        cant = $(this).parent().parent().find('input').val();
                        typeArticle = $(this).parent().parent().find('td').eq(4).html();
                        switch (typeArticle) {
                        case 'Herramientas':
                            typeArticle = "tools"
                            break;
                            case 'Equipos':
                            typeArticle = "hardware"
                            break;
                            case 'Consumibles':
                            typeArticle = "consumables"
                            break;
                            case 'Indumentaria':
                            typeArticle = "dress"
                            break;
                    }

                        list[i] = {
                            idProducto: idArticle,
                            Tipo: typeArticle,
                            Cantidad: cant 
                        }
                        i++;
                    if ($("input[type=checkbox]:checked")) {
                        $(this).parent().parent().find('input').prop( "disabled", true );
                    }
                });
            }
            console.log(list);
            });
        }
    });

    $('.sendNote').click(function (e) { 
        e.preventDefault();
        var url = "/api-stock/public/index.php/stock/order/create";
        var observation = $('.observation').val();
        var data = {data: {
        Origen: selectedDepotsOrigen,
        Destino: selectedDepotsDestino,
        idUsuario: userid, 
        Observacion: observation,
        items: list,
    }};
    console.log(data);
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: data,
        success: function(data, textStatus, xhr) {
            $('#success-alert').addClass('alert alert-success')
            .add('span')
            .text('Nota enviada con exito');
        $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
            $("#success-alert").slideUp(500);
            window.location.href = 'new_note.php';
        });
        },
        error: function(xhr, textStatus, errorThrown) {
            $('#danger-alert').addClass('alert alert-danger')
                .add('span')
                .text('Error al crear nota');
            $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
            $("#danger-alert").slideUp(500);
        });
        }
    });
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