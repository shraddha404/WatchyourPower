	<h3>Header</h3>
	<ul class="nav">
		<?php foreach($menus[$main_menu]['menu'] as $menu){  ?>
		<li <?php if($menu['is_last']){ echo 'class="last"'; } ?>><a href="<?php echo $menu['url']; ?>"><?php echo $menu['text']; ?></a></li>
		<?php } ?>
	</ul>
<!--
	<a href="#" class="link">Link here</a> 
	<a href="#" class="link">Link here</a> 
-->

