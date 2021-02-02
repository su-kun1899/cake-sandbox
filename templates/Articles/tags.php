<?php

use App\Model\Entity\Article;
use App\Model\Entity\Tag;
use App\View\AppView;
use Cake\Collection\CollectionInterface;

/**
 * @var AppView $this
 * @var Article[]|CollectionInterface $articles
 * @var Tag[]|CollectionInterface $tags
 */
?>
<h1>
    Articles tagged with
    <?= $this->Text->toList(h($tags), 'or') ?>
</h1>

<section>
    <?php foreach ($articles as $article): ?>
        <article>
            <h4><?= $this->Html->link(
                    $article->title,
                    ['controller' => 'Articles', 'action' => 'view', $article->slug]
                ) ?></h4>
            <span><?= h($article->created) ?></span>
        </article>
    <?php endforeach; ?>
</section>
