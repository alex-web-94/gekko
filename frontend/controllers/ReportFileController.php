<?php

namespace frontend\controllers;

use common\queue\ParsingTransactionJob;
use frontend\models\ReportFileForm;
use Yii;
use frontend\models\ReportFileSearch;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ReportFileController implements the CRUD actions for ReportFile model.
 */
class ReportFileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ReportFile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReportFileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReportFile model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ReportFile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        return $this->processForm(new ReportFileForm());
    }


    /**
     * Deletes an existing ReportFile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function processForm(ReportFileForm $model)
    {
        if ($model->load(Yii::$app->getRequest()->getBodyParams())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file) {
                $model->path = 'transactions';
                $path = Yii::getAlias('@secured/' . $model->path);
                $model->ext = $model->file->extension;
                $model->original_name = array_shift(explode('.', $model->file->name));
                do {
                    $model->name = $model->original_name . '-' . md5(microtime() . rand(0, 9999));
                    $file = $path . $model->name . $model->ext;
                } while (file_exists($file));
                $model->status = ReportFileForm::STATUS_NEW;
                FileHelper::createDirectory($path);
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    if ($model->save()) {
                        if ($model->file->saveAs(Yii::getAlias('@secured/') . $model->getFullName())) {
                            $transaction->commit();
                            Yii::$app->queue->push(new ParsingTransactionJob(['report_file' => $model]));
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }
        if ($model->isNewRecord) {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the ReportFile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReportFileForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReportFileForm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
