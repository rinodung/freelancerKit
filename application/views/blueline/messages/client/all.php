<link href="<?=base_url()?>assets/blueline/css/plugins/messages.css" rel="stylesheet">        
        
<div class="col-sm-13  col-md-12 messages" onmouseover="document.body.style.overflow='hidden';" onmouseout="document.body.style.overflow='auto';">  
<main id="main" >
  <div class="overlay"></div>
  <header class="header">
    <h1 class="page-title">
     <div class="message-list-header">
              <span id="inbox-folder"><i class="fa fa-inbox"></i> <?=$this->lang->line('application_INBOX');?></span>
              <span id="sent-folder"><i class="fa fa-mail-forward"></i> <?=$this->lang->line('application_sent');?></span>
              <span id="deleted-folder"><i class="fa fa-trash"></i> <?=$this->lang->line('application_Deleted');?></span>
              <span id="marked-folder"><i class="fa fa-star"></i> <?=$this->lang->line('application_Marked');?></span>
              </div>
    </h1>
  </header>
  <div class="action-bar">
    <ul>
             <li><a class="btn btn-success" data-toggle="mainmodal" role="button" href="<?=base_url()?>cmessages/write" title="<?=$this->lang->line('application_write_message');?>"><i class="fa fa-envelope space"></i> <?=$this->lang->line('application_write_message');?></a></li>
             <li>
                <div class="btn-group">
                     <a class="btn btn-primary message-list-load inbox-folder" id="message-trigger" role="button" href="<?=base_url()?>cmessages/messagelist" title="Inbox"><i class="fa fa-inbox space"></i> <?=$this->lang->line('application_INBOX');?></a>
                     <a class="btn btn-primary message-list-load sent-folder" role="button" href="<?=base_url()?>cmessages/filter/sent/0" title="Sent Folder"><i class="fa fa-share space"></i> <?=$this->lang->line('application_sent');?></a>
                     <a class="btn btn-primary message-list-load deleted-folder" role="button" href="<?=base_url()?>cmessages/filter/deleted/0" title="<?=$this->lang->line('application_messages_show_deleted');?>"><i class="fa fa-trash space"></i> <?=$this->lang->line('application_Deleted');?></a>
                     <a class="btn btn-primary message-list-load marked-folder" role="button" href="<?=base_url()?>cmessages/filter/marked/0" title="<?=$this->lang->line('application_Marked');?>"><i class="fa fa-star space"></i> <?=$this->lang->line('application_Marked');?></a>
                </div>
            </li>
               
    </ul>
  </div>
  <div id="main-nano-wrapper" class="nano">
    <div class="nano-content">
      <ul id="message-list" class="message-list">
        
      </ul>
    </div>
  </div>
</main>
<div id="message">
  
</div>
</div>
</div>
<script>
jQuery(document).ready(function($) {


$(document).on("click", '.message-list-load', function (e) {
  e.preventDefault();
  NProgress.start();

  messageheader(this);

  $('.message-list-footer').fadeOut('fast');

  var url = $(this).attr('href');
  if (url.indexOf('#') === 0) {
    
  } else {
    $.get(url, function(data) { 
                        $('#message-list').html(data);

    }).success(function() {   NProgress.done(); });
  }
});  
$('#message-trigger').click();


//message list menu 
function messageheader(active) {
    var classes = $(active).attr("class").split(/\s/);
        if(classes[3]){
            $('.message-list-header span').hide();
                $('.message-list-header #'+classes[3]).fadeIn('slow');
        }
            
    
}


  // Search box responsive stuff

  $('.search-box input').on('focus', function() {
    if($(window).width() <= 1360) {
      cols.hideMessage();
    }
  });

});


</script>