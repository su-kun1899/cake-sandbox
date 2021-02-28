<?php
declare(strict_types=1);

namespace App\Policy;

use Authentication\IdentityInterface;
use Cake\ORM\Query;

/**
 * Articles policy
 */
class ArticlesTablePolicy
{
    /**
     * @param \Authentication\IdentityInterface $user ユーザー
     * @param \Cake\ORM\Query $query クエリ
     * @return \Cake\ORM\Query
     */
    public function scopeIndex(IdentityInterface $user, Query $query)
    {
        return $query->where(['Articles.user_id' => $user->getIdentifier()]);
    }
}
