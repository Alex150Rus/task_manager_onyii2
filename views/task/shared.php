<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Shared Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

  <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Title',
                'value' => function (\app\models\Task $model) {
                    return Html::a(Html::encode($model->title),
                        \yii\helpers\Url::to(['task/view', 'id' => $model->id], true));
                },
                'format' => 'raw',
            ],
            'description:ntext',
            //список пользователей, которым мы расшарили задачу
            [
                'label' => 'Shared to',
                'value' => function (\app\models\Task $model) {
                    return join(', ', $model->getSharedUsers()->select('username')->column());
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {unshare}',
                'buttons' => [
                    'unshare' => function ($url, $model, $key) {
                        $icon = \yii\bootstrap\Html::icon('remove');
                        return Html::a($icon, ['task-user/unshare-all', 'taskId' => $model->id],[
                            'data' => [
                                'confirm' => 'Are you sure you want to unshare task from all users?',
                                'method' => 'post',
                            ],
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
