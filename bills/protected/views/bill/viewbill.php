<style>
	.clsInfo thead { display:block; }
        .clsInfo tbody {width: 100%;}
        .clsInfo th{width: 100%;}
        .clsInfo td{ color: #000;word-break: break-all;}
	.head { width: 20%;}
	.data {width: 50%;}
</style>
<div>
<?php if(Yii::app()->user->hasFlash('flash-error')): ?>
	<div class="flash-error" style='width: 150%;margin-left: 25px;margin-top: 150px;'>
		<?php echo Yii::app()->user->getFlash('flash-error'); ?>
	</div>
<?php else: ?>
<?php
//$dt =  new DateTime($model->created);
$dtDisplay = '';// $dt->format('M d Y, H:i:s');
?>
<h4 style='color:#FFF;font-size:12px;margin:10px -1px 15px;padding:10px;background:#756C75;'>Bill <?php echo '<strong><i>'.$model->name.'</i></strong>';?> detail information</h4>
        
<div style='background: none repeat scroll 0 0 #e8e8e8; padding: 10px 15px; margin: 2px; width: 732px;height: 532px; overflow: auto;'>
	<table class='clsInfo'>
		<tbody style=""> 
			<tr>
				    <td class='head'> <?php echo "Name"?> : </td>
				    <td class='data'><?php echo $model->name; ?></td>
			</tr>
                        <tr>
                                    <td class='head'> <?php echo "Compute Cloud Running Linux/UNIX"?> : </td>
                                    <table>
                                    <tr>
                                    <td class='head'>
                           <?php echo "$0.00 per Linux instance-hour (or partial hour)"?> :
                                    </td>
                                    <td class='head'>
                           <?php echo $model->totalCpu*30*8." hrs" ?> 
                                    </td>
                                    <td class='head'>
                           <?php echo "$0.00"?> 
                                    </td>
                                     </tr>
                                    </table>
                        </tr>
                        <tr>
                                    <td class='head'> <?php echo "Block Storage"?> : </td>
                                    <table>
                                    <tr>
                                    <td class='head'>
                           <?php echo "$0.00 per GB-month of General Purpose"?> :
                                    </td>
                                    <td class='head'>
                           <?php echo $model->storageSize*30*8." hrs" ?> 
                                    </td>
                                    <td class='head'>
                           <?php echo "$0.00"?> 
                                    </td>
                                     </tr>
                                    </table>
                        </tr> 
                        <tr>
                                    <td class='head'> <?php echo "CT to be collected"?> : </td>
                                    <td class='data'><?php echo "$0.00" ?></td>
                        </tr>
                        <tr>
                                    <td class='head'> <?php echo "GST to be collected"?> : </td>
                                    <td class='data'><?php echo "$0.00" ?></td>
                        </tr>
                        <tr>
                                    <td class='head'> <?php echo "US Sales Tax to be collected"?> : </td>
                                    <td class='data'><?php echo "$0.00" ?></td>
                        </tr>
                        <tr>
                                    <td class='head'> <?php echo "VAT to be collected"?> : </td>
                                    <td class='data'><?php echo "$0.00" ?></td>
                        </tr>
		</tbody>
	</table>
</div>
<?php endif;?>
