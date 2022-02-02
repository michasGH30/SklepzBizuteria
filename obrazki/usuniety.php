<?php

	session_start();
	
	require_once "connect.php";
	
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: panel_logowania.php');
		exit();
	}
	
	if(isset($_GET['ktory']))
	{
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
				$ktory=$_GET['ktory'];
				$to_delete=$polaczenie->query(sprintf("SELECT * FROM products WHERE ID=$ktory"));
				if(!$to_delete) throw new Exception($polaczenie->error);
				
				$to_delete_dane=$to_delete->fetch_assoc();
				
				$to_delete_photo_1=$to_delete_dane['photo_1'];
				$to_delete_photo_2=$to_delete_dane['photo_2'];
				$to_delete_photo_3=$to_delete_dane['photo_3'];
				
				$image_1=$polaczenie->query(sprintf("SELECT * FROM images WHERE ID=$to_delete_photo_1"));
				$image_dane_1=$image_1->fetch_assoc();
				$image_src_1=$image_dane_1['folder'].'/'.$image_dane_1['name'];
				unlink($image_src_1);
				if(!$polaczenie->query(sprintf("DELETE FROM images WHERE id=$to_delete_photo_1"))) throw new Exception($polaczenie->error);
				
				$image_2=$polaczenie->query(sprintf("SELECT * FROM images WHERE ID=$to_delete_photo_2"));
				$image_dane_2=$image_2->fetch_assoc();
				$image_src_2=$image_dane_2['folder'].'/'.$image_dane_2['name'];
				unlink($image_src_2);
				if(!$polaczenie->query(sprintf("DELETE FROM images WHERE id=$to_delete_photo_2"))) throw new Exception($polaczenie->error);;
				
				$image_3=$polaczenie->query(sprintf("SELECT * FROM images WHERE ID=$to_delete_photo_3"));
				$image_dane_3=$image_3->fetch_assoc();
				$image_src_3=$image_dane_3['folder'].'/'.$image_dane_3['name'];
				unlink($image_src_3);
				if(!$polaczenie->query(sprintf("DELETE FROM images WHERE id=$to_delete_photo_3"))) throw new Exception($polaczenie->error);
				
				if(!$polaczenie->query(sprintf("DELETE FROM products WHERE id=$ktory"))) throw new Exception($polaczenie->error);
				
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
