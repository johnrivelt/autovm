<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\Server;
use app\modules\admin\filters\OnlyAdminFilter;
use app\models\searchs\searchServer;

class ServerController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyAdminFilter::className(),
        ];
    }
    
    public function actionIndex()
    {   
        $searchModel = new searchServer();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionCreate()
    {
        $model = new Server;
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Your new server has been created'));
				
                return $this->refresh();
            }
        }
        
        return $this->render('create', compact('model'));
    }
    
    public function actionEdit($id)
    {
        $model = Server::findOne($id);
        
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Server has been edited'));
				
                return $this->refresh();
            }
        }
        
        return $this->render('edit', compact('model'));
    }
    
    public function actionDelete()
    {
        if (($data = Yii::$app->request->post('data')) && is_array($data)) {
            foreach ($data as $id) {
                Server::findOne($id)->delete();
            }
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }
}