</div>
<div id='content'>
<div id='admin_page'>
<div id='action'></div>
<h2 id='admin_title'>Gallery manager</h2>
<h3>Pick a gallery to edit</h3>
<? if ($galleries): ?>
	<ul id='galleries_list'>
	<? foreach ($galleries as $gallery): ?>
		<li class='gallery' id='gallery_<?=$gallery->id?>'>
			<h3><a href='<?=$gallery->name ?>/edit'><?=$gallery->name?></a></h3>
			<div class='gallery_links'>
				<? if ($gallery->visible == 1): ?><a href='<?=$gallery->name?>/show'>View</a> | <? endif; ?> 
				<a href='<?=$gallery->name?>/edit'>Edit</a> | 
				<? if ($gallery->visible == 0): ?><a href='gallery/show_hide/show/<?=$gallery->id?>'>Show</a><? elseif ($gallery->visible == 1): ?><a href='gallery/show_hide/hide/<?=$gallery->id?>'>Hide</a><? endif; ?> | 
				<a class='g_delete_link' href='javascript: void(0);' id='<?=$gallery->id?>' >Delete</a>
			</div>
		</li>
	<? endforeach; ?>
	</ul>
<? else: ?>
	<p id='no_galleries'>There are no galleries to display</p>
<? endif; ?>
<br>
<h2>Create a new gallery</h2>
<div id='new_gallery'>
	<?= form_open('gallery/new_gallery') ?>
		<label>Title:</label><br>
		<input type='text' title='title' name='title'><br>
		<label>Type:</label><br>
		<select name='type'>
			<option value='1'>Photo</option>
			<option value='2'>Video</option>
		</select><br><br>
		<label>Description:</label><br>
		<textarea title='description' name='description' class='g_desc_area'></textarea><br>
		<input type="submit" name="submit" id="submit" value="Create">
	</form>
</div>
</div>
</div>