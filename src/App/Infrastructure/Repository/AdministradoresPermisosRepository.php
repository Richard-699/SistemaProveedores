<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Interface\Repository\IAdministradoresPermisosRepository;

use App\Domain\Model\AdministradoresPermisos;
use PDO;

class AdministradoresPermisosRepository implements IAdministradoresPermisosRepository
{

    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }



    /**
     * @return AdministradoresPermisos[]
     */
    public function findByIdAdministrador(string $idAdministrador): array
    {
        $stmt = $this->db->prepare("
            SELECT * 
            FROM proveedores_hwi_administradores_permisos
            WHERE id_administrador_permiso = :id
        ");
        $stmt->bindParam(':id', $idAdministrador);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([AdministradoresPermisos::class, 'fromArray'], $rows);
    }


    public function delete(string $idAdministrador): bool
    {
        $stmtDel = $this->db->prepare("DELETE FROM proveedores_hwi_administradores_permisos WHERE id_administrador_permiso = :id");
        $stmtDel->bindParam(':id', $idAdministrador);
        return $stmtDel->execute();
    }

    public function save(AdministradoresPermisos $permiso): bool
    {
        try {
            $data = $permiso->toArray();
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($key) => ':' . $key, array_keys($data)));

            $stmt = $this->db->prepare(
                "INSERT INTO proveedores_hwi_administradores_permisos ($columns) VALUES ($placeholders)"
            );

            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $data[$key]);
            }

            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
