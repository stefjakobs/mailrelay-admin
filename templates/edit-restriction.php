<?php if( !defined('POSTFIXADMIN') ) die( "This file cannot be used standalone." ); ?>
<div id="edit_form">
<form name="edit_restriction" method="post">
<table>
   <tr>
      <td colspan="3"><h3><?php print $PALANG['pEdit_restriction_welcome']; ?></h3></td>
   </tr>
   <tr>
      <td colspan="3"><?php print $PALANG['pEdit_restriction_explanation']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pEdit_restriction_address'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fAddress" value="<?php print htmlspecialchars($tAddress, ENT_QUOTES); ?>" /></td>
      <td><?php print $PALANG['pEdit_restriction_address_text']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pEdit_restriction_description'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fDescription" value="<?php print htmlspecialchars($tDescription, ENT_QUOTES); ?>" /></td>
      <td>&nbsp;</td>
   </tr>
   <tr>
      <td><?php print $PALANG['pEdit_restriction_action'] . ":"; ?></td>
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
      <td><?php print $PALANG['pEdit_restriction_action_text']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pEdit_restriction_policy'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fPolicy" value="<?php print htmlspecialchars($tPolicy, ENT_QUOTES); ?>" /></td>
      <td><?php print $PALANG['pCreate_restriction_policy_text']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pEdit_restriction_domain'] . ":"; ?></td>
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
      <td><?php print $PALANG['pEdit_restriction_domain_text']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pEdit_restriction_active'] . ":"; ?></td>
      <td><?php $checked = (!empty ($tActive)) ? 'checked=checked' : ''; ?>
      <input class="flat" type="checkbox" name="fActive" <?php print $checked; ?> /></td>
      <td>&nbsp;</td>
   </tr>
   <tr>
      <td colspan="3" class="hlp_center"><input type="submit" class="button" name="submit" value="<?php print $PALANG['pEdit_restriction_button']; ?>" /></td>
   </tr>
   <tr>
      <td colspan="3" class="standout"><?php print $tMessage; ?></td>
   </tr>
</table>
</form>
</div>
