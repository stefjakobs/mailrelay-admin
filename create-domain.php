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
 * @version $Id: create-domain.php 1037 2011-04-15 23:26:58Z christian_boltz $ 
 * @license GNU GPL v2 or later. 
 * 
 * File: create-domain.php
 * Allows administrators to create new domains.
 * Template File: admin_create-domain.php
 *
 * Template Variables:
 *
 * tMessage
 * tDomain
 * tDescription
 * tTransport
 * tNexthop_id
 * tAliases
 * tMailboxes
 * tMaxquota
 * tDefaultaliases
 * tNexthops
 *
 * Form POST \ GET Variables:
 *
 * fDomain
 * fDescription
 * fTransport
 * fNexthop_id
 * fAliases
 * fMailboxes
 * fMaxquota
 * fDefaultaliases
 */

require_once('common.php');
$SESSID_USERNAME = authentication_get_username();
authentication_require_role('global-admin');


$form_fields = array(
    'fDomain'         => array('type' => 'str', 'default' => null),
    'fDescription'    => array('type' => 'str', 'default' =>''), 
    'fAliases'        => array('type' => 'int', 'default' => $CONF['aliases']), 
    'fMailboxes'      => array('type' => 'int', 'default' => $CONF['mailboxes']), 
    'fMaxquota'       => array('type' => 'int', 'default' => $CONF['maxquota']), 
    'fTransport'      => array('type' => 'str', 'default' => $CONF['transport_default'], 'options' => $CONF['transport_options']),
    'fNexthop_id'     => array('type' => 'int', 'default' => 0), 
    'fDefaultaliases' => array('type' => 'str', 'default' => 'off', 'options' => array('on', 'off')), 
    'fBackupmx'       => array('type' => 'str', 'default' => 'off', 'options' => array('on', 'off')) 
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

$fDomain = strtolower($fDomain);

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
    /* default values as set above */
    $tTransport = $fTransport;
    $tNexthop_id = $fNexthop_id;
    $tAliases = $fAliases;
    $tMaxquota = $fMaxquota;
    $tMailboxes = $fMailboxes;
    $tDefaultaliases = $fDefaultaliases;
    $tBackupmx = $fBackupmx;
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    $tBackupmx = "";
    if ($fDomain == null or domain_exist($fDomain) or !check_domain($fDomain))
    {
        $error = 1;
        $tDomain = $fDomain;
        $tDescription = $fDescription;
        $tAliases = $fAliases;
        $tMailboxes = $fMailboxes;
        if (isset ($_POST['fMaxquota'])) $tMaxquota = $fMaxquota;
        if (isset ($_POST['fTransport'])) $tTransport = $fTransport;
        if (isset ($_POST['fDefaultaliases'])) $tDefaultaliases = $fDefaultaliases;
        if (isset ($_POST['fBackupmx'])) $tBackupmx = $fBackupmx;
        if (isset ($_POST['fNexthop_id'])) $tNexthop_id = $fNexthop_id;
    }

    if ($error != 1)
    {
        $tAliases = $CONF['aliases'];
        $tMailboxes = $CONF['mailboxes'];
        $tMaxquota = $CONF['maxquota'];

        if ($fBackupmx == "on")
        {
            $fBackupmx = 1;
            $sqlBackupmx = db_get_boolean(true);
        }
        else
        {
            $fBackupmx = 0;
            $sqlBackupmx = db_get_boolean(false);
        }
        $tNexthop_id = 0;

        # add the new domain
        $sql_query = "INSERT INTO $table_domain "
                    ."(domain,description,aliases,mailboxes,maxquota,transport,backupmx,created,modified) "
                    ."VALUES ('$fDomain','$fDescription',$fAliases,$fMailboxes,$fMaxquota,'$fTransport','$sqlBackupmx',NOW(),NOW())";
        $result = db_query($sql_query);
        if ($result['rows'] != 1)
        {
            $tMessage = $PALANG['pAdminCreate_domain_result_error'] . "<br />($fDomain)<br />";
        }
        else
        {
            if ($fDefaultaliases == "on")
            {
                foreach ($CONF['default_aliases'] as $address=>$goto)
                {
                    $address = $address . "@" . $fDomain;
                    $result = db_query ("INSERT INTO $table_alias "
                                       ."(address,goto,domain,created,modified) VALUES "
                                       ."('$address','$goto','$fDomain',NOW(),NOW())");
                }
            }
                
            $tMessage = $PALANG['pAdminCreate_domain_result_success'] . "<br />($fDomain)</br />";
        }
        if ($CONF['relay'] != 'NO' )
        {
            $query = "INSERT INTO $table_transport_map (transport,address) "
                    ."VALUES ('$fNexthop_id', '$fDomain')";
            $result = db_query($query);
            if ($result['rows'] != 1)
            {
                $tMessage = $PALANG['pAdminCreate_transport_result_error'] . "<br />($fNexthop_id)<br />";
            }
        }
        if (!domain_postcreation($fDomain))
        {
             $tMessage = $PALANG['pAdminCreate_domain_error'];
        }
        db_log ($SESSID_USERNAME, $fDomain, 'create_domain', $fDomain );
    }
}

# fetch the nexthop transports
$query = "SELECT nexthop, id "
        ."FROM $table_transport "
        ."ORDER BY nexthop";

$tNexthops = db_query($query);


include ("templates/header.php");
include ("templates/menu.php");
include ("templates/admin_create-domain.php");
include ("templates/footer.php");

/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
?>
