<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/api-stock/css/bootstrap.css">
    <title>Inicio de sesión</title>
</head>
<body>
    <div class="container">
        <div class="row ml-1">
            <div class="col-12">
                <h1 class="text-md-center">Bienvenido a Assa</h1>
            </div>
        </div>
        <div class="row ml-1">
            <div class="col-12">
                <h2 class="text-md-center">Compras | Stock</h2>
                <h3 class="text-md-center">Inicio de sesión</h3>
                <h4 class="text-md-center ">Ingrese sus datos para continuar</h4>
            </div>
        </div>
        <div id="success-alert" role="alert">
        </div>
        <div id="danger-alert" role="alert">
        </div>
        <form action="" method="post">
            <div class="row">
                <div class="form-group col-12 col-md-6 offset-md-3">
                <label for="">Nombre de usuario</label>
                <input type="text"
                    class="form-control"
                    name="username"
                    placeholder="Ingrese su nombre de usuario">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12 col-md-6 offset-md-3">
                    <label for="">Ingrese su contraseña</label>
                    <input type="password" 
                        class="form-control"
                        name="password"
                        placeholder="Ingrese su contraseña">
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-12 col-md-6 offset-md-3">
                <button id="log" class="btn btn-primary float-right">Ingresar</button>
            </div>
        <!-- <div class="row">
            <div class="col-12 col-md-6 offset-md-3">
                <p>¿Olvidaste tu contraseña?</p>
                <p>Ayuda</p>
                <a class="btn btn-dark" href="/app-stock/login/register.php">Registrarse</a>
            </div>
        </div> -->
    </div>
    <script src="/api-stock/js/jquery-3.4.1.js"></script>
    <script src="/api-stock/js/bootstrap.min.js"></script>
    <script>
        $('#log').click(function (e) { 
            e.preventDefault();
            var username = $('input[name=username]').val();
            var password = $('input[name=password]').val();
            var data = {
                nombre: username,
                contrasenia: password
            }
            $.ajax({
                type: "POST",
                url: "/api-stock/public/index.php/users/login",
                data: data,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    if (data.message != "Acceso Denegado") {
                    $('#success-alert').addClass('alert alert-success')
                        .add('span')
                        .text(data.message);
                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                        $('span').remove();
                      window.location.href = "/app-stock/views/index.php";   
                    })
                    }else{
                    $('#danger-alert').addClass('alert alert-danger')
                        .add('span')
                        .text(data.message + ', ingrese sus datos correctamente');
                    $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#danger-alert").slideUp(500);
                        $('span').remove(); 
                    })
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    $('#danger-alert').addClass('alert alert-danger')
                        .add('span')
                        .text('Error al agregar ingresar');
                    $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                    $("#danger-alert").slideUp(500);
                });
                }
            });
        });
    </script>
</body>
</html>