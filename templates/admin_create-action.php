<?php if( !defined('POSTFIXADMIN') ) die( "This file cannot be used standalone." ); ?>
<div id="edit_form">
<form name="create_action" method="post">
<table>
   <tr>
      <td colspan="3"><h3><?php print $PALANG['pAdminCreate_action_welcome']; ?></h3></td>
   </tr>
   <tr>
      <td colspan="3"><?php print $PALANG['pAdminCreate_action_explanation']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pAdminCreate_action_action'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fAction" value="<?php print $tAction; ?>" /></td>
      <td><?php print $PALANG['pAdminCreate_action_action_text']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pAdminCreate_action_description'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fDescription" value="<?php print $tDescription; ?>" /></td>
      <td>&nbsp;</td>
   </tr>
   <tr>
      <td colspan="3" class="hlp_center"><input class="button" type="submit" name="submit" value="<?php print $PALANG['pAdminCreate_action_button']; ?>" /></td>
   </tr>
   <tr>
      <td colspan="3" class="standout"><?php print $tMessage; ?></td>
   </tr>
</table>
</form>
</div>
