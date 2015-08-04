<div class="row">
        <div class="col-xs-12 col-md-3">

	 	<div class="table-head"><?=$this->lang->line('application_ticket_details');?></div>
		<div class="subcont">
			<ul class="ticket-details">
			<?php $lable = FALSE; if($ticket->status == "new"){ $lable = "label-important"; }elseif($ticket->status == "open"){$lable = "label-warning";}elseif($ticket->status == "closed" || $ticket->status == "inprogress"){$lable = "label-success";}elseif($ticket->status == "reopened"){$lable = "label-warning";} ?>
	
				<li><h6><?=$this->lang->line('application_ticket_number');?>:</h6> #<?=$ticket->reference;?></li>
				<li><h6><?=$this->lang->line('application_status');?>:</h6> <span class="label <?php echo $lable; ?>"><?=$this->lang->line('application_ticket_status_'.$ticket->status);?></span></li>
				<li><h6><?=$this->lang->line('application_type');?>:</h6> <?php if(isset($ticket->type->name)){ ?><?=$ticket->type->name;?> <?php } ?></li>
				<li><h6><?=$this->lang->line('application_from');?>:</h6> <?php if(isset($ticket->client->email)){ echo '<span class="tt" title="'.$ticket->client->email.'">'.$ticket->client->firstname.' '.$ticket->client->lastname.'</span>';
				$emailsender = $ticket->client->email;
				}
				else{ 
    				$explode = explode(' - ', $ticket->from); 
    				if(isset($explode[1])){ 
    				$emailsender = $explode[1];
    			    $emailname = str_replace('"', '', $explode[0]);
    			    $emailname = str_replace('<', '', $emailname);
    			    $emailname = str_replace('>', '', $emailname);
    			    $emailname = explode(' ', $emailname); 
    			    $emailname = $emailname[0];
				}else{ $explodeemail = "-"; } 
				echo '<span class="tt" title="'.addslashes($emailsender).'">'.$emailname.'</span>'; } ?></li>
				<li><h6><?=$this->lang->line('application_queue');?>:</h6> <?php if(isset($ticket->queue->name)){ ?><?=$ticket->queue->name;?> <?php } ?></li>
				<li><h6><?=$this->lang->line('application_created');?>:</h6> <?php echo date($core_settings->date_format.'  '.$core_settings->date_time_format, $ticket->created); ?></li>
				<li><h6><?=$this->lang->line('application_owner');?>:</h6> <?php if(isset($ticket->user->firstname)){ ?><?=$ticket->user->firstname;?> <?=$ticket->user->lastname;?> <?php } else{ echo "-";} ?></li>
				
				</ul>

			
	 </div>
	 <br>


	 <div class="table-head"><?=$this->lang->line('application_client');?></div>
		<div class="subcont">
			<ul class="ticket-details">
				<?php if(isset($ticket->client->firstname)){ ?><li><h6><?=$this->lang->line('application_name');?>:</h6>  <?php echo $ticket->client->firstname.' '.$ticket->client->lastname; ?> </li><?php } ?>
				<li><h6><?=$this->lang->line('application_company');?>:</h6> <?php if(!isset($ticket->client->company->name)){ ?> <a href="#" class="label label-info"><?php echo $this->lang->line('application_no_client_assigned'); }else{ ?><a class="label label-info" href="<?=base_url()?>clients/view/<?=$ticket->client->company->id;?>"><?php echo $ticket->client->company->name;} ?></a></li>
				<?php if(isset($ticket->client->email)){ ?> <li><h6><?=$this->lang->line('application_email');?>:</h6> <?=$ticket->client->email;?></li><?php } ?>
				<?php if(isset($ticket->client->phone)){ ?> <li><h6><?=$this->lang->line('application_phone');?>:</h6> <?php echo $ticket->client->phone; ?></li><?php } ?>
				<?php if(isset($ticket->client->mobile)){ ?> <li><h6><?=$this->lang->line('application_mobile');?>:</h6> <?php echo $ticket->client->mobile; ?></li><?php } ?>
				</ul>
	 </div>
	</div>
	 <div class="col-xs-12 col-md-9">


	 			<a id="fadein" class="btn btn-success" style="margin-top: -2px;"><?=$this->lang->line('application_reply_back');?></a>
	 		 	<div class="btn-group nav-tabs hidden-xs">
				
	                <a class="btn btn-primary backlink" id="back" href="#"><?=$this->lang->line('application_back');?></a>
	                <a class="btn btn-primary" id="note" data-toggle="mainmodal" href="<?=base_url()?>tickets/article/<?=$ticket->id;?>/add"><?=$this->lang->line('application_add_note');?></a>
	                <a class="btn btn-primary" id="queue" data-toggle="mainmodal" href="<?=base_url()?>tickets/queue/<?=$ticket->id;?>"><?=$this->lang->line('application_queue');?></a>
	                <a class="btn btn-primary" id="type" data-toggle="mainmodal" href="<?=base_url()?>tickets/type/<?=$ticket->id;?>"><?=$this->lang->line('application_type');?></a>
	                <a class="btn btn-primary" id="client" data-toggle="mainmodal" href="<?=base_url()?>tickets/client/<?=$ticket->id;?>"><?=$this->lang->line('application_client');?></a>
	                <a class="btn btn-primary" id="assign" data-toggle="mainmodal" href="<?=base_url()?>tickets/assign/<?=$ticket->id;?>"><?=$this->lang->line('application_assign');?></a> 
  					<a class="btn btn-primary" id="status" data-toggle="mainmodal" href="<?=base_url()?>tickets/status/<?=$ticket->id;?>"><?=$this->lang->line('application_status');?></a>
  					<a class="btn btn-primary" id="close" data-toggle="mainmodal" href="<?=base_url()?>tickets/close/<?=$ticket->id;?>"><?=$this->lang->line('application_close');?></a>

	        </div> 
	        <div class="btn-group pull-right visible-xs">
			  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			    <i class="fa fa-cog"></i> <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
			    				<li><a class=" backlink" id="back" href="#"><?=$this->lang->line('application_back');?></a>
				                <li><a id="note" data-toggle="mainmodal" href="<?=base_url()?>tickets/article/<?=$ticket->id;?>/add"><?=$this->lang->line('application_add_note');?></a></li>
				                <li><a id="queue" data-toggle="mainmodal" href="<?=base_url()?>tickets/queue/<?=$ticket->id;?>"><?=$this->lang->line('application_queue');?></a></li>
				                <li><a id="type" data-toggle="mainmodal" href="<?=base_url()?>tickets/type/<?=$ticket->id;?>"><?=$this->lang->line('application_type');?></a></li>
				                <li><a id="client" data-toggle="mainmodal" href="<?=base_url()?>tickets/client/<?=$ticket->id;?>"><?=$this->lang->line('application_client');?></a></li>
				                <li><a id="assign" data-toggle="mainmodal" href="<?=base_url()?>tickets/assign/<?=$ticket->id;?>"><?=$this->lang->line('application_assign');?></a></li>
			  					<li><a id="close" data-toggle="mainmodal" href="<?=base_url()?>tickets/close/<?=$ticket->id;?>"><?=$this->lang->line('application_close');?></a></li>

			  </ul>
			</div>

	        <div class="message-content-reply fadein no-padding">
					    <?php   
					    $attributes = array('class' => '', 'id' => 'replyform');
					    echo form_open('tickets/article/'.$ticket->id.'/add', $attributes); 
					    ?>
					    <input id="ticket_id" type="hidden" name="ticket_id" value="<?php echo $ticket->id; ?>" />
					    <input type="hidden" name="to" value="<?=addslashes($emailsender);?>">
					    <input type="hidden" name="notify" value="yes">
					    <input type="hidden" name="subject" value="<?=$ticket->subject;?>">
					    <textarea id="reply" name="message" class="summernote" placeholder="<?=$this->lang->line('application_quick_reply');?>"></textarea>
					    <div class="textarea-footer">
					    <button id="send" name="send" class="btn btn-primary button-loader"><?=$this->lang->line('application_send');?></button>
					  	</div>
					    <?php echo form_close(); ?>

					  </div>
	        <div class="article-content">
					<h4><p class="truncate">[#<?=$ticket->reference;?>] <?=$ticket->subject;?></p></h4>
						<hr>
					
					<div class="article">
						<?=$ticket->text;?>

						<?php if(isset($ticket->ticket_has_attachments[0])){echo '<hr>'; } ?>
						<?php foreach ($ticket->ticket_has_attachments as $ticket_attachments):  ?>
			 				<a class="label label-info" href="<?=base_url()?>tickets/attachment/<?php echo $ticket_attachments->savename; ?>"><?php echo $ticket_attachments->filename; ?></a>
			 				<?php endforeach;?>
					
					</div>
			</div>
					
					 

				<?php
			    $i = 0;
			    foreach ($ticket->ticket_has_articles as $value): 
			      $i = $i+1;
			  if($i == 1){ ?>
			  
			  <?php }
			  ?>	
			  <div class="article-content">
			 		<div class="article-header">
			 		<div class="article-title"><?=$value->subject;?> <?php if($value->internal == "0"){ ?><i class="fa fa-eye tt pull-right" title="<?=$this->lang->line('application_task_public');?>"> </i> <?php } ?>
		</div>
			 			<span class="article-sub"><?php $from_explode = explode(' - ', $value->from); echo '<span class="tt" title="'.$from_explode[1].'">'.$from_explode[0].'</span>'; ?></span>  
				 		<span class="article-sub"><?php echo date($core_settings->date_format.' '.$core_settings->date_time_format, $value->datetime); ?></span>
						
				 		

			         <?php /* if($value->from == $this->user->firstname." ".$this->user->lastname || $this->user->admin == "1"){ ?>
			         <a href="<?=base_url()?>projects/deletemessage/<?=$ticket->project_id;?>/<?=$ticket->id;?>/<?=$value->id;?>" rel="" class="btn btn-mini pull-right btn-danger"><i class="icon-trash icon-white"></i></a>
			 		 <?php } */ ?>
			 		</div>
			 		<div class="article-body">
			 		<?=$value->message;?>

			 		<?php if(isset($value->article_has_attachments[0])){echo "<hr>"; } ?>
			 		<?php foreach ($value->article_has_attachments as $attachments):  ?>
			 				<a class="label label-success" href="<?=base_url()?>tickets/articleattachment/<?php echo $attachments->savename; ?>"><?php echo $attachments->filename; ?></a>
			 		<?php endforeach;?>

			 		</div>
			 		</div>
			  <?php endforeach;?>

			 

	  </div>
	</div>
	</div>
</div>