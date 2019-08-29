<table id="header">
  <tr>
    <td>
      <table width="100%" class="brand">
        <tr>
          <td>
            <table>
              <tr>
                  <td class="logo"><a href="{{ url('') }}"> <img src="{{ url('images/logo.jpg') }}" alt="logo"></a></td>
                  <td class="betslip"><a href="{{ url('betslip') }}"><?php echo $t->_('betslip'); ?><span class="betslip--count slip-counter"><?= $slipCount ?></span></a></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <table width="100%" class="nav">
        <tr>
          <td ><a class="<?= (@$men == 'home') ? 'selected': ''; ?>" href="{{ url('') }}"><?php echo $t->_('Home'); ?></a></td>
          <td><a class="<?= (@$men == 'football') ? 'selected': ''; ?>" href="{{ url('football') }}"><?php echo $t->_('Football'); ?></a></td>
          <td><a class="<?= (@$men == 'sports') ? 'selected': ''; ?>" href="{{ url('sports') }}"><?php echo $t->_('Sports'); ?></a></td>
          {#<td><a class="<?= (@$men == 'jackpot') ? 'selected': ''; ?>" href="{{ url('jackpot') }}"><?php echo $t->_('jackpot'); ?></a></td>#}
          <td><a class="<?= (@$men == 'jackpot') ? 'selected': ''; ?>" href="{{ url('jackpot') }}"><?php echo $t->_('jackpot'); ?></a></td>
           {% if session.get('auth')== null %}
            <td><a class="<?= (@$men == 'login') ? 'selected': ''; ?>" href="{{ url('login') }}"><?php echo $t->_('login'); ?></a></td>
            <td><a class="<?= (@$men == 'register') ? 'selected': ''; ?>" href="{{ url('signup') }}"><?php echo $t->_('register'); ?></a></td>
             {% endif %}


        </tr>
        {% if session.get('auth') != null %}
        <tr class="loggedin">
          <td><a href="{{ url('myaccount') }}"><?php echo $t->_('My Account'); ?></a></td>
          <td><a href="{{ url('mybets') }}"><?php echo $t->_('My Bets'); ?></a></td>
          <td><a href="{{url('logout')}}"><?php echo $t->_('Logout'); ?></a></td>
          <td>&nbsp;</td>
        </tr>
        {% endif %}
      </table>



    </td>
  </tr>
</table>