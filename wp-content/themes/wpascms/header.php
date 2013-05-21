<?php
/**
 * @package WordPress
 * @subpackage Wpascms_Theme
 */

define('URL', get_bloginfo('url'));
define('TEMPLATE_DIRECTORY', get_bloginfo('template_directory'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

</head>
<body>
<div id="container">
    <div id="header">
        <h1><a href="<?php echo URL; ?>">Wordpress as CMS tutorial</a></h1>
    </div>

    <div id="navigation">
        <a href="<?php echo URL; ?>">Home</a>
        <a href="<?php echo URL; ?>/portfolio/">Portfolio</a>
        <a href="<?php echo URL; ?>/blog/">Blog</a>
    </div>

    <div id="header_image">
        <img src="<?php echo TEMPLATE_DIRECTORY; ?>/img/header_image.jpg" alt="header image" title="Don't know why, but designers fancy big images in the headers :)" />
    </div>
