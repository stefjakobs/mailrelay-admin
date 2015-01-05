<?php if( !defined('POSTFIXADMIN') ) die( "This file cannot be used standalone." ); ?>
<div id="overview">
<form name="overview" method="post">
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
<input type="textbox" name="search" size="10">
</form>
</div>

<?php
   print "<table id=\"overview_table\">\n";
   print "   <tr>\n";
   print "      <td colspan=\"5\"><h3>".$PALANG['pOverview_title']."</h3></td>";
   print "   </tr>";
   print "   <tr class=\"header\">\n";
   print "      <td>" . $PALANG['pOverview_get_domain'] . "</td>\n";
   if ($CONF['virtual'] == 'YES') {
      print "      <td>" . $PALANG['pOverview_get_aliases'] . "</td>\n";
      print "      <td>" . $PALANG['pOverview_get_mailboxes'] . "</td>\n";
   }
   if ($CONF['quota'] == 'YES') print "      <td>" . $PALANG['pOverview_get_quota'] . "</td>\n";
   if ($CONF['relay'] == 'YES') print "      <td>" . $PALANG['pOverview_get_relay'] . "</td>\n";
   print "      <td>&nbsp;</td>\n";
   print "   </tr>\n";

   for ($i = 0; $i < sizeof ($list_domains); $i++)
   {
      if ((is_array ($list_domains) and sizeof ($list_domains) > 0))
      {
         $limit = get_domain_properties ($list_domains[$i]);

         if ($limit['aliases'] == 0) $limit['aliases'] = $PALANG['pOverview_unlimited'];
         if ($limit['mailboxes'] == 0) $limit['mailboxes'] = $PALANG['pOverview_unlimited'];
         if ($limit['maxquota'] == 0) $limit['maxquota'] = $PALANG['pOverview_unlimited'];
         if ($limit['aliases'] < 0) $limit['aliases'] = $PALANG['pOverview_disabled'];
         if ($limit['mailboxes'] < 0) $limit['mailboxes'] = $PALANG['pOverview_disabled'];
         if ($limit['maxquota'] < 0) $limit['maxquota'] = $PALANG['pOverview_disabled'];

         print "   <tr class=\"hilightoff\" onMouseOver=\"className='hilighton';\" onMouseOut=\"className='hilightoff';\">\n";
         if ($CONF['virtual'] == 'YES') {
            print "      <td align=right><a href=\"list-virtual.php?domain=" . $list_domains[$i] . "\">" . $list_domains[$i] . "</a></td>\n";
            print "      <td>" . $limit['alias_count'] . " / " . $limit['aliases'] . "</td>\n";
            print "      <td>" . $limit['mailbox_count'] . " / " . $limit['mailboxes'] . "</td>\n";
         } else {
            print "      <td><a href=\"edit-domain-transport.php?domain=" . $list_domains[$i] . "\">" . $list_domains[$i] . "</a></td>\n";
         }
         if ($CONF['quota'] == 'YES') print "      <td>" . $limit['maxquota'] . "</td>\n";
         if ($CONF['relay'] == 'YES') print "      <td align=right>" . $limit['nexthop'] . "</td>\n";
         print "<td><a href=\"edit-domain-transport.php?domain=" . $list_domains[$i] . "\">" . $PALANG['edit'] . "</a></td>";
         print "   </tr>\n";
      }
   }
   print "</table>\n";
?>
