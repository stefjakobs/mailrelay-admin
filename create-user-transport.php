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
 * File: create-user-transport.php
 * Allows administrators to create new transport entries for users.
 * Template File: create-user-transport.php
 *
 * Template Variables:
 *
 * tMessage
 * tLocalpart
 * tDomain
 * tSubdomain
 * tDescription
 * tNexthop_id
 * tNexthops
 *
 * Form POST \ GET Variables:
 *
 * fLocalpart
 * fDomain
 * fSubdomain
 * fDescription
 * fNexthop_id
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
   $where = " WHERE domain.domain != 'ALL' "; # TODO: the ALL dummy domain is annoying...
} else {
   $list_domains = escape_string($list_domains);
   $where = " WHERE domain.domain IN ('" . join("','", $list_domains) . "') ";
}


$form_fields = array(
    'fLocalpart'      => array('type' => 'str', 'default' => null),
    'fDomain'         => array('type' => 'str', 'default' => null),
    'fSubdomain'      => array('type' => 'str', 'default' => null),
    'fDescription'    => array('type' => 'str', 'default' => ''), 
    'fNexthop_id'     => array('type' => 'int', 'default' => 0), 
);

foreach($form_fields  as $key => $default) {
    if(isset($_POST[$key]) && (strlen($_POST[$key]) > 0)) {
        $$key = escape_string($_POST[$key]);
    }
    else {
        $$key = $default['default'];
    }
    if($default['type'] == 'int') {
        $$key = intval($$key);
    }
    if($default['type'] == 'str') {
        $$key = strip_tags($$key); /* should we even bother? */
    }
    if(isset($default['options'])) {
        if(!in_array($$key, $default['options'])) {
            die("Invalid parameter given for $key");
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == "GET")
{
    /* default values as set above */
    $tLocalpart = $fLocalpart;
    $tDomain = $fDomain;
    $tSubdomain = $fSubdomain;
    $tDescription = $fDescription;
    $tNexthop_id = $fNexthop_id;
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    $fLocalpart = trim($fLocalpart);
    $fSubdomain = trim($fSubdomain);
    $address = "$fLocalpart" . '@' . "$fSubdomain" . "$fDomain";

    $tLocalpart = '';
    $tDomain = $fDomain;
    $tSubdomain = $fSubdomain;
    $tNexthop_id = 0;
    if ($fDomain == null or !check_domain($fSubdomain .$fDomain) or 
        user_exist($address) or !check_subdomain(trim($fSubdomain)) )
    {
        $error = 1;
        $tLocalpart = $fLocalpart;
        $tDescription = $fDescription;
        $tNexthop_id = $fNexthop_id;
    }

    if ($error != 1)
    {
        # add the new user transport
        $sql_query = "INSERT INTO $table_user_transport "
                    ."(address,domain,description,created,modified) VALUES "
                    ."('$address','$fDomain','$fDescription',NOW(),NOW())";
        $result = db_query($sql_query);
        if ($result['rows'] != 1)
        {
            $tMessage = $PALANG['pCreate_user_result_error'] . "<br />($address)<br />";
        } else {
            $query = "INSERT INTO $table_transport_map (transport,address) "
                    ."VALUES ('$fNexthop_id', '$address')";
            $result = db_query($query);
            if ($result['rows'] != 1)
            {
                $tMessage = $PALANG['pCreate_user_result_error'] . "<br />($address)<br />";
            } else {
                $tMessage = $PALANG['pAdminCreate_user_result_success'] . "<br />($address)<br />";
                $result = db_query ("SELECT nexthop FROM $table_transport WHERE id=$fNexthop_id");
                $row = db_array($result['result']);
                db_log ($SESSID_USERNAME, $fDomain, 'create_user_transport', $address ." -> " .$row['nexthop']);
            }
        }
    }
}

# fetch the nexthop transports
$query = "SELECT nexthop, id FROM $table_transport ORDER BY nexthop";
$tNexthops = db_query($query);

# fetch the users domains
$query = "SELECT domain FROM $table_domain $where ORDER BY domain";
$tDomains = db_query($query);


include ("templates/header.php");
include ("templates/menu.php");
include ("templates/create-user-transport.php");
include ("templates/footer.php");

/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
?>
