<table class="mybets">
  <th class="title">My Bets</th>
  <?php foreach($myBets as $bet): ?>
  <tr class="bet">
    <td>
      <a href="{{ url('mybets/show?id=') }}{{bet['bet_id']}}" class="undecorate">
      <table>
        <tr class="title">
          <td class="id text-left">Bet ID: <span class=""><?= $bet['bet_id'] ?></span></td>
          <td class="status text-right"><?php
       if($bet['xstatus']==1){
      echo 'Pending';
          }elseif($bet['xstatus']==5){
                echo '<span class="won">Won</span>';
       }elseif($bet['xstatus']==3){
      echo 'Lost';
       }elseif($bet['xstatus']==4){
      echo 'Cancelled';
       }elseif($bet['xstatus']==9){
      echo 'Jackpot';
       }elseif($bet['xstatus']==24){
      echo 'Cancelled';
       }else{
      echo 'View';
       }
       ?></td>
        </tr>
        <tr class="detail">
          <td class="border-right border-bottom"><span class="pull-left dark-gray">Date</span><span class="pull-right theme-color"><?= date('d/m H:i', strtotime($bet['created'])) ?></span></td>
          <td class="border-left border-bottom"><span class="pull-left dark-gray">Type</span><span class="pull-right theme-color"><?php 
      if($bet['jackpot_bet_id']){
      echo "Jackpot";
      }else{
      if($bet['total_matches']>1){
      echo "Multi Bet";
      }else{
        echo "Single Bet";
      }
      }
      ?></span></td>
        </tr>
        <tr class="detail">
          <td class="border-right border-top"><span class="pull-left dark-gray">Total Odds</span><span class="pull-right theme-color">{{bet['total_odd']}}</span></td>
          <td class="border-left border-top"><span class="pull-left dark-gray">Possible Win</span><span class="pull-right theme-color">{{bet['possible_win']}}</span></td>
        </tr>
      </table>
      </a>
    </td>
  </tr>
  <?php endforeach; ?>

</table>