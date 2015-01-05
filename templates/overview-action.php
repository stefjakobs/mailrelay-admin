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
if (sizeof ($action_properties) > 0)
{
   print "<table id=\"admin_table\">\n";
   print "   <tr>\n";
   print "      <td colspan=\"6\"><h3>".$PALANG['pAdminList_action_title']."</h3></td>";
   print "   </tr>";
   print "   <tr class=\"header\">\n";
   print "      <td>" . $PALANG['pAdminList_action_action'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_action_description'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_action_restriction'] . "</td>\n";
   print "   </tr>\n";

   foreach(array_keys($action_properties) as $i)
   {
      if ((is_array ($action_properties) and sizeof ($action_properties) > 0))
      {
         print "   <tr class=\"hilightoff\" onMouseOver=\"className='hilighton';\" onMouseOut=\"className='hilightoff';\">\n";
         print "<td valign=\"top\">" . $action_properties[$i]['action'] . "</a></td>";
         print "<td valign=\"top\">" . $action_properties[$i]['description'] . "</td>";
         print "<td valign=\"top\">" . $action_properties[$i]['address'] . "</td>";
         print "</tr>\n";
		}
   }

   print "</table>\n";
}
?>
