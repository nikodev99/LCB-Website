<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Constant;
use Web\App\Core\Model;
use Web\App\Core\session\Session;

$session = new Session();

$lang = is_bool($session->get('lang'));

$redirect_url = AppFunction::multipleURL([
    $router->url("page.index", ['slug' => $params['slug']]),
    $router->url("page@indexed", ['slug' => $params['slug']])
], $lang);

$search = $_GET['q'] ?? null;

$search = htmlentities($search);

$posts = [];

if(empty($search) || (int)$search !== 0 || str_contains($search, 'TRUNCATE') || str_contains($search, 'truncate') || is_null($search)) {
    AppFunction::infoMessage("Impossible d'effectuer la recherche", $redirect_url);
}else {

    $posts = Model::getSearchedPosts(
        ['breadcrump', 'date', 'slug', 'slug_ang', 'title', 'title_ang', 'excerpt', 'excerpt_ang', 'content', 'content_ang', 'online'], 
        [
            'title'    =>  $search, 'title_ang'    =>  $search, 'excerpt'  =>  $search, 'excerpt_ang'  =>  $search, 'content'   =>  $search, 'content_ang'  =>  $search
        ], ['date', 2]
    );

}

?>

<section class="miscellaneous-area section-padding-100-0">
    <div class="container">
        <div class="row align-items-end justify-content-center">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center mb-100 wow fadeInUp" data-wow-delay="100ms">
                        <div class="line"></div>
                        <h2><?= $lang ? 'Search Results' : 'Resultat de la recherche' ?></h2>
                    </div>
                </div>
                <?php if (!empty($posts)): 
                    
                    $delay = 0;
                    
                    ?>
                    <?php foreach($posts as $post): 
                        if ($post->online > 0) :
                            $delay += 100;
                            $title = null;
                            $content = null;
                            $url = null;
                            if (strpos($post->title, $search)) {
                                $title = str_replace($search, '<mark>'. $search . '</mark>', $post->title);
                                $content =  AppFunction::viewSearch($post->excerpt, $search);
                                $url = $router->url("page.index", ['slug' => $post->slug]);
                            }elseif(strpos($post->title_ang, $search)) {
                                $title = str_replace($search, '<mark>'. $search . '</mark>', $post->title_ang);
                                $content =  AppFunction::viewSearch($post->excerpt_ang, $search);
                                $url = $router->url("page.index", ['slug' => $post->slug_ang]);
                            }elseif(strpos($post->excerpt, $search)) {
                                $title = $post->title;
                                $content =  AppFunction::viewSearch($post->excerpt, $search);
                                $url = $router->url("page.index", ['slug' => $post->slug]);
                            }elseif(strpos($post->excerpt_ang, $search)) {
                                $title = $post->title_ang;
                                $content =  AppFunction::viewSearch($post->excerpt_ang, $search);
                                $url = $router->url("page.index", ['slug' => $post->slug_ang]);
                            }elseif(strpos($post->content, $search)) {
                                $title = $post->title;
                                $content =  AppFunction::viewSearch($post->content, $search);
                                $url = $router->url("page.index", ['slug' => $post->slug]);
                            }elseif(strpos($post->content_ang, $search)) {
                                $title = $post->title_ang;
                                $content =  AppFunction::viewSearch($post->content_ang, $search);
                                $url = $router->url("page.index", ['slug' => $post->slug_ang]);
                            }else {
                                $title = $post->title;
                                $content =  AppFunction::excerpt($post->excerpt);
                                $url = $router->url("page.index", ['slug' => $post->slug]);
                            }
                            
                            ?>
                            <div class="col-lg-4">
                            <div class="item wow fadeInUp" data-wow-delay="<?= $delay ?>ms">
                                <div class="item-img">
                                    <a href="<?= $url ?>"><img class="img-fluid" src="<?= Constant::BG_PATH . $post->breadcrump ?>" alt=""></a>
                                </div>
                                <div class="item-body">
                                    <div class="item-title">
                                        <a href="<?= $url ?>"><?= $title ?></a>
                                    </div>
                                    <div class="item-description">
                                        <p><?= $content ?></p>
                                    </div>
                                </div>
                            </div>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>
                <?php else: ?>
                <div class="col-lg-12">
                    <div class="section-heading text-center mb-100 wow fadeInUp" data-wow-delay="100ms">
                        <h3>
                            <?= $lang 
                            ? "No post found with the keyword(s) «${search}». " . '<a href="'.$redirect_url.'" class="btn btn-danger">return</a>'
                            : "Aucune correspinance avec votre(vos) mot(s) clé(s) «${search}». " . '<a href="'.$redirect_url.'" class="btn btn-danger">Retour</a>'
                            ?>
                        </h3>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>