<?php
session_start();

$proveedor_aprobado = $_SESSION["proveedor_aprobado"];
if (!$proveedor_aprobado) {
    header("Location: ../../index.php");
}

$ruta = '../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
$maneja_formato_costbreakdown = $_SESSION['maneja_formato_costbreakdown'];

if (empty($_SESSION["id_rol_usuarios"])) {
    header("Location:../../index.php");
}
if ($_SESSION["id_rol_usuarios"] != 3) {
    header("Location:../../index.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../Styles/financiera.css">
    <link rel="shortcut icon" href="../../img/LogoBlanco.png">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Calendario/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/11f6140e1f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" src="../../Estilos/Supplier/estilos_index.css">
    <title>HWI</title>

    <style>

    </style>

</head>

<style>
    .video-container {
        position: relative;
    }

    #containerVideo video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
    }

    .content-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 1;
    }

    .card-container {
        display: flex;
        justify-content: space-between;
        width: 80%;
        max-width: 1200px;
        margin-top: 70px;
    }

    .card {
        width: 300px;
        height: 300px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        transition: transform 0.3s ease;
    }

    .card-container a.card {
        text-decoration: none;
        color: black;
    }

    .card:hover {
        transform: scale(1.07);
    }

    .title-container {
        color: white;
        z-index: 2;
    }

    .title-card {
        text-align: center;
        margin-top: 7%;
        font-weight: bold;
    }

    .img-card {
        margin-top: 10%;
        width: 80%;
        height: 60%;
    }

    .title {
        font-weight: bold;
        text-align: center;
    }
</style>

<body id="page-top animated animated--fade-in">

    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php
                include('../Modules/nav.php');
                ?>

                <div class="d-sm-flex align-items-center justify-content-between">
                    <div id="containerVideo" class="video-container">
                        <video src="../../Video/VideoHWI.mp4" muted autoplay loop></video>
                        <div class="overlay"></div>
                        <div class="content-overlay">
                            <div class="title-container">
                                <h2 class="title">Bienvenido <?php echo $_SESSION["nombre_proveedor"]; ?> al Sistema de Proveedores de Haceb Whirlpool Industrial SAS</h2>
                            </div>
                            <div class="card-container">
                                <?php if ($maneja_formato_costbreakdown != NULL) { ?>
                                    <a href="costBreakDown.php" class="card m-3">
                                        <p class="title-card"><?php echo $lang['Diligenciar CostBreakDown']; ?></p>
                                        <img class="img-card" src="../../img/Ilustraciones/cbd.jpg">
                                    </a>
                                <?php } ?>
                                <a href="../Laft/index.php" class="card m-3">
                                    <p class="title-card"><?php echo $lang['laft']; ?></p>
                                    <img class="img-card" src="../../img/Ilustraciones/LAFT.jpg">
                                </a>
                                <a href="documentacion.php" class="card m-3">
                                    <p class="title-card"><?php echo $lang['Documentacion']; ?></p>
                                    <img class="img-card" src="../../img/Ilustraciones/documents.jpg">
                                </a>

                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function expandCard(card) {
        card.style.transform = 'scale(1.05)';
    }

    function shrinkCard(card) {
        card.style.transform = 'scale(1)';
    }

    $(".sidebar ul li").on('click', function() {
        $(".sidebar ul li.active").removeClass('active');
        $(this).addClass('active');
    });

    $('.open-btn').on('click', function() {
        $('.sidebar').addClass('active');
    });

    $('.close-btn').on('click', function() {
        $('.sidebar').removeClass('active');
    });
</script>

</html>