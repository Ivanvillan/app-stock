<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de solicitudes de pedido</title>
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
            <div class="form-group my-4 col-md-4 select-depots">
                <label for="inputState">Selecciona un pañol</label>
                <div class="form-group ">
                    <select class="form-control" name="" id="select-depots">
                        <option value="">Selecciona un pañol</option>
                    </select>
                </div>
            </div>
            <div class="form-group my-4 col-md-4">
                <label for="inputState">Estado</label>
                <div class="form-group ">
                    <select class="form-control" name="" id="">
                        <option value="0">Selecciona un estado</option>
                    </select>
                </div>
            </div>
            <div class="form-group my-4 col-md-4">
                <label for="inputSearch">Buscar pedido</label>
                <input type="search" id="search" class="form-control" placeholder="Buscador">
            </div>
        </div>
        <div class="row">
            <div class="col">
            <table id="table-notes" class="table table-hover">
                <thead>
                    <tr>
                    <th scope="col">N° pedido</th>
                    <th scope="col" class="table-depot">Depósito</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Actualizado</th>
                    <th scope="col">Responsable</th>
                    <th scope="col">Observación</th>
                    <th scope="col">Ver</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                </table>                     
            </div>
        </div>             
    </div>
</div>
<script>
var selectedDepots = "";
var state = "";
$(document).ready(function () {
    $("#select-depots").change(function(){
        selectedDepots = $(this).children("option:selected").val();
    });
if (userprofile != 1) {
    console.log('no es 1')
    $('.table-depot').remove();
    $.ajax({
    url: '/api-stock/public/index.php/depots/get/user/' + userid,
    type: 'GET',
    dataType: 'json',
    success: function(data, textStatus, xhr) {
        console.log(data)
    $.each(data.result, function(index, item) {
        var depots = `<option value="${item.id}">${item.nombre}</option>`;
        $('#select-depots').append(depots);      
    });
    },
    error: function(xhr, textStatus, errorThrown) {
    }
});
$('#select-depots').change(function (e) {
    if ($('select[id=select-depots]').val() == $(this).children("option:selected").val()) {
        $("#table-notes>tbody").load(" #table-notes>tbody");
        $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/stock/order/get/" + selectedDepots + '/1' + '/all',
        dataType: "json",
        success: function (data) {
            let rows = data.result;
            let html = [];
            for (let i=0; i < rows.length; i++){
                var observation = rows[i].Observaciones;
                if (observation == null) {
                    observation = 'Sin observación';
                }
                html.push(
                `<tr class="tr-article content" idOrder="${rows[i].id}">
                    <td> ${rows[i].id} </td> 
                    <td> ${rows[i].estado} </td> 
                    <td> ${rows[i].Realizado} </td> 
                    <td> ${rows[i].Actualizado} </td>
                    <td>${rows[i].nombre}</td>
                    <td> ${observation} </td>
                    <td><button id="detail" class="btn btn-primary btn-sm detail"><i class="fas fa-sign-in-alt"></i></button></td>
                </tr>`
                );
            }    
            $('#table-notes>tbody').html(html.join(''));
        var dataCant = new Array(); 
        $('.detail').click(function (e) { 
            e.preventDefault();
            var element = $(this)[0].parentElement.parentElement;
            var order = $(element).attr('idOrder');
            state = $(this).parent().parent().find('td').eq(1).html();
            window.localStorage.setItem("order", JSON.stringify(order)); 
            window.localStorage.setItem("state", JSON.stringify(state)); 
            window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
            window.location.href ='../views/detail_note.php';
            });
        }
        });
    }
    });
}else{
    console.log('es 1')
    $.ajax({
    url: '/api-stock/public/index.php/depots/get/2/all/all',
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
$.ajax({
        url: '/api-stock/public/index.php/depots/get/3/all/all',
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
$.ajax({
        url: '/api-stock/public/index.php/depots/get/4/all/all',
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
        
$('.button-quit').remove();
$('.button-quit-all').remove();
$('#select-depots').change(function (e) {
    if ($('select[id=select-depots]').val() == $(this).children("option:selected").val()) {
        $("#table-notes>tbody").load(" #table-notes>tbody");
        $.ajax({
            type: "GET",
            url: "/api-stock/public/index.php/stock/order/get/" + selectedDepots + '/1' + '/all',
            dataType: "json",
            success: function (data) {
                let rows = data.result;
                let html = [];
                for (let i=0; i < rows.length; i++){
                    var observation = rows[i].Observaciones;
                    if (observation == null) {
                        observation = 'Sin observación';
                    }
                    html.push(
                    `<tr class="tr-article content" idOrder="${rows[i].id}">
                        <td> ${rows[i].id} </td> 
                        <td> ${rows[i].Origen} </td> 
                        <td> ${rows[i].estado} </td> 
                        <td> ${rows[i].Realizado} </td> 
                        <td> ${rows[i].Actualizado} </td>
                        <td> ${rows[i].nombre} </td>
                        <td> ${observation} </td>
                        <td><button id="detail" class="btn btn-primary btn-sm detail" data-toggle="modal" data-target="#modal-order"><i class="fas fa-sign-in-alt"></i></button></td>
                    </tr>`
                    );
                }    
                $('#table-notes>tbody').html(html.join(''));
                var dataCant = new Array();
                $('.detail').click(function (e) { 
                    e.preventDefault();
                    var element = $(this)[0].parentElement.parentElement;
                    var order = $(element).attr('idOrder');
                    state = $(this).parent().parent().find('td').eq(2).html();   
                    console.log(selectedDepots);
                    console.log(state);
                    window.localStorage.setItem("order", JSON.stringify(order));
                    window.localStorage.setItem("state", JSON.stringify(state)); 
                    window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
                    window.location.href ='../views/detail_note.php';
                    });
                }
            });
        }
        });
    }
    $("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
        $(".content").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
</body>
</html>