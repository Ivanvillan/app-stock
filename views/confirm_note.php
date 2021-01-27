<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de solicitud interna</title>
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
                        <h3>Confirmación de los artículos a solicitar</h3>
                    </div>
                    <div class="card-body">
                    <div id="success-alert" role="alert">
                    </div>
                    <div id="danger-alert" role="alert">
                    </div>
                    <div class="row">
                        <div class="col">
                            <table id="table-articles-toMove" class="table table-hover">
                                <thead>
                                    <tr id="tr-toMove">
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Marca</th>
                                        <th scope="col">Descripcion</th>
                                        <th scope="col">Cantidad</th>
                                    </tr>
                                </thead>
                                <form id="form-data">
                                    <tbody>
                                    </tbody>
                                </form>
                            </table>   
                            <div>
                                <textarea class="form-control observation col-md-6" rows="3" row="3" placeholder="Observacion"></textarea>
                            </div>
                            <div class="col mr-2">
                            <button id="addStock" class="btn btn-primary float-right" onClick="sendNote()">Enviar</button>
                        </div>                  
                        </div>
                    </div>
                    <iframe id="txtArea1" style="display:none"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/jspdf@1.5.3/dist/jspdf.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable@3.5.3/dist/jspdf.plugin.autotable.js"></script>
<script>
    var selectedDepots = JSON.parse(window.localStorage.getItem("selectedDepots"));
    var list = JSON.parse(window.localStorage.getItem("list"));
    $(document).ready(function () {
        console.log(list)
        console.log(selectedDepots);
        listToMove();
    });
function sendNote(){
    var typeArticle = "";
    var idArticle = "";
    var itemsResult = [];
    var observation = $('.observation').val();
    var cantArray = new Array();
    $('.cant').each(function(){
        cantArray.push($(this).val());
    });
    list.items.forEach(function (result, index){
        typeArticle = result.split('/')[0];
        idArticle = result.split('/')[1]; 
        itemsArray = {
            idProducto: idArticle,
            Tipo: typeArticle,
            Cantidad: cantArray[index],
        }
        itemsResult.push(itemsArray); 
    });
    var url = "/api-stock/public/index.php/stock/order/create";
    var dataCant = {data: {
        Origen: selectedDepots,
        Destino: 1,
        idUsuario: userid, 
        Observacion: observation,
        items: itemsResult,
    }};
    console.log(observation);
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: dataCant,
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
}
function listToMove(){
    list.items.forEach(function (result, index){
    var url = "/api-stock/public/index.php/products/getbyid/";
    $.ajax({
        type: "GET",
        url: url + result,
        dataType: "json",
        success: function (response) {
            console.log(response.result);
            var stock = response.result[0].StockResumen;
            if (stock == null) {
                stock = 'Sin Stock'
            }else{
                stock = response.result[0].StockResumen;
            }
            var row = `<tr class="tr-article" idArticle="${response.result[0].id}">
            <td> ${response.result[0].nombre} </td> 
            <td> ${response.result[0].marca} </td> 
            <td> ${response.result[0].descripcion} </td>
            <td> <input type="number" name="cant[]" class="cant form-control cant" placeholder="Cantidad"> </td>
            </tr>`;
        $('#table-articles-toMove>tbody').append(row);
        }
    });
});
         // <td> <button class='btn btn-danger btn-sm' onClick="removeArticle()"> <i class='fas fa-trash'> </i> </button> </td>
}
</script>
</body>
</html>