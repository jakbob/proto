<?php

require_once('./simplepie/autoloader.php');
 
// We'll process this feed with all of the default options.
$mainfeed = new SimplePie();
$mainfeed->set_feed_url("http://api.sr.se/api/rss/program/83");
$mainfeed->enable_cache(false);
$mainfeed->init();

$sidefeed = new SimplePie();
$sidefeed->set_feed_url("http://www.svd.se/?service=rss&type=senastenytt");
$sidefeed->enable_cache(false);
$sidefeed->init();
 
// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
$mainfeed->handle_content_type();
?><!DOCTYPE html>
<html>
<head>
	<title>News test</title>

	<link rel="stylesheet" type="text/css" href="bower_components/pure/pure-min.css" />
	<link rel="stylesheet" type="text/css" href="bower_components/pure/grids-responsive-min.css" />
	<link rel="stylesheet" type="text/css" href="bower_components/Navigataur/navigataur.css" />
	<link rel="stylesheet" type="text/css" href="bower_components/font-awesome/css/font-awesome.min.css" />

	<link rel="stylesheet" type="text/css" href="css/news.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width">
</head>
<body>
	<header>
		<div class="container">
			<!--<input type="search" placeholder="Sök" class="pull-right" />-->
			<input type="checkbox" id="toggle" />
			<nav class="pure-menu pure-menu-open pure-menu-horizontal">
				<h1>
					<a href="#">Nyheter</a>
				</h1>
			    <label for="toggle" class="toggle" data-open="Meny" data-close="Stäng meny" onclick></label>
			    <ul class="menu">
					<li><a href="javascript: void(0);">Inrikes</a></li>
					<li><a href="javascript: void(0);">Utrikes</a></li>
					<li><a href="javascript: void(0);">Sport</a></li>
					<li><a href="javascript: void(0);">Annat</a></li>
				</ul>
			</nav>
		</div>
	</header>
 
 	<div class="pure-g container">
 		<div id="main-column" class="pure-u-1 pure-u-md-2-3">
		<?php
		/*
		Here, we'll loop through all of the items in the feed, and $item represents the current item in the loop.
		*/
		$count = 0;
		foreach ($mainfeed->get_items() as $item):
			if ($count == 6) break;
		?>
	 
			<div class="item">
				<h2><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h2>
				<p><small>Posted on <?php echo $item->get_date('j F Y | H:i'); ?></small></p>

				<img src="http://lorempixel.com/1000/040/?=<?= rand(); ?>" class="pure-img" />
				<p><?php 
					$max_words = 51;
					$words = explode(" ", $item->get_content(), $max_words);

					if(count($words) == $max_words){
					  $words[$max_words-1] = '... <a href="javascript: void;">Läs mer >></a>';
					}

					echo implode(" ", $words);
				?></p>
			</div>
	 
		<?php $count++; endforeach; ?>
	</div>
	<div id="side-column" class="pure-u-1 pure-u-md-1-3">
		<h3>Senaste nytt</h3>
		<ul class="latest">
			<?php
			$count = 0;
			foreach ($sidefeed->get_items() as $item):
				if ($count == 20) break;
			?>
		 
				<li class="latest-news">
					<div>
						<span><?php echo $item->get_date('H:i'); ?></span>
						<a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a>
					</div>
					<p><?php echo $item->get_description(); ?></p>
				</li>
		 
			<?php $count++; endforeach; ?>
		</ul>	
	</div>
</body>
</html>