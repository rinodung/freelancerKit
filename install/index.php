<?php 
header('Content-type: text/html; charset=ISO-8859-1');
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Installation">
    <meta name="author" content="Luxsys">
    <title>Freelance Cockpit 2 - Installation</title>
    
    <link href="../assets/blueline/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/blueline/css/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/blueline/css/blueline.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600,700' rel='stylesheet' type='text/css'>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body class="install">
    <div class="container-fluid">
      <div class="row">

      <div class="col-xs-12 col-lg-8 col-lg-offset-2 install-frame">  
        <div class="logo"><img src="../assets/blueline/images/FC2_logo_dark.png"></div>
        <div class="install-content">
    
      <?php require("install.php"); ?>


<!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h4 class="modal-title">How to find your purchase code</h4>
          </div><form role="form" action="#">
          <div class="modal-body">
        <p><img class="img-responsive" src="purchaseCode.png"></p>
      </div>
    </div> 
  </div>
</div>

          <br clear="all">
          </div>
        </div> 

      </div>
    </div>
    <script src="../assets/blueline/js/plugins/jquery-1.11.0.min.js"></script>
    <script src="../assets/blueline/js/bootstrap.min.js"></script>
    <script src="../assets/blueline/js/plugins/jqBootstrapValidation.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
      $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
    });
    </script>
  </body>
</html>