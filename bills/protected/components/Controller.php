<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
    public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
    public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
    public $breadcrumbs=array();

    /**
     * Global send mail method
     * @param type $to
     * @param type $arrMail
     * @param type $data
     */
    public function sendMail($to, $arrMail, $data) {
        $mail = new YiiMailer();
        $mail->setFrom(Yii::app()->params['email']['address'], Yii::app()->params['email']['name']);
        $mail->setTo($to);
        if (!empty($arrMail['cc'])) {
            $mail->setCc($arrMail['cc']);
        }
        $env = Yii::app()->params['environment'] == 'DEV' ? ' DEV' : '';
        $subject = empty($arrMail['subject']) ? 'Adobe IT Cloud BareMetal' : $arrMail['subject'].$env;
        $mailTemplate = empty($arrMail['view']) ? 'blank' : $arrMail['view'];
        $mail->setSubject($subject);
        $mail->setView($mailTemplate);
        $mail->setData($data);
        $mail->send();
    }
}
