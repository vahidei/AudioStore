<?php

if(!function_exists('admin_base_url')){
    function admin_base_url($url=''){
        if(!empty($url)) $url = '/'.$url;
        return base_url(ADMIN_SEC.'/admin'.$url);
    }
}

if(!function_exists('admin_password_hash')){
    function admin_password_hash($password){
        return md5(sha1($password.md5($password)).strlen($password));
    }
}

if (!function_exists('output_api')) {
    function output_api($data = '', $status_code = 200, $error_code = 0)
    {
        $status = array(
            200 => '200 OK',
            204 => '204 No Content',
            400 => '400 Bad Request',
            401 => '401 Unauthorized',
            406 => '406 Not Acceptable',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error',
            501 => '501 Not Implemented'
        );

        // header_remove();
        http_response_code($status_code);
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        header('Content-Type: application/json');
        header('Status: ' . $status[$status_code]);

        die(json_encode([
            'status' => $status_code < 300,
            'error_code' => $error_code,
            'data' => $data
        ]));
    }
}

if (!function_exists('upload_media')) {
    function upload_media($file, $type, $is_original)
    {
        $target_dir = ((!$is_original) ? "public/demos/" : "originals/") . $type . '/';
        $target_file = $target_dir . basename($file["name"]);

        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $audio_arr = ['mp3', '3gp', 'ogg', 'wma'];
        if (!in_array($fileType, ['jpg', 'jpeg', 'bmp', 'png', 'gif', 'zip', 'avi', 'mp4']) && !in_array($fileType, $audio_arr)) {
            return false;
        }

        $md5 = md5(rand(1000, 9999) . basename($file["name"] . microtime())) . '.' . $fileType;

        $target_file = $target_dir . $md5;

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $size = filesize($target_file);
            $duration = 0;
            return ['code' => $md5, 'size' => $size, 'duration' => $duration, 'file_name' => $file['name']];
        } else {
            return false;
        }

    }
}

if (!function_exists('valid_email')) {
    function valid_email($email)
    {
        return preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i', $email);
    }
}

if (!function_exists('show_404')) {
    function show_404()
    {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        die();
    }
}

if (!function_exists('encode_password')) {
    function encode_password($password, $email)
    {
        return md5(md5(sha1($password)) . sha1($password) . md5($email));
    }
}
if (!function_exists('send_json')) {
    function send_json($array)
    {
        header('Content-Type: application/json');
        echo json_encode($array);
    }
}
if (!function_exists('colors_to_gradient')) {
    function colors_to_gradient($json)
    {
        $colors = json_decode($json, 1);
        $gradient = '';
        $i = 0;
        $sort = [135, 225, 45, 315];

        foreach ($colors as $items) {
            foreach ($items as $item) {
                $gradient .= 'linear-gradient(' . $sort[$i++] . 'deg, rgb(' . $item[0] . ',' . $item[1] . ',' . $item[2] . '), rgba(0,0,0,0) 100% ),';
            }
        }
        return rtrim($gradient, ',');
    }
}
if (!function_exists('calcDiscount')) {
    function calcDiscount($price, $discount, $type)
    {
        if(!empty($discount)){
            if ($type == 'money') {
                $value = $price - $discount;
                if ($value < 0) {
                    $value = 0;
                }
                return $value;
            }
            return $price - ($price * ($discount / 100));
        }
        return $price;
    }
}
if (!function_exists('isFree')) {
    function isFree($price, $discount, $type)
    {
        $value = $price;
        if(!empty($discount)){
            if ($type == 'money') {
                $value = $price - $discount;
            }else{
                $value = $price - ($price * ($discount / 100));
            }
        }
        if ($value <= 0) {
            return true;
        }
        return false;
    }
}
if (!function_exists('isJson')) {
    function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
if (!function_exists('clearCartcookie')) {
    function clearCartcookie()
    {
        $cookie = [];
        setcookie('cart', json_encode($cookie), time() + 2592000, '/');
    }
}
if (!function_exists('generate_code')) {
    function generate_code($str = '')
    {
        return
            sha1(rand() . time() . uniqid()) . md5(microtime() . $str);
    }
}
if (!function_exists('getIp')) {
    function getIp()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {

            $ip = $_SERVER['HTTP_CLIENT_IP'];

        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $ip;
    }
}
if (!function_exists('discountLabel')) {
    function discountLabel($discount, $type)
    {
        if (empty($discount)) return '';
        if ($type == 'money') {
            return number_format($discount) . ' '. setting('currency'.lang('main.lang_suffix'));
        }
        return $discount . '%';
    }
}

if (!function_exists('send_email')) {
    function send_email($to, $subject, $message)
    {
        $email = \Config\Services::email();
        $email->setFrom('no-reply@site.com', $subject);
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);
        return $email->send();
    }
}
if (!function_exists('isValidRecaptcha')) {
    function isValidRecaptcha($token)
    {
        try {

            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = ['secret' => '6LdZpxYcAAAAALS3l-bkvuVb0XwaoG5ovF5gaKHK',
                'response' => $token,
                'remoteip' => $_SERVER['REMOTE_ADDR']];

            $options = [
                'http' => [
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data)
                ]
            ];

            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            return json_decode($result)->success;
        } catch (Exception $e) {
            return null;
        }
    }
}
if(!function_exists('setting')){
    function setting($key){
        $main_model = new \App\Modules\Admin\Models\Main_model();
        if(is_array($key)){
            $items = $main_model->get_setting($key);
            return $items;
        }else{
            $item = $main_model->get_setting($key);
            return $item['value'];
        }
    }
}

if(!function_exists('switch_key')){
    function switch_key($item, $key, $lang_suffix){

        if($item[$key.$lang_suffix] == ''){
            return $item[$key];
        }else {
            return $item[$key . $lang_suffix];
        }
    }
}

if(!function_exists('toHHMMSS')){
    function toHHMMSS($duration){

        $sec_num = intval($duration, 10);
        $hours   = floor($sec_num / 3600);
        $minutes = floor(($sec_num - ($hours * 3600)) / 60);
        $seconds = $sec_num - ($hours * 3600) - ($minutes * 60);

        if ($hours   < 10) {$hours   = "0".$hours;}
        if ($minutes < 10) {$minutes = "0".$minutes;}
        if ($seconds < 10) {$seconds = "0".$seconds;}
        if($hours == '00'){
            return $minutes.':'.$seconds;
        }
        return $hours.':'.$minutes.':'.$seconds;
    }
}

if(!function_exists('products_count')){
    function products_count(){
        $main_model = new \App\Models\Main_model();
        return [
            'tracks' => $main_model->tracks_count(),
            'packages' => $main_model->packages_count(),
            'discounts' => $main_model->discounts_count(),
        ];
    }
}

if(!function_exists('valid_mobile')){
    function valid_mobile($mobile){
        if(strlen($mobile) !== 13 && strlen($mobile) !== 11){
            return false;
        }
        if(strlen($mobile) == 13){
            if($mobile[0] !== '+'){
                return false;
            }
            $m = ltrim($mobile, '+');
            if(!is_numeric($m)){
                return false;
            }
        }

        if(strlen($mobile) == 11 && !is_numeric($mobile)){
            return false;
        }

        return true;

    }
}
if(!function_exists('userLoggedIn')){
    function userLoggedIn(){
        return isset($_SESSION['user']);
    }
}
