<div id='main'>
<div class='content'>
<div class='title'>
<div class='span-12 heading'><h1><?=$gallery_info['name'] ?></h1></div>
<div class='span-12 last description'><p><?=$gallery_info['description']?></p></div>
</div>
<?php
foreach ($image_data as $pic) { ?>
<div class='photo_container'>
<a href="<?=$pic['file_link']?>" title="<?=$pic['title']?>" rel='lightbox[gallery]'>
<img src='<?=$pic["file_thumb_link"]?>' alt='<?=$pic['title']?>'>
</a>
</div>
<? } ?>
</div>
</div>