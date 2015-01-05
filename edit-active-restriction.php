<?php
/** 
 * Postfix Admin 
 * 
 * LICENSE 
 * This source file is subject to the GPL license that is bundled with  
 * this package in the file LICENSE.TXT. 
 * 
 * Further details on the project are available at : 
 *     http://www.postfixadmin.com or http://postfixadmin.sf.net 
 * 
 * @version $Id$ 
 * @license GNU GPL v2 or later. 
 * 
 * File: edit-active-restriction.php 
 * Responsible for toggling the status of a restriction
 * Template File: message.php
 *
 * Template Variables:
 *
 * tMessage
 *
 * Form POST \ GET Variables:
 *
 * id
 */

require_once('common.php');

authentication_require_role('admin');
$SESSID_USERNAME = authentication_get_username();

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
    if (isset ($_GET['id'])) $fRestriction = escape_string ($_GET['id']);
    if (isset ($_GET['domain'])) $fDomain = escape_string ($_GET['domain']);
    if (isset ($_GET['policy'])) $fPolicy = escape_string ($_GET['policy']);

    $sqlSet='active=1-active';
    if ('pgsql'==$CONF['database_type']) $sqlSet='active=NOT active';
   
    if (!check_owner ($SESSID_USERNAME, $fDomain))
    {
        $error = 1;
        $tMessage = $PALANG['pDelete_domain_error'] . "<b>$fDomain</b>!</span>";
    } else
    {
        $result = db_query ("UPDATE $table_restriction SET "
                           ."$sqlSet,modified=NOW() WHERE id='$fRestriction'");
        if ($result['rows'] != 1)
        {
            $error = 1;
            $tMessage = $PALANG['pEdit_restriction_result_error'];
        }
   
        if ($error != 1)
        {
            $result = db_query ("SELECT address, active, policy "
                               ."FROM $table_restriction WHERE id=$fRestriction");
            $row = db_array($result['result']);
            db_log ($SESSID_USERNAME, $fDomain, 'edit_restriction', $row['policy'] ." : " . $row['address'] ." : active=" . $row['active'] );
            header ("Location: list-restriction.php?policy=$fPolicy");
            exit;
        }
    }
    header ("Location: list-restriction.php?policy=$fPolicy");
}

include ("templates/header.php");
include ("templates/menu.php");
include ("templates/message.php");
include ("templates/footer.php");

/* vim: set expandtab softtabstop=3 tabstop=3 shiftwidth=3: */
?>
