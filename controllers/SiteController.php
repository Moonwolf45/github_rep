<?php

namespace app\controllers;


use Yii;
use yii\web\Controller;

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

        $result = Yii::$app->cache->getOrSet(['repositories'], function () use ($users) {
            $res = [];
            $curl = curl_init();
            foreach ($users as $user) {
                curl_setopt($curl, CURLOPT_URL, 'https://api.github.com/users/' . $user . '/repos?sort=updated&direction=desc');
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'content-type: application/json',
                    'accept: application/vnd.github.v3+json',
                    'User-Agent: Moonwolf45',
                ));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                $repository = json_decode(curl_exec($curl));
                if (!empty($repository)) {
                    $res = array_merge($repository, $res);
                }
            }
            curl_close($curl);

            uasort($res, function ($a, $b) {
                $lower_a = strtotime($a->updated_at);
                $lower_b = strtotime($b->updated_at);

                if ($lower_a == $lower_b) {
                    return 0;
                }
                return ($lower_a < $lower_b) ? 1 : -1;
            });

            return array_slice($res, 0, 10);
        }, 600);

        return $this->render('index', compact('result'));
    }

}
