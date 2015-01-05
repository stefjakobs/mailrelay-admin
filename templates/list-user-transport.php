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
if (sizeof ($user_properties) > 0)
{
   print "<table id=\"admin_table\">\n";
   print "   <tr>\n";
   print "      <td colspan=\"7\"><h3>".$PALANG['pAdminList_user_title']."</h3></td>";
   print "   </tr>";
   print "   <tr class=\"header\">\n";
   print "      <td>" . $PALANG['pAdminList_user_address'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_user_description'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_user_domain'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_user_relay'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_user_modified'] . "</td>\n";
#  print "      <td>" . $PALANG['pAdminList_user_active'] . "</td>\n";
   print "      <td colspan=\"2\">&nbsp;</td>\n";
   print "   </tr>\n";

   foreach(array_keys($user_properties) as $i)
   {
      if ((is_array ($user_properties) and sizeof ($user_properties) > 0))
      {
         print "   <tr class=\"hilightoff\" onMouseOver=\"className='hilighton';\" onMouseOut=\"className='hilightoff';\">\n";
         print "<td align=right><a href=\"edit-user-transport.php?address=" . $user_properties[$i]['address'] . "\">" . $user_properties[$i]['address'] . "</a></td>";
         print "<td>" . $user_properties[$i]['description'] . "</td>";
         if ($is_superadmin) {
             print "<td align=right><a href=\"edit-domain.php?domain=" . $user_properties[$i]['domain'] . "\">" . $user_properties[$i]['domain'] . "</a></td>";
             print "<td align=right><a href=\"edit-transport.php?id=" . $user_properties[$i]['id'] . "\">" . $user_properties[$i]['nexthop'] . "</a></td>";
         } else {
             print "<td align=right>" . $user_properties[$i]['domain'] . "</a></td>";
             print "<td align=right>" . $user_properties[$i]['nexthop'] . "</a></td>";
         }
         print "<td>" . $user_properties[$i]['modified'] . "</td>";
#         $active = ($user_properties[$i]['active'] == db_get_boolean(true)) ? $PALANG['YES'] : $PALANG['NO'];
#         print "<td><a href=\"edit-active-user-transport.php?address=" . $user_properties[$i]['address'] . "\">" . $active . "</a></td>";
         print "<td><a href=\"edit-user-transport.php?address=" . $user_properties[$i]['address'] . "\">" . $PALANG['edit'] . "</a></td>";
         print "<td><a href=\"delete.php?table=user_transport&delete=" . $user_properties[$i]['address'] . "&domain=" . $user_properties[$i]['domain'] . "\" onclick=\"return confirm ('" . $PALANG['confirm_user'] . $PALANG['pAdminList_admin_user'] . ": " . $user_properties[$i]['address'] . "')\">" . $PALANG['del'] . "</a></td>";
         print "</tr>\n";
		}
   }

   print "</table>\n";
}
echo "<p><a href='create-user-transport.php'>{$PALANG['pAdminMenu_create_user']}</a>";
?>
