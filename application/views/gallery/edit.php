<div id='main'>
<div class='content'>
<h1 id='admin'>Admin control panel</h1>
<h2>Upload, update and delete images from this panel</h2>
<p id='user'>Logged in as user: <span id='username'><?=$user?></span></p>
<div id='action'></div>
<h2 id='upload_title'>Upload an image</h2>
<?=form_open_multipart('gallery/upload'); ?>
<div id="titleform">
<label>Title:</label><br>
<input type="text" name="title" id="title">
</div>
<div id="uploadform">
<label>Select photo:</label><br>
<input type="file" name="photo" id="photo"><br>
</div>
<input type='hidden' name='g_id' value='<?=$g_id?>'>
<input type="submit" name="submit" id="submit" value="Upload">
</form>
<h2 id='edit_title'>Edit and delete images</h2>
<div>
<table class='photos'>
	<tr>
		<th>Thumbnail</th>
		<th>Title</th>
		<th>Modify</th>
	</tr>
<?php foreach (array_reverse($image_data) as $pic) { ?>
	<tr id='pic_id_<?=$pic['id']?>'>
		<td><img src='<?=$pic['file_thumb_link']?>' alt='<?=$pic['title']?>' title='<?=$pic['title']?>' class-'admin_thumb'></td>
		<td class='image_title editable' id='title_<?=$pic['id']?>'><?=$pic['title']?></td>
		<td class='edit_delete'><a class='edit_link' id='<?=$pic['id']?>' href='javascript:void(0)'>Edit</a>/<a class='delete_link' id='<?=$pic['id']?>' href='javascript: void(0)'>Delete</a><a class='revert_link' id='revert_<?=$pic['id']?>' href='javascript:void(0)' >Revert</a></td>
	</tr>
<?php } ?>
</table>
</div>
</div>
</div>