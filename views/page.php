<?php

use Web\App\Core\Flash;

$session = new Flash();
$session_flash = $session->flash();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $description ?? "Welcome on the LCB Website" ?>">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?? "LCB Bank, BMCE Group" ?></title>

    <link rel="stylesheet" href="../../webroot/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../webroot/css/classy-nav.css">
    <link rel="stylesheet" href="../../webroot/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../../webroot/css/animate.css">
    <link rel="stylesheet" href="../../webroot/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../webroot/css/credit-icon.css">
    <link rel="stylesheet" href="../../webroot/css/main.css">
</head>
<body>

<?php if (!empty($session_flash)): 
    $icon = null;
    ?>
    <?php foreach ($session_flash as $type => $message): 
            $class = "alert alert-$type";
            if($type === 'success') {
                $icon = 'check';
            }else {
                $icon = 'close';
                $class = 'alert alert-danger';
            }
        ?>
        <div class="<?= $class ?>" style="margin: 5px 20px">
            <i class="icon <?= $icon ?>"></i>
            <?= $session->get($type) ?>
        </div>
    <?php endforeach ?>
    <?php $session->unset_flash() ?>
<?php endif ?>

<?= $content ?? null ?>
    
<!-- ##### All Javascript Script ##### -->
<!-- jQuery-2.2.4 js -->
<script src="../../webroot/js/jquery-2.2.4.min.js"></script>
<!-- Popper js -->
<script src="../../webroot/js/bootstrap/popper.min.js"></script>
<!-- Bootstrap js -->
<script src="../../webroot/js/bootstrap/bootstrap.min.js"></script>
<!-- All Plugins js -->
<script src="../../webroot/js/plugins/plugins.js"></script>
<script src="../../webroot/js/plugins/carousel.js"></script>
<!-- Active js -->
<script src="../../webroot/js/base.js"></script>
<script src="../../webroot/js/main.js"></script>
</body>
</html>