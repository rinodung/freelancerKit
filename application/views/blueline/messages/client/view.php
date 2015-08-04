<?php if($conversation){ 
        $i = 0;
        foreach ($conversation as $value):
          $own = false;
          $unix = human_to_unix($value->time); 
          if("c".$this->client->id == $value->sender){ $own = " own";}else{$own = "-previous";}
          $i = $i+1;
        ?>    
    
<?php if($i == "1" && $own != "own"){ ?>
  
  
       

  <div id="message-nano-wrapper" class="nano ">
    <div class="nano-content">
       <div class="header">
 <div class="message-content-menu">
              <a class="message-reply-button btn btn-success" role="button"><i class="fa fa-reply"></i> Reply</a>
              <a class="btn btn-primary ajax-silent" href="<?=base_url()?>messages/mark/<?=$value->id?>" role="button">
              <?php if($value->status == 'Marked'){ ?>
              <i class="fa fa-star-o"></i> Unmark
              <?php }else{ ?>
              <i class="fa fa-star"></i> Mark
              <?php } ?>
              </a>
              <a class="btn btn-danger" href="<?=base_url()?>cmessages/delete/<?=$value->id?>" role="button"><i class="fa fa-trash-o"></i> <?=$this->lang->line('application_delete');?></a>
             
              
            </div>  
    <h1 class="page-title"><a class="icon glyphicon glyphicon-chevron-right trigger-message-close"></a><br><span class="dot"></span><?=$value->subject;?><span class="grey">(<?=$count;?>)</span></h1>
    <p class="subtitle">From <a href="#"><?php if(isset($value->sender_u) && $filter != "Deleted"){echo $value->sender_u;}else{ echo $value->sender_c; } ?></a> to <a href="#"><?php if(isset($value->recipient_u)){echo $value->recipient_u;}else{ echo $value->recipient_c; } ?></a>, started on <?php  echo date($core_settings->date_format.' '.$core_settings->date_time_format, $unix); ?></p>
  </div>
    
      <ul class="message-container">
          
          
          
          <div class="message-content-reply no-padding">
          <?php   
                $attributes = array('class' => 'ajaxform', 'id' => 'replyform');
                echo form_open('cmessages/write/reply', $attributes); 
                ?>
                <input type="hidden" name="recipient" value="<?=$value->sender;?>">
                <input type="hidden" name="subject" value="<?=$value->subject;?>">
                <input type="hidden" name="conversation" value="<?=$value->conversation;?>">
                <input type="hidden" name="previousmessage" value="<?=$value->id;?>">
              <div class="form-group">
                  <textarea class="input-block-level summernote-ajax" id="reply" name="message"></textarea>
              
              <div class="textarea-footer">
              <button id="send" name="send" class="btn btn-primary button-loader"><?=$this->lang->line('application_send');?></button>
              </div>
              </div>
              <?php echo form_close(); ?>
       <hr>
       <br>
        </div>
          <?php } ?> 
    
    


        <li class="item sent <?=$own;?>">
          <div class="details">
            <div class="left">
            <img class="userpic img-rounded pull-left" src="
       <?php 
              if($value->userpic_u){
                if($value->userpic_u != 'no-pic.png'){
                  echo base_url()."files/media/".$value->userpic_u;
                }else{
                  echo get_gravatar($value->email_u);
                }
                
              }else{
                if($value->userpic_c != 'no-pic.png'){
                  echo base_url()."files/media/".$value->userpic_c;
                }else{
                  echo get_gravatar($value->email_c);
                }
              }
              ?>
      "/>
            <?php if(isset($value->sender_u) && $filter != "Deleted"){echo $value->sender_u;}else{ echo $value->sender_c; } ?>
            </div>
            <div class="right"><?php  echo date($core_settings->date_format.' '.$core_settings->date_time_format, $unix); ?></div>
          </div>
          <div class="message">
            <?=$value->message;?> 
            </div>
        </li>

    
    <?php endforeach;?>
          </ul>
    </div>
    <?php } ?>
    <br><br>
    <script>
jQuery(document).ready(function($) {
    
  $('.nano').nanoScroller();
    $('.trigger-message-close').on('click', function() {
    $('body').removeClass('show-message');
    $('#main .message-list li').removeClass('active');
    messageIsOpen = false;
    $('body').removeClass('show-main-overlay');
  });
});
</script>