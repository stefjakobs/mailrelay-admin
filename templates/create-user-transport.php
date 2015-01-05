<?php if( !defined('POSTFIXADMIN') ) die( "This file cannot be used standalone." ); ?>
<div id="edit_form">
<form name="create_user_transport" method="post">
<table>
   <tr>
      <td colspan="4"><h3><?php print $PALANG['pCreate_user_welcome']; ?></h3></td>
   </tr>
   <tr>
      <td colspan="4"><?php print $PALANG['pCreate_user_explanation']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pCreate_user_localpart'] . ":"; ?></td>
      <td colspan="2"><input class="flat" type="text" name="fLocalpart" value="<?php print $tLocalpart; ?>" /></td>
      <td><?php print $PALANG['pCreate_user_localpart_text']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pCreate_user_domain'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fSubdomain" value="<?php print $tSubdomain; ?>" /></td>
      <td><select class="flat" name="fDomain">
      <?php
      while ($row = db_array ($tDomains['result']))
      {
         if ($row['domain'] == $tDomain)
         {
            print "<option value=\"" . $row['domain'] . "\" selected>" . $row['domain'] . "</option>\n";
         }
         else
         {
            print "<option value=\"" . $row['domain'] . "\">" . $row['domain'] . "</option>\n";
         }
      }
      ?>
      </select>
      </td>
      <td><?php print $PALANG['pCreate_user_domain_text']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pCreate_user_description'] . ":"; ?></td>
      <td colspan="2"><input class="flat" type="text" name="fDescription" value="<?php print $tDescription; ?>" /></td>
      <td>&nbsp;</td>
   </tr>
   <tr>
      <td><?php print $PALANG['pCreate_user_relay'] . ":"; ?></td>
      <td colspan="2"><select class="flat" name="fNexthop_id">
      <?php
      while ($row = db_array ($tNexthops['result'])) 
      {
         if ($row['id'] == $tNexthop_id)
         {
            print "<option value=\"" . $row['id'] . "\" selected>" . $row['nexthop'] . "</option>\n";
         }
         else
         {
            print "<option value=\"" . $row['id'] . "\">" . $row['nexthop'] . "</option>\n";
         }
      }
      ?>
      </select>
      </td>
      <td><?php print $PALANG['pCreate_user_relay_text']; ?></td>
   </tr>
   <tr>
      <td colspan="4" class="hlp_center"><input class="button" type="submit" name="submit" value="<?php print $PALANG['pCreate_user_button']; ?>" /></td>
   </tr>
   <tr>
      <td colspan="4" class="standout"><?php print $tMessage; ?></td>
   </tr>
</table>
</form>
</div>
