<?php

namespace app\controllers;


use app\models\Product;
use yii\db\Query;
use yii\helpers\VarDumper;
use yii\web\Controller;


class TestController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {

        $data = \Yii::$app->db->createCommand('SELECT * FROM product')->queryAll();
        _end($data);
      //return \Yii::$app->test->run();

      /*$product = new Product();
      $product->id = 1;
      $product->name = 'NVIDIA GEFORCE 1060';
      $product->category = 'видеокарты';
      $product->price = 25000;
      $content = 'Hello from test controller / action index';*/
      return $this->render('index', [
        'content' => $content,
        'product' => $product
        ]);
    }

    /**
     * Inserts data to 'user' table.
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionInsert(){
        $db = \Yii::$app->db;
        $data = $db->createCommand('SELECT * FROM user') ->queryAll();

        /*$db->createCommand()->insert('user', [
            'username' => 'Alex',
            'password_hash' => 'dhsgfsjh',
            'auth_key' => '123',
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => '',
            'updated_at' => '',
        ])->execute();*/

        /*$db->createCommand()->insert('user', [
            'username' => 'Boris',
            'password_hash' => 'dlslskfjs',
            'auth_key' => '125',
            'creator_id' => 2,
            'updater_id' => 2,
            'created_at' => time(),
            'updated_at' => time(),
        ])->execute();*/

        /*$db->createCommand()->insert('user', [
            'username' => 'Viktor',
            'password_hash' => 'ejdsjfl',
            'auth_key' => '126',
            'creator_id' => 3,
            'updater_id' => 3,
            'created_at' => time(),
            'updated_at' => time(),
        ])->execute();*/

       /* $db->createCommand()->batchInsert('user',
            ['username','password_hash', 'auth_key', 'creator_id', 'updater_id', 'created_at', 'updated_at'],[
            ['Vasya','dsjshfk','156', 4, 4, time(), time()],
            ['Olya','djhdskf','166', 5, 5, time(), time()],
            ['Katya','dfdsslfdk','178', 6, 6, time(), time()]]
        )->execute();*/

        return _end($data);
    }

    /**
     * Selects data from 'user' table where id = 1 and id > 1 ordered By 'username'
     *
     * @return
     * @throws \yii\db\Exception
     */
    public function actionSelect() {

        $dbConnection1 = new Query();
        $data = $dbConnection1->from('user')->where(['id' => 1])->one();

        $dbConnection2 = new Query();
        $data2 = $dbConnection2->from('user')->where(['>', 'id', 1])->orderBy('username')->all();

        $dbConnection3 = new Query();
        $data3 = $dbConnection3->from('user')->count();


        /*Пока ничего не выведет, так как таблица task не заполнена
         * $dbConnection4 = new Query();
        $data4 = $dbConnection4->from('task')->innerJoin('user','creator_id = user_id')->all();*/

       VarDumper::dump([$data, $data2, $data3, $data4], 5, true);

       exit;
    }
}
