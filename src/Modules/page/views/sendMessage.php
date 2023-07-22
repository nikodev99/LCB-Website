<?php

use PHPMailer\PHPMailer\PHPMailer;
use Web\App\Core\AppFunction;
use Web\App\Core\session\Post;

$post = new Post();

$last_name = $post->get('last_name');
$first_name = $post->get('first_name');
$sexe = $post->get('sexe');
$object = $post->get('object');
$cv = $_FILES['cv'] ?? null;
$letter = $_FILES['letter'] ?? null;
$message = $post->get('message');

$errors = [];

$redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

if (!$post->isEmpty()) {

    if (is_null($last_name) | is_null($first_name) | is_null($sexe) /*| is_null($cv) | is_null($letter) µ*/| is_null($message))
        $errors[] = 'Un ou plusieurs champs sont vides';

    if (empty($errors)) {

        $mail = new PHPMailer();

        $send = false;

        try {

            $mail->isSMTP();
            $mail->SMTPDebug = 2;
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "tt9999.tt123@gmail.com";
            $mail->Password = "@TestPCA99";
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->Priority = 1;
            $mail->SMTPOptions = array(
                'ssl' => [
                    'verify_peer' => true,                    'verify_depth' => 3,
                    'allow_self_signed' => true,
                    'peer_name' => 'smtp.gmail.com'
                ],
            );

            $mail->From = "tt9999.tt123@gmail.com";
            $mail->FromName = "Test Test";
            $mail->addAddress("n.niama@lcb-bank.com");
            
            $mail->isHTML(true);
            $mail->Subject = $object;
            $mail->Body = $message;

            $send = $mail->send();
            dump($mail->ErrorInfo);
            dd($send);

        }catch(Exception $e) {
            dump($mail->ErrorInfo);
            dd("cause du non envoie: " . $e->getMessage());
        }

        if ($send) {
            AppFunction::successMessage("Message envoyé. Merci d'avoir postuler à LCB Bank", $redirect_url);
        }else {
            AppFunction::errorMessage("Message non envoyé. Réessayer", $redirect_url);
        }

    }else {
        AppFunction::errorMessage($errors, $redirect_url);
    }

}