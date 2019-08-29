<table class="mybets">
  <th class="title">My Account</th>
  <table class="terms">
  <tr>
    <td colspan="2" class="text-center"><b>{{session.get('auth')['mobile']}}</b><hr></td>
  </tr>

  <tr>
    <td><b>Bal:</b> Ksh {{user['balance']}}</td>
    <td><b>Bonus:</b> Ksh. {{user['bonus_balance']}}</td>
  </tr>
 <!--  <tr>
    <td><b>ScorePesa points:</b> {{user['points']}}</td>
  </tr> -->
  
  </table>
</table>
<table class="full-width profile">
  <tr class="menu">
    <td class="text"><a href="{{url('deposit')}}">Deposit</a></td>
  </tr>
  <tr class="menu">
    <td class="text"><a href="{{url('withdraw')}}">Withdraw</a></td>
  </tr>
</table>