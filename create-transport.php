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
 * File: create-transport.php
 * Allows administrators to create new domains.
 * Template File: admin_create-domain.php
 *
 * Template Variables:
 *
 * tNexthop
 * tDescription
 *
 * Form POST \ GET Variables:
 *
 * fNexthop
 * fDescription
 */

require_once('common.php');

authentication_require_role('global-admin');
$SESSID_USERNAME = authentication_get_username();


$form_fields = array(
    'fNexthop'        => array('type' => 'str', 'default' => null),
    'fDescription'    => array('type' => 'str', 'default' =>'')
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
    $tNexthop = $fNexthop;
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if ($fNexthop == null)
    {
        $error = 1;
        $tNexthop = "";
        flash_error($PALANG['pInvalidTransportNull']);
    } elseif (transport_exist($fNexthop, '') or !check_transport($fNexthop))
    {
        $error = 1;
        $tNexthop = $fNexthop;
        $tDescription = $fDescription;
    }

    if ($error != 1)
    {
        # add the new transport 
        $sql_query = "INSERT INTO $table_transport (nexthop,description,created,modified) "
                    ."VALUES ('$fNexthop','$fDescription',NOW(),NOW())";
        $result = db_query($sql_query);
        if ($result['rows'] != 1)
        {
            $tMessage = $PALANG['pAdminCreate_transport_result_error'] . "<br />($fNexthop)<br />";
        }
        else
        {
            $tMessage = $PALANG['pAdminCreate_transport_result_success'] . "<br />($fNexthop)</br />";
            db_log ($SESSID_USERNAME, 'ALL', 'create_transport', $fNexthop);
        }
    }
}

include ("templates/header.php");
include ("templates/menu.php");
include ("templates/admin_create-transport.php");
include ("templates/footer.php");

/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
?>
