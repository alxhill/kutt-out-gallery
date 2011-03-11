<div id='main'>
<div class='content'>

<h1 id='admin'>Admin control panel</h1>
<h2>Upload, update and delete images from this panel</h2>
<p id='user'>Logged in as user: <span id='username'><?=$user?></span></p>
<div id='action'></div>

<div id='edit_upload'>
<div class='upload'>
	<? if ($g_info['type'] == 1): ?>
	<div class='photo_upload'>
	<h2 id='upload_title'>Upload an image</h2>
	<?=form_open_multipart('gallery/upload'); ?>
		<div class="titleform">
			<label>Title:</label><br>
			<input type="text" name="title" id="title">
		</div>
		<div class="uploadform">
			<label>Select photo:</label><br>
			<input type="file" name="photo" id="photo" /><br>
		</div>
		<input type='hidden' name='g_id' value='<?=$g_info['id']?>' />
		<input type='hidden' name='type' values='photo' />
		<input type="submit" name="submit" id="submit" value="Upload" />
	</form>
	</div>
<? elseif ($g_info['type'] ==  2): ?>
<div class='video_upload'>
	<?= form_open_multipart('gallery/upload'); ?>
		<div class='titleform'>
			<label>Vimeo URL:</label>
			<input type='text' name='url' id='title' />
		</div>
		<div class="uploadform">
			<label>Select thumbnail:</label><br>
			<input type="file" name="photo" id="photo" /><br>
		</div>
		<div id='descriptionform'>
			<label>Video Description:</label>
			<textarea cols='2' id='video_description' name='description'></textarea>
		</div>
		<input type='hidden' name='g_id' value='<?=$g_info['id']?>' />
		<input type='hidden' name='type' value='video' />
		<input type="submit" name="submit" id="submit" value="Add Video" />
	</form>
</div>
<? endif; ?>
</div>

<div id='edit_gallery'>
<h2 id='edit_title'>Edit gallery</h2>
<?=form_open('gallery/update_gallery')?>
<label>Title:</label>
<input type='text' id='g_name' name='g_name' value='<?=$g_info['name']?>'/><br>
<label>Description:</label>
<textarea name='g_description' class='g_desc_area'><?=$g_info['description']?></textarea>
<input type='hidden' name='g_id' value='<?=$g_info['id']?>' />
<input type='submit' value='Update' />
</form>
</div>
</div>

<div class='table'>
<? if ($g_info['type'] == 1) { ?>
<h2 id='edit_title'>Edit and delete images</h2>
<table class='photos'>
	<tr>
		<th>Thumbnail</th>
		<th>Title</th>
		<th>Modify</th>
	</tr>
<?php foreach (array_reverse($image_data) as $pic) { ?>
	<tr id='pic_id_<?=$pic['id']?>'>
		<td><img src='<?=$pic['file_thumb_link']?>' alt='<?=$pic['title']?>' title='<?=$pic['title']?>' class='admin_thumb'></td>
		<td class='image_title editable' id='title_<?=$pic['id']?>'><?=$pic['title']?></td>
		<td class='edit_delete'><a class='edit_link' id='<?=$pic['id']?>' href='javascript:void(0)'>Edit</a>/<a class='delete_link' id='<?=$pic['id']?>' href='javascript: void(0)'>Delete</a></td>
	</tr>
<?php } ?>
</table>
<? } else if ($g_info['type'] == 2) { ?>
<h2 id='edit_title'>Edit and delete videos</h2>
<div>
	<table>
		<tr>
			<th>Thumbnail</th>
			<th>Title</th>
			<th>Description</th>
			<th>Modify</th>
		</tr>
	</table>
</div>
<? } ?>
</div>
</div>