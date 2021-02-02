<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Article;
use App\Model\Table\ArticlesTable;
use Cake\Datasource\ResultSetInterface;
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
     * Index method
     */
    public function index()
    {
        $articles = $this->paginate($this->Articles);
        $this->set(compact('articles'));
    }

    /**
     * View method
     * @param null|string $slug
     */
    public function view($slug = null)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }

    /**
     * Add method
     */
    public function add(): ?Response
    {
        $article = $this->Articles->newEmptyEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            // TODO user_id の決め打ちは一時的
            $article->user_id = 1;

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }

        $tags = $this->Articles->Tags->find('list');

        $this->set('tags', $tags);
        $this->set('article', $article);

        return null;
    }

    /**
     * Edit method
     *
     * @param string $slug
     * @return Response|null
     */
    public function edit(string $slug): ?Response
    {
        $article = $this->Articles
            ->findBySlug($slug)
            ->contain('Tags')
            ->firstOrFail();
        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article'));
        }

        $tags = $this->Articles->Tags->find('list');

        $this->set('tags', $tags);
        $this->set('article', $article);

        return null;
    }

    /**
     * Delete method
     *
     * @param $slug
     * @return Response|null
     */
    public function delete($slug): ?Response
    {
        $this->request->allowMethod(['post', 'delete']);

        /** @var Article $article */
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));
            return $this->redirect(['action' => 'index']);
        }

        return null;
    }

    public function tags(...$tags)
    {
        $articles = $this->Articles->find(
            'tagged',
            [
                'tags' => $tags
            ]
        );

        $this->set(
            [
                'articles' => $articles,
                'tags' => $tags
            ]
        );
    }
}
