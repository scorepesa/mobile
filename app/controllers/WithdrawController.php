<?php

class WithdrawController extends ControllerBase
{
    public function IndexAction() {
    }

    public function withdrawalAction()
    {
        $amount = $this->request->getPost('amount', 'int');

        if ($amount<50) {
           $this->flashSession->error($this->flashError('Sorry, minimum withdraw amount is Ksh. 50.'));
            return $this->response->redirect('withdraw');
            $this->view->disable();
        }elseif ($amount>70000) {
            $this->flashSession->error($this->flashError('Sorry, maximum withdraw amount is Ksh. 70,000.'));
            return $this->response->redirect('deposit');
            $this->view->disable();
        }else{
                $mobile = $this->session->get('auth')['mobile'];
        
                $data = ['amount'=>$amount,'msisdn'=>$mobile];
        
                $exp=time()+3600;
        
                $token = $this->generateToken($data, $exp);
        
                $transaction = "token=$token";
        
                $withdraw = $this->withdraw($transaction);
        
                $this->flashSession->error($this->flashSuccess('Your withdrawal is being processed, you will receive a confirmation on SMS shortly'));
        
                $this->response->redirect('withdraw');
        
                $this->view->disable();}
    }
    
}

?>