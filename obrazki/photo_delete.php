<?php

session_start();
	
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: panel_logowania.php');
		exit();
	}
	
	if(isset($_GET['which']) && isset($_GET['ktory']))
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
				$which=$_GET['which'];
				if($which==1)
				{
					$photo_delete="SELECT photo_1 FROM products WHERE ID=$ktory;";
				} else if($which==2)
				{
					$photo_delete="SELECT photo_2 FROM products WHERE ID=$ktory;";
				} else if($which==3)
				{
					$photo_delete="SELECT photo_3 FROM products WHERE ID=$ktory;";
				}
				
				$delete=$polaczenie->query(sprintf($photo_delete));
				
				if(!$delete) throw new Exception($polaczenie->error);

				$delete_results=$delete->fetch_assoc();
				
				if($which==1)
				{
					$delete_ID=$delete_results['photo_1'];
				} else if($which==2)
				{
					$delete_ID=$delete_results['photo_2'];
				} else if($which==3)
				{
					$delete_ID=$delete_results['photo_3'];
				}
				
				$photo=$polaczenie->query(sprintf("SELECT * FROM images WHERE ID=$delete_ID"));
				if(!$photo) throw new Exception($polaczenie->error);
				$photo_results=$photo->fetch_assoc();
				$photo_folder=$photo_results['folder'];
				$photo_nazwa=$photo_results['name'];
				$photo_src=$photo_folder.'/'.$photo_nazwa;
				unlink($photo_src);
				
				if($which==1)
				{
					$photo_update="UPDATE products SET photo_1=0 WHERE ID=$ktory;";
				} else if($which==2)
				{
					$photo_update="UPDATE products SET photo_2=0 WHERE ID=$ktory;";
				} else if($which==3)
				{
					$photo_update="UPDATE products SET photo_3=0 WHERE ID=$ktory;";
				}
				
				$polaczenie->query(sprintf($photo_update));
				if(!$polaczenie) throw new Exception($polaczenie->error);
				$polaczenie->close();
				header("Location: photo_change.php?change=$ktory");
			}
		}
		catch(Exception $e)
		{
			echo "<span style='color:red'>Błąd w działaniu serwera spróbuj w innym terminie.</span>";
			echo "DEVELOP ".$e;
		}
	}
		

?>