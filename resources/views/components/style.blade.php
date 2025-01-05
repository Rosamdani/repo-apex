<?php

$primary_color_50 = setting('color.primary-color-50') ?? '#e4f1ff';
$primary_color_100 = setting('color.primary-color-100') ?? '#bfdcff';
$primary_color_200 = setting('color.primary-color-200') ?? '#95c7ff';
$primary_color_300 = setting('color.primary-color-300') ?? '#6bb1ff';
$primary_color_400 = setting('color.primary-color-400') ?? '#519fff';
$primary_color_500 = setting('color.primary-color-500') ?? '#458eff';
$primary_color_600 = setting('color.primary-color-600') ?? '#487fff';
$primary_color_700 = setting('color.primary-color-700') ?? '#486cea';
$primary_color_800 = setting('color.primary-color-800') ?? '#4759d6';
$primary_color_900 = setting('color.primary-color-900') ?? '#4536b6';
?>
<style>
    :root {
        --primary-50: <?php echo $primary_color_50; ?>;
        --primary-100: <?php echo $primary_color_100; ?>;
        --primary-200: <?php echo $primary_color_200; ?>;
        --primary-300: <?php echo $primary_color_300; ?>;
        --primary-400: <?php echo $primary_color_400; ?>;
        --primary-500: <?php echo $primary_color_500; ?>;
        --primary-600: <?php echo $primary_color_600; ?>;
        --primary-700: <?php echo $primary_color_700; ?>;
        --primary-800: <?php echo $primary_color_800; ?>;
        --primary-900: <?php echo $primary_color_900; ?>;
    }
</style>
