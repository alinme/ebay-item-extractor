<?php
require_once(plugin_dir_path(__FILE__) . '/vendor/autoload.php');
/*
    Plugin Name:  eBay Item Extractor
    Plugin URI:  https://github.com/alinme/ebay-item-extractor
    Description: This is my first attempt on creating a Wordpress plugin. Putting my php skills to use to create an <b>eBay item extractor</b> plugin that extracts the title, description, images and more from an ebay item url link.
    Version: 1.0.0
    Author: Alin M.
    Author URI: https://github.com/alinme
    License: GPLv2 or later
    Text Domain: ebay-item-extractor
*/

/*
eBay Item Extractor is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

eBay Item Extractor is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with eBay Item Extractor. If not, see http://www.gnu.org/licenses/gpl.html.
*/




$eix = new EbayItemExtractor\Extractor();
