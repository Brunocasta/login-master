<?php
include_once "config.php";
include_once "entidades/tipoproducto.php";
include_once "entidades/producto.php";

$pg = "Edicion de productos";

$tipoProducto = new TipoProducto();
$aTipoProductos = $tipoProducto->obtenerTodos();

$producto = new Producto();
$producto->cargarFormulario($_REQUEST);



if ($_POST) {
    if (isset($_POST["btnGuardar"])) {
        $nombreImagen = "";
        if ($_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
            $nombreRandom = date("Ymdhmsi");
            $archivoTmp = $_FILES["imagen"]["tmp_name"];
            $nombreArchivo = $_FILES["imagen"]["name"];
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            $nombreImagen = "$nombreRandom.$extension";
            move_uploaded_file($archivoTmp, "files/$nombreImagen");
        }

        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            $productoAnt = new Producto();
            $productoAnt->idproducto = $_GET["id"];
            $productoAnt->obtenerPorId();
            $imagenAnterior = $productoAnt->imagen;

            //Si es una actualizacion y se sube una imagen, elimina la anterior
            if ($_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
                if (!$imagenAnterior != "") {
                    unlink($imagenAnterior);
                }
            } else {
                //Si no viene ninguna imagen, setea como imagen la que habia previamente
                $nombreImagen = $imagenAnterior;
            }

            $producto->imagen = $nombreImagen;
            //Actualizo un cliente existente
            $producto->actualizar();
            header("Location: producto-formulario.php");
        } else {
            //Es nuevo
            $producto->imagen = $nombreImagen;
            $producto->insertar();
        }
    } else if (isset($_POST["btnBorrar"])) {
        $producto->eliminar();
        header("Location: productos.php");
    }
}

if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $producto->obtenerPorId();
}


include_once("header.php");
?>





<body>

    <div class="container-fluid">
        <h1 class="h3 text-gray-800 ms-3">Productos</h1>
        <div class="row">

            <div class="col-12 mt-3">

                <a href="productos.php" class="btn btn-primary mr-2">Listado</a>
                <a href="producto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
                <button type="submit" class="btn btn-danger mr-2" id="btnBorrar" name="btnBorrar">Borrar</button>

            </div>
        </div>

        <div class="row">
            <div class="col-6 mt-3 form-group">
                <label for="txtNombre">Nombre:</label>
                <input type="text" required class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $producto->nombre; ?>">
            </div>
            <div class="col-6 mt-3 form-group">

                <label for="txtNombre">Tipo de producto:</label>

                <select name="lstTipoProducto" id="lstTipoProducto" class="form-control selectpicker" data-live-search="true">
                    <option value="" disabled selected>Seleccionar</option>
                    <?php foreach ($aTipoProductos as $tipoProducto) : ?>
                        <?php if ($tipoProducto->idtipoproducto == $producto->fk_idtipoproducto) : ?>
                            <option selected value="<?php echo $tipoProducto->idtipoproducto; ?>"><?php echo $tipoProducto->nombre; ?></option>
                        <?php else : ?>
                            <option value="<?php echo $tipoProducto->idtipoproducto; ?>"><?php echo $tipoProducto->nombre; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>


            </div>
            <div class="col-6 form-group">
                <label for="txtCantidad">Cantidad:</label>
                <input type="number" required class="form-control" name="txtCantidad" id="txtCantidad" value="<?php echo $producto->cantidad; ?>">
            </div>
            <div class="col-6 form-group">
                <label for="txtPrecio">Precio:</label>
                <input type="number" required class="form-control" name="txtPrecio" id="txtPrecio" value="<?php echo $producto->precio; ?>">
            </div>


            <div class="col-12 form-group">
                <label for="txtCorreo">Descripción:</label>
                <textarea type="text" name="txtDescripcion" id="txtDescripcion" style="display: none;" value="<?php echo $producto->descripcion; ?>"></textarea>

                <div class="ck ck-reset ck-editor ck-rounded-corners" role="application" dir="ltr" lang="es" aria-labelledby="ck-editor__label_e1eb0f2bbde3a896368da5eec062d155a">
                    <label class="ck ck-label ck-voice-label" id="ck-editor__label_e1eb0f2bbde3a896368da5eec062d155a">Rich Text Editor</label>
                </div>
                <div class="ck ck-editor__top ck-reset_all" role="presentation">
                    <div class="ck ck-sticky-panel">
                        <div class="ck ck-sticky-panel__placeholder" style="display: none;"></div>
                        <div class="ck ck-sticky-panel__content">
                            <div class="ck ck-toolbar ck-toolbar_grouping" role="toolbar" aria-label="Editor toolbar">
                                <div class="ck ck-toolbar__items">
                                    <div class="ck ck-dropdown ck-heading-dropdown"><button class="ck ck-button ck-off ck-button_with-text ck-dropdown__button" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_ef9429f076d30d4b6514fde19fcc62fd1" aria-haspopup="true"><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Heading</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_ef9429f076d30d4b6514fde19fcc62fd1">Paragraph</span><svg class="ck ck-icon ck-dropdown__arrow" viewBox="0 0 10 10">
                                                <path d="M.941 4.523a.75.75 0 1 1 1.06-1.06l3.006 3.005 3.005-3.005a.75.75 0 1 1 1.06 1.06l-3.549 3.55a.75.75 0 0 1-1.168-.136L.941 4.523z"></path>
                                            </svg></button>
                                        <div class="ck ck-reset ck-dropdown__panel ck-dropdown__panel_se">
                                            <ul class="ck ck-reset ck-list">
                                                <li class="ck ck-list__item"><button class="ck ck-button ck-heading_paragraph ck-on ck-button_with-text" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_ea3ff95708696bf14e132aa14386f169c"><span class="ck ck-tooltip ck-tooltip_s ck-hidden"><span class="ck ck-tooltip__text"></span></span><span class="ck ck-button__label" id="ck-editor__aria-label_ea3ff95708696bf14e132aa14386f169c">Paragraph</span></button></li>
                                                <li class="ck ck-list__item"><button class="ck ck-button ck-heading_heading1 ck-off ck-button_with-text" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e047b4feeda2d4c5fe912bfc598d9a656"><span class="ck ck-tooltip ck-tooltip_s ck-hidden"><span class="ck ck-tooltip__text"></span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e047b4feeda2d4c5fe912bfc598d9a656">Heading 1</span></button></li>
                                                <li class="ck ck-list__item"><button class="ck ck-button ck-heading_heading2 ck-off ck-button_with-text" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e0d72fc1f150698e84382e0d60e6f5cb9"><span class="ck ck-tooltip ck-tooltip_s ck-hidden"><span class="ck ck-tooltip__text"></span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e0d72fc1f150698e84382e0d60e6f5cb9">Heading 2</span></button></li>
                                                <li class="ck ck-list__item"><button class="ck ck-button ck-heading_heading3 ck-off ck-button_with-text" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_ea831531c935660ebbca211a9cb8ed627"><span class="ck ck-tooltip ck-tooltip_s ck-hidden"><span class="ck ck-tooltip__text"></span></span><span class="ck ck-button__label" id="ck-editor__aria-label_ea831531c935660ebbca211a9cb8ed627">Heading 3</span></button></li>
                                            </ul>
                                        </div>
                                    </div><span class="ck ck-toolbar__separator"></span><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_efd706df4e4bb66eee4dd44e8bc1356d8" aria-pressed="false"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                            <path d="M10.187 17H5.773c-.637 0-1.092-.138-1.364-.415-.273-.277-.409-.718-.409-1.323V4.738c0-.617.14-1.062.419-1.332.279-.27.73-.406 1.354-.406h4.68c.69 0 1.288.041 1.793.124.506.083.96.242 1.36.478.341.197.644.447.906.75a3.262 3.262 0 0 1 .808 2.162c0 1.401-.722 2.426-2.167 3.075C15.05 10.175 16 11.315 16 13.01a3.756 3.756 0 0 1-2.296 3.504 6.1 6.1 0 0 1-1.517.377c-.571.073-1.238.11-2 .11zm-.217-6.217H7v4.087h3.069c1.977 0 2.965-.69 2.965-2.072 0-.707-.256-1.22-.768-1.537-.512-.319-1.277-.478-2.296-.478zM7 5.13v3.619h2.606c.729 0 1.292-.067 1.69-.2a1.6 1.6 0 0 0 .91-.765c.165-.267.247-.566.247-.897 0-.707-.26-1.176-.778-1.409-.519-.232-1.31-.348-2.375-.348H7z"></path>
                                        </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Bold (CTRL+B)</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_efd706df4e4bb66eee4dd44e8bc1356d8">Bold</span></button><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_eba0a4ae62f9fcd511e519838bfb19cb6" aria-pressed="false"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                            <path d="M9.586 14.633l.021.004c-.036.335.095.655.393.962.082.083.173.15.274.201h1.474a.6.6 0 1 1 0 1.2H5.304a.6.6 0 0 1 0-1.2h1.15c.474-.07.809-.182 1.005-.334.157-.122.291-.32.404-.597l2.416-9.55a1.053 1.053 0 0 0-.281-.823 1.12 1.12 0 0 0-.442-.296H8.15a.6.6 0 0 1 0-1.2h6.443a.6.6 0 1 1 0 1.2h-1.195c-.376.056-.65.155-.823.296-.215.175-.423.439-.623.79l-2.366 9.347z"></path>
                                        </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Italic (CTRL+I)</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_eba0a4ae62f9fcd511e519838bfb19cb6">Italic</span></button><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_eff0dbf33b6b6f0450e4340f15bd26b99" aria-pressed="false"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                            <path d="M11.077 15l.991-1.416a.75.75 0 1 1 1.229.86l-1.148 1.64a.748.748 0 0 1-.217.206 5.251 5.251 0 0 1-8.503-5.955.741.741 0 0 1 .12-.274l1.147-1.639a.75.75 0 1 1 1.228.86L4.933 10.7l.006.003a3.75 3.75 0 0 0 6.132 4.294l.006.004zm5.494-5.335a.748.748 0 0 1-.12.274l-1.147 1.639a.75.75 0 1 1-1.228-.86l.86-1.23a3.75 3.75 0 0 0-6.144-4.301l-.86 1.229a.75.75 0 0 1-1.229-.86l1.148-1.64a.748.748 0 0 1 .217-.206 5.251 5.251 0 0 1 8.503 5.955zm-4.563-2.532a.75.75 0 0 1 .184 1.045l-3.155 4.505a.75.75 0 1 1-1.229-.86l3.155-4.506a.75.75 0 0 1 1.045-.184z"></path>
                                        </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Link (Ctrl+K)</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_eff0dbf33b6b6f0450e4340f15bd26b99">Link</span></button><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e8362b921883884ea88427a8f2b6e6770" aria-pressed="false"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                            <path d="M7 5.75c0 .414.336.75.75.75h9.5a.75.75 0 1 0 0-1.5h-9.5a.75.75 0 0 0-.75.75zm-6 0C1 4.784 1.777 4 2.75 4c.966 0 1.75.777 1.75 1.75 0 .966-.777 1.75-1.75 1.75C1.784 7.5 1 6.723 1 5.75zm6 9c0 .414.336.75.75.75h9.5a.75.75 0 1 0 0-1.5h-9.5a.75.75 0 0 0-.75.75zm-6 0c0-.966.777-1.75 1.75-1.75.966 0 1.75.777 1.75 1.75 0 .966-.777 1.75-1.75 1.75-.966 0-1.75-.777-1.75-1.75z"></path>
                                        </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Bulleted List</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e8362b921883884ea88427a8f2b6e6770">Bulleted List</span></button><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e990132eb63f59b6f5c39a72a60986975" aria-pressed="false"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                            <path d="M7 5.75c0 .414.336.75.75.75h9.5a.75.75 0 1 0 0-1.5h-9.5a.75.75 0 0 0-.75.75zM3.5 3v5H2V3.7H1v-1h2.5V3zM.343 17.857l2.59-3.257H2.92a.6.6 0 1 0-1.04 0H.302a2 2 0 1 1 3.995 0h-.001c-.048.405-.16.734-.333.988-.175.254-.59.692-1.244 1.312H4.3v1h-4l.043-.043zM7 14.75a.75.75 0 0 1 .75-.75h9.5a.75.75 0 1 1 0 1.5h-9.5a.75.75 0 0 1-.75-.75z"></path>
                                        </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Numbered List</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e990132eb63f59b6f5c39a72a60986975">Numbered List</span></button><span class="ck ck-toolbar__separator"></span><button class="ck ck-button ck-disabled ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_eb474e6ee53d02d7d5eb58e3c442ff5e3" aria-disabled="true"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                            <path d="M2 3.75c0 .414.336.75.75.75h14.5a.75.75 0 1 0 0-1.5H2.75a.75.75 0 0 0-.75.75zm5 6c0 .414.336.75.75.75h9.5a.75.75 0 1 0 0-1.5h-9.5a.75.75 0 0 0-.75.75zM2.75 16.5h14.5a.75.75 0 1 0 0-1.5H2.75a.75.75 0 1 0 0 1.5zM1.632 6.95L5.02 9.358a.4.4 0 0 1-.013.661l-3.39 2.207A.4.4 0 0 1 1 11.892V7.275a.4.4 0 0 1 .632-.326z"></path>
                                        </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Increase indent</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_eb474e6ee53d02d7d5eb58e3c442ff5e3">Increase indent</span></button><button class="ck ck-button ck-disabled ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_eeac715a91285c9c2cbf02d0ed30d423b" aria-disabled="true"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                            <path d="M2 3.75c0 .414.336.75.75.75h14.5a.75.75 0 1 0 0-1.5H2.75a.75.75 0 0 0-.75.75zm5 6c0 .414.336.75.75.75h9.5a.75.75 0 1 0 0-1.5h-9.5a.75.75 0 0 0-.75.75zM2.75 16.5h14.5a.75.75 0 1 0 0-1.5H2.75a.75.75 0 1 0 0 1.5zm1.618-9.55L.98 9.358a.4.4 0 0 0 .013.661l3.39 2.207A.4.4 0 0 0 5 11.892V7.275a.4.4 0 0 0-.632-.326z"></path>
                                        </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Decrease indent</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_eeac715a91285c9c2cbf02d0ed30d423b">Decrease indent</span></button><span class="ck ck-toolbar__separator"></span><span class="ck-file-dialog-button"><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e0519b07ae7ddc0d168d2159f6c4fdff8"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                <path d="M6.91 10.54c.26-.23.64-.21.88.03l3.36 3.14 2.23-2.06a.64.64 0 0 1 .87 0l2.52 2.97V4.5H3.2v10.12l3.71-4.08zm10.27-7.51c.6 0 1.09.47 1.09 1.05v11.84c0 .59-.49 1.06-1.09 1.06H2.79c-.6 0-1.09-.47-1.09-1.06V4.08c0-.58.49-1.05 1.1-1.05h14.38zm-5.22 5.56a1.96 1.96 0 1 1 3.4-1.96 1.96 1.96 0 0 1-3.4 1.96z"></path>
                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Insert image</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e0519b07ae7ddc0d168d2159f6c4fdff8">Insert image</span></button><input class="ck-hidden" type="file" tabindex="-1" accept="image/jpeg,image/png,image/gif,image/bmp,image/webp,image/tiff" multiple="true"></span><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e0e5f8b66080cfc5cde9128d5a28aded8" aria-pressed="false"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                            <path d="M3 10.423a6.5 6.5 0 0 1 6.056-6.408l.038.67C6.448 5.423 5.354 7.663 5.22 10H9c.552 0 .5.432.5.986v4.511c0 .554-.448.503-1 .503h-5c-.552 0-.5-.449-.5-1.003v-4.574zm8 0a6.5 6.5 0 0 1 6.056-6.408l.038.67c-2.646.739-3.74 2.979-3.873 5.315H17c.552 0 .5.432.5.986v4.511c0 .554-.448.503-1 .503h-5c-.552 0-.5-.449-.5-1.003v-4.574z"></path>
                                        </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Block quote</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e0e5f8b66080cfc5cde9128d5a28aded8">Block quote</span></button>
                                    <div class="ck ck-dropdown"><button class="ck ck-button ck-off ck-dropdown__button" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e19789a180b9f7f7277302751c2e93fc2" aria-haspopup="true"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                <path d="M3 6v3h4V6H3zm0 4v3h4v-3H3zm0 4v3h4v-3H3zm5 3h4v-3H8v3zm5 0h4v-3h-4v3zm4-4v-3h-4v3h4zm0-4V6h-4v3h4zm1.5 8a1.5 1.5 0 0 1-1.5 1.5H3A1.5 1.5 0 0 1 1.5 17V4c.222-.863 1.068-1.5 2-1.5h13c.932 0 1.778.637 2 1.5v13zM12 13v-3H8v3h4zm0-4V6H8v3h4z"></path>
                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Insert table</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e19789a180b9f7f7277302751c2e93fc2">Insert table</span><svg class="ck ck-icon ck-dropdown__arrow" viewBox="0 0 10 10">
                                                <path d="M.941 4.523a.75.75 0 1 1 1.06-1.06l3.006 3.005 3.005-3.005a.75.75 0 1 1 1.06 1.06l-3.549 3.55a.75.75 0 0 1-1.168-.136L.941 4.523z"></path>
                                            </svg></button>
                                        <div class="ck ck-reset ck-dropdown__panel ck-dropdown__panel_se"></div>
                                    </div>
                                    <div class="ck ck-dropdown"><button class="ck ck-button ck-off ck-dropdown__button" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e512bd63ed9a381c3b8bb7821ff9b3b1d" aria-haspopup="true"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                <path d="M18.68 3.03c.6 0 .59-.03.59.55v12.84c0 .59.01.56-.59.56H1.29c-.6 0-.59.03-.59-.56V3.58c0-.58-.01-.55.6-.55h17.38zM15.77 15V5H4.2v10h11.57zM2 4v1h1V4H2zm0 2v1h1V6H2zm0 2v1h1V8H2zm0 2v1h1v-1H2zm0 2v1h1v-1H2zm0 2v1h1v-1H2zM17 4v1h1V4h-1zm0 2v1h1V6h-1zm0 2v1h1V8h-1zm0 2v1h1v-1h-1zm0 2v1h1v-1h-1zm0 2v1h1v-1h-1zM7.5 7.177a.4.4 0 01.593-.351l5.133 2.824a.4.4 0 010 .7l-5.133 2.824a.4.4 0 01-.593-.35V7.176v.001z"></path>
                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Insert media</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e512bd63ed9a381c3b8bb7821ff9b3b1d">Insert media</span><svg class="ck ck-icon ck-dropdown__arrow" viewBox="0 0 10 10">
                                                <path d="M.941 4.523a.75.75 0 1 1 1.06-1.06l3.006 3.005 3.005-3.005a.75.75 0 1 1 1.06 1.06l-3.549 3.55a.75.75 0 0 1-1.168-.136L.941 4.523z"></path>
                                            </svg></button>
                                        <div class="ck ck-reset ck-dropdown__panel ck-dropdown__panel_se">
                                            <form class="ck ck-media-form" tabindex="-1">
                                                <div class="ck ck-labeled-input"><label class="ck ck-label" id="ck-editor__label_ebfac31149454d0e09dec30ba6f2a1c6d" for="ck-input-eec1f61438bdc7e8517dcd2cd8f9c000f">Media URL</label><input type="text" class="ck ck-input ck-input-text" id="ck-input-eec1f61438bdc7e8517dcd2cd8f9c000f" placeholder="https://example.com" aria-describedby="ck-status-ecf0a4ba568a20b9e6e831c8c83649f53">
                                                    <div class="ck ck-labeled-input__status" id="ck-status-ecf0a4ba568a20b9e6e831c8c83649f53">Paste the media URL in the input.</div>
                                                </div><button class="ck ck-button ck-off ck-button-save" type="submit" tabindex="-1" aria-labelledby="ck-editor__aria-label_ee672764cf31c9b2e6ca4b1545f80a755"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                        <path d="M6.972 16.615a.997.997 0 0 1-.744-.292l-4.596-4.596a1 1 0 1 1 1.414-1.414l3.926 3.926 9.937-9.937a1 1 0 0 1 1.414 1.415L7.717 16.323a.997.997 0 0 1-.745.292z"></path>
                                                    </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Save</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_ee672764cf31c9b2e6ca4b1545f80a755">Save</span></button><button class="ck ck-button ck-off ck-button-cancel" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e5e739efcad15517eed0825f4fe4d0e75"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                        <path d="M11.591 10.177l4.243 4.242a1 1 0 0 1-1.415 1.415l-4.242-4.243-4.243 4.243a1 1 0 0 1-1.414-1.415l4.243-4.242L4.52 5.934A1 1 0 0 1 5.934 4.52l4.243 4.243 4.242-4.243a1 1 0 1 1 1.415 1.414l-4.243 4.243z"></path>
                                                    </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Cancel</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e5e739efcad15517eed0825f4fe4d0e75">Cancel</span></button>
                                            </form>
                                        </div>
                                    </div><button class="ck ck-button ck-disabled ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e43186bb4a881b52a068dc31dbc964b69" aria-disabled="true"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                            <path d="M5.042 9.367l2.189 1.837a.75.75 0 0 1-.965 1.149l-3.788-3.18a.747.747 0 0 1-.21-.284.75.75 0 0 1 .17-.945L6.23 4.762a.75.75 0 1 1 .964 1.15L4.863 7.866h8.917A.75.75 0 0 1 14 7.9a4 4 0 1 1-1.477 7.718l.344-1.489a2.5 2.5 0 1 0 1.094-4.73l.008-.032H5.042z"></path>
                                        </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Undo (CTRL+Z)</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e43186bb4a881b52a068dc31dbc964b69">Undo</span></button><button class="ck ck-button ck-disabled ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e5b5d0b0c64fdb3a1c587674f15c859e4" aria-disabled="true"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                            <path d="M14.958 9.367l-2.189 1.837a.75.75 0 0 0 .965 1.149l3.788-3.18a.747.747 0 0 0 .21-.284.75.75 0 0 0-.17-.945L13.77 4.762a.75.75 0 1 0-.964 1.15l2.331 1.955H6.22A.75.75 0 0 0 6 7.9a4 4 0 1 0 1.477 7.718l-.344-1.489A2.5 2.5 0 1 1 6.039 9.4l-.008-.032h8.927z"></path>
                                        </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Redo (CTRL+Y)</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e5b5d0b0c64fdb3a1c587674f15c859e4">Redo</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ck ck-editor__main" role="presentation">
                    <div class="ck-blurred ck ck-content ck-editor__editable ck-rounded-corners ck-editor__editable_inline" lang="en" dir="ltr" role="textbox" aria-label="Rich Text Editor, main" contenteditable="true">
                        <p><br data-cke-filler="true"></p>
                    </div>
                </div>
            </div>

            <div class="col-6 form-group">
                <label for="fileImagen">Imagen:</label>
                <input type="file" class="form-control-file" name="imagen" id="imagen">
                <img src="files/<?php echo $producto->imagen; ?>" class="img-thumbnail">
            </div>


        </div>
    </div>
    <script>
        $(document).ready(function() {
            var idCliente = '<?php echo isset($cliente) && $cliente->idcliente > 0 ? $cliente->idcliente : 0 ?>';

            var dataTable = $('#grilla').DataTable({
                "processing": true,
                "serverSide": false,
                "bFilter": false,
                "bInfo": true,
                "bSearchable": false,
                "paging": false,
                "pageLength": 25,
                "order": [
                    [0, "asc"]
                ],
                "ajax": "cliente-formulario.php?do=cargarGrilla&idCliente=" + idCliente
            });
        });

        function fBuscarLocalidad() {
            idProvincia = $("#lstProvincia option:selected").val();
            $.ajax({
                type: "GET",
                url: "cliente-formulario.php?do=buscarLocalidad",
                data: {
                    id: idprovincia
                },
                async: true,
                dataType: "json",
                success: function(respuesta) {
                    let opciones = "<option value='0' disabled selected>Seleccionar</option>";
                    const resultado = respuesta.reduce(function(acumulador, valor) {
                        const {
                            nombre,
                            idlocalidad
                        } = valor;
                        return acumulador + `<option value="${idlocalidad}">${nombre}</option>`;
                    }, opciones);
                    $("#lstLocalidad").empty().append(resultado);
                }
            });
        }

        function fAgregarDomicilio() {
            var grilla = $('#grilla').DataTable();
            grilla.row.add([
                $("#lstTipo option:selected").text() + "<input type='hidden' name='txtTipo[]' value='" + $("#lstTipo option:selected").val() + "'>",
                $("#lstProvincia option:selected").text() + "<input type='hidden' name='txtProvincia[]' value='" + $("#lstProvincia option:selected").val() + "'>",
                $("#lstLocalidad option:selected").text() + "<input type='hidden' name='txtLocalidad[]' value='" + $("#lstLocalidad option:selected").val() + "'>",
                $("#txtDireccion").val() + "<input type='hidden' name='txtDomicilio[]' value='" + $("#txtDireccion").val() + "'>"
            ]).draw();
            $('#modalDomicilio').modal('toggle');
            limpiarFormulario();
        }

        function limpiarFormulario() {
            $("#lstTipo").val("");
            $("#lstProvincia").val("");
            $("#lstLocalidad").val("");
            $("#txtDireccion").val("");
        }
    </script>

    <?php include_once("footer.php"); ?>