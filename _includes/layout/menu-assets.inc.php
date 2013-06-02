<?php
// /_includes/layout/menu-assets.inc.php
// 
// DomainMOD is an open source application written in PHP & MySQL used to track and manage your web resources.
// Copyright (C) 2010 Greg Chetcuti
// 
// DomainMOD is free software; you can redistribute it and/or modify it under the terms of the GNU General
// Public License as published by the Free Software Foundation; either version 2 of the License, or (at your
// option) any later version.
// 
// DomainMOD is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
// implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
// for more details.
// 
// You should have received a copy of the GNU General Public License along with DomainMOD. If not, please see
// http://www.gnu.org/licenses/
?>
[ <a href="<?php if ($web_root != "") echo $web_root; ?>/registrars.php">Registrars</a> ]&nbsp;
[ <a href="<?php if ($web_root != "") echo $web_root; ?>/registrar-accounts.php">Accounts</a> ]&nbsp;
[ <a href="<?php if ($web_root != "") echo $web_root; ?>/dns.php">DNS</a> ]&nbsp;
[ <a href="<?php if ($web_root != "") echo $web_root; ?>/ip-addresses.php">IPs</a> ]&nbsp;
[ <a href="<?php if ($web_root != "") echo $web_root; ?>/hosting.php">Hosting</a> ]&nbsp;
[ <a href="<?php if ($web_root != "") echo $web_root; ?>/categories.php">Categories</a> ]&nbsp;
[ <a href="<?php if ($web_root != "") echo $web_root; ?>/account-owners.php">Owners</a> ]&nbsp;
<BR><BR>
[ <a href="<?php if ($web_root != "") echo $web_root; ?>/ssl-providers.php">SSL Providers</a> ]&nbsp;
[ <a href="<?php if ($web_root != "") echo $web_root; ?>/ssl-accounts.php">SSL Accounts</a> ]&nbsp;
[ <a href="<?php if ($web_root != "") echo $web_root; ?>/ssl-types.php">SSL Cert Types</a> ]&nbsp;
<?php include($full_server_path . "/_includes/layout/menu-sub.inc.php"); ?>
