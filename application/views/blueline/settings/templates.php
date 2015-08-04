<script type="text/javascript" src="<?=base_url()?>assets/blackline/js/ckeditor/ckeditor.js"></script>
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

<div class="table-head"><?=$this->lang->line('application_'.$template.'_email_template');?>

<div class="btn-group pull-right">
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <?php if($template){echo $this->lang->line('application_'.$template.'_email_template');}?> <span class="caret"></span>
          </button>
          <ul class="dropdown-menu pull-right" role="menu">
         <?php foreach ($template_files as $value) { ?>
         	 <li><a href="<?=base_url()?>settings/templates/<?=$value;?>"><?=$this->lang->line('application_'.$value.'_email_template');?></a></li>
        <?php } ?>
        </div>
</div>
<?php   
$attributes = array('class' => '', 'id' => 'template_form');
echo form_open_multipart($form_action, $attributes); 
?>
<div class="table-div">
<br>
	<div class="form-group">

	<?php if(isset($settings->{$template.'_mail_subject'})){ ?>
<?=$this->lang->line('application_subject');?></td>
<input type="text" name="<?=$template;?>_mail_subject" class="required no-margin form-control" value="<?=$settings->{$template.'_mail_subject'};?>">

	<?php } ?>
</div>
<div class="form-group">
<label><?=$this->lang->line('application_mail_body');?></label>
<textarea class="required ckeditor"  name="mail_body"><?=$email;?></textarea>
</div>
<div class="form-group">
<a href="<?=base_url()?>settings/settings_reset/email_<?=$template;?>" class="btn btn-xs btn-primary tt" title="<?=$this->lang->line('application_reset_default');?>"><i class="fa fa-refresh"></i></a>
<br>
				<small style="font-weight: bold;"> 
					<?=$this->lang->line('application_short_tags');?>:<br/>
					{logo}<br/> 
					{invoice_logo}<br/> 
					{client_link}<br/> 
					{client_contact}<br/> 
					{due_date}<br/> 
					{invoice_id}<br/> 
					{company}<br/>
				</small>
			</div>
<div class="form-group">			
<input type="submit" name="send" class="btn btn-primary" value="<?=$this->lang->line('application_save');?>"/>
</div>
	
	<?php echo form_close(); ?>
</div>
</div>