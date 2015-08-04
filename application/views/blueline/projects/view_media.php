<link href="<?=base_url()?>assets/blackline/css/video-js.css" rel="stylesheet">

          
       <div class="row">
       <div class="col-xs-12 col-sm-3">
	 	<div class="table-head"><?=$this->lang->line('application_media_details');?></div>
		<div class="subcont">
			<ul class="details">
				<li><span><?=$this->lang->line('application_name');?>:</span> <?=$media->name;?></li>
				<li><span><?=$this->lang->line('application_filename');?>:</span> <?=$media->filename;?></li>
				<li><span><?=$this->lang->line('application_phase');?>:</span> <?=$media->phase;?></li>
				<li><span><?=$this->lang->line('application_uploaded_by');?>:</span> <a class="label label-info"><?php if(isset($media->user->firstname)){ ?><?=$media->user->firstname;?> <?=$media->user->lastname;?><?php }else{ ?> <?=$media->client->firstname;?> <?=$media->client->lastname;?><?php } ?></a></li>
				<li><span><?=$this->lang->line('application_uploaded_on');?>:</span> <?php $unix = human_to_unix($media->date); echo date($core_settings->date_format, $unix); ?></li>
				<li><span><?=$this->lang->line('application_download');?>:</span> <a href="<?=base_url()?>projects/download/<?=$media->id;?>" class="btn btn-xs btn-success"><i class="icon-download icon-white"></i> <?=$this->lang->line('application_download');?></a></li>
				<?php if(!empty($media->description)){ ?><li><span><?=$this->lang->line('application_description');?></span><br><p class="margintop5"> <?=$media->description;?></p></li><?php } ?>
			</ul>
			<br clear="both">
    	 </div>
    	 <br>
    	 <a class="btn btn-primary" href="<?=base_url()?><?=$backlink;?>"><i class="fa fa-arrow-left"></i> <?=$this->lang->line('application_back_to_project');?></a>
    	 </div>
     
	 <div class="col-sm-9">
	 		 	
	 		<?php
				$type = explode('/', $media->type);
				switch($type[0]){
				case "image": ?>
					<div class="table-head"><?=$this->lang->line('application_media_preview');?></div>
					<div class="subcont preview">
					<div align="center">
						<img src="<?=base_url()?>files/media/<?=$media->savename;?>">
					</div>
					</div>
				<?php 
				break; 
				case "application":
					if($type[1] == "ogg" || $type[1] == "mp4" || $type[1]  == "webm"){ ?>
					<div class="table-head"><?=$this->lang->line('application_media_preview');?></div>
					<div class="subcont preview">
					<video id="video" class="video-js vjs-default-skin" controls
				  		preload="auto" width="100%" height="350" data-setup="{}">
				  		<source src="<?=base_url()?>files/media/<?=$media->savename;?>" type='video/<?=$type[1];?>'>
					</video>
					</div>
					<?php } 

					if($type[1] == "pdf"){ ?>
			        <div class="table-head"><h6><i class="icon-picture"></i><?=$this->lang->line('application_media_preview');?></h6></div>
			        <div class="subcont preview">
			        <div align="center"><a href="<?=base_url()?>/files/media/<?=$media->savename;?>" title="View PDF">
			        <img src="http://docs.google.com/viewer?url=<?=base_url()?>/files/media/<?=$media->savename;?>&a=bi&pagenumber=1&w=auto" alt="" /></a>
			        </div>
			        </div>
					<?php } 
			
			break;
			case "video":
					?>
					<div class="table-head"><?=$this->lang->line('application_media_preview');?></div>
					<div class="subcont preview">
					<video id="video" class="video-js vjs-default-skin" controls
				  		preload="auto" width="100%" height="350" data-setup="{}">
				  		<source src="<?=base_url()?>files/media/<?=$media->savename;?>" type='video/<?=$type[1];?>'>
					</video>
					</div>
					<?php 
			
			break;

			} ?>
			<br>
			  <h2><?=$this->lang->line('application_comments');?></h2>
			  <hr>
			  <div id="timelinediv">
                  <ul class="timeline">
			   <li class="timeline-inverted add-comment">
                        <div class="timeline-badge gray open-comment-box"><i class="fa fa-plus"></i></div>
                        <div id="timeline-comment" class="timeline-panel">
                          <div class="timeline-heading">
                            <h4 class="timeline-title"><?=$this->lang->line('application_post_message');?></h4>
                          </div>
                          <div class="timeline-body">
                               <?php   
                                $attributes = array('class' => 'ajaxform', 'id' => 'replyform', 'data-reload' => 'timelinediv');
			                    echo form_open($form_action, $attributes); 
                                ?>

                                    <div class="form-group">
                                        <input id="timestamp" type="hidden" name="datetime" value="<?php echo $datetime; ?>" />
                                        <textarea class="input-block-level summernote" id="reply" name="message"/></textarea>
                                     </div>
                                <button id="send" name="send" class="btn btn-primary button-loader"><?=$this->lang->line('application_send');?></button>
                                </form>
                             
                          </div>
                        </div>
                      </li>


                
				<?php
			    $i = 0;
			    foreach ($media->messages as $value): 
			      $i = $i+1;
			  if($i == 1){ ?>
			  
			  <?php }
			  ?>	
			  
			 	
                      <li class="timeline-inverted">
                        <div class="timeline-badge" style="background: rgb(96, 187, 248);">
                        <?=$i;?>
                        </div>
                        <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h5 class="timeline-title">
                            <p><small class="text-muted"><span class="writer"><?=$value->from;?></span> <span class="datetime"><?php  $unix = human_to_unix($value->datetime); echo date($core_settings->date_format.' '.$core_settings->date_time_format, $unix); ?></span></small>
                            		<?php if($value->from == $this->user->firstname." ".$this->user->lastname || $this->user->admin == "1"){ ?>
							         <a href="<?=base_url()?>projects/deletemessage/<?=$media->project_id;?>/<?=$media->id;?>/<?=$value->id;?>" rel="" class="btn btn-xs btn-danger pull-right"><i class="fa fa-times"></i></a>
							 		 <?php } ?>
                            </p></h5>
                          </div>
                          
                          <div class="timeline-body">
                            <p><?=$value->text;?></p>
                          </div>
                        </div>
                      </li>	
			 	

			  <?php endforeach;?>
			 </div>
			 </div>



