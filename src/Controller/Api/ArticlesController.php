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
        $action = $this->request->getParam('action');
        $this->Authentication->addUnauthenticatedActions([$action]);

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

    /**
     * Add method
     */
    public function add(): void
    {
        $article = $this->Articles->newEntity($this->request->getData());

        if (!$this->Articles->save($article)) {
            $errors = $article->getErrors();
            $this->set('errors', compact('errors'));
            $this->set('_serialize', 'errors');
            $this->response = $this->response->withStatus(400);

            return;
        }

        $this->set(compact('article'));

        // オブジェクトとして返したいときはオプション指定が必要
        $this->set('_jsonOptions', JSON_FORCE_OBJECT);
        $this->set('_serialize', 'article');
    }
}
