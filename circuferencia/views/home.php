<?php
session_start();
require_once __DIR__ . '/../controller/AuthController.php';
require_once __DIR__ . '/../controller/CircuferenciaController.php';
AuthController::verificarSesion();

$circunferenciaController = new CircuferenciaController();
$mensaje = $_GET['mensaje'] ?? '';
$mensajeFormulario = $circunferenciaController->procesarFormulario();

if ($mensajeFormulario !== '') {
    $mensaje = $mensajeFormulario;
}

$buscar = trim($_GET['buscar'] ?? '');
$registroEditar = null;

if (isset($_GET['editar'])) {
    $registroEditar = $circunferenciaController->obtenerParaEditar((int) $_GET['editar']);
}

$registros = $circunferenciaController->listar($buscar);

$idActual = $registroEditar['id_circunferencia'] ?? '';
$radioActual = $registroEditar['radio'] ?? '';
$detalleActual = $registroEditar['detalle'] ?? '';
$areaActual = $registroEditar['area'] ?? '';
$perimetroActual = $registroEditar['perimetro'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
    <meta name="description" content="Calculo del area y perimetro de la circunferencia">
    <meta name="keywords" content="circunferencia, sistema, base de datos">
    <meta name="author" content="BC TECH">

    <link rel="icon" href="../imagenes/icono.png">
    <link rel="stylesheet" href="../assetes/css/style_home.css">

    <title>circunferencia-principal</title>
</head>
<body>
    <header class="headeerHome">
        <div class="lineaSup">
            <div class="logo-links">
                <div class="logo">
                    <img src="../imagenes/logo.png" alt="">
                    <h1>Matemáticas</h1>
                </div>
                <a href="">Quienes somos</a>
            </div>
            <div class="buscarHeader" >
                <form class="form1">
                    <img src="../imagenes/logo.png" alt="imagen de buscar">
                    <button type="submit">Buscar</button>
                    <input type="text"  name="buscar" id="txtBuscar" placeholder="BUSCAR"
                    value="<?php echo htmlspecialchars($buscar); ?>">
                </form>
            </div>
        </div>
        <div class="lineaInf">
            <nav class="menu">
                <li>
                    <a href="#">Inicio</a>
                </li>
                <li>
                    <a href="">Nuestra historia</a>
                </li>
                <li>
                    <a href="logout.php" onclick="return confirm('¿Desea salir?');">Cerrar sesión</a>
                </li>
            </nav>
        </div>
    </header>

    <main>
        <div class="datos">
            <div class="titulo">
                <h1>Calculo del area y perimetro de la circunferencia</h1>
                <p>Usuario: <?php echo htmlspecialchars($_SESSION['usuario']); ?></p>
            </div>

            <?php if ($mensaje !== '') : ?>
                <p class="mensaje">
                    <?php echo htmlspecialchars(ucfirst($mensaje)); ?>
                </p>
            <?php endif; ?>

            <form action="" method="post" class="form2" id="frmFormulario">
                <input type="hidden" name="id_circunferencia" value="<?php echo htmlspecialchars($idActual); ?>">

                <div class="inputs">
                    <label for="txtRadio">Radio</label>
                    <input class="inputHome" type="number" step="any" min="0" name="txtRadio" id="txtRadio" placeholder="Radio"
                     value="<?php echo htmlspecialchars($radioActual); ?>">
                </div>
                <div class="inputs">
                    <label for="txtDetalle">Comentario</label>
                    <input class="inputHome" type="text" placeholder="Opcional: Un comentario"
                    name="txtDetalle" id="txtDetalle" value="<?php echo htmlspecialchars($detalleActual); ?>">
                </div>
                <div class="inputs">
                    <label for="txtA">Area</label>
                    <input class="inputHome" type="text" name="txtA" id="txtA" readonly placeholder="Area"
                     value="<?php echo htmlspecialchars($areaActual); ?>">
                </div>
                <div class="inputs">
                    <label for="txtP">Perímetro</label>
                    <input class="inputHome" type="text" name="txtP" id="txtP" readonly placeholder="Perimetro"
                     value="<?php echo htmlspecialchars($perimetroActual); ?>">
                </div>
                <div class="botones">
                    <button type="button" onclick="calcularCircunferencia();">Calcular</button>
                    <a class="btn-link cancelar" href="home.php">Cancelar</a>
                    <button type="submit" name="accion" value="guardar">
                        <?php echo $idActual !== '' ? 'Actualizar' : 'Guardar'; ?>
                    </button>
                </div>
            </form>
        </div>
        <div class="buscar">
            <form action="home.php" method="get" id="frmBuscar">
                <img src="../imagenes/logo.png" alt="imagen de buscar">
                <button type="submit">Buscar</button>
                <input type="text" name="buscar" id="txtBuscar" placeholder="Buscar por radio"
                 value="<?php echo htmlspecialchars($buscar); ?>">
                <a class="btn-link limpiar" href="home.php">Limpiar</a>
            </form>
        </div>

        <div class="tabla">
            <h1>Historial de los calculos</h1>
            <table>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Radio</th>
                        <th>Detalle</th>
                        <th>Area</th>
                        <th>Perimetro</th>
                        <th>Fecha y hora</th>
                        <th>Botones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($registros) > 0) : ?>
                        <?php foreach ($registros as $fila) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fila['id_circunferencia']); ?></td>
                                <td><?php echo htmlspecialchars($fila['radio']); ?></td>
                                <td><?php echo htmlspecialchars($fila['detalle'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($fila['area']); ?></td>
                                <td><?php echo htmlspecialchars($fila['perimetro']); ?></td>
                                <td><?php echo htmlspecialchars($fila['fecha_registro']); ?></td>
                                <td class="acciones">
                                    <a class="btn-tabla editar" href="home.php?editar=<?php echo (int) $fila['id_circunferencia']; ?>">Editar</a>

                                    <form action="" method="post" onsubmit="return confirm('¿Desea eliminar este registro?');">
                                        <input type="hidden" name="id_circunferencia" value="<?php echo (int) $fila['id_circunferencia']; ?>">
                                        <button class="btn-tabla eliminar" type="submit" name="accion" value="eliminar">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7">No hay registros para mostrar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="../assetes/js/app.js?v=3"></script>
</body>
</html>
<!-- Vista principal: radio, area y perimetro -->
