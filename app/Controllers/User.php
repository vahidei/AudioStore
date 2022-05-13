<?php namespace App\Controllers;

use App\Config\ReCaptcha3;
use App\Models\Main_model;
use App\Models\User_model;

class User extends BaseController
{
    public function index()
    {

    }

    public function signin()
    {
        if(userLoggedIn()){
            return redirect()->to(base_url());
        }
        $data = [];
        if(isset($_POST['submit'])){

            if(isset($_POST['forgot_password'])){
                return $this->forgot_password();
            }

            $alert_message = [];
            if(!isset($_POST['email']) || empty($_POST['email']) || !valid_email($_POST['email'])){
                $alert_message[] = lang('main.enter_email_correctly');
            }
            if(!isset($_POST['password']) || empty($_POST['password'])){
                $alert_message[] = lang('main.enter_password');
            }
            if(!isValidRecaptcha($_POST['g-recaptcha-response'])){
                $alert_message[] = lang('main.complete_captcha');
            }

            if(!empty($alert_message)) goto view;
            $user_model = new User_model();

            $user = $user_model->get_user_by_email_password($_POST['email'], $_POST['password']);
            if($user){

                if($user['status'] == 'pending'){
                    $code = rand(10000, 99999);
                    $message = "Please verify the email ".anchor('user/verify/'.$_POST['email'].'/'.$code, 'Click here to verify','');
                    $email = \Config\Services::email();
                    $email->setFrom('no-reply@site.com', 'Verify your signup email on Site.com');
                    $email->setTo($_POST['email']);
                    $email->setSubject('Verify your signup email on Site.com');
                    $email->setMessage($message);//your message here
                    $email->send();

                    $user_model->update_code($user['username'], $code);
                    if(isset($_GET['shopping'])){
                        $shopping = '?shopping=true';
                    }else{
                        $shopping = '';
                    }
                    return redirect()->to(base_url().'/user/verify/'.$user['username'].'/'. $_POST['email'].$shopping);

                }else{
                    $_SESSION['user'] = $user;
                    $lsession = sha1(uniqid().md5(time())).md5(microtime()).sha1(time()).sha1(rand(10000,99999));
                    if($user_model->set_login_session($user['id'], $lsession)){
                        unset($_COOKIE['userls']);
                        setcookie('userls', null, -1, '/');
                        setcookie('userls', $lsession, time() + 2592000, '/');
                    }
                    if(isset($_GET['shopping'])){
                        return redirect()->to(base_url('buy/final'));
                    }

                    return redirect()->to(base_url());

                }

            }else{
                $alert_message[] = lang('main.email_password_incorrect');
            }
        }
        view:
        echo view('includes/light_header');
        echo view('user/signin', ['alert_message'=>$alert_message]);
        echo view('includes/light_footer');
    }

    function logout(){
        unset($_SESSION['user']);
        unset($_COOKIE['userls']);
        setcookie('userls', null, -1, '/');
        return redirect()->to(base_url());
    }

    private function isEmailRegistered($email){
        $user_model = new User_model();
        return $user_model->isEmailRegistered($email);
    }

    function verify($username, $email){
        if(userLoggedIn()){
            return redirect()->to(base_url());
        }
        $user_model = new User_model();
        if(!$user_model->isPending($username, $email)){
            return show_404();
        }
        $alert_message = '';
        if(isset($_POST['submit'])){
            if(empty($_POST['code']) || strlen($_POST['code']) !== 5 || !is_numeric($_POST['code'])){
                $alert_message[] = lang('main.invalid_verification_code');
                goto view;
            }

            if($user_model->check_verify_code($username, $_POST['code'])){
                if($user_model->verify_user($username)){
                    $user = $user_model->get_user_by_username($username);
                    $_SESSION['user'] = $user;
                    $_SESSION['user']['isNew'] = true;
                    if(isset($_GET['shopping'])){
                        return redirect()->to(base_url('buy/final'));
                    }else{
                        return redirect()->to(base_url());
                    }

                }else{
                    $alert_message[] = lang('main.verify_account_problem');
                }
            }else{
                $alert_message[] = lang('main.incorrect_verification_code');
            }

        }

        view:
        echo view('includes/light_header');
        echo view('user/verify', ['alert_message'=>$alert_message, 'data'=>['email'=>$email]]);
        echo view('includes/light_footer');

    }

    public function signup()
    {
        if(userLoggedIn()){
            return redirect()->to(base_url());
        }
        $user_model = new User_model();
        $alert_message = [];
        $post = [
            'name'=>'', 'email'=>'', 'password'=>'', 'repeat_password'=>'', 'terms'=>''
        ];
        if(isset($_POST['submit'])){
            if(empty($_POST['name'])){
                $alert_message[] = lang('main.enter_name');
            }
            if(empty($_POST['email'])){
                $alert_message[] = lang('main.enter_email');
            }
            if(empty($_POST['password'])){
                $alert_message[] = lang('main.enter_password');
            }
            if(empty($_POST['repeat_password'])){
                $alert_message[] = lang('main.enter_repeat_password');
            }
            if(strlen($_POST['password']) < 6){
                $alert_message[] = lang('main.password_length_error');
            }
            if($_POST['password'] !== $_POST['repeat_password']){
                $alert_message[] = lang('main.password_repeat_error');
            }
            if(!valid_email($_POST['email'])){
                $alert_message[] = lang('main.enter_email_correctly');
            }
            if(!isValidRecaptcha($_POST['g-recaptcha-response'])){
                $alert_message[] = lang('main.complete_captcha');
            }

            if($this->isEmailRegistered($_POST['email'])){
                $alert_message[] = lang('main.email_exists');
            }

            if(!isset($_POST['terms']) || $_POST['terms'] !== 'on'){
                $alert_message[] = lang('main.terms_error');
            }

            $_POST['email'] = strtolower($_POST['email']);
            if(!empty($alert_message)) goto view;

            $code = rand(10000, 99999);
            $message = "Please verify the email ".anchor('user/verify/'.$_POST['email'].'/'.$code, 'Click here to verify','');
            send_email($_POST['email'], 'Verify your signup email on Site.com', $message);

            $username = $user_model->add($_POST, $code);

            if($username){
               return redirect()->to(base_url().'/user/verify/'.$username.'/'. $_POST['email']);
            }else{
                $alert_message[] = lang('main.registering_error');
            }

        }

        view:
        $post = $_POST;
        echo view('includes/light_header');
        echo view('user/signup', ['post'=>$post,'alert_message'=>$alert_message]);
        echo view('includes/light_footer');
    }

    public function settings()
    {
        if(!userLoggedIn()){
            return redirect()->to(base_url('user/signin'));
        }
        if(isset($_POST['language']) && in_array($_POST['language'], ['fa', 'en'])){
            unset($_COOKIE['lang']);
            setcookie('lang', null, -1, '/');
            $language = \Config\Services::language();
            $language->setLocale(strtolower($_POST['language']));
            setcookie('lang', strtolower($_POST['language']), time() + 2592000, '/');
        }

        $data = [
            'alert_class'=>'',
            'alert_message'=>[]
        ];
        if(isset($_POST['submit'])){
            $pw_set = false;

            if(empty($_POST['name'])){
                $data['alert_message'][] = lang('main.enter_name');
            }
            if(!empty($_POST['password']) || !empty($_POST['repeat_password'])){
                $pw_set = true;
                if($_POST['password'] !== $_POST['repeat_password']){
                    $data['alert_message'][] = lang('main.password_repeat_error');
                }
                if(strlen($_POST['password']) < 6){
                    $data['alert_message'][] = lang('main.password_length_error');
                }
            }
            if(!empty($_POST['mobile']) && !valid_mobile($_POST['mobile'])){
                $data['alert_message'][] = lang('settings.mobile_number_error');
            }
            if(!isset($_POST['subscribe'])){
                $_POST['subscribe'] = 0;
            }else{
                $_POST['subscribe'] = 1;
            }

            if($pw_set){
                $_POST['password'] = encode_password($_POST['password'], $_SESSION['user']['email']);
            }

            if(!empty($data['alert_message'])){
                $data['alert_class'] = 'alert-danger';
                goto view;
            }

            $user_model = new User_model();
            if($user_model->update_settings($_POST, $pw_set, $_SESSION['user']['id'])){
                $_SESSION['user'] = $user_model->get_user_by_email($_SESSION['user']['email']);
                $data['alert_class'] = 'alert-success';
                $data['alert_message'] = lang('settings.update_successful');
            }
        }
        view:
        echo view('includes/header');
        echo view('user/settings', ['data'=>$data]);
        echo view('includes/footer');
    }

    public function saved($type='track', $page=1)
    {
        if(!userLoggedIn()){
            return redirect()->to(base_url('user/signin'));
        }
        if(!in_array($type, ['package', 'track']) || !is_numeric($page) || $page <= 0){
            show_404();
        }

        if(!isset($_SESSION['user'])){
            return redirect()->to(base_url('user/signin'));
        }

        $main_model = new Main_model();

        $data['limit'] = 20;
        $data['page'] = $page;
        $data['type'] = $type;
        $data['packages_count'] = $main_model->saved_items_count('package', $_SESSION['user']['id']);
        $data['tracks_count'] = $main_model->saved_items_count('track', $_SESSION['user']['id']);
        $data['items_count'] = $data[$type.'s_count'];

        $offset = ($page - 1) * $data['limit'];
        $data['items'] = $main_model->get_saved($type, $data['limit'], $offset, $_SESSION['user']['id']);

        echo view('includes/header');
        echo view('user/saved', ['data'=>$data]);
        echo view('includes/footer');
    }

    public function purchases($page)
    {
        if(!userLoggedIn()){
            return redirect()->to(base_url('user/signin'));
        }
        $user_model = new User_model();

        $data['limit'] = 20;
        $data['page'] = $page;
        $offset = ($page - 1) * $data['limit'];

        $data['items'] = $user_model->get_purchases($data['limit'], $offset, $_SESSION['user']['id'], $_SESSION['user']['email']);
        $data['purchases_count'] = $user_model->purchases_count('success', $_SESSION['user']['id'], $_SESSION['user']['email']);

        view:
        echo view('includes/header');
        echo view('user/purchases', ['data'=>$data]);
        echo view('includes/footer');
    }

    public function google_signin(){
        if(userLoggedIn()){
            return redirect()->to(base_url());
        }
        require APPPATH . 'Libraries/google/vendor/autoload.php';
        $google_client = new \Google_Client();
        $google_client->setClientId('262494965654-964o1153aa52ms7m7g2b2njgudb9d8ch.apps.googleusercontent.com'); //Define your ClientID

        $google_client->setClientSecret('iMoZNhnpgWenzYgaX4UA_jba'); //Define your Client Secret Key

        $google_client->setRedirectUri('http://localhost/newp/user/google_signin'); //Define your Redirect Uri

        $google_client->addScope('email');

        $google_client->addScope('profile');

        if(isset($_GET["code"]))
        {
            $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

            if(!isset($token["error"]))
            {
                $google_client->setAccessToken($token['access_token']);

                $google_service = new \Google_Service_Oauth2($google_client);

                $data = $google_service->userinfo->get();

                $user_data = array(
                    'name' => $data['given_name'].' '.$data['family_name'],
                    'email' => $data['email']
                );

                $user_model = new User_model();
                $user = $user_model->isEmailRegistered($data['email']);
                if(!empty($user))
                {
                    $exec = $user_model->google_user_update($user_data);
                    $ins = false;
                }
                else
                {
                    $exec = $user_model->google_user_insert($user_data);
                    $ins = true;
                }

                if($exec){
                    $user = $user_model->get_user_by_email($user_data['email']);
                    $_SESSION['user'] = $user;
                    $lsession = sha1(uniqid().md5(time())).md5(microtime()).sha1(time()).sha1(rand(10000,99999));
                    if($user_model->set_login_session($user['id'], $lsession)){
                        unset($_COOKIE['userls']);
                        setcookie('userls', null, -1, '/');
                        setcookie('userls', $lsession, time() + 2592000, '/');
                    }
                    if($ins){
                        $_SESSION['user']['isNew'] = true;
                    }
                    return redirect()->to(base_url());
                }

            }
        }else{
            return redirect()->to($google_client->createAuthUrl());
        }

    }

    private function forgot_password(){

        $alert_message = [];
        $action = 'reset_password';
        if(!isset($_POST['email']) || empty($_POST['email']) || !valid_email($_POST['email'])){
            $alert_message[] = lang('main.enter_email_correctly');
        }
        if(!isValidRecaptcha($_POST['g-recaptcha-response'])){
            $alert_message[] = lang('main.complete_captcha');
        }

        if(!empty($alert_message)){
            goto view;
        }

        $user_model = new User_model();

        $user = $user_model->get_user_by_email($_POST['email']);
        if(!empty($user)){
            $code = sha1($_POST['email'].rand(1000,9999).$_POST['email'].time());

            if($user_model->add_reset_password($_POST['email'], $code)){
                $url = base_url('user/reset_password/'.$code);
                $message = "Click on the link to reset password: ".anchor($url);
                send_email($_POST['email'], 'Reset Password', $message);
            }else {
                $alert_message[] = lang('main.cant_send_email');
            }
        }

        $action = 'email_sent';

        view:
        echo view('includes/light_header');
        echo view('user/signin', ['alert_message'=>$alert_message, 'action'=>$action]);
        echo view('includes/light_footer');
    }

    public function reset_password($code){
        if(userLoggedIn()){
            return redirect()->to(base_url());
        }
        $user_model = new User_model();
        $user = $user_model->get_rp_email($code);
        $alert_message = [];
        $action = '';
        if(!empty($user)){
            $page = 'reset_password';
            $action = $user['email'];
            if(isset($_POST['submit'])){
                if(empty($_POST['password'])){
                    $alert_message[] = lang('main.enter_password');
                }
                if(empty($_POST['repeat_password'])){
                    $alert_message[] = lang('main.enter_repeat_password');
                }
                if(strlen($_POST['password']) < 6){
                    $alert_message[] = lang('main.password_length_error');
                }
                if($_POST['password'] !== $_POST['repeat_password']){
                    $alert_message[] = lang('main.password_repeat_error');
                }

                if(!empty($alert_message)){
                    goto view;
                }

                if($user_model->update_user_password($user['email'], $_POST['password'])){
                    $action = 'reset_success';
                }else{
                    $alert_message[] = lang('main.problem_in_reset_password');
                }
            }
        }else{
            $alert_message[] = lang('main.rp_code_not_exists_or_expired');
            $action = 'reset_password';
            $page = 'signin';
        }

        view:
        echo view('includes/light_header');
        echo view('user/'.$page, ['alert_message'=>$alert_message, 'action'=>$action]);
        echo view('includes/light_footer');
    }

    //--------------------------------------------------------------------

}
