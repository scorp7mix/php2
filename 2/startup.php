<?php

function startup() {
	// Настройки подключения к БД.
	$hostname = 'localhost'; 
	$username = 'root'; 
	$password = '';
	$dbName = 'lesson1';
	
	// Языковая настройка.
	setlocale(LC_ALL, 'ru_RU.UTF-8'); // Устанавливаем нужную локаль (для дат, денег, запятых и пр.)
	mb_internal_encoding('UTF-8'); // Устанавливаем кодировку строк
	
	// Подключение к БД.
	mysql_connect($hostname, $username, $password) or die('No connect with data base'); 
	mysql_query('SET NAMES utf8');
	mysql_select_db($dbName) or die('No data base');

	// Открытие сессии.
	session_start();
}
