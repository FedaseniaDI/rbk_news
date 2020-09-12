<?php
namespace Application\Model\Base;

use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\TableGateway\TableGatewayInterface;
use RuntimeException;

class Table {
    protected TableGatewayInterface $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getTableGateway(): TableGatewayInterface
    {
        return $this->tableGateway;
    }

    public function fetchAll(): ResultSet
    {
        return $this->tableGateway->select();
    }

    public function getRow(int $id): TableModel {
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveRow(TableModel $model)
    {
        $data = $model->getArrayData();

        if ($model->id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getRow($model->id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update row with identifier %d; does not exist',
                $model->id
            ));
        }

        $this->tableGateway->update($data, ['id' => $model->id]);
    }

    public function deleteRow(int $id): void
    {
        $this->tableGateway->delete(['id' => $id]);
    }
}