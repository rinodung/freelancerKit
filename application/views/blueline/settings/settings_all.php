<div id="row">
	
		<div class="col-md-3">
			<div class="list-group">
				<?php foreach ($submenu as $name=>$value):
				$badge = "";
				$active = "";
				if($value == "settings/updates" && $update_count){ $badge = '<span class="badge badge-success">'.$update_count.'</span>';}
				if($value == "settings"){ $active = 'active';}?>
	               <a class="list-group-item <?=$active;?>" id="<?php $val_id = explode("/", $value); if(!is_numeric(end($val_id))){echo end($val_id);}else{$num = count($val_id)-2; echo $val_id[$num];} ?>" href="<?=site_url($value);?>"><?=$badge?> <?=$name?></a>
	            <?php endforeach;?>
			</div>
		</div>


<div class="col-md-9">
<div class="table-head"><?=$this->lang->line('application_settings');?></div>
<?php   
$attributes = array('class' => '', 'id' => 'settings_form');
echo form_open_multipart($form_action, $attributes); 
?>
<div class="table-div">
<br>
	<div class="form-group">
		<label><?=$this->lang->line('application_company_name');?></label>
		<input type="text" name="company" class="required form-control" value="<?=$settings->company;?>" required>
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_contact');?></label>
		<input type="text" name="invoice_contact" class="required form-control" value="<?=$settings->invoice_contact;?>" required>
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_address');?></label>
		<input type="text" name="invoice_address" class="required form-control" value="<?=$settings->invoice_address;?>" required>
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_city');?></label>
		<input type="text" name="invoice_city" class="required form-control" value="<?=$settings->invoice_city;?>" required>
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_phone');?></label>
		<input type="text" name="invoice_tel" class="required form-control" value="<?=$settings->invoice_tel;?>" required>
	</div>
		<div class="form-group">
		<label><?=$this->lang->line('application_email');?></label>
		<input type="text" name="email" class="required form-control" value="<?=$settings->email;?>" required>
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_domain');?> <button type="button" class="btn-option po pull-right" data-toggle="popover" data-placement="left" data-content="Full URL to your Freelance Cockpit installation. Including subfolder i.e. http://www.yoursite.com/FC/" data-original-title="Domain URL"> <i class="fa fa-info-circle"></i></button>
		</label>
		<input type="text" name="domain" class="required form-control" value="<?=$settings->domain;?>" required>
			
		
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_logo');?> (max 200x200) <button type="button" class="btn-option po pull-right" data-toggle="popover" data-placement="right" data-content="<div class='logo' style='padding:10px'><img src='<?=$core_settings->logo;?>'></div>" data-original-title="<?=$this->lang->line('application_logo');?>"> <i class="fa fa-info-circle"></i></button>
		</label>
		<div class="form-group">
                <div><input id="uploadFile" class="form-control uploadFile" placeholder="Choose File" disabled="disabled" />
                          <div class="fileUpload btn btn-primary">
                              <span><i class="fa fa-upload"></i><span class="hidden-xs"> <?=$this->lang->line('application_select');?></span></span>
                              <input id="uploadBtn" type="file" name="userfile" class="upload" />
                          </div>
            </div>
        </div>
                	
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_invoice');?> <?=$this->lang->line('application_logo');?>  (max 200x200) <button type="button" class="btn-option po " data-toggle="popover" data-placement="right"  data-content="<div style='padding:10px'><img src='<?=$core_settings->invoice_logo;?>'></div>" data-original-title="<?=$this->lang->line('application_invoice');?> <?=$this->lang->line('application_logo');?>"> <i class="fa fa-info-circle"></i></button>
		</label>
		
			<div class="form-group">
                <div>
                <input id="uploadFile2" class="form-control uploadFile" placeholder="Choose File" disabled="disabled" />
                          <div class="fileUpload btn btn-primary">
                              <span><i class="fa fa-upload"></i><span class="hidden-xs"> <?=$this->lang->line('application_select');?></span></span>
                              <input id="uploadBtn2" type="file" name="userfile2" class="upload" />
                          </div>
                  </div>
              </div>
		
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_tax');?></label>
		

			<div class="input-group col-md-2">
			  
			  <input type="text"  name="tax"  value="<?=$settings->tax;?>" class="form-control" placeholder="">
			 
			  <span class="input-group-addon">%</span>
			</div>
		
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_default_currency');?></label>
		

			<div class="input-group col-md-2">
			  
			  <input type="text"  name="currency" class="form-control" value="<?=$settings->currency;?>">
			</div>
		
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_default_template');?></label>
		 <?php $options = array();
			if ($handle = opendir('application/views/')) {

		        while (false !== ($entry = readdir($handle))) {
		              if ($entry != "." && $entry != ".." && $entry != "index.html") {
		              	$options[$entry] = ucwords($entry);
	                	}
				}
				closedir($handle);
			}
			echo form_dropdown('template', $options, $settings->template, 'style="width:250px" class="chosen-select"'); ?>
		
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_default_language');?></label>
		 <?php $options = array();
			if ($handle = opendir('application/language/')) {

		        while (false !== ($entry = readdir($handle))) {
		              if ($entry != "." && $entry != "..") {
		              	$options[$entry] = ucwords($entry);
	                	}
				}
				closedir($handle);
			}
			echo form_dropdown('language', $options, $settings->language, 'style="width:250px" class="chosen-select"'); ?>
		
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_date_format');?></label>
		 <?php $options = array(
			'F j, Y'  => date("F j, Y"),
			'Y/m/d'    => date("Y/m/d"),
			'm/d/Y' => date("m/d/Y"),
			'd/m/Y' => date("d/m/Y"),
			'd.m.Y' => date("d.m.Y"),
			'd-m-Y' => date("d-m-Y"),
			);
			echo form_dropdown('date_format', $options, $settings->date_format, 'style="width:250px" class="chosen-select"'); ?>
		
	</div>
	<div class="form-group">
		<label><?=$this->lang->line('application_date_time_format');?></label>
		 <?php $options = array(
			'g:i a'  => date("g:i a"),
			'g:i A'    => date("g:i A"),
			'H:i' => date("H:i"),
			);
			echo form_dropdown('date_time_format', $options, $settings->date_time_format, 'style="width:250px" class="chosen-select"'); ?>
		
	</div>
		<div class="form-group">
			<label><?=$this->lang->line('application_default_terms');?></label>
			<textarea class="textarea summernote" name="invoice_terms" rows="5"><?=$settings->invoice_terms;?></textarea>
		</div>
		<div class="form-group">
			 <input type="submit" name="send" class="btn btn-primary" value="<?=$this->lang->line('application_save');?>"/>
			
		</div>

	</table>
	
	<?php echo form_close(); ?>
	</div>
	</div>

	</div>
