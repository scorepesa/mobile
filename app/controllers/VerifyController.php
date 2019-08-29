<?php

class VerifyController extends ControllerBase
{

    public function indexAction()
    {
        $title = "Verify account";

        $this->tag->setTitle($title);
    }

    public function checkAction()
    {
    	$mobile = $this->request->get('mobile','int');
    	$verification_code = $this->request->get('verification_code','int');

    	if (!$mobile || !$verification_code) {
            	$this->flashSession->error($this->flashError('All fields are required'));
                $this->response->redirect('verify');

		        // Disable the view to avoid rendering
		        $this->view->disable();
        }else{
                $mobile = $this->formatMobileNumber($mobile);
        
                $user = $this->rawSelect("SELECT * from profile where msisdn='$mobile' limit 1");
                $user = $user['0'];

        
                if (!$user) {
                    $this->flashSession->error($this->flashError('User does not exist'));
                    $this->response->redirect('verify');
                    // Disable the view to avoid rendering
                    $this->view->disable();
                }else{
                        $profile_id = $user['profile_id'];
                        $checkUser = $this->rawSelect("SELECT * from profile_settings where profile_id='$profile_id' and verification_code='$verification_code' limit 1");
                        $checkUser = $checkUser['0'];
                
                        if (!$checkUser) {
                            $this->flashSession->error($this->flashError('Invalid verification code'));
                            $this->response->redirect('verify');
                            // Disable the view to avoid rendering
                            $this->view->disable();
                        }else{
                            $data = $this->rawInsert("update profile_settings set status='1' where profile_id='$profile_id'");
                
                            $dat = ['profile_id'=>$profile_id];
                    
                            $exp=time()+3600;
                    
                            $token = $this->generateToken($dat, $exp);
                    
                            $transaction = "token=$token";
                    
                            $bonus = $this->bonus($transaction);
                
                            $this->flashSession->error($this->flashError('Login to access your account'));
                            $this->response->redirect('login');
                            // Disable the view to avoid rendering
                            $this->view->disable();
                        }
                    }
                }
    }

}

