<?php
/*
 *  The Syncro Soft SRL License
 *
 *  Copyright (c) 1998-2012 Syncro Soft SRL, Romania.  All rights
 *  reserved.
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions
 *  are met:
 *
 *  1. Redistribution of source or in binary form is allowed only with
 *  the prior written permission of Syncro Soft SRL.
 *
 *  2. Redistributions of source code must retain the above copyright
 *  notice, this list of conditions and the following disclaimer.
 *
 *  3. Redistributions in binary form must reproduce the above copyright
 *  notice, this list of conditions and the following disclaimer in
 *  the documentation and/or other materials provided with the
 *  distribution.
 *
 *  4. The end-user documentation included with the redistribution,
 *  if any, must include the following acknowledgment:
 *  "This product includes software developed by the
 *  Syncro Soft SRL (http://www.sync.ro/)."
 *  Alternately, this acknowledgment may appear in the software itself,
 *  if and wherever such third-party acknowledgments normally appear.
 *
 *  5. The names "Oxygen" and "Syncro Soft SRL" must
 *  not be used to endorse or promote products derived from this
 *  software without prior written permission. For written
 *  permission, please contact support@oxygenxml.com.
 *
 *  6. Products derived from this software may not be called "Oxygen",
 *  nor may "Oxygen" appear in their name, without prior written
 *  permission of the Syncro Soft SRL.
 *
 *  THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESSED OR IMPLIED
 *  WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 *  OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED.  IN NO EVENT SHALL THE SYNCRO SOFT SRL OR
 *  ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 *  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 *  LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF
 *  USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 *  OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT
 *  OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 *  SUCH DAMAGE.
 */
require_once 'init.php';
 
 //$ses=Session::getInstance(); 
  	$json = new JsonResponse();
if (isset($_POST['userName']) && trim($_POST['userName']) != ''){
  // send email to support
  
  $info['username']= $_POST['userName'];
  $info['name'] = $_POST['name'];
  $info['password'] = $_POST['password'];
  $info['email'] = $_POST['email']; 
  
  $user= new User($dbConnectionInfo);
  $return = $user->insertNewUser($info);  
  if ($return->error=="true"){
  	echo $return;  	  	
  }else{
  	$id=base64_encode($user->userId."|".$user->date);
    $link="<a href='".__BASE_URL__."oxygen-webhelp/resources/confirm.html?id=$id'>".__BASE_URL__."oxygen-webhelp/resources/confirm.html?id=$id</a>";
  	$template = new Template("./templates/signUp.html");
  	$productTranslate=(defined("__PRODUCT_NAME__") ? __PRODUCT_NAME__ : $_POST['product']);
  	$arrayProducts=$user->getSharedProducts();
  	$products="";
  	foreach ($arrayProducts as $productId => $productName) {
  		$products.="\"".$productName."\" ";
  	}
  	
  	$confirmationMsg = $template->replace(
  			array("name"=>$info['name'],
  					"username"=>$info['username'],
  					"confirmationLink"=>$link,
  					"productName"=>$productTranslate,
  					"products"=>$products));
  	$mail = new Mail();
  	$mail->Subject("[".$productTranslate."] ".Utils::translate('signUpEmailSubject'));  	 
  	$mail->To($info['email']);  	
  	$mail->From(__EMAIL__);  	
  	$mail->Body($confirmationMsg);  	
  	$mail->Send();    	
	$json->set("error", "false");
	$json->set("msg", "SignUp Success");
	echo $json;	  	
  }
}else{
	$json->set("error", "true");
	$json->set("errorCode", "6");
	$json->set("msg", "Invalid username!");
	echo $json;
}
?>