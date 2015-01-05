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
 * File: edit-action.php 
 * Updates the properties of a action.
 * Template File: admin_edit-action.php
 *
 * Template Variables:
 *
 * tId
 * tAction
 * tDescription
 * tRestrictions
 *
 * Form POST \ GET Variables:
 *
 * fId
 * fAction
 * fDescription
 */

require_once('common.php');

authentication_require_role('global-admin');
$SESSID_USERNAME = authentication_get_username();

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
    if (isset ($_GET['id']))
    {
        $tId = intval ($_GET['id']);
        $action_properties = get_action_properties ($tId);

        $tAction = $action_properties['action'];
        $tDescription = $action_properties['description'];
        $tRestrictions = $action_properties['restrictions'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if (isset ($_GET['id'])) $fId = intval ($_GET['id']);
    if (isset ($_POST['fAction'])) $fAction = escape_string ($_POST['fAction']);
    if (isset ($_POST['fDescription'])) $fDescription = escape_string ($_POST['fDescription']);

    if ($fAction == NULL) {
        $error = 1;
        flash_error($PALANG['pInvalidActionNull']);
    } elseif (action_exist($fAction, $fId)) { # or !check_action($fAction))
        $error = 1;
    }
    
    if ($error != 1) {
        $result = db_query ("UPDATE $table_action SET "
                           ."action='$fAction', description='$fDescription',modified=NOW() "
                           ."WHERE id='$fId'");
        if ($result['rows'] == 1)
        {
            db_log ($SESSID_USERNAME, 'ALL', 'edit_action', $fAction);
            header ("Location: list-action.php");
            exit;
        } else {
            $tMessage = $PALANG['pAdminEdit_action_result_error'];
        }
    } else {
        $action_properties = get_action_properties ($fId);
        $tAction = $fAction;
        $tDescription = $fDescription;
        $tRestrictions = $action_properties['restrictions'];
    }
}

include ("templates/header.php");
include ("templates/menu.php");
include ("templates/admin_edit-action.php");
include ("templates/footer.php");

/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
?>
