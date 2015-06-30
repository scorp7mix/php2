<?php

namespace blog\models;

//
// Менеджер пользователей
//
class M_User
{
    private static $instance;
	private static $db;				// драйвер БД
	private $sid;				// идентификатор текущей сессии
	private $uid;				// идентификатор текущего пользователя
	

	//
	// Конструктор
	//
	protected function __construct()
	{
		self::$db = M_MySQLi::GetInstance();
		$this->sid = null;
		$this->uid = null;
	}

    //
    // для Singleton
    //
    public static function GetInstance()
    {
        if(null === self::$instance)
            self::$instance = new self();

        return self::$instance;
    }

	//
	// Очистка неиспользуемых сессий
	// 
	public function ClearSessions()
	{
		$min = date('Y-m-d H:i:s', time() - 60 * 20); 			
		$t = "time_last < '%s'";
		$where = sprintf($t, $min);
		self::$db->Delete('sessions', $where);
	}

	//
	// Авторизация
	// $login 		- логин
	// $password 	- пароль
	// $remember 	- нужно ли запомнить в куках
	// результат	- true или false
	//

	public function Login($login, $password, $remember = true)
	{
        if(empty($login))
            return ['login' => 'has-error', 'password' => 'has-error', 'text' => 'Неправильное имя пользователя или пароль!!'];

		// вытаскиваем пользователя из БД
		$user = $this->GetByLogin($login);

		if ($user == null)
            return ['login' => 'has-error', 'password' => 'has-error', 'text' => 'Неправильное имя пользователя или пароль!!'];
		
		$id_user = $user['id_user'];
		
		// проверяем пароль
		if ($user['password'] != md5($password))
            return ['login' => 'has-error', 'password' => 'has-error', 'text' => 'Неправильное имя пользователя или пароль!!'];
				
		// запоминаем имя и md5(пароль)
		if ($remember)
		{
			$expire = time() + 3600 * 24 * 100;
			setcookie('login', $login, $expire);
			setcookie('password', md5($password), $expire);
		}		
				
		// открываем сессию и запоминаем SID
		$this->sid = $this->OpenSession($id_user);
		
		return true;
	}

    //
    // Регистрация
    // $login 		- логин
    // $password 	- пароль
    // результат	- true или текст ошибки
    //

    public function Register($login, $password, $password2)
    {
        if(empty($login))
            return ['login' => 'has-error', 'text' => 'Введите имя пользователя!!'];

        $user = $this->GetByLogin($login);

        if (null != $user)
            return ['login' => 'has-error', 'text' => 'Такой пользователь уже зарегистрирован!!'];

        // проверка пароля
        if($password != $password2)
            return ['password' => 'has-error', 'text' => 'Пароли не совпадают!!'];

        if(empty($password))
            return ['password' => 'has-error', 'text' => 'Пароль не может быть пустым!!'];

        // создаем пользователя
        self::$db->Insert('users', ['login' => $login, 'password' => md5($password), 'id_role' => 2]);

        return true;
    }

	//
	// Выход
	//
	public function Logout()
	{
        if(!$this->Get())
            return false;
        self::$db->Delete('sessions', "sid='" . $this->sid . "'");
        setcookie('login', '', time() - 1);
		setcookie('password', '', time() - 1);
		unset($_COOKIE['login']);
		unset($_COOKIE['password']);
		unset($_SESSION['sid']);
		$this->sid = null;
		$this->uid = null;
	}
						
	//
	// Получение пользователя
	// $id_user		- если не указан, брать текущего
	// результат	- объект пользователя
	//
	public function Get($id_user = null)
	{	
		// Если id_user не указан, берем его по текущей сессии.
		if ($id_user == null)
			$id_user = $this->GetUid();

		if ($id_user == null)
			return null;
			
		// А теперь просто возвращаем пользователя по id_user.
		$t = "SELECT * FROM users WHERE id_user = '%d'";
		$query = sprintf($t, $id_user);
		$result = self::$db->Select($query);
		return $result[0];		
	}
	
	//
	// Получает пользователя по логину
	//
	public function GetByLogin($login)
	{	
		$t = "SELECT * FROM users WHERE login = '%s'";
		$query = sprintf($t, $login);
		$result = self::$db->Select($query);
		return $result[0];
	}
			
	//
	// Проверка наличия привилегии
	// $priv 		- имя привилегии
	// $id_user		- если не указан, значит, для текущего
	// результат	- true или false
	//
	public function Can($priv, $id_user = null)
	{
        if(null == $id_user)
            $id_user = $this->Get()['id_user'];
        if(null == $id_user)
            return false;

        $t = "SELECT * FROM users AS u " .
             "LEFT JOIN privs2roles AS pr ON u.id_role = pr.id_role " .
             "LEFT JOIN privs AS p ON pr.id_priv = p.id_priv " .
             "WHERE u.id_user = %d AND p.name = '%s'";

        $query = sprintf($t, $id_user, $priv);
        $result = self::$db->Select($query);

		return $result ? true : false;
	}

	//
	// Проверка активности пользователя
	// $id_user		- идентификатор
	// результат	- true если online
	//
	public function IsOnline($id_user)
	{		
        $t = "SELECT * FROM sessions WHERE id_user = %d";
        $query = sprintf($t, $id_user);
        $result = self::$db->Select($query);

        return $result ? true : false;
	}
	
	//
	// Получение id текущего пользователя
	// результат	- UID
	//
	public function GetUid()
	{	
		// Проверка кеша.
		if ($this->uid != null)
			return $this->uid;	

		// Берем по текущей сессии.
		$sid = $this->GetSid();

		if ($sid == null)
			return null;
			
		$t = "SELECT id_user FROM sessions WHERE sid = '%s'";
		$query = sprintf($t, $sid);
		$result = self::$db->Select($query);
				
		// Если сессию не нашли - значит пользователь не авторизован.
		if (count($result) == 0)
			return null;
			
		// Если нашли - запоминм ее.
		$this->uid = $result[0]['id_user'];
		return $this->uid;
	}

	//
	// Функция возвращает идентификатор текущей сессии
	// результат	- SID
	//
	private function GetSid()
	{
		// Проверка кеша.
		if ($this->sid != null)
			return $this->sid;
	
		// Ищем SID в сессии.
		$sid = @$_SESSION['sid'];

		// Если нашли, попробуем обновить time_last в базе.
		// Заодно и проверим, есть ли сессия там.
		if ($sid != null)
		{
			$session = array();
			$session['time_last'] = date('Y-m-d H:i:s'); 			
			$t = "sid = '%s'";
			$where = sprintf($t, $sid);
			$affected_rows = self::$db->Update('sessions', $session, $where);

			if ($affected_rows == 0)
			{
				$t = "SELECT count(*) FROM sessions WHERE sid = '%s'";		
				$query = sprintf($t, $sid);
				$result = self::$db->Select($query);
				
				if ($result[0]['count(*)'] == 0)
					$sid = null;			
			}			
		}		
		
		// Нет сессии? Ищем логин и md5(пароль) в куках.
		// Т.е. пробуем переподключиться.
		if ($sid == null && isset($_COOKIE['login']))
		{
			$user = $this->GetByLogin($_COOKIE['login']);
			
			if ($user != null && $user['password'] == $_COOKIE['password'])
				$sid = $this->OpenSession($user['id_user']);
		}
		
		// Запоминаем в кеш.
		if ($sid != null)
			$this->sid = $sid;
		
		// Возвращаем, наконец, SID.
		return $sid;		
	}
	
	//
	// Открытие новой сессии
	// результат	- SID
	//
	private function OpenSession($id_user)
	{
		// генерируем SID
		$sid = $this->GenerateStr(10);
				
		// вставляем SID в БД
		$now = date('Y-m-d H:i:s'); 
		$session = array();
		$session['id_user'] = $id_user;
		$session['sid'] = $sid;
		$session['time_start'] = $now;
		$session['time_last'] = $now;
        self::$db->Insert('sessions', $session);
				
		// регистрируем сессию в PHP сессии
		$_SESSION['sid'] = $sid;				
				
		// возвращаем SID
		return $sid;	
	}

	//
	// Генерация случайной последовательности
	// $length 		- ее длина
	// результат	- случайная строка
	//
	private function GenerateStr($length = 10) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;  

		while (strlen($code) < $length) 
            $code .= $chars[mt_rand(0, $clen)];  

		return $code;
	}
}
