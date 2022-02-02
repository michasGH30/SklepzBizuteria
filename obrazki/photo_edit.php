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
	
				if(isset($_FILES['image']))
				{
					$errors= array();
					$file_name = $_FILES['image']['name'];
					$file_size = $_FILES['image']['size'];
					$file_tmp = $_FILES['image']['tmp_name'];
					$file_type = $_FILES['image']['type'];
					$file_ext_f=explode('.',$_FILES['image']['name']);
					$file_ext=strtolower(end($file_ext_f));
					$file_folder_results=$polaczenie->query(sprintf("SELECT category FROM products WHERE ID=$ktory"));
					if(!$file_folder_results) throw new Exception($polaczenie->error);
					$file_f=$file_folder_results->fetch_assoc();
					$file_folder=$file_f['category'];
					$extensions= array("jpeg","jpg","png");
			  
					if(in_array($file_ext,$extensions)=== false){
						$errors[]="extension not allowed, please choose a JPEG or PNG file.";
					}
			  
					if($file_size > 1073741824) {
					$errors[]='File size must be excately 10 MB';
					}
			  
					if(empty($errors)) 
					{
						$file_src=$file_folder."/".$file_name;
					  
						move_uploaded_file($file_tmp,$file_src);
					 
						$insert = "INSERT INTO images(ID,folder,name) VALUES (' ','$file_folder','$file_name')";
					 
						if($polaczenie->query(sprintf($insert)) === TRUE)
						{
							$_SESSION["photo_edit_message"]="Dodano zdjęcie<br>";
							$photo=$polaczenie->query(sprintf("SELECT ID FROM images WHERE name='$file_name'"));

							$photo_assoc=$photo->fetch_assoc(); 
					
							$photo_id=$photo_assoc['ID'];
					
							if($which==1)
							{
							$insert_product="UPDATE products SET photo_1='$photo_id' WHERE ID=$ktory";
							}
							else if($which==2)
							{
								$insert_product="UPDATE products SET photo_2='$photo_id' WHERE ID=$ktory";
							}
							else if($which==3)
							{
								$insert_product="UPDATE products SET photo_3='$photo_id' WHERE ID=$ktory";
							}
					
							if($polaczenie->query(sprintf($insert_product))=== TRUE)
							{
								$_SESSION["photo_edit_message"]=$_SESSION["photo_edit_message"]."Dodano zdjęcie do produktu<br>";
							}
							else
							{
								
							}
					
						}
						else
						{
							throw new Exception($polaczenie->error);
						}
					
						$_SESSION["photo_edit_message"]=$_SESSION["photo_edit_message"]."<img src=$file_src /><br><br>";
						header("Location: photo_change.php?change=$ktory");
						exit();
					}
				}
			$polaczenie->close();
			}
		}
		catch(Exception $e)
		{
			echo "<span style='color:red'>Błąd w działaniu serwera spróbuj w innym terminie.</span>";
			echo "DEVELOP ".$e;
		}
   }
   



 
	
		

	  
?>