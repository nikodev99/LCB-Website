<?php

namespace Web\App\Core;

use DateTime;
use DateTimeZone;
use Web\App\Core\Route\Route;

class AppFunction
{

    public static function stamp (string $time, string $format = Constant::DATE_FORMAT): string
    {
        return (new DateTime("@$time"))
            ->setTimezone(new DateTimeZone('Africa/Brazzaville'))
            ->format($format);
    }

    public static function successMessage(string $message, string $url): void
    {
        $flash = new Flash();
        $flash->success($message);
        Route::redirect($url);
    }

    public static function infoMessage(string $message, string $url): void
    {
        $flash = new Flash();
        $flash->info($message);
        Route::redirect($url);
    } 

    public static function errorMessage($message, string $url): void
    {
        $flash = new Flash();
        $flash->error($message);
        Route::redirect($url);
    }

    public static function user_connected(): bool
    {
        $connected = false;
        $parts = explode('/', Request::getRequest());
        if (!in_array('create_page', $parts) && self::session_exists()) {
            $connected = true;
        }
        return $connected;
    }

    public static function user_not_connected(string $url): void
    {

        if (!self::session_exists()) {
            self::errorMessage('Page inaccessible en étant déconecté. Veuillez vous connecter pour y acceder.', $url);
        }
    }

    public static function online(int $online): string
    {
        $background = Constant::RED_COLOR;
        if ($online === 1) {
            $background = Constant::GREEN_COLOR;
        }
        return <<<HTML
            <span class="make-online" style="background:{$background}"></span>
        HTML;
    }

    public static function array_compare(array $array1, array $array2): bool
    {
        $comparison_result = false;
        $diff = array_diff($array1, $array2);
        if (empty($diff)) $comparison_result = true;
        return $comparison_result;
    }

    public static function array_trim(array $array_to_trim): array
    {
        $array_trimed = [];
        foreach($array_to_trim as $value_to_trim) {
            $array_trimed[] = trim($value_to_trim);
        }
        return $array_trimed;
    }

    public static function pageURL(string $url): string
    {
        $lien = trim($url);
        $request = Request::getRequest();
        $url_parts = explode('/', $request);
        if (in_array('en', $url_parts)) {
            return '/en/page/'.$lien;
        }elseif(in_array('fr', $url_parts)) {
            return '/fr/page/'.$lien;
        }else {
            return '/page/'.$lien;
        }
    }

    public static function requestLang(string $request): bool
    {
        $url_requested = explode("/", trim($request));
        return in_array('en', $url_requested);
    }

    public static function langVerify(): bool
    {
        $url_requested = explode("/", trim(Request::getRequest()));
        return in_array('en', $url_requested);
    }

    public static function multipleURL(array $urls, bool $choice = false): string
    {
        $url = $urls[0];
        if ($choice) $url = $urls[1];
        return $url;
    }

    public static function generate_ids(int $length = 3, bool $elevate = false): string
    {
        $digits = 'AZERTYUIOPQSDFGHJKLMWXCVBN';
        if ($elevate) {
            $digits .= '1234567890';
            $length = 5;
        }
        return substr(str_shuffle(str_repeat($digits, $length)), 0, $length).time();
    }

    public static function display(bool $notConnected = true, array $array, string $str, string $url): bool
    {
        if (self::user_connected()) {
            return in_array($str, $array);
        }else {
            if($notConnected) {
                return $notConnected && in_array($str, $array);
            }else {
                Route::redirect($url); 
            }
        }
    }

    public static function excerpt(?string $content, int $limit = 220): ?string
    {
        if (is_null($content)) return null;
        if (mb_strlen($content) > $limit) {
            $excerpt = mb_substr($content, 0, $limit);
            $last_space = mb_strrpos($excerpt, ' ');
            return substr($content, 0, $last_space) . '...';
        }
        return $content;
    }

    public static function viewSearch(string $content, $needle): string
    {
        $content = strip_tags($content);
        if(mb_strpos($content, $needle)) {
            if (mb_strlen($content) > 220) {
                $start = mb_strpos($content, $needle);
                $text = mb_substr($content, $start, 220);
                $end = mb_strrpos($text, ' ');
                return str_replace($needle, '<mark>' . $needle . '</mark>', '...' . mb_substr($content, $start, $end) . '...');
            }
        }
        return self::excerpt($content);
    }

    public static function unlinking(string $file): void
    {
        $realPath = self::getBGPath($file);
        if(file_exists($realPath)) {
            unlink($realPath);
        }
    }

    public static function getBGPath(string $file): string
    {
        return dirname(__DIR__, 2) . Constant::DS . 'public' . Constant::DS . 'webroot' . Constant::DS . 'img' . Constant::DS . 'bg-img'. Constant::DS . $file;
    }

    public static function getImgRoute(string $file): string
    {
        return Constant::BG_PATH.$file;
    }

    public static function hashPassword(string $password): string
    {
        $pwd_prepared = hash_hmac('sha256', $password, Constant::PEPPER, true);
        return password_hash($pwd_prepared, PASSWORD_ARGON2ID);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        $pwd = hash_hmac('sha256', $password, Constant::PEPPER, true);
        return password_verify($pwd, $hash);
    }

    protected static function session_exists(): bool
    {
        $logged = new Logged();
        return $logged->session_exists('connected');
    }
}
