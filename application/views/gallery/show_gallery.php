<div id='main'>
<div class='content'>
<div class='title'>
<div class='span-12 heading'><h1><?=$gallery_info->name ?></h1></div>
<div class='span-12 last description'><p><?=$gallery_info->description?></p></div>
</div>
<?php
if ($type == 'photo'):
foreach ($image_data as $pic): ?>
<div class='photo_container'>
<a href="<?=$pic->file_link?>" title="<?=$pic->title?>" rel='lightbox[gallery]'>
<img src='<?=$pic->file_thumb_link?>' alt='<?=$pic->title?>'>
</a>
</div>
<?
endforeach;
elseif ($type == 'video'):
foreach ($video_data as $video): ?>
<div class='video_container'>
<a rel='shadowbox[videos];title="<?=$video['title']?>"' href='http://player.vimeo.com/video/<?=$video['video_id']?>'><img src='<?=$video['file_link']?>'></a>
</div>
<?
endforeach;
endif;
?>
</div>
</div>