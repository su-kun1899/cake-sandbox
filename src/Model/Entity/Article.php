<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Article Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string|null $body
 * @property bool $published
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property string $tag_string
 *
 * @property User $user
 * @property Tag[] $tags
 */
class Article extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'title' => true,
        'slug' => false,
        'body' => true,
        'published' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'tags' => true,
        'tag_string' => true,
    ];

    protected function _getTagString(): string
    {
        if (isset($this->_fields['tag_string'])) {
            return $this->_fields['tag_string'];
        }
        if (empty($this->tags)) {
            return '';
        }
        $tags = collection($this->tags);;
        $str = $tags->reduce(
            function ($string, $tag) {
                return $string . $tag->title . ', ';
            },
            ''
        );
        return trim($str, ', ');
    }
}
