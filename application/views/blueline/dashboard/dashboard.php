<?php 
if(isset($update)){
if($this->user->admin == "1" && $update){
 ?>
<div class="newsbox"><a href="<?=base_url()?>settings/updates"><?=$this->lang->line('application_update_available');?> <?=$update?> <i class="fa fa-download"></i> </a></div>
<?php } }?>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4">
              <div class="stdpad"><h2><?=$this->lang->line('application_events');?><small> (<?=$eventcount;?>)</small></h2>
                    <ul class="eventlist">
                            <?php $count = 0;
                            foreach ($events as $value):  $count = $count+1; ?>            
                                    <li>
                                       <p class="truncate"><?=$value;?></p>  
                                    </li>
                            <?php endforeach;?>
                            <?php if($count == 0) { ?>
                                    <li> <p class="truncate"><?=$this->lang->line('application_no_events_yet');?></p></li>
                            <?php } ?>
                    </ul>
             </div>

            </div>
        
            <div class="col-xs-12 col-sm-12 col-md-4">
            <?php if(isset($tasks)){ ?> 
              <div class="stdpad">
                  <h2><?=$this->lang->line('application_my_open_tasks');?></h2>
                  <div id="main-nano-wrapper" class="nano">
    <div class="nano-content"><ul id="jp-container" class="todo jp-container">
                         <?php $count = 0;
                                foreach ($tasks as $value):  $count = $count+1; ?>
                                    <li class="<?=$value->status;?>">
                                      <span class="lbl-"> 
                                        <p class="truncate"><input name="form-field-checkbox" type="checkbox" class="checkbox-nolabel task-check" data-link="<?=base_url()?>projects/tasks/<?=$value->project_id;?>/check/<?=$value->id;?>" <?=$value->status;?>/>
                                   <a href="<?=base_url()?>projects/view/<?=$value->project_id;?>"><?=$value->name;?></a></p></span> 
                                             <span class="pull-right"><img class="img-circle list-profile-img" width="21px" height="21px" src="<?php 
                                                if($this->user->userpic != 'no-pic.png'){
                                                  echo base_url()."files/media/".$this->user->userpic;
                                                }else{
                                                  echo get_gravatar($this->user->email);
                                                }
                                                 ?>">
                                             </span>
                                         
                                    </li>
                                <?php endforeach;?>
                                
                                <?php if($count == 0) { ?>
                                    <li class="notask"><?=$this->lang->line('application_no_tasks_yet');?></li>
                                    
                                <?php } ?>

                  </ul></div></div>
                </div>
                <?php } ?>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-4">
            <?php if(isset($message)){ ?> 
                <div class="stdpad">
                    <h2><?=$this->lang->line('application_recent_messages');?></h2>

                        <ul class="dash-messages">
                            <?php foreach ($message as $value):?>          
                                <li style="display: list-item;">
                                    <a href="<?=base_url()?>messages">
                                      <img class="userpic img-circle" src="
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
                                        ">
                                    <h5><?php if(isset($value->sender_u)){echo $value->sender_u;}else{ echo $value->sender_c; } ?> <small><?php echo time_ago($value->time); ?></small></h5>
                                    <p class="truncate" style="width:80%"><span> <?php if($value->status == "New"){ echo '<span class="new"><i class="fa fa-circle-o"></i></span>';}?> <?=$value->subject;?></span></p>
                                    </a>
                                </li>
                            <?php endforeach;?>
                            <?php if(empty($message)) { ?>
                                <li style="padding: 10px 0 0 0; height: 24px;"><?=$this->lang->line('application_no_messages');?></li>
                            <?php } ?>
                        </ul><br/>
                       </div>
            <?php } ?>
            </div>
            
            
        </div>
<?php if($this->user->admin == "1"){ ?>        
    <div class="row statstic-chart">
          <div class="col-xs-12 col-sm-12 dashboard-chart">
            <h1><?=$this->lang->line('application_statistics');?> 
            <div class="btn-group pull-right">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <?=$year;?> <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="<?=base_url()?>dashboard/filter/<?=date("Y");?>"><?=date("Y");?></a></li>
                    <li class="divider"></li>
                    <li><a href="<?=base_url()?>dashboard/filter/<?=date("Y")-1;?>"><?=date("Y")-1;?></a></li>
                    <li><a href="<?=base_url()?>dashboard/filter/<?=date("Y")-2;?>"><?=date("Y")-2;?></a></li>
                    <li><a href="<?=base_url()?>dashboard/filter/<?=date("Y")-3;?>"><?=date("Y")-3;?></a></li>
                    <li><a href="<?=base_url()?>dashboard/filter/<?=date("Y")-4;?>"><?=date("Y")-4;?></a></li>
                    <li><a href="<?=base_url()?>dashboard/filter/<?=date("Y")-5;?>"><?=date("Y")-5;?></a></li>
                  </ul>
            </div></h1>
            
            <figure style="width: auto; height: 250px;" id="dashboard_line_chart"></figure>
          </div>
    </div>
    
    <div id="stat-numbers" class="row">
        <div class="col-xs-6 col-sm-3"><h2><?=$invoices_open;?><small> / <?=$invoices_all;?></small></h2> <h5><?=$this->lang->line('application_open_invoices');?></h5></div>
        <div class="col-xs-6 col-sm-3"><h2><?=$projects_open;?><small> / <?=$projects_all;?></small></h2> <h5><?=$this->lang->line('application_open_projects');?></h5></div>
        <div class="col-xs-6 col-sm-3"><h2><?=$core_settings->currency;?> <?php if(empty($payments[0]->summary)){echo "0";}else{echo number_format($payments[0]->summary); }?></h2> <h5><?=$this->lang->line('application_'.$month);?> <?=$this->lang->line('application_payments');?></h5></div>
        <div class="col-xs-6 col-sm-3"><h2><?=$core_settings->currency;?> <?php if(empty($paymentsoutstanding[0]->summary)){echo "0";}else{echo number_format($paymentsoutstanding[0]->summary); } ?></h2> <h5><?=$this->lang->line('application_outstanding_payments');?></h5></div>  
      </div>
<?php } ?>    
         
 

      <?php 
      $line1 = '{ "xScale": "time",
                  "yScale": "linear",
                  "yMin": 0,
                   "main": [
                    {
                    "className": ".dashboard_chart",
                    "data": [';
      for ($i = 01; $i <= 12; $i++) {

        $num = "0";
        foreach ($stats as $value):
        $act_month = explode("-", $value->paid_date); 
        if($act_month[1] == $i){  
          $num = $value->summary; 
        }
        endforeach; 
          $i = sprintf("%02.2d", $i);
          $line1 .= "{x: '".$year."-".$i."', y: ".$num."}";
          if($i != "12"){ $line1 .= ",";}
        } 
        
        $line1 .= ']}, 
                            ]
                          }';
        ?>



  <script type="text/javascript">
    $(document).ready(function(){

      //xChart Dashboard 
                         var tt = document.createElement('div'),
                            leftOffset = -(~~$('html').css('padding-left').replace('px', '') + ~~$('body').css('margin-left').replace('px', '')),
                            topOffset = -32;
                          tt.className = 'ex-tooltip';
                          document.body.appendChild(tt);
                          
                          
                        var data = <?=$line1?>;
                          var opts = {
                            "dataFormatX": function (x) { return d3.time.format('%Y-%m').parse(x); },
                            "tickFormatX": function (x) { return d3.time.format('%Y-%m')(x); },
                            "mouseover": function (d, i) {
                              var pos = $(this).offset();
                              var lineclass = $(this).parent().attr("class");
                              lineclass = lineclass.split(" ");
                              console.log(lineclass[2]);
                              if( lineclass[2] == "dashboard_chart"){
                                var linename = "<?=$this->lang->line('application_received');?>: ";
                              }else{
                                var linename = "Opened";
                              }
                              $(tt).text(linename + d.y)
                                .css({top: topOffset + pos.top, left: pos.left + leftOffset})
                                .show();
                            },
                            "mouseout": function (x) {
                              $(tt).hide();
                            },
                            "tickHintY": 4,
                            "paddingLeft":40,
                             
                          };
                          if($("#dashboard_line_chart").length != 0) {
                          var myChart = new xChart('line-dotted', data, '#dashboard_line_chart', opts);
                          }
                          //xChart DAshboard End

function tick(){
  $('ul.dash-messages li:first').slideUp('slow', function () { $(this).appendTo($('ul.dash-messages')).fadeIn('slow'); });
}
<?php if(count($message) > 2){ ?>
setInterval(function(){ tick() }, 5000);
<?php } ?>
$('ul.eventlist li').click(function(){
  $('ul.eventlist li:first').slideUp('slow', function () { $(this).appendTo($('ul.eventlist')).fadeIn('slow'); });
});



    });
    </script>




 