<?php


namespace app\components;


use yii\base\Component;

/**
* @property $test string
*/

class TestServiceComponent extends Component
{
    public $test = 'Test Service Component';

    public function run() {
      return $this->test;
    }
}