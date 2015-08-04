<div id="row"> 
	
		<div class="col-md-3">
			<div class="list-group">
				<?php foreach ($submenu as $name=>$value):
				$badge = "";
				$active = "";
				if($value == "settings/updates"){ $active = 'active';}?>
	               <a class="list-group-item <?=$active;?>" id="<?php $val_id = explode("/", $value); if(!is_numeric(end($val_id))){echo end($val_id);}else{$num = count($val_id)-2; echo $val_id[$num];} ?>" href="<?=site_url($value);?>"><?=$badge?> <?=$name?></a>
	            <?php endforeach;?>
			</div>
		</div>


<div class="col-md-9">
		<div class='alert alert-warning'><?=$this->lang->line('application_always_make_backup');?></div>
		<div class='alert alert-info'>You are running version <?=$core_settings->version;?></div>
		<?php if($writable == "FALSE"){ ?>
		<div class='alert alert-danger'>No write permissions to the following folders <b>/application/</b> and <b>/assets/</b> <br> Please change the permissions of those folders temporary to 777 in order to install the updates. Change the permissions back to 755 after you have installed all updates.</div>
		<?php } ?>
		<?php if($version_mismatch != "FALSE"){ ?>
		<div class='alert alert-danger'>Your database version does not match with file version!</div>
		<?php } ?>

		<?php if($curl_error){ ?>
		<div class='alert alert-danger'>Could not connect to update server. Please check if php_curl extension is enabled!</div>
		<?php } ?>

		<div class="table-head"><?=$this->lang->line('application_system_updates');?> <span class="pull-right"> <a href="<?=base_url()?>settings/updates" class="btn btn-primary"><i class="fa fa-refresh"></i> <?=$this->lang->line('application_check_for_updates');?></a> <a href="<?=base_url()?>settings/mysql_backup" class="btn btn-primary"><i class="fa fa-download"></i> <?=$this->lang->line('application_backup_database');?></a></span></div>
		<div class="table-div"><table id="updates" class="table" cellspacing="0" cellpadding="0">
		<thead>
			<th><?=$this->lang->line('application_update');?></th>
			<th><?=$this->lang->line('application_release_date');?></th>
			<th><?=$this->lang->line('application_info');?></th>
			<th><?=$this->lang->line('application_action');?></th>
		</thead>
		<?php  $first = FALSE;
		foreach ($lists as $key => $file):
		 if($file->version > $core_settings->version){
		?>

		<tr>
			<td><?php echo "Core ".$file->version;?></td>
			<td><?=$file->date;?></td>
			<td><a href="#" class="po" rel="popover" data-placement="top" data-content="<?=$file->changelog;?>" data-original-title="Update <?=$file->version;?>"><?=$this->lang->line('application_view_changelog');?></a></td>
			
			<td class="option">
				<?php if($first){echo $this->lang->line('application_previous_update_required');}else{ ?>
				<a <?php if(in_array($file->file, $downloaded_updates)){echo 'class="btn btn-xs disabled" disabled="disabled"';}else{ echo 'href="update_download/'.$file->file.'" class="btn btn-xs btn-success button-loader"';} ?>><?=$this->lang->line('application_download');?></a>
				<a <?php if(in_array($file->file, $downloaded_updates) && $writable == "TRUE"){echo 'href="update_install/'.$file->file.'/'.$file->version.'" class="btn btn-xs btn-success button-loader"';}else{ echo 'class="btn btn-xs btn-option disabled" disabled="disabled"';} ?>><?=$this->lang->line('application_install');?></a>
				<?php } ?>
			</td>
		</tr>

		<?php $first = TRUE; } endforeach; 
		if(!$first){ ?>
		<tr>
			<td colspan="4"><?=$this->lang->line('application_system_up_to_date');?></td>
		</tr> 
		<?php } ?>
	 	</table>
	 	</div>
	</div>	</div>