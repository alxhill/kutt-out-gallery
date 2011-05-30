</div>
<div id='content'>
	<?php if ($type == 'photo'): ?>
		<div class='gallery'>
			<?php foreach ($image_data as $pic): ?>
				<div class='photo_container'>
					<a href='<?=$pic->file_link?>' title='<?=$pic->title?>' rel='lightbox[gallery]'>
						<img src='<?=$pic->file_thumb_link?>' alt='<?=$pic->title?>' class='gallery_img'>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	<?php elseif ($type == 'video'): ?>
		<div class='gallery'>
			<?php foreach ($video_data as $vid): ?>
				<div class='video_container'>
					<a href='http://player.vimeo.com/video/<?=$vid->video_id?>' title='<?=$vid->title?>'>
						<img src='<?=$vid->file_thumb_link?>' alt='<?=$vid->title?>' class='gallery_img'>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
		<div id="video_view">
			<iframe src="" width="400" height="500" frameborder="0"></iframe>
		</div>
	<?php endif; ?>
</div>