<?php
/*
 * script for install wizard
 * License: GNU
 * Copyright 2016 WebAppFirewall RomanShneer <romanshneer@gmail.com>
 */
session_start();
#require_once "libs/config.inc.php";

require_once "libs/db.inc.php";
require_once "libs/user.class.php";
require_once "libs/installer.class.php";

$WI=new WafInstaller;
$err='';

$post=array('dbhost'=>'localhost',
						'dbuser'=>'root',
						'dbpass'=>'',
						'dbname'=>'waf',
						'email'=>'',
						'password'=>''
		);
if(isset($_POST['op'])&&($_POST['op']=='Save'))
{
	$err=$WI->try_install_waf($_POST);
	$post=$_POST;
}
$errors=Array();
$warnings=Array();
if(!isset($_SERVER['HTTP_WAF_TEST'])||($_SERVER['HTTP_WAF_TEST']!='SUCCESS'))
	$errors[]='SetEntIf blocked or not available.';
if(function_exists('apache_get_modules'))
{
	if(!in_array('mod_rewrite', apache_get_modules()))$errors[]='Mod_Rewrite disabled.';
}else{
	$warnings[]='unknown if mod_rewrite enabled on your server, try use for your risk.';
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  xml:lang="en" lang="en">
<head>
<?php require_once 'include/head.php';?>
</head>
<body>
		<div class='header'></div> 
		<h1 class='title'>Wellcome to W.A.F.</h1>
		
				<div class='box'>	
						<center>	
						<?php if(count($errors)==0):?>	
								<h5>Install Easy</h5>		
						<form action="" method='POST'>
				<?php foreach($warnings as $warning) echo "<p style='color:#ccc'>".$warning."</p>"; ?>		
				<?php if(!empty($err))echo '<div style="color:red">'.$err.'</div>';?>		
								<table>
										<tr><th colspan="2">Database parameters</th></tr>
										<tr>
												<td>DB Host:</td>
												<td><input type="text" name="dbhost" placeholder="localhost" class="inset" value="<?php echo $post['dbhost']?>"></td>
										</tr>
										<tr>
												<td>DB User:</td>
												<td><input type="text" name="dbuser" placeholder="root" class="inset" value="<?php echo $post['dbuser']?>"></td>
										</tr>
										<tr>
												<td>DB Password:</td>
												<td><input type="text" name="dbpass" placeholder="" class="inset" value="<?php echo $post['dbpass']?>"></td>
										</tr>
										<tr>
												<td>DB Name:</td>
												<td><input type="text" name="dbname" placeholder="waf" class="inset" value="<?php echo $post['dbname']?>"></td>
										</tr>
										<tr>
												<td>Create New DB:</td>
												<td><input type="checkbox" name="new_db" <?php if(isset($_POST['new_db'])):?> checked="checked"<?php endif;?>></td>
										</tr>
										<tr>
												<td>Save Old Data:</td>
												<td><select name="keep_db" class="inset">
																<option value="new"<?php if(isset($_POST['keep_db'])&&($_POST['keep_db']=='new')):?> selected<?php endif;?>>New Installation</option>
																<option value="keep<?php if(isset($_POST['keep_db'])&&($_POST['keep_db']=='keep')):?> selected<?php endif;?>">Keep old data</option>
														</select>
										</tr>
										
										<tr><th colspan="2">First User</th></tr>
										<tr>
												<td>Email:</td>
												<td><input type="text" name="email" placeholder="admin@test.com" class="inset" value="<?php echo $post['email']?>"></td>
										</tr>
										<tr>
												<td>Password:</td>
												<td><input type="password" name="password" placeholder="12345" class="inset" value="<?php echo $post['password']?>"></td>
										</tr>
										<tr>
												<td>Confirm Password:</td>
												<td><input type="password" name="password1" placeholder="12345" class="inset" value="<?php echo $post['password']?>"></td>
										</tr>
										<tr><th><input type="submit" class="add_user" value="Save" name="op"></th></tr>
								</table>
						</form>
						<?php else:?>
						<h1>Sorry, cannot work on your webserver configurations</h1>
						<?php foreach($errors as $error) echo "<p style='color:Red'>".$error."</p>"; ?>
						<?php foreach($warnings as $warning) echo "<p style='color:#ccc'>".$warning."</p>"; ?>
						<br><br><p>Contact you web-provider about changing configuration</p>
						<?php endif;?>
						</center>		
				</div>			
		
</body>
</html>