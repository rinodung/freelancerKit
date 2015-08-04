<?php $attributes = array('class' => 'form-signin', 'role'=> 'form', 'id' => 'forgotpass'); ?>
<?=form_open('forgotpass', $attributes)?>
        <div class="logo"><img src="<?=base_url()?><?=$core_settings->invoice_logo;?>" alt="<?=$core_settings->company;?>"></div>
        <?php if(isset($message)) { $message = explode(':', $message)?>
            <div class="forgotpass-success">
              <?=$message[1]?>
            </div>
        <?php }else{ ?>
          <div class="forgotpass-info"><?=$this->lang->line('application_identify_account');?></div>
          <?php } ?>
          <div class="form-group">
            <label for="email"><?=$this->lang->line('application_email');?></label>
            <input type="text" class="form-control" name="email" id="email" placeholder="<?=$this->lang->line('application_email');?>">
          </div>

          <input type="submit" class="btn btn-primary" value="<?=$this->lang->line('application_reset_password');?>" />
          <div class="forgotpassword"><a href="<?=site_url("login");?>"><?=$this->lang->line('application_go_to_login');?></a></div>
<?=form_close()?>