<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT']."/app-stock/head/head.php"); ?>
<div class="container">
    <h3>Inicio</h3>
    <h5>Vista estadistica de control</h5>
    <div class="d-flex justify-content-around">
        <div class="card my-2" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Consumibles</h5>
                <p class="card-text">Estadisticas de consumibles</p>
                <a href="#" id="consumables" class="btn btn-primary">Consumibles</a>
            </div>
        </div>
        <div class="card my-2" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Movimientos</h5>
                <p class="card-text">Cantidad de movimientos</p>
                <a href="#" id="movements" class="btn btn-primary">Movimientos</a>
            </div>
        </div>
    </div>
    <!-- <div class="d-flex justify-content-around">
        <div class="card my-2" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Notas de pedido</h5>
                <p class="card-text">Entrada de articulos</p>
                <a href="#" class="btn btn-primary">Notas</a>
            </div>
        </div>
        <div class="card my-2" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Observaciones</h5>
                <p class="card-text">Nuevas observaciones desde pañoles</p>
                <a href="#" class="btn btn-primary">Observaciones</a>
            </div>
        </div>
    </div> -->
</div>
<script>
    $(document).ready(function () {
        if (userprofile != 1) {
            window.location.href = "/app-stock/views/stock.php"; 
        }
    });
    $('#consumables').click(function (e) { 
        e.preventDefault();
        window.location.href = "/app-stock/views/statistics_consumables.php";   
    });
    $('#movements').click(function (e) { 
        e.preventDefault();
        window.location.href = "/app-stock/views/statistics_movements.php";   
    });
</script>
</body>
</html>