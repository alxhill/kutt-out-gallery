<nav>
	<ul>
		<li><a href='/gallery/home'>Home</a></li>
		<? foreach ($galleries as $gallery):?>
		<li><a class='nav_link' href='/gallery/<?=$gallery['name']?>/show' id='g_<?=$gallery['id']?>'><?=$gallery['name']?></a></li>
		<? endforeach; ?>
	</ul>
</nav>