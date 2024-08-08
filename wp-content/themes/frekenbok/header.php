<!DOCTYPE html>
<html lang="<?php echo \PS::$current_language; ?>">
<head>
    <meta charset="UTF-8">

	<title><?php $wp_title = wp_title('', false); echo $wp_title; ?></title>
    <meta name="description" content='<?php $context = PS::get_context(); echo $context['seo_description']; ?>'>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo PS::$assets_url; ?>images/favicon.png" />
					
    <meta property="og:title" content="<?php echo $wp_title; ?>"/>					
    <meta property="og:description" content="<?php echo $context['seo_description']; ?>"/>					
    <meta property="og:type" content="website" />
	<?php $img = get_field('img_1_1', \PS::$pages['front']); if(is_array($img) && count($img)): ?>
        <meta property="og:image" content="<?php echo $img['sizes']['960x0']; ?>" />
    <?php endif; ?>

	<?php /* DON'T REMOVE THIS */ ?>
	<?php wp_head(); ?>
	<?php /* END */ ?>
</head>
<body>