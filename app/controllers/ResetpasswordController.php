<?php

class ResetpasswordController extends ControllerBase
{
    public function indexAction()
    {
        $id = $this->request->get('id','int');

        if ($id) {
            $user = $this->rawSelect("SELECT * from profile where profile_id='$id' limit 1");
            $user = $user['0'];
    
            $this->view->setVars(["msisdn"=>$user['msisdn'],"profile_id"=>$user['profile_id']]);
        }
    }

    public function codeAction()
    {
        $verification_code = substr(number_format(time() * mt_rand(),0,'',''),0,4);
        $mobile = $this->request->getPost('mobile', 'int');

        if (!$mobile) {
            $this->flashSession->error($this->flashError('Please enter your mobile number'));
            $this->response->redirect('resetpassword');

            $this->view->disable();
        }else{
                $mobile = $this->formatMobileNumber($mobile);

                if ($mobile==false) {
                $this->flashSession->error($this->flashError('Invalid mobile number'));
                $this->response->redirect('resetpassword');

                // Disable the view to avoid rendering
                $this->view->disable();
                }else{

                $user = $this->rawSelect("SELECT * from profile where msisdn='$mobile' limit 1");
                $user = $user['0'];
        
                if (!$user) {
                    $this->flashSession->error($this->flashError('User does not exist'));
                    $this->response->redirect('resetpassword');
                    // Disable the view to avoid rendering
                    $this->view->disable();
                }else{
                    $sms = "Your SCOREPESA password reset code is $verification_code. Use this to reset your password.";
                    $sms = str_replace(' ', '+', $sms);
                    $sms = "msisdn=$mobile&message=$sms&short_code=29777&correlator=&message_type=BULK&link_id=";
        
                    $profile_id = $user['profile_id'];
        
                    $checkUser = $this->rawSelect("SELECT * from profile_settings where profile_id='$profile_id' limit 1");
                    $checkUser = $checkUser['0'];
                
                    if ($checkUser) {
                        $insert = $this->rawInsert("update profile_settings set verification_code='$verification_code' where profile_id='$profile_id'");
                        $send = $this->sendSMS($sms);
                        $this->flashSession->error($this->flashSuccess('Use the code sent to your phone to reset your password'));
                        $this->response->redirect("resetpassword?id=$profile_id");
                    // Disable the view to avoid rendering
                    $this->view->disable();
                    }else {
                        $insert = $this->rawInsert("insert into profile_settings (profile_id,password,verification_code,created_at) values('$profile_id','','$verification_code',now())");

                        $send = $this->sendSMS($sms);

                        $this->flashSession->error($this->flashSuccess('Use the code sent to your phone to reset your password'));
                        $this->response->redirect("resetpassword?id=$profile_id");
                    }
                }
            }
            }
    }

    public function passwordAction()
    {
        $password = $this->request->getPost('password');
        $repeatPassword = $this->request->getPost('repeatPassword');
        $reset_code = $this->request->getPost('reset_code','int');
        $profile_id = $this->request->getPost('profile_id','int');

        if (!$password || !$reset_code || !$repeatPassword || !$profile_id) {
            $this->flashSession->error($this->flashError('All fields are required'));
            $this->response->redirect("resetpassword?id=$profile_id");

            // Disable the view to avoid rendering
            $this->view->disable();
        }else{
                if ($password != $repeatPassword) {
                    $this->flashSession->error($this->flashError('Passwords do not match'));
                    $this->response->redirect("resetpassword?id=$profile_id");
        
                    // Disable the view to avoid rendering
                    $this->view->disable();
                }else{
                    $valid = $this->rawSelect("select profile_id from profile_settings where profile_id='$profile_id' and verification_code='$reset_code'");
                    if ($valid) {
                       $password = $this->security->hash($password);
                       $updatePassword = $this->rawInsert("update profile_settings set password='$password', status='1' where profile_id='$profile_id'");
                       $this->flashSession->error($this->flashSuccess('Password successfully reset'));
                        $this->response->redirect('login');
        
                        // Disable the view to avoid rendering
                        $this->view->disable();
                    }else{
                        $this->flashSession->error($this->flashError('Invalid reset code'));
                        $this->response->redirect("resetpassword?id=$profile_id");
        
                        // Disable the view to avoid rendering
                        $this->view->disable();
                    }
                }
            }
    }

}

?>