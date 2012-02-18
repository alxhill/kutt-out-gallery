<nav class="grid_2">
  <? foreach($galleries as $title => $elements): ?>
  <h2><?=$title?></h2>
  <ul>
    <? foreach($elements as $gallery): ?>
    <li><a href="/gallery/<?=$gallery->name?>/show" id="g_<?=$gallery->id?>"><?=$gallery->name?></a></li>
    <? endforeach; ?>
  </ul>
  <? endforeach; ?>
</nav>
<div id="content" class="grid_7">