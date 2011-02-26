<div id='nav'>
<div id='mainbg'>
	<ul>
		<li><a href='<?=site_url('/home')?>'><div id='kutt'>Kutt</div><div id='out'>Out</div><div id='studios'>Studios</div></a></li>
		<? foreach ($galleries as $gallery):?>
		<li><a class='nav_link' href='/gallery/<?=$gallery['name']?>/show' id='g_<?=$gallery['id']?>'><?=$gallery['name']?></a></li>
		<? endforeach; ?>
		<div id='nav_bottom'>
		<li id='norm'><a href='#'>Contact</a></li>
		<li><a href='#'><div id='billy'>Billy</div><div id='boyd'>Boyd</div><div id='cape'>Cape</div></a></li>
		</div>
	</ul>
</div>
</div>