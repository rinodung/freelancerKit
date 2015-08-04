
 <?php 
$attributes = array('class' => '', 'id' => 'payment-form');
echo form_open($form_action, $attributes); 
?>

        <?php // Show PHP errors, if they exist:
        if (isset($errors) && !empty($errors) && is_array($errors)) {
            echo '<div class="alert alert-danger"><h4>Error!</h4>The following error(s) occurred:<ul>';
            foreach ($errors as $e) {
                echo "<li>$e</li>";
            }
            echo '</ul></div>'; 
        }?>

        <div id="payment-errors"></div>

        <span class="help-block">You can pay using: Mastercard, Visa, American Express, JCB, Discover, and Diners Club.</span>

        <div class="alert alert-info"><h4>JavaScript Required!</h4>For security purposes, JavaScript is required in order to complete an order.</div>
         <div class="form-group">
        <label>Card Number</label>
        <input type="text" size="20" autocomplete="off" class="form-control card-number input-medium">
        <span class="help-block">Enter the number without spaces or hyphens.</span>
        </div>

        <div class="form-group">
        <label>CVC</label>
        <input type="text" size="4" autocomplete="off" class="form-control card-cvc input-mini">
        
        </div>
        <div class="form-group">
        <label>Expiration (MM/YYYY)</label>
        <input type="text" size="2" class="form-control card-expiry-month input-mini">
        <span> / </span>
        <input type="text" size="4" class="form-control card-expiry-year input-mini">
        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><?=$this->lang->line('application_send');?></button>
        <a class="btn" data-dismiss="modal"><?=$this->lang->line('application_close');?></a>
        </div>

<?php echo form_close(); ?>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">

    Stripe.setPublishableKey('<?php echo $public_key; ?>');

    Stripe.card.createToken({
  number: $('.card-number').val(),
  cvc: $('.card-cvc').val(),
  exp_month: $('.card-expiry-month').val(),
  exp_year: $('.card-expiry-year').val()
}, stripeResponseHandler);


    function stripeResponseHandler(status, response) {
  var $form = $('#payment-form');

  if (response.error) {
    // Show the errors on the form
    $form.find('.payment-errors').text(response.error.message);
    $form.find('button').prop('disabled', false);
  } else {
    // response contains id and card, which contains additional card details
    var token = response.id;
    // Insert the token into the form so it gets submitted to the server
    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
    // and submit
    $form.get(0).submit();
  }
}


jQuery(function($) {
  $('#payment-form').submit(function(event) {
    var $form = $(this);

    // Disable the submit button to prevent repeated clicks
    $form.find('button').prop('disabled', true);

    Stripe.card.createToken($form, stripeResponseHandler);

    // Prevent the form from submitting with the default action
    return false;
  });
});
</script>

