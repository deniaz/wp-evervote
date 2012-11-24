<?php
/*
 * Plugin Name: EverVote
 * Plugin URI: http://projects.cheekyowl.com/evervote
 * Description: Voting for Posts
 * Version: 0.1
 * Author: Robert Vogt
 * Author URI: http://www.cheekyowl.com
 * License: GPLv3
 *
 * 
 * Copyright 2012 Robert Vogt (email: robert@cheekyowl.com)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <http://www.gnu.org/licenses/>.
 */

include_once('classes/evervote.php');

$everVote = new EverVote(plugin_dir_path(__FILE__), plugin_dir_url(__FILE__));
