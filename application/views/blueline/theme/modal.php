<script type="text/javascript" src="<?=base_url()?>assets/blueline/js/ajax.js"></script>
<script>$(document).ready(function(){ 
    
    $("form").validator();

//button loaded on click
        $(document).on("click", '.button-loader', function (e) {
          var value = $( this ).text();
            $(this).html('<i class="fa fa-spinner fa-spin"></i> '+ value);
        });

//item-selector
    $('.additem').click(function(e){
      $('#item-selector').slideUp('fast');
      $('#item-editor').slideDown('slow');
    });

//upload button
        $(document).on("change", '#uploadBtn', function (e) {
          var value = $( this ).val().replace(/\\/g, '/').replace(/.*\//, '');
            $("#uploadFile").val(value);
        });
        $(document).on("change", '#uploadBtn2', function (e) {
          var value = $( this ).val().replace(/\\/g, '/').replace(/.*\//, '');
            $("#uploadFile2").val(value);
        });

// Checkbox Plugin

        $(".checkbox").labelauty(); 
 

  $('.datepicker').datepicker({"format": 'yyyy-mm-dd', "autoclose": true});
  $('.date-picker').datepicker({"format": 'yyyy-mm-dd', "autoclose": true});
});
$.ajaxSetup ({
    cache: false
});
</script>

 <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h4 class="modal-title"><?=$title;?></h4>
      </div>
      <div class="modal-body">
                    
        <?=$yield?>         

     
    </div>
  </div>



