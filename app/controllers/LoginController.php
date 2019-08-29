<?php

/**
 * Class LoginController
 */
class LoginController extends ControllerBase
{
    /**
     *
     */
    public function IndexAction()
    {
        $ref = $this->request->get('ref') ?: '';
        $this->view->setVars(['ref' => $ref]);
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function authenticateAction()
    {
        if ($this->request->isPost()) {
            $mobile = $this->request->getPost('mobile', 'int');
            $remember = $this->request->getPost('remember', 'int') ?: 0;
            $password = $this->request->getPost('password');
            $refURL = $this->request->getPost('ref') ?: '';
            $refU = "login?ref=" . $refURL;

            if (!$mobile || !$password || !preg_match('/^(?:\+?(?:[1-9]{3})|0)?([6,7]([0-9]{8}))$/', $mobile)) {
                $this->flashSession->error($this->flashError('All fields are required'));

                return $this->response->redirect($refU);
                $this->view->disable();
            }


            $mobile = $this->formatMobileNumber($mobile);
            

            if ($mobile) {
                $user = $this->rawSelect("SELECT * from profile where msisdn='$mobile' limit 1");
                $user = $user['0'];

                if ($user == false) {
                    $this->flashSession->error($this->flashError('Invalid username and or password 1'));
                    $this->response->redirect($refU);
                    // Disable the view to avoid rendering
                    $this->view->disable();
                }

                $profile_id = $user['profile_id'];

                $checkUser = $this->rawSelect("SELECT * from profile_settings where profile_id='$profile_id' limit 1");
                $checkUser = $checkUser['0'];

                if ($checkUser['status'] == '0') {
                    $this->flashSession->error($this->flashError('Your account is not verified, please enter the code sent to your phone to verify'));
                    $this->response->redirect('verify');
                    // Disable the view to avoid rendering
                    $this->view->disable();
                } else {

                    if ($checkUser) {
                        $thePassword = $checkUser['password'];

                        if (!$this->security->checkHash($password, $thePassword)) {
                            $this->flashSession->error($this->flashError('Invalid username and or password'));
                            $this->response->redirect($refU);
                            // Disable the view to avoid rendering
                            $this->view->disable();
                        } else {
                            $device = $this->getDevice();
                            $sessionData = ['id'       => $checkUser['profile_id'],
                                            'remember' => $remember,
                                            'mobile'   => $mobile,
                                            'device'   => $device,
                            ];
                            $exp = time() + (3600 * 24 * 5);
                            $this->registerAuth($sessionData, $exp);
                            $this->response->redirect($refURL);
                        }

                    } else {
                        $this->flashSession->error($this->flashError('Invalid username and or password'));
                        $this->response->redirect($refU);
                        // Disable the view to avoid rendering
                        $this->view->disable();
                    }
                }

            }

        }
    }

}

?>