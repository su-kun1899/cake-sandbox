<?php

use App\Model\Entity\Article;
use App\Model\Entity\Tag;
use App\View\AppView;
use Cake\Collection\CollectionInterface;

/**
 * @var AppView $this
 * @var Article $article
 * @var Tag[]|CollectionInterface $tags
 */
?>
<h1>記事の追加</h1>
<?php
echo $this->Form->create($article);
echo $this->Form->control('title');
echo $this->Form->control('body', ['rows' => 3]);
echo $this->Form->control('tag_string', ['type' => 'text']);
echo $this->Form->button(__('Save Article'));
echo $this->Form->end();
?>
