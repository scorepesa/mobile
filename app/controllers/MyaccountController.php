<?php

/**
 * Class MyaccountController
 */
class MyaccountController extends ControllerBase
{
    /**
     *
     */
    public function IndexAction()
    {

        $id = $this->session->get('auth')['id'];
        $user = $this->rawSelect("select profile_balance.balance,profile_balance.bonus_balance from profile_settings left join profile_balance on profile_settings.profile_id=profile_balance.profile_id where profile_settings.profile_id='$id' limit 1");
        $user = $user['0'];
        $this->view->setVars([
            'user' => $user,
        ]);
    }

}