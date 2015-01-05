<?php if( !defined('POSTFIXADMIN') ) die( "This file cannot be used standalone." ); ?>
<div id="edit_form">
<form name="create_transport" method="post">
<table>
   <tr>
      <td colspan="3"><h3><?php print $PALANG['pAdminCreate_transport_welcome']; ?></h3></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pAdminCreate_transport_transport'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fNexthop" value="<?php print $tNexthop; ?>" /></td>
      <td><?php print $PALANG['pAdminCreate_transport_transport_text']; ?></td>
   </tr>
   <tr>
      <td><?php print $PALANG['pAdminCreate_transport_description'] . ":"; ?></td>
      <td><input class="flat" type="text" name="fDescription" value="<?php print $tDescription; ?>" /></td>
      <td>&nbsp;</td>
   </tr>
   <tr>
      <td colspan="3" class="hlp_center"><input class="button" type="submit" name="submit" value="<?php print $PALANG['pAdminCreate_transport_button']; ?>" /></td>
   </tr>
   <tr>
      <td colspan="3" class="standout"><?php print $tMessage; ?></td>
   </tr>
</table>
</form>
</div>
