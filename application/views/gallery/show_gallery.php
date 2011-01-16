<div class="span-16 last">
<?php
foreach ($image_data as $pic) { ?>
<div class='photo_container'>
<a href="<?=$pic["file"]?>" title="<?=$pic['title']?>" rel='lightbox[gallery]'>
<img src='<?=$pic["file_thumb"]?>' alt='<?=$pic['title']?>'>
</a>
</div>
<? } ?>
</div>