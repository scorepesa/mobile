
<?php 
      function clean($string) {
         $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
         $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

         return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
      }
?>
<table class="highlights" width="100%">
        <tr>
          <td style="padding: 0;">

<!-- List matches -->

          <?php foreach($matches as $day): ?>

            <?php $theMatch = @$betslip[$day['match_id']]; 
            ?>

            <table class="highlights--item" width="100%" cellpadding="0" cellspacing="0">
              
              <tr>
                <td colspan="10">
                  <table class="league">
                    <tr >
                      <td style="text-align: left;" class="meta"><?php echo $day['competition_name'].", ".$day['category']; ?></td>
                      <td style="text-align: right;"> <?php echo date('d/m H:i', strtotime($day['start_time'])); ?> - <?php echo $t->_('ID'); ?>: <?php echo $day['game_id']; ?></td>
                    </tr>
                  </table>
                </td>
              </tr>
              
              <tr class="game">
                <td colspan="10">
                  <table width="100%">
                    <tr>
                      <td class="clubs" colspan="2">
                        <table><tr><td>
                          <?php echo $day['home_team']; ?> 
                          </td></tr>
                          <tr><td> 
                            <?php echo $day['away_team']; ?>
                          </td></tr>
                        </table>
                          
                        </td>
                      <td class="sidebet"><a class="<?php if($theMatch && $theMatch['sub_type_id']!=1){echo ' picked';}
             ?>" href="{{ url('match?id=') }}{{day['match_id']}}">+<?php echo $day['side_bets']; ?></a></td>
                    </tr>
                  </table>
                </td>
              </tr>

              <tr><td class="market-head">3 WAY</td></tr>

              <tr class="odds">
                <td class="clubone <?php echo $day['match_id']; ?> <?php
                  echo clean($day['match_id'].$day['sub_type_id'].$day['home_team']);
                     if($theMatch['bet_pick']==$day['home_team'] && $theMatch['sub_type_id']=='1'){
                        echo ' picked';
                     }
                  ?>">
                  <table cellspacing="0" cellpadding="0">
                    <tr>

                      <td class=""><button href="javascript:;" class="" hometeam="<?php echo $day['home_team']; ?>" oddtype="3 Way" bettype='prematch' awayteam="<?php echo $day['away_team']; ?>" oddvalue="<?php echo $day['home_odd']; ?>" target="." odd-key="<?php echo $day['home_team']; ?>" parentmatchid="<?php echo $day['parent_match_id']; ?>" id="<?php echo $day['match_id']; ?>" custom="<?php echo clean($day['match_id'].$day['sub_type_id'].$day['home_team']); ?>" sub-type-id="1" special-value-value="0" onClick="addBet(this.id,this.getAttribute('sub-type-id'),this.getAttribute('odd-key'),this.getAttribute('custom'),this.getAttribute('special-value-value'),this.getAttribute('bettype'),this.getAttribute('hometeam'),this.getAttribute('awayteam'),this.getAttribute('oddvalue'),this.getAttribute('oddtype'),this.getAttribute('parentmatchid'))">
                        <span class="pick"><?= $day['home_team'] ?>
                      </span>
                      <br/>
                      <span class="odd">
                        <?php echo $day['home_odd']; ?>
                       </span>
                      </button>
                    </td>

                    </tr>
                  </table>
                </td>
                <td class="border-td"></td>
                <td class="<?php echo $day['match_id']; ?> <?php
                  echo clean($day['match_id'].$day['sub_type_id'].'draw');
                     if($theMatch['bet_pick']=='draw' && $theMatch['sub_type_id']=='1'){
                        echo ' picked';
                     }
                  ?>">
                  <table>
                    <tr>
                      <td>
                        <table>
                          <tr>
                            <td class=""><button href="javascript:;" class="" hometeam="<?php echo $day['home_team']; ?>" oddtype="3 Way" bettype='prematch' awayteam="<?php echo $day['away_team']; ?>" oddvalue="<?php echo $day['neutral_odd']; ?>" target="javascript:;" odd-key="draw" parentmatchid="<?php echo $day['parent_match_id']; ?>" id="<?php echo $day['match_id']; ?>" custom="<?php echo clean($day['match_id'].$day['sub_type_id']."draw"); ?>" sub-type-id="1" special-value-value="0" onClick="addBet(this.id,this.getAttribute('sub-type-id'),this.getAttribute('odd-key'),this.getAttribute('custom'),this.getAttribute('special-value-value'),this.getAttribute('bettype'),this.getAttribute('hometeam'),this.getAttribute('awayteam'),this.getAttribute('oddvalue'),this.getAttribute('oddtype'),this.getAttribute('parentmatchid'))"><span class="pick">Draw </span><br/><span class="odd"><?php echo $day['neutral_odd']; ?></span></button></td>
                            
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="border-td"></td>
                <td class="clubtwo <?php echo $day['match_id']; ?> <?php
                  echo clean($day['match_id'].$day['sub_type_id'].$day['away_team']);
                     if($theMatch['bet_pick']==$day['away_team'] && $theMatch['sub_type_id']=='1'){
                        echo ' picked';
                     }
                  ?>">
                  <table>
                    <tr>
                      <td class=""><button href="javascript:;" class="" hometeam="<?php echo $day['home_team']; ?>" oddtype="3 Way" bettype='prematch' awayteam="<?php echo $day['away_team']; ?>" oddvalue="<?php echo $day['away_odd']; ?>" target="javascript:;" odd-key="<?php echo $day['away_team']; ?>" parentmatchid="<?php echo $day['parent_match_id']; ?>" id="<?php echo $day['match_id']; ?>" custom="<?php echo clean($day['match_id'].$day['sub_type_id'].$day['away_team']); ?>" sub-type-id="1" special-value-value="0" onClick="addBet(this.id,this.getAttribute('sub-type-id'),this.getAttribute('odd-key'),this.getAttribute('custom'),this.getAttribute('special-value-value'),this.getAttribute('bettype'),this.getAttribute('hometeam'),this.getAttribute('awayteam'),this.getAttribute('oddvalue'),this.getAttribute('oddtype'),this.getAttribute('parentmatchid'))"><span class="pick"> <?php echo $day['away_team']; ?></span><br/><span class="odd"><?php echo $day['away_odd']; ?></span></button></td>
                      
                    </tr>
                  </table>
                </td>
              </tr>
              <?php if($day['double_chance_1x_odd'] > 0): ?>
              <tr><td class="market-head">DOUBLE CHANCE</td></tr>
              <!-- start double chance -->
              <tr class="odds">
                <td class="clubone <?php echo $day['match_id']; ?> <?php
                            echo clean($day['match_id'] . '10' . $day['home_team']. ' or draw');
                            if ($theMatch['bet_pick'] == $day['home_team'] . ' or draw' && $theMatch['sub_type_id'] == 10) {
                                echo ' picked';
                            }
                  ?>">
                  <table cellspacing="0" cellpadding="0">
                    <tr>

                      <td class=""><button href="javascript:;" class="" hometeam="<?php echo $day['home_team']; ?>" oddtype="Double Chance" bettype='prematch' awayteam="<?php echo $day['away_team']; ?>" oddvalue="<?php echo $day['double_chance_1x_odd']; ?>" target="." odd-key="<?php echo $day['home_team']. ' or draw'; ?>" parentmatchid="<?php echo $day['parent_match_id']; ?>" id="<?php echo $day['match_id']; ?>" custom="<?php echo clean($day['match_id']  . '10' . $day['home_team']. ' or draw'); ?>" sub-type-id="10" special-value-value="0" onClick="addBet(this.id,this.getAttribute('sub-type-id'),this.getAttribute('odd-key'),this.getAttribute('custom'),this.getAttribute('special-value-value'),this.getAttribute('bettype'),this.getAttribute('hometeam'),this.getAttribute('awayteam'),this.getAttribute('oddvalue'),this.getAttribute('oddtype'),this.getAttribute('parentmatchid'))">
                        <span class="pick">1 OR X
                      </span>
                      <br/>
                      <span class="odd">
                        <?php echo $day['double_chance_1x_odd']; ?>
                       </span>
                      </button>
                    </td>

                    </tr>
                  </table>
                </td>
                <td class="border-td"></td>
                <td class="<?php echo $day['match_id']; ?> <?php
                            echo clean($day['match_id'] . '10' . 'draw or '.$day['away_team']);
                            if ($theMatch['bet_pick'] == '10' . 'draw or '.$day['away_team'] && $theMatch['sub_type_id'] == 10) {
                                echo ' picked';
                            }
                  ?>">
                  <table>
                    <tr>
                      <td>
                        <table>
                          <tr>
                            <td class=""><button href="javascript:;" class="" hometeam="<?php echo $day['home_team']; ?>" oddtype="Double Chnace" bettype='prematch' awayteam="<?php echo $day['away_team']; ?>" oddvalue="<?php echo $day['double_chance_x2_odd']; ?>" target="javascript:;" odd-key="<?php echo 'draw or '.$day['away_team']; ?>" parentmatchid="<?php echo $day['parent_match_id']; ?>" id="<?php echo $day['match_id']; ?>" custom="<?php echo clean($day['match_id'] . '10' . 'draw or '.$day['away_team']); ?>" sub-type-id="10" special-value-value="0" onClick="addBet(this.id,this.getAttribute('sub-type-id'),this.getAttribute('odd-key'),this.getAttribute('custom'),this.getAttribute('special-value-value'),this.getAttribute('bettype'),this.getAttribute('hometeam'),this.getAttribute('awayteam'),this.getAttribute('oddvalue'),this.getAttribute('oddtype'),this.getAttribute('parentmatchid'))"><span class="pick">X OR 2 </span><br/><span class="odd"><?php echo $day['double_chance_x2_odd']; ?></span></button></td>
                            
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="border-td"></td>
                <td class="clubtwo <?php echo $day['match_id']; ?> <?php
                            echo clean($day['match_id'] . '10' . $day['home_team'] . ' or '.$day['away_team']);
                            if ($theMatch['bet_pick'] == $day['home_team'] . ' or '.$day['away_team'] && $theMatch['sub_type_id'] == 10) {
                                echo ' picked';
                            }
                  ?>">
                  <table>
                    <tr>
                      <td class=""><button href="javascript:;" class="" hometeam="<?php echo $day['home_team']; ?>" oddtype="Double Chance" bettype='prematch' awayteam="<?php echo $day['away_team']; ?>" oddvalue="<?php echo $day['double_chance_12_odd']; ?>" target="javascript:;" odd-key="<?php echo $day['home_team'] . ' or '.$day['away_team']; ?>" parentmatchid="<?php echo $day['parent_match_id']; ?>" id="<?php echo $day['match_id']; ?>" custom="<?php echo clean($day['match_id'] . '10' . $day['home_team'] . ' or '.$day['away_team']); ?>" sub-type-id="10" special-value-value="0" onClick="addBet(this.id,this.getAttribute('sub-type-id'),this.getAttribute('odd-key'),this.getAttribute('custom'),this.getAttribute('special-value-value'),this.getAttribute('bettype'),this.getAttribute('hometeam'),this.getAttribute('awayteam'),this.getAttribute('oddvalue'),this.getAttribute('oddtype'),this.getAttribute('parentmatchid'))"><span class="pick"> 1 OR 2</span><br/><span class="odd"><?php echo $day['double_chance_12_odd']; ?></span></button></td>
                      
                    </tr>
                  </table>
                </td>
              </tr>
              <!-- end double chance market -->
              <?php endif ?>
              <?php if($day['over_25_odd'] > 0): ?>
       
               <tr><td colspan="6" class="market-head">Total OVER/UNDER 2.5</td></tr>
              <!-- start over under 2.5 -->
              <tr class="odds">
                <td colspan=2 class="clubone-50 <?php echo $day['match_id']; ?> <?php
                            echo clean($day['match_id'] . '18' . 'over 2.5');
                            if ($theMatch['bet_pick'] == 'over 2.5' && $theMatch['sub_type_id'] == 18) {
                                echo ' picked';
                            }
                  ?>">
                  <table cellspacing="0" cellpadding="0">
                    <tr>

                      <td class=""><button href="javascript:;" class="" hometeam="<?php echo $day['home_team']; ?>" oddtype="Total" bettype='prematch' awayteam="<?php echo $day['away_team']; ?>" oddvalue="<?php echo $day['over_25_odd']; ?>" target="." odd-key="<?php echo 'over 2.5'; ?>" parentmatchid="<?php echo $day['parent_match_id']; ?>" id="<?php echo $day['match_id']; ?>" custom="<?php echo clean($day['match_id'] . '18' . 'over 2.5'); ?>" sub-type-id="18" special-value-value="2.5" onClick="addBet(this.id,this.getAttribute('sub-type-id'),this.getAttribute('odd-key'),this.getAttribute('custom'),this.getAttribute('special-value-value'),this.getAttribute('bettype'),this.getAttribute('hometeam'),this.getAttribute('awayteam'),this.getAttribute('oddvalue'),this.getAttribute('oddtype'),this.getAttribute('parentmatchid'))">
                        <span class="pick">OVER
                      </span>
                      <br/>
                      <span class="odd">
                        <?php echo $day['over_25_odd']; ?>
                       </span>
                      </button>
                    </td>

                    </tr>
                  </table>
                </td>
                <td class="border-td"></td>
                <td colspan="2" class="clubone-50 <?php echo $day['match_id']; ?> <?php
                            echo clean($day['match_id'] . '18' . 'under 2.5');
                            if ($theMatch['bet_pick'] == 'under 2.5' && $theMatch['sub_type_id'] == 18) {
                                echo ' picked';
                            }
                  ?>">
                  <table>
                    <tr>
                      <td>
                        <table>
                          <tr>
                            <td class=""><button href="javascript:;" class="" hometeam="<?php echo $day['home_team']; ?>" oddtype="Total" bettype='prematch' awayteam="<?php echo $day['away_team']; ?>" oddvalue="<?php echo $day['under_25_odd']; ?>" target="javascript:;" odd-key="<?php echo 'under 2.5'; ?>" parentmatchid="<?php echo $day['parent_match_id']; ?>" id="<?php echo $day['match_id']; ?>" custom="<?php echo clean($day['match_id'] . '18' . 'under 2.5'); ?>" sub-type-id="18" special-value-value="2.5" onClick="addBet(this.id,this.getAttribute('sub-type-id'),this.getAttribute('odd-key'),this.getAttribute('custom'),this.getAttribute('special-value-value'),this.getAttribute('bettype'),this.getAttribute('hometeam'),this.getAttribute('awayteam'),this.getAttribute('oddvalue'),this.getAttribute('oddtype'),this.getAttribute('parentmatchid'))"><span class="pick">Under </span><br/><span class="odd"><?php echo $day['under_25_odd']; ?></span></button></td>
                            
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                
              </tr>

              <!-- end over under 2.5 -->
              <?php endif ?>
            </table>


            <?php endforeach; ?>

<!-- List matches end -->

          </td>
        </tr>
<?php if($pages > 1): ?>
        <tr class="spacer"></tr>
        <tr class="pagination">
          <td>
            <table>
              <tr>
              <?php if($page > 1): ?>
              <td class=""><a href="?page=<?= $page-1; ?>">< </a></td>
              <?php endif; ?>
              <?php for ($x = 0; $x <= $pages-1; $x++): ?> 
                <td class="<?php if($x==$page){ echo 'selected';}?>
    "><a href="?page=<?= $x; ?>" ><?= $x+1; ?></a></td>
                <?php endfor; ?>
                <?php if($page == $pages): ?>
                <td class=""><a href="?page=<?= $page+1; ?>">> </a></td>
                <?php endif; ?>
              </tr>
            </table>
          </td>
        </tr>
<?php endif; ?>     
      </table>