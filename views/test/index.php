<?php
  /* @var $this yii\web\View */
  /* @var $content string */
  /* @var $product \app\models\Product */

echo "$content<br>";

echo \yii\widgets\DetailView::widget(['model' => $product]);