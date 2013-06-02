<?php
// /_includes/layout/header-login.inc.php
// 
// DomainMOD - A web-based application written in PHP & MySQL used to manage a collection of domain names.
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
<a name="top"></a>
<div class="main-container-login">

    <div class="header-container">
        <div class="header-center">
            <a href="<?php if ($web_root != "") echo $web_root; ?>/"><img border="0" src="<?php if ($web_root != "") echo $web_root; ?>/images/logo.png"></a>
        </div>
    </div>

    <div class="main-outer-login">
        <div class="main-inner">
            <BR><?php 
                include($full_server_path . "/_includes/layout/table-maintenance.inc.php"); 
            ?>
            <?php 
            if ($_SESSION['result_message'] != "") {
                include($full_server_path . "/_includes/layout/table-result-message.inc.php"); 
                unset($_SESSION['result_message']);
            }
            ?>
