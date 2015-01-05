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
 * File: list-action.php
 * List all actions as a quick overview.
 * Template File: admin_list-action.php
 *                overview-action.php
 *
 * Template Variables:
 *
 * -none-
 *
 * Form POST \ GET Variables:
 *
 * fUsername
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

if ($list_all_domains == 1) {
   $where = "";
} else {
   $list_domains = escape_string($list_domains);
   $where = " WHERE $table_restriction.domain IN ('" . join("','", $list_domains) . "') ";
}


# fetch action data
# (PgSQL requires the extensive GROUP BY statement, https://sourceforge.net/forum/message.php?msg_id=7386240)
$query = "SELECT $table_action.*, $table_restriction.address, $table_restriction.domain " 
        ."FROM $table_action "
        ."LEFT JOIN $table_restriction ON $table_action.id = $table_restriction.action "
        ."$where "
        ."GROUP BY $table_action.id, $table_action.action, $table_action.description, "
        ."$table_action.created, $table_action.modified, $table_restriction.address, "
        ."$table_restriction.domain "
        ."ORDER BY $table_action.action";
$result = db_query($query);

$action_properties = array();
while ($row = db_array ($result['result'])) {
   if(isset($action_properties[$row['action']]) ) {
      $actions = $action_properties[$row['action']]['address'];
      $action_properties[$row['action']] = $row;
      $action_properties[$row['action']]['address'] = $actions . '<br>' 
	. $action_properties[$row['action']]['address'];
   } else {
      $action_properties[$row['action']] = $row;
   }
}

include ("templates/header.php");
include ("templates/menu.php");

if ($is_superadmin) {
   include ("templates/admin_list-action.php");
} else {
   include ("templates/overview-action.php");
}
include ("templates/footer.php");

/* vim: set expandtab softtabstop=3 tabstop=3 shiftwidth=3: */
?>
