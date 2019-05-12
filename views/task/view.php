<?php

use app\models\Task;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $dataProvider app\models\Task */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'My Tasks', 'url' => ['task/my']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-view">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
      <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
      <?= Html::a('Delete', ['delete', 'id' => $model->id], [
          'class' => 'btn btn-danger',
          'data' => [
              'confirm' => 'Are you sure you want to delete this item?',
              'method' => 'post',
          ],
      ]) ?>
  </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'creator_id',
            'updater_id',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'shared to',
                'value' => function (\app\models\TaskUser $model) {
                    return join(', ', \app\models\User::find()->where(['id' => $model->user_id])->select('username')->column());
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        $icon = \yii\bootstrap\Html::icon('trash');
                        return Html::a($icon, ['task-user/delete', 'id' => $model->id], [
                            'data' => [
                                'confirm' => 'Are you sure you want to delete task from this user?',
                                'method' => 'post',
                            ],
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>

</div>
