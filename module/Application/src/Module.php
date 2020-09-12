<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Application\Model\Base\TableModel;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;

class Module implements ConfigProviderInterface
{
    public function getConfig() : array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function createTableGateway($container, TableModel $model): TableGateway
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype($model);
        return new TableGateway($model->getTableName(), $dbAdapter, null, $resultSetPrototype);
    }

    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                Model\NewsTable::class => function($container) {
                    $tableGateway = $container->get(Model\NewsTableGateway::class);
                    return new Model\NewsTable($tableGateway);
                },
                Model\NewsTableGateway::class => function ($container) {
                    return $this->createTableGateway($container, new Model\News);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\NewsController::class => function($container) {
                    return new Controller\NewsController(
                        $container->get(Model\NewsTable::class)
                    );
                },
            ],
        ];
    }
}
