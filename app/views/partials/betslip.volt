<?php $totalOdds=1; ?>
<table width="100%" class="betslip">
  <th class="title" colspan="10">BETSLIP</th>
  <tr>
    <td colspan="10">
      {{ this.flashSession.output() }}
<?php
  $matchCount = 0;
  $bonus = 0;
  $bonusOdds = 1;
?>

<?php foreach((array)$betslip as $bet): ?>
  <?php
    if (!$bet){continue;}
    $odd = $bet['odd_value'];

    if($bet['bet_pick']=='x'){
        $pick = 'DRAW';
    }else if($bet['bet_pick']=='2'){
        $pick = $bet['away_team'];
    }else if($bet['bet_pick']=='1'){
        $pick = $bet['home_team'];
    }
    else{
        $pick = $bet['bet_pick'];
    }

    if($bet['odd_value']>=1.6){
    $bonusOdds*=$odd;
    $matchCount++;
    }

    $totalOdds = round($totalOdds*$odd,2);

    ?>
      <table class="bet">
        <tr>
          <td class="padding-up-down" colspan="10">
            <table width="100%">
              <tr>
                <td>
                  <table>
                    <tr class="game">
                      <td>
                      <?php
                      if($bet['away_team']=='na'){
                        echo $bet['home_team']; 
                        }
                        else{
                          echo $bet['home_team']." v ".$bet['away_team'];
                        }

                      ?>


                      </td>
                    </tr>
                    <tr class="type">
                      <td class="dark-gray"><?php echo $bet['odd_type']; ?> (<?php echo $bet['odd_value']; ?>)</td>
                    </tr>
                    <tr class="pick">
                      <td><?= $pick; ?> </td>
                    </tr>
                  </table>
                </td>
                <td class="odd-del">
                  <table style="width:100%;text-align:center;">
                    <tr>
                    <?php echo $this->tag->form("betslip/remove"); ?>
                      <td class="delete">
                      <input type="hidden" name="match_id" value="{{bet['match_id']}}">
                      <button class="remove-match" type="submit" value="submit">X</button>
                      </td>
                    </form>
                    </tr>
                    
                    
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
  <?php endforeach; ?>

    </td>
  </tr>

  <tr class="details">
    <td class="left" colspan="3">
      <table width="100%">
        <tr>
          <td class="dark-gray">Stake after Tax</td>
          <td class="text-right bold"><?= round($stake/1.2,2); ?></td>
        </tr>
        <tr>
          <td class="dark-gray">Total Odds</td>
          <td class="text-right bold"><?= $totalOdds; ?></td>
        </tr>
        <tr>
          <?php $winnings = (($totalOdds*($stake/1.2))<50000)?$totalOdds*($stake/1.2):50000; ?>
          <?php $taxable = $winnings - ($stake/1.2); ?>
          <?php $netWinnings = $winnings - ($taxable*0.2); ?>
          <td class="dark-gray">Possible Win</td>
          <td class="text-right bold"><?= round($winnings,2); ?></td>
        </tr>
        <tr>
          <td class="dark-gray">Withholding Tax (20%)</td>
          <td class="text-right bold"><?= round($taxable*0.2,2); ?></td>
        </tr>
        <tr>
          <td class="dark-gray">Net Possible Win</td>
          <td class="text-right bold"><?= round($netWinnings,2); ?></td>
        </tr>
      </table>
    </td>
    <td class="right">
        <table width="100%">
          <tr>
            <?php echo $this->tag->form("betslip/stake"); ?>
            <td class="dark-gray">Amount</td>
            <td><input type="text" class="stake" name="stake" value="{{stake}}"></td>
              <td><button type="submit" class="update">Update</button></td>
            </form>
          </tr>
          
          <tr>
            <td colspan="10">
            <?php echo $this->tag->form("betslip/placebet"); ?>
            <input type="hidden" name="stake" value="{{stake}}">
            <input type="hidden" name="src" value="mobile">
            <input type="hidden" id="user_id" name="user_id" value="{{session.get('auth')['id']}}">
            <input type="hidden" id="total_odd_m" name="total_odd" value="<?php echo $totalOdds; ?>">
            {% if session.get('auth') != null %}
            <button type="submit" class="place" onclick="fbPurchase()">Place Bet</button>
            {% else %}
            <a href="{{url('login')}}?ref=betslip" class="place dark-gray login-button">Login to Bet</a>
            {% endif %}
            </form>
            </td>
          </tr>
        </table>
        </td>
  </tr>
  <tr class="spacer"></tr>
  <tr>
    <td class="" colspan="10">
  <?php echo $this->tag->form("betslip/clearslip"); ?>
  <input type="hidden" name="src" value="mobile">
    <button type="submit" class="delete-all">Delete All</button>
  </form>
    </td>
  </tr>
</table>