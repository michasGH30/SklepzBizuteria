<?php
session_start();
	
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: panel_logowania.php');
		exit();
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

<!--<link href="https://fonts.googleapis.com/css2?family=Fondamento&display=swap" rel="stylesheet">-->
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet">
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
			<main>
				<article style="text-align:center;background-color:#e0e0e0;margin-top:100px;">
				<span style="text-align:center; line-height:60px;">
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
			$select=$polaczenie->query(sprintf("SELECT MAX(ID) AS max_id FROM products"));
			if(!$select)
			{
				throw new Exception($polaczenie->error);
			}
			else
			{
				$how_much=$select->fetch_assoc();
				$how_much_id=$how_much['max_id'];
				for($i=1;$i<=$how_much_id;$i++)
				{
					$products=$polaczenie->query(sprintf("SELECT * FROM products WHERE ID=$i"));
					if(!$products)
					{
						throw new Exception($polaczenie->error);
					}
					else
					{
						$products_ile=$products->num_rows;
						if($products_ile > 0)
						{
							$products_results = $products->fetch_assoc();
							
							$photo_1=$products_results['photo_1'];
							$image_1=$polaczenie->query(sprintf("SELECT * FROM images WHERE ID=$photo_1"));
							if(!$image_1){throw new Exception($polaczenie->error);}
							$image_results_1=$image_1->fetch_assoc();
							$image_src_1=$image_results_1['folder'].'/'.$image_results_1['name'];
							
							$photo_2=$products_results['photo_2'];
							$image_2=$polaczenie->query(sprintf("SELECT * FROM images WHERE ID=$photo_2"));
							if(!$image_2){throw new Exception($polaczenie->error);}
							$image_results_2=$image_2->fetch_assoc();
							$image_src_2=$image_results_2['folder'].'/'.$image_results_2['name'];
							
							$photo_3=$products_results['photo_3'];
							$image_3=$polaczenie->query(sprintf("SELECT * FROM images WHERE ID=$photo_3"));
							if(!$image_3){throw new Exception($polaczenie->error);}
							$image_results_3=$image_3->fetch_assoc();
							$image_src_3=$image_results_3['folder'].'/'.$image_results_3['name'];
							echo "<span style=";
							if($products_results['available'] == 1)
							{
								echo "'color:green'>Dostępny ";
							}
							else 
							{
								echo "'color:red'>Niedostępny ";
							}
							$products_title=$products_results['title'];
							$products_price=$products_results['price'];
							$products_number=$products_results['number'];
							echo "</span>Tytuł: $products_title Cena: $products_price zł Ilość:$products_number <br>";
							echo "<img src=$image_src_1 />
							<img src=$image_src_2 />
							<img src=$image_src_3 /><br>
							<form action='dostepny.php?ktory=$i' method='POST'>
							<input type='submit' value='Zmiana' class='myButton'>
							</form>
							<form action='usuniety.php?ktory=$i' method='POST' id='del'>
							<input type='submit' value='Usuń' onclick='usun(event)' class='myButton'>
							</form>";
							echo "<script>
							function usun(e)
							{
								var confirmation = confirm('Czy na pewno chcesz usunąć?');

								if (!confirmation)
								{
									e.preventDefault() ;
									returnToPreviousPage();
								}

								return confirmation ;
							}
							</script>";
						}
					}
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
	
	
	
?>
				</span>
				</article>
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
</body>
</html>