<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo articulo</title>
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/app-stock/head/head.php') ?>
<div class="container">
    <div class="card mb-2">
        <div class="card-header">
            <h4>Registro de artículos</h4>
        </div>
    <div class="card-body">
    <div class="card-title">
        <h4 id="title-form" class="text-center"></h4>
    </div>
    <div id="success-alert" role="alert">
    </div>
    <div id="danger-alert" role="alert">
    </div>
    <div class="row">
        <div class="form-group col col-md-6 offset-md-3">
            <label for="inputState">Selecciona tipo de articulo</label>
            <select id="select-articles" class="form-control">
                <option selected>Herramientas</option>
                <option>Equipos</option>
                <option>Consumibles</option>
                <option>Indumentaria</option>
            </select> 
        </div>
    </div>
        <!-- Herramienta -->
    <div id="tool" class="d-form d-none">
        <form method="post" id="form-tool">
        <div class="row">
                <div class="form-group col col-md-6 offset-md-3">
                    <label for="inputState">Subtipo</label>
                    <select id="subtype-tool" class="subtype form-control">
                        <option value="0" selected>Seleccione subtipo</option>
                    </select> 
                </div>
            </div>
            <div class="row">
                <div class="form-group col col-md-3 offset-md-3">
                    <label for="">Nombre</label>
                    <input type="text" name="nombreTool" class="form-control text-capitalize" placeholder="Ingrese nombre">
                </div>

                <div class="form-group col col-md-3">
                    <label for="">Marca</label>
                    <input type="text" name="marcaTool" class="form-control text-capitalize" placeholder="Ingrese marca">
                </div>
            </div>
            <!-- <div class="row">
                <div class="form-group col col-md-6 offset-md-3">
                    <label for="inputState">Dieléctrica</label>
                    <select id="ifDielectrica" class="form-control">
                        <option value="0" selected>No</option>
                        <option value="1">Si</option>
                    </select> 
                </div>
            </div> -->
            <div class="row">
                <div class="form-group col col-md-6 offset-md-3">
                    <label for="">Detalle</label>
                    <input type="text" name="detalleTool" class="form-control text-capitalize" placeholder="Ingrese detalle">
                </div>
            </div>

            <div class="row">
                <div class="col col-md-6 offset-md-3">
                    <button class="btn btn-primary float-right" id="addTool">Confirmar</button>
                </div>
            </div>
        </form>
    </div>
            <!-- Equipo -->
    <div id="hardware" class="d-form d-none">
        <form method="post" id="form-hardware">
        <div class="row">
                <div class="form-group col col-md-6 offset-md-3">
                    <label for="inputState">Subtipo</label>
                    <select id="subtype-hardware" class="subtype form-control">
                        <option value="0" selected>Seleccione subtipo</option>
                    </select> 
                </div>
            </div>
            <div class="row">
                <div class="form-group col col-md-3 offset-md-3">
                    <label for="">Nombre</label>
                    <input type="text" name="nombreHardware" class="form-control text-capitalize" placeholder="Ingrese nombre">
                </div>

                <div class="form-group col col-md-3">
                    <label for="">Marca</label>
                    <input type="text" name="marcaHardware" class="form-control text-capitalize" placeholder="Ingrese marca">
                </div>
            </div>

            <div class="row form-group">
                <div class="col col-md-3 offset-md-3">
                    <label for="">Código</label>
                    <input type="text" name="codHardware" class="form-control text-capitalize" placeholder="Ingrese código">
                </div>
                <div class="col col-md-3">
                    <label for="">Modelo</label>
                    <input type="text" name="modeloHardware" class="form-control text-capitalize" placeholder="Ingrese modelo">
                </div>
            </div>

            <div class="row form-group">
                <div class="col col-md-3 offset-md-3">
                    <label for="">Medida | Longitud</label>
                    <input type="number" name="medidaHardware" class="form-control text-capitalize" placeholder="Ingrese medida">
                </div>
                <div class="col col-md-3">
                    <label for="">Unidad</label>
                    <select name="" id="unit-1" class="form-control">
                        <option value="">Seleccione unidad</option>
                        <option value="Pulgadas">Pulgadas</option>
                        <option value="Mts">Mts</option>
                        <option value="Cm">Cm</option>
                        <option value="Mm">Mm</option>
                    </select>
                    <!-- <input type="text" id="measure" name="medidaHardware" class="form-control text-capitalize" placeholder="Ingrese unidad de medida"> -->
                </div>
            </div>

            <div class="row form-group">
                <div class="col col-md-3 offset-md-3">
                    <label for="">Potencia | Tonelada</label>
                    <input type="number" name="potenciaHardware" class="form-control text-capitalize" placeholder="Ingrese potencia">
                </div>
                <div class="col col-md-3">
                    <label for="">Unidad</label>
                    <select name="" id="unit-2" class="form-control">
                        <option value="">Seleccione unidad</option>
                        <option value="Ampere">Ampere</option>
                        <option value="KG">KG</option>
                        <option value="kW">kW</option>
                        <option value="Toneladas">Toneladas</option>
                        <option value="Watts">Watts</option>
                    </select>
                    <!-- <input type="text" id="potency" name="potenciaHardware" class="form-control text-capitalize" placeholder="Ingrese unidad de medida"> -->
                </div>
            </div>

            <!-- <div class="row form-group ml-3">
                <div class="col col-md-2 offset-md-3 isType">
                    <input type="checkbox" class="form-check-input type" name="" id="type" value="checkedValue">
                    <label class="form-check-label">
                        Tipo
                    </label>
                </div>
                <div class="col col-md-2 isTon">
                    <input type="checkbox" class="form-check-input ton" name="" id="ton" value="checkedValue">
                    <label class="form-check-label">
                        Toneladas
                    </label>
                </div>
                <div class="col col-md-2 isLength">
                    <input type="checkbox" class="form-check-input length" name="" id="lenght" value="checkedValue">
                    <label class="form-check-label">
                        Longitud
                    </label>
                </div>
            </div>
            <div class="row inputType d-none">
                <div class="form-group col col-md-6 offset-md-3">
                    <input type="text" name="tipoHardware" class="form-control text-capitalize" placeholder="Ingrese tipo">
                </div>
            </div>
            <div class="row inputTon d-none">
                <div class="form-group col col-md-6 offset-md-3">
                    <input type="text" name="toneladaHardware" class="form-control text-capitalize" placeholder="Ingrese tonelada">
                </div>
            </div>
            <div class="row inputLenght d-none">
                <div class="form-group col col-md-6 offset-md-3">
                    <input type="text" name="longitudHardware" class="form-control text-capitalize" placeholder="Ingrese longitud">
                </div>
            </div> -->
        
            <div class="row mt-2">
                <div class="col col-md-6 offset-md-3">
                    <button id="addHardware" class="btn btn-primary float-right">Confirmar</button>
                </div>
            </div>
        </form>
    </div>
        <!-- Consumible -->
    <div id="consumables" class="d-form d-none">
        <form method="post" id="form-consumables">
        <div class="row">
                <div class="form-group col col-md-6 offset-md-3">
                    <label for="inputState">Subtipo</label>
                    <select id="subtype-consumable" class="subtype form-control">
                        <option value="0" selected>Seleccione subtipo</option>
                    </select> 
                </div>
            </div>

            <div class="row">
                <div class="form-group col col-md-3 offset-md-3">
                    <label for="">Nombre</label>
                    <input type="text" name="nombreConsumable" class="form-control text-capitalize"placeholder="Ingrese nombre">
                </div>

                <div class="form-group col col-md-3">
                    <label for="">Marca</label>
                    <input type="text" name="marcaConsumable" class="form-control text-capitalize" placeholder="Ingrese marca">
                </div>
            </div>

            <div class="row form-group">
                <div class="form-group col col-md-6 offset-md-3">
                    <label for="">Código</label>
                    <input type="text" name="codConsumable" class="form-control text-capitalize" placeholder="Ingrese código">
                </div>
            </div>

            <div class="row">
                <div class="form-group col col-md-6 offset-md-3">
                    <label for="">Detalle</label>
                    <input type="text" name="detalleConsumable" class="form-control text-capitalize" placeholder="Ingrese detalle">
                </div>
            </div>

            <div class="row">
                <div class="col col-md-6 offset-md-3">
                    <button id="addConsumable" class="btn btn-primary float-right">Confirmar</button>
                </div>
            </div>
        </form>
    </div>
        <!-- Indumentaria -->
    <div id="dress" class="d-form d-none">
        <form method="post" id="form-dress">
        
        <div class="row">
                <div class="form-group col col-md-6 offset-md-3">
                    <label for="inputState">Subtipo</label>
                    <select id="subtype-dress" class="subtype form-control">
                        <option value="0" selected>Seleccione subtipo</option>
                    </select> 
                </div>
            </div>

            <div class="row">
                <div class="form-group col col-md-3 offset-md-3">
                    <label for="">Nombre</label>
                    <input type="text" name="nombreDress" class="form-control text-capitalize" placeholder="Ingrese nombre">
                </div>

                <div class="form-group col col-md-3">
                    <label for="">Marca</label>
                    <input type="text" name="marcaDress" class="form-control text-capitalize" placeholder="Ingrese marca">
                </div>
            </div>

            <div class="row form-group">
            <div class="form-group col col-md-6 offset-md-3">
                    <label for="">Talle</label>
                    <input type="text" name="talleDress" class="form-control text-capitalize" placeholder="Ingrese talle">
                </div>
            </div>

            <div class="row">
                <div class="form-group col col-md-6 offset-md-3">
                    <label for="">Detalle</label>
                    <input type="text" name="detalleDress" class="form-control text-capitalize" placeholder="Ingrese detalle">
                </div>
            </div>
            <div class="row">
                <div class="col col-md-6 offset-md-3">
                    <button id="addDress" class="btn btn-primary float-right">Confirmar</button>
                </div>
            </div>
        </form>
    </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        checked1();
        checked2();
        checked3();
        function checked1(){
        $('#type').change(function() {
            // this will contain a reference to the checkbox   
            if (this.checked) {
                $('.inputType').addClass('d-block');
            } else {
                $('.inputType').removeClass('d-block');
            }
        });
        }
        function checked2(){
        $('#ton').change(function() {
            // this will contain a reference to the checkbox   
            if (this.checked) {
                $('.inputTon').addClass('d-block');
            } else {
                $('.inputTon').removeClass('d-block');
            }
        });
        }
        function checked3(){
        $('#lenght').change(function() {
            // this will contain a reference to the checkbox   
            if (this.checked) {
                $('.inputLenght').addClass('d-block');
            } else {
                $('.inputLenght').removeClass('d-block');
            }
        });
        }
        if ($('#tool').hasClass('d-none')) {
            $('#title-form').empty();
            $('#title-form').append($('select[id=select-articles]').val());
            $('#tool').addClass('d-block');
            $('.subtype').trigger("reset");
            $.ajax({
                type: "GET",
                url: "/api-stock/public/index.php/products/subtypes/tools",
                dataType: "json",
                success: function (response) {
                    console.log(response)
                    $.each(response.result, function(index, item) {
                        var listSubtype = `<option value="${item.id}">${item.nombre}</option>`;
                        $('#subtype-tool').append(listSubtype);      
                    });
                }
            });
            $('#addTool').click(function (e) { 
                e.preventDefault();
                data = {
                    nombre: $("input[name=nombreTool]").val(),
                    dielectrica: 0,
                    marca: $("input[name=marcaTool]").val(),
                    subtipo: $("select[id=subtype-tool]").val(),
                    detalle: $("input[name=detalleTool]").val(),
                };
                console.log(data);
                $.ajax({
                    type: "POST",
                    url: '/api-stock/public/index.php/products/addorupdate/tools',
                    data: data,
                    success: function(data, status){
                        console.log(data + status);
                        $('#success-alert').addClass('alert alert-success')
                            .add('span')
                            .text('Artículo agregado correctamente');
                            $("#addTool").attr("disabled", true);
                                setTimeout(function() {
                                    $("#addTool").removeAttr("disabled");      
                                }, 4000);
                        $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#success-alert").slideUp(500);
                            // $('#form-tool').trigger("reset");
                    });
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $('#danger-alert').addClass('alert alert-danger')
                            .add('span')
                            .text('Error al agregar artículo');
                        $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#danger-alert").slideUp(500);
                    });
                    },
                    dataType: 'json'
                });
            });
        }
        $('#tool').addClass('d-block');
        $('#select-articles').change(function (e) { 
            $('.subtype').trigger("reset");
            e.preventDefault();
            console.log($('select[id=select-articles]').val());
            if ($('select[id=select-articles]').val() == 'Herramientas') {
                $.ajax({
                    type: "GET",
                    url: "/api-stock/public/index.php/products/subtypes/tools",
                    dataType: "json",
                    success: function (response) {
                        console.log(response)
                        $.each(response.result, function(index, item) {
                            var listSubtype = `<option value="${item.id}">${item.nombre}</option>`;
                            $('#subtype-tool').append(listSubtype);      
                        });
                    }
                });
                $('#title-form').empty();
                $('#title-form').append($('select[id=select-articles]').val());
                $('.d-form').removeClass('d-block');
                $('#tool').addClass('d-block');
                $('#addTool').click(function (e) { 
                e.preventDefault();
                data = {
                    nombre: $("input[name=nombreTool]").val(),
                    dielectrica: 0,
                    marca: $("input[name=marcaTool]").val(),
                    subtipo: $("select[id=subtype-tool]").val(),
                    detalle: $("input[name=detalleTool]").val(),
                };
                console.log(data);
                $.ajax({
                    type: "POST",
                    url: '/api-stock/public/index.php/products/addorupdate/tools',
                    data: data,
                    success: function(data, status){
                        console.log(data + status);
                        $('#success-alert').addClass('alert alert-success')
                            .add('span')
                            .text('Artículo agregado correctamente');
                            $("#addTool").attr("disabled", true);
                                setTimeout(function() {
                                    $("#addTool").removeAttr("disabled");      
                                }, 4000);
                        $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#success-alert").slideUp(500);
                            // $('#form-tool').trigger("reset");
                    });
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $('#danger-alert').addClass('alert alert-danger')
                            .add('span')
                            .text('Error al agregar artículo');
                        $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#danger-alert").slideUp(500);
                    });
                    },
                    dataType: 'json'
                });
            });
            }
            if ($('select[id=select-articles]').val() == 'Equipos') {
                $('.subtype').trigger("reset");
                $.ajax({
                    type: "GET",
                    url: "/api-stock/public/index.php/products/subtypes/hardware",
                    dataType: "json",
                    success: function (response) {
                        console.log(response)
                        $.each(response.result, function(index, item) {
                            var listSubtype = `<option value="${item.id}">${item.nombre}</option>`;
                            $('#subtype-hardware').append(listSubtype);      
                        });
                    }
                });
                $('#title-form').empty();
                $('#title-form').append($('select[id=select-articles]').val());
                $('.d-form').removeClass('d-block');
                $('#hardware').addClass('d-block'); 
                $('#addHardware').click(function (e) { 
                e.preventDefault();
                var measure = $('input[name=medidaHardware]').val() + ' ' + $('select[id=unit-1]').val();
                var potency = $('input[name=potenciaHardware]').val() + ' ' + $('select[id=unit-2]').val();
                data = {
                    nombre: $("input[name=nombreHardware]").val(),
                    codigo: $("input[name=codHardware]").val(),
                    marca: $("input[name=marcaHardware]").val(),
                    modelo: $("input[name=modeloHardware]").val(),
                    medida: measure,
                    potencia: potency,
                    // tipo: $("input[name=tipoHardware]").val(),
                    // tonelada: $("input[name=toneladaHardware]").val(),
                    // longitud: $("input[name=longitudHardware]").val(),
                    categoria: $("select[id=subtype-hardware]").val()
                };
                console.log(data);
                $.ajax({
                    type: "POST",
                    url: '/api-stock/public/index.php/products/addorupdate/hardware',
                    data: data,
                    success: function(data, status){
                        console.log(data + status);
                        $('#success-alert').addClass('alert alert-success')
                            .add('span')
                            .text('Artículo agregado correctamente');
                            $("#addHardware").attr("disabled", true);
                                setTimeout(function() {
                                    $("#addHardware").removeAttr("disabled");      
                                }, 4000);
                        $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#success-alert").slideUp(500);
                            // $('#form-hardware').trigger("reset");
                    });
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $('#danger-alert').addClass('alert alert-danger')
                            .add('span')
                            .text('Error al agregar artículo');
                        $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#danger-alert").slideUp(500);
                    });
                    },
                    dataType: 'json'
                });
            });
            }
            if ($('select[id=select-articles]').val() == 'Consumibles') {
                $('.subtype').trigger("reset");
                $.ajax({
                    type: "GET",
                    url: "/api-stock/public/index.php/products/subtypes/consumables",
                    dataType: "json",
                    success: function (response) {
                        console.log(response)
                        $.each(response.result, function(index, item) {
                            var listSubtype = `<option value="${item.id}">${item.nombre}</option>`;
                            $('#subtype-consumable').append(listSubtype);      
                        });
                    }
                });
                $('#title-form').empty();
                $('#title-form').append($('select[id=select-articles]').val());
                $('.d-form').removeClass('d-block');
                $('#consumables').addClass('d-block');
                $('#addConsumable').click(function (e) { 
                e.preventDefault();
                data = {
                    nombre: $("input[name=nombreConsumable]").val(),
                    codigo: $("input[name=codConsumable]").val(),
                    marca: $("input[name=marcaConsumable]").val(),
                    detalle: $("input[name=detalleConsumable]").val(),
                    tipo: $("select[id=subtype-consumable]").val(),
                };
                console.log(data);
                $.ajax({
                    type: "POST",
                    url: '/api-stock/public/index.php/products/addorupdate/consumables',
                    data: data,
                    success: function(data, status){
                        console.log(data + status);
                        $('#success-alert').addClass('alert alert-success')
                            .add('span')
                            .text('Artículo agregado correctamente');
                            $("#addConsumable").attr("disabled", true);
                                setTimeout(function() {
                                    $("#addConsumable").removeAttr("disabled");      
                                }, 4000);
                        $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#success-alert").slideUp(500);
                            // $('#form-consumables').trigger("reset");

                    });
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $('#danger-alert').addClass('alert alert-danger')
                            .add('span')
                            .text('Error al agregar artículo');
                        $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#danger-alert").slideUp(500);
                    });
                    },
                    dataType: 'json'
                });
            });
            }
            if ($('select[id=select-articles]').val() == 'Indumentaria') {
                $('.subtype').trigger("reset");
                $.ajax({
                    type: "GET",
                    url: "/api-stock/public/index.php/products/subtypes/dress",
                    dataType: "json",
                    success: function (response) {
                        console.log(response)
                        $.each(response.result, function(index, item) {
                            var listSubtype = `<option value="${item.id}">${item.nombre}</option>`;
                            $('#subtype-dress').append(listSubtype);      
                        });
                    }
                });
                $('#title-form').empty();
                $('#title-form').append($('select[id=select-articles]').val());
                $('.d-form').removeClass('d-block');
                $('#dress').addClass('d-block');
                $('#addDress').click(function (e) { 
                e.preventDefault();
                data = {
                    nombre: $("input[name=nombreDress]").val(),
                    marca: $("input[name=marcaDress]").val(),
                    talle: $("input[name=talleDress]").val(),
                    detalle: $("input[name=detalleDress]").val(),
                    tipo: $("select[id=subtype-dress]").val(),
                };
                console.log(data);
                $.ajax({
                    type: "POST",
                    url: '/api-stock/public/index.php/products/addorupdate/dress',
                    data: data,
                    success: function(data, status){
                        console.log(data + status);
                        $('#success-alert').addClass('alert alert-success')
                            .add('span')
                            .text('Artículo agregado correctamente');
                            $("#addDress").attr("disabled", true);
                                setTimeout(function() {
                                    $("#addDress").removeAttr("disabled");      
                                }, 4000);
                        $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#success-alert").slideUp(500);
                            // $('#form-dress').trigger("reset");
                    });
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $('#danger-alert').addClass('alert alert-danger')
                            .add('span')
                            .text('Error al agregar artículo');
                        $("#danger-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#danger-alert").slideUp(500);
                    });
                    },
                    dataType: 'json'
                });
            });
            }                    
        });
    })
</script>
</body>
</html>