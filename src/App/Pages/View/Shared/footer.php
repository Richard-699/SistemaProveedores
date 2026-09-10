    </div>
<?php if (!isset($baseUrl)) { $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/SistemaProveedores"; } ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="<?php echo $baseUrl; ?>/public/js/Auth/layout.js"></script>
    <script src="<?php echo $baseUrl; ?>/public/js/utils/spinner.js"></script>
    <script src="<?php echo $baseUrl; ?>/public/js/utils/bootstrap-notify.min.js"></script>
    <script src="<?php echo $baseUrl; ?>/public/js/utils/notify-animations.js"></script>
    <script src="<?php echo $baseUrl; ?>/public/js/utils/notify.js"></script>
    <script src="<?php echo $baseUrl; ?>/public/js/utils/logout.js"></script>
    </body>

    </html>