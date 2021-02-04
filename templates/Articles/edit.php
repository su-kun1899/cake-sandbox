<?php

use App\Model\Entity\Article;
use App\Model\Entity\Tag;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Article $article
 * @var Tag[] $tags
 */
?>
<h1>記事の編集</h1>
<?php
echo $this->Form->create($article);
echo $this->Form->control('user_id', ['type' => 'hidden']);
echo $this->Form->control('title');
echo $this->Form->control('body', ['rows' => 3]);
echo $this->Form->control('tag_string', ['type' => 'text']);
echo $this->Form->button(__('Save Article'));
echo $this->Form->end();
?>
