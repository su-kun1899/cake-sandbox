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
     *
     * @return Response|null|void Renders view
     */
    public function index(): ?Response
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $articles = $this->paginate($this->Articles);

        $this->set(compact('articles'));
    }
}
