<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

class TesteController extends Controller {
	public function actionIndex() {
		return $this->render('index', [
			'data'=>date('d/m/Y H:i:s') 
		]);
	}
}