<?php if( !defined('POSTFIXADMIN') ) die( "This file cannot be used standalone." ); ?>
<div id="edit_form">
<form name="create_restriction" method="post">
<table>
   <tr>
      <td colspan="3"><h3><?php print $PALANG['pCreate_restriction_welcome']; ?></h3></td>
   </tr>
   <tr>
      <td colspan="3"><?php print $PALANG['pCreate_restriction_explanation']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pCreate_restriction_address'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fAddress" value="<?php print $tAddress; ?>" /></td>
      <td><?php print $PALANG['pCreate_restriction_address_text']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pCreate_restriction_action'] . ":"; ?></td>
      <td><select class="flat" name="fAction">
      <?php
      while ($row = db_array ($tActions['result']))
      {
         if ($row['id'] == $tAction)
         {
            print "<option value=\"" . $row['id'] . "\" selected>" . $row['action'] . "</option>\n";
         }
         else
         {
            print "<option value=\"" . $row['id'] . "\">" . $row['action'] . "</option>\n";
         }
      }
      ?>
      </select>
      </td>
      <td><?php print $PALANG['pCreate_restriction_action_text']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pCreate_restriction_description'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fDescription" value="<?php print $tDescription; ?>" /></td>
      <td>&nbsp;</td>
   </tr>
   <tr>
      <td><?php print $PALANG['pCreate_restriction_policy'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fPolicy" value="<?php print $tPolicy; ?>" /></td>
      <td><?php print $PALANG['pCreate_restriction_policy_text']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pCreate_restriction_domain'] . ":"; ?></td>
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
      <td><?php print $PALANG['pCreate_restriction_domain_text']; ?></td>
   </tr>
   <tr>
      <td colspan="3" class="hlp_center"><input class="button" type="submit" name="submit" value="<?php print $PALANG['pCreate_restriction_button']; ?>" /></td>
   </tr>
   <tr>
      <td colspan="3" class="standout"><?php print $tMessage; ?></td>
   </tr>
</table>
</form>
</div>
