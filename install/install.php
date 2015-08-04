<?php

/**
 * @author	Luxsys
 * @package FC2
 * @subpackage install
**/

$installFile = "../INSTALL_TRUE";
$DBconfigFile = "../application/config/database.php";
if (is_file($installFile)) { 

function remote_get_contents($url)
{
        if (function_exists('curl_get_contents') AND function_exists('curl_init'))
        {
                return curl_get_contents($url);
        }
        else
        {
                return file_get_contents($url);
        }
}

function curl_get_contents($url)
{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
}



switch($_GET['step']){
	default: ?>
	
	<?php 
			$error = FALSE;
			if(phpversion() < "5.3"){$error = TRUE; $check1 = "<span class='label label-danger'>Your PHP version is ".phpversion()."</span>";}else{$check1 = "<span class='label label-success'>v.".phpversion()."</span>";} 
			if(!extension_loaded('mcrypt')){$error = TRUE; $check2 = "<span class='label label-danger'>Not enabled</span>";}else{$check2 = "<span class='label label-success'>OK</span>";}
			if(!extension_loaded('mysql')){$error = TRUE; $check3 = "<span class='label label-danger'>Not enabled</span>";}else{$check3 = "<span class='label label-success'>OK</span>";}
		  if(!extension_loaded('mbstring')){$error = TRUE; $check4 = "<span class='label label-danger'>Not enabled</span>";}else{$check4 = "<span class='label label-success'>OK</span>";}
			if(!extension_loaded('gd')){$check5 = "<span class='label label-danger'>Not enabled</span>";}else{$check5 = "<span class='label label-success'>OK</span>";}
			if(!extension_loaded('pdo')){$error = TRUE; $check6 = "<span class='label label-danger'>Not enabled</span>";}else{$check6 = "<span class='label label-success'>OK</span>";}
			if(!extension_loaded('dom')){$check7 = "<span class='label label-danger'>Not enabled</span>";}else{$check7 = "<span class='label label-success'>OK</span>";}
			if(!extension_loaded('curl')){$error = TRUE; $check8 = "<span class='label label-danger'>Not enabled</span>";}else{$check8 = "<span class='label label-success'>OK</span>";}
			if(!is_writeable($DBconfigFile)){$error = TRUE; $check9 = "<span class='label label-danger'>Database File (application/config/database.php) is not writeable!</span>";}else{$check9 = "<span class='label label-success'>OK</span>";}
			if(!is_writeable("../files")){$check10 = "<span class='label label-danger'>/files folder is not writeable!</span>";}else{$check10 = "<span class='label label-success'>OK</span>";}
			if(ini_get('allow_url_fopen') != "1"){$check11 = "<span class='label label-warning'>Allow_url_fopen is not enabled!</span>";}else{$check11 = "<span class='label label-success'>OK</span>";}
            if(!extension_loaded('zip')){$check12 = "<span class='label label-warning'>Not enabled</span>";}else{$check12 = "<span class='label label-success'>OK</span>";}
	        if(!extension_loaded('imap')){$check13 = "<span class='label label-warning'>Not enabled</span>";}else{$check13 = "<span class='label label-success'>OK</span>";}
            if(!is_writeable("../application/views/blueline/templates")){$check14 = "<span class='label label-warning'>/application/views/blueline/templates/ folder is not writeable!</span>";}else{$check14 = "<span class='label label-success'>OK</span>";}

?>          
	
	     <div class="form-group">
        <div class="col-xs-14">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="active"><a href="#step-1">
                    <h4 class="list-group-item-heading">System Check</h4>
                    <p class="list-group-item-text">Server Requirements</p>
                </a></li>
                <li class="disabled"><a href="#step-2">
                    <h4 class="list-group-item-heading">Validate</h4>
                    <p class="list-group-item-text">Purchase code</p>
                </a></li>
                <li class="disabled"><a href="#step-3">
                    <h4 class="list-group-item-heading">Database</h4>
                    <p class="list-group-item-text">MYSQL details</p>
                </a></li>
                <li class="disabled"><a href="#step-3">
                    <h4 class="list-group-item-heading">Settings</h4>
                    <p class="list-group-item-text">Your Info</p>
                </a></li>
                <li class="disabled"><a href="#step-3">
                    <h4 class="list-group-item-heading">Done!</h4>
                    <p class="list-group-item-text">That's it</p>
                </a></li>
            </ul>
        </div>
  </div>
        <h3>Server Requirements</h3>
          <table class="table table-striped">
      <thead>
        <tr>
          <th>Required</th>
          <th>Result</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>PHP 5.3+ </td>
          <td><?php echo $check1; ?></td>
        </tr>
         <tr>
          <td>Mysql PHP extension</td>
          <td><?php echo $check3; ?></td>
        </tr>
        <tr>
          <td>Mcrypt PHP extension</td>
          <td><?php echo $check2; ?></td>
        </tr>
        <tr>
          <td>MBString PHP extension</td>
          <td><?php echo $check4; ?></td>
        </tr>
        <tr>
          <td>GD PHP extension</td>
          <td><?php echo $check5; ?></td>
        </tr>
        <tr>
          <td>PDO PHP extension</td>
          <td><?php echo $check6; ?></td>
        </tr>
        <tr>
          <td>DOM PHP extension</td>
          <td><?php echo $check7; ?></td>
        </tr>
        <tr>
          <td>CURL PHP extension</td>
          <td><?php echo $check8; ?></td>
        </tr>
         <tr>
          <td>ZIP PHP extension</td>
          <td><?php echo $check12; ?></td>
        </tr>
        <tr>
          <td>IMAP PHP extension (only needed for Email Tickets)</td>
          <td><?php echo $check13; ?></td>
        </tr>
        <tr>
          <td>Allow_url_fopen is enabled!</td>
          <td><?php echo $check11; ?></td>
        </tr>
       <tr>
          <td>Database file (/application/config/database.php) writeable</td>
          <td><?php echo $check9; ?></td>
        </tr>
         <tr>
          <td>/files folder is writeable</td>
          <td><?php echo $check10; ?></td>
        </tr>
        <tr>
          <td>/application/views/blueline/templates/ folder is writeable</td>
          <td><?php echo $check14; ?></td>
        </tr>
      </tbody>
    </table>
		   
		<div class="bottom">
			<?php if($error){ ?>
			<a href="#" class="btn btn-primary disabled pull-right">Next Step</a>
			<?php }else{ ?>
			<a href="?step=0" class="btn btn-primary pull-right">Next Step</a>
			<?php } ?>
		</div>

<?php
	break;
	case "0": ?>
		     <div class="form-group">
        <div class="col-xs-14">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="done"><a href="#step-1">
                    <h4 class="list-group-item-heading">System Check</h4>
                    <p class="list-group-item-text">Server Requirements</p>
                </a></li>
                <li class="active"><a href="#step-2">
                    <h4 class="list-group-item-heading">Validate</h4>
                    <p class="list-group-item-text">Purchase code</p>
                </a></li>
                <li class="disabled"><a href="#step-3">
                    <h4 class="list-group-item-heading">Database</h4>
                    <p class="list-group-item-text">MYSQL details</p>
                </a></li>
                <li class="disabled"><a href="#step-3">
                    <h4 class="list-group-item-heading">Settings</h4>
                    <p class="list-group-item-text">Your Info</p>
                </a></li>
                <li class="disabled"><a href="#step-3">
                    <h4 class="list-group-item-heading">Done!</h4>
                    <p class="list-group-item-text">That's it</p>
                </a></li>
            </ul>
        </div>
  </div>
	<h3>Purchase Code Check</h3>
	<?php
		if($_POST){
			$code = $_POST["code"];
			$object = file_get_contents('data.php');
			$object = json_decode($object);
			var_dump($object);
		if ($object->error != FALSE) {
		    ?>
		    <div class="label label-important"><?php echo $object->error; ?></div><br><br>
		    <form action="?step=0" method="POST">
		<p>
		        <label for="code">Item Purchase Code <a href="#myModal" role="button" data-toggle="modal"><i class="fa fa-question-circle"></i></a></label>
		        <input id="code" type="text" class="form-control" name="code" />
		</p>
		<div class="bottom">
			<input type="submit" class="btn btn-primary pull-right" value="Check"/>
		</div>
		</form>

		    <?php
		}else{ ?>
			<form action="?step=1" method="POST">
		<p>
		<div class="label label-success">Your purchase code is valid!</div>   
		</p><input id="code" type="hidden" name="code" value="<?php echo $code; ?>" />
		<div class="bottom">
			<input type="submit" class="btn btn-primary pull-right" value="Next Step"/>
		</div>
		</form><?php
		}
		}else{	?>
    <p>Please enter your item purchase code of Freelance Cockpit 2</p><br>
    <form action="?step=0" method="POST">
    <p>
            <label for="code">Item Purchase Code <a href="#myModal" role="button" data-toggle="modal"><i class="fa fa-question-circle"></i></a></label>
            <input id="code"  class="form-control" type="text" name="code">
    </p>
    <button type="submit" class="btn btn-primary pull-right">Next</button>
    </form>
	<?php }
	break;
	case "1": ?>
	<div class="form-group">
        <div class="col-xs-14">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="done"><a href="">
                    <h4 class="list-group-item-heading">System Check</h4>
                    <p class="list-group-item-text">Server Requirements</p>
                </a></li>
                <li class="done"><a href="">
                    <h4 class="list-group-item-heading">Validate</h4>
                    <p class="list-group-item-text">Purchase code</p>
                </a></li>
                <li class="active"><a href="">
                    <h4 class="list-group-item-heading">Database</h4>
                    <p class="list-group-item-text">MYSQL details</p>
                </a></li>
                <li class="disabled"><a href="">
                    <h4 class="list-group-item-heading">Settings</h4>
                    <p class="list-group-item-text">Your Info</p>
                </a></li>
                <li class="disabled"><a href="">
                    <h4 class="list-group-item-heading">Done!</h4>
                    <p class="list-group-item-text">That's it</p>
                </a></li>
            </ul>
        </div>
  </div>
	<?php if($_POST){ ?>
	<h3>Database Config</h3>
	<p class="label label-warning">Information: If the database does not exist the system will try to create it.</p>
		<form action="?step=2" method="POST">
		<p>
		        <label for="host">Host *</label>
		        <input id="host" type="text" name="host" class="required form-control" value="localhost" />
		</p>
		<p>
		        <label for="username">Username *</label>
		        <input id="username" type="text" name="username" class="form-control required" />
		</p>
		<p>
		        <label for="password">Password *</label>
		        <input id="password" type="password" class="form-control" name="password" />
		</p>
		<p>
		        <label for="dbname">Database Name *</label>
		        <input id="dbname" type="text" class="form-control" name="dbname" value="FC2" />
		</p>
		<input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
		<div class="bottom">
			<input type="submit" class="btn btn-primary pull-right" value="Next Step"/>
		</div>
		</form>
	<?php }
	break;
	case "2":
	?>
<div class="form-group">
        <div class="col-xs-14">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="done"><a href="">
                    <h4 class="list-group-item-heading">System Check</h4>
                    <p class="list-group-item-text">Server Requirements</p>
                </a></li>
                <li class="done"><a href="">
                    <h4 class="list-group-item-heading">Validate</h4>
                    <p class="list-group-item-text">Purchase code</p>
                </a></li>
                <li class="active"><a href="">
                    <h4 class="list-group-item-heading">Database</h4>
                    <p class="list-group-item-text">MYSQL details</p>
                </a></li>
                <li class="disabled"><a href="">
                    <h4 class="list-group-item-heading">Settings</h4>
                    <p class="list-group-item-text">Your Info</p>
                </a></li>
                <li class="disabled"><a href="">
                    <h4 class="list-group-item-heading">Done!</h4>
                    <p class="list-group-item-text">That's it</p>
                </a></li>
            </ul>
        </div>
  </div>
	<h3>Saving database config</h3>
	<?php
		if($_POST){
			$host = $_POST["host"];
			$username = $_POST["username"];
			$password = $_POST["password"];
			$dbname = $_POST["dbname"];
			$code = $_POST["code"];
			$link = @mysql_connect($host, $username, $password);
		if (!$link) {
		    echo "<br><div class='label label-danger'>Could not connect to MYSQL!</div>";
		}else{
			echo '<br><div class="label label-success">Connection to MYSQL successful!</div>';
			
			$db_selected = @mysql_select_db($dbname, $link);
			if (!$db_selected) {
				if(!mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname` /*!40100 CHARACTER SET utf8 COLLATE 'utf8_general_ci' */")){
					echo "<br><div class='label label-important'>Database ".$dbname." does not exist and could not be created. Please create the Database manually and retry this step.</div>";
					$res = mysql_query("SHOW DATABASES");
					echo "<br><br><b>The following databases are available:</b><br>";
					while ($row = mysql_fetch_assoc($res)) {
						    echo $row['Database'] . "<br>";
						}
					return FALSE;
				}else{ echo "<br><div class='label label-success'>Database ".$dbname." created</div>";}
			}
				mysql_select_db($dbname);
				define("BASEPATH", "install/");
		
		function write_dbconfig($host, $username, $password,$dbname, $DBconfigFile){

			$newcontent = '<?php  if ( !defined(\'BASEPATH\')) exit(\'No direct script access allowed\');
							/*
							| -------------------------------------------------------------------
							| DATABASE CONNECTIVITY SETTINGS
							| -------------------------------------------------------------------
							| This file will contain the settings needed to access your database.
							|
							| For complete instructions please consult the \'Database Connection\'
							| page of the User Guide.
							|
							*/

							$active_group = \'default\';
							$active_record = TRUE;

							$db[\'default\'][\'hostname\'] = \''.$host.'\';
							$db[\'default\'][\'username\'] = \''.$username.'\';
							$db[\'default\'][\'password\'] = \''.$password.'\';
							$db[\'default\'][\'database\'] = \''.$dbname.'\';
							$db[\'default\'][\'dbdriver\'] = \'mysql\';
							$db[\'default\'][\'dbprefix\'] = \'\';
							$db[\'default\'][\'pconnect\'] = TRUE;
							$db[\'default\'][\'db_debug\'] = TRUE;
							$db[\'default\'][\'cache_on\'] = FALSE;
							$db[\'default\'][\'cachedir\'] = \'\';
							$db[\'default\'][\'char_set\'] = \'utf8\';
							$db[\'default\'][\'dbcollat\'] = \'utf8_general_ci\';
							$db[\'default\'][\'swap_pre\'] = \'\';
							$db[\'default\'][\'autoinit\'] = TRUE;
							$db[\'default\'][\'stricton\'] = FALSE;


							/* End of file database.php */
							/* Location: ./application/config/database.php */
							';


			$file_contents = file_get_contents($DBconfigFile);
			$fh = fopen($DBconfigFile, "w");
			$file_contents = $newcontent;
			if(fwrite($fh, $file_contents)){
				return true;
			}
			fclose($fh);

		}
		if(!write_dbconfig($host,$username,$password,$dbname,$DBconfigFile)){
				echo "<br><div class='label label-important'>Failed to write config to ".$DBconfigFile."</div>";
		}else{ echo "<br><div class='label label-success'>Database config written to the database file.</div>"; }
		}
		}else{echo "<br><div class='label label-success'>Nothing to do...</div>";}
		?>
		<div class="bottom">
			<form action="?step=1" method="POST">
		    <input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
			<input type="submit" class="btn btn-default pull-left" value="Previous Step"/>
			</form>
			<form action="?step=3" method="POST">
		    <input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
			<input type="submit" class="btn btn-primary pull-right" value="Next Step">
			</form>
			<br clear="all">
		</div>
		<?php
	break;
	case "3":
	?>
<div class="form-group">
        <div class="col-xs-14">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="done"><a href="">
                    <h4 class="list-group-item-heading">System Check</h4>
                    <p class="list-group-item-text">Server Requirements</p>
                </a></li>
                <li class="done"><a href="">
                    <h4 class="list-group-item-heading">Validate</h4>
                    <p class="list-group-item-text">Purchase code</p>
                </a></li>
                <li class="done"><a href="">
                    <h4 class="list-group-item-heading">Database</h4>
                    <p class="list-group-item-text">MYSQL details</p>
                </a></li>
                <li class="active"><a href="">
                    <h4 class="list-group-item-heading">Settings</h4>
                    <p class="list-group-item-text">Your Info</p>
                </a></li>
                <li class="disabled"><a href="">
                    <h4 class="list-group-item-heading">Done!</h4>
                    <p class="list-group-item-text">That's it</p>
                </a></li>
            </ul>
        </div>
  </div>
		<?php if($_POST){ ?>
		<form id="step3" action="?step=4" method="POST">
		<p>
		        <label for="company">Company Name *</label>
		        <input type="text" name="company" class="form-control" value="" required/>
		</p>
		<p>
		        <label for="invoice_address">Contact Name *</label>
		        <input type="text" name="invoice_contact" class="form-control" value=""  required/>
		</p>
		<p>
		        <label for="invoice_address">Address *</label>
		        <input type="text" name="invoice_address" class="form-control" value="" required/>
		</p>
		<p>
		        <label for="invoice_city">City *</label>
		        <input type="text" name="invoice_city" class="form-control" value="" required/>
		</p>
		<p>
		        <label for="invoice_tel">Phone</label>
		        <input type="text" name="invoice_tel" class="form-control " value="" />
		</p>
		<p>
		        <label for="domain">Domain *</label>
		        <input type="text" name="domain" class="form-control" value="<?php echo "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -15); ?>" required/>
		</p>
		<p>
		        <label for="email">Email *</label>
		        <input type="text" name="email" class="form-control email" value="" required/>
		</p>
		<p>
		        <label for="tax">Tax (%)</label>
		        <input type="text" name="tax" class="form-control number" value="" />
		</p>
		<p>
		        <label for="currency">Default Currency</label>
		        <input type="text" name="currency" class="form-control" value="$" />
		</p>
		<p>
		        <label for="invoice_reference">Invoice Reference *</label>
		        <input type="text" name="invoice_reference" class="form-control number" value="31001" required/>
		</p>
		<p>
		        <label for="company_reference">Company Reference *</label>
		        <input type="text" name="company_reference" class="form-control number" value="41001" required/>
		</p>
		<p>
		        <label for="project_reference">Project Reference *</label> 
		        <input type="text" name="project_reference" class="form-control number" value="51001" required/>
		</p>
		<p>
		        <label for="subscription_reference">Subscription Reference *</label> 
		        <input type="text" name="subscription_reference" class="form-control number" value="61001" required/>
		</p>
		<input type="hidden" name="pc" value="<?php echo $_POST['code']; ?>" />
		<div class="bottom">
			<a href="?step=2" class="btn btn-default pull-left">Previous Step</a>
			<input type="submit" class="btn btn-primary pull-right" value="Next Step"/>
		</div>
		</form>
		<?php } ?>


	<?php
	break;
	case "4": ?>
<div class="form-group">
        <div class="col-xs-14">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="done"><a href="">
                    <h4 class="list-group-item-heading">System Check</h4>
                    <p class="list-group-item-text">Server Requirements</p>
                </a></li>
                <li class="done"><a href="">
                    <h4 class="list-group-item-heading">Validate</h4>
                    <p class="list-group-item-text">Purchase code</p>
                </a></li>
                <li class="done"><a href="">
                    <h4 class="list-group-item-heading">Database</h4>
                    <p class="list-group-item-text">MYSQL details</p>
                </a></li>
                <li class="done"><a href="">
                    <h4 class="list-group-item-heading">Settings</h4>
                    <p class="list-group-item-text">Your Info</p>
                </a></li>
                <li class="active"><a href="">
                    <h4 class="list-group-item-heading">Done!</h4>
                    <p class="list-group-item-text">That's it</p>
                </a></li>
            </ul>
        </div>
  </div>

	<?php if($_POST){
		$domain = $_POST['domain'];
		$email = $_POST['email'];
		$company = addslashes($_POST['company']);
		if(!empty($tax)){$tax = $_POST['tax'];}else{$tax = "0";} 
		$currency = $_POST['currency']; 
		$company_reference = $_POST['company_reference']; 
		$project_reference = $_POST['project_reference']; 
		$invoice_reference = $_POST['invoice_reference'];
		$subscription_reference = $_POST['subscription_reference'];
		$invoice_address = addslashes($_POST['invoice_address']);
		$invoice_city = addslashes($_POST['invoice_city']);
		$invoice_contact = addslashes($_POST['invoice_contact']);
		$invoice_tel = $_POST['invoice_tel'];
		$pc = $_POST['pc'];
		define("BASEPATH", "install/");
		include("../application/config/database.php");
		mysql_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password']);
		mysql_select_db($db['default']['database']);

			$object = file_get_contents('data.php');
			$object = json_decode($object);
		if ($object->error != FALSE) {
			echo "<br><div class='label label-important'>Error while validating your purchase code!</div>";
		}else{
			foreach ($object->database as $key => $value) {
				mysql_query($value) or die(mysql_error());
			}
		}

$insert = mysql_query("INSERT INTO core (`id`, `version`, `domain`, `email`, `company`, `tax`, `currency`, `autobackup`, 
`cronjob`, `last_cronjob`, `last_autobackup`, `invoice_terms`, `company_reference`, `project_reference`, 
`invoice_reference`, `subscription_reference`, `ticket_reference`, `date_format`, `date_time_format`, `invoice_mail_subject`, 
`pw_reset_mail_subject`, `pw_reset_link_mail_subject`, `credentials_mail_subject`, `notification_mail_subject`, 
`language`, `invoice_address`, `invoice_city`, `invoice_contact`, `invoice_tel`, `subscription_mail_subject`, `logo`, 
`template`, `paypal`, `paypal_currency`, `paypal_account`, `invoice_logo`, `pc`) 
VALUES (1, '2.2.5', '$domain', '$email', '$company', '$tax', '$currency', '1', '1', '', '', 
'Thank you for your business. We do expect payment within {due_date}, so please process this invoice within that time.', 
'$company_reference', '$project_reference', '$invoice_reference', '$subscription_reference', '10000', 
'Y/m/d', 'g:i A', 'New Invoice', 'Password Reset', 'Password Reset', 'Login Details', 'Notification', 'english', 
'$invoice_address', '$invoice_city', '$invoice_contact', '$invoice_tel', 'New Subscription', 'assets/blueline/images/FC2_logo_light.png', 'blueline', '0', 'USD', 
'', 'assets/blueline/images/FC2_logo_dark.png', '$pc');");

$check_user = mysql_query("SELECT count(*) as val from users where username = 'Admin'");
$check_user = @mysql_fetch_assoc($check_user);
if($check_user['val'] == 0){
mysql_query("INSERT INTO `users` (`username`, `firstname`, `lastname`, `hashed_password`, `email`, `status`, `admin`, `created`, `userpic`, `title`, `access`, `last_active`, `last_login`) VALUES ('Admin', 'John', 'Doe', '785ea3511702420413df674029fe58d69692b3a0a571c0ba30177c7808db69ea22a8596b1cc5777403d4374dafaa708445a9926d6ead9a262e37cb0d78db1fe5', 'local@localhost', 'active', '1', '2013-01-01 00:00:00', 'no-pic.png', 'Administrator', '1,2,3,4,5,8,6,7,9,10,11,16', '', '')");

}else{
  mysql_query("UPDATE users SET `hashed_password` = '785ea3511702420413df674029fe58d69692b3a0a571c0ba30177c7808db69ea22a8596b1cc5777403d4374dafaa708445a9926d6ead9a262e37cb0d78db1fe5' WHERE username = 'Admin'");
}
			if(!$insert){echo "<div class='label label-important'>Error while saving settings to database!</div>";}else{
				echo "<h3>Installation successful!</h3>";
				if(!@unlink('../INSTALL_TRUE')){
					echo "<br><div class='label label-warning'>Please remove the INSTALL_TRUE file from the main folder in order to disable the installation tool!</div>";
					}
				
	$subfolder = substr($_SERVER["REQUEST_URI"], 0, -15);
	$subfoldercheck = substr($_SERVER["REQUEST_URI"], 0, -16);
	if(!empty($subfoldercheck)){
					
$input = 'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteBase '.$subfolder.'
RewriteRule ^(.*)$ index.php?/$1 [L]';
$current = @file_put_contents('../.htaccess', $input);
	}
				} 
			} 

$url = "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -15)."/login";
$ch = curl_init($url);
curl_setopt($ch,  CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch,  CURLOPT_HEADER, TRUE); //Include the headers
curl_setopt($ch,  CURLOPT_NOBODY, TRUE); //Make HEAD request

$response = curl_exec($ch);

if ( $response === false ){
    //something went wrong, assume not valid
}

//list of status codes to treat as valid:    
$validStatus = array(200, 301, 302, 303, 307);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if( !in_array(curl_getinfo($ch, CURLINFO_HTTP_CODE), $validStatus) ) {
    ?>
    
    <br><div class="label label-important"><b>Your .htaccess file check failed with http status <?php echo $http_status; ?>.</b><br> You might get an error message if you click on "Go to Login" as your .htaccess needs to be changed. This issue mostly appears if you have installed Freelance Cockpit into a sub folder. Please take a look at the <a href="http://codecanyon.net/item/freelance-cockpit-2-project-management/4203727/support" target="blank">FAQ</a> in order to fix your .htaccess file.</div>
    <?php
}

curl_close($ch);

?>
			
			<br><div ><b>You can login using the following credentials: </b><br>Username: <b>Admin</b> <br>Password: <b>password</b></div>
			<br><div class="label label-warning">Important! Change your password after login.</div><br><br>
					<div class="bottom">
					<a href="<?php echo "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -15); ?>" class="btn btn-blue">Go to Login</a>
				</div>
			
	<?php	
	}

}else{
			echo "<div class='label label-important'>Installation tool not active! Just create a file named \"INSTALL_TRUE\" within the main folder.</div>";
		}
?>

 