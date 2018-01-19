<?php  
include('include/config.php');
include("addpayment.class.php");

if($_GET['editId']) {
	
	$label = "Update";
}

else {

	$label = "Add";
}

$userdata = $_SESSION['BLData']; 

$BASE_URL = "http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?');

if(!isset($_SESSION['BLData']) && empty($_SESSION['BLData']))
{
	$_SESSION['error'] = 'Invalid Request';
	?><script>document.location="index.php";</script><?php
}

$error='';
$success='';
$currentDate = date('Y-m-d');
$datetime = date('Y-m-d H:i:s');

$txtFirstName = '';
$txtLastName = '';
$txtAddress = '';
$txtCity = '';
$txtPincode = '';
$txtMobileNo = '';
$txtEmail = '';
$txtUsername = '';
$status = 1;
$txtPassword = '';
$lsDealerId = '';
$lstPackageType = '';
$packagePrice = '';
$dtCreated = date('m/d/Y');
$userType = '';

// create an array for package information

$qryPackageInfo = mysqli_query($conn,"SELECT  * FROM packages");
$arrPackageNameInfo = array();
if(mysqli_num_rows($qryPackageInfo) > 0) {
	while ($packageInfo = mysqli_fetch_array($qryPackageInfo)) {
		$arrPackageNameInfo[] = $packageInfo;
	}
}

if($_GET['editId']) {
	$buttonLable="Update";
	$label = "Update";
}

else {
	$buttonLable="Save";
	$label = "Add";
}



if(isset($_GET['editId']))
{
	$query = " SELECT  * FROM users WHERE id='".$_GET['editId']."' AND (endeffdate IS NULL OR endeffdate ='NULL' OR endeffdate='0000-00-00')";
	$result = mysqli_query($conn,$query);
	if(mysqli_num_rows($result) > 0)
	{
		$row = mysqli_fetch_array($result);

		$txtFirstName = $row['fname'];
		$txtLastName = $row['lname'];
		$txtAddress = $row['address'];
		$txtCity = $row['city'];
		$txtPincode = $row['pincode'];
		$txtMobileNo = $row['mobile_no'];
		$txtEmail = $row['email'];
		$lstPackageType = $row['package_id'];
		$txtUsername = $row['username'];
		$status = $row['status'];
		$txtPassword = $row['password'];
		$lsDealerId = $row['dealer_id'];
		$packagePrice = $row['package_price'];
	}
	
}



// This function is check if string is encrypted with md5 function or not 

function isValidMd5($md5 ='')
{
    return preg_match('/^[a-f0-9]{32}$/', $md5);
}

if($_POST)
{
	if(isset($_POST['submit']))
	{
		if($_POST['id']!='' && $_POST['action']=='edit') 
		{ 
			$id  = getData('id');
			$txtFirstName = getData('txtFirstName');
			$txtLastName = getData('txtLastName');
			$txtAddress = getData('txtAddress');
			$txtCity = getData('txtCity');
			$txtPincode = getData('txtPincode');
			$txtMobileNo = getData('txtMobileNo');
			$txtEmail = getData('txtEmail');
			$lsDealer = getData('lsDealer');
			$lstPackageType = getData('lstPackageType');
			$lsDealerId = getData('lsDealer');
			
			$txtUsername = getData('txtUsername');
			$txtPassword = getData('txtPassword');
			
			if($userdata['user_type'] == "")	{
				$packagePrice = getData('txtCustomerPackagePrice');
			}
			else{
				$packagePrice = getData('txtPackagePrice');
			}
			
			if(empty($lsDealer)){
				$lsDealer = $userdata['id'];			
			}
			
			/* if user is not changing the password then we send original encripted password 
			 * otherwise we are dycrypt password with md5 
			 */

			if(isValidMd5($txtPassword)){
				$txtPassword = getData('txtPassword');
			}
			else{
				$txtPassword = getData('txtPassword');
				$txtPassword = md5($txtPassword);
			} 
			$status = isset($_POST['status'])?1:0;
			
			/* $packageValidity = getPackageValidity($lstPackageType);
			
			if($packageValidity == '1m'){
				$endDate = date('Y-m-d', strtotime('+1 months'));
			}
			if($packageValidity =='1y'){
				$endDate = date('Y-m-d', strtotime('+1 years'));
			} */
			
			$query = " update users set 
									fname = '".$txtFirstName."',
									lname= '".$txtLastName."',
									address= '".$txtAddress."',
									city= '".$txtCity."',
									pincode= '".$txtPincode."',
									mobile_no= '".$txtMobileNo."',
									username= '".$txtUsername."',
									email= '".$txtEmail."',
									package_id= '".$lstPackageType."',										
									password='".$txtPassword."',
									dealer_id = '".$lsDealer."',
									user_type = '".$userType."',
									package_price = '".$packagePrice."',
									status= '".$status."' where id = '".$_POST['id']."'";
			
			$is_insert = mysqli_query($conn,$query);
			if($is_insert)
			{
				$success = 'Profile Updated Successfully.';
				?><script>document.location="add-customer.php?action=edit&editId=<?php print($_POST['id']);?>";</script><?php
				//header("Location:add-customer.php?action=edit&editId={$_POST['id']}");
			}
			else
			{
				$error .= 'Profile Not Updated Successfully.';
				?><script>document.location="add-customer.php?action=edit&editId=<?php print($_POST['id']);?>";</script><?php
				//header("Location:add-customer.php?action=edit&editId={$_POST['id']}");
			}
		} 
		else { 
		
			$txtFirstName = getData('txtFirstName');
			$txtLastName = getData('txtLastName');
			$txtAddress = getData('txtAddress');
			$txtCity = getData('txtCity');
			$txtPincode = getData('txtPincode');
			$txtMobileNo = getData('txtMobileNo');
			$txtEmail = getData('txtEmail');
			$txtUsername = getData('txtUsername');
			$txtPassword = getData('txtPassword');
			$lsDealer =  getData('lsDealer');	
			$lstPackageType = getData('lstPackageType');
			
			if($userdata['user_type'] == "")	{
				$txtPackagePrice = getData('txtCustomerPackagePrice');
			}
			else{
				$txtPackagePrice = getData('txtPackagePrice');
			}
			
			if(empty($lsDealer)){
				$lsDealer = $userdata['id'];			
			}
				
			$status = isset($_POST['status'])?1:0;
			if (checkLen($txtFirstName)){
				$error .= 'First Name should be within 3-20 characters long.';
			}
			if (checkLen($txtLastName)){
				$error .= 'Last Name should be within 3-20 characters long.';
			}
			if (checkMoNo($txtMobileNo)) {
				//$error .= '<li>Enter a valid phone number.</li>';
			}
			if($error == ""){
				if(isEmailExist($email)) {
					$error .= 'Email already exists!.';
				}

				if(isUsernameExist($username)) {
					$error .= 'Username already exists!.';
				}
				
				$packageValidity = getPackageValidity($lstPackageType);
				
				if($packageValidity == '1m'){
					$endDate = date('Y-m-d', strtotime('+1 months'));
				}
				if($packageValidity =='1y'){
					$endDate = date('Y-m-d', strtotime('+1 years'));
				}
			
				if($error == ""){
					// Generate a unique code:
					$hash = md5(uniqid(rand(), true));
						$query = " INSERT users SET 
										fname = '".$txtFirstName."',
										lname= '".$txtLastName."',
										address= '".$txtAddress."',
										city= '".$txtCity."',
										pincode= '".$txtPincode."',
										mobile_no = '".$txtMobileNo."',
										username = '".$txtUsername."',
										email = '".$txtEmail."',	
										package_id = '".$lstPackageType."',
										password = '".md5($txtPassword)."',
										user_type = '".$userType."',
										dealer_id = '".$lsDealer."',
										package_price = '".$txtPackagePrice."',
										status = '".$status."',
										startdate = '".$currentDate."',
										enddate = '".$endDate."',
										datetimex = '".$datetime."',
										hash = '".$hash."'";
					
					$is_insert = mysqli_query($conn,$query);
					if($is_insert) {
							 $success = 'Profile Added Successfully.';
						//E-MAIL VERIFY LINK START
							require 'PHPMailer/PHPMailerAutoload.php';

							$mail = new PHPMailer;
							$mail->isSMTP();                                   // Set mailer to use SMTP
							$mail->Host = 'smtp.gmail.com';                    // Specify main and backup SMTP servers
							$mail->SMTPAuth = true;                            // Enable SMTP authentication
							$mail->Username = 'sanskartechnolab@gmail.com';   	 // SMTP username - Insert Email Address
							$mail->Password = 'sanskar@987654'; 					// SMTP password - Insert Email Account Password

							//$mail->SMTPSecure = 'ssl';                         // Enable TLS encryption, `ssl` also accepted
							//$mail->Port = 465; 

							$mail->SMTPSecure = 'tls';                         // Enable TLS encryption, `ssl` also accepted
							$mail->Port = 587;                                 // TCP port to connect to  587 / 465
							//$mail->SMTPDebug = 3;
							$mail->setFrom('sanskartechnolab@gmail.com', 'Sanskar');
							$mail->addReplyTo('sanskartechnolab@gmail.com', 'Sanskar');
							$mail->addAddress('sanskartechnolab@gmail.com');
							$mail->addAddress($email);   			// Add a recipient
							//$mail->addCC('demo@yahoo.co.in');
							//$mail->addBCC('bcc@example.com');
							$mail->isHTML(true);  // Set email format to HTML
							$url= $BASE_URL . '/login.php?email=' . urlencode($email) . "&key=$hash";
							$bodyContent = '<h1>Sanskar Technolab Pvt. Ltd.</h1>';
							$bodyContent .= '<p>Your Registration is successfully done in <b>H.I.S.</b></p>';

							$bodyContent .='<p>To activate your account please click on Activate buttton</p>';
							$bodyContent.='<table cellspacing="0" cellpadding="0"> <tr>'; 
							$bodyContent .= '<td align="center" width="300" height="40" bgcolor="#000091" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; color: #ffffff; display: block;">';
							$bodyContent .= '<a href="'.$url.'" style="color: #ffffff; font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none;  line-height:40px; width:100%; display:inline-block">Click to Activate</a>';

							$bodyContent .= '</td> </tr> </table>'; 
							$mail->Subject = 'Activate Your Email';
							$mail->Body    = $bodyContent;
							if(!$mail->send()) {
								$error .= 'Message could not be sent. </li>';
								$error .='Mailer Error: ' . $mail->ErrorInfo.'</li>';
							} 
							else {
								$success = '<li  style="font-size:16px;">Message has been sent<br>A confirmation email has been sent to <b>'. $email.' </b> Please click on the Activate Button to Activate your account.</li>';
							} 
							//E-MAIL VERIFY LINK END
					}
					else
					{
						$error .= 'Profile Not Added Successfully.';
					}
				}
			}
		}
	}
}

 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Coderthemes">
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.png">

        <!-- App title -->
        <title><?php print($label); ?> Customer | <?php print(APP_NAME); ?></title>

        <!--Morris Chart CSS -->
		<link rel="stylesheet" href="plugins/morris/morris.css">

        <!-- Table Responsive css -->
        <link href="plugins/responsive-table/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">



        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="plugins/switchery/switchery.min.css">



        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

		<link href="plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
		<link href="plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
        <link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />
        <link href="plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />

        <script src="assets/js/modernizr.min.js"></script>
    </head><?php 


?>

    <body class="fixed-left">
        <!-- Loader -->
         <?php include("include/loader.php");?>
        <!-- Begin page -->
        <div id="wrapper">
            <!-- Top Bar Start -->
           <?php include("include/topbar.php");?>
            <!-- Top Bar End -->
           <!-- ========== Left Sidebar Start ========== -->
            <?php include("include/leftbar.php");?>
            <!-- Left Sidebar End -->
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title"><?php print($label);?> Customer</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li><a href="dashboard.php">Dashboard</a></li>
										<li><a href="javascript:;">Customer Management</a></li>
                                        <li class="active"><?php print($label);?> Customer</li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>
                        <!-- end row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-box table-responsive">
								<?php if($error!=''){ ?>
									<div class="alert alert-danger" role="alert">
										<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
										<?php echo $error; ?>
									</div>
									<?php } ?>
									<?php if($success!=''){ ?>
										<div class="alert alert-success" role="alert">
											<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
											<?php echo $success; ?>
										</div>
									<?php } ?>
									<h3 class="header-title m-t-0 m-b-10"><i class="fa fa-plus-circle"></i> <?php print($label);?> Customer</h3>  
									<form class="form-horizontal" onSubmit="javascript:return submitAdminNewUser();" name="frmUser" id="frmUser" role="form" method="POST" action="add-customer.php">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="col-md-2 control-label">First Name</label>
													<div class="col-md-10">
														<input type="text" value="<?php print($txtFirstName);?>" name="txtFirstName" id="txtFirstName" class="form-control"  placeholder="Enter First Name">
														<ul class="parsley-errors-list filled" id="firstName" style="display:none;"><li class="parsley-required">Please enter first name.</li></ul>
													</div>
												</div>
											</div>  
											<div class="col-md-4">
												<div class="form-group">
													<label class="col-md-2 control-label">Last Name</label>
													<div class="col-md-10">
														<input type="text" value="<?php print($txtLastName);?>" name="txtLastName" id="txtLastName" class="form-control"  placeholder="Enter Last Name">
														<ul class="parsley-errors-list filled" id="lastName" style="display:none;"><li class="parsley-required">Please enter last name.</li></ul>
													</div>
												</div>
											</div> 
											<div class="col-md-4">
												<div class="form-group">
													<label class="col-md-2 control-label" >Username</label>
													<div class="col-md-10">
														<input type="text" value="<?php print($txtUsername);?>" id="txtUsername"  name="txtUsername" class="form-control" placeholder="Enter Username">
														<ul class="parsley-errors-list filled" id="userName" style="display:none;"><li class="parsley-required">Please enter username.</li></ul>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="col-md-2 control-label" >Email</label>
													<div class="col-md-10">
														<input class="form-control" value="<?php print($txtEmail);?>" id="txtEmail" placeholder="Enter Email" name="txtEmail" type="email">
														<ul class="parsley-errors-list filled" id="Email" style="display:none;"><li class="parsley-required">Please enter email .</li></ul>
														<ul class="parsley-errors-list filled" id="IVEmail" style="display:none;"><li class="parsley-required">Please enter valid email.</li></ul>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="col-md-2 control-label">Mobile No.</label>
													<div class="col-md-10">
														<input type="text" value="<?php print($txtMobileNo);?>" class="form-control" id="txtMobileNo" name="txtMobileNo"  placeholder="Enter Mobile Number">
														<ul class="parsley-errors-list filled" id="mobileNo" style="display:none;"><li class="parsley-required">Please enter mobile number .</li></ul>
														<ul class="parsley-errors-list filled" id="IVMobileNo" style="display:none;"><li class="parsley-required">Please enter valid mobile number.</li></ul>
													</div>
												</div>
											</div>  
											<div class="col-md-4">
												<div class="form-group">
													<label class="col-md-2 control-label"> Password</label>
													<div class="col-md-10">
														<input type="password" value="<?php print($txtPassword);?>" id="txtPassword" name="txtPassword" class="form-control" placeholder="Enter Password">
														<ul class="parsley-errors-list filled" id="password" style="display:none;"><li class="parsley-required">Please enter password.</li></ul>
													</div>
												</div>
											</div>		
										</div>

										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="col-md-2 control-label" >Address</label>
													<div class="col-md-10">
														<input type="text" value="<?php print($txtAddress);?>" id="txtAddress"  name="txtAddress" class="form-control" placeholder="Enter Address">
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="col-md-2 control-label" >City</label>
													<div class="col-md-10">
														<input type="text" value="<?php print($txtCity);?>" class="form-control" id="txtCity"  name="txtCity" placeholder="Enter City">
														<ul class="parsley-errors-list filled" id="city" style="display:none;"><li class="parsley-required">Please enter city.</li></ul>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="col-md-2 control-label">Pincode</label>
													<div class="col-md-10">
														<input type="text" value="<?php print($txtPincode);?>" class="form-control" id="txtPincode"  name="txtPincode"  placeholder="Enter Pincode">
													</div>
												</div>
											</div>	
											<div class="col-md-4" id="divPackage" >
												<div class="form-group"><?php 
												if($userdata['user_type'] == "")	{
													?><label class="col-md-2 control-label" >Select Dealer :</label>
													<select style="max-width: 80%;" id="lsDealer" class="form-control select2" name="lsDealer" disabled>
														 <option value="">Select Dealer</option><?php 
															$arrDealers = getDealer();
															foreach($arrDealers as $key => $value){
															?><option value="<?php print($key);?>" 
															<?php if($userdata['id'] ==  $key ){
																?> selected="selected" <?php 
															}?>>
															<?php print($value);?></option><?php
														}
														?>
													</select><?php	
												}
												else { 
													?><label class="col-md-2 control-label" >Select Dealer :</label>
													<select style="max-width: 80%;" id="lsDealer" class="form-control select2" name="lsDealer">
														 <option value="">Select Dealer</option><?php 
															$arrDealers = getDealer();
															foreach($arrDealers as $key => $value){
															?><option value="<?php print($key);?>" 
															<?php if($lsDealerId ==  $key ){
																?> selected="selected" <?php 
															}?>>
															<?php print($value);?></option><?php
														}
														?>
													</select><?php
												}?>		
												</div>
											</div>
											<div class="col-md-4" id="divPackage" >
												<div class="form-group">
													<label class="col-md-2 control-label" >Package</label>
													<div class="col-md-10">
														<select  name="lstPackageType" id="lstPackageType" class="form-control" onChange ="javascript:getPackage(this.value);">
															<option value="">Select Package</option><?php
															foreach($arrPackageNameInfo as $key => $value){
																?><option value="<?php print($value['id']);?>" 
																<?php if($lstPackageType == $value['id'] ){
																	?> selected="selected" <?php 
																}?>>
																<?php print($value['name']);?></option><?php
															}
															?>
														</select>
														<ul class="parsley-errors-list filled" id="selPackage" style="display:none;"><li class="parsley-required">Please select atleast one package.</li></ul>
													</div>
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label class="col-md-2 control-label">Package Price</label>
													<div class="col-md-10"><?php 
													if($userdata['user_type'] == "")	{
														?><input type="text" value="<?php print($packagePrice);?>" class="form-control" id="txtPackagePrice"  name="txtPackagePrice" disabled>
														 <input type="hidden" value="<?php print($packagePrice);?>" class="form-control" id="txtCustomerPackagePrice"  name="txtCustomerPackagePrice"><?php
													}
													else {
														?><input type="text" value="<?php print($packagePrice);?>" class="form-control" id="txtPackagePrice"  name="txtPackagePrice"><?php
													}?>
													</div>
												</div>
											</div>	
											<?php 

											//for Profile View
											if(isset($_REQUEST['from']) && $_REQUEST['from'] == "prf") {
												?><div class="col-md-4">
												<div class="form-group">
													<label class="col-md-2 control-label text-right" >Status</label>
													<div class="col-md-10" style="margin-top: 8px;">
														<?php if($status == 1){

															print("<label style='color:#4bd396;'>Active</label>"); 

														}else{

															print("<label style='color:#f5707a;'>Deactive</label>"); 

														}?>
													</div>
												</div>
											</div><?php
											}
											else {
											?>
											<div class="col-md-4">
												<div class="form-group">
													<label class="col-md-2 control-label text-right" >Status</label>
													<div class="col-md-10" style="margin-top: 8px;">
														<input type="checkbox" id="switch5" switch="bool" <?php echo ($status == 1)?"checked":""; ?> name="status"/>
														<label for="switch5" data-on-label="Active" data-off-label="Deactive"></label>
													</div>
												</div>
											</div><?php 
											}
										?></div>
										<div class="col-md-12">
											<div class="col-md-6 text-left">
												<a href="view-customers.php"  class="btn btn-primary btn-rounded w-md waves-effect waves-light">Back</a>
											</div>
											<div class="col-md-6 text-right">
												 <?php
													if(isset($_GET['editId'])) { ?>
														<input type="hidden" name="id" value="<?php echo $_GET['editId'];?>" />
														<input type="hidden" name="action" value="<?php echo $_GET['action'];?>" />
														<input type="hidden" name="user_type" value="<?php echo $user_type;?>" />                                                                  
												   <?php } ?>     
												<button type="submit" name="submit" id="submit" class="btn btn-info btn-rounded w-md waves-effect waves-light" title="<?php if(isset($_GET['editId'])) { echo "Update "; } else { echo "Add "; } ?>Customer"><?php if(isset($_GET['editId'])) { echo "Update "; } else { echo "Add "; } ?> Customer</button>
											</div>
										</div>
									</form>     
                                </div>
                                <!-- END SIMPLE DATATABLE -->
                            </div>
                        </div>
                        <!-- end row -->
                    </div> <!-- container -->
                </div> <!-- content -->
                <?php include("include/footer-text.php");?>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
            <!-- Right Sidebar -->
            <?php include("include/right-sidebar.php");?>
            <!-- /Right-bar -->
        </div>
        <!-- END wrapper -->

       <script>
            var resizefunc = [];
        </script>
		<!-- jQuery  -->
	   	<script src="assets/js/fieldvalidation.js"></script>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="plugins/switchery/switchery.min.js"></script>
       <!-- App js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>
		</script>
<script>
	// This i global veriable for xmlHttp object
	var xmlHttp;

	// This function is been used to create xmlHttp object for ajax request
	function GetXmlHttpObject() {
		try	{
			// Firefox, Opera 8.0+, Safari
			xmlHttp = new XMLHttpRequest();
		}
		catch(e) {
			// Internet Explorer
			try	{
				xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e) {
				try {
					xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch(e) {
					alert("Your browser does not support AJAX!");
					return false;
				}
			}
		}
	}
	
	/* 
		This function is used to Fetch th Batch
	*/
	function getPackage(val) {
		GetXmlHttpObject();
		var url = 'getajax.php?action=getPackagePrice&pckId='+val;
		xmlHttp.onreadystatechange = getPriceOfPackage;
		xmlHttp.open("GET",url,true);
		xmlHttp.send(null);
	}

	/*
		Function calling for handling ajax response.
	*/
	function getPriceOfPackage() {
		if(xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
			strResponseText	 = xmlHttp.responseText;
			var pckPrice = strResponseText.trim();
			document.getElementById("txtPackagePrice").value = pckPrice;
			document.getElementById("txtCustomerPackagePrice").value = pckPrice;
		}
	}
	</script>
    </body>

</html>