<?php
require_once  __DIR__ . '/../../../../scalr.net-trunk/app/src/prepend.inc.php';
class UserController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
        /*
	 * This is the action to handle external exceptions.
	 */
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


	/**
	 * Displays the login page
	 */
	public function actionSSOLogin()
	{
        echo "Hello";
        $as = new SimpleSAML_Auth_Simple('default-sp');
        if (!$as->isAuthenticated()) {
            $this->response->setRedirect('/rajamani/bills/');
        }
        $attributes = $as->getAttributes();
        $username = $attributes['uid'][0] . '@none.com';
        $userIdentity = new UserIdentity($username, null);
        $userIdentity->authenticate();
        if ($userIdentity->errorCode == UserIdentity::ERROR_USERNAME_INVALID) {
            $this->redirect('/request.php?r=rajamani/bills');
        } else if ($userIdentity->errorCode == UserIdentity::ERROR_NONE) {
            Yii::app()->user->login($userIdentity, 3600 * 24 * 30);
            if (array_key_exists('redirectto', $_REQUEST) && $_REQUEST['redirectto'] !== "") {
                $this->redirect($_REQUEST['redirectto']);
                return;
            } else {
                $this->redirect('/rajamani/bills/index.php?r=bill/list');
            }
        } else {
            header('Location: /request.php');
            exit;
        }
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
?>
