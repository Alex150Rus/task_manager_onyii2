<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Task;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Displays test page.
     *
     *
     * @throws \yii\db\Exception
     */
    public function actionTest()
    {
       /* $user= new User();
        $user->username = 'Jenya';
        $user->password_hash = 'gksjzhgfkz';
        $user->auth_key ='sdfhksjazgf';
        $user->creator_id = 8;
        $user->updater_id = 8;
        $user->created_at = time();
        $user->updated_at = time();
        $user->save();*/

       /* $user = User::findOne(4);
        $task = new Task();
        $task->title = 'yii2';
        $task->description = 'сдать ДЗ 5';
        $task->created_at;
        $task -> link (Task::RELATION_CREATOR, $user);

        $user = User::findOne(4);
        $task = new Task();
        $task->title = 'yii2';
        $task->description = 'получить оценку за ДЗ 5';
        $task->created_at;
        $task -> link (Task::RELATION_CREATOR, $user);

        $user = User::findOne(5);
        $task = new Task();
        $task->title = 'yii2';
        $task->description = 'помочь другу с ДЗ 5';
        $task->created_at;
        $task -> link (Task::RELATION_CREATOR, $user);*/

        /*$users = User::find()->with(User::RELATION_TASKS)->asArray()->all();
        _end($users);*/

        /*$usersJoin = User::find()->joinWith(User::RELATION_TASKS)->asArray()->all();
        _end($usersJoin);*/

        //Дадим 6-ому пользователю доступ к третьей задаче:
        $user = User::findOne(5);
        $task = Task::findOne(3);
        $task -> link (Task::RELATION_ACCESSED_USERS, $user);

        $tasks = Task::find()->joinWith(Task::RELATION_TASK_USERS)->asArray()->indexBy('id')->all();
        _end($tasks);


        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);

    }
}
