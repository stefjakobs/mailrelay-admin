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
 * File: create-action.php
 * Allows administrators to create new restriction actions.
 * Template File: admin_create-action.php
 *
 * Template Variables:
 *
 * tMessage
 * tAction
 * tDescription
 *
 * Form POST \ GET Variables:
 *
 * fAction
 * fDescription
 */

require_once('common.php');

authentication_require_role('global-admin');
$SESSID_USERNAME = authentication_get_username();

$form_fields = array(
    'fAction'         => array('type' => 'str', 'default' => null),
    'fDescription'    => array('type' => 'str', 'default' => ''), 
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
    $tAction = $fAction;
    $tDescription = $fDescription;
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if ($fAction == null) # or !check_action($fAction))  
    {
        $error = 1;
        flash_error($PALANG['pInvalidActionNull']);
    } elseif (action_exist($fAction, '')) { # or !check_action($fAction)) 
        $error = 1;
    }
    $tAction = '';
    $tDescription = '';

    if ($error != 1)
    {
        # add the new action 
        $sql_query = "INSERT INTO $table_action (action,description,created,modified) "
                    ."VALUES ('$fAction','$fDescription',NOW(),NOW())";
        $result = db_query($sql_query);
        if ($result['rows'] != 1)
        {
            $tMessage = $PALANG['pCreate_action_result_error'] . "<br />($fAction)<br />";
        } else {
            db_log ($SESSID_USERNAME, 'ALL', 'create_action', $fAction);
            $tMessage = $PALANG['pAdminCreate_action_result_success'] . "<br />($fAction)</br />";
        }
    } else {
        $tAction = $fAction;
        $tDescription = $fDescription;
    }
}


include ("templates/header.php");
include ("templates/menu.php");
include ("templates/admin_create-action.php");
include ("templates/footer.php");

/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
?>
