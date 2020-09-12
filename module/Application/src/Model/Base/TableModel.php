<?php
namespace Application\Model\Base;

class TableModel extends Model
{
    public const TABLE_NAME = '';
    public int $id = 0;

    public function getTableName(): string
    {
        return static::TABLE_NAME;
    }
}