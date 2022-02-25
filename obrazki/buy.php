<?php
session_start();
if(isset($_POST['imie']))
{
	$ok=true;
	
	$imie=$_POST['imie'];
	
	$imie=strip_tags($imie);
	
	$nazwisko=$_POST['nazwisko'];
	
	$nazwisko=strip_tags($nazwisko);
	
	$phone=$_POST['phone'];
	
	$fil_phone=filter_var($phone,FILTER_SANITIZE_NUMBER_INT);
	
	$phone_to_check=str_replace("-", "", $fil_phone);
	
	if (strlen($phone_to_check) < 9) 
	{
		$ok=false;
		$_SESSION['e_phone']="Podano zły numer telefonu!";
    } 
	$email=$_POST['email'];
	$emailB=filter_var($email,FILTER_SANITIZE_EMAIL);
	
	if(filter_var($emailB,FILTER_VALIDATE_EMAIL)==false || ($email!=$emailB))
	{
		$ok=false;
		$_SESSION['e_email']="Podano zły adres e-mail!";
	}
	
	$adres=$_POST['adres'];
	
	$adres=strip_tags($adres);
	
	$sekret="XYZ";
	
	$r=$_POST['g-recaptcha-response'];
	
	$url="https://www.google.com/recaptcha/api/siteverify?secret=$sekret&response=$r";
	
	$sprawdz=file_get_contents($url);
	
	$odpowiedz=json_decode($sprawdz, true);
	
	if($odpowiedz['success']==false)
	{
		$ok=false;
		$_SESSION['e_bot']="Potwierdź, że nie jesteś botem!";
	}
	
	$_SESSION['fr_nazwisko']=$nazwisko;
	$_SESSION['fr_email']=$email;
	$_SESSION['fr_imie']=$imie;
	$_SESSION['fr_adres']=$adres;
	$_SESSION['fr_phone']=$phone;
	
	$_SESSION['session_ok']=$ok;
	
	if($ok)
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
				$id_max=$polaczenie->query(sprintf("SELECT MAX(ID_order) AS M FROM orders_details"));
				if(!$id_max){throw new Exception($polaczenie->error);}
				$results=$id_max->fetch_assoc();
				$your_id=$results['M']+1;
				$_SESSION['your_id']=$your_id;
				$price_to_pay=0;
				if(isset($_SESSION['bucket']))
				{
					$max=sizeof($_SESSION['bucket']);
					for($i=0; $i<$max; $i++)
					{ 
						if(isset($_SESSION['bucket'][$i]['ID']))
						{ 
							$price_to_pay+=$_SESSION['bucket'][$i]['price'];
						}
					}
					//echo "Cena po zwykłych zakupach".$price_to_pay."<br>";
					for($i=0; $i<$max; $i++)
					{ 
						if(isset($_SESSION['bucket'][$i]['ID']))
						{ 
							$tytuł_z=$_SESSION['bucket'][$i]['title'];
							$cena_z=$_SESSION['bucket'][$i]['price'];
							$kolor_z=$_SESSION['bucket'][$i]['color'];
							$dlugosc_z=$_SESSION['bucket'][$i]['long'];
							$id=$_SESSION['bucket'][$i]['ID'];
							
							$add_to_order="INSERT INTO orders values('','$your_id','$id','$tytuł_z','$kolor_z','$dlugosc_z')";
							$add_to_order_query=$polaczenie->query(sprintf($add_to_order));
							
							if(!$add_to_order_query){throw new Exception($polaczenie->error);}
							
							$number_update="UPDATE products SET number = number-1 WHERE ID=$id";
							$number_query=$polaczenie->query(sprintf($number_update));
							if(!$number_query){throw new Exception($polaczenie->error);}
							$available_update=$polaczenie->query(sprintf("SELECT number FROM products WHERE ID=$id"));
							if(!$available_update){throw new Exception($polaczenie->error);}
							$available_update_r=$available_update->fetch_assoc();
							$available_update_num=$available_update_r['number'];
							if($available_update_num<=0)
							{
								$available_update=$polaczenie->query(sprintf("UPDATE products SET available=0 WHERE ID=$id"));
								if(!$available_update){throw new Exception($polaczenie->error);}
							}
						}
					}
				}
				if(isset($_SESSION['personalise']))
				{
					$max=sizeof($_SESSION['personalise']);
					for($i=0; $i<$max; $i++)
					{ 
						if(isset($_SESSION['personalise'][$i]['jewellery']))
						{ 
							$price_to_pay+=$_SESSION['personalise'][$i]['price'];
						}
					}
					//echo "Cena po pers zakupach".$price_to_pay."<br>";
					for($i=0; $i<$max; $i++)
					{ 
						if(isset($_SESSION['personalise'][$i]['jewellery']))
						{
							$jewellery=$_SESSION['personalise'][$i]['jewellery'];
							$background=$_SESSION['personalise'][$i]['background'];
							
							$flower_1=$_SESSION['personalise'][$i]['flowers_1'];
							$flower_2=$_SESSION['personalise'][$i]['flowers_2'];
							$flower_3=$_SESSION['personalise'][$i]['flowers_3'];
							$flower_4=$_SESSION['personalise'][$i]['flowers_4'];
							$flower_5=$_SESSION['personalise'][$i]['flowers_5'];
							
							$color=$_SESSION['personalise'][$i]['color'];
							
							$long=0;
							
							if($_SESSION['personalise'][$i]['long']!="0")
							{
								$long=$_SESSION['personalise'][$i]['long'];
							}
							else if($_SESSION['personalise'][$i]['diameter']!="0")
							{
								$long=$_SESSION['personalise'][$i]['diameter'];
							}
							$add_to_order="INSERT INTO orders_personalise values('','$your_id','$jewellery','$background','$flower_1','$flower_2','$flower_3','$flower_4','$flower_5','$color','$long')";
							$add_to_order_query=$polaczenie->query(sprintf($add_to_order));
							if(!$add_to_order_query){throw new Exception($polaczenie->error);}
							
							for($j=1;$j<6;$j++)
							{
								$to_flowers="flowers_".$j;
								if($_SESSION['personalise'][$i][$to_flowers]!="none")
								{
									$w_exactly=$_SESSION['personalise'][$i][$to_flowers];
									$number_update="UPDATE personalise SET number = number-1 WHERE jewellery='$jewellery' AND what_type='kwiaty' AND what_exactly='$w_exactly'";
									$number_query=$polaczenie->query(sprintf($number_update));
									if(!$number_query){throw new Exception($polaczenie->error);}
									
									$available_update=$polaczenie->query(sprintf("SELECT number FROM personalise WHERE jewellery='$jewellery' AND what_type='kwiaty' AND what_exactly='$w_exactly'"));
									if(!$available_update){throw new Exception($polaczenie->error);}
									
									$available_update_r=$available_update->fetch_assoc();
									$available_update_num=$available_update_r['number'];
									if($available_update_num<=0)
									{
										$available_update=$polaczenie->query(sprintf("UPDATE personalise SET available=0 WHERE jewellery='$jewellery' AND what_type='kwiaty' AND what_exactly='$w_exactly'"));
										if(!$available_update){throw new Exception($polaczenie->error);}
									}
								}
							}
						}
					}
				}
				
				if($_POST['mail']=="mail")
				{
					$price_to_pay+=8.00;
				}
				else if($_POST['mail']=="paczkomat")
				{
					$price_to_pay+=12.00;
				}
				$price_to_pay=number_format($price_to_pay,2);
				$_SESSION['price_to_pay']=$price_to_pay;
				//echo "Cena po dostawie".$price_to_pay."<br>";
				$add_details="INSERT INTO orders_details values('','$your_id','$imie','$nazwisko','$phone_to_check','$emailB','$adres','$price_to_pay',0,0)";
				$add_details_query=$polaczenie->query(sprintf($add_details));
				$_SESSION['potwierdzenie']="Numer twojego zamowienia to: ".$your_id." opłać zamówienie na podaną kwotę ".$price_to_pay." zł w tytule podając swój numer zamowienia, aby rozpocząć proces wysyłki. Sprawdź także swojego e-mail'a, ponieważ zostanie na niego dostarczona informacja o wysłaniu paczki lub otrzymaniu przelewu.";
				$polaczenie->close();
				$_SESSION['jump']=1;
			}
		}
		catch(Exception $e)
		{
			echo "<span style='color:red'>Błąd w działaniu serwera spróbuj w innym terminie.</span>";
			echo "DEVELOP ".$e;
		}
		
		
	}
	
}
?>
<html lang="pl">
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset="utf-8">
<META HTTP-EQUIV="Pragma" content="cache">
<META NAME="ROBOTS" CONTENT="all">
<META HTTP-EQUIV="Content-Language" CONTENT="pl">
<META NAME="description" CONTENT="Lubisz biżuterię? Mamy coś dla ciebie. Tylko u nasz ręcznie robiona biżuteria z żywicy.">
<META NAME="keywords" CONTENT="biżuteria,żywica,bransoletka,naszyjnik,pierścionek,broszka,kolczyki">
<META NAME="author" CONTENT="Michał Żuk">
<META HTTP-EQUIV="Reply-to" CONTENT="michal.zuk30601@gmail.com">
<META NAME="revisit-after" CONTENT="2 days">
<TITLE>Żuk w żywicy</TITLE>
<title>Zamawiam</title>
<link rel="stylesheet" href="css/jquery.tabSlideOut.css">
<link rel="stylesheet" href="css/fontello.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<script src="https://www.google.com/recaptcha/api.js"></script>
<style>
#txt {
  min-width: 173px;
}

#hide {
  position: absolute;
  height: 0;
  overflow: hidden;
  white-space: pre;
}

.error
{
	color:red;
	margin-top: 10px;
	margin-bottom: 10px;
}
<!--#on{
position: absolute;
left:25%;
top: 50%;
z-index: 1;
color:white;
max-width: 50%;
background: rgba(10, 10, 10, 0.7);
border: 1px solid black;
border-radius: 10px;
padding:10px;
}-->
</style>
</head>
<body>
	<header>
		<div id="logo"><a href="index.php"><img src="img/logozuk.jpg" style="width:300px"><br>
		<div>
		MAGDALENA ŻUK<br>
		<span style="letter-spacing:20px;">handmade</span></div></a>
		</div>
		<nav>
			<div id="topnav">
				<div id="licon"><i class="icon-th-list"></i></div>
				<div id="links">
					<div class="navlink" id="jewelery_nav"><span class="nav_hover">BIŻUTERIA</span>
					<ul class="jew_ul">
					<li><a href="products.php?type=necklace">Naszyjniki</a></li>
					<li><a href="products.php?type=rings">Pierścionki</a></li>
					<li><a href="products.php?type=bracelet">Bransoletki</a></li>
					<li><a href="products.php?type=cufflinks">Spinki</a></li>
					<li><a href="products.php?type=brooch">Broszki</a></li>
					</ul></div>
					<div class="navlink" id="other_nav"><span class="nav_hover">INNE</span>
					<ul class="other_ul">
					<li><a href="products.php?type=pictures">Obrazy</a></li>
					<li><a href="products.php?type=napkins">Serwetki</a></li>
					<li><a href="products.php?type=hoops">Tamborki</a></li>
					<li><a href="products.php?type=handicraft">Rękodzieło</a></li>
					</ul></div>
					<div class="navlink" id="p"><a href="personalise.php"><span class="nav_hover">PERSONALIZOWANE</span></a></div>
					<div class="navlink" id="b"><a href="bucket.php"><span class="nav_hover">Koszyk <i class="demo-icon icon-basket"></i></a></div>
					<div class="navlink" id="l"><form action ="panel_logowania.php">
						<input id="loginButton" type="submit" <?php if(isset($_SESSION['zalogowany'])){echo "value='Panel Administracyjny'";}else{echo "value='Zaloguj się'";}?>> 
						</form>
					</div>
				</div>
			</div>
	</nav>
	</header>
	<div id="container" style="text-align:left;">
		<main>
			<div style="float:left;">
				<article>
					<?php
					if(isset($_SESSION['bucket']) || isset($_SESSION['personalise']))
					{
						$price_to_write=0;
						if(isset($_SESSION['bucket']))
						{
							$max=sizeof($_SESSION['bucket']);
							for($i=0; $i<$max; $i++)
							{ 
								if(isset($_SESSION['bucket'][$i]['ID']))
								{ 
									echo "Tytuł: ".$_SESSION['bucket'][$i]['title'];
									echo "<br>Cena: ".$_SESSION['bucket'][$i]['price']." zł";
									if($_SESSION['bucket'][$i]['color']!='none')
									{
										echo "<br>Kolor: ".$_SESSION['bucket'][$i]['color'];
									}
									if($_SESSION['bucket'][$i]['long']!=0)
									{
										echo "<br>Długość: ".$_SESSION['bucket'][$i]['long'];
										if($_SESSION['bucket'][$i]['category']=="necklace")
										{
											echo " cm";
										}
										else if($_SESSION['bucket'][$i]['category']=="earrings")
										{
											echo " mm";
										}
									}
									
									echo "<img src='{$_SESSION['bucket'][$i]['src']}' /> <br>";
									$price_to_write+=$_SESSION['bucket'][$i]['price'];
								}
							}
							
						}
						if(isset($_SESSION['personalise']))
						{
							$max_p=sizeof($_SESSION['personalise']);
							for($i=0; $i<$max_p; $i++)
							{ 
								if(isset($_SESSION['personalise'][$i]['jewellery']))
								{ 
									$src="personalise/".$_SESSION['personalise'][$i]['jewellery']."/tło/".$_SESSION['personalise'][$i]['background'];
									echo "<div style='width:60%;min-width:750px;text-align:center;'><div><img src='$src' style='width:350px;'></div><div>";
									$width=0;
									for($j=1;$j<6;$j++)
									{
										$name="flowers_".$j;
										if($_SESSION['personalise'][$i][$name]!="none")
										{
											$width++;
										}
									}
									if($width==1)
									{
										$width=350;
									}
									else
									{
										$width=700/$width;
									}
									for($j=1;$j<6;$j++)
									{
										$name="flowers_".$j;
										if($_SESSION['personalise'][$i][$name]!="none")
										{
											$src="personalise/".$_SESSION['personalise'][$i]['jewellery']."/kwiaty/".$_SESSION['personalise'][$i][$name];
											echo" <img src='$src' style='width:$width'>";
										}
									}
									echo "</div>Cena: ".$_SESSION['personalise'][$i]['price']," zł";
									if($_SESSION['personalise'][$i]['color']!='none')
									{
										echo "<br>Kolor: ".$_SESSION['personalise'][$i]['color'];
									}
									if($_SESSION['personalise'][$i]['long']!=0 || $_SESSION['personalise'][$i]['diameter']!=0)
									{
										echo "<br>Długość: ".$_SESSION['personalise'][$i]['long'];
										if($_SESSION['personalise'][$i]['long']!=0)
										{
											echo " cm";
										}
										else if($_SESSION['personalise'][$i]['diameter']!=0)
										{
											echo " mm";
										}
									}
									$price_to_write+=$_SESSION['personalise'][$i]['price'];
								}
							}
						}
						$price_to_write=number_format($price_to_write,2);
						//echo "Kwota do zapłaty: <span id='do_zaplaty'>".$price_to_write." </span>zł<br>";
					}
					else
					{
						echo "Koszyk pusty.<br>";
					}
					if(isset($_SESSION['session_ok']))
					{
						$msg =$_SESSION['potwierdzenie']; 
						echo "<div id='on'>$msg <button onclick='rozumiem()'>Rozumiem</button></div>";
						unset($_SESSION['jump']);
					}
					?>
				</article>
			</div>
			<div style="float:left;">
				<article>
					<?php  echo "Kwota do zapłaty: <span id='do_zaplaty'>".$price_to_write." </span>zł<br>";?>
					<form action=" " method="POST">
						<label>Imię: <input type="text" name="imie" value="
						<?php if(isset($_SESSION['fr_imie']))
						{
							echo $_SESSION['fr_imie'];
							unset($_SESSION['fr_imie']);
						}
						?>" placeholder="Imię"required></label><br>
						<label>Nazwisko: <input type="text" name="nazwisko" 
						value="
						<?php if(isset($_SESSION['fr_nazwisko']))
						{
							echo $_SESSION['fr_nazwisko'];
							unset($_SESSION['fr_nazwisko']);
						}
						?>" placeholder="Nazwisko" required></label><br>
						<label>Telefon: <input type="tel" name="phone" required 
						value="
						<?php if(isset($_SESSION['fr_phone']))
						{
							echo $_SESSION['fr_phone'];
							unset($_SESSION['fr_phone']);
						}
						?>" placeholder="123123123"></label><br>
						<?php 
							if (isset($_SESSION['e_phone']))
							{
								echo '<div class="error">'.$_SESSION['e_phone'].'</div>';
								unset($_SESSION['e_phone']);
							}
						?>
						<label>E-mail: <input type="email" name="email" value="
						<?php if(isset($_SESSION['fr_email']))
						{
							echo $_SESSION['fr_email'];
							unset($_SESSION['fr_email']);
						}
						?>" placeholder="twójemail@poczta.pl" required></label><br>
						<?php 
							if (isset($_SESSION['e_email']))
							{
								echo '<div class="error">'.$_SESSION['e_email'].'</div>';
								unset($_SESSION['e_email']);
							}
						?>
						<label>Poczta: <input type="radio" name="mail" value="mail" id="mail" required></label><br>
						<label>Paczkomat: <input type="radio" name="mail" value="paczkomat" id="paczkomat"></label><br>
						<label>Adres paczkomatu(wystarczy jego numer np."BPO03M")/adres do wysłania listem: <span id="hide"></span><input id="txt" type="text" name="adres" 
						value="
						<?php if(isset($_SESSION['fr_adres']))
						{
							echo $_SESSION['fr_adres'];
							unset($_SESSION['fr_adres']);
						}
						?>" placeholder="PACZKOMAT/ADRES" required></label><br> 
						<a href="https://inpost.pl/znajdz-paczkomat" target="_blank">Kliknij, aby znaleźć paczkomat.</a><br>
						<div class="g-recaptcha" data-sitekey="6LdDkqUZAAAAAAMCij2YATbpVrjlbZDyDIZgiNLC"></div>
						<?php 
							if (isset($_SESSION['e_bot']))
							{
								echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
								unset($_SESSION['e_bot']);
							}
						?>
						<input type="submit" value="Zamawiam" class="myButton" <?php if(!isset($_SESSION['bucket']) && !isset($_SESSION['personalise'])){echo " disabled='disabled' ";}?>>
						<?php if(!isset($_SESSION['bucket']) && !isset($_SESSION['personalise'])) {echo "<span style='color:red;'><br>Koszyk nie może być pusty!</span>";}?>
					</form>
				</article>
			</div>
			<div style="clear:both;"></div>
			<div>
			<span style="text-align:center;color:black;font-size:25px;"><h1>NOWOŚCI:</h1></span>
			<div class="proposed">
					<?php
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
								$mniejniz3=$polaczenie->query(sprintf("SELECT ID FROM products"));
								if(!$mniejniz3)throw new Exception($polaczenie->error);
								if($mniejniz3->num_rows>=3)
								{
									$ile=$polaczenie->query(sprintf("SELECT max(ID) as 'M' FROM products"));
									if(!$ile)throw new Exception($polaczenie->error);
									$ile=$ile->fetch_assoc();
									$ile_ID=$ile['M'];
									$i=1;
									$numbers = array();
									while($i<4)
									{
										$random=$polaczenie->query(sprintf("SELECT ID FROM products WHERE ID =(SELECT FLOOR(1 + (RAND() * $ile_ID))) AND available = 1 LIMIT 1"));
										$random_r = $random->fetch_assoc();
										if($random_r)
										{
											$losowe_id=$random_r['ID'];
											$jest=false;
											if(in_array($losowe_id,$numbers))
											{
												$jest = true;
											}
											if($jest==false)
											{
												array_push($numbers,$losowe_id);
												$i++;
											}
										}
									}
									for($i=1;$i<4;$i++)
									{
										$from_numbers=$numbers[$i-1];
										$proposed=$polaczenie->query(sprintf("SELECT ID,price,title,photo_1 FROM products WHERE ID=$from_numbers"));
										$proposed_r=$proposed->fetch_assoc();
										$price=$proposed_r['price'];
										$title=$proposed_r['title'];
										$photo=$proposed_r['photo_1'];
										$ID=$proposed_r['ID'];
										$image=$polaczenie->query(sprintf("SELECT * FROM images WHERE ID=$photo"));
										if(!$image) throw new Exception($polaczenie->error);
										$image_results=$image->fetch_assoc();
										$image_src=$image_results['folder'].'/'.$image_results['name'];
										$class="proposed_image_".$i;
										echo "<div class='$class'>
												<a href='product.php?ktory=$ID' style='text-decoration:none;color:black;'>
													<img src='$image_src' style='max-height:300px;'> <br>Tytuł: $title <br> Cena: $price zł
												</a>
											</div>";
									}
									echo "<div style='clear:both'></div>";
								}
								else
								{
									echo "<div style='width:100%;text-align:center;'>Na razie nie mamy nic w magazynie, ale możesz złożyć swoje spersonalizowane zamówienie :)</div>";
								}
								$polaczenie->close();
							}
						}
						catch(Exception $e)
						{
							echo "<span style='color:red'>Błąd w działaniu serwera spróbuj w innym terminie.</span>";
							echo "DEVELOP ".$e;
						}
					?>
			</div>
			</div>
		</main>
	</div>
	<div id="footer">
		<footer>
			<div style="float:left;"><?php echo date("Y");?> &copy; Wszystkie prawa zastrzeżone Michał Żuk </div>
			<div style="float:right;"><a style="color:white;background-color:#4267B2;margin-left:5px;" href="https://www.facebook.com/profile.php?id=100005484482717" target="_blank"><i class="demo-icon icon-facebook"></i></a>
			<a style="color:white;" href="mailto:michal.zuk30601@gmail.com"><i style="margin-right:5px;" class="demo-icon icon-mail"></i></a>
			<a href="https://www.instagram.com/michas.elo123/" style="color:black" target="_blank"><i style="background-color:white" class="demo-icon icon-instagram"></i></a></div>
			<div style="clear:both;"></div>
		</footer>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="js/jquery.tabSlideOut.js"></script>
	
	<div id="my-tab">
		<span class="handle" style="transform: rotate(0deg) translate(-100%,-100%);"><i class="demo-icon icon-facebook"></i></span>
		<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FM.Zuk.handmade&tabs=timeline&width=340&height=500&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="340" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
	</div>
	
	<div id="my-ins-tab">
		<span class="handle" style="color:black;background-color:white;transform: rotate(0deg) translate(-100%,-100%);"><i class="demo-icon icon-instagram"></i></span>
		<blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="https://www.instagram.com/p/CB0emylJ-9t/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="12" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);"><div style="padding:16px;"> <a href="https://www.instagram.com/p/CB0emylJ-9t/?utm_source=ig_embed&amp;utm_campaign=loading" style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;" target="_blank"> <div style=" display: flex; flex-direction: row; align-items: center;"> <div style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;"></div> <div style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;"> <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;"></div> <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;"></div></div></div><div style="padding: 19% 0;"></div> <div style="display:block; height:50px; margin:0 auto 12px; width:50px;"><svg width="50px" height="50px" viewBox="0 0 60 60" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-511.000000, -20.000000)" fill="#000000"><g><path d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631"></path></g></g></g></svg></div><div style="padding-top: 8px;"> <div style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;"> Wyświetl ten post na Instagramie.</div></div><div style="padding: 12.5% 0;"></div> <div style="display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;"><div> <div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);"></div> <div style="background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;"></div> <div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);"></div></div><div style="margin-left: 8px;"> <div style=" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;"></div> <div style=" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)"></div></div><div style="margin-left: auto;"> <div style=" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);"></div> <div style=" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);"></div> <div style=" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);"></div></div></div></a> <p style=" margin:8px 0 0 0; padding:0 4px;"> <a href="https://www.instagram.com/p/CB0emylJ-9t/?utm_source=ig_embed&amp;utm_campaign=loading" style=" color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;" target="_blank">Gipsówka to moja miłość a najlepiej z czerwonymi różami... taki klasyk❤ Dzięki mamo za pomoc ( i jej dokumentację fotograficzną😄) w moim rękodziele podczas mojej nieobecności jak i również obecności w pracowni Dobrze jest mieć życzliwych ludzi wokół siebie 💁‍♀️ A ja już niedługo wracam 😁</a></p> <p style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;">Post udostępniony przez <a href="https://www.instagram.com/zuk.w.zywicy/?utm_source=ig_embed&amp;utm_campaign=loading" style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px;" target="_blank"> zuk.w.zywicy</a> (@zuk.w.zywicy) <time style=" font-family:Arial,sans-serif; font-size:14px; line-height:17px;" datetime="2020-06-24T13:44:49+00:00">Cze 24, 2020 o 6:44 PDT</time></p></div></blockquote> <script async src="//www.instagram.com/embed.js"></script>
	</div>
	<script>$('#my-tab').tabSlideOut( {'tabLocation':'right','offset':'175px'} );</script>
	<script>$('#my-ins-tab').tabSlideOut( {'tabLocation':'right','offset':'235px'} );</script>
	<script src="js/resize.js"></script>
	<script>
var hide = document.getElementById('hide');
var txt = document.getElementById('txt');
txt.addEventListener("input", resize);
resize();

function resize() 
{
  hide.textContent = txt.value;
  txt.style.width = (hide.offsetWidth+10) + "px";
}
function rozumiem()
{
	document.getElementById("on").style.display = "none";
}
</script>

<script>

var value=parseFloat($('#do_zaplaty').text());

var value_poczta=value+=8.00;

var value_paczkomat=value+=12.00;

$('#mail').change(function()
{
	$('#do_zaplaty').html(value_poczta.toFixed(2));
});

$('#paczkomat').change(function()
{
	$('#do_zaplaty').html(value_paczkomat.toFixed(2));
});

</script>
</body>
</html>
