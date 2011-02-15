<div id='main'>
<div class='content'>
<h1 id='admin_title'>Gallery manager</h1>
<h2>Pick a gallery to edit</h2>
<? if ($galleries) { ?>
	<ul id='galleries_list'>
	<? foreach ($galleries as $gallery) { ?>
		<li class='gallery' id='gallery_<?=$gallery['id']?>'>
			<a href='<?=$gallery['name'] ?>/edit'><h3><?=$gallery['name']?></h3></a>
			<div class='gallery_links'><a href='<?=$gallery['name']?>/show'>View</a> | <a href='<?=$gallery['name']?>/edit'>Edit</a></div>
		</li>
		
	<? } ?>
	</ul>
<? } else { ?>
	<p id='no_galleries'>There are no galleries to display</p>
<? } ?>
<br>
<h2>Create a new gallery</h2>
<div id='new_gallery'>
	<?= form_open('gallery/new_gallery') ?>
		<label>Title</label><br>
		<input type='text' title='title' name='title'><br>
		<label>Description</label><br>
		<input type='text' title='description' name='description'><br>
		<input type="submit" name="submit" id="submit" value="Create">
	</form>
</div>
</div>
</div>