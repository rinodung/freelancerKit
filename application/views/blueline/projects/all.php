
	
	
	<div class="col-sm-13  col-md-12 main">  
    <div class="row tile-row">
      <div class="col-md-3 col-xs-6 tile"><h1><i class="fa fa-lightbulb-o"></i> <?php if(isset($projects_assigned_to_me)){echo $projects_assigned_to_me;} ?> <span class="hidden-xs"><?=$this->lang->line('application_projects');?></span></h1><h2 class="hidden-xs"><?=$this->lang->line('application_assigned_to_me');?></h2></div>
      <div class="col-md-3 col-xs-6 tile"> <h1><i class="fa fa-tasks"></i> <?php if(isset($tasks_assigned_to_me)){echo $tasks_assigned_to_me;} ?> <span class="hidden-xs"><?=$this->lang->line('application_tasks');?></span></h1><h2 class="hidden-xs"><?=$this->lang->line('application_assigned_to_me');?></h2></div>
      <div class="col-md-6 col-xs-12 tile">
      <figure style="width: auto; height: 100px;" id="project_line_chart"></figure>
      </div>
    
    </div>   
     <div class="row">
      <a href="<?=base_url()?>projects/create" class="btn btn-primary" data-toggle="mainmodal"><?=$this->lang->line('application_create_new_project');?></a>
      <div class="btn-group pull-right-responsive margin-right-3">
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <?php $last_uri = $this->uri->segment($this->uri->total_segments()); if($last_uri != "projects"){echo $this->lang->line('application_'.$last_uri);}else{echo $this->lang->line('application_all');} ?> <span class="caret"></span>
          </button>
          <ul class="dropdown-menu pull-right" role="menu">
            <?php foreach ($submenu as $name=>$value):?>
	                <li><a id="<?php $val_id = explode("/", $value); if(!is_numeric(end($val_id))){echo end($val_id);}else{$num = count($val_id)-2; echo $val_id[$num];} ?>" href="<?=site_url($value);?>"><?=$name?></a></li>
	            <?php endforeach;?>
          </ul>
      </div>
    </div>  
      <div class="row">

         <div class="table-head"><?=$this->lang->line('application_projects');?></div>
         <div class="table-div">
         <table class="data table" id="projects" rel="<?=base_url()?>" cellspacing="0" cellpadding="0">
                <thead>
                  <tr>
                      <th class="hidden-xs"><?=$this->lang->line('application_project_id');?></th>
                      <th><?=$this->lang->line('application_name');?></th>
                      <th class="hidden-xs"><?=$this->lang->line('application_client');?></th>
                      <th class="hidden-xs"><?=$this->lang->line('application_deadline');?></th>
                      <th class="hidden-xs"><?=$this->lang->line('application_progress');?></th>
                      <th><?=$this->lang->line('application_action');?></th>
                  </tr></thead>
                
                <tbody>
                <?php foreach ($project as $value):
        			$workers = array();
        			foreach($value->project_has_workers as $worker){ array_push($workers, $worker->user_id);}
        			if($this->user->admin == "1" || in_array($this->user->id, $workers)){ ?>
                <tr id="<?=$value->id;?>">
                  <td class="hidden-xs"><?=$value->reference;?></td>
                  <td onclick=""><?=$value->name;?></td>
                  <td class="hidden-xs"><a class="label label-info"><?php if(!isset($value->company->name)){echo $this->lang->line('application_no_client_assigned'); }else{ echo $value->company->name; }?></a></td>
                  <td class="hidden-xs"><span class="hidden-xs label label-success <?php if($value->end <= date('Y-m-d') && $value->progress != 100){ echo 'label-important tt" title="'.$this->lang->line('application_overdue'); } ?>"><?php $unix = human_to_unix($value->end.' 00:00');echo '<span class="hidden">'.$unix.'</span> '; echo date($core_settings->date_format, $unix);?></span></td>

                  <td class="hidden-xs"><div class="progress progress-striped active progress-medium tt <?php if($value->progress== "100"){ ?>progress-success<?php } ?>" title="<?=$value->progress;?>%">
                      <div class="bar" style="width:<?=$value->progress;?>%"></div>
                </div></td>
                  <td class="option" width="8%">
				        <button type="button" class="btn-option delete po" data-toggle="popover" data-placement="left" data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?=base_url()?>projects/delete/<?=$value->id;?>'><?=$this->lang->line('application_yes_im_sure');?></a> <button class='btn po-close'><?=$this->lang->line('application_no');?></button> <input type='hidden' name='td-id' class='id' value='<?=$value->id;?>'>" data-original-title="<b><?=$this->lang->line('application_really_delete');?></b>"><i class="fa fa-times"></i></button>
				        <a href="<?=base_url()?>projects/update/<?=$value->id;?>" class="btn-option" data-toggle="mainmodal"><i class="fa fa-cog"></i></a>
			       </td>
                </tr>
                <?php } ?>
		        <?php endforeach;?>
                
               

              </tbody>
            </table>
            </div>

      </div>
<script>
$(document).ready(function(){ 
                        //xChart 
                          var tt = document.createElement('div'),
                            leftOffset = -(~~$('html').css('padding-left').replace('px', '') + ~~$('body').css('margin-left').replace('px', '')),
                            topOffset = -32;
                          tt.className = 'ex-tooltip';
                          document.body.appendChild(tt);

                          var data = {
                            "xScale": "time",
                            "yScale": "linear",
                            "yMin": 0,
                            "main": [
                              {
                                "className": ".project_chart_opened",
                                "data": [
                                <?php
                                $days = array(); 
                                $this_week_days = array(
                                  date("Y-m-d",strtotime('monday this week')),
                                  date("Y-m-d",strtotime('tuesday this week')),
                                    date("Y-m-d",strtotime('wednesday this week')),
                                      date("Y-m-d",strtotime('thursday this week')),
                                        date("Y-m-d",strtotime('friday this week')),
                                          date("Y-m-d",strtotime('saturday this week')),
                                            date("Y-m-d",strtotime('sunday this week')));
                                foreach ($projects_opened_this_week as $value) {
                                  $days[$value->date_formatted] = $value->amount;

                                  //$days[$value->date_day."_date"] = $value->date_formatted;
                                }
                                foreach ($this_week_days as $selected_day) {
                                  $y = 0;
                                    if(isset($days[$selected_day])){ $y = $days[$selected_day];}
                                      ?>{
                                    
                                    "x": "<?php echo $selected_day; ?>",
                                    "y": <?php echo $y; ?>
                                  },
                                  <?php } ?>
          
                                 
                                ]}, 
                              {
                                "className": ".project_chart_closed",
                                "data": [
                                  
                                ] 



                              }
                            ]
                          };
                          var opts = {
                            "dataFormatX": function (x) { return d3.time.format('%Y-%m-%d').parse(x); },
                            "tickFormatX": function (x) { return d3.time.format('%a')(x); },
                            "mouseover": function (d, i) {
                              var pos = $(this).offset();
                              var lineclass = $(this).parent().attr("class");
                              lineclass = lineclass.split(" ");
                              console.log(lineclass[2]);
                              if( lineclass[2] == "project_chart_closed"){
                                var linename = "Closed";
                              }else{
                                var linename = "Opened";
                              }
                              $(tt).text(d.y + ' Projects ' +linename)
                                .css({top: topOffset + pos.top, left: pos.left + leftOffset})
                                .show();
                            },
                            "mouseout": function (x) {
                              $(tt).hide();
                            },
                            "tickHintY": 4,
                            "paddingLeft":20,
                             
                          };
                          if($("#project_line_chart").length != 0) {
                          var myChart = new xChart('line-dotted', data, '#project_line_chart', opts);
                          }
                          //xChart End
});
</script>
	