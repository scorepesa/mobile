<?php

class MybetsController extends ControllerBase
{
    public function IndexAction() {
    	$betID = $this->request->get('id','int');
  
		if(!is_numeric($betID))
  			$betID = 0;

  	  	$id = $this->session->get('auth')['id'];

        if ($id) {
      	$myBets = $this->rawSelect("select b.bet_id,b.created, b.total_odd, jb.jackpot_bet_id, total_games as total_matches, b.bet_message,b.bet_amount,b.possible_win,b.status, if(FIND_IN_SET(b.status,(select group_concat(status) from bet_slip where bet_id = b.bet_id)), b.status, 1) xstatus from bet b inner join bet_slip s on b.bet_id=s.bet_id left join jackpot_bet jb on jb.bet_id=b.bet_id where b.profile_id=$id group by s.bet_id order by b.created desc limit 30;");
    
      	$title = "Bets";

          $this->tag->setTitle($title);

      	$this->view->setVars(["myBets"=>$myBets]);
      }else{
        $this->flashSession->error($this->flashError('You do not have permission to view this page'));
                $this->response->redirect('login?ref=mybets');
                // Disable the view to avoid rendering
                $this->view->disable();
      }
    }
    
    public function ShowAction() {
      $betID = $this->request->get('id','int');
      $myBet = $this->rawSelect("select b.bet_id,b.created, total_games as total_matches, b.bet_message,b.bet_amount,b.possible_win,b.status, b.profile_id, if(FIND_IN_SET(b.status,(select group_concat(status) from bet_slip where bet_id = b.bet_id)), b.status, 1) xstatus from bet b inner join bet_slip s on b.bet_id=s.bet_id where b.bet_id='$betID' group by s.bet_id order by b.created desc limit 1");
    		$myBet = $myBet['0'];
      $betDetails = $this->rawSelect("select b.bet_id, b.created, m.start_time, b.bet_amount, possible_win, b.win, b.status, m.game_id, m.away_team, (select winning_outcome from outcome where parent_match_id=s.parent_match_id and sub_type_id=45 and is_winning_outcome =1) as ft_score, m.home_team, s.odd_value, s.sub_type_id, s.bet_pick, group_concat(o.winning_outcome) as winning_outcome, concat(t.name,' ',s.special_bet_value) as bet_type from bet b inner join bet_slip s on s.bet_id = b.bet_id inner join `match` m on m.parent_match_id = s.parent_match_id inner join odd_type t on (s.sub_type_id=t.sub_type_id and s.live_bet = t.live_bet and s.parent_match_id = t.parent_match_id) left join outcome o on (o.parent_match_id = s.parent_match_id and s.sub_type_id = o.sub_type_id and s.special_bet_value = o.special_bet_value and s.live_bet = o.live_bet and  o.is_winning_outcome=1) where s.bet_id = '$betID' group by s.bet_slip_id, s.special_bet_value order by b.bet_id desc");
      $this->view->setVars(["betDetails"=>$betDetails,'myBet'=>$myBet]);                
    }
}

?>