<?php
/*
Controller : ReminderController
*/
class ReminderController extends Controller
{
    public function actions()
    {
        return array(
                'page'=>array(
                        'class'=>'CViewAction',
                )
        );
    }

    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
                if(Yii::app()->request->isAjaxRequest)
                        echo $error['message'];
                else
                        $this->render('error', $error);
        }
    }

    public function actionDeleteReminder()
    {
        $reminderId = $_REQUEST['name'];
        if(!$reminderId){
            Yii::app()->user->setFlash('delete-error','You are trying to delete reminder that does not exists.');
        }
        else
        {
            $params = array();
            $params['accessReminder'] = $reminderId;
            $arrUrl = $this->getApiUrl('delete');
            $url = $arrUrl[1].'?'.http_build_query($params);
            $client = new RESTClient();
            $client->initialize(array('http_user' => Yii::app()->params['adminAPICred']['username'],
                'http_pass' => Yii::app()->params['adminAPICred']['password'], 'http_auth' => 'any', 'server' => $arrUrl[0]));

            $reminderlistObj = $client->delete($url,array(),'json');

            $responseStatus = $client->info();
            if($responseStatus['http_code'] == 400){
                Yii::app()->user->setFlash('delete-error','Invalid Reminder.');
            }else if($responseStatus['http_code'] == 200){
                Yii::app()->user->setFlash('delete-success','Reminder '.$reminderId.' removed successfully.');
            }
        }
        $this->redirect('/app/index.php?r=reminder/listreminder');
            }


    public function actionAddReminder()
    {
        $params = array();
        $params['userId'] = stristr(Yii::app()->user->id, '@', true);
        $params['groupId'] = 'appteam';
        $arrUrl = $this->getApiUrl('add');
        $url = $arrUrl[1].'?'.http_build_query($params);

        $client = new RESTClient();
        $client->initialize(array('http_user' => Yii::app()->params['adminAPICred']['username'],
                'http_pass' => Yii::app()->params['adminAPICred']['password'], 'http_auth' => 'any', 'server' => $arrUrl[0]));

        $reminderlistObj = $client->put($url,array(),'json');
        $responseStatus = $client->info();

        if($responseStatus['http_code'] == 400){
                Yii::app()->user->setFlash('delete-error','User does not exist.');
        }else if($responseStatus['http_code'] == 403){
                Yii::app()->user->setFlash('delete-error','Reached maximum number of reminders allowed.');
        }else{
            Yii::app()->user->setFlash('delete-success','New reminder created successfully.');
        }
        $this->redirect('/app/index.php?r=reminder/listreminder');
    }


    public function actionListReminder()
    {
        $arrUrl = $this->getApiUrl('list');
        $params = array();
        $params['userId'] = stristr(Yii::app()->user->id, '@', true);
        $params['groupId'] = 'appteam';

        $client = new RESTClient();
        $client->initialize(array('http_user' => Yii::app()->params['adminAPICred']['username'],
                'http_pass' => Yii::app()->params['adminAPICred']['password'], 'http_auth' => 'any', 'server' => $arrUrl[0]));

        $reminderlistObj = $client->get($arrUrl[1], $params, 'json');

        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.plugin.js');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.countdown.js');
        Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery.confirm.css');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.confirm.js');
        $this->render('listreminder', array(
         'models' => $reminderlistObj
        ));
    }
    
    private function getApiUrl($action){

        $endPointArray = Yii::app()->params['reminderUrl'];
        $apiServerUrl = $endPointArray['url']['San Jose'].':'.$endPointArray['port']['admin'].'/';

        switch($action){

                case 'list':
                        $apiUrl = 'user/reminders/list';
                break;

                case 'add':
                        $apiUrl = 'user/reminders';
                break;

                case 'status':
                        $apiUrl = 'user/reminders/status';
                break;

                case 'delete':
                        $apiUrl = 'user/reminders';
                break;

                default:
                        $apiUrl = 'user/reminders';
                break;

        }
        return array($apiServerUrl, $apiUrl);
    }

}
?>
