<?php

use Web\App\Core\Model;
use Web\App\Core\Logged;
use Web\App\Core\AppFunction;
use Web\App\Core\session\Post;

$errors = [];
$redirect_url = $router->url('admin.dashboard');
$id = '5f1a92c4b2d5f';
$logged = new Logged();
$post = new Post();
$auth_cookie = $logged->getCookie('auth') ?? null;

if (!is_null($auth_cookie) && !$logged->session_exists('connected')) {
    $auth = explode($id, $auth_cookie);
    $userToConnect = Model::getUser(['id' => $auth[0]]);
    $crypt = sha1($userToConnect->username . $userToConnect->password . $_SERVER['REMOTE_ADDR']);
    dump($crypt === $auth[1]);
    if ($crypt === $auth[1]) {
        $logged->setAuthentificationCookie($auth_cookie);
        $logged->getConnected($userToConnect, $redirect_url);
    }else {
        $logged->removeCookie('auth');
    }
}else {
    if (!$post->isEmpty()) {
        $username = trim($post->get('username'));
        $password = trim($post->get('password'));
        if (!empty($username) && !empty($password)) {
            $user = Model::getUser([
                'username'  =>  $username,
                'email' =>  $username
            ]);
            if (!is_null($post->get('remember_me'))) {
                $cookie_value = $user->id . $id . sha1($user->username . $user->password . $_SERVER['REMOTE_ADDR']);
                $logged->setAuthentificationCookie($cookie_value);
            }
            if (is_null($user) | empty($user)) {
                $errors[] = 'Pseudo ou mot de passe incorrect';
            }else {
                if (AppFunction::verifyPassword($password, $user->password)) {
                    $logged->getConnected($user, $redirect_url);
                }else {
                    $errors[] = 'Pseudo ou mot de passe incorrect';
                }
            }
        }else {
            $errors[] = 'Champs vides, veuillez entrer votre identifiant ou email et un mot de passe valide pour vous connecter';
        }
    }
}

?>

<section class="login">
   <form action="" method="POST" class="login-form">
        <?php if (!empty($errors)): ?>
            <div class="ui error message" style="margin-bottom: 0;">
                <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>
        <div class="login-form-logo-container">
            <img class="login-form-logo" src="../webroot/img/icons/logo1.jpg" alt="">
        </div>
        <div class="login-form-content">
            <div class="login-form-header">Connecter vous </div>
            <input type="text" name="username" placeholder="Nom d'utilisateur ou email">
            <input type="password" name="password" placeholder="Mot de passe">
            <label for="subscribeNews">Se rappeeler de moi? 
                <input type="checkbox" name="remember_me" id="remember_me" value="remember me">
            </label>
            <button type="submit" class="login-form-button">Se connecter</button>
            <div class="login-form-links">
                <a href="#" >Mot de passe oublié ?</a>
            </div>
        </div>
    </form>
</section>