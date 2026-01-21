<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="shortcut icon" href="../../img/LogoBlanco.png">
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../../Estilos/css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/11f6140e1f.js" crossorigin="anonymous"></script>
</head>

<body id="page-top animated animated--fade-in">
    <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">

    <div class="container-img">
        <img class="img-fluid" src="../../img/LogoBlanco.png" alt="Logo">
    </div>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

            
            <!-- Nav Item - Infomación de Usuario -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <?php echo $_SESSION["nombre_proveedor"]; ?>
                </a>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <li class="nav-item">
                <a class="nav-link" href="../../Actions/Generals/cerrarsesion.php">
                    <span>Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </nav>
</body>

</html>