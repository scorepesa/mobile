<table class="betdetail">
  <tr class="title">
    <th class="text-left id"> BET ID : <?= $myBet['bet_id'] ?></th>
    <th class="text-right status pending"><?php
       if($myBet['status']==1){
      echo 'Pending';
          }elseif($myBet['status']==5){
                echo '<span class="won">Won</span>';
       }elseif($myBet['status']==3){
      echo 'Lost';
       }elseif($myBet['status']==4){
      echo 'Cancelled';
       }elseif($myBet['status']==9){
      echo 'Jackpot';
       }elseif($myBet['status']==24){
      echo 'Cancelled';
       }else{
      echo 'View';
       }
       ?></th>
  </tr>
  <tr>
    <td colspan="10">
      <table class="summary">
        <th>Date</th>
        <th>Type</th>
        <th>Bet Amount</th>
        <th>Possible Win</th>
        <tr>
          <td><?= date('d/m H:i', strtotime($myBet['created'])) ?></td>
          <td>
          <?php 
            if($myBet['total_matches']>1){
            echo "Multi Bet";
            }else{
              echo "Single Bet";
            }
          ?>
          </td>
          <td><?= $myBet['bet_amount'] ?></td>
          <td><?= $myBet['possible_win'] ?></td>
        </tr>
      </table>
      <span class="pull-left padding-up-down betdetail-event"> Events </span>
      <?php foreach($betDetails as $bet): ?>
      <table class="event">
        <th colspan="10" class="text-left"><span class="dark-gray">#<?= $bet['game_id'] ?></span> : <?= $bet['home_team']." v ".$bet['away_team'] ?></th>
        
        <tr class="detail">
          <td class="border-right border-bottom">
            <table>
              <tr>
                <td class="text-left">
                  <span class="dark-gray">Date</span>
                </td>
                <td class="text-right">
                  <span class="theme-color"><?= date('d/m H:i', strtotime($bet['start_time'])) ?></span>
                </td>
              </tr>
            </table>
          </td>
          <td class="border-left border-bottom">
            <table>
              <tr>
                <td class="text-left">
                  <span class="dark-gray">Pick</span>
                </td>
                <td class="text-right">
                  <span class="theme-color"><?= $bet['bet_pick'] ?></span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr class="detail">
          <td class="border-right border-top">
            <table>
              <tr>
                <td class="text-left">
                  <span class="dark-gray">Odds</span>
                </td>
                <td class="text-right">
                  <span class="theme-color"><?= $bet['odd_value'] ?></span>
                </td>
              </tr>
            </table>
          </td>
          <td class="border-left border-top">
              <table>
              <tr>
                <td class="text-left">
                  <span class="dark-gray">Outcome</span>
                </td>
                <td class="text-right">
                  <span class="theme-color"><?php 
        if(empty($bet['winning_outcome']))
          echo "Pending";
        else
          echo $bet['winning_outcome'];

     ?></span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr class="detail">
          
          <td class="border-right border-top">
            <table>
              <tr>
                <td class="text-left">
                  <span class="dark-gray">Type</span>
                </td>
                <td class="text-right">
                  <span class="theme-color"><?= $bet['bet_type'] ?></span>
                </td>
              </tr>
            </table>
          </td>
          <td class="border-left border-top">
              <table>
              <tr>
                <td class="text-left">
                  <span class="dark-gray">Results</span>
                </td>
                <td class="text-right">
                  <span class="theme-color"><?= $bet['ft_score'] ?></span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <?php endforeach; ?>

    </td>
  </tr>
</table>