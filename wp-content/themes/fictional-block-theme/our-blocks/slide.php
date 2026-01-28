<?php
if(!isset($attributes['imgUrl'])) {
    $attributes['imgUrl'] = get_theme_file_uri('/images/library-hero.jpg');
}
?>

<div class="hero-slider__slide" style="background-image: url('<?php echo $attributes['imgUrl']; ?>')">
    <div class="hero-slider__interior container">
        <div class="hero-slider__overlay t-center">
            <?php echo $content; ?>
        </div>
    </div>
</div>
<!-- <div class="page-banner__content container t-center c-white">
    <div class="hero-slider__overlay">
        <?php echo $content; ?>
    </div>
</div> -->
