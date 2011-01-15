<?php
foreach ($image_data as $pic) { ?>
<div class='photo_container'>
<a href="<?=$pic["file"]?>" title="<?=$pic['title']?>" rel='lightbox'>
<img src='<?=$pic["file_thumb"]?>' alt='<?=$pic['title']?>'>
</a>
<p><?=$pic['title'] ?></p>
</div>
<? } ?>