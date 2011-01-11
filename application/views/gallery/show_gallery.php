<?= isset($message) ? show_messages($class,$message) : null; ?>
<?php foreach ($image_data as $pic) { ?>
<img src='<?=$pic["file"]?>' title="<?=$pic['title'] ?>">
<?php } ?>