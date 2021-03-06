<?php
/**
 * /assets/dns.php
 *
 * This file is part of DomainMOD, an open source domain and internet asset manager.
 * Copyright (c) 2010-2016 Greg Chetcuti <greg@chetcuti.com>
 *
 * Project: http://domainmod.org   Author: http://chetcuti.com
 *
 * DomainMOD is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * DomainMOD is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with DomainMOD. If not, see
 * http://www.gnu.org/licenses/.
 *
 */
?>
<?php //@formatter:off
include("../_includes/start-session.inc.php");
include("../_includes/init.inc.php");

require_once(DIR_ROOT . "classes/Autoloader.php");
spl_autoload_register('DomainMOD\Autoloader::classAutoloader');

$system = new DomainMOD\System();
$error = new DomainMOD\Error();
$layout = new DomainMOD\Layout();
$time = new DomainMOD\Time();

include(DIR_INC . "head.inc.php");
include(DIR_INC . "config.inc.php");
include(DIR_INC . "software.inc.php");
include(DIR_INC . "settings/assets-dns.inc.php");
include(DIR_INC . "database.inc.php");

$system->authCheck($web_root);

$export_data = $_GET['export_data'];

$sql = "SELECT id, `name`, number_of_servers, dns1, dns2, dns3, dns4, dns5, dns6, dns7, dns8, dns9, dns10, ip1, ip2, ip3,
            ip4, ip5, ip6, ip7, ip8, ip9, ip10, notes, creation_type_id, created_by, insert_time, update_time
        FROM dns
        ORDER BY `name` ASC, number_of_servers DESC";

if ($export_data == '1') {

    $result = mysqli_query($connection, $sql) or $error->outputOldSqlError($connection);

    $export = new DomainMOD\Export();
    $export_file = $export->openFile('dns_profile_list', strtotime($time->stamp()));

    $row_contents = array($page_title);
    $export->writeRow($export_file, $row_contents);

    $export->writeBlankRow($export_file);

    $row_contents = array(
        'Status',
        'DNS Profile',
        'DNS Servers',
        'Domains',
        'Default DNS Profile?',
        'DNS Server 1',
        'IP Address 1',
        'DNS Server 2',
        'IP Address 2',
        'DNS Server 3',
        'IP Address 3',
        'DNS Server 4',
        'IP Address 4',
        'DNS Server 5',
        'IP Address 5',
        'DNS Server 6',
        'IP Address 6',
        'DNS Server 7',
        'IP Address 7',
        'DNS Server 8',
        'IP Address 8',
        'DNS Server 9',
        'IP Address 9',
        'DNS Server 10',
        'IP Address 10',
        'Notes',
        'Creation Type',
        'Created By',
        'Inserted',
        'Updated'
    );
    $export->writeRow($export_file, $row_contents);

    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_object($result)) {

            $sql_total_count = "SELECT count(*) AS total_count
                                FROM domains
                                WHERE dns_id = '" . $row->id . "'
                                  AND active NOT IN ('0', '10')";
            $result_total_count = mysqli_query($connection, $sql_total_count);

            while ($row_total_count = mysqli_fetch_object($result_total_count)) {
                $total_domains = $row_total_count->total_count;
            }

            if ($row->id == $_SESSION['s_default_dns']) {

                $is_default = '1';

            } else {

                $is_default = '0';

            }

            if ($total_domains >= 1) {

                $status = 'Active';

            } else {

                $status = 'Inactive';

            }

            $creation_type = $system->getCreationType($connection, $row->creation_type_id);
            
            if ($row->created_by == '0') {
                $created_by = 'Unknown';
            } else {
                $user = new DomainMOD\User();
                $created_by = $user->getFullName($connection, $row->created_by);
            }

            $row_contents = array(
                $status,
                $row->name,
                number_format($row->number_of_servers),
                number_format($total_domains),
                $is_default,
                $row->dns1,
                $row->ip1,
                $row->dns2,
                $row->ip2,
                $row->dns3,
                $row->ip3,
                $row->dns4,
                $row->ip4,
                $row->dns5,
                $row->ip5,
                $row->dns6,
                $row->ip6,
                $row->dns7,
                $row->ip7,
                $row->dns8,
                $row->ip8,
                $row->dns9,
                $row->ip9,
                $row->dns10,
                $row->ip10,
                $row->notes,
                $creation_type,
                $created_by,
                $time->toUserTimezone($row->insert_time),
                $time->toUserTimezone($row->update_time)
            );
            $export->writeRow($export_file, $row_contents);

        }

    }
    $export->closeFile($export_file);

}
?>
<?php include(DIR_INC . 'doctype.inc.php'); ?>
<html>
<head>
    <title><?php echo $system->pageTitle($software_title, $page_title); ?></title>
    <?php include(DIR_INC . "layout/head-tags.inc.php"); ?>
</head>
<body class="hold-transition skin-red sidebar-mini">
<?php include(DIR_INC . "layout/header.inc.php"); ?>
Below is a list of all the DNS Profiles that are stored in <?php echo $software_title; ?>.<BR><BR>
<a href="add/dns.php"><?php echo $layout->showButton('button', 'Add DNS Profile'); ?></a>&nbsp;&nbsp;&nbsp;
<a href="dns.php?export_data=1"><?php echo $layout->showButton('button', 'Export'); ?></a><BR><BR><?php

$result = mysqli_query($connection, $sql) or $error->outputOldSqlError($connection);

if (mysqli_num_rows($result) > 0) { ?>

    <table id="<?php echo $slug; ?>" class="<?php echo $datatable_class; ?>">
        <thead>
        <tr>
            <th width="20px"></th>
            <th>Name</th>
            <th>Servers</th>
            <th>Domains</th>
        </tr>
        </thead>
        <tbody><?php

        while ($row = mysqli_fetch_object($result)) {

            $sql_total_count = "SELECT count(*) AS total_count
                                FROM domains
                                WHERE dns_id = '" . $row->id . "'
                                  AND active NOT IN ('0', '10')";
            $result_total_count = mysqli_query($connection, $sql_total_count);

            while ($row_total_count = mysqli_fetch_object($result_total_count)) {
                $total_domains = $row_total_count->total_count;
            }

            if ($total_domains >= 1 || $_SESSION['s_display_inactive_assets'] == '1') { ?>

                <tr>
                <td></td>
                <td>
                    <a href="edit/dns.php?dnsid=<?php echo $row->id; ?>"><?php echo $row->name; ?></a><?php if ($_SESSION['s_default_dns'] == $row->id) echo '<strong>*</strong>'; ?>
                </td>
                <td>
                    <a href="edit/dns.php?dnsid=<?php echo $row->id; ?>"><?php echo $row->number_of_servers; ?></a>
                </td>
                <td><?php

                    if ($total_domains >= 1) { ?>

                        <a href="../domains/index.php?dnsid=<?php echo $row->id; ?>"><?php echo number_format($total_domains); ?></a><?php

                    } else {

                        echo '-';

                    } ?>

                </td>
                </tr><?php

            }

        } ?>

        </tbody>
    </table>

    <strong>*</strong> = Default (<a href="../settings/defaults/">set defaults</a>)<BR><BR><?php

} else { ?>

    <BR>You don't currently have any DNS Profiles. <a href="add/dns.php">Click here to add one</a>.<?php

} ?>
<?php include(DIR_INC . "layout/asset-footer.inc.php"); ?>
<?php include(DIR_INC . "layout/footer.inc.php"); //@formatter:on ?>
</body>
</html>
