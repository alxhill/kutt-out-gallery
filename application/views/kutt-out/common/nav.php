<nav>
  <? foreach($galleries as $title => $elements): ?>
  <h2><?=$title?></h2>
  <ul>
    <? foreach($elements as $gallery): ?>
    <li><a class="nav_link" href="/gallery/<?=$gallery->name?>/show" id="g_<?=$gallery->id?>"><?=$gallery->name?></a></li>
    <? endforeach; ?>
  </ul>
  <? endforeach; ?>
</nav>
<div id="content">