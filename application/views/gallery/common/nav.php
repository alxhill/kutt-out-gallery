<div id='nav'>
<div id='mainbg'>
	<ul>
		<li><a href='<?=site_url('/home')?>'><div id='kutt'>Kutt</div><div id='out'>Out</div><div id='studios'>Studios</div></a></li>
		<? foreach ($galleries as $gallery):?>
		<li><a href='/gallery/<?=$gallery?>/show'><?=$gallery?></a>
		<? endforeach; ?>
		<!--<li><a href='/gallery/portraits/show'>Portraits</a></li>
		<li><a href='/gallery/landscapes/show'>Landscapes</a></li>
		<li id="middle"><a href='/gallery/digital/show'>Digital</a></li>
		<li><a href='#'>Videos</a></li>-->
		<li><a href='#'>Contact</a></li>
		<li><a href='#'><div id='billy'>Billy</div><div id='boyd'>Boyd</div><div id='cape'>Cape</div></a></li>
	</ul>
</div>
</div>