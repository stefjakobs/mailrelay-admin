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
 * File: edit-transport.php 
 * Updates the properties of a transport.
 * Template File: admin_edit-transport.php
 *
 * Template Variables:
 *
 * tId
 * tNexthop
 * tDescription
 * tDomains
 *
 * Form POST \ GET Variables:
 *
 * fId
 * fNexthop
 * fDescription
 */

require_once('common.php');

authentication_require_role('global-admin');
$SESSID_USERNAME = authentication_get_username();

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
    if (isset ($_GET['id']))
    {
        $tId = escape_string ($_GET['id']);
        $transport_properties = get_transport_properties ($tId);

        $tNexthop = $transport_properties['nexthop'];
        $tDescription = $transport_properties['description'];
        $tDomains = $transport_properties['domains'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if (isset ($_GET['id'])) $fId = escape_string ($_GET['id']);
    if (isset ($_POST['fNexthop'])) $fNexthop = escape_string ($_POST['fNexthop']);
    if (isset ($_POST['fDescription'])) $fDescription = escape_string ($_POST['fDescription']);

    if ($fNexthop == null) {
        $error = 1;
        flash_error($PALANG['pInvalidTransportNull']);
    } elseif (!check_transport($fNexthop) or transport_exist($fNexthop, $fId)) {
        $error = 1;
    }
    
    if ($error != 1)  
    {
        $result = db_query ("UPDATE $table_transport SET "
                           ."nexthop='$fNexthop', description='$fDescription',modified=NOW() "
                           ."WHERE id='$fId'");
        if ($result['rows'] == 1)
        {
            db_log ($SESSID_USERNAME, 'ALL', 'edit_transport', $fNexthop);
            header ("Location: list-transport.php");
            exit;
        }
        else
        {
            $tMessage = $PALANG['pAdminEdit_transport_result_error'];
        }
    } else {
        $transport_properties = get_transport_properties ($fId);
        $tId = $fId;
        $tNexthop = $fNexthop;
        $tDescription = $fDescription;
        $tDomains = $transport_properties['domains'];
    }
}

include ("templates/header.php");
include ("templates/menu.php");
include ("templates/admin_edit-transport.php");
include ("templates/footer.php");

/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
?>
