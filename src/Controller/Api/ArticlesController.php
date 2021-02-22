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
     * @inheritDoc
     */
    public function beforeRender(EventInterface $event)
    {
        // TODO 基底クラスに何か用意してあげるとよさそう
        // viewVar を json レスポンス用に serialize する
        parent::beforeRender($event);
        $viewVars = array_keys($this->viewBuilder()->getVars());
        $this->viewBuilder()->setOption('serialize', $viewVars);
    }

    /**
     * Json レスポンスを強制的にオブジェクト形式にする
     * 空の場合に配列形式になるのを避けるための利用を想定
     */
    protected function enableForceObject()
    {
        // TODO 基底クラスに何か用意してあげるとよさそう
        // 空の場合でもオブジェクトとして返したいときはオプション指定が必要
        $this->viewBuilder()->setOption('jsonOptions', JSON_FORCE_OBJECT);
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

        $this->set(compact('articles'));
    }

    /**
     * View method
     * @param int|string $id 記事ID
     */
    public function view($id): void
    {
        $article = $this->Articles->get($id);
        $this->set(compact('article'));
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

        $article = $this->Articles->get($article->id);
        $this->set(compact('article'));
    }

    /**
     * Edit method
     *
     * @param string $id
     */
    public function edit(string $id): void
    {
        $article = $this->Articles
            ->get($id);

        $article = $this->Articles->patchEntity($article, $this->request->getData());
        if (!$this->Articles->save($article)) {
            $errors = $article->getErrors();
            $this->set('errors', compact('errors'));
            $this->response = $this->response->withStatus(400);

            return;
        }

        $this->set('article', $article);
    }

    /**
     * Delete method
     *
     * @param int|string $id 記事ID
     */
    public function delete($id): void
    {
        $article = $this->Articles->get($id);;
        $this->Articles->delete($article);
    }
}
