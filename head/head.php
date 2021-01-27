<?php
session_start();
if (isset($_SESSION['USER'])) {
  $userid = $_SESSION['USER'];
  $username = $_SESSION['NAME']; 
  $userprofile = $_SESSION['PROFILE']; 
} else {
  header("Location: /app-stock/login/login.php");
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title></title>

    <link rel="stylesheet" href= "/api-stock/css/bootstrap.min.css" >
    <link rel="stylesheet" href="/api-stock/css/sidebar.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><a href="#" class="indexLogo"><img src="/api-stock/assets/assa-sijam.png" alt="Assa-Sijam" max-width="100" height="130"></a></h3>
                <strong><a href="#" class="indexLogo"><img src="/api-stock/assets/assa-sijam.png" alt="Assa Sijam" width="50" height="50"></a></strong>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-receipt"></i>
                        Pedidos
                    </a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <a href="list_notes.php" class="list-order">Listado de pedidos</a>
                        </li>
                        <li id="newnote">
                            <a href="new_note.php" class="new-nota">Crear nota de pedido</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#homeSubmenu0" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-exchange-alt"></i>
                        E/S de stock
                    </a>
                    <ul class="collapse list-unstyled" id="homeSubmenu0">
                        <li>
                            <a href="inputs.php" class="input_stock">Ingreso de stock</a>
                        </li>
                        <li>
                            <a href="#homeSubmenu1" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Envío de stock</a>
                            <ul class="collapse list-unstyled" id="homeSubmenu1">
                                <li>
                                    <a href="output_stock.php" class="output_stock">Crear envío</a>
                                </li>
                                <li>
                                    <a href="list_outputStock.php" class="list-output-depot">Lista de envíos</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#homeSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Ingreso a depósito</a>
                            <ul class="collapse list-unstyled" id="homeSubmenu2">
                                <li>
                                    <a href="input_stock.php" class="auto_input">Ingreso a depósito especifico</a>
                                </li>
                                <li>
                                    <a href="returnStock.php" class="return-stock">Devolver Stock</a>
                                </li>
                                <li>
                                    <a href="list_returnStock.php" class="list-return-stock">Stock devuelto</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#homeSubmenu3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-paper-plane"></i>
                        Movimientos
                    </a>
                    <ul class="collapse list-unstyled" id="homeSubmenu3">
                        <li>
                            <a href="list_derived.php" class="derived">Derivaciones</a>
                        </li>
                        <li>
                            <a href="list_repair.php" class="repair">Reparaciones</a>
                        </li>
                        <li>
                            <a href="list_maintenance.php" class="repair">Mantenimientos</a>
                        </li>
                        <li>
                            <a href="list_reduce.php" class="drop">Bajas</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="stock.php">
                        <i class="fas fa-arrow-alt-circle-down"></i>
                        Stock
                    </a>
                </li>
                <li id="articles">
                    <a href="#homeSubmenu4" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-list"></i>
                        Articulos
                    </a>
                    <ul class="collapse list-unstyled" id="homeSubmenu4">
                        <li>
                            <a href="list_tool.php">
                                Listado de articulos
                            </a>
                        </li>
                        <li>
                            <a href="new_article.php">
                                Crear Articulo
                            </a>
                        </li>
                    </ul>
                </li>
                <li id="depots">
                    <a href="#homeSubmenu5" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-store-alt  "></i>
                        Depositos
                    </a>
                    <ul class="collapse list-unstyled" id="homeSubmenu5">
                        <li>
                            <a href="list_depots.php">Administrar</a>
                        </li>
                        <li>
                            <a href="new_depot.php">Crear deposito</a>
                        </li>
                    </ul>
                </li>
                <li>
                <li id="users">
                    <a href="#homeSubmenu6" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="fas fa-users"></i>
                        Usuarios
                    </a>
                    <ul class="collapse list-unstyled" id="homeSubmenu6">
                        <li>
                            <a href="users.php">Administrar</a>
                        </li>
                        <li>
                            <a href="/app-stock/login/register.php">Crear usuario</a>
                        </li>
                    </ul>
                </li>
                <!-- <li>
                    <a>
                        <i class="fas fa-info-circle "></i>
                        Informes
                    </a>
                </li> -->
            </ul>
        </nav>
        <div id="content" class="col">
                <div class="col-12">
                <nav style="margin-bottom: 10px; margin-top: -5px; padding-top: 10px;" class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn button-toggle">
                        <i class="fas fa-align-left"></i>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fas fa-bell"></i></a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" id="username" href="#"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" id="logout">Salir</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    <div>
</div>
<script src="/api-stock/js/jquery-3.4.1.js"></script>
<script src="/api-stock/js/popper.min.js"></script>
<script src="/api-stock/js/bootstrap.min.js"></script>
<script type="text/javascript">
    var userid = <?php echo json_encode($userid) ?>;
    var username = <?php echo json_encode($username) ?>;
    var userprofile = <?php echo json_encode($userprofile) ?>;
    $(document).ready(function () {
        var upname = username.toUpperCase();
        var textname = `<p style="font-weight: bold;">${upname}<p>`;
        $('#username').append(textname);
        console.log('id: ' + userid);
        console.log('name: ' + username);
        console.log('profile: ' + userprofile);
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
        $('#username').click(function (e) { 
            e.preventDefault();
            $.ajax({
            type: "POST",
            url: "/api-stock/public/index.php/users/state",
            dataType: "json",
            success: function (response) {
                console.log(response)
            }
        });
        });
        $('#logout').click(function (e) { 
            e.preventDefault();
            var data = {
            id: userid,
        }
        $.ajax({
            type: "POST",
            url: "/api-stock/public/index.php/users/logout",
            dataType: "json",
            data: data,
            success: function (response) {
                console.log(response)
                window.location.href = "/app-stock/login/login.php"; 
            }
        });
        });
        if (userprofile != 1) {
            $('#articles').remove();
            $('#depots').remove();
            $('#inputs').remove();
            $('.auto_input').remove();
            $('.input_stock').remove();
            $('.output_stock').remove();
            $('#users').remove();
            $('.list-return-stock').remove();
        }
        if (userprofile == 1) {
            $('#newnote').remove();
            $('.return-stock').remove();
            $('.indexLogo').click(function (e) { 
                e.preventDefault();
                window.location.href = "/app-stock/views/index.php";
            });
        }
    });
</script>
</body>
</html>