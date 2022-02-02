<?php
	session_start();
	
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: panel_logowania.php');
		exit();
	}
	
	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
		
	$jewellery=$_POST['jewellery'];
	
	$what_type=$_POST['what_type'];
	
	if($what_type=="tło")
	{
		$available=1;
		$number=1;
	}
	else
	{
		if(isset($_POST['available']) && $_POST['available']=="Yes")
		{
			$available=1;
			$number=$_POST['number'];
		}
		else
		{
			$available=0;
			$number=$_POST['number'];
		}
	}
	
	try
	{
		$polaczenie = new mysqli($host,$db_user,$db_password,$db_name);

		if($polaczenie->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			$extensions= array("jpeg","jpg","png");
			$errors=array();
			$file_name = $_FILES["image"]['name'];
			$file_size = $_FILES["image"]['size'];
			$file_tmp = $_FILES["image"]['tmp_name'];
			$file_type = $_FILES["image"]['type'];
			$file_ext_f=explode('.',$_FILES["image"]['name']);
			$file_ext=strtolower(end($file_ext_f));    
			if(in_array($file_ext,$extensions)=== false)
			{
				$errors[]="extension not allowed, please choose a JPEG or PNG file.";
			}			
			if(empty($errors)==true) 
			{
				$file_src="personalise/".$jewellery."/".$what_type."/".$file_name;
				  
				move_uploaded_file($file_tmp,$file_src);
				 
				$insert = "INSERT INTO personalise(ID,jewellery,what_type,what_exactly,available,number) VALUES (' ','$jewellery','$what_type','$file_name','$available',$number)";
				
				if($polaczenie->query(sprintf($insert)) === TRUE)
				{
					$_SESSION["personalise_add_message"]="Dodano zdjęcie: $jewellery, $what_type, $file_name<br> 
					<img src=$file_src /><br>";
				}
				else
				{
					$_SESSION["personalise_add_message"]="Nie doddano $file_src zdjęcia<br>";
					throw new Exception($polaczenie->error);
				}
				header("Location: personalise_u.php");
				
			}
			
		}
		
	}
	catch(Exception $e)
	{
		echo "<span style='color:red'>Błąd w działaniu serwera spróbuj w innym terminie.</span>";
		echo "DEVELOP ".$e;
	}
	
	
?>