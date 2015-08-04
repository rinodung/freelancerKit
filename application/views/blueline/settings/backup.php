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

		<div class="table-head"><?=$this->lang->line('application_database_backups');?> <span class="pull-right"><a href="mysql_restore" class="btn btn-primary " data-toggle="mainmodal"><i class="fa fa-upload"></i> <?=$this->lang->line('application_restore_database');?></a> <a href="<?=base_url()?>settings/mysql_backup" class="btn btn-primray"><i class="fa fa-download"></i> <?=$this->lang->line('application_backup_database');?></a></span></div>
		<div class="table-div settings">
		<table id="backup" class="table" cellspacing="0" cellpadding="0">
		<thead>
			<th><?=$this->lang->line('application_date');?></th>
			<th><?=$this->lang->line('application_info');?></th>
			<th><?=$this->lang->line('application_action');?></th>
		</thead>
		<?php if(isset($backups)){
		arsort($backups);
		foreach ($backups as $file):
		 $filename = explode("_", $file);
		?>

		<tr>
			<td><?php echo str_replace('.zip', '', $filename[1]);?> <?php echo str_replace('.zip', '', $filename[2]);?></td>
			<td><?php echo str_replace('-', ' ', $filename[0]);?></td>
			<td class="option" style="width:8%">
				<a class="btn-option tt" href="<?=base_url()?>settings/mysql_download/<?=$file?>" title="<?=$this->lang->line('application_download');?>"><i class="fa fa-download"></i></a>
			</td>
		</tr>

		<?php endforeach; 
		}else{ ?>
		<tr>
			<td colspan="4"><?=$this->lang->line('application_no_backups');?></td>
		</tr> 
		<?php } ?>
	 	</table>
	 	</div>
	 	</div>
	</div>