<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application\Controller;

use Application\Model\NewsTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class NewsController extends AbstractActionController
{
    private NewsTable $table;

    public function __construct(NewsTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        $news = $this->table->getTableGateway()->select(function($select) {
            $select->order('datetime desc');
        });

        return new ViewModel([
            'news' => $news
        ]);
    }

    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $news = $this->table->getRow($id);
        return new ViewModel([
            'news' => $news
        ]);
    }

    public function syncAction()
    {

        if($this->table->syncNews()) {
            $this->flashMessenger()->addSuccessMessage('Новости успешно обновлены');
        }
        return $this->redirect()->toRoute('home');
    }
}
