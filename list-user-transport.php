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
 * File: list-user-transport.php
 * List all user transports as a quick overview.
 * Template File: admin_list-user-transport.php
 *                overview-user-transport.php
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
   $where = " WHERE $table_user_transport.domain IN ('" . join("','", $list_domains) . "') ";
}

# fetch user transport data and nexthop
# (PgSQL requires the extensive GROUP BY statement, https://sourceforge.net/forum/message.php?msg_id=7386240)
$query = "SELECT $table_user_transport.*, $table_transport.nexthop, $table_transport.id " 
        ."FROM $table_user_transport "
        ."LEFT JOIN $table_transport_map ON $table_transport_map.address = $table_user_transport.address "
        ."LEFT JOIN $table_transport ON $table_transport_map.transport = $table_transport.id "
        ."$where "
        ."GROUP BY $table_user_transport.domain, $table_user_transport.description, "
        ."$table_user_transport.address, $table_user_transport.created, "
        ."$table_user_transport.modified, $table_user_transport.active, "
        ."$table_transport.nexthop, $table_transport.id "
        ."ORDER BY $table_user_transport.address";
$result = db_query($query);

$user_properties = array();
while ($row = db_array ($result['result'])) {
   $user_properties[$row['address']] = $row;
}

include ("templates/header.php");
include ("templates/menu.php");
include ("templates/list-user-transport.php");
include ("templates/footer.php");

/* vim: set expandtab softtabstop=3 tabstop=3 shiftwidth=3: */
?>
