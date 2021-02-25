<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimiento de articulos</title>
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
                        <h3>Lista de artículos a enviar</h3>
                    </div>
                    <div class="card-body">
                    <div id="success-alert" role="alert">
                    </div>
                    <div id="danger-alert" role="alert">
                    </div>
                    <div class="row">
                            <div class="col col-md-4 mr-2 mb-2 isBuy">
                                <div class="card card-buy">
                                    <div class="card-header">
                                        <h5 class="buy-2">Actualizar precio en todas las ubicaciones</h5>
                                        <label class="form-check-label float-right">
                                            <input type="checkbox" class="form-check-input buy" name="" id="buy" value="checkedValue">
                                            Si
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <!-- <div class="col col-md-4 offset-md-2 mb-2">
                        <div class="form-group select-state">
                            <label for="">Selecciona un estado</label>
                            <select id="selectState" class="form-control">
                                <option>Estado de solicitud</option>
                                <option value="3">Finalizada</option>
                                <option value="4">Finalizada Parcialmente</option>
                                <option value="5">Finalizada con Modificaciones</option>
                                <option value="6">Rechazada</option>
                            </select> 
                        </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col">
                            <table id="table-articles-toMove" class="table table-hover">
                                <thead>
                                    <tr id="tr-toMove">
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Marca</th>
                                        <th scope="col">Descripcion</th>
                                        <th scope="col" class="col-cant">Enviado</th>
                                        <th scope="col" class="col-stock">Stock</th>
                                        <th scope="col" class="col-solicitude">Enviar</th>
                                        <th scope="col">Observación</th>
                                        <th scope="col" class="d-none input-price">Precio</th>
                                    </tr>
                                </thead>
                                <form id="form-data">
                                    <tbody>
                                    </tbody>
                                </form>
                            </table>  
                            <div class="col col-md-1 align-center float-right"><button id="addStock" class="btn btn-primary sendOrder">Confirmar</button></div>                   
                        </div>
                    </div>
                    <iframe id="txtArea1" style="display:none"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-liberate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Liberar códigos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body-liberate">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Lista de códigos del artículo</h4>
                            <table id="table-codes" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Identificador</th>
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
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
    </div>
</div>
<script src="https://unpkg.com/jspdf@1.5.3/dist/jspdf.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable@3.5.3/dist/jspdf.plugin.autotable.js"></script>
<script>
    var dataTypeArt = JSON.parse(window.localStorage.getItem("dataTypeArt"));
    var idProduct = JSON.parse(window.localStorage.getItem("idProduct"));
    var dataCant = JSON.parse(window.localStorage.getItem("dataCant"));
    var selectedDepotsOrigen = JSON.parse(window.localStorage.getItem("selectedDepotsOrigen"));
    var order = JSON.parse(window.localStorage.getItem("order"));
    var idDetPed = JSON.parse(window.localStorage.getItem("idDetPed"));
    var cantReal = JSON.parse(window.localStorage.getItem("cantReal"));
    var state = JSON.parse(window.localStorage.getItem("state"));
    var selectedDepotsDestino = JSON.parse(window.localStorage.getItem("selectedDepotsDestino"));
    var selectedState = "";
    var selectedCode = "";
    var list = {items: []};
    var i = 0;
    $(document).ready(function () {
        console.log('tipo art: ' + dataTypeArt);
        console.log('cant: ' + dataCant);
        console.log('depo:' + selectedDepotsOrigen);
        console.log('orden: ' + order);
        console.log('det ped: ' + idDetPed);
        console.log('cant real:'+cantReal);
        console.log('estado: ' + state);
        console.log('idprod:' +idProduct);
        listToMove();
        $("#selectState").change(function(){
            selectedState = $(this).children("option:selected").val();
            console.log(selectedState);
            console.log(list);
        });
        $('#buy').change(function() {
            // this will contain a reference to the checkbox   
            if (this.checked) {
                $('.price').addClass('d-block');
                $('.input-price').addClass('d-block')
            } else {
                $('.price').removeClass('d-block');
                $('.input-price').removeClass('d-block');
            }
        });
        if (userprofile != 1) {
            $('.buy-2').text('Agregar precio');
            $('.isBuy').remove();
            $('.select-state').remove();
            $('.col-stock').text('Solicitado');
            $('.col-solicitude').text('Recibido');
            $('.col-cant').remove();
        }
    $('.sendOrder').click(function (e) { 
        e.preventDefault();
        if (userprofile != 1) {
            var cant = $('.cant').val();
            var price = $('.price').val();
            var observation = $('.observation').val();
            var url = "/api-stock/public/index.php/stock/register/";
            var depots = selectedDepotsOrigen + '/';
            var typeArticle = dataTypeArt;
            var idArticle = idProduct;
            var typeMove = 'input';
            var data = {
                id: idArticle,
                cantidad: cant,
                control: 0,
                precio: price,
                observaciones: observation,
                usuario: userid,
                idDetallePedido: idDetPed
            };
            $.ajax({
            url: url + depots + typeArticle + typeMove,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(data, textStatus, xhr) {
                // if (state == ' FINALIZADA PARCIALMENTE ') {
                //     var urlState = '/api-stock/public/index.php/stock/order/state/' + 4 + '/' + order; 
                //     $.ajax({
                //         type: "POST",
                //         url: urlState,
                //         dataType: "json",
                //         success: function (response) {
                            $('#success-alert').addClass('alert alert-success')
                                .add('span')
                                .text('Stock ingresado');
                            $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                                $("#success-alert").slideUp(500);
                                history.back();
                            });
                        // }
                //     });
                // }else{
                //     var urlState = '/api-stock/public/index.php/stock/order/state/' + 2 + '/' + order; 
                //     $.ajax({
                //         type: "POST",
                //         url: urlState,
                //         dataType: "json",
                //         success: function (response) {
                //             $('#success-alert').addClass('alert alert-success')
                //                 .add('span')
                //                 .text('Stock ingresado');
                //             $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                //                 $("#success-alert").slideUp(500);
                //                 window.location.href = 'list_notes.php';
                //             });
                //         }
                //     });
                // }
            },
            error: function(xhr, textStatus, errorThrown) {
                $('#danger-alert').addClass('alert alert-danger')
                        .add('span')
                        .text('Error al ingresar Stock');
                    $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#danger-alert").slideUp(500);
                });
            }
        });
        }else{
            if ($('input[id=buy]').is(':checked')) {
                // && selectedState != 6
                var cant = $('.cant').val();
                var price = $('.price').val();
                var observation = $('.observation').val();
                var url = "/api-stock/public/index.php/stock/register/";
                var depots = selectedDepotsDestino + '/';
                var typeArticle = dataTypeArt;
                var idArticle = idProduct;
                var typeMove = 'output';
                var data = {
                    id: idArticle,
                    cantidad: cant,
                    control: 1,
                    precio: price,
                    observaciones: observation,
                    usuario: userid,
                    idDetallePedido: idDetPed
                };
                $.ajax({
                url: url + depots + typeArticle + typeMove,
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function(data, textStatus, xhr) {
                    // var urlState = '/api-stock/public/index.php/stock/order/state/' + selectedState + '/' + order; 
                    // $.ajax({
                    //     type: "POST",
                    //     url: urlState,
                    //     dataType: "json",
                    //     success: function (response) {
                            $('#success-alert').addClass('alert alert-success')
                                .add('span')
                                .text('Stock enviado con precio');
                            $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                                $("#success-alert").slideUp(500);
                                
                                history.back();
                            });
                    //     }
                    // });
                },
                error: function(xhr, textStatus, errorThrown) {
                    $('#danger-alert').addClass('alert alert-danger')
                            .add('span')
                            .text('Error al enviar Stock');
                        $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#danger-alert").slideUp(500);
                    });
                }
            });
            }else if ($('input[id=buy]').is(":not(:checked)")){
                // && selectedState != 6
                console.log('not checked');
                var cant = parseInt($('.cant').val());
                var price = $('.price').val();
                var observation = $('.observation').val();
                    var url = "/api-stock/public/index.php/stock/register/";
                    var depots = selectedDepotsDestino + '/';
                    var typeArticle = dataTypeArt;
                    var idArticle = idProduct;
                    var typeMove = 'output';
                    var data = {
                        id: idArticle,
                        cantidad: cant,
                        control: 0,
                        precio: price,
                        observaciones: observation,
                        usuario: userid,
                        idDetallePedido: idDetPed
                    };
                    console.log(data);
                    $.ajax({
                    url: url + depots + typeArticle + typeMove,
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function(data, textStatus, xhr) {
                        // var urlState = '/api-stock/public/index.php/stock/order/state/' + selectedState + '/' + order; 
                        // $.ajax({
                        //     type: "POST",
                        //     url: urlState,
                        //     dataType: "json",
                        //     success: function (response) {
                                $('#success-alert').addClass('alert alert-success')
                                    .add('span')
                                    .text('Stock enviado');
                                $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#success-alert").slideUp(500);
                                    history.back();
                                    
                                });
                        //     }
                        // });
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        $('#danger-alert').addClass('alert alert-danger')
                                .add('span')
                                .text('Error al enviar Stock');
                            $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#danger-alert").slideUp(500);
                        });
                    }
                });
            }
            // else{
            //     var urlState = '/api-stock/public/index.php/stock/order/state/' + selectedState + '/' + order; 
            //     $.ajax({
            //         type: "POST",
            //         url: urlState,
            //         dataType: "json",
            //         success: function (response) {
            //             $('#success-alert').addClass('alert alert-warning')
            //                 .add('span')
            //                 .text('Solicitud rechazada');
            //             $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
            //                 $("#success-alert").slideUp(500);
            //                 window.location.href = 'list_notes.php';
            //             });
            //         }
            //     });
            // }
        }
    });
    function listToMove(){
        if (userprofile != 1) {
        var url = "/api-stock/public/index.php/products/getbyid/";
        var depots = selectedDepotsDestino + '/'
        $.ajax({
            type: "GET",
            url: url + dataTypeArt + idProduct,
            dataType: "json",
            success: function (data) {
                let rows = data.result;
                let html = [];
                for (let i=0; i < rows.length; i++){
                    html.push(
                    `<tr <tr class="tr-article" idArticle="${rows[i].id}">
                    <td> ${rows[i].nombre} </td> 
                    <td> ${rows[i].marca} </td> 
                    <td> ${rows[i].descripcion} </td> 
                    <td>${dataCant}</td>
                    <td> <input type="number" value="${cantReal}" class="cant form-control" placeholder="Cant."> </td>
                    <td> <input type="text"class="observation form-control observation" placeholder="Observacion"> </td> 
                    <td> <input type="number" value="0" class="price form-control d-none" placeholder="Precio"> </td>
                    </tr>`
                    );    
                }
                    // <td> <input type="number" class="cant form-control" placeholder="Cantidad"> </td>
                $('#table-articles-toMove>tbody').html(html.join(''));
            }
        });
        }else{
            var url = "/api-stock/public/index.php/stock/get/" + selectedDepotsDestino + "/";
            $.ajax({
                type: "GET",
                url: url + dataTypeArt + idProduct,
                dataType: "json",
                success: function (data) {
                let rows = data.result;
                let html = [];
                for (let i=0; i < rows.length; i++){
                    var stock = rows[i].existencia;
                    if (stock == null || 0 || undefined) {
                        priceItem = 0;
                    }else{
                        stock = rows[i].existencia;
                    }
                    var priceItem = rows[i].precio;
                    if (priceItem == null || 0 || undefined) {
                        priceItem = 0;
                    }else{
                        priceItem = rows[i].precio;
                    }
                html.push(
                `<tr <tr class="tr-article" idArticle="${rows[i].idProducto}">
                    <td> ${rows[i].Producto} </td> 
                    <td> ${rows[i].marca} </td> 
                    <td> ${rows[i].descripcion} </td> 
                    <tdclass="d-none"> ${rows[i].tipo} </td> 
                    <td>${cantReal}</td>
                    <td>${stock}</td>
                    <td> <input type="number" name="cant[]" max="${rows[i].existencia}" min="1" value="${dataCant}" class="cant form-control" placeholder="Cant."> </td>
                    <td> <input type="text" name="observation[]" class="observation form-control" placeholder="Observacion"> </td> 
                    <td> <input type="number" name="price[]" value="${priceItem}" class="price form-control d-none" placeholder="Precio"> </td>
                    <td> <button class="codes-liberate btn btn-dark">Ident.</button> </td>
                </tr>`
                );    
            }
                $('#table-articles-toMove>tbody').html(html.join(''));
                // <td> <input type="number" class="cant form-control" placeholder="Cantidad"> </td>
                $('.cant').change(function() {
                var max = parseInt($(this).attr('max'));
                var min = parseInt($(this).attr('min'));
                if ($(this).val() > max){
                    $(this).val(max);
                }
                else if ($(this).val() < min){
                    $(this).val(min);
                }       
                });
                if (stock <= 0) {
                    $('.cant').prop('disabled', true);
                    $('.observation').prop('disabled', true);
                    $('.price').prop('disabled', true);
                    $('.codes-liberate').prop('disabled', true);
                }
                $('.codes-liberate').click(function (e) { 
                    e.preventDefault();
                    var article = $(this)[0].parentElement.parentElement;
                    var idArticle = $(article).attr('idArticle');
                    var typeArticle = $(this).parent().parent().find('td').eq(3).html();
                    window.localStorage.setItem("typeArticle", JSON.stringify(typeArticle));
                    window.localStorage.setItem("idArticle", JSON.stringify(idArticle));
                    window.localStorage.setItem("selectedDepotsOrigen", JSON.stringify(selectedDepotsOrigen));
                    window.localStorage.setItem("selectedDepotsDestino", JSON.stringify(selectedDepotsDestino));
                    window.location.href ='../views/code_liberate.php';
                });
            }
            
        });
    }
        // <td> <button class='btn btn-danger btn-sm' onClick="removeArticle()"> <i class='fas fa-trash'> </i> </button> </td>
}


    // function sendCodes(){
    //     list.items.forEach(function (dataCode, i){
    //         var urlSendCode = '/api-stock/public/index.php/stock/code/liberate/' + dataCode + selectedDepots;
    //         $.ajax({
    //             type: "POST",
    //             url: urlSendCode,
    //             dataType: "json",
    //             success: function (response) {
    //                 console.log(response);
    //             }
    //         });
    //     });
    // }
});
</script>
</body>
</html>