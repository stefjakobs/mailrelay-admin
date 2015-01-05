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
 * File: create-restriction.php
 * Allows administrators to create new restrictions.
 * Template File: admin_create-restriction.php
 *
 * Template Variables:
 *
 * tMessage
 * tAddress
 * tDomain
 * tAction
 * tDescription
 * tPolicy
 *
 * Form POST \ GET Variables:
 *
 * fAddress
 * fDomain
 * fAction
 * fDescription
 * fPolicy
 */

require_once('common.php');

authentication_require_role('global-admin');
$SESSID_USERNAME = authentication_get_username();

$form_fields = array(
    'fAddress'        => array('type' => 'str', 'default' => null),
    'fDomain'         => array('type' => 'str', 'default' => null),
    'fAction'         => array('type' => 'int', 'default' => 0),
    'fDescription'    => array('type' => 'str', 'default' => null), 
    'fPolicy'         => array('type' => 'str', 'default' => null), 
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
    $tAddress = $fAddress;
    $tDomain = $fDomain;
    $tAction = $fAction;
    $tDescription = $fDescription;
    $tPolicy = $fPolicy;
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if ($fAddress == null or $fPolicy == null) {
        flash_error($PALANG['pInvalidRestrictionNull']);
        $error = 1;
    } elseif (restriction_exist($fAddress, $fPolicy, '')) { # or !check_address($fAddress)
        $error = 1;
    }
    $tAddress = '';
    $tDescription = '';
    $tDomain = $fDomain;
    $tAction = $fAction;
    $tPolicy = '';

    if ($error != 1)
    {
        # add the new user transport
        $sql_query = "INSERT INTO $table_restriction "
                    ."(address,action,description,policy,domain,created,modified) "
                    ."VALUES ('$fAddress','$fAction','$fDescription','$fPolicy','$fDomain',NOW(),NOW())";
        $result = db_query($sql_query);
        if ($result['rows'] != 1)
        {
            $tMessage = $PALANG['pCreate_restriction_result_error'] . "<br />($fAddress)<br />";
        } else {
            $result = db_query("SELECT action FROM $table_action WHERE id=$fAction");
            $row = db_array($result['result']);
            db_log ($SESSID_USERNAME, $fDomain, 'create_restriction', $fPolicy . " : " . $fAddress . " : " . $row['action']);
            $tMessage = $PALANG['pAdminCreate_restriction_result_success'] . "<br />($fAddress)</br />";
        }
    } else {
        $tAddress = $fAddress;
        $tDomain = $fDomain;
        $tAction = $fAction;
        $tDescription = $fDescription;
        $tPolicy = $fPolicy;
    }
}

# fetch a domain list
$query = "SELECT domain FROM $table_domain ORDER BY domain";
$tDomains = db_query($query);

# fetch a action list
$query = "SELECT action, id FROM $table_action ORDER BY action";
$tActions = db_query($query);


include ("templates/header.php");
include ("templates/menu.php");
include ("templates/admin_create-restriction.php");
include ("templates/footer.php");

/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
?>
