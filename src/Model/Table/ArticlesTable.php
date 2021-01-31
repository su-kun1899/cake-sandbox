<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Article;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * Articles Model
 *
 * @property UsersTable&BelongsTo $Users
 * @property TagsTable&BelongsToMany $Tags
 *
 * @method Article newEmptyEntity()
 * @method Article newEntity(array $data, array $options = [])
 * @method Article[] newEntities(array $data, array $options = [])
 * @method Article get($primaryKey, $options = [])
 * @method Article findOrCreate($search, ?callable $callback = null, $options = [])
 * @method Article patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Article[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method Article|false save(EntityInterface $entity, $options = [])
 * @method Article saveOrFail(EntityInterface $entity, $options = [])
 * @method Article[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method Article[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method Article[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method Article[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @method Query findBySlug($slug = null)
 *
 * @mixin TimestampBehavior
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
            'Tags',
            [
                'joinTable' => 'article_tags',
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

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
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
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
     * @param EventInterface $event
     * @param EntityInterface $entity
     * @param ArrayObject $options
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            // TODO 重複はまだ考慮していない
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }
}
