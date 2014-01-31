<?php
/*
Plugin Name: Post Indexer
Plugin URI: http://premium.wpmudev.org/project/post-indexer/
Description: Indexes all posts across your network and brings them into one spot – a very powerful tool that you use as a base to display posts in different ways or to manage your network.
Author: Barry (Incsub)
Version: 3.0.5.1
Author URI: http://premium.wpmudev.org
WDP ID: 30
Network: true
*/

/*
Copyright Incsub (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

require_once('includes/config.php');
require_once('includes/functions.php');

// Include the database model we will be using across classes
require_once('classes/class.model.php');

// Include the network query class for other plugins to use
require_once('classes/networkquery.php');

// Include the rebuild cron class
require_once('classes/cron.postindexerrebuild.php');

if(is_admin()) {
	include_once('external/wpmudev-dash-notification.php');

	require_once('classes/class.postindexeradmin.php');
}