<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        $users = ['Moonwolf45', 'warrence', 'kartik-v', 'Innovotel', 'nadzif', 'wrt54gl', 'KartikVD23', 'kartikaVina13',
                  'atalargo', 'oki2a24'];

//        $repositories = Yii::$app->cache->getOrSet(['repositories'], function () use ($users) {

            $repositories = [];
//        }, 600);

        return $this->render('index', compact('repositories'));
    }

}
