<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Table\ArticlesTable;
use Authorization\IdentityInterface;
use Cake\ORM\Query;

/**
 * Articles policy
 */
class ArticlesTablePolicy
{
    /**
     * @param IdentityInterface|\Authentication\IdentityInterface|null $user
     * @param Query $query
     * @return Query
     */
    public function scopeIndex($user, Query $query)
    {
        return $query->where(['Articles.user_id' => $user->getIdentifier()]);
    }
}
