<?php

namespace Web\App\Core;

use stdClass;
use Web\App\Components\connect\DBConnector;
use Web\App\Core\models\{
    Query,
    tables\ReasonsTable,
    tables\WorkTable,
    tables\TitleTable,
    tables\MultipleFeatures
};
use Web\App\Core\models\tables\MessageTable;
use Web\App\Core\models\tables\MetadataTable;
use Web\App\Core\models\tables\PostsTable;
use Web\App\Core\models\tables\PresentationsTable;

class Model 
{
    private const TABLES = [
        'users_table'   =>  'users',
        'menu_navbar'   =>  'navbar',
        'pages'         =>  'pages',
        'breadcrumb'    =>  'banniere',
        'slide'         =>  'carousel',
        'feat'          =>  'feature',
        'cta'           =>  'cta',
        'service'       =>  'services',
        'contact'       =>  'contact',
        'about'         =>  'about',
        'expose'        =>  'expose',
        'acc'           =>  'accordion',
        'left'          =>  'left_expose',
        'tab'           =>  'tabs',
        'text'          =>  'text',
        'news'          =>  'newsletter',
    ];

    //USERS CONCERN
    public static function getAllUsers(?string $column = null): array
    {
        return self::mysqldb(self::TABLES['users_table'])->findAll($column);
    }

    public static function getUser(array $data): ?stdClass
    {
        return self::mysqldb(self::TABLES['users_table'])->find($data);
    }

    //POSTS CONCERN
    public static function getAllPosts(array $conditions = [], ?string $column = null, array $columns = [], array $order = []): array
    {
        return (new PostsTable())->getAll($conditions, $column, $columns, $order);
    }

    public static function getPostPaginated(array $order = []): array
    {
        return (new PostsTable())->getPagination($order);
    }

    public static function getPost(array $condition, ?string $column = null, array $columns = []): ?stdClass
    {
        return (new PostsTable())->get($condition, $column, $columns);
    }

    public static function getSearchedPosts(array $columns, array $conditions, array $orders): array
    {
        return (new PostsTable())->searchPosts($columns, $conditions, $orders);
    }

    public static function updatePost(array $condition, array $data): void
    {
        (new PostsTable())->update($condition, $data);
    }

    public static function deletePost(array $condition): void
    {
        (new PostsTable())->delete($condition);
    }

    public static function insertPost(array $data): void
    {
        (new PostsTable())->insert($data);
    }

    //NEWSLETTER CONCERN
    public static function getAllNewsletter(?string $column = null, array $columns = []): array
    {
        return (self::mysqldb(self::TABLES['news'])->findAll($column, $columns));
    }

    public static function getNewsletter(array $condition, ?string $column = null, array $columns = []): ?stdClass
    {
        return self::mysqldb(self::TABLES['news'])->find($condition, $column, $columns);
    }

    public static function insertNewsletter(array $data): void
    {
        self::mysqldb(self::TABLES['news'])->insert($data);
    }

    public static function deleteNewsletter(array $condition): void
    {
        self::mysqldb(self::TABLES['news'])->delete($condition);
    }

    //MESSAGES CONCERN
    public static function getAllMessages(array $condition =[], ?string $column = null, array $columns = [], array $order = []): array
    {
        return (new MessageTable())->getAll($condition, $column, $columns, $order);
    }

    public static function getAMessage(array $condition =[], ?string $column = null, array $columns = []): ?stdClass
    {
        return (new MessageTable())->get($condition, $column, $columns);
    }

    public static function insertMessage(array $data): void
    {
        (new MessageTable())->insert($data);
    }

    public static function updateMessage(array $condition, array $data): void
    {
        (new MessageTable())->update($condition, $data);
    }

    public static function deleteMessage(array $condition): void
    {
        (new MessageTable())->delete($condition);
    }

    //MENU CONCERN
    public static function getAllMenuItem(?string $column = null): array
    {
        return self::sqlitedb('menu', self::TABLES['menu_navbar'])->findAll($column);
    }

    public static function getPageURLs(array $condition, array $joins, array $column = []): array
    {
        return self::sqlitedb('menu', self::TABLES['menu_navbar'])->findJoin($joins, null, $column, $condition);
    }

    public static function getMenuItem(array $data): ?stdClass
    {
        return self::sqlitedb('menu', self::TABLES['menu_navbar'])->find($data);
    }

    public static function insertMenu(array $data, bool $id = false)
    {
        return self::sqlitedb('menu', self::TABLES['menu_navbar'])->insert($data, $id);
    }

    public static function deleteMenu(array $data): void
    {
        self::sqlitedb('menu', self::TABLES['menu_navbar'])->delete($data);
    }

    public static function updateMenu(array $condition, array $data): void
    {
        self::sqlitedb('menu', self::TABLES['menu_navbar'])->update($condition, $data);
    }
    
    //PAGES CONCERN
    public static function getAllPages(?array $condition = [], ?string $column = null, array $columns = [], array $orders = []): array
    {
        return self::sqlitedb('menu', self::TABLES['pages'])->findAll($column, $columns, $condition, $orders);
    }

    public static function getPage(array $conditions, ?string $column = null, array $columns = []): ?stdClass
    {
        return self::sqlitedb('menu', self::TABLES['pages'])->find($conditions, $column, $columns);
    }

    public static function insertPage(array $data): void
    {
        self::sqlitedb('menu', self::TABLES['pages'])->insert($data);
    }

    public static function insertSlug(array $data, bool $inserted = false): int
    {
        return self::sqlitedb('menu', self::TABLES['pages'])->insert($data, $inserted);
    }

    public static function updatePage(array $condition, array $data): void
    {
        self::sqlitedb('menu', self::TABLES['pages'])->update($condition, $data);
    }

    public static function deletePage(array $data): void
    {
        self::sqlitedb('menu', self::TABLES['pages'])->delete($data);
    }

    //BANNIERE SECTION CONCERN
    public static function insertBreadcrumb(array $data): void
    {
        self::sqlitedb('sections', self::TABLES['breadcrumb'])->insert($data);
    }

    public static function getBreadcrumb(array $data, ?string $column = null): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['breadcrumb'])->find($data, $column);
    }

    public static function updateBreadcrumb(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['breadcrumb'])->update($condition, $data);
    }

    //ABOUT SECTION CONCERN
    public static function insertAbout(array $data): void
    {
        self::sqlitedb('sections', self::TABLES['about'])->insert($data);
    }

    public static function getAbout(array $data): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['about'])->find($data);
    }

    public static function updateAbout(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['about'])->update($condition, $data);
    }

    //EXPOSE SECTION CONCERN
    public static function insertExpose(array $data): void
    {
        self::sqlitedb('sections', self::TABLES['expose'])->insert($data);
    }

    public static function getExpose(array $data): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['expose'])->find($data);
    }

    public static function updateExpose(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['expose'])->update($condition, $data);
    }

    //ACCORDION SECTION CONCERN
    public static function insertAccordion(array $data): void
    {
        self::sqlitedb('sections', self::TABLES['acc'])->insert($data);
    }

    public static function getAllAccordion(array $condition): array
    {
        return self::sqlitedb('sections', self::TABLES['acc'])->findAll(null, [], $condition);
    }

    public static function getAccordion(array $data): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['acc'])->find($data);
    }

    public static function updateAccordion(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['acc'])->update($condition, $data);
    }

    public static function deleteAccodrion(array $condition): void
    {
        self::sqlitedb('sections', self::TABLES['acc'])->delete($condition);
    }

    //LEFT EXPOSE SECTION CONCERN
    public static function insertLeftExpose(array $data): void
    {
        self::sqlitedb('sections', self::TABLES['left'])->insert($data);
    }

    public static function getLeftExpose(array $data): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['left'])->find($data);
    }

    public static function updateLeftExpose(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['left'])->update($condition, $data);
    }

    //CONTACT SECTION CONCERN
    public static function insertContact(array $data): void
    {
        self::sqlitedb('sections', self::TABLES['contact'])->insert($data);
    }

    public static function getContact(array $data): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['contact'])->find($data);
    }

    public static function updateContact(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['contact'])->update($condition, $data);
    }

    //TEXT SECTION CONCERN
    public static function insertNewText(array $data): void
    {
        self::sqlitedb('sections', self::TABLES['text'])->insert($data);
    }

    public static function getText(array $data): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['text'])->find($data);
    }

    public static function updateText(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['text'])->update($condition, $data);
    }

    //TABS SECTION CONCERN
    public static function insertTabs(array $data): void
    {
        self::sqlitedb('sections', self::TABLES['tab'])->insert($data);
    }

    public static function getAllTabs(array $condition, ?string $column = null, array $columns = []): array
    {
        return self::sqlitedb('sections', self::TABLES['tab'])->findAll($column, $columns, $condition);
    }

    public static function getTabs(array $data): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['tab'])->find($data);
    }

    public static function updateTabs(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['tab'])->update($condition, $data);
    }

    public static function deleteTab(array $condition): void
    {
        self::sqlitedb('sections', self::TABLES['tab'])->delete($condition);
    }

    //CAROUSEL SECTION CONCERN
    public static function getAllCarousel(?string $column = null, array $columns = [], array $order = []): array
    {
        return self::sqlitedb('sections', self::TABLES['slide'])->findAll($column, $columns, [], $order);
    }

    public static function getCarousel(array $condition, ?string $column = null, array $columns = []): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['slide'])->find($condition, $column, $columns);
    }

    public static function insertCarousel(array $data): void
    {
        self::sqlitedb('sections', self::TABLES['slide'])->insert($data);
    }

    public static function updateCarousel(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['slide'])->update($condition, $data);
    }

    public static function removeCarousel(array $condition): void
    {
        self::sqlitedb('sections', self::TABLES['slide'])->delete($condition);
    }

    //METADATA CONCERN
    public static function getMetadata(array $condition): ?stdClass
    {
        return (new MetadataTable())->get($condition);
    }

    public static function insertMetadata(array $data): void
    {
        (new MetadataTable())->insert($data);
    }

    public static function updateMetadata(array $condition, array $data): void
    {
        (new MetadataTable())->update($condition, $data);
    }

    //FEATURE SECTION CONCERN
    public static function getAllFeature(?string $column = null, array $columns = []): array
    {
        return self::sqlitedb('sections', self::TABLES['feat'])->findAll($column, $columns);
    }

    public static function getFeature(array $condition, ?string $column = null, array $columns = []): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['feat'])->find($condition, $column, $columns);
    }

    public static function insertFeature(array $data): void
    {
        self::sqlitedb('sections', self::TABLES['feat'])->insert($data);
    }

    public static function updateFeature(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['feat'])->update($condition, $data);
    }

    public static function removeFeature(array $condition): void
    {
        self::sqlitedb('sections', self::TABLES['feat'])->delete($condition);
    }

    //CTA SECTION CONCERN
    public static function getCTA(?string $column = null, array $columns = []): array
    {
        return self::sqlitedb('sections', self::TABLES['cta'])->findAll($column, $columns);
    }

    public static function cta(array $condition, ?string $column = null, array $columns = []): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['cta'])->find($condition, $column, $columns);
    }

    public static function insertCTA(array $data): void
    {
        self::sqlitedb('sections', self::TABLES['cta'])->insert($data);
    }

    public static function updateCTA(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['cta'])->update($condition, $data);
    }

    //SERVICES CONCERN
    public static function getAllServices(?string $column = null, array $columns = []): array
    {
        return self::sqlitedb('sections', self::TABLES['service'])->findAll($column, $columns);
    }

    public static function getServices(array $condition, ?string $column = null, array $columns = []): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['service'])->find($condition, $column, $columns);
    }

    public static function insertService(array $data): void
    {
        self::sqlitedb('sections', self::TABLES['service'])->insert($data);
    }

    public static function updateService(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['service'])->update($condition, $data);
    }

    public static function deleteService(array $condition): void
    {
        self::sqlitedb('sections', self::TABLES['service'])->delete($condition);
    }

    //NEWSLETTER CONCERN
    public static function getNewsletterInfos(array $condition, ?string $column = null, array $columns = []): ?stdClass
    {
        return self::sqlitedb('sections', self::TABLES['news'])->find($condition, $column, $columns);
    }

    public static function updateNewsletterInfos(array $condition, array $data): void
    {
        self::sqlitedb('sections', self::TABLES['news'])->update($condition, $data);
    }

    //REASON CONCERN
    public static function getReason(array $conditions): ?stdClass
    {
        return (new ReasonsTable())->get($conditions);
    }

    public static function insertReason(array $data): void
    {
        (new ReasonsTable())->insert($data);
    }
    
    public static function updateReason(array $conditions, array $data): void
    {
        (new ReasonsTable())->update($conditions, $data);
    }

    //WORK CONCERN
    public static function getAllWork(array $conditions): array
    {
        return (new WorkTable())->getAll($conditions);
    }

    public static function getWork(array $conditions): ?stdClass
    {
        return (new WorkTable())->get($conditions);
    }

    public static function insertWork(array $data): void
    {
        (new WorkTable())->insert($data);
    }
    
    public static function updateWork(array $conditions, array $data): void
    {
        (new WorkTable())->update($conditions, $data);
    }

    public static function deleteWork(array $conditions): void
    {
        (new WorkTable())->delete($conditions);
    }

    //PRESENTATIONS CONCERN
    public static function getAllPresentation($conditions): array
    {
        return (new PresentationsTable())->getAll($conditions);
    }

    public static function getPresentation(array $conditions): ?stdClass
    {
        return (new PresentationsTable())->get($conditions);
    }

    public static function insertPresentation(array $data): void
    {
        (new PresentationsTable())->insert($data);
    }
    
    public static function updatePresentation(array $conditions, array $data): void
    {
        (new PresentationsTable())->update($conditions, $data);
    }

    public static function deletePresentation(array $conditions): void
    {
        (new PresentationsTable())->delete($conditions);
    }

    //TITLE CONCERN
    public static function getTitle(array $conditions, array $columns = []): ?stdClass
    {
        return (new TitleTable())->get($conditions, $columns);
    }

    public static function insertTitle(array $data): void
    {
        (new TitleTable())->insert($data);
    }
    
    public static function updateTitle(array $conditions, array $data): void
    {
        (new TitleTable())->update($conditions, $data);
    }

    public static function deleteTitle(array $conditions): void
    {
        (new TitleTable())->delete($conditions);
    }

    //MULTIPLE FEATURES CONCERN
    public static function getMultipleFeatures(array $conditions): array
    {
        return (new MultipleFeatures())->getAll($conditions);
    }

    public static function getMultipleFeature(array $conditions, ?string $column = null): ?stdClass
    {
        return (new MultipleFeatures())->get($conditions, $column);
    }

    public static function insertMultipleFeature(array $data): void
    {
        (new MultipleFeatures())->insert($data);
    }
    
    public static function updateMultipleFeature(array $conditions, array $data): void
    {
        (new MultipleFeatures())->update($conditions, $data);
    }

    public static function deleteMultipleFeature(array $conditions): void
    {
        (new MultipleFeatures())->delete($conditions);
    }

    //PRIVATE METHOD
    private static function mysqldb (string $table): Query
    {
        return new Query(DBConnector::getConnectedToMYSQL(), $table);
    }

    private static function sqlitedb (string $sqlitedb, string $table): Query
    {
        return new Query(DBConnector::getConnectedToSQLITE($sqlitedb), $table);
    }
}