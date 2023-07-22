<?php

namespace Web\App\Core\models;

use Exception;
use PDO;
use Web\App\Components\connect\DBConnector;
use Web\App\Core\AppFunction;
use Web\App\Core\Url;

class PaginationQuery {

    private $query;
    private $queryCount;
    private $pdo;
    private $limit;
    private $count;
    private $items;

    public function __construct(string $query, string $queryCount, ?PDO $pdo = null, int $limit = 6)
    {
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->pdo = $pdo ?: DBConnector::getConnectedToMYSQL();
        $this->limit = $limit;
    }

    public function getItems(): array
    {
        if ($this->items === null) {
            $currentPage = $this->getCurrentPage();
            $pages = $this->getPage();
            if ($currentPage > $pages) throw new Exception('Cette page n\'existe pas');
            $offset = ($currentPage - 1) * $this->limit;
            $query = $this->pdo->prepare("{$this->query} LIMIT {$this->limit} OFFSET $offset");
            $query->execute();
            $this->items = $query->fetchAll();
        }
        return $this->items;
    }

    public function prev(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        if($currentPage > 1) {
            if ($currentPage > 2) $link .= '?p=' . ($currentPage - 1);
            $label = AppFunction::langVerify() ? 'Previous Page' : 'Page Précédente';
            return <<<HTML
            <a href="{$link}" class="btn credit-btn box-shadow"><i class="fa fa-chevron-left"></i> {$label}</a>
HTML;
        }
        return null;
    }

    public function next(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPage();
        if ($currentPage >= $pages) return null;
        $link .= '?p='. ($currentPage + 1);
        $label = AppFunction::langVerify() ? 'Next Page' : 'Page Suivante';
        return <<<HTML
        <a href="{$link}" class="btn credit-btn box-shadow">{$label} <i class="fa fa-chevron-right"></i></a>
HTML;
    }

    private function getCurrentPage(): int
    {
       return Url::getPositiveInt('p', 1);
    }

    private function getPage(): float
    {
        if ($this->count === null) {
            $this->count = (int) $this->pdo->query($this->queryCount)->fetch(PDO::FETCH_NUM)[0];
        }
        return ceil($this->count / $this->limit);
    }

}