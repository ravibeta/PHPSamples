<?php

class UserVar extends CActiveRecord
{

     public static function model($className=__CLASS__)
     {
         return parent::model($className);
     }

     public function tableName()
     {
         return 'my_user_vars';
     }

     
}

?>
