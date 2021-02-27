<?php
declare(strict_types=1);

namespace App\Policy;

use Cake\ORM\Query;

/**
 * Articles policy
 */
class ArticlesTablePolicy
{
    /**
     * @param \Authorization\IdentityInterface|\Authentication\IdentityInterface|null $user
     * @param \Cake\ORM\Query $query
     * @return \Cake\ORM\Query
     */
    public function scopeIndex($user, Query $query)
    {
        return $query->where(['Articles.user_id' => $user->getIdentifier()]);
    }
}
