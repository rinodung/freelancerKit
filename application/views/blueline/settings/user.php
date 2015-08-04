<div id="row">
	
		<div class="col-md-3">
			<div class="list-group">
				<?php foreach ($submenu as $name=>$value):
				$badge = "";
				$active = "";
				if($value == "settings/updates"){ $badge = '<span class="badge badge-success">'.$update_count.'</span>';}
				if($name == $breadcrumb){ $active = 'active';}?>
	               <a class="list-group-item <?=$active;?>" id="<?php $val_id = explode("/", $value); if(!is_numeric(end($val_id))){echo end($val_id);}else{$num = count($val_id)-2; echo $val_id[$num];} ?>" href="<?=site_url($value);?>"><?=$badge?> <?=$name?></a>
	            <?php endforeach;?>
			</div>
		</div>


<div class="col-md-9">
		<div class="table-head"><?=$this->lang->line('application_all_users');?> <span class="pull-right"><a href="<?=base_url()?>settings/user_create" class="btn btn-primary" data-toggle="mainmodal"><?=$this->lang->line('application_create_user');?></a></span></div>
		<div class="table-div">
		<table id="users" class="data-no-search table" cellspacing="0" cellpadding="0">
		<thead>
			<th style="width:10px"></th>
			<th><?=$this->lang->line('application_username');?></th>
			<th><?=$this->lang->line('application_full_name');?></th>
			<th><?=$this->lang->line('application_title');?></th>
			<th><?=$this->lang->line('application_email');?></th>
			<th><?=$this->lang->line('application_status');?></th>
			<th><?=$this->lang->line('application_admin');?></th>
			<th><?=$this->lang->line('application_last_login');?></th>
			<th><?=$this->lang->line('application_action');?></th>
		</thead>
		<?php foreach ($users as $user):?>

		<tr id="<?=$user->id;?>">
			<td  style="width:10px">
			<img class="minipic" src="
               <?php 
                if($user->userpic != 'no-pic.png'){
                  echo base_url()."files/media/".$user->userpic;
                }else{
                  echo get_gravatar($user->email, '20');
                }
                 ?>
                "/>
            </td>
			<td><?=$user->username;?></td>
			<td><?php echo $user->firstname." ".$user->lastname;?></td>
			<td><?=$user->title;?></td>
			<td><?=$user->email;?></td>
			<td><span class="label label-<?php if($user->status == "active"){ echo "success"; }else{echo "important";} ?>"><?=$this->lang->line('application_'.$user->status);?></span></td>
			<td><span class="label label-<?php if($user->admin == "1"){ echo "success"; }else{echo "";} ?>"><?php if($user->admin){echo $this->lang->line('application_yes');}else{echo $this->lang->line('application_no');}?></span></td>
			<td><span><?php if(!empty($user->last_login)){ echo date($core_settings->date_format.' '.$core_settings->date_time_format, $user->last_login); } else{echo "-";}?></span></td>
			
			<td class="option" width="8%">
				        <button type="button" class="btn-option delete po" data-toggle="popover" data-placement="left" data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?=base_url()?>settings/user_delete/<?=$user->id;?>'><?=$this->lang->line('application_yes_im_sure');?></a> <button class='btn po-close'><?=$this->lang->line('application_no');?></button> <input type='hidden' name='td-id' class='id' value='<?=$user->id;?>'>" data-original-title="<b><?=$this->lang->line('application_really_delete');?></b>"><i class="fa fa-times"></i></button>
				        <a href="<?=base_url()?>settings/user_update/<?=$user->id;?>" class="btn-option" data-toggle="mainmodal"><i class="fa fa-cog"></i></a>
			</td>
		</tr>

		<?php endforeach;?>
	 	</table>
	 	</div>
	</div>
</div>