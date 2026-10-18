<?php
require_once __DIR__ . '/../model/Circuferencia.php';

class CircuferenciaController
{
    private Circuferencia $modelo;

    public function __construct()
    {
        $this->modelo = new Circuferencia();
    }

    public function procesarFormulario(): string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return '';
        }

        $accion = $_POST['accion'] ?? '';

        if ($accion === 'guardar') {
            return $this->guardarOActualizar();
        }

        if ($accion === 'eliminar') {
            return $this->eliminar();
        }

        return '';
    }

    private function guardarOActualizar(): string
    {
        $id = (int) ($_POST['id_circunferencia'] ?? 0);
        $detalle = trim($_POST['txtDetalle'] ?? '');
        $radio = trim($_POST['txtRadio'] ?? '');

        if ($radio === '' || !is_numeric($radio) || (float) $radio <= 0) {
            return 'Ingrese un radio válido mayor a cero';
        }

        if ($id > 0) {
            $this->modelo->actualizar($id, $detalle, (float) $radio);
            header('Location: home.php?mensaje=actualizado');
            exit;
        }

        $this->modelo->guardar($detalle, (float) $radio);
        header('Location: home.php?mensaje=guardado');
        exit;
    }

    private function eliminar(): string
    {
        $id = (int) ($_POST['id_circunferencia'] ?? 0);

        if ($id <= 0) {
            return 'No se pudo eliminar el registro';
        }

        $this->modelo->eliminar($id);
        header('Location: home.php?mensaje=eliminado');
        exit;
    }

    public function listar(string $buscar = ''): array
    {
        return $this->modelo->listar($buscar);
    }

    public function obtenerParaEditar(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        return $this->modelo->obtenerPorId($id);
    }
}
// Controlador: valida el radio y llama al modelo
