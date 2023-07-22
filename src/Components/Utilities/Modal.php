<?php 

namespace Web\App\Components\Utilities;

use Web\App\Components\Form;

class Modal {

    public static function modal(bool $display = false, string $url, string $pointer, string $name): ?string
    {
        $content = null;
        $title = null;
        if($name === 'breadcrump') {
            $content = self::form($url, self::breadcrumpModify());
            $title = 'Modification de la bannière';
        }
        if($name === 'contact') {
            $content = self::form($url, self::contactModify());
            $title = 'Modification de la section contact';
        }
        if ($display) {
        return <<<HTML
        <div class="modal fade" id="{$pointer}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{$title}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {$content}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
HTML;
        }else {
            return null;
        }
    } 

    private static function form(string $url, string $content): string {
        return <<<HTML
        <form action="{$url}" method="post" enctype="multipart/form-data">
            {$content}
            <button class="btn btn-success">Modifier</button>
        </form>
HTML;
    } 

    private static function breadcrumpModify(): string
    {   $header = Form::input([
            'label' =>  'Titre',
            'name'  =>  'header',
            'value' =>  '',
        ]);
        $headre_ang = Form::input([
            'label' =>  'Titre en anglais',
            'name'  =>  'header_ang',
            'value' =>  ''
        ]);
        $background = Form::input([
            'type'  =>  'file',
            'label' =>  'Photo de la bannière',
            'name'  =>  'background',
        ]);
        return <<<HTML
        <div class="row">
            <div class="col-lg-6">{$header}</div>
            <div class="col-lg-6">{$headre_ang}</div>
        </div>
        <div class="row">
            <div class="col-lg-6">{$background}</div>
        </div>   
HTML;
    }

    private static function contactModify(): string
    {
        $address = Form::input([
            'label' =>  'Nouvelle adresse',
            'name'  =>  'address',
            'placeholder' =>  'Entrer une nouvelle adresse',
        ]);
        $tel = Form::input([
            'label' =>  'Nouveau numéro de téléphone',
            'name'  =>  'tel',
            'placeholder' =>  'ex: 06 700 11 11. Ne pas mettre (+242)'
        ]);
        $email = Form::input([
            'type'  =>  'email',
            'label' =>  'Nouvelle adresse mail',
            'name'  =>  'email',
            'placeholder' =>  'Entrer une nouvelle adresse email'
        ]);
        $description = Form::textarea([
            'name'  =>  'description',
            'placeholder'   =>  'Entrer une nouvelle description'
        ], [], 4, 'form-group');

        $description_ang = Form::textarea([
            'name'  =>  'description_ang',
            'placeholder'   =>  'Entrer une nouvelle description en anglais'
        ], [], 4, 'form-group');
        return <<<HTML
        <div class="row">
            <div class="col-lg-12">{$address}</div>
            <div class="col-lg-12">{$tel}</div>
            <div class="col-lg-12">{$email}</div>
        </div>
        <div class="row">
            <div class="col-lg-6">{$description}</div>
            <div class="col-lg-6">{$description_ang}</div>
        </div> 
HTML; 
    }

}

