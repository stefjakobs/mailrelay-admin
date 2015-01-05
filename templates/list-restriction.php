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
</form>
<form name="search" method="post">
<?php print $PALANG['pOverview_restriction_search']; ?>
<select name="fPolicy" onChange="this.form.submit();">
<?php
if (!empty ($list_policy))
{
   for ($i = 0; $i < sizeof ($list_policy); $i++)
   {
      if ($fPolicy == $list_policy[$i])
      {
         print "<option value=\"" . $list_policy[$i] . "\" selected>" . $list_policy[$i] . "</option>\n";
      }
      else
      {
         print "<option value=\"" . $list_policy[$i] . "\">" . $list_policy[$i] . "</option>\n";
      }
   }
}
?>
</select>
<input class="button" type="submit" name="go" value="<?php print $PALANG['pOverview_button']; ?>" />
</form>
</div>

<?php 
if (sizeof ($restriction_properties) > 0)
{
   print "<table id=\"admin_table\">\n";
   print "   <tr>\n";
   print "      <td colspan=\"8\"><h3>".$PALANG['pAdminList_restriction_title']."</h3></td>";
   print "   </tr>";
   print "   <tr class=\"header\">\n";
   print "      <td>" . $PALANG['pAdminList_restriction_address'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_restriction_action'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_restriction_description'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_restriction_policy'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_restriction_modified'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_restriction_active'] . "</td>\n";
   print "      <td colspan=\"2\">&nbsp;</td>\n";
   print "   </tr>\n";

   foreach(array_keys($restriction_properties) as $i)
   {
      if ((is_array ($restriction_properties) and sizeof ($restriction_properties) > 0))
      {
         print "   <tr class=\"hilightoff\" onMouseOver=\"className='hilighton';\" onMouseOut=\"className='hilightoff';\">\n";
         print "<td align=right><a href=\"edit-restriction.php?id=" .$restriction_properties[$i]['id'] . "\">" . $restriction_properties[$i]['address'] . "</a></td>";
         if ($is_superadmin) {
             print "<td><a href=\"edit-action.php?id=" .$restriction_properties[$i]['action_id'] . "\">" . $restriction_properties[$i]['action'] . "</a></td>";
         } else {
             print "<td>" . $restriction_properties[$i]['action'] . "</a></td>";
         }
         print "<td>" . $restriction_properties[$i]['description'] . "</td>";
         print "<td>" . $restriction_properties[$i]['policy'] . "</td>";
         print "<td>" . $restriction_properties[$i]['modified'] . "</td>";
         $active = ($restriction_properties[$i]['active'] == db_get_boolean(true)) ? $PALANG['YES'] : $PALANG['NO'];
         print "<td><a href=\"edit-active-restriction.php?id=" . $restriction_properties[$i]['id'] . "&domain=" . $restriction_properties[$i]['domain'] . "&policy=" . $fPolicy . "\">" . $active . "</a></td>";
         print "<td><a href=\"edit-restriction.php?id=" . $restriction_properties[$i]['id'] . "\">" . $PALANG['edit'] . "</a></td>";
         print "<td><a href=\"delete.php?table=restriction&delete=" . $restriction_properties[$i]['id'] . "&domain=" . $restriction_properties[$i]['domain'] . "&policy=" . $fPolicy . "\" onclick=\"return confirm ('" . $PALANG['confirm'] . $PALANG['pAdminList_admin_restriction'] . ": " . $restriction_properties[$i]['address'] . "')\">" . $PALANG['del'] . "</a></td>";
         print "</tr>\n";
		}
   }

   print "</table>\n";
}
if ($is_superadmin) {
    echo "<p><a href='create-restriction.php'>{$PALANG['pAdminMenu_create_restriction']}</a>";
} 
?>
