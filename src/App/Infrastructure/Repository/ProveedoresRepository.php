<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Proveedores;
use App\Aplication\Interface\Repository\IProveedoresRepository;
use PDO;

class ProveedoresRepository implements IProveedoresRepository {

    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findByUsuario(string $usuario): ?int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM proveedores_hwi WHERE usuario_proveedor = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        
        return (int) $stmt->fetchColumn();
    }

    public function findByUsuarioForAuth(string $usuario): ?array {
        $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi WHERE usuario_proveedor = :usuario LIMIT 1");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function save(Proveedores $proveedor): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO proveedores_hwi 
            (id_proveedor, numero_acreedor_proveedor, nombre_proveedor, id_tipo_proveedor, id_idioma_proveedor, id_estado_proveedor, maneja_formato_costbreakdown_proveedor, historia_proveedor, descripcion_proveedor, porcentaje_bom_proveedor, logo_proveedor, id_srm_proveedor, id_categoria_proveedor, id_sub_categoria_proveedor, formulario_ambiental_proveedor, permitir_carta_beneficiarios_finales_proveedor, id_administrador_proveedor, usuario_proveedor, password_proveedor, password_is_temporal_proveedor) 
            VALUES (UUID(), :numero_acreedor, :nombre, :id_tipo, :id_idioma, :id_estado, :maneja_formato, :historia, :descripcion, :porcentaje, :logo, :id_srm, :id_categoria, :id_sub_categoria, :formulario, :permitir_carta, :id_administrador, :usuario, :password, :is_temporal)"
        );
        $stmt->bindParam(':numero_acreedor', $proveedor->numero_acreedor_proveedor);
        $stmt->bindParam(':nombre', $proveedor->nombre_proveedor);
        
        $id_tipo = (int) $proveedor->id_tipo_proveedor;
        $stmt->bindParam(':id_tipo', $id_tipo, PDO::PARAM_INT);
        
        $id_idioma = (int) $proveedor->id_idioma_proveedor;
        $stmt->bindParam(':id_idioma', $id_idioma, PDO::PARAM_INT);
        
        $id_estado = (int) $proveedor->id_estado_proveedor;
        $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
        
        $maneja_formato = (int) $proveedor->maneja_formato_costbreakdown_proveedor;
        $stmt->bindParam(':maneja_formato', $maneja_formato, PDO::PARAM_INT);
        
        $stmt->bindParam(':historia', $proveedor->historia_proveedor);
        $stmt->bindParam(':descripcion', $proveedor->descripcion_proveedor);
        
        $porcentaje = (float) $proveedor->porcentaje_bom_proveedor;
        $stmt->bindParam(':porcentaje', $porcentaje);
        
        $stmt->bindParam(':logo', $proveedor->logo_proveedor);
        $stmt->bindParam(':id_srm', $proveedor->id_srm_proveedor);
        
        $id_categoria = (int) $proveedor->id_categoria_proveedor;
        $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
        
        $id_sub_categoria = $proveedor->id_sub_categoria_proveedor !== null ? (int) $proveedor->id_sub_categoria_proveedor : null;
        $stmt->bindParam(':id_sub_categoria', $id_sub_categoria, PDO::PARAM_INT);
        
        $formulario = (int) $proveedor->formulario_ambiental_proveedor;
        $stmt->bindParam(':formulario', $formulario, PDO::PARAM_INT);
        
        $permitir_carta = (int) $proveedor->permitir_carta_beneficiarios_finales_proveedor;
        $stmt->bindParam(':permitir_carta', $permitir_carta, PDO::PARAM_INT);
        
        $stmt->bindParam(':id_administrador', $proveedor->id_administrador_proveedor);
        $stmt->bindParam(':usuario', $proveedor->usuario_proveedor);
        $stmt->bindParam(':password', $proveedor->password_proveedor);
        
        $is_temporal = (int) $proveedor->password_is_temporal_proveedor;
        $stmt->bindParam(':is_temporal', $is_temporal, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updatePassword(Proveedores $proveedor): bool {
        $stmt = $this->db->prepare(
            "UPDATE proveedores_hwi SET password_proveedor = :password WHERE id_proveedor = :id"
        );
        $stmt->bindParam(':password', $proveedor->password_proveedor);
        $stmt->bindParam(':id', $proveedor->id_proveedor);

        return $stmt->execute();
    }
}
