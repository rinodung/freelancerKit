<?php 
/**
 * @file        Fullpage View
 * @author      Luxsys <support@luxsys-apps.com>
 * @copyright   By Luxsys (http://www.luxsys-apps.com)
 * @version     2.2.0
 */
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
    <link rel="SHORTCUT ICON" href="<?=base_url()?>assets/blueline/img/favicon.ico"/>
    <title><?=$core_settings->company;?></title> 

    <!-- Bootstrap core CSS and JS -->
    <link href="<?=base_url()?>assets/blueline/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?=base_url()?>assets/blueline/js/plugins/jquery-1.11.0.min.js"></script>


    <!-- Custom styles for this template -->
    <link href="<?=base_url()?>assets/blueline/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>

    <!-- Plugins -->
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/jquery-ui-1.10.3.custom.min.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/datepicker.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/bootstrap-timepicker.css"/>
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/colorpicker.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/refineslide.css"/>
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/jquery-slider.css" />
    <!--<link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/checkbox-radio-switch.css" />-->
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/summernote.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/chosen.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/dataTables.bootstrap.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/jquery.mCustomScrollbar.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/xcharts.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/nprogress.css" />
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/plugins/jquery-labelauty.css" />

    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/blueline.css"/>
    <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/user.css"/> 
       <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
      html{
        height: 100%;
      }
      body {
        padding-bottom: 40px;
        height: 100%;
        /*background:#444;*/
      }  
    </style>
     
  <title><?=$core_settings->company;?></title>
 </head>
  <body>
  <div class="container">
  
  		<img class="fullpage-logo" src="<?=base_url()?><?=$core_settings->invoice_logo;?>" alt="<?=$core_settings->company;?>" />
     

    <div>
     <?php if($this->session->flashdata('message')) { $exp = explode(':', $this->session->flashdata('message'))?>
	    <div id="quotemessage" class="alert alert-success"><span><?=$exp[1]?></span></div>
	    <?php } ?>
<?=$yield?>
<br clear="all"/>
	</div>

</div>
     <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery-ui-1.10.3.custom.min.js"></script>
    
    <!-- Plugins -->
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/date-time/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/date-time/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/bootstrap-colorpicker.min.js"></script>
    
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.knob.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.autosize-min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.inputlimiter.1.3.1.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.maskedinput.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.refineslide.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/summernote.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.mCustomScrollbar.concat.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery.nanoscroller.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jqBootstrapValidation.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/chart.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/d3.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/xcharts.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/nprogress.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/jquery-labelauty.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/plugins/validator.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/blueline.js"></script>
    

      <script type="text/javascript" charset="utf-8">
      
//Validation
  $("form").validator();

        $(document).ready(function(){ 

              $(".removehttp").change(function(e){
                $(this).val($(this).val().replace("http://",""));
              });

        });
    </script>

 </body>
</html>
