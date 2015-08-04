
          
          <div class="row">
              <div class="col-xs-12 col-sm-12">
                  <a class="btn btn-primary" href="<?=base_url()?>projects/update/<?=$project->id;?>" data-toggle="mainmodal" data-target="#mainModal"><?=$this->lang->line('application_edit_this_project');?></a>
                  <?php if($project->sticky == 0){ ?>
        				<a href="<?=base_url()?>projects/sticky/<?=$project->id;?>" class="btn btn-primary hidden-xs"><i class="fa fa-star"></i> <?=$this->lang->line('application_add_to_quick_access');?></a>
        			<?php }else{ ?>
        				<a href="<?=base_url()?>projects/sticky/<?=$project->id;?>" class="btn btn-primary hidden-xs"><i class="fa fa-star-o"></i> <?=$this->lang->line('application_remove_from_quick_access');?></a>
        			<?php } ?>
                  
                  
                  <?php if(!empty($project->tracking)){ ?>
                  <!--<span class="tracking-counter pull-right"><?=$time_spent;?></span>-->
				<a href="<?=base_url()?>projects/tracking/<?=$project->id;?>" class="btn btn-danger pull-right"><i class="fa fa-time"></i> <?=$this->lang->line('application_stop_timer');?></a>
			<?php }else{ ?>
				<!-- <span class="tracking-counter pull-right proj_timer" data-timestamp="1385132831"></span> -->
				<a href="<?=base_url()?>projects/tracking/<?=$project->id;?>" class="btn btn-success pull-right"><i class="fa fa-time"></i> <?=$this->lang->line('application_start_timer');?></a>
				
			<?php } ?>
              </div>
          </div>
          <div class="row">
              
              <div class="col-xs-12 col-sm-12">
                  <h1><span class="nobold">#<?=$project->reference;?></span> - <?=$project->name;?></h1>
                  <p class="truncate description"><?=$project->description;?></p>
                  <div class="progress tt" title="<?=$project->progress;?>%">
                    <div class="progress-bar <?php if($project->progress== "100"){ ?>done<?php } ?>" role="progressbar" aria-valuenow="<?=$project->progress;?>"  aria-valuemin="0" aria-valuemax="100" style="width: <?=$project->progress;?>%;"></div>
                  </div>
              </div>
</div><div class="row">
              <div class="col-xs-12 col-sm-12">
            <div class="table-head"><?=$this->lang->line('application_project_details');?></div>
                <div class="subcont">
                  <ul class="details col-xs-12 col-sm-6">
                    <li><span><?=$this->lang->line('application_project_id');?>:</span> <?=$project->reference;?></li>
                    <li><span><?=$this->lang->line('application_category');?>:</span> <?=$project->category;?></li>
                    <li><span><?=$this->lang->line('application_client');?>:</span> <?php if(!isset($project->company->name)){ ?> <a href="#" class="label label-default"><?php echo $this->lang->line('application_no_client_assigned'); }else{ ?><a class="label label-success" href="<?=base_url()?>clients/view/<?=$project->company->id;?>"><?php echo $project->company->name;} ?></a></li>
				<li><span><?=$this->lang->line('application_assigned_to');?>:</span> <?php foreach ($project->project_has_workers as $workers):?> <a class="label label-info" style="padding: 2px 5px 3px;"><?php echo $workers->user->firstname." ".$workers->user->lastname;?></a><?php endforeach;?> <a href="<?=base_url()?>projects/assign/<?=$project->id;?>" class="label label-info tt" style="padding: 2px 5px 3px;" title="<?=$this->lang->line('application_assign_to');?>" data-toggle="mainmodal"><i class="fa fa-plus"></i></a></li>
				
                  </ul>
                  <ul class="details col-xs-12 col-sm-6"><span class="visible-xs divider"></span>
                    <li><span><?=$this->lang->line('application_start_date');?>:</span> <?php  $unix = human_to_unix($project->start.' 00:00'); echo date($core_settings->date_format, $unix);?></li>
				<li><span><?=$this->lang->line('application_deadline');?>:</span> <?php  $unix = human_to_unix($project->end.' 00:00'); echo date($core_settings->date_format, $unix);?></li>
				<li><span><?=$this->lang->line('application_time_spent');?>:</span> <?=$time_spent;?> <a href="<?=base_url()?>projects/timer_reset/<?=$project->id;?>" class="tt" title="<?=$this->lang->line('application_reset_timer');?>"><i class="fa fa-refresh"></i></a></li>
				<li><span><?=$this->lang->line('application_created_on');?>:</span> <?php  echo date($core_settings->date_format.' '.$core_settings->date_time_format, $project->datetime); ?></li>
				</ul>
                  <br clear="both">
                </div>
               </div>

            </div>
            <div class="row">
               <div class="col-xs-12 col-sm-12">
            <div class="table-head"><?=$this->lang->line('application_tasks');?> <span class=" pull-right"><a href="<?=base_url()?>projects/tasks/<?=$project->id;?>/add" class="btn btn-primary" data-toggle="mainmodal"><?=$this->lang->line('application_add_task');?></a></span></div>
                <div class="subcont">
                  <ul class="todo">
                    	<?php 
				$count = 0;
				foreach ($project->project_has_tasks as $value):  $count = $count+1; ?>

				    <li class="<?=$value->status;?> priority<?=$value->priority;?>"><a href="<?=base_url()?>projects/tasks/<?=$project->id;?>/check/<?=$value->id;?>" class="ajax-silent task-check"></a>
				    	<input name="form-field-checkbox" class="checkbox-nolabel task-check" type="checkbox" data-link="<?=base_url()?>projects/tasks/<?=$project->id;?>/check/<?=$value->id;?>" <?php if($value->status == "done"){echo "checked";}?>/>
				    	<span class="lbl"> <p class="truncate name"><?=$value->name;?></p></span>
				    	<span class="pull-right">
                                  <?php if ($value->user_id != 0) {  ?><img class="img-circle list-profile-img tt"  title="<?=$value->user->firstname;?> <?=$value->user->lastname;?>"  src="<?php 
                if($value->user->userpic != 'no-pic.png'){
                  echo base_url()."files/media/".$value->user->userpic;
                }else{
                  echo get_gravatar($value->user->email);
                }
                 ?>"><?php } ?>
                                    <?php if ($value->public != 0) {  ?><span class="list-button"><i class="fa fa-eye tt" title="" data-original-title="<?=$this->lang->line('application_task_public');?>"></i></span><?php } ?>
                                    <a href="<?=base_url()?>projects/tasks/<?=$project->id;?>/update/<?=$value->id;?>" class="edit-button" data-toggle="mainmodal"><i class="fa fa-cog"></i></a>
                                  </span>
                    <div class="todo-details">
                    <div class="row">
                        <div class="col-sm-3">
                        <ul class="details">
                            <li><span><?=$this->lang->line('application_priority');?>:</span> <?php switch($value->priority){case "0": echo $this->lang->line('application_no_priority'); break; case "1": echo $this->lang->line('application_low_priority'); break; case "2": echo $this->lang->line('application_med_priority'); break; case "3": echo $this->lang->line('application_high_priority'); break;};?></li>
                            <?php if($value->value != 0){ ?><li><span><?=$this->lang->line('application_value');?>:</span> <?=$value->value;?></li><?php } ?>
                            <?php if($value->due_date != ""){ ?><li><span><?=$this->lang->line('application_due_date');?>:</span> <?php  $unix = human_to_unix($value->due_date.' 00:00'); echo date($core_settings->date_format, $unix);?></li><?php } ?>
                            <li><span><?=$this->lang->line('application_assigned_to');?>:</span> <?php if(isset($value->user->lastname)){ echo $value->user->firstname." ".$value->user->lastname;}else{$this->lang->line('application_not_assigned');}?> </li>

                         </ul>
                        
                        </div>
                        <div class="col-sm-9"><h3><?=$this->lang->line('application_description');?></h3> <p><?=$value->description;?></p></div>
                        
                    </div>
                    </div>
				    	
					</li>
				 <?php endforeach;?>
				 <?php if($count == 0) { ?>
					<li class="notask">No Tasks yet</li>
				 <?php } ?>

                       
         
                         </ul>
                </div>
               </div>
</div>
<div class="row">
<div class="col-xs-12 col-sm-6">
 <div class="table-head"><?=$this->lang->line('application_media');?> <span class=" pull-right"><a href="<?=base_url()?>projects/media/<?=$project->id;?>/add" class="btn btn-primary" data-toggle="mainmodal"><?=$this->lang->line('application_add_media');?></a></span></div>
<div class="table-div min-height-410">
 <table id="media" class="table data-media" rel="<?=base_url()?>projects/media/<?=$project->id;?>" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
                    <th  class="hidden"></th>
					<th><?=$this->lang->line('application_name');?></th>
					<th class="hidden-xs"><?=$this->lang->line('application_filename');?></th>
					<th class="hidden-xs"><?=$this->lang->line('application_phase');?></th>
					<th class="hidden-xs"><i class="fa fa-download"></i></th>
					<th><?=$this->lang->line('application_action');?></th>
          </tr></thead>
        
        <tbody>
        <?php foreach ($project->project_has_files as $value):?>

				<tr id="<?=$value->id;?>">
					<td class="hidden"><?=human_to_unix($value->date);?></td>
					<td onclick=""><?=$value->name;?></td>
					<td class="hidden-xs truncate" style="max-width: 80px;"><?=$value->filename;?></td>
					<td class="hidden-xs"><?=$value->phase;?></td>
					<td class="hidden-xs"><span class="label label-info tt" title="<?=$this->lang->line('application_download_counter');?>" ><?=$value->download_counter;?></span></td>
					<td class="option " width="10%">
				        <button type="button" class="btn-option btn-xs po" data-toggle="popover" data-placement="left" data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?=base_url()?>projects/media/<?=$project->id;?>/delete/<?=$value->id;?>'><?=$this->lang->line('application_yes_im_sure');?></a> <button class='btn po-close'><?=$this->lang->line('application_no');?></button> <input type='hidden' name='td-id' class='id' value='<?=$value->id;?>'>" data-original-title="<b><?=$this->lang->line('application_really_delete');?></b>"><i class="fa fa-times"></i></button>
				        <a href="<?=base_url()?>projects/media/<?=$project->id;?>/update/<?=$value->id;?>" class="btn-option" data-toggle="mainmodal"><i class="fa fa-cog"></i></a>
			       </td>
					
				</tr>

				<?php endforeach;?>
				
        
        
        </tbody></table>
        <?php if(!$project->project_has_files) { ?>
				<div class="no-files">	
				    <i class="fa fa-cloud-upload"></i><br>
				    No files have been uploaded yet!
				</div>
				 <?php } ?>
        </div>
</div>
<div class="col-xs-12 col-sm-6">
<?php $attributes = array('class' => 'note-form', 'id' => '_notes');
		echo form_open(base_url()."projects/notes/".$project->id, $attributes); ?>
 <div class="table-head"><?=$this->lang->line('application_notes');?> <span class=" pull-right"><a id="send" name="send" class="btn btn-primary button-loader"><?=$this->lang->line('application_save');?></a></span><span id="changed" class="pull-right label label-warning"><?=$this->lang->line('application_unsaved');?></span></div>

  <textarea class="input-block-level summernote-note" name="note" id="textfield" ><?=$project->note;?></textarea>
</form>
</div>

</div>


<div class="row">
 <div class="col-xs-12 col-sm-12">
 <div class="table-head"><?=$this->lang->line('application_invoices');?> <span class=" pull-right"></span></div>
<div class="table-div">
 <table class="data table" id="invoices" rel="<?=base_url()?>" cellspacing="0" cellpadding="0">
    <thead>
      <th width="70px" class="hidden-xs"><?=$this->lang->line('application_invoice_id');?></th>
      <th><?=$this->lang->line('application_client');?></th>
      <th class="hidden-xs"><?=$this->lang->line('application_issue_date');?></th>
      <th class="hidden-xs"><?=$this->lang->line('application_due_date');?></th>
      <th><?=$this->lang->line('application_status');?></th>
      <th class="hidden-xs"><?=$this->lang->line('application_action');?></th>
    </thead>
    <?php foreach ($project_has_invoices as $value):?>

    <tr id="<?=$value->id;?>" >
      <td class="hidden-xs" onclick=""><?=$value->reference;?></td>
      <td onclick=""><span class="label label-info"><?php if(isset($value->company->name)){echo $value->company->name; }?></span></td>
      <td class="hidden-xs"><span><?php $unix = human_to_unix($value->issue_date.' 00:00'); echo '<span class="hidden">'.$unix.'</span> '; echo date($core_settings->date_format, $unix);?></span></td>
      <td class="hidden-xs"><span class="label <?php if($value->status == "Paid"){echo 'label-success';} if($value->due_date <= date('Y-m-d') && $value->status != "Paid"){ echo 'label-important tt" title="'.$this->lang->line('application_overdue'); } ?>"><?php $unix = human_to_unix($value->due_date.' 00:00'); echo '<span class="hidden">'.$unix.'</span> '; echo date($core_settings->date_format, $unix);?></span> <span class="hidden"><?=$unix;?></span></td>
      <td onclick=""><span class="label <?php $unix = human_to_unix($value->sent_date.' 00:00'); if($value->status == "Paid"){echo 'label-success';}elseif($value->status == "Sent"){ echo 'label-warning tt" title="'.date($core_settings->date_format, $unix);} ?>"><?=$this->lang->line('application_'.$value->status);?></span></td>
    
      <td class="option hidden-xs" width="8%">
                <button type="button" class="btn-option delete po" data-toggle="popover" data-placement="left" data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?=base_url()?>invoices/delete/<?=$value->id;?>'><?=$this->lang->line('application_yes_im_sure');?></a> <button class='btn po-close'><?=$this->lang->line('application_no');?></button> <input type='hidden' name='td-id' class='id' value='<?=$value->id;?>'>" data-original-title="<b><?=$this->lang->line('application_really_delete');?></b>"><i class="fa fa-times"></i></button>
                <a href="<?=base_url()?>invoices/update/<?=$value->id;?>" class="btn-option" data-toggle="mainmodal"><i class="fa fa-cog"></i></a>
      </td>
    </tr>

    <?php endforeach;?>
    </table>
        <?php if(!$project_has_invoices) { ?>
        <div class="no-files">  
            <i class="fa fa-file-text"></i><br>
            
            <?=$this->lang->line('application_no_invoices_yet');?>
        </div>
         <?php } ?>
        </div>
  </div>             


</div>


<br>


<div class="row">
  <div class="col-sm-12"><h2><?=$this->lang->line('application_activities');?></h2><hr/></div>
  
</div>
<div class="row">



              <div class="col-xs-12 col-sm-12">
            <div id="timelinediv">
                  <ul class="timeline">
                     <li class="timeline-inverted add-comment">
                        <div class="timeline-badge gray open-comment-box"><i class="fa fa-plus"></i></div>
                        <div id="timeline-comment" class="timeline-panel">
                          <div class="timeline-heading">
                            <h5 class="timeline-title"><?=$this->lang->line('application_new_comment');?></h5>
                          </div>
                          <div class="timeline-body">
                               <?php   
                                $attributes = array('class' => 'ajaxform', 'id' => 'replyform', 'data-reload' => 'timelinediv');
                                echo form_open('projects/activity/'.$project->id.'/add', $attributes); 
                                ?>
                                  <div class="form-group">
                                    <input type="text" name="subject" class="form-control" id="subject" placeholder="<?=$this->lang->line('application_subject');?>" required/>
                                  </div>
                                    <div class="form-group">
                                        <textarea class="input-block-level summernote" id="reply" name="message" required/></textarea>
                                     </div>
                                <button id="send" name="send" class="btn btn-primary button-loader"><?=$this->lang->line('application_send');?></button>
                                </form>
                             
                          </div>
                        </div>
                      </li>

 <?php foreach ($project->project_has_activities as $value):?>
                      <?php 
                      $writer = FALSE;
                      if ($value->user_id != 0) { 
                          $writer = $value->user->firstname." ".$value->user->lastname;
                          $image = get_user_pic($value->user->userpic, $value->user->email);
                          }else{
                          $writer = $value->client->firstname." ".$value->client->lastname;
                          $image = get_user_pic($value->client->userpic, $value->client->email);
                                
                      }?>
                      <li class="timeline-inverted">
                        <div class="timeline-badge">
                        <?php if ($writer != FALSE) {  ?>
                        <img class="img-circle timeline-profile-img tt" title="<?=$writer?>"  src="<?=$image?>">
                        <?php }else{ } ?></div>
                        <div class="timeline-panel">
                          <div class="timeline-heading">
                            <h5 class="timeline-title"><?=$value->subject;?></h5>
                            <p><small class="text-muted"><span class="writer"><?=$writer?></span> <span class="datetime"><?php  echo date($core_settings->date_format.' '.$core_settings->date_time_format, $value->datetime); ?></span></small></p>
                          </div>
                          <div class="timeline-body">
                            <p><?=$value->message;?></p>
                          </div>
                        </div>
                      </li>
	<?php endforeach;?>
                      <li class="timeline-inverted timeline-firstentry">
                        <div class="timeline-badge gray"><i class="fa fa-bolt"></i></div>
                        <div class="timeline-panel">
                          <div class="timeline-heading">
                            <h5 class="timeline-title"><?=$this->lang->line('application_project_created');?></h5>
                            <p><small class="text-muted"><?php  echo date($core_settings->date_format.' '.$core_settings->date_time_format, $project->datetime); ?></small></p>
                          </div>
                          <div class="timeline-body">
                            <p><?=$this->lang->line('application_project_has_been_created');?></p>
                          </div>
                        </div>
                      </li>
                  </ul>
                  </div>
              </div>








    
	
	
	