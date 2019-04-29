<?php

namespace app\controllers;

use app\models\Task;
use app\models\User;
use Yii;
use app\models\TaskUser;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskUserController implements the CRUD actions for TaskUser model.
 */
class TaskUserController extends Controller
{
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
                    'unshare-all' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Creates a new TaskUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *  @throws ForbiddenHttpException if the model cannot be found or creator_id !== logged in user
     * @return mixed
     */
    public function actionCreate($taskId)
    {
        $model = Task::findOne($taskId);
        if (!$model || $model->creator_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }

        $model = new TaskUser();
        $model->task_id = $taskId;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Share success');
            return $this->redirect(['task/my', 'id' => $model->id]);
        }
        $users = User::find()
            ->where(['<>', 'id', Yii::$app->user->id])
            ->select('username')
            ->indexBy('id')
            ->column();

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Unshares Task from shared users .
     * If unsharing is successful, the browser will be redirected to the 'view' page.
     * @throws ForbiddenHttpException if the model cannot be found or creator_id !== logged in user
     * @return mixed
     */
    public function actionUnshareAll($taskId)
    {
        $model = Task::findOne($taskId);
        if (!$model || $model->creator_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }

        $model->unlinkAll(Task::RELATION_SHARED_USERS, true);
        Yii::$app->session->setFlash('success', 'Unshared successfully');

        return $this->redirect(['task/shared']);
    }

     /**
     * Deletes an existing TaskUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found or task creator is not current user
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $creator = (join(', ',
            \app\models\Task::find()->where(['id' => $model->task_id])->select('creator_id')->column()));
        if (!$model || $creator != Yii::$app->user->id) {
            throw new ForbiddenHttpException('задача создана не вами или не существует');
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'deleted successfully');

        return $this->redirect(['task/my']);
    }

    /**
     * Finds the TaskUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TaskUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TaskUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
