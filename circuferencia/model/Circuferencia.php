<?php
require_once __DIR__ . '/../config/Database.php';

class Circuferencia
{
    private PDO $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->conectar();
    }

    public function listar(string $buscar = ''): array
    {
        if ($buscar !== '') {
            $sql = "SELECT * FROM circunferencia
                    WHERE detalle LIKE :buscarDetalle
                       OR CAST(radio AS CHAR) LIKE :buscarRadio
                       OR CAST(area AS CHAR) LIKE :buscarArea
                       OR CAST(perimetro AS CHAR) LIKE :buscarPerimetro
                    ORDER BY id_circunferencia DESC";
            $stmt = $this->pdo->prepare($sql);

            $valorBuscar = '%' . $buscar . '%';

            $stmt->execute([
                'buscarDetalle' => $valorBuscar,
                'buscarRadio' => $valorBuscar,
                'buscarArea' => $valorBuscar,
                'buscarPerimetro' => $valorBuscar,
            ]);

            return $stmt->fetchAll();
        }

        $sql = "SELECT * FROM circunferencia ORDER BY id_circunferencia DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT * FROM circunferencia WHERE id_circunferencia = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $registro = $stmt->fetch();

        return $registro ?: null;
    }

    public function guardar(string $detalle, float $radio): bool
    {
        $area = M_PI * $radio ** 2;
        $perimetro = 2 * M_PI * $radio;

        $sql = "INSERT INTO circunferencia(detalle, radio, area, perimetro)
                VALUES(:detalle, :radio, :area, :perimetro)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'detalle' => $detalle,
            'radio' => $radio,
            'area' => round($area, 2),
            'perimetro' => round($perimetro, 2),
        ]);
    }

    public function actualizar(int $id, string $detalle, float $radio): bool
    {
        $area = M_PI * $radio ** 2;
        $perimetro = 2 * M_PI * $radio;

        $sql = "UPDATE circunferencia
                SET detalle = :detalle,
                    radio = :radio,
                    area = :area,
                    perimetro = :perimetro
                WHERE id_circunferencia = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'detalle' => $detalle,
            'radio' => $radio,
            'area' => round($area, 2),
            'perimetro' => round($perimetro, 2),
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM circunferencia WHERE id_circunferencia = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}
// Modelo: calcula area y perimetro a partir del radio
