<?php


namespace Web\App\Components\Utilities;

use Web\App\Core\AppFunction;

class Footer
{
    public static function getFooter(array $footerElements = []): string
    {
        $footer = [];
        foreach($footerElements as $elements) {
            $footer[] = self::footer_construct($elements);
        }
        return implode(PHP_EOL, $footer);
    }

    private static function footer_construct(array $footerElements): string
    {
        $title = $footerElements['title'] ?? null;
        $footerItems = $footerElements['items'] ?? null;
        $icon = array_key_exists('icon', $footerElements) ? $footerElements['icon'] : false;
        $items = [];
        foreach ($footerItems as $url => $anchorName) {
            if ($icon) {
                $items[] = '<li><a href="'. AppFunction::pageURL($url) .'"><i class="fa fa-'.$anchorName.'"></i></a></li>';
            }else {
                $items[] = '<li><a href="'. AppFunction::pageURL($url) .'">'. $anchorName .'</a></li>';
            }
        }
        $item = implode(PHP_EOL, $items);
        return <<<HTML
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="single-footer-widget mb-100">
                    <h5 class="widget-title">{$title}</h5>
                    <nav>
                        <ul>
                            {$item}
                        </ul>
                    </nav>
                </div>
            </div>
        HTML;
    }
}