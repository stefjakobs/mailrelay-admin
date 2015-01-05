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
 * File: edit-restriction.php 
 * Updates the properties of a restriction.
 * Template File: edit-restriction.php
 *
 * Template Variables:
 *
 * tAddress
 * tAction
 * tAction_name
 * tDescription
 * tPolicy
 * tActive
 * tActions
 * tDomains
 *
 * Form POST \ GET Variables:
 *
 * fAddress
 * fAction
 * fDescription
 * fPolicy
 * fActive
 */

require_once('common.php');

authentication_require_role('admin');
$SESSID_USERNAME = authentication_get_username();
if (authentication_has_role('global-admin')) {
   $list_admins = list_admins ();
   $is_superadmin = 1;
   $fUsername = safepost('fUsername', safeget('username')); # prefer POST over GET variable
   if ($fUsername != "") $admin_properties = get_admin_properties($fUsername);
} else {
   $list_admins = array(authentication_get_username());
   $is_superadmin = 0;
   $fUsername = "";
}
$list_all_domains = 0;
if (isset($admin_properties) && $admin_properties['domain_count'] == 'ALL') { # list all domains for superadmins
   $list_all_domains = 1;
} elseif (!empty($fUsername)) {
  $list_domains = list_domains_for_admin ($fUsername);
} elseif ($is_superadmin) {
   $list_all_domains = 1;
} else {
   $list_domains = list_domains_for_admin(authentication_get_username());
}
if ($list_all_domains == 1) {
   $where = ""; 
} else {
   $list_domains = escape_string($list_domains);
   $where = " WHERE domain.domain IN ('" . join("','", $list_domains) . "') ";
}


if ($_SERVER['REQUEST_METHOD'] == "GET")
{
    if (isset ($_GET['id']))
    {
        $tId = intval ($_GET['id']);
        $restriction_properties = get_restriction_properties ($tId);

        $tAddress = $restriction_properties['address'];
        $tAction = $restriction_properties['action'];
        $tAction_name = $restriction_properties['action_name'];
        $tDescription = $restriction_properties['description'];
        $tPolicy = $restriction_properties['policy'];
        $tDomain = $restriction_properties['domain'];
        $tActive = $restriction_properties['active'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if (isset ($_GET['id'])) $fId = intval ($_GET['id']);

    if (isset ($_POST['fAddress'])) $fAddress = escape_string ($_POST['fAddress']);
    if (isset ($_POST['fAction'])) $fAction = intval ($_POST['fAction']);
    if (isset ($_POST['fDescription'])) $fDescription = escape_string ($_POST['fDescription']);
    if (isset ($_POST['fPolicy'])) $fPolicy = escape_string ($_POST['fPolicy']);
    if (isset ($_POST['fDomain'])) $fDomain = escape_string ($_POST['fDomain']);
    if (isset ($_POST['fActive'])) $fActive = escape_string ($_POST['fActive']);

    if ($fActive == "on") {
        $sqlActive = db_get_boolean(True);
    }
    else {
        $sqlActive = db_get_boolean(False);
    }

    if ($fAddress == null or $fPolicy == null) {
        flash_error($PALANG['pInvalidRestrictionNull']);
        $error = 1;
    } elseif (restriction_exist($fAddress, $fPolicy, $fId)) { # or !check_address($fAddress)
        $error = 1;
    }

    if ($error != 1) {
        $result = db_query ("UPDATE $table_restriction " 
           ."SET address='$fAddress',action='$fAction',description='$fDescription', "
           ."policy='$fPolicy',domain='$fDomain',active='$sqlActive',modified=NOW() "
           ."WHERE id='$fId'");
        if ($result['rows'] != 1)
        {
            $tMessage = $PALANG['pEdit_restriction_result_error'] . "<br />($fAddress)<br />";
        }
        else
        {
            $result = db_query ("SELECT action FROM $table_action WHERE id=$fAction");
            $row = db_array($result['result']);
            db_log ($SESSID_USERNAME, $fDomain, 'edit_restriction', $fPolicy . " : " . $fAddress . " : " . $row['action']);
            header ("Location: list-restriction.php");
            exit;
        }
    } else {
        $tAddress = $fAddress;
        $tAction = $fAction;
        $tDescription = $fDescription;
        $tPolicy = $fPolicy;
        $tDomain = $fDomain;
        $tActive = $fActive;
    }
}

# fetch the action list 
$query = "SELECT action, id FROM $table_action";
$tActions = db_query($query);

# fetch the domain list 
$query = "SELECT domain FROM $table_domain $where";
$tDomains = db_query($query);


include ("templates/header.php");
include ("templates/menu.php");
include ("templates/edit-restriction.php");
include ("templates/footer.php");

/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
?>
