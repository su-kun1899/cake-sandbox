<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Article;
use Authorization\IdentityInterface;

/**
 * Article policy
 */
class ArticlePolicy
{
    /**
     * Check if $user can add Article
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\Article $article The article.
     * @return bool
     */
    public function canAdd(?IdentityInterface $user, Article $article)
    {
        return $user->getOriginalData()->id === $article->user_id;
    }

    /**
     * Check if $user can edit Article
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Article $article The article.
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Article $article)
    {
        return $user->getOriginalData()->id === $article->user_id;
    }

    /**
     * Check if $user can delete Article
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Article $article The article.
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Article $article)
    {
        return $user->getOriginalData()->id === $article->user_id;
    }
}
