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
 * File: list-restriction.php
 * List all restrictions as a quick overview.
 * Template File: list-restriction.php
 *
 * Template Variables:
 *
 * -none-
 *
 * Form POST \ GET Variables:
 *
 * fUsername
 * fPolicy
 */

require_once('common.php');

authentication_require_role('admin');

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

$fPolicy = safepost('fPolicy', safeget('policy'));
if ($list_all_domains == 1) {
   if(!empty($fPolicy)) {
       $where = " WHERE $table_restriction.policy = '$fPolicy'";
   } else $where = "";
} else {
   $list_domains = escape_string($list_domains);
   $where = " WHERE $table_restriction.domain IN ('" . join("','", $list_domains) . "') ";
   if(!empty($fPolicy)) {
       $where = $where . " AND $table_restriction.policy = '$fPolicy'";
   }
}


# fetch restriction data 
$query = "SELECT $table_restriction.* , $table_action.id AS action_id, $table_action.action "
        ."FROM $table_restriction "
        ."LEFT JOIN $table_action ON $table_action.id = $table_restriction.action "
        ."$where "
        ."ORDER BY $table_restriction.address";
$result = db_query($query);

$restriction_properties = array();
while ($row = db_array ($result['result'])) {
    $restriction_properties[$row['id']] = $row;
}

# fetch a list of policies
$query = "SELECT DISTINCT policy FROM $table_restriction GROUP BY policy";
$result = db_query($query);

# add an empty policy which is used to show all policies
$list_policy = "";
if ($result['rows'] > 0) {
   $list_policy[0] = '';
   $i = 1;
   while ($row = db_array ($result['result'])) {
       $list_policy[$i] = $row['policy'];
       $i++;
   }
}

include ("templates/header.php");
include ("templates/menu.php");
include ("templates/list-restriction.php");
include ("templates/footer.php");

/* vim: set expandtab softtabstop=3 tabstop=3 shiftwidth=3: */
?>
