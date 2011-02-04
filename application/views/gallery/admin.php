<div id='main'>
<div class='content'>
<h1 id='admin_title'>Pick a gallery to edit:</h1>
<? if ($galleries) { ?>
	<ul id='galleries_list'>
	<? foreach ($galleries as $gallery) { ?>
		<li class='gallery' id='gallery_<?=$gallery['id']?>'><a href='/gallery/gallery/edit/<?=$gallery['id'] ?>'><?=$gallery['name']?></li>
	<? } ?>
	</ul>
<? } else { ?>
	<p id='no_galleries'>There are no galleries to display</p>
<? } ?>
</div>
</div>