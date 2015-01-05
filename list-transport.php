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
 * File: list-transport.php
 * List all transport as a quick overview.
 * Template File: admin_list-transport.php
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

$list_all_transports = 0;
if (isset($admin_properties) && $admin_properties['domain_count'] == 'ALL') { # list all transports for superadmins
   $list_all_transports = 1;
} elseif (!empty($fUsername)) {
  $list_domains = list_domains_for_admin ($fUsername);
} elseif ($is_superadmin) {
   $list_all_transports = 1;
} else {
   $list_domains = list_domains_for_admin(authentication_get_username());
}

if ($list_all_transports == 1) {
   $where = ""; 
} else {
   $list_domains = escape_string($list_domains);
   $where = " WHERE $table_transport_map.address IN ('" . join("','", $list_domains) . "')" 
           ." OR $table_user_transport.domain IN ('" . join("','", $list_domains) . "') ";
}

# fetch transport data
# (PgSQL requires the extensive GROUP BY statement, https://sourceforge.net/forum/message.php?msg_id=7386240)
$query = "SELECT $table_transport.*, $table_transport_map.address "
        ."FROM $table_transport "
        ."LEFT JOIN $table_transport_map ON $table_transport_map.transport = $table_transport.id "
        ."LEFT JOIN $table_user_transport ON $table_user_transport.address = $table_transport_map.address "
        ."$where "
        ."GROUP BY $table_transport.id, $table_transport.nexthop, "
            ."$table_transport.description, $table_transport_map.address, "
            ."$table_transport.created, $table_transport.modified "
        ."ORDER BY $table_transport.nexthop";
$result = db_query($query);

$transport_properties = array();
while ($row = db_array ($result['result'])) {
   if ( isset($transport_properties[$row['nexthop']]) ) {
      $domains = $transport_properties[$row['nexthop']]['address'];
      $transport_properties[$row['nexthop']] = $row;
      $transport_properties[$row['nexthop']]['address'] = $domains . '<br>' . $transport_properties[$row['nexthop']]['address'];
   } else {
      $transport_properties[$row['nexthop']] = $row;
   }
}

include ("templates/header.php");
include ("templates/menu.php");

if ($is_superadmin) {
   include ("templates/admin_list-transport.php");
} else {
   include ("templates/overview-transport.php");
}
include ("templates/footer.php");

/* vim: set expandtab softtabstop=3 tabstop=3 shiftwidth=3: */
?>
