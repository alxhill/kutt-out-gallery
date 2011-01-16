<div class="span-16 last content">
<div class='title'>
<div class='span-12 heading'><h1>Portraits</h1></div>
<div class='span-12 last description'><p>A selection of either studio, live music or documentary photographs of the human form.</p></div>
</div>
<?php
foreach ($image_data as $pic) { ?>
<div class='photo_container'>
<a href="<?=$pic["file"]?>" title="<?=$pic['title']?>" rel='lightbox[gallery]'>
<img src='<?=$pic["file_thumb"]?>' alt='<?=$pic['title']?>'>
</a>
</div>
<? } ?>
</div>