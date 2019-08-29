<table id="contacts">
    <tr>
        <td>
            <p><span><?php echo $t->_('M-pesa Paybill'); ?>: </span><?php echo $t->_('paybill'); ?></p>
            <p><span><?php echo $t->_('Account'); ?>: </span><?php echo $t->_('app_name'); ?></p>
            <p><span><?php echo $t->_('Contacts'); ?>: </span>
                <?php echo $t->_('contacts-list') ?>
            </p>
        </td>
    </tr>
</table>

<table id="footer">

    <tr class="top">
        <td>
            <a href="{{ url('terms') }}"><?php echo $t->_('policy'); ?></a>
        </td>
    </tr>
    <tr class="spacer"></tr>
    <tr>
        <td><?php echo $t->_('licence'); ?></td>
    </tr>
    <tr class="spacer"></tr>
    
</table>