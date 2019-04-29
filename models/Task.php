<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $creator_id
 * @property int $updater_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $creator
 * @property User $updater
 * @property TaskUser[] $taskUsers
 * @const RELATION_CREATOR string creator
 * @const RELATION_TASK_USERS string taskUsers
 * @const RELATION_ACCESSED_USERS string accessedUsers
 * @mixin TimestampBehavior
 * @mixin BlameableBehavior
 */
class Task extends \yii\db\ActiveRecord
{

    const RELATION_CREATOR = 'creator';
    const RELATION_TASK_USERS = 'taskUsers';
    const RELATION_ACCESSED_USERS = 'accessedUsers';
    const RELATION_SHARED_USERS = 'sharedUsers';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
            [['updater_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updater_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            //короткий способ описания поведения без настрйоки
            ['class' => TimestampBehavior::class],

            //позволяет заполнить поля creator_id и updater_id
            ['class' => BlameableBehavior::class,
             'createdByAttribute' => 'creator_id',
             'updatedByAttribute' => 'updater_id',
            ],
        ];

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'creator_id' => 'Creator ID',
            'updater_id' => 'Updater ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updater_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskUsers()
    {
        return $this->hasMany(TaskUser::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSharedUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->via(self::RELATION_TASK_USERS);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessedUsers()
    {
        return $this->hasMany(Task::className(), ['id' => 'task_id'])->via(self::RELATION_TASK_USERS);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\TaskQuery(get_called_class());
    }
}
