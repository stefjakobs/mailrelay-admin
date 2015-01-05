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
 * File: edit-user-transport.php
 * Allows to edit a transport entries for users.
 * Template File: edit-user-transport.php
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
 * fAddress
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
    if (isset ($_GET['address']))
    {
        $fAddress = escape_string ($_GET['address']);
        $user_properties = get_user_properties ($fAddress);
        $tLocalpart = preg_split('/@/', $fAddress);
        $tLocalpart = $tLocalpart[0];
        $tDomain = $user_properties['domain'];
        $tSubdomain = preg_split("/$tLocalpart@/", $fAddress);
        $tSubdomain = preg_split("/$tDomain/", $tSubdomain[1]);
        $tSubdomain = $tSubdomain[0];
        $tDescription = $user_properties['description'];
        $tNexthop_id = $user_properties['nexthop_id'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if (isset ($_GET['address'])) $fAddress = escape_string ($_GET['address']);
    if (isset ($_POST['fLocalpart'])) $fLocalpart = escape_string ($_POST['fLocalpart']);
    if (isset ($_POST['fSubdomain'])) $fSubdomain = escape_string ($_POST['fSubdomain']);
    if (isset ($_POST['fDomain'])) $fDomain = escape_string ($_POST['fDomain']);
    if (isset ($_POST['fDescription'])) $fDescription = escape_string ($_POST['fDescription']);
    if (isset ($_POST['fNexthop_id'])) $fNexthop_id = escape_string ($_POST['fNexthop_id']);

    if (!isset($address)) $address = "$fLocalpart" . '@' . "$fSubdomain" . "$fDomain";

    if ($fDomain == null or $fLocalpart == null )
    {
        $error = 1;
        flash_error(sprintf($PALANG['pInvalidNullString'], htmlentities($address)));
    }
    if ( !check_domain(trim($fSubdomain .$fDomain)) or !check_subdomain(trim($fSubdomain)) )
    {
        $error = 1;
        // error message handled by check_* functions
    }
    if ($address != $fAddress) {
       if (user_exist($address)) { $error = 1; }
    }
    if (preg_match ('/^\./', trim($fSubdomain .$fDomain)) )
    {
        $error = 1;
        flash_error(sprintf($PALANG['pInvalidLeadingDot'], htmlentities($fSubdomain .$fDomain)));
    }
    $tLocalpart = $fLocalpart;
    $tDomain = $fDomain;
    $tSubdomain = $fSubdomain;
    $tDescription = $fDescription;
    $tNexthop_id = $fNexthop_id;

    if ($error != 1)
    {
        # update the user transport
        $sql_query = "UPDATE $table_user_transport " 
                    ."SET address='$address', domain='$fDomain', "
                    ."description='$fDescription', modified=NOW() "
                    ."WHERE address='$fAddress'";
        $result = db_query($sql_query);
        if ($result['rows'] != 1)
        {
            $tMessage = $PALANG['pEdit_user_result_error'] . "<br />($address)<br />";
        } else {
            $query = "UPDATE $table_transport_map "
                    ."SET transport='$fNexthop_id',address='$address' "
                    ."WHERE address='$fAddress'";
            $result = db_query($query);
            if ($result['rows'] == 1 || $result['rows'] == 0 )
            {
                $result = db_query ("SELECT nexthop FROM $table_transport WHERE id=$fNexthop_id");
                $row = db_array($result['result']);
                db_log ($SESSID_USERNAME, $fDomain, 'edit_user_transport', $address ." -> " .$row['nexthop']);
                header ("Location: list-user-transport.php");
                exit;
            } else
            {
                $tMessage = $PALANG['pEdit_user_result_error'] . "<br />($address)<br />";
            }
        }
    }
}

# fetch the nexthop transports
$query = "SELECT nexthop, id
          FROM $table_transport
         ";
$tNexthops = db_query($query);

# fetch the users domains
$query = "SELECT domain
          FROM $table_domain
          $where 
          ORDER BY domain
         ";
$tDomains = db_query($query);


include ("templates/header.php");
include ("templates/menu.php");
include ("templates/edit-user-transport.php");
include ("templates/footer.php");

/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
?>
