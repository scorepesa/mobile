<?php

class SignupController extends ControllerBase
{
	public function initialize()
    {
        $this->tag->setTitle('Sign up');
    }

    public function indexAction()
    {
    	
    }

    public function joinAction(){

        if ($this->request->isPost()) {

            $mobile = $this->request->getPost('mobile', 'int');
            $age = $this->request->getPost('age', 'int');
            $terms = $this->request->getPost('terms', 'int');
            $password = $this->request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');

            if (!$mobile || !$password || !$repeatPassword || !$age || !$terms ) {
            	$this->flashSession->error($this->flashError('All fields are required'));
                $this->response->redirect('signup');

		        // Disable the view to avoid rendering
		        $this->view->disable();
            }else{

            if ($password != $repeatPassword) {
                $this->flashSession->error($this->flashError('Passwords do not match'));
                $this->response->redirect('signup');

		        // Disable the view to avoid rendering
		        $this->view->disable();
            }

            $mobile = $this->formatMobileNumber($mobile);
            $password = $this->security->hash($password);
            $verification_code = substr(number_format(time() * mt_rand(),0,'',''),0,4);

            if (!$mobile) {
                $this->flashSession->error($this->flashError('Invalid mobile number'));
                $this->response->redirect('signup');

                // Disable the view to avoid rendering
                $this->view->disable();
            }else{

            $sms = "Your SCOREPESA account verification code is $verification_code. Enter this to activate your account.";

            $sms = str_replace(' ', '+', $sms);

            $sms = "msisdn=$mobile&message=$sms&short_code=29777&correlator=&message_type=BULK&link_id=";

            $checkProfile = $this->rawSelect("select msisdn,profile_id from profile where msisdn='$mobile' limit 1");
            $checkProfile=$checkProfile['0'];

            if (!$checkProfile) {

            	$profile_id = $this->rawInsert("insert into profile (msisdn,status,created_by,created) values('$mobile','1','Bikosports.com',now())");

            	$insert = $this->rawInsert("insert into profile_settings (profile_id,password,verification_code,created_at) values('$profile_id','$password','$verification_code',now())");

                $send = $this->sendSMS($sms);

                $this->flashSession->error($this->flashError('Please enter the code sent to your phone to verify your account'));
                $this->response->redirect('verify');

                //Disable the view to avoid rendering

                $this->view->disable();

            }else{

            	$profile_id = $checkProfile['profile_id'];

            	$checkUser = $this->rawSelect("select profile_id from profile_settings where profile_id='$profile_id' limit 1");

            	if (!$checkUser) {
            		$insert = $this->rawInsert("insert into profile_settings (profile_id,password,verification_code,created_at) values('$profile_id','$password','$verification_code',now())");

                    $send = $this->sendSMS($sms);

                    $this->flashSession->error($this->flashError('Please enter the code sent to your phone to verify your account'));
                    $this->response->redirect('verify');

                    // Disable the view to avoid rendering
                    $this->view->disable();

            	}else{
            		$this->flashSession->error($this->flashError('Mobile number already in use'));
	                $this->response->redirect('signup');

			        // Disable the view to avoid rendering
			        $this->view->disable();
            	}
            }
        }
        }
      }
    }

}

