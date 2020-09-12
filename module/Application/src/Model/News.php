<?php
namespace Application\Model;

use Application\Model\Base\TableModel;

class News extends TableModel
{
    public const TABLE_NAME = 'news';

    public string $title;
    public ?string $text_overview;
    public ?string $img_src;
    public ?string $img_description;
    public ?string $img_author;
    public ?string $content;
    public ?string $author;
    public string $datetime;
    public string $s_id;
    public string $s_url;

    protected array $fillableFields = [
        'id',
        'title',
        'text_overview',
        'img_src',
        'img_description',
        'img_author',
        'content',
        'author',
        'datetime',
        's_id',
        's_url'
    ];
}