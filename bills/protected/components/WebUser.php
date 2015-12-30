<?php

class WebUser extends CWebUser {

    private $_user;
 
    public function login($identity, $duration=0)
    {
        parent::login($identity, $duration);
    }

    function getUserID(){
        $user = $this->loadUser(Yii::app()->user->id);
        return $user->id;
    }

    function isAdmin() {
        return true;
    }

    function getUserName() {
        $user = $this->loadUser(Yii::app()->user->id);
        if(!$user) {
           header('Location: '.Yii::app()->request->baseUrl.'?r=user/ssologin');
           exit;
        }
        return $user->fullname;
    }

    protected function loadUser($id=null)
    {
        if($this->_user===null)
        {
            if($id!==null)
                $this->_user=User::model()->findByAttributes(array('email'=>$id));
        }
        return $this->_user;
    }

    protected function loadUserVar($name)
    {
	$var = Yii::app()->db->createCommand()->select("value")->from("my_user_vars")->where('user_id=:id AND name=:name', array(':id'=>$this->_user->id, ':name'=>$name))->queryScalar();
        return $var;
    }
}
?>
