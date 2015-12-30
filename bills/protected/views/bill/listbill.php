<style>
.listserver td{
	padding-top: 5px;
}
</style>
<?php 
if($userflag == 1){
    $heading = 'My Bills';
}else{
    $heading = 'Bills List';
}
?>
<div class="listserver" style="padding:0px;width:100%;font-family:Tahoma,Geneva,sans-serif">
    <h2 style="color:#FFF;font-size:1.6em;margin:10px 15px 0px 15px;padding:10px;background-image:linear-gradient(to bottom, #756C75 88%, #756C75 97%)"><?php echo $heading;?></h2>
    <?php if(Yii::app()->user->hasFlash('flash-success')): ?>
        <div class="flash-success" style='margin-top: 10px;'>
            <?php echo Yii::app()->user->getFlash('flash-success'); ?>
        </div>
    <?php endif; ?>
    <?php if(Yii::app()->user->hasFlash('flash-error')): ?>
        <div class="flash-error" style='margin-top: 10px;'>
            <?php echo Yii::app()->user->getFlash('flash-error'); ?>
        </div>
    <?php endif; ?>
    <div class="form row buttons" style="margin: 10px 0px; width: 110px; min-height: 32px;">
        <input type="button" id="btnViewBills" class='' name="yt1" value="View bills" style='background-color: #7a0d0d;color: #fff; border-radius: 5px;padding: 5px; width: 130px;'>
    </div>
</div>
<script>
     var time = new Date().getTime();
     $(document.body).bind("mousemove keypress", function(e) {
         time = new Date().getTime();
     });

     function refresh() {
         if(new Date().getTime() - time >= 60000)
             window.location.reload(true);
         else
             setTimeout(refresh, 10000);
     }

     setTimeout(refresh, 10000);
</script>
<script type="text/javascript">

    $('#btnViewBills').click(function(){
        window.location.href = <?php echo "'".Yii::app()->request->baseUrl."'";?> + '/index.php?r=bill/view';
    });
</script>
