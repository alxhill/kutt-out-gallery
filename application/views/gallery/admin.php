<div class='span-16 last'>
<div class='content'>
<h1>Admin control panel</h1>
<h2>Upload, update and delete images from this panel</h2>
<p id='user'>Logged in as user: <span id='username'><?=$user?></span></p>
<span id='action'></span>
<table class='photos'>
	<tr>
		<th>Image ID</th>
		<th>Thumbnail</th>
		<th>Title</th>
		<th>Edit/Delete?</th>
	</tr>
<?php foreach (array_reverse($image_data) as $pic) { ?>
	<tr id='pic_id_<?=$pic['id']?>'>
		<td class='image_id'><?=$pic['id']?></td>
		<td><img src='<?=$pic['file_thumb']?>' alt='<?=$pic['title']?>' title='<?=$pic['title']?>'></td>
		<td class='image_title editable' id='title_<?=$pic['id']?>'><?=$pic['title']?></td>
		<td class='edit_delete'><a class='edit_link' id='<?=$pic['id']?>' href='javascript:void(0)'>Edit</a>/<a class='delete_link' id='<?=$pic['id']?>' href='javascript: void(0)'>Delete</a><a class='revert_link' id='revert_<?=$pic['id']?>' href='javascript:void(0)' >Revert</a></td>
	</tr>
<?php } ?>
</table>
</div>
</div>