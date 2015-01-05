<?php if( !defined('POSTFIXADMIN') ) die( "This file cannot be used standalone." ); ?>
<div id="edit_form">
<form name="edit_action" method="post">
<table>
   <tr>
      <td colspan="3"><h3><?php print $PALANG['pAdminEdit_action_welcome']; ?></h3></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pAdminEdit_action_action'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fAction" value="<?php print htmlspecialchars($tAction, ENT_QUOTES); ?>" /></td>
      <td>&nbsp;</td>
   </tr>
   <tr>
      <td valign="top"><?php print $PALANG['pAdminEdit_action_restrictions'] . ":"; ?></td>
      <td><?php print $tRestrictions; ?></td>
      <td>&nbsp;</td>
   </tr>
   <tr>
      <td><?php print $PALANG['pAdminEdit_action_description'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fDescription" value="<?php print htmlspecialchars($tDescription, ENT_QUOTES); ?>" /></td>
      <td>&nbsp;</td>
   </tr>
   <tr>
      <td colspan="3" class="hlp_center"><input type="submit" class="button" name="submit" value="<?php print $PALANG['pAdminEdit_action_button']; ?>" /></td>
   </tr>
   <tr>
      <td colspan="3" class="standout"><?php print $tMessage; ?></td>
   </tr>
</table>
</form>
</div>
