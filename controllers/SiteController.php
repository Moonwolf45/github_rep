<?php

namespace app\controllers;


use app\models\User;
use Yii;
use yii\caching\TagDependency;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
        $result = Yii::$app->cache->getOrSet(['repositories'], function () {
            $users = User::find()->asArray()->all();

            $res = [];
            $curl = curl_init();
            foreach ($users as $user) {
                curl_setopt($curl, CURLOPT_URL, 'https://api.github.com/users/' . $user['username'] . '/repos?sort=updated&direction=desc');
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
        }, 600, new TagDependency(['tags' => ['list_user']]));

        return $this->render('index', compact('result'));
    }

    /**
     * @return string
     */
    public function actionView() {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('view', compact('dataProvider'));
    }

    /**
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionCreate() {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            TagDependency::invalidate(Yii::$app->cache, 'list_user');
            return $this->redirect(['view']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Addition model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            TagDependency::invalidate(Yii::$app->cache, 'list_user');
            return $this->redirect(['view']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        TagDependency::invalidate(Yii::$app->cache, 'list_user');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Addition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
