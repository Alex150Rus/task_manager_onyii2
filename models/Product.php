<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string $price
 * @property int $created_at
 */
class Product extends ActiveRecord
{

    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CREATE = 'create';

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['name'],
            self::SCENARIO_UPDATE => ['price'],
            self::SCENARIO_CREATE => ['name'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'created_at'], 'required'],
            [['created_at'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['name'], 'filter', 'filter' => function($value){
              return strip_tags(trim($value));
            }],
            [['price'], 'integer', 'min' => 1, 'max' => '999']

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'created_at' => 'Создан',
        ];
    }
}
