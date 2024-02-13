<?php

declare(strict_types=1);

namespace backend\controllers;

use backend\models\search\ProductSearch;
use common\models\Product;
use common\models\ProductTag;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;


class ProductController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $model->tags = $model->getTags();

        $oldImage = $model->image;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->remove) {
                if ($oldImage) {
                    $model::removeImage($oldImage);
                }
                $model->image = null;
                $oldImage = null;
            } else {
                $model->image = $oldImage;
            }

            $model->upload = UploadedFile::getInstance($model, 'image');
            $newImage = $model->uploadImage();
            if ($newImage) {
                if (!empty($oldImage)) {
                    $model::removeImage($oldImage);
                }
                $model->image = $newImage;
            }
            $model->save();

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            ProductTag::deleteAll('product_id = :productId', [':productId' => $model->id]);
            $model->delete(); // you need it if you have restrict relations in your db
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Product
    {
        if (($model = Product::findOne($id, true)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');

    }
}
