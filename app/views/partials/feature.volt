<?php 
$sub_type_id=''; 
  function clean($string) {
     $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
     $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

     return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
  }
?>
<table class="feature">
  <th class="title">
    <table width="100%">
      <tr class="game">
        <td colspan="3"><?php echo $matchInfo['home_team']." vs ".$matchInfo['away_team']; ?></td>
      </tr>
      <tr class="spacer"></tr>
      <tr class="details dark-gray">
        <td class="text-left"><?= $matchInfo['competition_name'].", ".$matchInfo['category']; 
?></td>
        <td class="text-center">Game ID : <?= $matchInfo['game_id']; ?></td>
        <td><span class="text-left"><?= date('d/m', strtotime($matchInfo['start_time'])); ?></span> <span><?= date('g:i a', strtotime($matchInfo['start_time'])); ?></span> </td>
      </tr>
    </table>
  </th>
  
</table>
<div class="sidebets"> 
<?php foreach($subTypes as $bt): ?>
<?php 
$theMatch = @$betslip[$bt['match_id']];
?>
<?php if($bt['name']): ?>
<div class="sidebet-card">
<?php if($sub_type_id!=$bt['sub_type_id']): ?>
   <div class="sidebet-header">{{bt['name']}}</div>
<?php endif; ?>

<?php 
if($bt['name']){
$sub_type_id=$bt['sub_type_id']; 
$special_bet_value=$bt['special_bet_value']; 
$special_bet_display = '';
if($special_bet_value){
  $special_bet_display = '('.$special_bet_value.')';
}
}
?>

<?php if($sub_type_id==$bt['sub_type_id']): ?>

<button class="sidebet-odd <?= @$style[$sub_type_id]; ?> <?php echo $bt['match_id']; ?> <?php echo clean($bt['match_id'].$bt['sub_type_id'].$bt['odd_key'].$special_bet_value); 
                    if($theMatch['bet_pick']==$bt['odd_key'] && $theMatch['sub_type_id']==$bt['sub_type_id'] && $theMatch['special_bet_value']==$bt['special_bet_value']){
                        echo ' picked';
                     }
                  ?>" oddtype="<?= $bt['name'] ?>" parentmatchid="<?php echo $matchInfo['parent_match_id']; ?>" bettype='prematch' hometeam="<?php echo $matchInfo['home_team']; ?>" awayteam="<?php echo $matchInfo['away_team']; ?>" oddvalue="<?php echo $bt['odd_value']; ?>" custom="<?php echo clean($bt['match_id'].$bt['sub_type_id'].$bt['odd_key'].$special_bet_value); ?>" target="javascript:;" id="<?php echo $bt['match_id']; ?>" odd-key="<?php echo $bt['odd_key']; ?>" value="<?php echo $bt['sub_type_id']; ?>" special-value-value="<?= $special_bet_value ?>" onClick="addBet(this.id,this.value,this.getAttribute('odd-key'),this.getAttribute('custom'),this.getAttribute('special-value-value'),this.getAttribute('bettype'),this.getAttribute('hometeam'),this.getAttribute('awayteam'),this.getAttribute('oddvalue'),this.getAttribute('oddtype'),this.getAttribute('parentmatchid'))"><span class="side-label"> <?php echo $bt['display']; ?></span> <span class="odd-value"> <?php echo $bt['odd_value']; ?></span> </button> 
<?php endif; ?>
</div>
<?php endif; ?>
<?php endforeach; ?>
</div>