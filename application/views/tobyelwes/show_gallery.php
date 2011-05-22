</div>
<div id='content'>
	<div class='gallery'>
		<?php foreach ($image_data as $pic): ?>
			<div class='photo_container'>
				<a href='<?=$pic->file_link?>' title='<?=$pic->title?>' rel='lightbox[gallery]'>
					<img src='<?=$pic->file_thumb_link?>' alt='<?=$pic->title?>' class='gallery_img'>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
</div>