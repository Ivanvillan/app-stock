<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo pañol</title>
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/app-stock/head/head.php') ?>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col col-md-6">
                        <h4>Registro de depositos</h4>
                    </div>
                    <div class="col">
                        <button id="newUser" class="btn btn-link newUser float-right">Crear usuario</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="success-alert" role="alert">
                </div>
                <div id="danger-alert" role="alert">
                </div>
                <div class="card-title">
                    <h4 class="text-md-center ">Ingrese los datos para crear un deposito</h4>
                </div>
                <form action="" id="form-depot" method="post">
                    <div class="row">
                        <div class="form-group col col-md-6 offset-md-3">
                            <label for="">Nombre</label>
                            <input type="text"
                            name="depotsName" 
                            class="form-control"
                            placeholder="Ingrese un nombre">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col col-md-6 offset-md-3">
                            <label for="inputState">Tipo</label>
                            <select id="type-depots" class="form-control">
                                <option value="1" selected>Almacen</option>
                                <option value="2">Pañol</option>
                                <option value="3">Carro</option>
                                <option value="4">Casilla</option>
                            </select> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col col-md-6 offset-md-3">
                        <label for="">Ubicación</label>
                        <input type="text"
                            name="depotsLocation" 
                            class="form-control" 
                            placeholder="Ingrese ubicación">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col col-md-6 offset-md-3">
                            <label for="inputState">Responsable</label>
                            <select id="userSelect" class="form-control">
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col col-md-6 offset-md-3">
                        <label for="">Contrato</label>
                        <input type="text"
                            name="depotsContract" 
                            class="form-control" 
                            placeholder="Ingrese contrato">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col col-md-6 offset-md-3">
                        <label for="">Empresa</label>
                        <input type="text"
                            name="depotsCompany" 
                            class="form-control" 
                            placeholder="Ingrese empresa">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col col-md-6 offset-md-3">
                            <label for="">Click para seleccionar un color</label> <br>
                            <button class="btn btn-secondary colorPicker jscolor {valueElement: 'color_value'}">Seleccionar un color</button>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col col-md-6 offset-md-3">
                        <button id="createStoreroom" class="btn btn-primary float-right">Confirmar</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-6 offset-md-3">
                        <p>Ayuda</p>
                    </div>
                </div>
            </div>
            </div>
        </div>
<script src="../../js/jscolor.js"></script>
<script>
$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/users/get/all/Ivan Villan",
        dataType: "json",
        success: function (response) {
            console.log(response.result[0])
        }
    });
    var users = $.ajax({
        type: "GET",
        url: "/api-stock/public/index.php/users/get/all",
        dataType: "json",
            success: function (data) {
                console.log(data.result)
                $.each(data.result, function (i, item) { 
                var users = `<option value="${item.ID}">${item.nombre}</option>`;
                console.log(item.ID)
            $('#userSelect').append(users);
            });
        }
    });
    $('.newUser').click(function (e) { 
        e.preventDefault();
        window.location.href = "../login/register.php";
    });
    $('#createStoreroom').click(function(event) {
        var colorPicker = $('.colorPicker').css('background-color')
        var data = {
            nombre: $("input[name=depotsName]").val(),
            ubicacion: $("input[name=depotsLocation]").val(),
            tipo: $("select[id=type-depots]").val(),
            usuario: $("select[id=userSelect]").val(),
            contrato: $("input[name=depotsContract]").val(),
            empresa: $("input[name=depotsCompany]").val(),
            color: colorPicker,
        }
        jQuery.ajax({
          url: '/api-stock/public/index.php/depots/addorupdate',
          type: 'POST',
          dataType: 'json',
          data: data,
          success: function(data, textStatus, xhr) {
                $('#success-alert').addClass('alert alert-success')
                    .add('span')
                    .text('Deposito creado correctamente');
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#success-alert").slideUp(500);
                    $('span').remove();
                    $('#form-depot').trigger("reset");
            });
          },
          error: function(xhr, textStatus, errorThrown) {
                $('#danger-alert').addClass('alert alert-danger')
                    .add('span')
                    .text('Error al crear deposito');
                $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                $("#danger-alert").slideUp(500);
                $('span').remove();
            });
          }
        });
    });
});             
</script>
</body>
</html>