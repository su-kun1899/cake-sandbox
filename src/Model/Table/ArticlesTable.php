<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * Articles Model
 *
 * @property \App\Model\Table\UsersTable&\App\Model\Table\BelongsTo $Users
 * @property \App\Model\Table\TagsTable&\App\Model\Table\BelongsToMany $Tags
 * @method \App\Model\Table\Article newEmptyEntity()
 * @method \App\Model\Entity\Article newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Article[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Article get($primaryKey, $options = [])
 * @method \App\Model\Entity\Article findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Article patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Article[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Article|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article[]|\App\Model\Table\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Article[]|\App\Model\Table\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Article[]|\App\Model\Table\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Article[]|\App\Model\Table\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findBySlug($slug = null)
 * @method \Cake\ORM\Query findById(int $articleId)
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArticlesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('articles');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsToMany(
            'Tags'
        );
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 191)
//            ->requirePresence('slug', 'create')
            ->notEmptyString('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('body')
            ->allowEmptyString('body');

        $validator
            ->boolean('published')
            ->notEmptyString('published');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['slug']), ['errorField' => 'slug']);
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

    /**
     * Model.beforeSave イベント
     *
     * @param \Cake\Event\EventInterface $event
     * @param \Cake\Datasource\EntityInterface $entity
     * @param \ArrayObject $options
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        /** @var \App\Model\Entity\Article $entity */
        if ($entity->tag_string) {
            $entity->tags = $this->_buildTags($entity->user_id, $entity->tag_string);
        }

        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            // TODO 重複はまだ考慮していない
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    protected function _buildTags($userId, $tagString): array
    {
        // タグをトリミング
        $newTags = array_map('trim', explode(',', $tagString));
        // すべての空のタグを削除
        $newTags = array_filter($newTags);
        // 重複するタグの削減
        $newTags = array_unique($newTags);

        $out = [];
        $query = $this->Tags->find()
            ->where(['Tags.title IN' => $newTags]);

        // 新しいタグのリストから既存のタグを削除
        foreach ($query->extract('title') as $existing) {
            $index = array_search($existing, $newTags);
            if ($index !== false) {
                unset($newTags[$index]);
            }
        }
        // 既存のタグを追加
        foreach ($query as $tag) {
            $out[] = $tag;
        }
        // 新しいタグを追加
        foreach ($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['title' => $tag, 'user_id' => $userId]);
        }

        return $out;
    }

    /**
     * タグの付いた記事を検索する
     *
     * @param \Cake\ORM\Query $query
     * @param array $options
     * @return \Cake\ORM\Query
     */
    public function findTagged(Query $query, array $options): Query
    {
        $columns = [
            'Articles.id',
            'Articles.user_id',
            'Articles.title',
            'Articles.body',
            'Articles.published',
            'Articles.created',
            'Articles.slug',
        ];

        $query = $query
            ->select($columns)
            ->distinct($columns);

        if (empty($options['tags'])) {
            $query->leftJoinWith('Tags')
                ->where(['Tags.title IS' => null]);
        } else {
            $query->innerJoinWith('Tags')
                ->where(['Tags.title IN' => $options['tags']]);
        }

        return $query->group(['Articles.id']);
    }
}
