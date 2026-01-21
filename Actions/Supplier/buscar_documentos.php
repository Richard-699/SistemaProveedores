<?php
session_start();
$numero_acreedor = $_SESSION['numero_acreedor'];
require_once '../../upload-php/api-google/vendor/autoload.php';

putenv('GOOGLE_APPLICATION_CREDENTIALS=../../Json/prueba-drive-428316-1e3ed7a2d174.json');

$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes(['https://www.googleapis.com/auth/drive.readonly']);

$service = new Google_Service_Drive($client);

$baseFolderId = '1RYGIdvzscpqcr5GHAUTkvid7-mV73gii';
$ano_documento = $_POST['ano_documento'];
$tipo_documento = $_POST['tipo_documento'];
$vigencia = $_POST['vigencia'];

$queryFolder = "'$baseFolderId' in parents and name='$numero_acreedor' and mimeType='application/vnd.google-apps.folder' and trashed=false";
$paramsFolder = [
    'q' => $queryFolder,
    'fields' => 'files(id, name)',
];

try {
    $resultsFolder = $service->files->listFiles($paramsFolder);
    if (count($resultsFolder->getFiles()) == 0) {
        echo json_encode(['success' => false, 'message' => 'No se encontró la carpeta del acreedor.']);
    } else {
        $acreedorFolderId = $resultsFolder->getFiles()[0]->getId();

        $queryYearFolder = "'$acreedorFolderId' in parents and name='$ano_documento' and mimeType='application/vnd.google-apps.folder' and trashed=false";
        $paramsYearFolder = [
            'q' => $queryYearFolder,
            'fields' => 'files(id, name)',
        ];

        $resultsYearFolder = $service->files->listFiles($paramsYearFolder);

        if (count($resultsYearFolder->getFiles()) == 0) {
            echo json_encode(['success' => false, 'message' => 'No se encontró la subcarpeta del año.']);
        } else {
            $yearFolderId = $resultsYearFolder->getFiles()[0]->getId();

            $queryDocTypeFolder = "'$yearFolderId' in parents and name='$tipo_documento' and mimeType='application/vnd.google-apps.folder' and trashed=false";
            $paramsDocTypeFolder = [
                'q' => $queryDocTypeFolder,
                'fields' => 'files(id, name)',
            ];

            $resultsDocTypeFolder = $service->files->listFiles($paramsDocTypeFolder);

            if (count($resultsDocTypeFolder->getFiles()) == 0) {
                echo json_encode(['success' => false, 'message' => 'No se encontró la subcarpeta del tipo de documento.']);
                exit;
            } else {
                $docTypeFolderId = $resultsDocTypeFolder->getFiles()[0]->getId();
            }

            if ($tipo_documento !== 'Rete. Fuente') {
                $queryVigenciaFolder = "'$docTypeFolderId' in parents and name='$vigencia' and mimeType='application/vnd.google-apps.folder' and trashed=false";
                $paramsVigenciaFolder = [
                    'q' => $queryVigenciaFolder,
                    'fields' => 'files(id, name)',
                ];

                $resultsVigenciaFolder = $service->files->listFiles($paramsVigenciaFolder);

                if (count($resultsVigenciaFolder->getFiles()) == 0) {
                    echo json_encode(['success' => false, 'message' => 'No se encontró la subcarpeta de la vigencia.']);
                } else {
                    $vigenciaFolderId = $resultsVigenciaFolder->getFiles()[0]->getId();

                    $queryFiles = "'$vigenciaFolderId' in parents and trashed=false";
                    $paramsFiles = [
                        'q' => $queryFiles,
                        'fields' => 'files(id, name)',
                    ];

                    $resultsFiles = $service->files->listFiles($paramsFiles);

                    if (count($resultsFiles->getFiles()) == 0) {
                        echo json_encode(['success' => false, 'message' => 'No se encontraron archivos en la subcarpeta de la vigencia.']);
                    } else {
                        $filesData = [];
                        foreach ($resultsFiles->getFiles() as $file) {
                            $fileId = $file->getId();
                            $fileName = $file->getName();
                            $filePreviewUrl = "https://drive.google.com/file/d/$fileId/preview";
                            $fileDownloadUrl = "https://drive.google.com/uc?export=download&id=$fileId";
                            $filesData[] = [
                                'name' => $fileName,
                                'previewUrl' => $filePreviewUrl,
                                'downloadUrl' => $fileDownloadUrl,
                            ];
                        }
                        echo json_encode(['success' => true, 'files' => $filesData]);
                    }
                }
            } else {
                $queryFiles = "'$docTypeFolderId' in parents and trashed=false";
                $paramsFiles = [
                    'q' => $queryFiles,
                    'fields' => 'files(id, name)',
                ];

                $resultsFiles = $service->files->listFiles($paramsFiles);

                if (count($resultsFiles->getFiles()) == 0) {
                    echo json_encode(['success' => false, 'message' => 'No se encontraron archivos en la carpeta del tipo de documento.']);
                } else {
                    $filesData = [];
                    foreach ($resultsFiles->getFiles() as $file) {
                        $fileId = $file->getId();
                        $fileName = $file->getName();
                        $filePreviewUrl = "https://drive.google.com/file/d/$fileId/preview";
                        $fileDownloadUrl = "https://drive.google.com/uc?export=download&id=$fileId";
                        $filesData[] = [
                            'name' => $fileName,
                            'previewUrl' => $filePreviewUrl,
                            'downloadUrl' => $fileDownloadUrl,
                        ];
                    }
                    echo json_encode(['success' => true, 'files' => $filesData]);
                }
            }
        }
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Ha ocurrido un error: ' . $e->getMessage()]);
}
?>
