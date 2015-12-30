<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */

        public $user; 
	public function authenticate()
	{
                $user = User::model()->findByAttributes(array('email'=>$this->username));
		if($user == null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else {
		    $this->errorCode=self::ERROR_NONE;
                    $this->user = $user;
                }
		return $this->errorCode;
	}
}
