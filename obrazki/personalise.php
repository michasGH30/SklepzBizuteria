<!DOCTYPE HTML>
<?php
	require_once "connect.php";
	session_start();
	$tlo = array("naszyjnik" => tlo("naszyjnik"),"pierścionek" => tlo("pierścionek"),"kolczyki" => tlo("kolczyki"),"broszka" => tlo("broszka"),"bransoletka" => tlo("bransoletka"));
	$kwiaty = array("naszyjnik" => kwiaty("naszyjnik"),"pierścionek" => kwiaty("pierścionek"),"kolczyki" => kwiaty("kolczyki"),"broszka" => kwiaty("broszka"),"bransoletka"=>kwiaty("bransoletka"));
	function tlo($word)
	{
		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			$j = new mysqli("localhost","root","","zywica");

			if($j->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{	
				$tlo = array();
				$jewellery_price=$j->query(sprintf("SELECT $word FROM personalise_price WHERE ID=1"));
				if(!$jewellery_price){throw new Exception($j->error);}
				$jewellery_price=$jewellery_price->fetch_assoc();
				$jp=$jewellery_price[$word];
				array_push($tlo,$jp);
				$jewellery=$j->query(sprintf("SELECT what_exactly FROM personalise WHERE jewellery='$word' AND what_type='tło'"));
				if(!$jewellery){throw new Exception($j->error);}
				$num = $jewellery->num_rows;
				for($i=0;$i<$num;$i++)
				{
					$row=$jewellery->fetch_assoc();
					$type=$row['what_exactly'];
					$name=substr($type,0,strpos($type,'.'));
					$name=ucfirst($name);
					$r=array($type,$name);
					array_push($tlo,$r);
				}
				$j->close();
				return $tlo;
			}
		}
		catch(Exception $e)
		{
			echo "<span style='color:red'>Błąd w działaniu serwera spróbuj w innym terminie.</span>";
			echo "DEVELOP ".$e;
		}
	}
	function kwiaty($word)
	{
		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			$j = new mysqli("localhost","root","","zywica");
			
			if($j->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$kwiaty = array();
				for($k=0;$k<5;$k++)
				{
					$number=$k+1;
					$query="SELECT what_exactly FROM personalise WHERE jewellery='$word' AND what_type='kwiaty' AND available=1 AND number >='$number'";
					
					$jewellery=$j->query(sprintf($query));
					if(!$jewellery){throw new Exception($j->error);}
					$num = $jewellery->num_rows;
					$kwiaty_po_numerach=array();
					for($i=0;$i<$num;$i++)
					{
						$row=$jewellery->fetch_assoc();
						$type=$row['what_exactly'];
						$name=substr($type,0,strpos($type,'.'));
						$name=ucfirst($name);
						$r=array($type,$name);
						array_push($kwiaty_po_numerach,$r);
					}
					array_push($kwiaty,$kwiaty_po_numerach);
				}
				$j->close();
				return $kwiaty;
			}
		}
		catch(Exception $e)
		{
			echo "<span style='color:red'>Błąd w działaniu serwera spróbuj w innym terminie.</span>";
			echo "DEVELOP ".$e;
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
<link rel="stylesheet" href="css/jquery.tabSlideOut.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/fontello.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<style>
.no-close .ui-dialog-titlebar-close 
{
  display: none;
}
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
	<div id="container">
		<div id="content" style="text-align:left;">
			<main>
				<header><span style="text-align:center;color:black;font-size:25px;"><h1>Personalizacja</h1></span></header>
				<article>
					<div style="width:40%;float:left;padding-left:20px;" id="formularz">
						Tutaj możesz skompletować swoje indywidualne zamówienie.<br>
						<form method="POST">
						<div style="float:left;">
						<select name="jewellery" id="jewellery">
						<option>Wybierz rodzaj biżuterii</option>
						<option value="naszyjnik">Naszyjnik</option>
						<option value="bransoletka">Bransoletka</option>
						<option value="kolczyki">Kolczyki</option>
						<option value="broszka">Broszka</option>
						<option value="pierścionek">Pierścionek</option>
						</select>
						</div>
						<div id="price" style="float:left;margin-left:5px;"></div>
						<input type="hidden" name="price" id="form_price">
						<div style="clear:both;"></div>
						<span id="background_f" style="display:none;">
						<select name="background" id="background">
						</select>
						</span>
						<span id="flowers_f_0" style="display:none;">
						KWIATY:<br>
						<select name="flowers[0]" id="flowers_0">
						</select>
						</span>
						<span id="flowers_f_1" style="display:none;">
						<select name="flowers[1]" id="flowers_1">
						</select>
						</span>
						<span id="flowers_f_2" style="display:none;">
						<select name="flowers[2]" id="flowers_2">
						</select>
						</span>
						<span id="flowers_f_3" style="display:none;">
						<select name="flowers[3]" id="flowers_3">
						</select>
						</span>
						<span id="flowers_f_4" style="display:none;">
						<select name="flowers[4]" id="flowers_4">
						</select>
						</span>
						<span id="plus" style="display:none;">
						Dodaj kwiat: <button id="plus_button" type="button" class="myButton">+</button>
						</span>
						<span id="minus" style="display:none;">
						Usuń kwiat: <button id="minus_button" type="button" class="myButton">-</button>
						</span>
						<span id="chain" style="display:none">
							<?php 
							try
								{
									$polaczenie = new mysqli($host,$db_user,$db_password,$db_name);

									if($polaczenie->connect_errno!=0)
									{
										throw new Exception(mysqli_connect_errno());
									}
									else
									{
										$chains=$polaczenie->query(sprintf("SELECT * FROM chains"));
										$chains=$chains->fetch_assoc();
										$longs=array($chains['long_1'],$chains['long_2'],$chains['long_3'],$chains['long_4'],$chains['long_5'],$chains['long_6'],$chains['long_7'],$chains['long_8'],$chains['long_9'],$chains['long_10']);
								
										$color=$polaczenie->query(sprintf("SELECT * FROM colors"));
										$color=$color->fetch_assoc();
										$colors=array($color['color_1'],$color['color_2'],$color['color_3'],$color['color_4'],$color['color_5'],$color['color_6'],$color['color_7'],$color['color_8'],$color['color_9'],$color['color_10']);
										$polaczenie->close();
										echo "Łańcuszek: <input type='checkbox' value='Yes' name='chain' id='chains_ch'>
										<br>Długości:
										<select name='long' id='long' disabled='disabled'>
										<option value='0'>Wybierz długość</option>";
										for($i=0;$i<10;$i++)
										{
											if($longs[$i]!="0.00")
											{
												echo "<option value='$longs[$i]'>$longs[$i] cm</option>";
											}
										}
										echo "</select> <br>Kolor: <select name='color' id='color' disabled='disabled'>
										<option value='none'>Wybierz kolor</option>";
										for($i=0;$i<10;$i++)
										{
											if($colors[$i]!="none")
											{
												echo "<option value='$colors[$i]'>$colors[$i]</option>";
											}
										}
										echo "</select>
										<br></select>";
									}
								}
								catch(Exception $e)
								{
									echo "<span style='color:red'>Błąd w działaniu serwera spróbuj w innym terminie.</span>";
									echo "DEVELOP ".$e;
								}
							?>
						</span>
						<span id="earrings" style="display:none;">
						<?php 
						try
						{
							$polaczenie = new mysqli($host,$db_user,$db_password,$db_name);

							if($polaczenie->connect_errno!=0)
							{
								throw new Exception(mysqli_connect_errno());
							}
							else
							{
								$diameters=$polaczenie->query(sprintf("SELECT * FROM diameters"));
								$diameters=$diameters->fetch_assoc();
								$long=array($diameters['diameter_1'],$diameters['diameter_2'],$diameters['diameter_3'],$diameters['diameter_4'],$diameters['diameter_5'],$diameters['diameter_6'],$diameters['diameter_7'],$diameters['diameter_8'],$diameters['diameter_9'],$diameters['diameter_10']);
								$polaczenie->close();
								echo "Średnice:
								<select name='diameter' id='diameter' disabled='disabled'>
								<option value='0'>Wybierz średnicę</option>";
								for($i=0;$i<10;$i++)
								{
									if($long[$i]!="0")
									{
										echo "<option value='$long[$i]'>$long[$i] mm</option>";
									}
								}
								echo "</select><br>";
							}
						}
						catch(Exception $e)
						{
							echo "<span style='color:red'>Błąd w działaniu serwera spróbuj w innym terminie.</span>";
							echo "DEVELOP ".$e;
						}

						?>
						</span>
						<span id="sub" style="display:none;">
						<input type='submit' value='Dodaj do koszyka' name='add_to_bucket'>
						<input type='submit' value='Zamów' name='buy' >
						</span>
						</form>
					</div>
					<div style="float:left;text-align:center;min-width:750px;" id="your_order">
						<div><img style="width:150px;" id="image_back"></div>
						<img style="width:150px;" id="b0">
					</div>
					<div style="clear:both;"></div>
				</article>
				
				<article><span style="text-align:center;color:black;font-size:25px;"><header><h1>Proponowane oferty:</h1></header></span>
					<div class="proposed">
						<?php
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
				</article>
			</main>
		</div>
	</div>
	<div id="footer" style="margin-top:100px;">
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
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	
	<div id="my-tab">
		<span class="handle" style="transform: rotate(0deg) translate(-100%,-100%);"><i class="demo-icon icon-facebook"></i></span>
		<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FM.Zuk.handmade&tabs=timeline&width=340&height=500&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="340" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
	</div>
	
	<div id="my-ins-tab">
		<span class="handle" style="color:black;background-color:white;transform: rotate(0deg) translate(-100%,-100%);"><i class="demo-icon icon-instagram"></i></span>
		<blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="https://www.instagram.com/p/CB0emylJ-9t/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="12" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);"><div style="padding:16px;"> <a href="https://www.instagram.com/p/CB0emylJ-9t/?utm_source=ig_embed&amp;utm_campaign=loading" style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;" target="_blank"> <div style=" display: flex; flex-direction: row; align-items: center;"> <div style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;"></div> <div style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;"> <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;"></div> <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;"></div></div></div><div style="padding: 19% 0;"></div> <div style="display:block; height:50px; margin:0 auto 12px; width:50px;"><svg width="50px" height="50px" viewBox="0 0 60 60" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-511.000000, -20.000000)" fill="#000000"><g><path d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631"></path></g></g></g></svg></div><div style="padding-top: 8px;"> <div style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;"> Wyświetl ten post na Instagramie.</div></div><div style="padding: 12.5% 0;"></div> <div style="display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;"><div> <div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);"></div> <div style="background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;"></div> <div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);"></div></div><div style="margin-left: 8px;"> <div style=" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;"></div> <div style=" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)"></div></div><div style="margin-left: auto;"> <div style=" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);"></div> <div style=" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);"></div> <div style=" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);"></div></div></div></a> <p style=" margin:8px 0 0 0; padding:0 4px;"> <a href="https://www.instagram.com/p/CB0emylJ-9t/?utm_source=ig_embed&amp;utm_campaign=loading" style=" color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;" target="_blank">Gipsówka to moja miłość a najlepiej z czerwonymi różami... taki klasyk❤ Dzięki mamo za pomoc ( i jej dokumentację fotograficzną😄) w moim rękodziele podczas mojej nieobecności jak i również obecności w pracowni Dobrze jest mieć życzliwych ludzi wokół siebie 💁‍♀️ A ja już niedługo wracam 😁</a></p> <p style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;">Post udostępniony przez <a href="https://www.instagram.com/zuk.w.zywicy/?utm_source=ig_embed&amp;utm_campaign=loading" style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px;" target="_blank"> zuk.w.zywicy</a> (@zuk.w.zywicy) <time style=" font-family:Arial,sans-serif; font-size:14px; line-height:17px;" datetime="2020-06-24T13:44:49+00:00">Cze 24, 2020 o 6:44 PDT</time></p></div></blockquote> <script async src="//www.instagram.com/embed.js"></script>
	</div>
	<div id="dialog">Dodałeś product do koszyka. Czy chcesz przejść do koszyka czy pozostać na stronie?</div>
	
	<script src="js/resize.js"></script>
	<script>$('#my-tab').tabSlideOut( {'tabLocation':'right','offset':'175px'} );</script>
	<script>$('#my-ins-tab').tabSlideOut( {'tabLocation':'right','offset':'235px'} );</script>
	<script>
		$( "#dialog" ).dialog({
			autoOpen: false,
			 dialogClass: "no-close",
			 draggable: false,
			 resizable: false,
			 title: "Koszyk",
			 modal: true,
			buttons: [
			{
				text: "Przechodzę",
				click: function()
				{
					window.location.replace("bucket.php");
					$(this).dialog("close");
				}
			},
			{
				text: "Zostaję",
				click: function()
				{
					$(this).dialog("close");
				}
			}
			]
			});
			
		$('#chains_ch').change(function()
		{
			if(!this.checked)
			{
				$('#color').attr('disabled','disabled');
				$('#long').attr('disabled','disabled');
			}
			else
			{
				$('#color').removeAttr('disabled');
				$('#long').removeAttr('disabled');
			}
		});

		var ile=1;

		$('#jewellery').change(function()
		{
			var jewellery = $('#jewellery').val();
			
			var tlo=<?php echo json_encode($tlo); ?>;
			
			var kwiaty = <?php echo json_encode($kwiaty); ?>;
			
			$('#background').empty();
			$('#background').append
			(
				$('<option>',
					{
						text: "Wybierz kształt"
					}
				)
			);
			
			for(let i=0;i<5;i++)
			{
				let f="#flowers_"+i;
				$(f).empty();
				$(f).append(
					$('<option>',
					{
						value: "none",
						text: "Wybierz kwiat"
					}
					)
				);
			}
			
			$('#sub').css('display','block');
			$('#plus').css('display','block');
			$('#minus').css('display','block');
			
			if(jewellery=="kolczyki")
			{
				$('#chain').css('display','none');
				$('#earrings').css('display','none');
				
				$('#long').val('0');
				$('#diameter').val('0');
				$('#chains_ch').prop("checked",false);
				
				var r = tlo.kolczyki;
				$('#background_f').css("display","block");
				$('#price').html("Cena: "+r[0]+" zł");
				$('#form_price').val(r[0]);
				
				for(i=1;i<r.length;i++)
				{
					$('#background').append(
						$('<option>',
						{
							value:r[i][0],
							text: r[i][1]
						}
						)
					);
				}
				
				var r2 = kwiaty.kolczyki;
				
				for(let i=0;i<5;i++)
				{
					for(let j=0;j<r2[i].length;j++)
					{
						$('#flowers_'+i).append(
							$('<option>',
							{
								value:r2[i][j][0],
								text: r2[i][j][1]
							}
							)
						);
					}
				}
				
				$('#flowers_f_0').css("display","block");
			}
			else if(jewellery=="naszyjnik")
			{
				$('#earrings').css('display','none');
				
				$('#diameter').val('0');
				
				var r = tlo.naszyjnik;
				$('#background_f').css("display","block");
				
				$('#price').html("Cena: "+r[0]+" zł");
				$('#form_price').val(r[0]);
				
				for(i=1;i<r.length;i++)
				{
					$('#background').append(
						$('<option>',
						{
							value:r[i][0],
							text: r[i][1]
						}
						)
					);
				}
				
				var r2 = kwiaty.naszyjnik;
				
				for(let i=0;i<5;i++)
				{
					for(let j=0;j<r2[i].length;j++)
					{
						$('#flowers_'+i).append(
							$('<option>',
							{
								value:r2[i][j][0],
								text: r2[i][j][1]
							}
							)
						);
					}
				}
				
				$('#flowers_f_0').css("display","block");
				$('#chain').css('display','block');
				
			}
			else if(jewellery=="broszka")
			{
				$('#chain').css('display','none');
				$('#earrings').css('display','none');
				
				$('#long').val('0');
				$('#diameter').val('0');
				$('#chains_ch').prop("checked",false);
				
				var r = tlo.broszka;
				$('#background_f').css("display","block");
				
				$('#price').html("Cena: "+r[0]+" zł");
				$('#form_price').val(r[0]);
				
				for(i=1;i<r.length;i++)
				{
					$('#background').append(
						$('<option>',
						{
							value:r[i][0],
							text: r[i][1]
						}
						)
					);
				}
				
				var r2 = kwiaty.broszka;
				
				for(let i=0;i<5;i++)
				{
					for(let j=0;j<r2[i].length;j++)
					{
						$('#flowers_'+i).append(
							$('<option>',
							{
								value:r2[i][j][0],
								text: r2[i][j][1]
							}
							)
						);
					}
				}
				
				$('#flowers_f_0').css("display","block");
			}
			else if(jewellery=="pierścionek")
			{
				$('#chain').css('display','none');
				$('#long').val('0');
				
				$('#diameter').prop("disabled",false);
				$('#chains_ch').prop("checked",false);

				var r = tlo.pierścionek;
				$('#background_f').css("display","block");
				
				$('#price').html("Cena: "+r[0]+" zł");
				$('#form_price').val(r[0]);
				
				for(i=1;i<r.length;i++)
				{
					$('#background').append(
						$('<option>',
						{
							value:r[i][0],
							text: r[i][1]
						}
						)
					);
				}
				
				var r2 = kwiaty.pierścionek;
				for(let i=0;i<5;i++)
				{
					for(let j=0;j<r2[i].length;j++)
					{
						$('#flowers_'+i).append(
							$('<option>',
							{
								value:r2[i][j][0],
								text: r2[i][j][1]
							}
							)
						);
					}
				}
				$('#flowers_f_0').css("display","block");
				$('#earrings').css('display','block');
			}
			else if(jewellery=="bransoletka")
			{
				$('#earrings').css('display','none');
				
				$('#diameter').val('0');
				
				var r = tlo.bransoletka;
				$('#background_f').css("display","block");
				
				$('#price').html("Cena: "+r[0]+" zł");
				$('#form_price').val(r[0]);
				
				for(i=1;i<r.length;i++)
				{
					$('#background').append(
						$('<option>',
						{
							value:r[i][0],
							text: r[i][1]
						}
						)
					);
				}
				
				var r2 = kwiaty.bransoletka;
				for(let i=0;i<5;i++)
				{
					for(let j=0;j<r2[i].length;j++)
					{
						$('#flowers_'+i).append(
							$('<option>',
							{
								value:r2[i][j][0],
								text: r2[i][j][1]
							}
							)
						);
					}
				}
				
				$('#flowers_f_0').css("display","block");
				$('#chain').css('display','block');
			}
		});

		$('#background').change(function()
		{
			var back = $('#background').val();
			var jewellery = $('#jewellery').val();
			$('#image_back').attr('src',"personalise/"+jewellery+"/tło/"+back);
		});

		$('#flowers_0').change(function()
		{
			var flow = $('#flowers_0').val();
			var jewellery = $('#jewellery').val();
			$('#b0').attr('src',"personalise/"+jewellery+"/kwiaty/"+flow);
		});
		$('#flowers_1').change(function()
		{
			var flow = $('#flowers_1').val();
			var jewellery = $('#jewellery').val();
			$('#b1').attr('src',"personalise/"+jewellery+"/kwiaty/"+flow);
		});
		$('#flowers_2').change(function()
		{
			var flow = $('#flowers_2').val();
			var jewellery = $('#jewellery').val();
			$('#b2').attr('src',"personalise/"+jewellery+"/kwiaty/"+flow);
		});
		$('#flowers_3').change(function()
		{
			var flow = $('#flowers_3').val();
			var jewellery = $('#jewellery').val();
			$('#b3').attr('src',"personalise/"+jewellery+"/kwiaty/"+flow);
		});
		$('#flowers_4').change(function()
		{
			var flow = $('#flowers_4').val();
			var jewellery = $('#jewellery').val();
			$('#b4').attr('src',"personalise/"+jewellery+"/kwiaty/"+flow);
		});

		$('#plus_button').click(function()
		{
			let del="#flowers_"+ile+" option";
			if(ile<4 && $(del).length>1)
			{
				ile++;
				let img_num=ile-1;
				$('#your_order').append("<img id=b"+img_num+"> ");
				for(let i=0;i<ile;i++)
				{
					let f='#flowers_f_'+i;
					$(f).css("display","block");
					let img = '#b'+i;
					//let width=700/ile;
					//$(img).css('width',width);
					$(img).css('width',150);
				}
			}
			else
			{
				
			}
		}
		);
		$('#minus_button').click(function()
		{
			if(ile>1)
			{	
				ile--;
				let f='#flowers_f_'+ile;
				$(f).css("display","none");
				for(let i=0;i<5;i++)
				{
					let f_to_check="#flowers_f_"+i;
					if($(f).css('display')=='none')
					{
						let del ='#flowers_'+ile;
						$(del).val("none");
					}
				}
				
				let img ="#b"+ile;
				$(img).remove();
				for(let i =0;i<5;i++)
				{
					if(i<=ile)
					{
						let img2="#b"+i;
						let width=700/ile;
						if(width==700)
						{
							width=350;
						}
						$(img2).css('width',150);
					}
				}
			}
		}
		);
	</script>
	</body>
	
<?php 
if(isset($_POST['add_to_bucket']))
{
	if(isset($_POST['long']))
	{
		$posted_long=$_POST['long'];
	}
	else
	{
		$posted_long=0;
	}
	
	if(isset($_POST['color']))
	{
		$posted_color=$_POST['color'];
	}
	else
	{
		$posted_color='none';
	}
	
	if(isset($_POST['diameter']))
	{
		$posted_diameter=$_POST['diameter'];
	}
	else
	{
		$posted_diameter=0;
	}
	$posted_jewellery=$_POST['jewellery'];
	$posted_background=$_POST['background'];
	$posted_price=$_POST['price'];
	$posted_flowers=array($_POST['flowers'][0],$_POST['flowers'][1],$_POST['flowers'][2],$_POST['flowers'][3],$_POST['flowers'][4]);
	add($posted_jewellery,$posted_background,$posted_flowers,$posted_price,$posted_long,$posted_color,$posted_diameter,0);
}
else if(isset($_POST['buy']))
{
	if(isset($_POST['long']))
	{
		$posted_long=$_POST['long'];
	}
	else
	{
		$posted_long=0;
	}
	
	if(isset($_POST['color']))
	{
		$posted_color=$_POST['color'];
	}
	else
	{
		$posted_color='none';
	}
	
	if(isset($_POST['diameter']))
	{
		$posted_diameter=$_POST['diameter'];
	}
	else
	{
		$posted_diameter=0;
	}
	$posted_jewellery=$_POST['jewellery'];
	$posted_background=$_POST['background'];
	$posted_price=$_POST['price'];
	$posted_flowers=array($_POST['flowers'][0],$_POST['flowers'][1],$_POST['flowers'][2],$_POST['flowers'][3],$_POST['flowers'][4]);
	add($posted_jewellery,$posted_background,$posted_flowers,$posted_price,$posted_long,$posted_color,$posted_diameter,1);
}
function add($j,$b,$f,$p,$l,$c,$d,$stay)
{
	if(!isset($_SESSION['personalise']))
	{
		$_SESSION['personalise']=array(array("jewellery"=>"$j","background"=>"$b","flowers_1"=>$f[0],"flowers_2"=>$f[1],"flowers_3"=>$f[2],"flowers_4"=>$f[3],"flowers_5"=>$f[4],"price"=>"$p","color"=>"$c","long"=>"$l","diameter"=>"$d"));
	}
	else
	{
		$a=array("jewellery"=>"$j","background"=>"$b","flowers_1"=>$f[0],"flowers_2"=>$f[1],"flowers_3"=>$f[2],"flowers_4"=>$f[3],"flowers_5"=>$f[4],"price"=>"$p","color"=>"$c","long"=>"$l","diameter"=>"$d");
		array_push($_SESSION['personalise'],$a);
	}
	if ($stay==0)
	{
		echo "<script>$('#dialog').dialog( 'open' );</script>";	
		
	}
	if($stay==1)
	{
		echo"<script>window.location.replace('bucket.php');</script>";
	}
}
?>
</html>