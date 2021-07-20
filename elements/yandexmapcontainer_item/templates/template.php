<?php
$element;
?>

<?php if(!empty($props['image'])): ?>
<img class="map-location-switcher-image" src="<?= $props['image'] ?>">
<?php endif; ?>
<div class="map-location-switcher-list">
	<?= $props['content'] ?>
</div>
<?php if(!empty($props['button_link'])): ?>
<a href="<?= $props['button_link'] ?>" class="uk-button uk-button-default uk-width-1-1" target="_blank"><?= $props['button_label'] ?></a>
<?php endif; ?>