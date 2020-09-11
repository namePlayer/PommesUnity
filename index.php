<?php
session_start();
$return = '';
date_default_timezone_set('Europe/Berlin');
$currenttime = time();
# error_reporting(1);
require_once('inc/routes.inc.php');
require_once('inc/database.inc.php');
require_once('inc/timefunct.inc.php');
require_once('inc/accounteng.inc.php');
require_once('inc/security.inc.php');
require_once('inc/random.inc.php');
require_once('inc/noxss/HTMLPurifier.auto.php');
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if(isset($_SESSION['pu_login'])) {
    $userid = $_SESSION['pu_login'];
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php base() ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php base() ?>css/all.min.css">
    <link rel="stylesheet" href="<?php base() ?>css/secondarynav.css">
    <script src="<?php base() ?>js/tinymce/tinymce.min.js"></script>
    <script>
    tinymce.init({
        selector:'textarea',
        menubar: false,
        plugins: 'lists',
        toolbar: 'bold italic underline | styleselect | forecolor | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | undo redo',
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
    </script>
    <title>PommesUnity</title>
</head>
<body>
    <?php require_once('assets/header.php'); ?>

    <div class="container" style="margin-top: 25px; position: relative; ">
    <?= $return ?>
        <noscript>
            <div class="alert alert-warning" role="alert">
            <strong>Warnung!</strong> Einige Elemente dieser Seite funktionieren nicht, da Javascript deaktiviert ist!
            </div>
        </noscript>
        <?php 
        if($found == 1) {
            require_once('pagefiles/' . $url[0] . '.php');
        }
        # echo time();
        # print_r(getdate());
        ?>
    </div>

    <?php require_once('assets/footer.php'); ?>
    <script src="<?php base() ?>js/bootstrap.bundle.min.js"></script>
</body>
</html>