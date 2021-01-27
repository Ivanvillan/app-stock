<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/api-stock/css/bootstrap.css">
    <title>Registro</title>
</head>
<body>
    <div class="container">
        <div class="row ml-1">
            <div class="col-12">
                <h3 class="text-md-center">Registro de usuario</h3>
                <h4 class="text-md-center ">Ingrese los datos para continuar</h4>
            </div>
        </div>
        <div id="success-alert" role="alert">
        </div>
        <div id="danger-alert" role="alert">
        </div>
        <form id="form-register" method="post">
            <!-- <div class="row">
                <div class="form-group col col-md-6 offset-md-3">
                    <label for="inputState">¿Responsable de un?</label>
                    <select id="type-user" class="form-control">
                        <option value="1" selected>Almacen</option>
                        <option value="2">Pañol</option>
                        <option value="3">Carro</option>
                        <option value="4">Casilla</option>
                    </select> 
                </div>
            </div> -->
            <div class="row">
                <div class="form-group col-12 col-md-3 offset-md-3">
                    <label for="">Nombre</label>
                    <input type="text"
                    class="form-control"
                    name="firstname"
                    placeholder="Ingrese un nombre de usuario">
                </div>
                <div class="form-group col-12 col-md-3">
                    <label for="">Apellido</label>
                    <input type="text"
                    id="lastname"
                    class="form-control"
                    name="lastname"
                    placeholder="Ingrese un apellido de usuario">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12 col-md-6 offset-md-3">
                <label for="">Email de usuario</label>
                <input type="email"
                    class="form-control"
                    name="usermail"
                    placeholder="Ingrese un email">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12 col-md-6 offset-md-3">
                <label for="">Contraseña de usuario</label>
                <input type="password" 
                    name="passworduser"
                    class="form-control" 
                    placeholder="Ingrese una contraseña">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12 col-md-6 offset-md-3">
                <label for="inputState">Tipo de usuario</label>
                <select id="type-user" class="form-control">
                    <option value="0" selected>Elige tipo de usuario</option>
                    <option value="1">Administrador</option>
                    <option value="2">Usuario</option>
                </select> 
                </div>
            </div>
            <!-- <div class="form-group col-12 col-md-6 offset-md-3">
                <label for="">Confirmar contraseña de usuario</label>
                <input type="password" 
                class="form-control" 
                placeholder="Confirmación de contraseña">
            </div> -->
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3">
                    <button id="addUser" class="btn btn-primary float-right">Confirmar</button>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-12 col-md-6 offset-md-3">
                <button class="btn btn-link backDepot">Volver</button>
            </div>
        </div>
    </div>
</body>
<script src="/api-stock/js/jquery-3.4.1.js"></script>
<script src="/api-stock/js/bootstrap.min.js"></script>
<script>
$(document).ready(function () {
    $('#addUser').click(function (e) { 
    e.preventDefault();
    var username = $("input[name=firstname]").val() + ' ' + $("input[id=lastname]").val();
    data = {
        nombre: username,
        email: $("input[name=usermail]").val(),
        contrasenia: $("input[name=passworduser]").val(),
        perfil: $("select[id=type-user]").val(),
    };
    console.log(data);
    $.ajax({
        type: "POST",
        url: "/api-stock/public/index.php/users/create",
        data: data,
        dataType: 'json',
        success: function(data, status){
            console.log(data + status);
            $('#success-alert').addClass('alert alert-success')
                .add('span')
                .text('Usuario creado correctamente');
            $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                $("#success-alert").slideUp(500);
                $('#form-register').trigger("reset");
        });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $('#danger-alert').addClass('alert alert-danger')
                .add('span')
                .text('Error al crear usuario');
            $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                $("#danger-alert").slideUp(500);
        });
        },
    });
});
$('.backDepot').click(function (e) { 
    e.preventDefault();
    window.history.back();
});
});
</script>
</html>
