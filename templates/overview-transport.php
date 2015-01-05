<?php if( !defined('POSTFIXADMIN') ) die( "This file cannot be used standalone." ); ?>
<div id="overview">
<form name="overview" method="get">
<select name="fUsername" onChange="this.form.submit();">
<?php
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
<form name="search" method="post" action="search.php"><?php print $PALANG['pSearch']; ?>:
<input type="textbox" name="search" size="10" />
</form>
</div>

<?php 
if (sizeof ($transport_properties) > 0)
{
   print "<table id=\"overview_table\">\n";
   print "   <tr>\n";
   print "      <td colspan=\"3\"><h3>".$PALANG['pOverview_transport_title']."</h3></td>";
   print "   </tr>";
   print "   <tr class=\"header\">\n";
   print "      <td>" . $PALANG['pAdminList_transport_transport'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_transport_description'] . "</td>\n";
   print "      <td>" . $PALANG['pAdminList_transport_domains'] . "</td>\n";
   print "   </tr>\n";

   foreach(array_keys($transport_properties) as $i)
   {
      if ((is_array ($transport_properties) and sizeof ($transport_properties) > 0))
      {
         print "   <tr class=\"hilightoff\" onMouseOver=\"className='hilighton';\" onMouseOut=\"className='hilightoff';\">\n";
         print "<td>" . $transport_properties[$i]['nexthop'] . "</a></td>";
         print "<td>" . $transport_properties[$i]['description'] . "</td>";
         print "<td>" . $transport_properties[$i]['address'] . "</td>";
         print "</tr>\n";
      }
   }

   print "</table>\n";
}
?>
