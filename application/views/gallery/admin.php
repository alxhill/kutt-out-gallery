<div class='span-16 last'>
<div class='content'>
<h1>Admin control panel</h1>
<h2>Upload, update and delete images from this panel</h2>
<p id='user'>Logged in as user: <span id='username'><?=$user?></span></p>
<div id='action'></div>
<table class='photos'>
	<tr>
		<th>Image ID</th>
		<th>Thumbnail</th>
		<th>Title</th>
		<th>Edit/Delete?</th>
	</tr>
<?php foreach ($image_data as $pic) { ?>
	<tr id='pic_id_<?=$pic['id']?>'>
		<td class='image_id'><?=$pic['id']?></td>
		<td><img src='<?=$pic['file_thumb']?>' alt='<?=$pic['title']?>' title='<?=$pic['title']?>'></td>
		<td class='image_title'><?=$pic['title']?></td>
		<td class='edit_delete'><a class='edit_link' id='<?=$pic['id']?>' href='javascript: void(0)'>Edit</a>/<a class='delete_link' id='<?=$pic['id']?>' href='javascript: void(0)'>Delete</a></td>
	</tr>
<?php } ?>
</table>
</div>
</div>