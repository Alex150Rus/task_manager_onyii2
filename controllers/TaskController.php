<?php

namespace app\controllers;

use Yii;
use app\models\Task;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{

    public $defaultAction = 'my';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        //@ - означает аутентифицированные пользователи
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionMy()
    {
        $query = Task::find()->byCreator(Yii::$app->user->id);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('my', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionShared()
    {
        $query = Task::find()
            ->byCreator(Yii::$app->user->id)
            ->innerJoinWith(Task::RELATION_TASK_USERS);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('shared', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionAccessed()
    {
        $query = Task::find()
            ->innerJoinWith(Task::RELATION_TASK_USERS)
            ->where(['user_id' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->pagination->pageSize = 5;

        return $this->render('accessed', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Exception if user is not creator and not in the accessed list
     */
    public function actionView($id)
    {
        $userId = Yii::$app->user->id;
        $task = $this->findModel($id);

        if ($userId !== $task->creator->id && !array_key_exists($userId, $task->getSharedUsers()->indexBy('id')->all())){
            throw new Exception('Только создатель или допущенный пользователь может смотреть задачу');
        }

        $dataProvider = new ActiveDataProvider([

            // вернуть AR, который выведет все элементы task_users, гду task_id - id текущей модели
            'query' => $task->getTaskUsers(),
        ]);

        return $this->render('view', [
            'model' => $task,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'create success');
            return $this->redirect(['task/my']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Exception if user in not a creator of task
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $userId = Yii::$app->user->id;

        if ($userId !== $this->findModel($id)->creator->id){
            throw new Exception('Только создатель может изменять задачу');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'update success');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws Exception if user is not creator
     * @throws NotFoundHttpException if model(Task) hasn't been found
     */
    public function actionDelete($id)
    {
        $userId = Yii::$app->user->id;
        $model = $this->findModel($id);

        if (!$model) {
            throw new NotFoundHttpException();
        }

        if ($userId !== $model->creator->id){
            throw new Exception('Только создатель может удалить задачу');
        }

        $model->unlinkAll(Task::RELATION_TASK_USERS, true);
        $model->delete();
        Yii::$app->session->setFlash('success', 'delete success');

        return $this->redirect(['task/my']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
