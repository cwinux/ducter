<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $username = $this->username;
            $password = $this->password;
            $timestamp = time();

            $url_trans = "http://ucapi.lecommons.com/transcode.php";
            $url_check = "http://ucapi.lecommons.com/check.php";

            $value = 'site=dcmd_lecloud_com&time='.$timestamp.'&type=ENCODE&v='.$password.'CYA76OUBXiJJOEr08pURasZDXVPUfh13c3wAZDD9sfE=';
            $sign = md5($value);
            $post_d = 'site=dcmd_lecloud_com&type=ENCODE&time='.$timestamp.'&v='.urlencode($password);
            $post_data = $post_d.'&sign='.$sign;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url_trans);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            $output = curl_exec($ch);
            curl_close($ch);
            
            $result = json_decode($output,true);
            $passwd = $result["objects"];
            $value_check = 'password='.$passwd.'&site=dcmd_lecloud_com&time='.$timestamp.'&username='.$username.'CYA76OUBXiJJOEr08pURasZDXVPUfh13c3wAZDD9sfE=';
            $sign_check = md5($value_check);
            $post_check = 'password='.$passwd.'&site=dcmd_lecloud_com&time='.$timestamp.'&username='.$username.'&sign='.$sign_check;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url_check);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_check);
            $out = curl_exec($ch);
            curl_close($ch);
            $result_check = json_decode($out,true);
            if (($username !='noc') & (!$user || $result_check["respond"]["status"] ==0)) {
#            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password!');
            }
            else if ($username == "noc" & !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }

        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) { 
           return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}
