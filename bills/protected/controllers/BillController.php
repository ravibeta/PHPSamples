<?php
/*
Controller : BillController
*/
require_once dirname(__FILE__).'/../components/CURL.php';
class BillController extends Controller
{
    private $ssh_key_path;

    public function init(){
    }

    public function actions(){

        return array(
                'page'=>array(
                        'class'=>'CViewAction',
                )
        );
    }

    public function actionError(){

        if($error=Yii::app()->errorHandler->error)
        {
                if(Yii::app()->request->isAjaxRequest)
                        echo $error['message'];
                else
                        $this->render('error', $error);
        }
    }
    /* get token from oauth for Group calls */


    private function executeAPI($method, $url, $params="", $headers=array()){

        $result = $this->curlExec($method, rtrim(MESOS_API_URL2,'/')."/".$url,$params, $headers);
        $obj = $result['obj'];
        $statusCode = $result['statusCode'];

        if($statusCode == 200 || $statusCode == 201 || $statusCode == 204){
                return array('success'=>1, 'status_code'=>$statusCode,'message'=>'success','data'=>$obj);
        }else{
                if ($obj == null) {$obj = array("message"=>"Request failed.");}
                $errmsg = serialize($obj);
                $msgJson = json_decode($obj);
                if ($msgJson != null && property_exists($msgJson, 'message')){
                     $errmsg = $msgJson->message;
                }
                $errmsg = ''.$statusCode.':'.$errmsg;
                Yii::app()->user->setFlash('flash-error','Service is unavailable!!'.$errmsg);
                return array('success'=>0, 'status_code'=>$statusCode,'data'=>json_encode($obj), 'message'=>$errmsg);
        }
    }

    public function actionList($username=null, $password=null, $project=null){
        echo "Hello";
        $model = new Bill();
        $model->totalCpu = 4;
        $model->storageSize = 80;
        $viewData = array();
        array_push($viewData, $model);

        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.plugin.js');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.countdown.js');
        Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery.confirm.css');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.confirm.js');
        $this->render('listbill', array(
            'model' => $model,'userflag'=>1,'viewData'=>$viewData,'cntRecord'=>count($viewData),
        ));
   }

    private function sendResponse($response){

        header('Content-Type: application/json');
        $jsonData = json_encode($response);
        echo $jsonData;
        exit;
    }


   private function curlExec($method, $url, $params = "", $headers=array()){
         $data_string = $params;
         if( !function_exists("curl_init") &&
             !function_exists("curl_setopt") &&
             !function_exists("curl_exec") &&
             !function_exists("curl_close") ) {
              Yii::app()->user->setFlash('flash-error','Service is unavailable!!');
              return array('obj' => null, 'statusCode'=>500);
         }
         $ch = curl_init($url);
         if ($ch == FALSE) {
            Yii::app()->user->setFlash('flash-error','Service is unavailable!!');
            return array('obj' => null, 'statusCode'=>500);
         }
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
         if (strtoupper($method) == 'POST' || strtoupper($method) == 'PUT'){
             curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
         }
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $token = array('auth'=>'Some_Token');// $this->getToken();
         $ldap = explode("@",Yii::app()->user->id);
         $ldapId = $ldap[0];
         $optHeader = array(
                    'Authorization: '.$token['auth'],
          );
        foreach ($headers as $key => $value){
             array_push($optHeader, $key.": ".$value);
        }
        if (array_key_exists('Content-type', $headers) != true) {
            array_push($optHeader, 'Content-type: application/json');
        }
         if (strtoupper($method) == 'POST' || strtoupper($method) == 'PUT'){
             curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
             array_push($optHeader, 'Content-length: '.strlen($data_string));
         }
         Yii::log('Executing '.strtoupper($method).' on url='.$url.' with params='.serialize($params).' and headers='.serialize($headers), 'info', 'mesos');
         curl_setopt($ch, CURLOPT_HTTPHEADER, $optHeader);
          $obj = curl_exec($ch);
          $statusCode = 500;
            $info = curl_getinfo($ch);
            $statusCode = intval($info['http_code']);
          curl_close($ch);
         Yii::log('Execution returned statusCode='.serialize($statusCode).' and data='.serialize($obj),'info', 'mesos');
          return array('obj' => $obj, 'statusCode'=>$statusCode);
   }

  public function actionView()
  {
        $model = new Bill();
        $model-name="Openstack";
        $model->totalCpu = 4;
        $model->storageSize = 80;
        $viewData = array();
        array_push($viewData, $model);

        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.plugin.js');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.countdown.js');
        Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery.confirm.css');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.confirm.js');
        $this->render('viewbill', array(
            'model' => $model,
        ));
 }
}
?>
