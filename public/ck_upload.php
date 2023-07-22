<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Constant;
use Web\App\Core\upload\ImgUpload;

require "../vendor/autoload.php";

AppFunction::user_not_connected('/admin_login');

$img = new ImgUpload($_FILES['upload']);
$sizes = getimagesize($_FILES['upload']['tmp_name']);
$filename = $img->newFilename();
$img->upload($filename, '/lcb_bank_admin_panel', $sizes[0], $sizes[1]);

$function_number = $_GET['CKEditorFuncNum'];
$url = Constant::BG_PATH . $filename;
$message = 'Image télécharger avec succès';

echo 
"<script type=\"text/javascript\">
    window.parent.CKEDITOR.tools.callFunction('${function_number}', '${url}', '${message}')
</script>";