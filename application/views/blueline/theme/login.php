<?php 
/**
 * @file        Login View
 * @author      Luxsys <support@luxsys-apps.com>
 * @copyright   By Luxsys (http://www.luxsys-apps.com)
 * @license     http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version     2.2.0
 * @link        http://pear.php.net/package/PackageName
 */
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title><?=$core_settings->company;?></title>
    
    <link href="<?=base_url()?>assets/blueline/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/blueline/css/blueline.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600,700' rel='stylesheet' type='text/css'>
    <link rel="SHORTCUT ICON" href="<?=base_url()?>assets/blueline/img/favicon.ico"/>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body class="login">
    <div class="container-fluid">
      <div class="row">
        <?=$yield?>
      </div>
    </div>
    <script src="<?=base_url()?>assets/blueline/js/plugins/jquery-1.11.0.min.js"></script>
    <script src="<?=base_url()?>assets/blueline/js/bootstrap.min.js"></script>
    <?php if($error == "true") { ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#error").delay(400).slideDown();
            });
        </script> 
    <?php } ?>
  </body>
</html>
