<?php

namespace Web\App\Components\Utilities;

use Web\App\Core\AppFunction;
use Web\App\Core\Request;

class NavBar
{

    public static function getMenu(array $menuTable)
    {
        $menu_anchors = [];
        $values = [];
        foreach($menuTable as $menu_item => $submenu_items) {
            $values[] = $submenu_items;
            if (is_array($submenu_items)) {
                if (self::getType($submenu_items))
                    $menu_anchors[] = self::NavBarItemHasSubItem('#', $menu_item, $submenu_items);
                if(!self::getType($submenu_items))
                    $menu_anchors[] = self::navBarItemHasChildren('#', $menu_item, $submenu_items);
            }else {
                $menu_anchors[] = self::navBarItem($menuTable[$menu_item], $menu_item);
            }
        }
        return implode(PHP_EOL, $menu_anchors);
    }

    public static function multyLang(array $allLang, string $request): string
    {
        $langAnchor = [];
        $class = null;
        foreach ($allLang as $url => $name) {
            if ($url === $request) {
                $class = "active";
            }else {
                $class = "";
            }
            $langAnchor[] = '<a href="'.$url.'" class="'.$class.'" data-toggle="tooltip" data-placement="bottom" title="'. $name['title'] .'">
                <img src="'. $name['image'] .'" alt=""><span>'.$name['name'].'</span>
            </a>';
        }
        return implode("\n", $langAnchor);
    }

    public static function adminNavItem(array $navItems): string
    {
        $items = [];
        $allItems = self::adminSingleItem($navItems['items']);
        foreach($navItems['items'] as $navItem) {
            if (is_array($navItem)) {
                $items[] = self::adminSingleItem($navItem);
            }
        }
        if (!empty($items)) $allItems = implode(PHP_EOL, $items);
        if (array_key_exists('header', $navItems)):
            return <<<HTML
                <div class="item">
                    <div class="header">{$navItems['header']}</div>
                    <div class="menu">
                        {$allItems}
                    </div>
                </div>
            HTML;
        else:
            return $allItems;
        endif;
    }

    public static function getBarProgress(array $progresses): string
    {
        $progress = [];
        foreach ($progresses as $progresse) {
            $progress[] = self::barProgress($progresse);
        }
        $bar_progress = implode(PHP_EOL, $progress);
        return <<<HTML
            <div class="ui segment">
                {$bar_progress}
            </div>
        HTML;
    }

    public static function removal (string $url = '', string $title = 'ceci', $label = 'Suprimer'): string
    {
        return <<<HTML
        <form action="{$url}" method="POST"
            onsubmit="return confirm('Voulez vous vraiment supprimer {$title} ?')" style="display:inline">
            <button type="submit" class="ui negative fluid button">{$label}</button>
        </form>
        HTML;
    }

    private static function navBarItem(string $url, string $anchor): string
    {
        return '<li><a href="'. self::pageURL($url) .'">' . trim($anchor) . '</a></li>';
    }

    private static function navBarItemHasChildren(string $url, string $anchor, array $childurls): string
    {
        $items = [];
        foreach ($childurls as $key => $name) {
            $items[] = self::subItem($name, "single-mega cn-col-4");
        }
        $childItems = implode("\n", $items);
        return <<<HTML
            <li><a href="{$url}">{$anchor}</a>
                <div class="megamenu">
                    <div class="container">
                        {$childItems}
                    </div>
                </div>
            </li>
        HTML;
    }

    private static function NavBarItemHasSubItem(string $url, string $anchor, array $childAnchors): string
    {
        $subItems = self::subItem($childAnchors);
        return <<<HTML
            <li><a href="{$url}">{$anchor}</a>
                $subItems
            </li>
        HTML;
    }

    private static function subItem(array $childAnchors, ?string $class = "dropdown"): string
    {
        $urls = [];
        foreach ($childAnchors as $key => $anchors) {
            $urls[] = '<li><a href="'.self::pageURL($key).'">'.trim(ucfirst($anchors)).'</a></li>';
        }
        $children = implode("", $urls);
        return <<<HTML
            <ul class="{$class}">
                {$children}
            </ul>
        HTML;

    }

    private static function pageURL(string $url): string
    {
        return AppFunction::pageURL($url);
    }

    private static function adminSingleItem(array $items): string
    {
        $url = $items['url'] ?? null;
        $icon = $items['icon'] ?? null;
        $name = $items['name'] ?? null;
        return <<<HTML
            <a href="{$url}" class="item">
                <div>
                    <i class="icon {$icon}"></i>{$name}
                </div>
            </a>
        HTML;
    }

    private static function barProgress(array $progresses): string
    {
        $width = $progresses['width'] ?? null;
        $background = $progresses['background'] ?? null;
        $label = $progresses['label'];
        return <<<HTML
            <div class="ui tiny progress">
                <div class="bar" style="width: {$width}; background: {$background};"></div>
                <div class="label">{$label}</div>
            </div>
        HTML;
    }

    private static function getType(array $data): bool
    {
        $dropdown = false;
        foreach($data as $items) {
            if (is_string($items)) $dropdown = true;
        }
        return $dropdown;
    }

}