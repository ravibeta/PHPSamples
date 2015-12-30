<div style="display:none;height:30px;text-align:right;width:100%;"><a href='#' id='newServer'>Can not find right server?</a></div>
			<div class="welcome_text">
				<h1>Welcome to Adobe IT Cloud - Cluster Service</h1>
                                <img class="welcome_text_img" alt="image" src="<?php echo Yii::app()->request->baseUrl.'/images/logonone.png';?>">
				<p>
                                        <span class="span1">Welcome to Billing Service. This portal enables you to
                        automatically generate bills for your resources.
                                </P>
			</div>
    <div class="form row buttons" style="margin: 10px 0px; width: 110px; min-height: 32px;">
        <input type="button" id="btnListBills" class='' name="yt1" value="List Bills" style='background-color: #7a0d0d;color: #fff; border-radius: 5px;padding: 5px; width: 130px;'>
    </div>

<script type="text/javascript">

$(document).ready(function(){

        var emptyHmtl = '<p style="float: left;margin-left: 390px; margin-top: 80px;"></p>';
    $('#btnListBills').click(function(){
        window.location.href = <?php echo "'".Yii::app()->request->baseUrl."'";?>+'/index.php?r=bill/list';
    });

});

</script>
