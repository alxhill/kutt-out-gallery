<div id='nav'>
<div id='mainbg'>
	<ul>
		<? foreach ($galleries as $gallery):?>
		<li><a class='nav_link' href='/gallery/<?=$gallery['name']?>/show' id='g_<?=$gallery['id']?>'><?=$gallery['name']?></a></li>
		<? endforeach; ?>
		<div id='nav_bottom'>
		<li id='norm'><a href='/gallery/contact'>Contact</a></li>
		<li><a href='/gallery/about_me'><div id='billy'>Billy</div><div id='boyd'>Boyd</div><div id='cape'>Cape</div></a></li>
		</div>
	</ul>
</div>
</div>