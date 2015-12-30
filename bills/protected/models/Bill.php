<?php
class Bill extends CActiveRecord
{
    public $name;
    public $totalCpu;
    public $memorySize;
    public $storageSize;
    public $status;
    public $search_field;
    public $search_value;
    public $id;
    public $url;
    public $size_unit = 'GB';
    public $modified;
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'unread'; // This is not read
    }
    public function relations()
    {
        return array(
               'users'=>array(self::BELONGS_TO, 'User', array('created_by'=>'id')),
        );
    }

    public function rules()
    {
	$str = 'name';
	if($this->getScenario() == 'addbill')
		$str .= ', totalCpu, memorySize, storageSize';
        return array(
                   array($str, 'required'),
                   array('name', 'safe'),
                   array('name', 'length', 'max' => 80),
                   array('name', 'safe', 'on' => 'search')
           );
        
    }

    public function attributeLabels()
    {
       return array(
           'name'=>'Name','totalCpu'=>'Total CPU','memorySize'=>'Memory Size in GB','storageSize' => 'Storage Size in GB');
    }

    public function search() {
    }

}
?>
