<section class="wrapper<?= !empty($classes) ? ' ' . implode(' ', $classes) : '';?>">
<? if( !empty($anchor_name)) :?>
    <a name="<?= $anchor_name ; ?>" class="anchor"></a>
<? endif; ?>
<? if(!empty($partials)) {
    foreach($partials as $partial) {
        partial($partial['name'], $partial['parts']);
    }
} ?>
</section>