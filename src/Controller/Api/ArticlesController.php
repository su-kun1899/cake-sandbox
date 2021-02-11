<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Model\Entity\Article;
use App\Model\Table\ArticlesTable;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Articles Controller
 *
 * @property ArticlesTable $Articles
 * @method Article[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        // TODO API の基底クラスでやってもよさそう
        $this->loadComponent('RequestHandler');
    }
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): ?Response
    {
        parent::beforeFilter($event);

        // TODO 現状 API の認証はなし
        $this->Authentication->addUnauthenticatedActions(['index']);

        // TODO API の基底クラスでやってもよさそう
        $this->RequestHandler->renderAs($this, 'json');

        return null;
    }

    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function index(): void
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $articles = $this->paginate($this->Articles);

        // TODO 基底クラスに何か用意してあげるとよさそう
        $this->set(compact('articles'));
        $this->set('_serialize', ['articles']);
    }
}
