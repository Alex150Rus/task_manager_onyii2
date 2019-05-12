<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accessed Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
      <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) ?>
  </p>

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
            'created_at:datetime',
            'updated_at:datetime',
            //связь через рилэйшн Creator, который возвращает модель User, из которой берём поле username
            [
                'label' => 'Creator',
                'value' => function (\app\models\Task $model) {
                    return Html::a(Html::encode($model->creator->username),
                        \yii\helpers\Url::to(['user/view', 'id' => $model->creator->id], true));
                },
                'format' => 'raw'

            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
