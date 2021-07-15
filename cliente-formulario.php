<?php

include_once "config.php";
include_once "entidades/cliente.php";
include_once "entidades/provincia.entidad.php";
include_once "entidades/localidad.entidad.php";



$pg = "Edición de cliente";

$cliente = new Cliente();
$cliente->cargarFormulario($_REQUEST);



if ($_POST) {

    if (isset($_POST["btnGuardar"])) {
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            //Actualizo un cliente existente
            $cliente->actualizar();
        } else {
            //Es nuevo
            $cliente->insertar();
        }
    } else if (isset($_POST["btnBorrar"])) {
        $cliente->eliminar();
        header("Location: clientes.php");
    }
}

if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $cliente->obtenerPorId();
}

$provincia = new Provincia();
$aProvincias = $provincia->obtenerTodos();

$localidad = new Localidad();
$aLocalidades = $localidad->obtenerPorProvincia($localidad->nombre);
// arreglar esto o preguntarle a toty


include_once("header.php");
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Cliente</h1>
    <div class="row">
        <div class="col-12 mb-3">
            <a href="clientes.php" class="btn btn-primary mr-2">Listado</a>
            <a href="cliente-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
            <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
            <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
        </div>
    </div>
    <div class="row">
        <div class="col-6 form-group">
            <label for="txtNombre">Nombre:</label>
            <input type="text" required class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $cliente->nombre; ?>">
        </div>
        <div class="col-6 form-group">
            <label for="txtCuit">CUIT:</label>
            <input type="text" required class="form-control" name="txtCuit" id="txtCuit" value="<?php echo $cliente->cuit; ?>" maxlength="11">
        </div>
        <div class="col-6 form-group">
            <label for="txtCorreo">Correo:</label>
            <input type="" class="form-control" name="txtCorreo" id="txtCorreo" value="<?php echo $cliente->correo; ?>">
        </div>
        <div class="col-6 form-group">
            <label for="txtTelefono">Teléfono:</label>
            <input type="number" class="form-control" name="txtTelefono" id="txtTelefono" value="<?php echo $cliente->telefono; ?>">
        </div>
        <div class="col-6 form-group">
            <label for="txtFechaNac" class="d-block">Fecha de nacimiento:</label>
            <select class="form-control d-inline" name="txtDiaNac" id="txtDiaNac" style="width: 80px">
                <option selected="" disabled="">DD</option>
                <?php for ($i = 1; $i <= 31; $i++) : ?>
                    <?php if ($cliente->fecha_nac != "" && $i == date_format(date_create($cliente->fecha_nac), "d")) : ?>
                        <option selected><?php echo $i; ?></option>
                    <?php else : ?>
                        <option><?php echo $i; ?></option>
                    <?php endif; ?>
                <?php endfor; ?>
            </select>
            <select class="form-control d-inline" name="txtMesNac" id="txtMesNac" style="width: 80px">
                <option selected="" disabled="">MM</option>
                <?php for ($i = 1; $i <= 12; $i++) : ?>
                    <?php if ($cliente->fecha_nac != "" && $i == date_format(date_create($cliente->fecha_nac), "m")) : ?>
                        <option selected><?php echo $i; ?></option>
                    <?php else : ?>
                        <option><?php echo $i; ?></option>
                    <?php endif; ?>
                <?php endfor; ?>
            </select>
            <select class="form-control d-inline" name="txtAnioNac" id="txtAnioNac" style="width: 100px">
                <option selected="" disabled="">YYYY</option>
                <?php for ($i = 1900; $i <= date("Y"); $i++) : ?>
                    <?php if ($cliente->fecha_nac != "" && $i == date_format(date_create($cliente->fecha_nac), "Y")) : ?>
                        <option selected><?php echo $i; ?></option>
                    <?php else : ?>
                        <option><?php echo $i; ?></option>
                    <?php endif; ?>
                <?php endfor; ?> 
            </select>
        </div>


        <div class="col-6 form-group">
            <label for="lstProvincia">Provincia:</label>
            <select name="lstProvincia" id="lstProvincia" onchange="" class="form-control">
                <option value="" disabled selected>Seleccionar</option>
                <?php foreach ($aProvincias as $provincia) : ?>
                    <?php if ($provincia->idprovincia) : ?>
                        <option selected value="<?php echo $provincia->idprovincia; ?>"><?php echo $provincia->nombre; ?></option>

                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 form-group">
            <label for="lstLocalidad">Localidad:</label>
            <select name="lstLocalidad" id="lstLocalidad" class="form-control">
                <option value="" disabled selected onchange="obtenerPorProvincia();">Seleccionar</option>
            </select>
        </div>



        <div class="modal fade" id="modalDomicilio" tabindex="-1" role="dialog" aria-labelledby="modalDomicilioLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDomicilioLabel">Domicilio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="lstTipo">Tipo:</label>
                                <select name="lstTipo" id="lstTipo" class="form-control">
                                    <option value="" disabled selected>Seleccionar</option>
                                    <option value="1">Personal</option>
                                    <option value="2">Laboral</option>
                                    <option value="3">Comercial</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="lstProvincia">Provincia:</label>
                                <select name="lstProvincia" id="lstProvincia" onchange="fBuscarLocalidad();" class="form-control">
                                    <option value="" disabled selected>Seleccionar</option>
                                    <?php foreach ($aProvincias as $prov) : ?>
                                        <option value="<?php echo $prov->idprovincia; ?>"><?php echo $prov->nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="lstLocalidad">Localidad:</label>
                                <select name="lstLocalidad" id="lstLocalidad" class="form-control">
                                    <option value="" disabled selected>Seleccionar</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="txtDireccion">Dirección:</label>
                                <input type="text" name="" id="txtDireccion" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="fAgregarDomicilio()">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

</div>
<script>
        window.onload = function() {
          

            $("#lstLocalidad").change(function(){
                idprovincia = $("#lstLocalidad option:selected").val();
                $.ajax({
                    type: "GET",
                    url: "cliente-formulario.php?do=obtenerPorProvincia",
                    data: {
                        id: idlocalidad
                    },
                    async: true,
                    dataType: "json",
                    success: function(respuesta) {
                       var localidad = json;
                       for (i=0; i<localidad.length;i++){
                           $("<option>",{
                               value:localidad[i]["id"],
                               text:localidad[i]["nombre"]
                           }).appendTo("lstLocalidad");
                       }
                       $("#lstLocalidad").prop("selected","-1");
                    }
                });
            });

        };
    </script>
<!-- End of Main Content -->

<?php include_once("footer.php"); ?>