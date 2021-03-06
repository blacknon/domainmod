<?php
/**
 * /segments/add.php
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
<?php
include("../_includes/start-session.inc.php");
include("../_includes/init.inc.php");

require_once(DIR_ROOT . "classes/Autoloader.php");
spl_autoload_register('DomainMOD\Autoloader::classAutoloader');

$error = new DomainMOD\Error();
$maint = new DomainMOD\Maintenance();
$system = new DomainMOD\System();
$form = new DomainMOD\Form();
$time = new DomainMOD\Time();
$timestamp = $time->stamp();

include(DIR_INC . "head.inc.php");
include(DIR_INC . "config.inc.php");
include(DIR_INC . "software.inc.php");
include(DIR_INC . "settings/segments-add.inc.php");
include(DIR_INC . "database.inc.php");

$system->authCheck($web_root);
$system->readOnlyCheck($_SERVER['HTTP_REFERER']);

$new_name = $_POST['new_name'];
$new_description = $_POST['new_description'];
$raw_domain_list = $_POST['raw_domain_list'];
$new_notes = $_POST['new_notes'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $format = new DomainMOD\Format();
    $domain_list = $format->cleanAndSplitDomains($raw_domain_list);

    if ($new_name != "" && $raw_domain_list != "") {

        $domain = new DomainMOD\Domain();

        list($invalid_to_display, $invalid_domains, $invalid_count, $temp_result_message) =
            $domain->findInvalidDomains($domain_list);

        if ($raw_domain_list == "" || $invalid_domains == 1) {

            if ($invalid_domains == 1) {

                if ($invalid_count == 1) {

                    $_SESSION['s_message_danger'] .= "There is " . number_format($invalid_count) . " invalid domain
                        on your list<BR><BR>" . $temp_result_message;

                } else {

                    $_SESSION['s_message_danger'] .= "There are " . number_format($invalid_count) . " invalid
                        domains on your list<BR><BR>" . $temp_result_message;

                    if (($invalid_count - $invalid_to_display) == 1) {

                        $_SESSION['s_message_danger'] .= "<BR>Plus " .
                            number_format($invalid_count - $invalid_to_display) . " other<BR>";

                    } elseif (($invalid_count - $invalid_to_display) > 1) {

                        $_SESSION['s_message_danger'] .= "<BR>Plus " .
                            number_format($invalid_count - $invalid_to_display) . " others<BR>";
                    }

                }

            }
            $submission_failed = 1;

        } else {

            $number_of_domains = count($domain_list);

            $domain = new DomainMOD\Domain();

            while (list($key, $new_domain) = each($domain_list)) {

                if (!$domain->checkFormat($new_domain)) {
                    echo "invalid domain $key";
                    exit;
                }

            }

            $new_data_formatted = $format->formatForMysql($domain_list);

            $query = "INSERT INTO segments
                      (`name`, description, segment, number_of_domains, notes, created_by, insert_time)
                      VALUES
                      (?, ?, ?, ?, ?, ?, ?)";
            $q = $conn->stmt_init();

            if ($q->prepare($query)) {

                $q->bind_param('sssisis', $new_name, $new_description, $new_data_formatted, $number_of_domains,
                    $new_notes, $_SESSION['s_user_id'], $timestamp);
                $q->execute();
                $q->close();

            } else {
                $error->outputSqlError($conn, "ERROR");
            }

            $query = "SELECT id
                      FROM segments
                      WHERE `name` = ?
                        AND segment = ?
                        AND insert_time = ?";
            $q = $conn->stmt_init();

            if ($q->prepare($query)) {

                $q->bind_param('sss', $new_name, $new_data_formatted, $timestamp);
                $q->execute();
                $q->store_result();
                $q->bind_result($temp_segment_id);
                $q->fetch();
                $q->close();

            } else {
                $error->outputSqlError($conn, "ERROR");
            }

            $query = "DELETE FROM segment_data
                      WHERE segment_id = ?";
            $q = $conn->stmt_init();

            if ($q->prepare($query)) {

                $q->bind_param('i', $temp_segment_id);
                $q->execute();
                $q->close();

            } else {
                $error->outputSqlError($conn, "ERROR");
            }

            foreach ($domain_list as $domain) {

                $query = "INSERT INTO segment_data
                          (segment_id, domain, insert_time)
                          VALUES
                          (?, ?, ?)";
                $q = $conn->stmt_init();

                if ($q->prepare($query)) {

                    $q->bind_param('iss', $temp_segment_id, $domain, $timestamp);
                    $q->execute();
                    $q->close();

                } else {
                    $error->outputSqlError($conn, "ERROR");
                }

            }

            $_SESSION['s_message_success'] .= "Segment " . $new_name . " Added<BR>";

            $maint->updateSegments($connection);

            header("Location: ../segments/");
            exit;

        }

    } else {

        if ($new_name == "") {
            $_SESSION['s_message_danger'] .= "Enter the segment name<BR>";
        }
        if ($raw_domain_list == "") {
            $_SESSION['s_message_danger'] .= "Enter the segment<BR>";
        }

    }

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
<?php
echo $form->showFormTop('');
echo $form->showInputText('new_name', 'Segment Name (35)', '', $new_name, '35', '', '1', '', '');
echo $form->showInputTextarea('raw_domain_list', 'Segment Domains (one per line)', '', $raw_domain_list, '1', '', '');
echo $form->showInputTextarea('new_description', 'Description', '', $new_description, '', '', '');
echo $form->showInputTextarea('new_notes', 'Notes', '', $new_notes, '', '', '');
echo $form->showSubmitButton('Add Segment', '', '');
echo $form->showFormBottom('');
?>
<?php include(DIR_INC . "layout/footer.inc.php"); ?>
</body>
</html>
