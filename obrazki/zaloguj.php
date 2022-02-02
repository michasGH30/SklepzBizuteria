<?php

	session_start();
	
	if((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
	{
		header('Location: panel_logowania.php');
		exit();
	}
	else
	{
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		$login = $_POST['login'];
		$password = $_POST['haslo'];
		
		$login=htmlentities($login,ENT_QUOTES,"UTF-8");
		$password=htmlentities($password,ENT_QUOTES,"UTF-8");
		
		try
		{
			$polaczenie = new mysqli($host,$db_user,$db_password,$db_name);
			if($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_error);
			}
			else
			{
				$query=$polaczenie->query(
				sprintf("SELECT * FROM admins WHERE login='%s'",
				mysqli_real_escape_string($polaczenie,$login)));
				if(!$query){throw new Exception($polaczenie->error);}
				$num = $query->num_rows;
				if($num>0)
				{
					$results=$query->fetch_assoc();
					if(password_verify($password,$results['password']))
					{
						$_SESSION['zalogowany'] = true;
						header('Location: zalogowany.php');
					}
					else
					{
						$_SESSION['blad']="<span style='color:red;'>Niepoprawne hasło</span>";
						header('Location: panel_logowania.php');
						exit();
					}
				}
				else
				{
					$_SESSION['blad']="<span style='color:red;'>Niepoprawny login</span>";
					header('Location: panel_logowania.php');
					exit();
				}
			}
		}
		catch (Exception $e)
		{
			echo "<span style='color:red'>Błąd w działaniu serwera spróbuj w innym terminie.</span>";
			echo "DEVELOP ".$e;
		}
	}

?>