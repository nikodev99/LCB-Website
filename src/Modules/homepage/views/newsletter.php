<?php

use Web\App\Core\AppFunction;

?>

<p>
    <a style="color:red" href="<?= AppFunction::multipleURL([$router->url('home@index'), $router->url('homepage@index')], AppFunction::langVerify()) ?>">
        <?= AppFunction::langVerify() ? 'Return to homepage': "Retour à la page d'acceuil" ?>
    </a>
</p>