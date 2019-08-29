<?php echo $this->tag->form(""); ?>
  <table width="100%">
    <tr>
      <td>
        <input type="text" name="keyword" placeholder="<?php echo $t->_('Team, Competition or GameID'); ?>" class="top--search--input">
      </td>
      <td>
        <button type="submit" class="top--search--button"><?php echo $t->_('Search'); ?></button>
      </td>
    </tr>
  </table>
</form>