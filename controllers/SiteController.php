<?php

namespace app\controllers;

use app\models\Form;
use app\models\Photos;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Photos();
        if (Yii::$app->request->isGet) {

            return $this->render('index', ['model' => $model, 'errors' => $model->getErrors()]);
        }
        return $this->refresh('#error');
    }

    /**
     * @return Response|string
     */
    public function actionToLoadPhoto()
    {
        $model = new Form();
        if (Yii::$app->request->isPost) {
            $model->photos = UploadedFile::getInstances($model, 'photos');
            if ($model->upload() && !$model->hasErrors()) {
                return $this->refresh('#success');
            } else {

                return $this->render('to-load-photo', ['model' => $model, 'errors' => $model->getErrors()]);
            }
        }
        return $this->render('to-load-photo', ['model' => $model]);
    }

    /**
     * @return Response|string
     */
    public function actionGetPhotos()
    {
        $model = new Photos();
        if (Yii::$app->request->isGet) {
            if (isset(Yii::$app->request->getQueryParams()['id'])) {
                $id = (int)Yii::$app->request->getQueryParams()['id'];
                $oActiveRecord = $model->getPhotosById($id);

                $arrActiveRecord['id'] = $oActiveRecord->id;
                $arrActiveRecord['unique_name_photo'] = $oActiveRecord->unique_name_photo;
                $arrActiveRecord['name_file'] = $oActiveRecord->name_file;
                $arrActiveRecord['date'] = $oActiveRecord->date;
                $arrActiveRecord['time'] = $oActiveRecord->time;

                return json_encode($arrActiveRecord);
            }
            return json_encode($model->getPhotosBySortDateTime(), JSON_UNESCAPED_UNICODE);
        }
        return $this->refresh('#error');
    }

    /**
     * @return Response|string
     */
    public function actionViewPhotos()
    {
        $model = new Photos();
        if (Yii::$app->request->isGet) {

            return json_encode($model->getGroupedPhoto(), JSON_UNESCAPED_UNICODE);


//            return json_encode($model->getPhotos(), JSON_UNESCAPED_UNICODE);
        }
        return $this->refresh('#error');
    }
}
