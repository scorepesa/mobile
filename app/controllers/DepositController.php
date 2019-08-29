<?php

class DepositController extends ControllerBase
{
    public function IndexAction() {
      
    }

    public function topupAction()
    {
        if($this->session->get('auth'))
    	{
            $amount = $this->request->getPost('amount', 'int');
        
                if ($amount<10) {
                    $this->flashSession->error($this->flashError('Sorry, minimum top up amount is Ksh. 10'));
                    return $this->response->redirect('deposit');
                    $this->view->disable();
                }elseif ($amount>70000) {
                    $this->flashSession->error($this->flashError('Sorry, maximum top up amount is Ksh. 70,000'));
                    return $this->response->redirect('deposit');
                    $this->view->disable();
                }else{
                    $mobile = $this->session->get('auth')['mobile'];
            
                    $push = "msisdn=$mobile&amount=$amount";
            
                    $this->topup($push);
            
                    $this->flashSession->error($this->flashSuccess('Deposit request is sent to your phone, Kindly enter password from your phone to complete MPESA deposit online.'));
            
                    $this->response->redirect('deposit');
                    // Disable the view to avoid rendering
                    $this->view->disable();
                }
        }else{
            $this->response->redirect('login');
            // Disable the view to avoid rendering
            $this->view->disable();
        }
    }
    
}

?>
