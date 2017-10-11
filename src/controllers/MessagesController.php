<?php

namespace maissoftware\sms\controllers;

use maissoftware\sms\SMS;
use Twilio\Exceptions\TwilioException;
use Yii;
use maissoftware\sms\models\Messages;
use maissoftware\sms\models\MessagesSearch;
//use common\models\User;
use maissoftware\sms\models\User;
use maissoftware\sms\models\PhoneNumber;
use yii\db\Schema;
use yii\db\TableSchema;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \yii\web\Response;
use yii\helpers\Html;
use maissoftware\sms\components\TwilioHelper;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;

/**
 * MessagesController implements the CRUD actions for Messages model.
 */
class MessagesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /*'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'view', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'view', 'index'],
                        'roles' => ['employee']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['guest']
                    ],
                ],
            ],*/
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Messages models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new MessagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['sent_by' =>Yii::$app->user->getId()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Messages model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Messages #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Messages model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Messages();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Messages",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Send',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                //Sets the timezone for the message sent at attribute
                date_default_timezone_set('America/Vancouver');
                $model->sent_at = date('Y-m-d H:i:s');
                //Gets a User where the id is the id in the user field
                $users = User::find()->where(['id' => $model->user_id])->all();
                //Only sends if the user id is not empty
                if($model->user_id != ""){
                    if(sizeof($users) == 1){
                        try{
                            $message = new Messages();
                            $phone = SMS::$phoneColumn;
                            foreach ($users as $user){
                                if(TwilioHelper::checkTableNames(SMS::$phoneTable)){
                                    $table = Yii::$app->db->schema->getTableSchema(SMS::$phoneTable);
                                    $tableColumns = $table->getColumnNames();
                                    $phoneNumbers = TwilioHelper::getPhoneNumbers($user, $tableColumns);
                                    if(sizeof($phoneNumbers) > 0){
                                        //$model->message = 'from send one with phoneNumber > 0';
                                        foreach ($phoneNumbers as $phoneNumber){
                                            //$model->message = 'This is from the phone numbers';
                                            TwilioHelper::sendOne($phoneNumber->phone_number, $model->message);
                                            $message = new Messages();
                                            $message->user_id = $user->id;
                                            $message->message = $model->message;
                                            $message->sent_by = Yii::$app->user->identity->getId();
                                            $message->sent_at = $model->sent_at;
                                            $message->save();
                                        }
                                    }else{
                                        //$model->message = 'from send one with phoneNumber < 0';
                                        TwilioHelper::sendOne($user->$phone, $model->message);
                                        $message = new Messages();
                                        $message->user_id = $user->id;
                                        $message->message = $model->message;
                                        $message->sent_by = Yii::$app->user->identity->getId();
                                        $message->sent_at = $model->sent_at;
                                        $message->save();
                                    }
                                }else{
                                    //$model->message = 'from the user table when there is no phone table';
                                    TwilioHelper::sendOne($user->$phone, $model->message);
                                    $message = new Messages();
                                    $message->user_id = $user->id;
                                    $message->message = $model->message;
                                    $message->sent_by = Yii::$app->user->identity->getId();
                                    $message->sent_at = $model->sent_at;
                                    $message->save();
                                }
                            }
                        }catch (RestException $restException) {
                            $message->save();
                            return [
                                'forceReload' => '#crud-datatable-pjax',
                                'title' => "Create new Message",
                                'content' => '<span class="alert alert-danger">Create Message Failed - Must include a message and a user with a cell number.</span>',
                                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        }
                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Create new Messages",
                            'content'=>'<span class="text-success">Meessage Successfully Sent</span>',
                            'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

                        ];
                    }else{
                        try{
                            //$model->message = 'This is from the group messages';
                            TwilioHelper::sendGroup($users, $model->message);
                        }catch (TwilioException $twilioException) {
                            //$model->save();
                            return [
                                'forceReload' => '#crud-datatable-pjax',
                                'title' => "Create new Messages",
                                'content' => '<span class="alert alert-danger">Create Messages Failed - Must include a message and users with a cell number.</span>',
                                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        }
                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Create new Messages",
                            'content'=>'<span class="text-success">Meessages Successfully Sent</span>',
                            'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                        ];
                    }
                }
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Messages",
                    'content'=>'<span class="alert alert-danger">No Cell Number Found</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Messages",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Send',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Messages model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Messages #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Messages #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Messages #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Messages model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Messages model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Messages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Messages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Messages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
