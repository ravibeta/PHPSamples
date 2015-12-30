<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<!-- blueprint CSS framework -->
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bills.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tooltip.css" media="screen, projection" />
	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jqueryslidemenu.css" />
        <link media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/simplemodal/basic/css/demo.css" type="text/css">
        <link media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/simplemodal/basic/css/basic.css" type="text/css">
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tablesorter/jquery-latest.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jqueryslidemenu.js"></script> 
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/simplemodal/basic/js/basic.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/simplemodal/basic/js/jquery.simplemodal.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>//js/tablesorter/blue/style.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container main_wrapper" id="page">

<!--	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div>--><!-- header -->


        <div class="row_6">
                <div class="company_logo">
                        <img class="image" src="<?php echo Yii::app()->params['site_url'];?>/images/logonone.png" height="64" width="39">
                        <h2>
                                <span style="font-weight: normal;" class="span">IT Cloud Physical&nbsp;</span>
                        </h2>
                </div>
                <p>
                        <span style="font-family: Tahoma, Geneva, sans-serif; position: relative; top: 10px; " class="span5">
                                <span style="color: rgb(245, 240, 245); font-size: 23px; line-height: 23px;top:-5px;left:20px;position:relative; ">Billing Service</span>
                        </span>
                </p>
                <p style="position:relative;text-align:right;color:#fff;font-family: Tahoma, Geneva, sans-serif;margin-right:10px;"><?php echo 'Welcome, '.Yii::app()->user->getUserName(); ?>
			<?php  /*
				$userId = Scalr_Session::getInstance()->getUserId();
				$user = Scalr_Account_User::init();
			        $user->loadById($userId);	
				echo $user->fullname;
                            */
			?>
		</p>
        </div>

	<div id="myslidemenu" class=" jqueryslidemenu" style='width: 100%'>
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
                                array('label'=>'My Account', 'items' => array(//array('label'=>'My Servers','url'=>array('/user/listserver')),
                                                                              array('label'=>'My Bills','url'=>array('/bill/list')))),
				array('label'=>'Documentation', 'url'=>'https://documentation_in_near_future/','linkOptions'=>array('target'=>'_new')),
			),
		)); ?>
	</div><!-- mainmenu -->
        <div class='content'>
	<?php echo $content; ?>
        </div>
	<div class="clear"></div>
        <div class="copyright">
                <i class="paragraph2">
                     <span style="font-family: 'Trebuchet MS', sans-serif; " class="span6">© 2015  All Rights Reserved. </span>
                     <span class="privacy_policy privacy_policyspanCopy">
                         <span style="font-family: 'Trebuchet MS', sans-serif;">Privacy Policy.</span>
                     </span>
                </i>
        </div>
<!--
	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div>--><!-- footer -->

</div><!-- page -->
<script type='text/javascript'>
	$("#outer").click(function(){
		var option = $("#outer").val();
		if(option == 'advanced')
			window.location.href = '/?p=advanced';
		else if(option == 'public')
			window.location.href = '/amp';
	});
</script>
</body>
<!--<script src="//code.jquery.com/jquery-1.11.2.js"></script>-->
<!--<script src="//code.jquery.com/jquery-1.11.2.js"></script>-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/exporting.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/simplemodal/basic/js/jquery.simplemodal.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/simplemodal/basic/js/basic.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/piechart.js"></script>
<!--
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/exporting.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootbox.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.confirm.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/piechart.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
-->
</html>
