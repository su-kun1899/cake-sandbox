<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Tag'), ['action' => 'edit', $tag->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Tag'), ['action' => 'delete', $tag->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tag->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Tags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Tag'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tags view content">
            <h3><?= h($tag->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $tag->has('user') ? $this->Html->link($tag->user->id, ['controller' => 'Users', 'action' => 'view', $tag->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($tag->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($tag->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($tag->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($tag->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Article Tags') ?></h4>
                <?php if (!empty($tag->article_tags)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Article Id') ?></th>
                            <th><?= __('Tag Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($tag->article_tags as $articleTags) : ?>
                        <tr>
                            <td><?= h($articleTags->article_id) ?></td>
                            <td><?= h($articleTags->tag_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'ArticleTags', 'action' => 'view', $articleTags->article_id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'ArticleTags', 'action' => 'edit', $articleTags->article_id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'ArticleTags', 'action' => 'delete', $articleTags->article_id], ['confirm' => __('Are you sure you want to delete # {0}?', $articleTags->article_id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
