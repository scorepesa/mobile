
<?php

use Phalcon\Session\Bag as SessionBag;
use Phalcon\Http\Response;

class BetslipController extends ControllerBase
{
    public function IndexAction()
    {
        $stake = $this->session->get('stake') ?: 50;
        $this->view->setVars(['stake' => $stake]);
    }

    public function stakeAction()
    {
        $stake = $this->request->get('stake') ?: 50;

        if ($stake < 30) {
            $stake = 30;
        }

        $this->session->set('stake', $stake);

        $this->flashSession->error($this->flashSuccess('Possible win updated'));

        $this->response->redirect('betslip');
        // Disable the view to avoid rendering
        $this->view->disable();
    }

    public function addAction()
    {
        $match_id = $this->request->getPost('match_id', 'int');
        $bet_pick = $this->request->getPost('odd_key');
        $sub_type_id = $this->request->getPost('sub_type_id', 'int');
        $special_bet_value = $this->request->getPost('special_bet_value');
        $bet_type = $this->request->getPost('bet_type') ?: 'prematch';
        $home_team = $this->request->getPost('home');
        $away_team = $this->request->getPost('away');
        $odd_value = $this->request->getPost('odd');
        $odd_type = $this->request->getPost('oddtype');
        $parent_match_id = $this->request->getPost('parentmatchid', 'int');
        $pos = $this->request->getPost('pos');

        if ($special_bet_value == '0') {
            $special_bet_value = '';
        }

        if ($bet_type == 'live') {
            $this->session->set("betslip", '');
        }

        $status = 1;

        $betslip[] = '';

        unset($betslip);

        if ($this->session->has("betslip")) {
            $betslip = $this->session->get("betslip");
        }

        if ($betslip["$match_id"] && $betslip["$match_id"]["bet_pick"] == $bet_pick) {
            unset($betslip["$match_id"]);
            $status = 0;
        } else {
            $betslip["$match_id"] = [
                'match_id'          => $match_id,
                'bet_pick'          => $bet_pick,
                'sub_type_id'       => $sub_type_id,
                'special_bet_value' => $special_bet_value,
                'bet_type'          => $bet_type,
                'home_team'         => $home_team,
                'away_team'         => $away_team,
                'odd_value'         => $odd_value,
                'odd_type'          => $odd_type,
                'parent_match_id'   => $parent_match_id,
                'pos'               => $pos,
            ];
        }

        $this->session->set("betslip", $betslip);

        if($bet_type == 'prematch'){
            $bets = $this->betslip('prematch');
        }else{

            $bets = $this->betslip('jackpot');
        }

        $count = sizeof($bets);

        $data = [
            'status'  => $status,
            'total'   => $count,
            'betslip' => $betslip,
        ];

        $response = new Response();
        $response->setStatusCode(201, "OK");
        $response->setHeader("Content-Type", "application/json");

        $response->setContent(json_encode($data));

        return $response;
    }

    public function removeAction()
    {
        $match_id = $this->request->getPost('match_id', 'int');
        $betslip = $this->session->get("betslip");

        unset($betslip["$match_id"]);

        $this->session->set("betslip", $betslip);

        $this->flashSession->error($this->flashSuccess('Match successfully removed'));
        $this->response->redirect('betslip');
        // Disable the view to avoid rendering
        $this->view->disable();
    }

    public function clearslipAction()
    {

        $this->session->remove("betslip");

        $data = '1';

        $src = $this->request->getPost('src', 'string');
        $this->flashSession->error($this->flashSuccess('Betslip cleared'));
        $this->response->redirect('betslip');
        // Disable the view to avoid rendering
        $this->view->disable();

    }

    public function placebetAction()
    {
        $user_id = $this->request->getPost('user_id', 'int');
        $bet_amount = $this->request->getPost('stake', 'float');
        $total_odd = $this->request->getPost('total_odd', 'int');
        $possible_win = $bet_amount * $total_odd;
        $src = $this->request->getPost('src', 'string') ?: 'internet';
        $betslip = $this->session->get('betslip');
        $endCustomerIP = $this->getClientIP();
        $account = $this->request->getPost('account', 'int', 1);
        $bet_type = $this->request->getPost('bet_type', 'string') ?: 'prematch';

        if ($account !== 1) {
            $account = 0;
        }

        $response = new Response();
        $response->setStatusCode(201, "OK");
        $response->setHeader("Content-Type", "application/json");

        if (!$user_id || !$bet_amount || !$total_odd || !$possible_win) {
            $this->flashSession->error($this->flashError('All fields are required'));
            $this->response->redirect('betslip');
            // Disable the view to avoid rendering
            $this->view->disable();
        } else {

            if ($bet_amount < 50) {
                if ($src == 'mobile') {
                    $this->flashSession->error($this->flashMessages('Bet amount should be at least KES. 50'));
                    $this->response->redirect('betslip');
                    // Disable the view to avoid rendering
                    $this->view->disable();
                } else {
                    $data = [
                        "status_code" => 421,
                        "message"=> "Bet amount should be at least KES. 50",
                    ];
                    $response->setContent(json_encode($data));

                    return $response;
                    $this->view->disable();
                }
            } else {
                $checkUser = $this->rawSelect("SELECT * from profile where profile_id='$user_id' limit 1");

                $checkUser = $checkUser['0'];

                $mobile = $checkUser['msisdn'];

                $totalMatch = sizeof($betslip);

                $slip = [];

                $betslip = $this->array_msort($betslip, ['pos' => SORT_ASC]);

                foreach ($betslip as $match) {
                    $parent_match_id = $match['parent_match_id'];
                    $bet_pick = $match['bet_pick'];
                    $odd_value = $match['odd_value'];
                    $sub_type_id = $match['sub_type_id'];
                    $home_team = $match['home_team'];
                    $away_team = $match['away_team'];
                    $special_bet_value = $match['special_bet_value'];

                    $thisMatch = '';

                    if ($away_team == 'na') {
                        $thisMatch = [
                            "sub_type_id"            => $sub_type_id,
                            "betrader_competitor_id" => $special_bet_value,
                            "odd_value"              => $odd_value,
                            "parent_outright_id"     => $parent_match_id,
                        ];
                    } else {
                        $thisMatch = [
                            "sub_type_id"       => $sub_type_id,
                            "special_bet_value" => $special_bet_value,
                            "pick_key"          => $bet_pick,
                            "odd_value"         => $odd_value,
                            "parent_match_id"   => $parent_match_id,
                        ];
                    }


                    $slip[] = $thisMatch;
                }

                $bet = [
                    "bet_string"     => 'sms',
                    "app_name"       => "LITE",
                    "possible_win"   => $possible_win,
                    "profile_id"     => $user_id,
                    "stake_amount"   => $bet_amount,
                    "bet_total_odds" => $total_odd,
                    "deviceID"       => "6489000GX",
                    "endCustomerIP"  => $endCustomerIP,
                    "channelID"      => $src,
                    "slip"           => $slip,
                    "account"        => $account,
                    'msisdn'         => $mobile,
                ];


                if($bet_type == 'jackpot'){
                    $placeB = $this->betJackpot($bet);
                }else{

                    $placeB = $this->bet($bet);
                }

                if ($src == 'mobile') {
                    $feedback = $placeB['message'];
                    if ($placeB['status_code'] == 201) {
                        $feedback = $placeB['message'];
                        $this->betslipUnset('prematch');
                        $this->betslipUnset('jackpot');
                        $this->flashSession->success($this->flashSuccess($feedback));
                    } else {
                        $this->flashSession->error($this->flashError($feedback));
                    }

                    $this->response->redirect('betslip');
                    // Disable the view to avoid rendering
                    $this->view->disable();

                } else {
                    if ($placeB['status_code'] == 201) {
                        $this->deleteReference();
                        $this->session->remove("betslip");
                        $this->reference();
                    }

                    $response->setContent(json_encode($placeB));

                    return $response;
                    $this->view->disable();
                }
            }
        }

    }


    public function betJackpotAction() {

        $reference_id = $this->reference();
        $user_id = $this->request->getPost('user_id', 'int');
        $jackpot_id = $this->request->getPost('jackpot_id', 'int');
        $jackpot_type = $this->request->getPost('jackpot_type', 'int');
        $total_matches = $this->request->getPost('total_matches', 'int');
        $bet_amount = $this->request->getPost('stake', 'int');
        $total_odd = $this->request->getPost('total_odd', 'int');
        $possible_win = $bet_amount * $total_odd;
        $src = $this->request->getPost('src', 'string');
        $betslip = $this->session->get('betslip');
        $bet_type = 'jackpot';

        $response = new Response();
        $response->setStatusCode(201, "OK");
        $response->setHeader("Content-Type", "application/json");
        $src == 'mobile';
        if (!$user_id || !$bet_amount) {
            if ($src == 'mobile') {
                $this->flashSession->error($this->flashMessages('All fields are required'));
                $this->response->redirect('betslip');
                $this->view->disable();
            } else {
                $data = ["status_code" => 421, "message" => "All fields are required --------"];
                $response->setContent(json_encode($data));
                return $response;
                $this->view->disable();
            }
        } else {
            if ($bet_amount < 50) {
                if ($src == 'mobile') {
                    $this->flashSession->error($this->flashMessages('Bet amount should be at least Ksh. 50'));
                    $this->response->redirect('betslip');
                    // Disable the view to avoid rendering
                    $this->view->disable();
                } else {
                    $data = ["status_code" => 421, "message" => "Bet amount should be at least Ksh. 50"];
                    $response->setContent(json_encode($data));
                    return $response;
                    $this->view->disable();
                }
            } else {

                $matches = [];

                $betslip = $this->session->get("betslip");

                foreach ($betslip as $slip) {
                    if ($slip['bet_type'] == $bet_type) {
                        $matches[$slip['match_id']] = $slip;
                    }
                }
        
                $matches = $this->array_msort($matches, ['pos' => SORT_ASC]);

                $totalMatch = sizeof($matches);

                if ($totalMatch < $total_matches) {
                    if ($src == 'mobile') {
                        $this->flashSession->error($this->flashMessages('You must select an outcome for all Jackpot Matches'));
                        $this->response->redirect('betmobile');
                        // Disable the view to avoid rendering
                        $this->view->disable();
                    } else {
                        $data = [
                            "status_code"  => "421",
                            "message"      => "You must select an outcome for all Jackpot Matches",
                            "jackpot_type" => $jackpot_type,
                        ];
                        $response->setContent(json_encode($data));

                        return $response;
                        $this->view->disable();
                    }
                } else {

                     $checkUser = $this->rawSelect("SELECT * from profile where profile_id='$user_id' limit 1");

                    $checkUser = $checkUser['0'];

                    $mobile = $checkUser['msisdn'];

                    $slip = [];

                    foreach ($matches as $match) {
                        $parent_match_id = $match['parent_match_id'];
                        $bet_pick = $match['bet_pick'];
                        $odd_value = $match['odd_value'];
                        $sub_type_id = $match['sub_type_id'];
                        $home_team = $match['home_team'];
                        $away_team = $match['away_team'];
                        $special_bet_value = $match['special_bet_value'];

                        $thisMatch = ["sub_type_id" => $sub_type_id, "special_bet_value" => $special_bet_value, "pick_key" => $bet_pick, "odd_value" => $odd_value, "parent_match_id" => $parent_match_id];

                        $slip[] = $thisMatch;
                    }

                    $bet = ["bet_string" => 'sms',
                        "possible_win" => $possible_win,
                        "profile_id" => $user_id,
                        "jackpot_id" => $jackpot_id,
                        "jackpot_type" => $jackpot_type,
                        "stake_amount" => $bet_amount,
                        "bet_total_odds" => $total_odd,
                        "slip" => $slip];

                        $placeB = $this->betJackpot($bet);

                        if ($placeB['status_code'] == 201) {
                            $message = $placeB['message'];
                            $sms = "msisdn=$mobile&message=$message&short_code=29008&correlator=&message_type=BULK&link_id=";
                            //$this->sendSMS($sms);
                        }

                    if ($src == 'mobile') {
                        $feedback = $placeB['message'];
                        if ($placeB['status_code'] == 201) {
                            $feedback = $placeB['message'];
                            $this->session->remove("betslip");
                            $this->flashSession->success($this->flashSuccess($feedback));
                        } else {
                            $this->flashSession->error($this->flashMessages($feedback));
                        }

                        $this->response->redirect('betmobile');
                        // Disable the view to avoid rendering
                        $this->view->disable();
                    } else {

                        if ($placeB['status_code'] == 201) {
                            $this->deleteReference();
                            $this->session->remove("betslip");
                            $this->reference();
                        }

                        $response->setContent(json_encode($placeB));
                        return $response;
                        $this->view->disable();
                    }
                }
            }
        }
    }

    private function array_msort($array, $cols)
    {
        $colarr = [];
        foreach ($cols as $col => $order) {
            $colarr[$col] = [];
            foreach ($array as $k => $row) {
                $colarr[$col]['_' . $k] = strtolower($row[$col]);
            }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
        }
        $eval = substr($eval, 0, -1) . ');';
        eval($eval);
        $ret = [];
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);
                if (!isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }

        return $ret;
    }

}

?>