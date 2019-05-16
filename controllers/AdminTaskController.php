<?php

namespace app\controllers;

use app\models\tables\TaskStatuses;
use app\models\tables\Users;
use Yii;
use app\models\tables\Task;
use app\models\filters\TasksFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminTaskController implements the CRUD actions for Task model.
 */
class AdminTaskController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TasksFilter();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $creatorId = Task::getCreatorId();
        $responsibleId = Task::getResponsibleId();
        $statusId = Task::getStatusId();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'creatorId' => $creatorId,
            'responsibleId' => $responsibleId,
            'statusId' => $statusId,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $creatorId = Task::getCreatorId();
        $responsibleId = Task::getResponsibleId();
        $statusId = Task::getStatusId();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'creatorId' => $creatorId,
            'responsibleId' => $responsibleId,
            'statusId' => $statusId,
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $usersList = Users::getUsersList();
        $statusesList = TaskStatuses::getList();
        return $this->render('create', [
            'model' => $model,
            'usersList' => $usersList,
            'statusesList' => $statusesList,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $usersList = Users::getUsersList();
        return $this->render('update', [
            'model' => $model,
            'usersList' => $usersList,
        ]);
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index.php' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
