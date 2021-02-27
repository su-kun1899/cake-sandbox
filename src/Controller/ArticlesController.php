<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 * @method \App\Model\Entity\Article[]|\App\Controller\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{
    /**
     * Index method
     */
    public function index()
    {
        $query = $this->Articles->find();
        $this->Authorization->applyScope($query);

        $articles = $this->paginate($query);
        $this->set(compact('articles'));
    }

    /**
     * View method
     *
     * @param null|string $slug
     */
    public function view($slug = null)
    {
        $article = $this->Articles
            ->findBySlug($slug)
            ->contain('Tags')
            ->firstOrFail();
        $this->set(compact('article'));
    }

    /**
     * Add method
     */
    public function add(): ?Response
    {
        $article = $this->Articles->newEmptyEntity();
        $article->user_id = $this->Authentication->getIdentityData('id');
        $this->Authorization->authorize($article);

        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

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
     * @return \Cake\Http\Response|null
     */
    public function edit(string $slug): ?Response
    {
        $article = $this->Articles
            ->findBySlug($slug)
            ->contain('Tags')
            ->firstOrFail();

        $this->Authorization->authorize($article);

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
     * @return \Cake\Http\Response|null
     */
    public function delete($slug): ?Response
    {
        $this->request->allowMethod(['post', 'delete']);

        /** @var \App\Model\Entity\Article $article */
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->Authorization->authorize($article);

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
                'tags' => $tags,
            ]
        );

        $this->set(
            [
                'articles' => $articles,
                'tags' => $tags,
            ]
        );
    }
}
