 
          <div class="row">
              <div class="col-xs-12 col-sm-12">
            <a href="<?=base_url()?>invoices/update/<?=$invoice->id;?>/view" class="btn btn-primary" data-toggle="mainmodal"><i class="fa fa-edit visible-xs"></i><span class="hidden-xs"><?=$this->lang->line('application_edit_invoice');?></span></a>
			<a href="<?=base_url()?>invoices/item/<?=$invoice->id;?>" class="btn btn-primary" data-toggle="mainmodal"><i class="fa fa-plus visible-xs"></i><span class="hidden-xs"><?=$this->lang->line('application_add_item');?></span></a>
			<a href="<?=base_url()?>invoices/preview/<?=$invoice->id;?>" class="btn btn-primary"><i class="fa fa-file visible-xs-*"></i><span class="hidden-xs"><?=$this->lang->line('application_preview');?></span></a>
			<?php if($invoice->status != "Paid" && isset($invoice->company->name)){ ?><a href="<?=base_url()?>invoices/sendinvoice/<?=$invoice->id;?>" class="btn btn-primary"><i class="fa fa-envelope visible-xs"></i><span class="hidden-xs"><?=$this->lang->line('application_send_invoice_to_client');?></span></a><?php } ?>

              </div>
          </div>
          <div class="row">

		<div class="col-md-12">
		<div class="table-head"><?=$this->lang->line('application_invoice_details');?></div>
		<div class="subcont">
		<ul class="details col-xs-12 col-sm-6">
			<li><span><?=$this->lang->line('application_invoice_id');?>:</span> <?=$invoice->reference;?></li>
			<li class="<?=$invoice->status;?>"><span><?=$this->lang->line('application_status');?>:</span>
			<a class="label label-default <?php $unix = human_to_unix($invoice->sent_date.' 00:00'); $unix2 = human_to_unix($invoice->paid_date.' 00:00'); if($invoice->status == "Paid"){echo 'label-success tt" title="'.date($core_settings->date_format, $unix2);}elseif($invoice->status == "Sent"){ echo 'label-warning tt" title="'.date($core_settings->date_format, $unix);} ?>"><?=$this->lang->line('application_'.$invoice->status);?>
			</a>
			</li>
			<li><span><?=$this->lang->line('application_issue_date');?>:</span> <?php $unix = human_to_unix($invoice->issue_date.' 00:00'); echo date($core_settings->date_format, $unix);?></li>
			<li><span><?=$this->lang->line('application_due_date');?>:</span> <a class="label label-default <?php if($invoice->status == "Paid"){echo 'label-success';} if($invoice->due_date <= date('Y-m-d') && $invoice->status != "Paid"){ echo 'label-important tt" title="'.$this->lang->line('application_overdue'); } ?>"><?php $unix = human_to_unix($invoice->due_date.' 00:00'); echo date($core_settings->date_format, $unix);?></a></li>
			<?php if(isset($invoice->company->vat)){?> 
			<li><span><?=$this->lang->line('application_vat');?>:</span> <?php echo $invoice->company->vat; ?></li>
			<?php } ?>
			<?php if(isset($invoice->project->name)){?>
			<li><span><?=$this->lang->line('application_projects');?>:</span> <?php echo $invoice->project->name; ?></li>
			<?php } ?>
			<span class="visible-xs"></span>
		</ul>
		<ul class="details col-xs-12 col-sm-6">
			<?php if(isset($invoice->company->name)){ ?>
			<li><span><?=$this->lang->line('application_company');?>:</span> <a href="<?=base_url()?>clients/view/<?=$invoice->company->id;?>" class="label label-info"><?=$invoice->company->name;?></a></li>
			<li><span><?=$this->lang->line('application_contact');?>:</span> <?php if(isset($invoice->company->client->firstname)){ ?><?=$invoice->company->client->firstname;?> <?=$invoice->company->client->lastname;?> <?php }else{echo "-";} ?></li>
			<li><span><?=$this->lang->line('application_street');?>:</span> <?=$invoice->company->address;?></li>
			<li><span><?=$this->lang->line('application_city');?>:</span> <?=$invoice->company->zipcode;?> <?=$invoice->company->city;?></li>
			<li><span><?=$this->lang->line('application_province');?>:</span> <?php echo $invoice->company->province = empty($invoice->company->province) ? "-" : $invoice->company->province; ?></li>
			<?php }else{ ?>
				<li><?=$this->lang->line('application_no_client_assigned');?></li>
			<?php } ?>
		</ul>
		<br clear="all">
		</div>
		</div>
		</div>

		<div class="row">
		<div class="col-md-12">
		<div class="table-head"><?=$this->lang->line('application_invoice_items');?> <span class=" pull-right"><a href="<?=base_url()?>invoices/item/<?=$invoice->id;?>" class="btn btn-md btn-primary" data-toggle="mainmodal"><i class="fa fa fa-plus visible-xs"></i><span class="hidden-xs"><?=$this->lang->line('application_add_item');?></span></a></span></div>
		<div class="table-div min-height-200">
		<table class="table" id="items" rel="<?=base_url()?>" cellspacing="0" cellpadding="0">
		<thead>
		<th width="4%"><?=$this->lang->line('application_action');?></th>
			<th><?=$this->lang->line('application_name');?></th>
			<th class="hidden-xs"><?=$this->lang->line('application_description');?></th>
			<th class="hidden-xs" width="8%"><?=$this->lang->line('application_hrs_qty');?></th>
			<th class="hidden-xs" width="12%"><?=$this->lang->line('application_unit_price');?></th>
			<th class="hidden-xs" width="12%"><?=$this->lang->line('application_sub_total');?></th>
		</thead>
		<?php $i = 0; $sum = 0;?>
		<?php foreach ($items as $value):?>
		<tr id="<?=$value->id;?>" >
		<td class="option" style="text-align:left;" width="8%">
				        <button type="button" class="btn-option delete po" data-toggle="popover" data-placement="left" data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?=base_url()?>invoices/item_delete/<?=$invoice->invoice_has_items[$i]->id;?>/<?=$invoice->id;?>'><?=$this->lang->line('application_yes_im_sure');?></a> <button class='btn po-close'><?=$this->lang->line('application_no');?></button> <input type='hidden' name='td-id' class='id' value='<?=$value->id;?>'>" data-original-title="<b><?=$this->lang->line('application_really_delete');?></b>"><i class="fa fa-times"></i></button>
				        <a href="<?=base_url()?>invoices/item_update/<?=$invoice->invoice_has_items[$i]->id;?>" title="<?=$this->lang->line('application_edit');?>" class="btn-option" data-toggle="mainmodal"><i class="fa fa-cog"></i></a>
			</td>
	
			<td><?php if(!empty($value->name)){echo $value->name;}else{ echo $invoice->invoice_has_items[$i]->item->name; }?></td>
			<td class="hidden-xs"><?=$invoice->invoice_has_items[$i]->description;?></td>
			<td class="hidden-xs" align="center"><?=$invoice->invoice_has_items[$i]->amount;?></td>
			<td class="hidden-xs"><?php echo sprintf("%01.2f",$invoice->invoice_has_items[$i]->value);?></td>
			<td class="hidden-xs"><?php echo sprintf("%01.2f",$invoice->invoice_has_items[$i]->amount*$invoice->invoice_has_items[$i]->value);?></td>

		</tr>
		
		<?php $sum = $sum+$invoice->invoice_has_items[$i]->amount*$invoice->invoice_has_items[$i]->value; $i++;?>
		
		<?php endforeach;
		if(empty($items)){ echo "<tr><td colspan='6'>".$this->lang->line('application_no_items_yet')."</td></tr>";}
		if(substr($invoice->discount, -1) == "%"){ $discount = sprintf("%01.2f", round(($sum/100)*substr($invoice->discount, 0, -1), 2)); }
		else{$discount = $invoice->discount;}
		$sum = $sum-$discount;

		if($invoice->tax != ""){
			$tax_value = $invoice->tax;
		}else{
			$tax_value = $core_settings->tax;
		}

		$tax = sprintf("%01.2f", round(($sum/100)*$tax_value, 2));
		$sum = sprintf("%01.2f", round($sum+$tax, 2));
		?>
		<?php if ($discount != 0): ?>
		<tr>
			<td colspan="5" align="right"><?=$this->lang->line('application_discount');?></td>
			<td>- <?=$invoice->discount;?></td>
		</tr>	
		<?php endif ?>
		<?php if ($tax_value != "0"){ ?>
		<tr>
			<td colspan="5" align="right"><?=$this->lang->line('application_tax');?> (<?= $tax_value?>%)</td>
			<td><?=$tax?></td>
		</tr>
		<?php } ?>
		<tr class="active">
			<td colspan="5" align="right"><?=$this->lang->line('application_total');?></td>
			<td> <?=$invoice->currency?> <?=$sum;?></td>
		</tr>
		</table>
		
		</div>
		
		<span class="pull-left">
<?php if($core_settings->stripe == "1" && $sum != "0.00" && empty($invoice->paid_date)){ ?>
<button href="<?=base_url()?>invoices/stripepay/<?=$invoice->id;?>" class="btn btn-primary" data-toggle="mainmodal"><?=$this->lang->line('application_pay_with_credit_card');?></button>
<?php } ?>
</span>

<span class="pull-right">
			<?php if($core_settings->paypal == "1" && $sum != "0.00" && empty($invoice->paid_date)){ ?><br/>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="cmd" value="_xclick">
						<input type="hidden" name="business" value="<?=$core_settings->paypal_account;?>">
						<input type="hidden" name="item_name" value="<?=$invoice->reference;?>">
						<input type="hidden" name="item_number" value="<?=$invoice->reference;?>">
						<input type="hidden" name="image_url" value="<?=base_url()?><?=$core_settings->invoice_logo;?>">
						<input type="hidden" name="amount" value="<?=$sum;?>">
						<input type="hidden" name="no_shipping" value="1">
						<input type="hidden" name="no_note" value="1">
						<input type="hidden" name="currency_code" value="<?= $core_settings->paypal_currency;?>">
						<input type="hidden" name="bn" value="FC-BuyNow">
						<input type="submit" class="btn btn-primary" name="send" value="<?=$this->lang->line('application_pay_via_paypal');?>">
						<input type="hidden" name="return" value="<?=base_url()?>invoices/view/<?=$invoice->id;?>"> 
						<input type="hidden" name="cancel_return" value="<?=base_url()?>invoices/view/<?=$invoice->id;?>">
						<input type="hidden" name="rm" value="2">
						<input type="hidden" name="notify_url" value="<?=base_url()?>paypalipn" /> 
						<input type="hidden" name="custom" value="invoice-<?=$sum;?>">     
						</form>
						<?php } ?>
</span>	
<br>



		</div>
		</div>

		

