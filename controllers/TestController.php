<?php

namespace app\controllers;


use app\models\Task;
use yii\BaseYii;
use yii\db\Query;
use yii\helpers\Url;
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
        //true добавляет к относительному адресу текущий домен и получится абсолютный адрес
        //Url::to(['task/veiw', 'id' => 1], true);
        //Url::to('task/1/');

        // вот так можно посмотреть элиасы _end(\Yii::$aliases); getAlias('@runtime/logs/pay.log') - расшифрует алиас
        // setAlias() - установит элиас

        _end(\Yii::t('yii', 'File upload failed.'));

        //\Yii::$app->cache;
       /* $task = new Task();
        $task -> description ='описание';
        $task -> title = 'название';
        $task -> save();*/

        //$data = \Yii::$app->db->createCommand('SELECT * FROM product')->queryAll();
        //_end($task);
      //return \Yii::$app->test->run();

      return $this->render('index');
    }

    /**
     * Inserts data to 'user' table.
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionInsert(){
        $db = \Yii::$app->db;
        $data = $db->createCommand('SELECT * FROM task') ->queryAll();

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

        /*$db->createCommand()->batchInsert('task',
            ['title','description', 'creator_id', 'updater_id', 'created_at', 'updated_at'],[
            ['JS','стать крутым разработчиком', 1, 1, time(), time()],
            ['NodeJS','практиковаться в Node.JS', 2, 2, time(), time()],
            ['CodeWars','периодически заглядывать на портал и выполнять упражнения', 3, 3, time(), time()]]
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

        $data = (new Query()) -> from('user')->where(['id' => 1])->one();

        $data2 = (new Query()) ->from('user')->where(['>', 'id', 1])->orderBy('username')->all();

        $data3 = (new Query()) ->from('user')->count();

        $data4 = (new Query()) ->from('task')->innerJoin('user','task.creator_id = user.id')->all();

       VarDumper::dump([$data, $data2, $data3, $data4], 5, true);

       exit;
    }
}
