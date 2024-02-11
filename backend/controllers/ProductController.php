<?php

namespace backend\controllers;

use backend\models\CategoryForm;
use backend\models\ProductForm;
use backend\models\search\CategorySearch;
use backend\models\search\ProductSearch;
use backend\models\UserForm;
use common\models\Category;
use common\models\Product;
use common\models\ProductTag;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
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

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex(): string
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new Product();
        //$model->setScenario('create');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Category model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {//echo "<pre>";print_r($this->findModel($id));exit;
        //$model = new CategoryForm();
        //echo "<pre>";print_r($this->findModel($id));
        //$model = new ProductForm();
        //$model->setModel($this->findModel($id));
        $model = $this->findModel($id);
        $model->tags = $model->getTags();
        //$model = $this->findModel($id);
        //echo "<pre>";print_r($model->tags); exit;
        //$model->load(Yii::$app->request->post());
        //$model->save();echo "<pre>";print_r($model);exit;
        // старое изображение, которое надо удалить, если загружено новое
        $old = $model->image;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // если отмечен checkbox «Удалить изображение»
            if ($model->remove) {
                // удаляем старое изображение
                if (!empty($old)) {
                    $model::removeImage($old);
                }
                // сохраняем в БД пустое имя
                $model->image = null;
                // чтобы повторно не удалять
                $old = null;
            } else { // оставляем старое изображение
                $model->image = $old;
            }
            // загружаем изображение и выполняем resize исходного изображения
            $model->upload = UploadedFile::getInstance($model, 'image');
            if ($new = $model->uploadImage()) { // если изображение было загружено
                // удаляем старое изображение
                if (!empty($old)) {
                    $model::removeImage($old);
                }
                // сохраняем в БД новое имя
                $model->image = $new;
            }
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id): Response
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Product
    {
        if (($model = Product::findOne($id, true)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');

    }
}
