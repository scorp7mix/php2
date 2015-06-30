<?php

namespace blog\models;

class M_User
{
    use M_Singleton;

	protected static $db;
	private $sid;
	private $uid;
	

	protected function __construct()
	{
		static::$db = M_PDO::getInstance();
		$this->sid = null;
		$this->uid = null;
	}

	public function clearSessions()
	{
		$minimal_date = date('Y-m-d H:i:s', time() - 60 * 20);
		$conditions = "time_last < '%s'";
		$query = sprintf($conditions, $minimal_date);
        static::$db->delete('sessions', $query);
	}

	public function login($login, $password, $remember = true)
	{
        $error_object = [
            'login'     => 'has-error',
            'password'  => 'has-error',
            'text'      => 'Неправильное имя пользователя или пароль!!'
        ];

        if (empty($login)) {
            return $error_object;
        }

		$user = $this->getByLogin($login);

		if (null === $user) {
            return $error_object;
        }

		$id_user = $user['id_user'];
		
		if ($user['password'] != md5('secret' . $password)) {
            return $error_object;
        }

		if ($remember) {
			$expire = time() + 3600 * 24 * 100;
			setcookie('login', $login, $expire);
			setcookie('password', md5('secret' . $password), $expire);
		}		

		$this->sid = $this->openSession($id_user);
		
		return true;
	}

    public function register($login, $password, $password2)
    {
        if (empty($login)) {
            return ['login' => 'has-error', 'text' => 'Введите имя пользователя!!'];
        }

        $user = $this->getByLogin($login);

        if (null != $user) {
            return ['login' => 'has-error', 'text' => 'Такой пользователь уже зарегистрирован!!'];
        }

        if ($password != $password2) {
            return ['password' => 'has-error', 'text' => 'Пароли не совпадают!!'];
        }

        if (empty($password)) {
            return ['password' => 'has-error', 'text' => 'Пароль не может быть пустым!!'];
        }

        static::$db->insert('users', ['login' => $login, 'password' => md5('secret' . $password), 'id_role' => 2]);

        return true;
    }

	public function logout()
	{
        if(!$this->Get()) {
            return false;
        }

        static::$db->delete('sessions', "sid='" . $this->sid . "'");

        setcookie('login', '', time() - 1);
		setcookie('password', '', time() - 1);

		unset($_COOKIE['login']);
		unset($_COOKIE['password']);
		unset($_SESSION['sid']);

		$this->sid = null;
		$this->uid = null;

        return true;
	}
						
	public function get($id_user = null)
	{
		if (null === $id_user) {
            $id_user = $this->getUid();
        }

		if (null === $id_user) {
            return null;
        }

		return static::$db->select('users', "id_user=" . $id_user)[0];
	}
	
	public function getByLogin($login)
	{
        return static::$db->select('users', "login='" . $login . "'")[0];
	}
			
	public function can($priv, $id_user = null)
	{
        if (null == $id_user) {
            $id_user = $this->Get()['id_user'];
        }

        if (null == $id_user) {
            return false;
        }

        $t = "SELECT * FROM users AS u " .
             "LEFT JOIN privs2roles AS pr ON u.id_role = pr.id_role " .
             "LEFT JOIN privs AS p ON pr.id_priv = p.id_priv " .
             "WHERE u.id_user = %d AND p.name = '%s'";

        $query = sprintf($t, $id_user, $priv);
        return static::$db->customSelect($query);
	}

	public function isOnline($id_user)
	{
        return static::$db->select('sessions', "id_user=" . $id_user)[0];
	}
	
	public function getUid()
	{	
		if (null !== $this->uid) {
            return $this->uid;
        }

		$sid = $this->getSid();

		if (null === $sid) {
            return null;
        }

		$result = static::$db->select('sessions', "sid='" . $sid . "'");
				
		if (null === $result) {
            return null;
        }

		$this->uid = $result[0]['id_user'];

		return $this->uid;
	}

	private function getSid()
	{
		if (null !== $this->sid) {
            return $this->sid;
        }
	
		$sid = $_SESSION['sid'];

		if (null !== $sid) {
			$session = [];
			$session['time_last'] = date('Y-m-d H:i:s'); 			
			$condition = "sid = '%s'";
			$query = sprintf($condition, $sid);
			$affected_rows = static::$db->update('sessions', $session, $query);

			if ($affected_rows == 0) {
				$result = static::$db->select('sessions', "sid='" . $sid . "'");
				
				if (null === $result) {
                    $sid = null;
                }
			}			
		}		
		
		if (null === $sid && isset($_COOKIE['login'])) {
			$user = $this->getByLogin($_COOKIE['login']);
			
			if (null !== $user && $user['password'] == $_COOKIE['password']) {
                $sid = $this->openSession($user['id_user']);
            }
		}
		
		if (null !== $sid) {
            $this->sid = $sid;
        }
		
		return $sid;
	}
	
	private function openSession($id_user)
	{
		$sid = $this->GenerateStr(10);
				
		$now = date('Y-m-d H:i:s');
		$session = [];
		$session['id_user'] = $id_user;
		$session['sid'] = $sid;
		$session['time_start'] = $now;
		$session['time_last'] = $now;
        static::$db->insert('sessions', $session);
				
		$_SESSION['sid'] = $sid;
				
		return $sid;
	}

	private function generateStr($length = 10)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;  

		while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clen)];
        }

		return $code;
	}
}
