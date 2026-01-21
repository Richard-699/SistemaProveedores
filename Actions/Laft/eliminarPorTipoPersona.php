<?php

session_start();
include "../../ConexionBD/conexion.php";

$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$consultarlaft = mysqli_query($conexion, "SELECT * FROM laft WHERE Id_proveedor_laft = '$Id_proveedor_laft'");
$row = mysqli_fetch_assoc($consultarlaft);
$Id_laft = $row['Id_laft'];

$tipoPersona = $_POST['tipoPersona'];

if ($tipoPersona == "Juridica") {

    $consultarPersonaNatural = mysqli_query($conexion, "SELECT * FROM laft_persona_natural WHERE id_laft_persona_natural = '$Id_laft'");

    if (mysqli_num_rows($consultarPersonaNatural) > 0) {

        $deletePersonaNatural = $conexion->prepare("DELETE FROM laft_persona_natural WHERE id_laft_persona_natural = ?");
        $deletePersonaNatural->bind_param("i", $Id_laft);

        if ($deletePersonaNatural->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $deletePepInfoGeneral = $conexion->prepare("DELETE FROM laft_pep_infogeneral WHERE Id_laft_pep_infogeneral = ?");
        $deletePepInfoGeneral->bind_param("i", $Id_laft);

        if ($deletePepInfoGeneral->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $deletePep = $conexion->prepare("DELETE FROM laft_pep WHERE Id_laft_pep  = ?");
        $deletePep->bind_param("i", $Id_laft);

        if ($deletePep->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $consultarDocumentos = "SELECT documento_laft FROM laft_documentos WHERE Id_laft_documentos = ?";
        $stmt = $conexion->prepare($consultarDocumentos);
        $stmt->bind_param("i", $Id_laft);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $filesToDelete = [];
            while ($row = $result->fetch_assoc()) {
                $filesToDelete[] = $row['documento_laft'];
            }
        } else {
            $filesToDelete = [];
        }

        foreach ($filesToDelete as $filePath) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $deleteDocuments = $conexion->prepare("DELETE FROM laft_documentos WHERE Id_laft_documentos = ?");
        $deleteDocuments->bind_param("i", $Id_laft);

        if ($deleteDocuments->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $declaracion_origen_fondos_informacion = null;
        $autorizacion_proteccion_datos = null;
        $declaracion_etica = null;

        $actualizarLaft = $conexion->prepare("UPDATE laft
                                                SET declaracion_origen_fondos_informacion = ?,
                                                autorizacion_proteccion_datos = ?,
                                                declaracion_etica = ?
                                                WHERE Id_laft = ?");
        $actualizarLaft->bind_param(
            "sssi",
            $declaracion_origen_fondos_informacion,
            $autorizacion_proteccion_datos,
            $declaracion_etica,
            $Id_laft
        );
        if ($actualizarLaft->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    } else {
        $response = ['success' => true];
    }
} else {
    $consultarPersonaJuridica = mysqli_query($conexion, "SELECT * FROM laft_persona_juridica WHERE Id_laft_persona_juridica = '$Id_laft'");
    
    if(mysqli_num_rows($consultarPersonaJuridica)>0){

        $fila = mysqli_fetch_assoc($consultarPersonaJuridica);
        $Id_persona_juridica = $fila['Id_persona_juridica'];

        $deleteCertificaciones = $conexion->prepare("DELETE FROM laft_certificaciones WHERE Id_laft_persona_juridica_certificacion = ?");
        $deleteCertificaciones->bind_param("s", $Id_persona_juridica);

        if ($deleteCertificaciones->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $consultarDocumentos = "SELECT documento_laft FROM laft_documentos WHERE Id_laft_documentos = ?";
        $stmt = $conexion->prepare($consultarDocumentos);
        $stmt->bind_param("i", $Id_laft);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $filesToDelete = [];
            while ($row = $result->fetch_assoc()) {
                $filesToDelete[] = $row['documento_laft'];
            }
        } else {
            $filesToDelete = [];
        }

        foreach ($filesToDelete as $filePath) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $deleteDocuments = $conexion->prepare("DELETE FROM laft_documentos WHERE Id_laft_documentos = ?");
        $deleteDocuments->bind_param("i", $Id_laft);

        if ($deleteDocuments->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $deletePersonaJuridica = $conexion->prepare("DELETE FROM laft_persona_juridica WHERE Id_laft_persona_juridica = ?");
        $deletePersonaJuridica->bind_param("i", $Id_laft);

        if ($deletePersonaJuridica->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $deleteRepresentanteLegal = $conexion->prepare("DELETE FROM laft_representante_legal WHERE Id_laft_representante_legal = ?");
        $deleteRepresentanteLegal->bind_param("i", $Id_laft);

        if ($deleteRepresentanteLegal->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $deleteBeneficiariosFinales = $conexion->prepare("DELETE FROM laft_beneficiarios_finales WHERE Id_laft_beneficiarios_finales = ?");
        $deleteBeneficiariosFinales->bind_param("i", $Id_laft);

        if ($deleteBeneficiariosFinales->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $deleteContacto = $conexion->prepare("DELETE FROM laft_contacto WHERE Id_laft_contacto = ?");
        $deleteContacto->bind_param("i", $Id_laft);

        if ($deleteContacto->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $deletePepInfoGeneral = $conexion->prepare("DELETE FROM laft_pep_infogeneral WHERE Id_laft_pep_infogeneral = ?");
        $deletePepInfoGeneral->bind_param("i", $Id_laft);

        if ($deletePepInfoGeneral->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $deletePep = $conexion->prepare("DELETE FROM laft_pep WHERE Id_laft_pep  = ?");
        $deletePep->bind_param("i", $Id_laft);

        if ($deletePep->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $oficial_cumplimiento = null;

        $actualizarLaft = $conexion->prepare("UPDATE laft
                                                SET oficial_cumplimiento = ?
                                                WHERE Id_laft = ?");
        $actualizarLaft->bind_param(
            "si",
            $oficial_cumplimiento,
            $Id_laft
        );
        if ($actualizarLaft->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    }else{
        $response = ['success' => true];
    }
}

echo json_encode($response);
