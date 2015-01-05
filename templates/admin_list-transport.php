<?php if( !defined('POSTFIXADMIN') ) die( "This file cannot be used standalone." ); ?>
<div id="overview">
<form name="overview" method="post">
<select name="fUsername" onChange="this.form.submit();">
<?php
if ($fUsername == '') {
    $fUsername = $_SESSION['sessid']['username'];
}
if (!empty ($list_admins))
{
   for ($i = 0; $i < sizeof ($list_admins); $i++)
   {
      if ($fUsername == $list_admins[$i])
      {
         print "<option value=\"" . $list_admins[$i] . "\" selected>" . $list_admins[$i] . "</option>\n";
      }
      else
      {
         print "<option value=\"" . $list_admins[$i] . "\">" . $list_admins[$i] . "</option>\n";
      }
   }
}
?>
</select>
<input class="button" type="submit" name="go" value="<?php print $PALANG['pOverview_button']; ?>" />
</form>
<form name="search" method="post" action="search.php">
<input type="textbox" name="search" size="10" />
</form>
</div>

<?php 
if (sizeof ($transport_properties) > 0)
{
   print "<table id=\"admin_table\">\n";
   print "   <tr>\n";
   print "      <td colspan=\"7\"><h3>".$PALANG['pAdminList_transport_title']."</h3></td>";
   print "   </tr>";
   print "   <tr class=\"header\">\n";
   print "      <td>" . $PALANG['pAdminList_transport_id'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_transport_transport'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_transport_description'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_transport_modified'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_transport_domains'] . "</td>\n";
   print "      <td colspan=\"2\">&nbsp;</td>\n";
   print "   </tr>\n";

   foreach(array_keys($transport_properties) as $i)
   {
      if ((is_array ($transport_properties) and sizeof ($transport_properties) > 0))
      {
         print "   <tr class=\"hilightoff\" onMouseOver=\"className='hilighton';\" onMouseOut=\"className='hilightoff';\">\n";
         print "<td valign=\"top\">" . $transport_properties[$i]['id'] . "</td>";
         print "<td valign=\"top\" align=right><a href=\"edit-transport.php?id=" . $transport_properties[$i]['id'] . "\">" . $transport_properties[$i]['nexthop'] . "</a></td>";
         print "<td valign=\"top\">" . $transport_properties[$i]['description'] . "</td>";
         print "<td valign=\"top\">" . $transport_properties[$i]['modified'] . "</td>";
         print "<td valign=\"top\" align=right>" . $transport_properties[$i]['address'] . "</td>";
         print "<td valign=\"top\"><a href=\"edit-transport.php?id=" . $transport_properties[$i]['id'] . "\">" . $PALANG['edit'] . "</a></td>";
         print "<td valign=\"top\"><a href=\"delete.php?table=transport&delete=" . $transport_properties[$i]['id'] . "\" onclick=\"return confirm ('" . $PALANG['confirm_transport'] . $PALANG['pAdminList_admin_transport'] . ": " . $transport_properties[$i]['nexthop'] . "')\">" . $PALANG['del'] . "</a></td>";
         print "</tr>\n";
		}
   }
   print "</table>\n";
}
echo "<p><a href='create-transport.php'>{$PALANG['pAdminMenu_create_transport']}</a>";
?>
