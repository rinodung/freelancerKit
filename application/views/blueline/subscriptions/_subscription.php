<?php   
$attributes = array('class' => '', 'id' => '_subscriptions');
echo form_open($form_action, $attributes); 
?>

<?php if(isset($subscription)){ ?>
<input id="id" type="hidden" name="id" value="<?=$subscription->id;?>" />
<?php } ?>
<?php if(isset($view)){ ?>
<input id="view" type="hidden" name="view" value="true" />
<?php } ?>
<input id="status" name="status" type="hidden" value="Active"> 


 <div class="form-group">
        <label for="reference"><?=$this->lang->line('application_reference_id');?> *</label>
        <input id="reference" type="text" name="reference" class="form-control"  value="<?php if(isset($subscription) ){echo $subscription->reference;} else{ echo $core_settings->subscription_reference; } ?>"   readonly="readonly" />
</div> 

 <div class="form-group">
        <label for="client"><?=$this->lang->line('application_client');?></label>
        <?php $options = array();
                $options['1'] = '-';
                foreach ($companies as $value):  
                $options[$value->id] = $value->name;
                endforeach;
        if(isset($subscription) ){$client = $subscription->company_id;}else{$client = "";}
        echo form_dropdown('company_id', $options, $client, 'style="width:100%" class="chosen-select"');?>
</div> 

   
<?php if(isset($subscription) ){ ?>
 <div class="form-group">
        <label for="status"><?=$this->lang->line('application_status');?></label>
        <?php $options = array(
                  'Active'  => $this->lang->line('application_Active'),
                  'Inactive'    => $this->lang->line('application_Inactive'),
                );
                echo form_dropdown('status', $options, $subscription->status, 'style="width:100%" class="chosen-select"'); ?>

</div> 
<?php } ?>

<?php if(!isset($subscription) ){ ?>
 

<div class="form-group">
<p id="recurring">
        <label for="recurring"><?=$this->lang->line('application_frequency');?></label>
        <?php $options = array(
                  '+7 day'  => $this->lang->line('application_weekly'),
                  '+14 day' => $this->lang->line('application_every_other_week'),
                  '+1 month' => $this->lang->line('application_monthly'),
                  '+3 month' => $this->lang->line('application_quarterly'),
                  '+6 month' => $this->lang->line('application_semi_annually'),
                  '+1 year' => $this->lang->line('application_annually'),
                );
                echo form_dropdown('frequency', $options, '+7 day', 'style="width:100%" class="chosen-select"'); ?>

</p>
</div> 

<?php } ?>

 <div class="form-group">
        <label for="issue_date"><?=$this->lang->line('application_issue_date');?></label>
        <input id="issue_date" type="text" name="issue_date" class="required datepicker form-control" value="<?php if(isset($subscription) ){echo $subscription->issue_date;} ?>"  />
</div> 

 <div class="form-group">
        <label for="end_date"><?=$this->lang->line('application_end_date');?></label>
        <input id="end_date" type="text" name="end_date" class="required datepicker form-control" value="<?php if(isset($subscription) ){echo $subscription->end_date;} ?>"  />
</div> 

 <div class="form-group">
        <label for="currency"><?=$this->lang->line('application_currency');?></label>
        <input id="currency" type="text" name="currency" class="required form-control" value="<?php if(isset($subscription) ){ echo $subscription->currency; }else { echo $core_settings->currency; } ?>"/>
</div> 

 <div class="form-group">
        <label for="currency"><?=$this->lang->line('application_discount');?></label>
        <input class="form-control" name="discount" id="appendedInput" type="text" value="<?php if(isset($subscription) ){ echo $subscription->discount;} ?>"/>
</div>
 
  <div class="form-group">
        <label for="terms"><?=$this->lang->line('application_custom_tax');?></label>
        <input class="form-control" name="tax" type="text" value="<?php if(isset($subscription)){ echo $subscription->tax;} ?>"/>
 </div>

 <div class="form-group">
        <label for="terms"><?=$this->lang->line('application_terms');?></label>
        <textarea id="terms" name="terms" class="textarea required form-control" style="height:100px"><?php if(isset($subscription) ){echo $subscription->terms;}else{ echo $core_settings->invoice_terms; }?></textarea>
</div> 

        <div class="modal-footer">
        <input type="submit" name="send" class="btn btn-primary" value="<?=$this->lang->line('application_save');?>"/>
        <a class="btn" data-dismiss="modal"><?=$this->lang->line('application_close');?></a>
        </div>


<?php echo form_close(); ?>