<?php

namespace Web\App\Components;

class Component
{
    public static function dataTable(array $tablesCells = [], array $features = []): string
    {
        $ths = [];
        $tds = [];
        foreach($tablesCells['th'] as $th) {
            $ths[] = self::th($th);
        }
        foreach($tablesCells['td'] as $key => $td) {
            if (empty($features)) {
                $tds[] = self::td($td);
            }else {
                foreach($features as $k => $bg) {
                    if ($key === $k) {
                        $link = array_key_exists('url', $bg) ? $bg['url'] : null;
                        $background = array_key_exists('url', $bg) ? $bg['bg'] : false;
                        $tds[] = self::td($td, $link, $background, true);
                    }
                }
            }
        }
        $thcells = implode("\n", $ths);
        $tdcells = implode("\n", $tds);
        return <<<HTML
        <table id="datatables" class="ui celled striped table">
            <thead>
                {$thcells}
            </thead>
            <tbody>
                {$tdcells}
            </tbody>
        </table>
HTML;
    }

    public static function table(array $tablesCells = []): string
    {
        $ths = [];
        $tds = [];
        $caption = $tablesCells['caption'] ?? null;
        if (!is_null($caption)) $caption = '<caption>'.$tablesCells['caption'].'</caption>';
        foreach($tablesCells['th'] as $th) {
            $ths[] = self::th($th);
        }
        foreach($tablesCells['td'] as $td) {
            $tds[] = self::td($td);
        }
        $thcells = implode("\n", $ths);
        $tdcells = implode("\n", $tds);
        return <<<HTML
        {$caption}
        <table class="ui celled striped table">
            <thead>
                {$thcells}
            </thead>
            <tbody>
                {$tdcells}
            </tbody>
        </table>
HTML;
    }

    public static function modalLauncher(array $data = []): string
    {
        $icon = array_key_exists('icon', $data) ? $data['icon'] : 'plus circle'; 
        $data_pointer = array_key_exists('data', $data) ? $data['data'] : null; 
        $label = array_key_exists('label', $data) ? $data['label'] : 'Ajouter';
        $color = array_key_exists('color', $data) ? $data['color'] : 'positive';
        return <<<HTML
        <button class="ui {$color} button" data-modal-target="{$data_pointer}">
            <i class="icon {$icon}"></i>
            {$label}
        </button>
HTML;
    }

    public static function back_button(?string $data_pointer = null) {
        return <<<HTML
        <a href="{$data_pointer}" class="ui positive button"><i class="ui icon arrow left circle"></i> Retour</a>
HTML;
    }

    public static function modify_button(?string $data_pointer = null, string $label = 'Modifier'): string
    {
        return <<<HTML
        <button type="button" class="btn btn-success modify-button" data-toggle="modal" data-target="#{$data_pointer}"><i class="fa fa-edit"></i>{$label}</button>
HTML;
    } 

    public static function modify_anchor(?string $data_pointer = null, string $label = 'Modifier'): string
    {
        return <<<HTML
        <a href="{$data_pointer}" class="btn btn-success modify-button"><i class="fa fa-edit"></i>$label</a>
HTML;
    }

    public static function ui_modify_anchor(?string $data_pointer = null, string $label = 'Modifier', array $beauty = []): string
    {
        $icon = array_key_exists('icon', $beauty) ? $beauty['icon'] : 'edit';
        $color = array_key_exists('color', $beauty) ? $beauty['color'] : 'primary';
        return <<<HTML
        <a href="{$data_pointer}" class="ui {$color} button"><i class="ui icon {$icon}"></i>$label</a>
HTML;
    }

    public static function removal (string $url = '', string $title = 'ceci', $label = 'Suprimer'): string
    {
        return <<<HTML
        <form action="{$url}" method="POST"
            onsubmit="return confirm('Voulez vous vraiment supprimer {$title} ?')" style="display:inline">
            <button type="submit" class="ui negative button"><i class="ui icon close"></i>{$label}</button>
        </form>
        HTML;
    }

    public static function button(array $labels, array $urls, bool $indentifier = false, array $colors = [], array $icons = []): string
    {
        $label = $labels[0];
        $url = $urls[0];
        $color = !empty($colors) && array_key_exists(0, $colors) ? $colors[0] : 'primary';
        $icon = !empty($icons) && array_key_exists(0, $icons) ?$icons[0] : null;
        if($indentifier) {
            $label = $labels[1];
            $url = $urls[1];
            $color = !empty($colors) && array_key_exists(1, $colors) ? $colors[1] : 'primary';
            $icon = !empty($icons) && array_key_exists(1, $icons) ? $icons[1] : null;
        }
        $i = !is_null($icon) ? '<i class="ui icon '.$icon.'"></i>' : null;
        return <<<HTML
        <a href="{$url}" class="ui {$color} button">{$i}{$label}</a>
HTML;
    }

    public static function form(array $labels, array $urls, bool $indentifier = false, array $colors = []): string
    {
        $label = $labels[0];
        $url = $urls[0];
        $color = !empty($colors) ? $colors[0] : 'primary';
        if($indentifier) {
            $label = $labels[1];
            $url = $urls[1];
            $color = $colors[1];
        }
        return <<<HTML
        <form action="{$url}" method="POST" style="display:inline">
            <button class="ui {$color} button">$label</button>
        </form>
HTML;
    }

    private static function th(array $ths): string
    {
        $thTable =  [];
        foreach ($ths as $th) {
            if (is_int(strpos('|', $th))) {
                $thcol = explode('|', $th);
                $col = explode(':', $thcol[1]);
                $thTable[] = '<th '.$col[0].'="'.$col[1].'">'.$thcol[0].'</th>';
            }else {
                $thTable[] = '<th>'.$th.'</th>';
            }
        }
        $thcells = implode(PHP_EOL, $thTable);
        return <<<HTML
        <tr>
            {$thcells}
        </tr>
HTML;
    }

    private static function td(array $ths, string $linked = null, bool $background = false, bool $special_background = false): string
    {
        $thTable =  [];
        $class = $special_background ? 'class="dataTable_feature"' : null;
        foreach ($ths as $th) {
            if (is_int(strpos($th, '|'))) {
                $thcol = explode('|', $th);
                $col = explode(':', $thcol[1]);
                if (!is_null($linked)) {
                    $thTable[] = $thTable[] = '<td '.$col[0].'="'.$col[1].'"><a href="'.$linked.'">'.$thcol[0].'</a></td>';
                }else {
                    $thTable[] = '<td '.$col[0].'="'.$col[1].'">'.$thcol[0].'</td>';
                }
            }else {
                if (!is_null($linked)) {
                    $thTable[] = '<td><a href="'.$linked.'">'.$th.'</a></td>';
                }else {
                    $thTable[] = '<td>'.$th.'</td>';
                }
            }
        }
        if ($special_background && $background) $class = 'class="dataTable_new_feature"';
        $thcells = implode(PHP_EOL, $thTable);
        return <<<HTML
        <tr {$class}>
            {$thcells}
        </tr>
HTML;
    }
}