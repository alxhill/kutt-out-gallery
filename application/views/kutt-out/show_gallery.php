<?php
$class = $type == 'photo' ? 'photo' : 'video';
?>

<div class="gallery <?=$class?>">
  <? foreach($image_data as $pic): ?>
  <div class="photo-container">
    <a href="<?=$pic->file_link?>" title="<?=$pic->title?>" rel='lightbox[gallery]'>
      <img src="<?=$pic->file_thumb_link?>" alt="<?=$pic->title?>"/>
    </a>
  </div>
  <? endforeach; ?>
</div>