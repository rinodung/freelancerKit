 <div class="row">

	<div class="col-md-12">
		<h2><?=$company->name;?></h2> 
	</div>
</div>
		<div class="row">
		<div class="col-md-12 marginbottom20">
		<div class="table-head"><?=$this->lang->line('application_company_details');?> <span class="pull-right"><a href="<?=base_url()?>clients/company/update/<?=$company->id;?>/view" class="btn btn-primary" data-toggle="mainmodal"><i class="icon-edit"></i> <?=$this->lang->line('application_edit');?></a></div>
		<div class="subcont">
		<ul class="details col-md-6">
			<li><span><?=$this->lang->line('application_company_name');?>:</span> <?php echo $company->name = empty($company->name) ? "-" : $company->name; ?></li>
			<li><span><?=$this->lang->line('application_primary_contact');?>:</span> <?php if(isset($company->client->firstname)){ echo $company->client->firstname.' '.$company->client->lastname;}else{echo "-";} ?></li>
			<li><span><?=$this->lang->line('application_email');?>:</span> <?php if(isset($company->client->email)){ echo $company->client->email; }else{ echo "-"; } ?></li>
			<li><span><?=$this->lang->line('application_website');?>:</span> <?php echo $company->website = empty($company->website) ? "-" : '<a target="_blank" href="http://'.$company->website.'">'.$company->website.'</a>' ?></li>
			<li><span><?=$this->lang->line('application_phone');?>:</span> <?php echo $company->phone = empty($company->phone) ? "-" : $company->phone; ?></li>
			<?php if($company->vat != ""){?>
			<li><span><?=$this->lang->line('application_vat');?>:</span> <?php echo $company->vat; ?></li>
			<?php } ?>
			<span class="visible-xs"></span>
		</ul>
		<ul class="details col-md-6">
			<li><span><?=$this->lang->line('application_address');?>:</span> <?php echo $company->address = empty($company->address) ? "-" : $company->address; ?></li>
			<li><span><?=$this->lang->line('application_zip_code');?>:</span> <?php echo $company->zipcode = empty($company->zipcode) ? "-" : $company->zipcode; ?></li>
			<li><span><?=$this->lang->line('application_city');?>:</span> <?php echo $company->city = empty($company->city) ? "-" : $company->city; ?></li>
			<li><span><?=$this->lang->line('application_country');?>:</span> <?php echo $company->country = empty($company->country) ? "-" : $company->country; ?></li>
			<li><span><?=$this->lang->line('application_province');?>:</span> <?php echo $company->province = empty($company->province) ? "-" : $company->province; ?></li>

		</ul>
		<br clear="all">
		</div>
		</div>
		</div>
		
<div class="row">
	 	<div class="col-md-12">
	 		<?php if(!isset($company->clients[0])){ ?><div class="alert alert-warning"><?=$this->lang->line('application_client_has_no_contacts');?> <a href="<?=base_url()?>clients/create/<?=$company->id;?>" data-toggle="mainmodal"><?=$this->lang->line('application_add_new_contact');?></a></div>
	 		<?php } ?>
	 	<div class="data-table-marginbottom">

		<div class="table-head"><?=$this->lang->line('application_contacts');?> <span class="pull-right"><a href="<?=base_url()?>clients/create/<?=$company->id;?>" class="btn btn-primary" data-toggle="mainmodal"><?=$this->lang->line('application_add_new_contact');?></a></span></div>
		<div class="table-div">
		<table id="contacts" class="data-no-search table" rel="<?=base_url()?>" cellspacing="0" cellpadding="0">
		<thead>
			<th style="width:10px"></th>
			<th><?=$this->lang->line('application_name');?></th>
			<th class="hidden-xs"><?=$this->lang->line('application_email');?></th>
			<th class="hidden-xs"><?=$this->lang->line('application_phone');?></th>
			<th class="hidden-xs"><?=$this->lang->line('application_mobile');?></th>
			<th class="hidden-xs"><?=$this->lang->line('application_last_login');?></th>
			<th><?=$this->lang->line('application_action');?></th>
		</thead>
		<?php foreach ($company->clients as $value):?>

		<tr id="<?=$value->id;?>" >
			<td style="width:10px"><img class="minipic" src="
               <?php 
                if($value->userpic != 'no-pic.png'){
                  echo base_url()."files/media/".$value->userpic;
                }else{
                  echo get_gravatar($value->email, '20');
                }
                 ?>
                " /></td>
			<td><?=$value->firstname;?> <?=$value->lastname;?></td>
			<td class="hidden-xs"><?=$value->email;?></td>
			<td class="hidden-xs"><?=$value->phone;?></td>
			<td class="hidden-xs"><?=$value->mobile;?></td>
			<td class="hidden-xs"><?php if(!empty($value->last_login)){ echo date($core_settings->date_format.' '.$core_settings->date_time_format, $value->last_login); } else{echo "-";} ?></td>

			<td class="option" style="text-align:left; text-wrap:nowrap " width="9%">
						<a  href="<?=base_url()?>clients/credentials/<?=$value->id;?>" class="btn-option tt" title="<?=$this->lang->line('application_email_login_details');?>" data-toggle="mainmodal"><i class="fa fa-envelope"></i></a>
				        <button type="button" class="btn-option delete po" data-toggle="popover" data-placement="left" data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?=base_url()?>clients/delete/<?=$value->id;?>'><?=$this->lang->line('application_yes_im_sure');?></a> <button class='btn po-close'><?=$this->lang->line('application_no');?></button> <input type='hidden' name='td-id' class='id' value='<?=$value->id;?>'>" data-original-title="<b><?=$this->lang->line('application_really_delete');?></b>"><i class="fa fa-times"></i></button>
				        <a href="<?=base_url()?>clients/update/<?=$value->id;?>" title="<?=$this->lang->line('application_edit');?>" class="btn-option" data-toggle="mainmodal"><i class="fa fa-cog"></i></a>
			</td>
		</tr>

		<?php endforeach;?>
		</table>
		</div>
	</div>
	</div>
</div>
<div class="row">
	
	<div class="col-xs-12 col-sm-12">
<?php $attributes = array('class' => 'note-form', 'id' => '_notes');
		echo form_open(base_url()."clients/notes/".$company->id, $attributes); ?>
 <div class="table-head"><?=$this->lang->line('application_notes');?> <span class=" pull-right"><a id="send" name="send" class="btn btn-primary"><?=$this->lang->line('application_save');?></a></span><span id="changed" class="pull-right label label-warning"><?=$this->lang->line('application_unsaved');?></span></div>

  <textarea class="input-block-level summernote-note" name="note" id="textfield" ><?=$company->note;?></textarea>
</form>
</div>
</div>

		<div class="row">
	 	<div class="col-md-6">
	 	<div class="data-table-marginbottom">

		<div class="table-head"><?=$this->lang->line('application_projects');?></div>
		<div class="table-div">
		<table id="projects" class="data-no-search table" rel="<?=base_url()?>" cellspacing="0" cellpadding="0">
		<thead>
			<th class="hidden-xs" style="width:70px"><?=$this->lang->line('application_project_id');?></th>
			<th><?=$this->lang->line('application_name');?></th>
			<th><?=$this->lang->line('application_progress');?></th>
		</thead>
		<?php foreach ($company->projects as $value):?>

		<tr id="<?=$value->id;?>" >
			<td class="hidden-xs" style="width:70px"><?=$value->reference;?></td>
			<td><?=$value->name;?></td>
            <td class="hidden-xs"><div class="progress progress-striped active progress-medium tt <?php if($value->progress== "100"){ ?>progress-success<?php } ?>" title="<?=$value->progress;?>%">
                      <div class="bar" style="width:<?=$value->progress;?>%"></div>
                </div></td>
		</tr>

		<?php endforeach;?>
		</table>
		<?php if(!$company->projects) { ?>
        <div class="no-files">  
            <i class="fa fa-lightbulb-o"></i><br>
            
            <?=$this->lang->line('application_no_projects_yet');?>
        </div>
         <?php } ?>
		</div>
		</div>
		</div>

		<div class="col-md-6">
	 	<div class="data-table-marginbottom">
		<div class="table-head"><?=$this->lang->line('application_invoices');?></div>
		<div class="table-div">
		<table id="invoices" class="data-no-search table" rel="<?=base_url()?>" cellspacing="0" cellpadding="0">
		<thead>
			<th width="70px"><?=$this->lang->line('application_invoice_id');?></th>
			<th><?=$this->lang->line('application_issue_date');?></th>
			<th><?=$this->lang->line('application_due_date');?></th>
			<th><?=$this->lang->line('application_status');?></th>
		</thead>
		<?php foreach ($company->invoices as $value):?>

		<tr id="<?=$value->id;?>" >
			<td><?=$value->reference;?></td>
			<td><span class="label"><?php $unix = human_to_unix($value->issue_date.' 00:00'); echo date($core_settings->date_format, $unix);?></span></td>
			<td><span class="label <?php if($value->status == "Paid"){echo 'label-success';} if($value->due_date <= date('Y-m-d') && $value->status != "Paid"){ echo 'label-important tt" title="'.$this->lang->line('application_overdue'); } ?>"><?php $unix = human_to_unix($value->due_date.' 00:00'); echo date($core_settings->date_format, $unix);?></span></td>
			<td><span class="label <?php $unix = human_to_unix($value->sent_date.' 00:00'); if($value->status == "Paid"){echo 'label-success';}elseif($value->status == "Sent"){ echo 'label-warning tt" title="'.date($core_settings->date_format, $unix);} ?>"><?=$this->lang->line('application_'.$value->status);?></span></td>
		</tr>
		<?php endforeach;?>
		</table>
		<?php if(!$company->invoices) { ?>
        <div class="no-files">  
            <i class="fa fa-file-text"></i><br>
            
            <?=$this->lang->line('application_no_invoices_yet');?>
        </div>
         <?php } ?>
		</div>
		</div>
		</div>
		</div>

