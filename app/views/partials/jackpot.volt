<?php 
  function clean($string) {
     $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
     $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

     return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
  }
?>
<table class="jp">
  <th class="title" style="text-align:center">SCOREPESA JACKPOT KSH. 1,000,000 <br> 
  <span class="meta">Closes on {{startTime}}</span>
  
  </th>
  <tr>
    <td>
      <table class="highlights" width="100%">
        <tr>
          <td style="padding: 0;">
          {{ this.flashSession.output() }}
            <!-- List matches -->

          <?php foreach($games as $day): ?>

          <?php
          $theMatch = $theBetslip[$day['match_id']];
          $odds = $day['ht_ft'];
          $odds = explode(',', $odds);

          $home_over = $odds['0'];
          $home_under = $odds['1'];

          $draw_over = $odds['2'];
          $draw_under = $odds['3'];

          $away_over = $odds['4'];
          $away_under = $odds['5'];
          ?>
            <table class="highlights--item" width="100%" cellpadding="0" cellspacing="0">
              
              <tr class="game">
                <td colspan="10">
                  <table width="100%">
                    <tr>
                      <td class="clubs" colspan="2"><?php echo $day['pos']; ?>. <?php echo $day['home_team']; ?> V. <?php echo $day['away_team']; ?></td>
                    </tr>
                  </table>
                </td>
              </tr>

              <tr>
                <td colspan="10">
                  <table class="league">
                    <tr >
                      <td style="text-align: center;" class="meta"><?php echo date('d/m H:i', strtotime($day['start_time'])); ?>
                        {%if !jpactive %}<span class="jp-results">RESULTS: <?= $day['winning_outcome']?$day['winning_outcome']:"--" ?> </span>{%endif%}
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <tr>
                <td colspan="10">
                  <table class="league">
                    <tr >
                      <td style="text-align: center;" class="meta">
                        Home
                      </td>
                       <td style="text-align: center;" class="meta">
                        Draw
                      </td>
                       <td style="text-align: center;" class="meta">
                        Away
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
          
              <tr class="odds">
                <td>
                  <table class="over-under-btn">
                  <tr>
                  <td class="clubone <?php echo $day['match_id']; ?>
                      <?php
                        echo clean($day['match_id'] . $day['sub_type_id'] . '1/O');
                        if ($theMatch['bet_pick'] == '1/O' && $theMatch['sub_type_id'] == '37') {
                            echo ' picked';
                        }
                    ?>">
                    <table cellspacing="0" cellpadding="0">
                      <tr>
                            <td class="<?php echo $day['match_id']; ?> 
                               <?php 
                                 echo clean($day['match_id'] . $day['sub_type_id'] . $day['home_team']);
                                     if ($theMatch['bet_pick'] == $day['home_team'] 
                                         && $theMatch['sub_type_id'] == $day['sub_type_id']) {
                                         echo ' picked';
                                     }
                                  ?> ">
                              <button href="javascript:;" class="" 
                              pos="<?= $day['pos']; ?>"
                              hometeam="<?php echo $day['home_team']; ?>" 
                              oddtype="1x2" 
                              bettype='jackpot' 
                              awayteam="<?php echo $day['away_team']; ?>" 
                              oddvalue="<?php echo $home_odd; ?>" 
                              target="javascript:;" 
                              odd-key="<?php echo $day['home_team']; ?>" 
                              parentmatchid="<?php echo $day['parent_match_id']; ?>" 
                              id="<?php echo $day['match_id']; ?>" 
                              custom="<?php echo clean($day['match_id'] . $day['sub_type_id'] . $day['home_team']); ?>" 
                              value="<?php echo $day['sub_type_id']; ?>" 
                              special-value-value="0" 
                              onClick="addBet(this.id, this.value, this.getAttribute('odd-key'), this.getAttribute('custom'), this.getAttribute('special-value-value'), this.getAttribute('bettype'), this.getAttribute('hometeam'), this.getAttribute('awayteam'), this.getAttribute('oddvalue'), this.getAttribute('oddtype'), this.getAttribute('parentmatchid'),this.getAttribute('pos'))">
                              <span class="pick">1</span><span class="odd">
                                <?php echo $home_odd; ?></span></button>
                              </td>
                          </tr>
                        </table>
                    </td>
                    
                    <td class="clubone <?php echo $day['match_id']; ?>
                      <?php
                          echo clean($day['match_id'] . $day['sub_type_id'] . '1/U');
                          if ($theMatch['bet_pick'] == '1/U' && $theMatch['sub_type_id'] == '37') {
                              echo ' picked';
                          } ?>">

                      <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="<?php echo $day['match_id']; ?> <?php
                                echo clean($day['match_id'] . $day['sub_type_id'] . 'draw');
                                if ($theMatch['bet_pick'] == 'draw' && $theMatch['sub_type_id'] == $day['sub_type_id']) {
                                    echo ' picked';
                                }
                                ?> ">
                              <button href="javascript:;" class="" 
                              pos="<?= $day['pos']; ?>"
                              hometeam="<?php echo $day['home_team']; ?>" 
                              oddtype="1x2" bettype='jackpot' 
                              awayteam="<?php echo $day['away_team']; ?>" 
                              oddvalue="<?php echo $neutral_odd; ?>" 
                              custom="<?php echo clean($day['match_id'] . $day['sub_type_id'] . "draw"); ?>" 
                              value="<?php echo $day['sub_type_id']; ?>" 
                              odd-key="draw" 
                              target="javascript:;" 
                              parentmatchid="<?php echo $day['parent_match_id']; ?>" 
                              id="<?php echo $day['match_id']; ?>" 
                              special-value-value="0" 
                              onClick="addBet(this.id, this.value, this.getAttribute('odd-key'), this.getAttribute('custom'), this.getAttribute('special-value-value'), this.getAttribute('bettype'), this.getAttribute('hometeam'), this.getAttribute('awayteam'), this.getAttribute('oddvalue'), this.getAttribute('oddtype'), this.getAttribute('parentmatchid'),this.getAttribute('pos'))">
                              <span class="pick">X</span><span class="odd">
                                <?php echo $neutral_odd; ?></span></button>
                            </td>
                          </tr>
                        </table>
                    </td>

                  </tr>
                </table> <!-- over under btn -->
              </td>
              <td class="border-td"></td>
              <!-- draw and over and under -->
              <td>
                  <table class="draw-under-btn <?php echo $day['match_id']; ?> <?php
                        echo clean($day['match_id'] . $day['sub_type_id'] . $day['away_team']);
                        if ($theMatch['bet_pick'] == $day['away_team'] && $theMatch['sub_type_id'] == $day['sub_type_id']) {
                            echo ' picked';
                        }
                        ?>">
                  <tr>
                  <td class="clubone <?php echo $day['match_id']; ?> <?php
                      echo clean($day['match_id'] . $day['sub_type_id'] . "X/O");
                      if ($theMatch['bet_pick'] == 'X/O' && $theMatch['sub_type_id'] == '37') {
                          echo ' picked';
                      }
                    ?>">
                    <table cellspacing="0" cellpadding="0">
                      <tr>
                            <td class="">
                              <button href="javascript:;" class="" 
                              pos="<?= $day['pos']; ?>"
                              hometeam="<?php echo $day['home_team']; ?>" 
                              oddtype="1x2" bettype='jackpot' 
                              awayteam="<?php echo $day['away_team']; ?>" 
                              oddvalue="<?php echo $away_odd; ?>" 
                              value="<?php echo $day['sub_type_id']; ?>" 
                              custom="<?php echo clean($day['match_id'] . $day['sub_type_id'] . $day['away_team']); ?>"
                              odd-key="<?php echo $day['away_team']; ?>" 
                              target="javascript:;" 
                              parentmatchid="<?php echo $day['parent_match_id']; ?>" 
                              id="<?php echo $day['match_id']; ?>" 
                              special-value-value="0"
                              onClick="addBet(this.id, this.value, this.getAttribute('odd-key'), this.getAttribute('custom'), this.getAttribute('special-value-value'), this.getAttribute('bettype'), this.getAttribute('hometeam'), this.getAttribute('awayteam'), this.getAttribute('oddvalue'), this.getAttribute('oddtype'), this.getAttribute('parentmatchid'),this.getAttribute('pos'))">
                              <span class="pick">2</span><span class="odd">
                                <?php echo $away_odd; ?></span></button>
                            </td>
                          </tr>
                        </table>
                    </td>
                  </tr>
                </table> <!-- end draw and over and under btns -->
              </td>

              </tr>
            </table>

            <?php endforeach; ?>
          

          </td>
        </tr>
        <tr class="spacer"></tr>
      </table>
    </td>
  </tr>
</table>
<div class="placebet">
<?php echo $this->tag->form("betslip/placebet"); ?>
<input type="hidden" id="user_id" name="user_id" value="{{session.get('auth')['id']}}">
<input type="hidden" name="jackpot_type" id="jackpot_type" value="12" >
<input type="hidden" name="src" id="src" value="mobile" >
<input type="hidden" name="stake" id="stake" value="50" >
<input type="hidden" name="total_odd" id="src" value="1" >
<input type="hidden" name="bet_type" id="bet_type" value="jackpot" >
<input type="hidden" name="jackpot_id" id="jackpot_id" value="{{jackpotID}}" >
<input type="hidden" name="jackpot_type" id="jackpot_type" value="{{jackpotType}}" >
 <div class="total-stake"><span class="met">Total Stake: </span><span class="stake-amt">KSH 50</span></div>

{% if session.get('auth') != null %}
<button type="submit" id="place_bet_button" class="place" onclick="fbJackpot()">Place Bet</button>
{% else %}
<a href="{{url('login')}}?ref=jackpot" class="place dark-gray login-button">Login to Bet</a>
{% endif %}
 </form>
</div>