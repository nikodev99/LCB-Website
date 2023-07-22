<?php

namespace Web\App\Components\Utilities;

class DashboardContent
{
    public static function getGrid(array $content = []): string
    {
        $card = [];
        foreach($content as $eachContent) {
            $card[] = self::singleGrid($eachContent);
        }
        return implode(PHP_EOL, $card);
    }

    private static function singleGrid(array $content): string
    {
        $icon = $content['icon'] ?? null;
        $stat = $content['stat'] ?? null;
        $title = $content['title'] ?? null;
        $description = $content['description'] ?? null;
        $button = $content['button'] ?? null;
        $color = $content['color'] ?? null;
        $url = $content['url'] ?? null;
        return <<<HTML
            <div class="four wide computer eight wide tablet sixteen wide mobile column">
                <div class="ui fluid card">
                    <div class="content">
                        <div class="ui right floated header" style="color: {$color}">
                            <i class="icon {$icon}"></i>
                        </div>
                        <div class="header">
                            <div class="ui header" style="color: {$color}">{$stat}</div>
                        </div>
                        <div class="meta">{$title}</div>
                        <div class="description">
                            {$description}
                        </div>
                    </div>
                    <div class="extra content">
                        <a href="{$url}" class="ui fluid button red" style="background: {$color}">{$button}</a>
                    </div>
                </div>
            </div>
        HTML;
    }
}