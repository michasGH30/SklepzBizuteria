<?php

session_start();

if(!isset($_SESSION['zalogowany']))
{
	header('Location: panel_logowania.php');
	exit();
}

if(isset($_GET['change']))
{	
	$ktory=$_GET['change'];
	
	$product_title=$_POST['title'];
		
	$product_price=$_POST['price'];
		
	$product_category=$_POST['folder'];
		
	if (isset($_POST['available']) && $_POST['available'] == 'Yes') 
	{
		$product_available=1;
	}
	else
	{
		$product_available=0;
	}    
		
	if(isset($_POST['number']))
	{
		$product_number=$_POST['number'];
	}
	else
	{
		$product_number=0;
	}
		
	if(isset($_POST['number']))
	{
		$product_number=$_POST['number'];
	}
	else
	{
		$product_number=0;
	}
	
	$product_description=$_POST['description'];
	
	$insert_product_title="UPDATE products SET title='$product_title'WHERE ID=$ktory";
	$insert_product_cat="UPDATE products SET category='$product_category' WHERE ID=$ktory";
	$insert_product_ava="UPDATE products SET available='$product_available' WHERE ID=$ktory";
	$insert_product_num="UPDATE products SET number='$product_number' WHERE ID=$ktory";
	$insert_product_price="UPDATE products SET price='$product_price' WHERE ID=$ktory";
	$insert_product_description="UPDATE products SET description='$product_description' WHERE ID=$ktory";
	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);

		
	try
	{
		$polaczenie = new mysqli($host,$db_user,$db_password,$db_name);

		if($polaczenie->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			if(!$polaczenie->query(sprintf($insert_product_title))) throw new Exception($polaczenie->error);
	
			if(!$polaczenie->query(sprintf($insert_product_cat))) throw new Exception($polaczenie->error);;
		
			if(!$polaczenie->query(sprintf($insert_product_ava))) throw new Exception($polaczenie->error);
	
			if(!$polaczenie->query(sprintf($insert_product_num))) throw new Exception($polaczenie->error);
	
			if(!$polaczenie->query(sprintf($insert_product_price))) throw new Exception($polaczenie->error);
	
			if(!$polaczenie->query(sprintf($insert_product_description))) throw new Exception($polaczenie->error);

			$polaczenie->close();
			header('Location: deleted.php');
		}
	}
	catch(Exception $e)
	{
		echo "<span style='color:red'>Błąd w działaniu serwera spróbuj w innym terminie.</span>";
		echo "DEVELOP ".$e;
	}
		
		
	
}

?>