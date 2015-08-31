<div class="listserver" tyle="padding:0px;width:100%;font-family:Tahoma,Geneva,sans-serif">
    <h2 style="color:#FFF;font-size:1.6em;margin:10px 15px 15px 15px;padding:10px;background-image:linear-gradient(to bottom, #756C75 88%, #756C75 97%)">Your Reminders</h2>
    <?php if(Yii::app()->user->hasFlash('delete-success')): ?>
        <div class="flash-success">
            <?php echo Yii::app()->user->getFlash('delete-success'); ?>
        </div>
    <?php endif; ?>
    <?php if(Yii::app()->user->hasFlash('delete-error')): ?>
        <div class="flash-error">
            <?php echo Yii::app()->user->getFlash('delete-error'); ?>
        </div>
    <?php endif; ?>
    <div class="flash-error" id='msgBox' style='display:none;'></div>
    <table style="margin:0 0px 0 15px;width:97%;">
       <tr style="font-size:1.1em;font-weight:bold;background-color:#E8E8E8;">
           <th style="padding:10px;width:10%;">Reminder Id</th>
           <th style="padding:10px;text-align:center;width: 10%">Created By</th>
           <th style="padding:10px;;text-align:center;width: 10%">Purpose</th>
           <th style="padding:10px;;text-align:center;width:10%;" colspan="2">Landscape</th>
           <th style="padding:10px;text-align:center;width: 10%;">Action</th>
           <th style="padding:10px;text-align:center;width: 10%">Created Date</th>
           <th style="padding:10px;text-align:center;width: 10%">Activate/Deactivate/Delete</th>
      </tr>
          <?php
    $record = 1;
    if($models):
        foreach($models as $model): ?>
          <tr class="">
            <td style="padding:10px;"> <span  title="<?php echo $model->reminder_name;?>" class='hostname'><?php echo $model->reminder_name?></span> </td>
            <td style="padding:10px;"> <span  title="<?php echo $model->created_by;?>" class='hostname'><?php echo $model->created_by?></span> </td>
            <td style="padding:10px;"> <span  title="<?php echo $model->purpose;?>" class='hostname'><?php echo $model->purpose?></span> </td>
            <td style="padding:10px;"> <span  title="<?php echo $model->landscape;?>" class='hostname'><?php echo $model->landscape?></span> </td>
            <td style="padding:10px;"> <span  title="<?php echo $model->action;?>" class='hostname'><?php echo $model->action?></span> </td>
            <td style="padding:10px;text-align:center;width: 15%"><?php echo date('M-d-Y H:i:s',substr($model->createDate,0,10));?> </td>
            <td style="padding:10px;text-align:center;">
                <?php if($model->active == 1):?>
                        <?php if($record > 1): ?>
                                <a class='' onclick="changestatus('<?php echo $model->reminder_name?>',false)" href='#'>Deactivate</a>
                        <?php endif; ?>
                <?php else: ?>
                <a class='' onclick="changestatus('<?php echo $model->reminder_name?>',true)" href='#'>Activate</a>
                <?php endif; ?>
            </td>
            <td style="padding:10px;text-align:center;">
                <?php if($record > 1):?>
                <a class='del' onclick="deleteReminder('<?php echo $model->reminder_name?>')" href='#'>Delete</a>
                <?php endif;?>
            </td>
            <td style="padding:10px;text-align:center;width: 10%">
               <?php if($record == 1):?>
                        <input type='checkbox' disabled=true checked='checked'>
               <?php endif;?>
            </td>
          </tr>
      <?php $record++;
        endforeach;
    else:
    ?>
        <tr>
            <td colspan=8 style="border-color:#fff;padding:10px;text-align:center">You do not have any Reminder yet.</td>
        </tr>
    <?php
    endif;
    ?>
    </table>
    <div class="form row buttons" style="margin: 20px 0px; width: 110px; min-height: 32px;">
        <input type="button" id="btnAddReminder" class='' name="yt1" value="Create New Reminder" style='background-color: #7a0d0d;color: #fff; border-radius: 5px;padding: 5px; width: 130px;'>
    </div>
</div>


<script type="text/javascript">

    $(document).ready(function(){

        $('#btnAddReminder').click(function(){
                window.location.href = '/storage/index.php?r=reminder/addreminder';
        });

    });

    function showSecretReminder(divID){

        $("#dispSecretDiv_"+divID).hide();
        $("#hideSecretDiv_"+divID).show();
        $("#hideSecretReminderDiv_"+divID).show();
    }

    function hideSecretReminder(divID){

        $("#dispSecretDiv_"+divID).show();
        $("#hideSecretDiv_"+divID).hide();
        $("#hideSecretReminderDiv_"+divID).hide();
    }


    function deleteReminder(reminderId){

        $.confirm({
            'title'     : 'Delete Access Reminder',
            'message'   : 'You are about to delete reminder <strong>'+ reminderId  +'</strong>.<br/> <br />WARNING! this action can not be undone.',
            'buttons'   : {
                'Yes'   : {
                    'class'     : 'blue',
                    'action': function(){
                        window.location.href = '/storage/index.php?r=reminder/deletereminder&name=' + reminderId;
                    }
                },
                'No'    : {
                    'class'     : 'gray',
                    'action': function(){return false;} 
                }
            }
        });
    }
</script>
