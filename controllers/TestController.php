<?php

namespace app\controllers;


use app\models\Product;
use yii\web\Controller;


class TestController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
      $product = new Product();
      $product->id = 1;
      $product->name = 'NVIDIA GEFORCE 1060';
      $product->category = 'видеокарты';
      $product->price = 25000;
      $content = 'Hello from test controller / action index';
      return $this->render('index', [
        'content' => $content,
        'product' => $product
        ]);
    }

}
