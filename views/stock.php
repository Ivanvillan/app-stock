<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock</title>
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
                        <h3>Stock</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-2">
            <div class="form-group col col-md-4">
                <select class="form-control list-depots" name="" id="select-depots">
                <option value="">Selecciona un depósito</option>
                </select>
            </div>
        </div>
        <div class="row my-2">
            <div class="col form-group col col-md-4">
                <input type="search" id="search" class="form-control" placeholder="Buscador">
            </div>
            <div class="form-group col col-md-4">
                <select class="form-control list-types" name="" id="select-type" onChange="filterText()">
                <option value="all">Mostrar todo</option>
                <option value="Herramienta">Herramientas</option>
                <option value="Equipo">Equipos</option>
                <option value="Consumible">Consumibles</option>
                <option value="Indumentaria">Indumentaria</option>
                </select>
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
            <div id="stock-depots">
                <table id="table-articles" class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Detalle</th>
                            <th scope="col">Descripcion</th>
                            <th>Cant. Ident.</th>
                            <th scope="col" class="d-none">Tipo</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col" class="stock-price">Precio</th>
                            <th scope="col" class="stock-sum">Sumar</th>
                            <th scope="col" class="stock-code">Identificador</th>
                            <th scope="col">Prestar</th>
                            <th scope="col">Rep/Mant</th>
                            <th scope="col">Baja</th>
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
<script src="https://unpkg.com/jspdf@1.5.3/dist/jspdf.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable@3.5.3/dist/jspdf.plugin.autotable.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script>
var code = "";
var exist = "";
var typeArticle = "";
var selectedDepots = "";
var element = "";
var idElement = "";
$(document).ready(function () {
    listStock();
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
    $("#select-depots").change(function(){
        selectedDepots = $(this).children("option:selected").val();
    });
function listStock(){
    if (userprofile != 1) {
        $('.table-price').remove();
        $('.table-sum').remove();
        $('.stock-price').remove();
        $('.stock-sum').remove();
        console.log('no es uno');
        $.ajax({
            url: '/api-stock/public/index.php/depots/get/user/' + userid,
            type: 'GET',
            dataType: 'json',
            success: function(data, textStatus, xhr) {
                console.log(data)
            $.each(data.result, function(index, item) {
                var depotsList = `<option value="${item.id}">${item.nombre}</option>`;
                $('#select-depots').append(depotsList);      
            });
            },
            error: function(xhr, textStatus, errorThrown) {
            }
        });
        $('#select-depots').change(function (e) { 
        if ($('select[id=select-depots]').val() == $(this).children("option:selected").val()) {
            $("#table-articles>tbody").load(" #table-articles>tbody");
            var url = "/api-stock/public/index.php/stock/get/";
            var depots = selectedDepots + '/';
            var all = "all";
            $.ajax({
                type: "GET",
                url: url + depots + all,
                dataType: "json",
                    success: function (data) {
                        let rows = data.result;
                        let html = [];
                        for (let i=0; i < rows.length; i++){
                            html.push(
                        `<tr class="content" idArticle="${rows[i].idProducto}">
                            <td> ${rows[i].Producto} </td> 
                            <td> ${rows[i].marca} </td> 
                            <td> ${rows[i].detalle} </td> 
                            <td> ${rows[i].descripcion} </td>
                            <td> ${rows[i].codigos} </td>
                            <td class="typeArticle d-none">${rows[i].tipo}</td>
                            <td id="exist">${rows[i].existencia}</td>
                            <td> <button class='btn btn-dark btn-sm addCode'> <i class="fas fa-unlock-alt"></i> </button> </td>
                            <td> <button class='btn btn-info btn-sm moveDerive'> <i class="fa fa-share">  </i> </button> </td>
                            <td> <button class='btn btn-warning btn-sm moveRepair'> <i class="fa fa-wrench">  </i> </button> </td>
                            <td> <button class='btn btn-danger btn-sm moveReduce'> <i class="fa fa-window-close">  </i></button> </td>
                            </tr>`
                            );
                        }    
                        $('#table-articles>tbody').html(html.join(''));
                    $('.addCode').click(function () { 
                        var urlGetItem = "/api-stock/public/index.php/products/getbyid/";
                        var urlGetCodes = "/api-stock/public/index.php/stock/codes/";
                        // code = $(this).parent().parent().find('td').eq(4).html();
                        // var exist = $(this).parent().parent().find('td').eq(6).html();
                        element = $(this)[0].parentElement.parentElement;
                        idElement = $(element).attr('idArticle');
                        typeArticle = $(this).parent().parent().find('td').eq(5).html();
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
                        window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
                        window.localStorage.setItem("idElement", JSON.stringify(idElement));
                        window.localStorage.setItem("typeArticle", JSON.stringify(typeArticle));
                        window.location.href ='../views/new_code.php';
                    });
                    $('.moveDerive').click(function () { 
                        exist = $(this).parent().parent().find('td').eq(6).html();
                        element = $(this)[0].parentElement.parentElement;
                        idElement = $(element).attr('idArticle');
                        typeArticle = $(this).parent().parent().find('td').eq(5).html();
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
                        window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
                        window.localStorage.setItem("idElement", JSON.stringify(idElement));
                        window.localStorage.setItem("typeArticle", JSON.stringify(typeArticle));
                        window.location.href ='../views/new_derived.php';
                    });
                    $('.moveRepair').click(function () { 
                        exist = $(this).parent().parent().find('td').eq(6).html();
                        element = $(this)[0].parentElement.parentElement;
                        idElement = $(element).attr('idArticle');
                        typeArticle = $(this).parent().parent().find('td').eq(5).html();
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
                        window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
                        window.localStorage.setItem("idElement", JSON.stringify(idElement));
                        window.localStorage.setItem("typeArticle", JSON.stringify(typeArticle));
                        window.location.href ='../views/new_repair.php';
                    });
                    $('.moveReduce').click(function () { 
                        exist = $(this).parent().parent().find('td').eq(6).html();
                        element = $(this)[0].parentElement.parentElement;
                        idElement = $(element).attr('idArticle');
                        typeArticle = $(this).parent().parent().find('td').eq(5).html();
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
                        window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
                        window.localStorage.setItem("idElement", JSON.stringify(idElement));
                        window.localStorage.setItem("typeArticle", JSON.stringify(typeArticle));
                        window.location.href ='../views/new_reduce.php';
                    });
                }
            });
        }
    });
    }else{
        console.log('es uno')
        $.ajax({
            url: '/api-stock/public/index.php/depots/get/all/all/all',
            type: 'GET',
            dataType: 'json',
            success: function(data, textStatus, xhr) {
                console.log(data)
            $.each(data.result, function(index, item) {
                var depotsList = `<option value="${item.id}">${item.nombre}</option>`;
                $('#select-depots').append(depotsList);      
            });
            },
            error: function(xhr, textStatus, errorThrown) {
            }
        });
        $('#select-depots').change(function (e) { 
        if ($('select[id=select-depots]').val() == $(this).children("option:selected").val()) {
            $("#table-articles>tbody").load(" #table-articles>tbody");
            var url = "/api-stock/public/index.php/stock/get/";
            var depots = selectedDepots + '/';
            var all = "all";
            $.ajax({
                type: "GET",
                url: url + depots + all,
                dataType: "json",
                    success: function (data) {
                        let rows = data.result;
                        let html = [];
                        for (let i=0; i < rows.length; i++){
                            type = rows[i].tipo;
                            html.push(
                        `<tr class="content" idArticle="${rows[i].idProducto}">
                            <td> ${rows[i].Producto} </td> 
                            <td> ${rows[i].marca} </td> 
                            <td> ${rows[i].detalle} </td> 
                            <td> ${rows[i].descripcion} </td>
                            <td> ${rows[i].codigos} </td>
                            <td class="typeArticle d-none">${rows[i].tipo}</td>
                            <td id="exist">${rows[i].existencia}</td>
                            <td id="exist">${rows[i].precio}</td>
                            <td> <button class='btn btn-success btn-sm addStock'> <i class="fa fa-plus-square"></i> </button> </td>
                            <td> <button class='btn btn-dark btn-sm addCode'> <i class="fas fa-unlock-alt"></i> </button> </td>
                            <td> <button class='btn btn-info btn-sm moveDerive'> <i class="fa fa-share">  </i> </button> </td>
                            <td> <button class='btn btn-warning btn-sm moveRepair'> <i class="fa fa-wrench">  </i> </button> </td>
                            <td> <button class='btn btn-danger btn-sm moveReduce'> <i class="fa fa-window-close">  </i></button> </td>
                            </tr>`
                            );
                        }    
                        $('#table-articles>tbody').html(html.join(''));
                    $('.addCode').click(function () { 
                        var urlGetItem = "/api-stock/public/index.php/products/getbyid/";
                        var urlGetCodes = "/api-stock/public/index.php/stock/codes/";
                        // code = $(this).parent().parent().find('td').eq(4).html();
                        // var exist = $(this).parent().parent().find('td').eq(6).html();
                        element = $(this)[0].parentElement.parentElement;
                        idElement = $(element).attr('idArticle');
                        typeArticle = $(this).parent().parent().find('td').eq(5).html();
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
                        window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
                        window.localStorage.setItem("idElement", JSON.stringify(idElement));
                        window.localStorage.setItem("typeArticle", JSON.stringify(typeArticle));
                        window.location.href ='../views/new_code.php';
                    });
                    $('.addStock').click(function () { 
                        var result = {items: []};
                        var i = 0;
                        exist = $(this).parent().parent().find('td').eq(6).html();
                        element = $(this)[0].parentElement.parentElement;
                        idElement = $(element).attr('idArticle');
                        typeArticle = $(this).parent().parent().find('td').eq(5).html();
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
                        result.items[i] = typeArticle + idElement;
                        i++;
                        window.localStorage.setItem("result", JSON.stringify(result));
                        window.location.href ='../views/articlesToMove.php';
                    });
                    $('.moveDerive').click(function () { 
                        exist = $(this).parent().parent().find('td').eq(6).html();
                        element = $(this)[0].parentElement.parentElement;
                        idElement = $(element).attr('idArticle');
                        typeArticle = $(this).parent().parent().find('td').eq(5).html();
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
                        window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
                        window.localStorage.setItem("idElement", JSON.stringify(idElement));
                        window.localStorage.setItem("typeArticle", JSON.stringify(typeArticle));
                        window.location.href ='../views/new_derived.php';
                    });
                    $('.moveRepair').click(function () { 
                        exist = $(this).parent().parent().find('td').eq(6).html();
                        element = $(this)[0].parentElement.parentElement;
                        idElement = $(element).attr('idArticle');
                        typeArticle = $(this).parent().parent().find('td').eq(5).html();
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
                        window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
                        window.localStorage.setItem("idElement", JSON.stringify(idElement));
                        window.localStorage.setItem("typeArticle", JSON.stringify(typeArticle));
                        window.location.href ='../views/new_repair.php';
                    });
                    $('.moveReduce').click(function () { 
                        exist = $(this).parent().parent().find('td').eq(6).html();
                        element = $(this)[0].parentElement.parentElement;
                        idElement = $(element).attr('idArticle');
                        typeArticle = $(this).parent().parent().find('td').eq(5).html();
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
                        window.localStorage.setItem("selectedDepots", JSON.stringify(selectedDepots));
                        window.localStorage.setItem("idElement", JSON.stringify(idElement));
                        window.localStorage.setItem("typeArticle", JSON.stringify(typeArticle));
                        window.location.href ='../views/new_reduce.php';
                    });
                }
            });
        }
    });
    }
}
</script>
</body>
</html>