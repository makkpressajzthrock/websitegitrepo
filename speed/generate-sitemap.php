<?php
include "sitemap-generator.php";
$config = include("sitemap-config.php");
$smg = new SitemapGenerator($config);
// Run the generator
$smg->GenerateSitemap();
?>