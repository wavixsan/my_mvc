<?php /*0000|*/

//ini_set('display_errors',1);
error_reporting(E_ALL);

/*|keys*/ $keysPW4=array('SQL_Console','RecBios','PackNEC','File_Editor','PhpWav4_v3','ScriptPack','Program_Manager');

/*|boot*/ $bootPW4=array('PhpWav4_v3','Program_Manager');

/*|bios*/ $biosPW4="";

/*|programs*/

/*>|SQL_Console*/
class SQL_Console{
	public $dynamic=false; //- Для динамического отображения
	public $name="SQL Console"; //- Имя программы для отображения.
	public $info="SQL Консоль"; //- Описание программы.
	public $ver="1.01"; //- Версия программы.
	public $desc=<<<NEC
16.06.17 v1.0 - Выпуск SQL Консоль
23.06.17 v1.01 - Испр. вывод табов, испр. выбор базы с помощью use, испр. редактор команд, вывод ошибка скриптов
NEC;
	// - js сделать чтобы после точки с запетой при нажатии на enter отправлялась форма
	// - Экспортировать базуданных и импортировать, править тав + '    ->'+' ' добавить пробел
	// - переделать через ПОСТ - отправлять длинные запросы

	private $host = 'localhost';
	private $user = 'root';
	private $pass = '';
	private $base = '';

	private $code = '';
	private $result;
	private $time;
	private $number=-3;
	private $error_connect=0;
	private $error_script='';
	private $dynamicView=false;
	private $view='';

	private function sql_connect($sql=false){
		$connect = @(new mysqli($this->host,$this->user,$this->pass,$this->base));
		$start=microtime(true);
		$result = @($connect->query($sql));
		$this->time=round(microtime(true)-$start,3);
		if($connect->connect_errno){
			switch($connect->connect_errno){
				case 2002: case 1045: case 1049: $this->error_connect = $connect->connect_errno; break;
			}
			$this->result = "ERROR {$connect->connect_errno}: {$connect->connect_error}".chr(10);
		}else if(isset($connect->errno)){
			switch($connect->errno){
				case 0: break;
				default:
					$this->result = "ERROR {$connect->errno} ({$connect->sqlstate}): {$connect->error}".chr(10);
			}
		}
		@mysqli_close($connect);
		return $result;
	}//sql_connect() -

	private function sql_result_display($res){
		$rows=array();
		$z=true; $t='';
		while($row = $res->fetch_assoc()){
			$rows[]=$row;
			foreach($row as $k=>$v){
				if($z==true){$cols[$k]=mb_strlen($k,'utf-8');}
				if(isset($cols[$k]) and $cols[$k]<mb_strlen($v,'utf-8')){$cols[$k]=mb_strlen($v,'utf-8');}
			}
			$z=false;
		}
		function line($cols){
			$t='+';
			foreach($cols as $v){for($i=0;$i<($v+2);$i++){$t.="-";} $t.="+";}
			return $t.chr(10);
		}
		if(isset($cols)){
			$t.=line($cols).'|';
			foreach($cols as $k=>$v){
				$t.=" $k ";
				for($i=0; $i<($v-mb_strlen($k,'utf-8'));$i++){$t.=' ';}
				$t.= "|";
			}
			$t.=chr(10).line($cols);
			foreach($rows as $value){
				$t.="|";
				foreach($value as $k=>$v){
					$t.=" $v ";
					for($i=0; $i<($cols[$k]-mb_strlen($v,'utf-8'));$i++){$t.=' ';}
					$t.="|";
				}
				$t.=chr(10);
			}
			$t.=line($cols);
		}
		return $t.count($rows)." rows in set ({$this->time} sec)".chr(10);
	}//sql_result_display() -

	private function sql_command_number($n){
		if(isset($_SESSION['sql_console_command'])){
			$mas = unserialize($_SESSION['sql_console_command']);
			$this->number = count($mas);
			if(isset($_GET['sql_number']) and $_GET['sql_number']!=''){$this->number=$_GET['sql_number'];}
			if($n == -1 and $this->number >= 0){$this->number--;}
			if($n == 1 and $this->number < count($mas)){$this->number++;}
			if($this->number >= 0 and $this->number < count($mas)){$this->code = $mas[($this->number)];}
		}
	}//sql_command_number() -

	public function start()
	{
		function sql_console_command($sql){
			$s = []; $m=[];
			if(isset($_SESSION['sql_console_command'])){
				$s = unserialize($_SESSION['sql_console_command']);
			}
			for($i=0;$i<count($s);$i++){
				if($s[$i]==$sql){continue;}
				$m[] = $s[$i];
			}
			$m[]=$sql;
			$_SESSION['sql_console_command'] = serialize($m);
		}//sql_console_command() -

		function sql_edit_command($sql){
			$sql = str_replace(chr(13),'',$sql);
			$m = []; $s = chr(10);
			foreach(explode($s,$sql) as $v){
				if($v == ''){continue;}
				$m[] = $v;
			}
			$sql = implode($s,$m);
			if(substr($sql,strlen($sql)-1,1) != ';'){$sql.=';';}
			return $sql;
		}//sql_edit_command

		function sql_tab($sql){
			$sql = str_replace(chr(13),'',$sql);
			$s = chr(10);
			$m = explode($s,$sql);
			for($i=1;$i<count($m);$i++){
				$m[$i] = "    ->".$m[$i];
			}
			return implode($s,$m);
		}//sql_tab() -

		function add_script(){
			$s='ScriptPack'; //v1.01
			if(class_exists($s) and method_exists($s,$s)){
				$s = (new $s)->$s(['_xml','_form']);
				if($s!==false){
					$_SESSION['sql_console_script']=$s;
					return true;
				}
			}
			return false;
		}//add_script() -

		if(session_id()==false){session_start();}
		if(isset($_SESSION['sql_console_log'])){
			$this->result = $_SESSION['sql_console_log'];
		}else{
			$_SESSION['sql_console_log']='';
		}

		if(isset($_COOKIE['sql_connect_setting'])
			and !(isset($_GET['sql_key']) and $_GET['sql_key']=='setting_default')
		){
			$setting = unserialize($_COOKIE['sql_connect_setting']);
			$this->host = $setting['host'];
			$this->user = $setting['user'];
			$this->pass = $setting['pass'];
			$this->base = $setting['base'];
		}
		if(isset($_SESSION['sql_console_base'])){$this->base = $_SESSION['sql_console_base'];}

		if(isset($_GET['sql_key'])){
			switch($_GET['sql_key']){
				case 'up':
					$this->sql_command_number(-1);
					break;
				case 'down':
					$this->sql_command_number(1);
					break;
				case 'clear':
					unset($_SESSION['sql_console_base']);
					unset($_SESSION['sql_console_log']);
					unset($_SESSION['sql_console_command']);
					unset($_SESSION['sql_console_setting']);
					if($this->dynamic){header('location:?sql_dynamic=ok'); exit;}
					header('location:?'); exit;
					break;
				case 'setting_on':
					$_SESSION['sql_console_setting']='on';
					break;
				case 'setting_off':
					unset($_SESSION['sql_console_setting']);
					break;
				case 'setting_test':
					$setting= ['host'=>$this->host,'user'=>$this->user,'pass'=>$this->pass,'base'=>$this->base];
					if(isset($_GET['host'])){$this->host = $setting['host'] = $_GET['host'];}
					if(isset($_GET['user'])){$this->user = $setting['user'] = $_GET['user'];}
					if(isset($_GET['pass'])){$this->pass = $setting['pass'] = $_GET['pass'];}
					if(isset($_GET['base'])){$this->base = $setting['base'] = $_GET['base'];}
					setcookie('sql_connect_setting',serialize($setting),time()+(60*60*24*360));
					break;
				case 'setting_default':
					setcookie('sql_connect_setting','',time());
					break;
			}
		}

		if(isset($_GET['sql_code']) and isset($_GET['send'])){
			if(strpos($_GET['sql_code'],'drop') !== false and strpos($_GET['sql_code'],'database') !== false){
				$dropDatabase = true;
			}
			if(strpos($_GET['sql_code'],'use')!==false){
				$str = str_replace([chr(13),chr(10)],' ',$_GET['sql_code']);
				$arr = explode(' ',$str);
				foreach($arr as $v){
					if($v!='' and $v!='use' and $v!=';'){
						$this->base = trim($v,';');
						$use = true;
						break;
					}
				}
			}
			$result = $this->sql_connect($_GET['sql_code']);
			switch(gettype($result)){
				case "object":
					if($result!=false){
						$this->result = $this->sql_result_display($result);
					}
					break;
				case 'NULL':
					if(isset($use)){
						$_SESSION['sql_console_base'] = $this->base = '';
					}
					break;
				case "boolean":
					if($result == true){
						if(isset($dropDatabase)){
							$this->base = $_SESSION['sql_console_base'] = '';
						}
						if(isset($use)){
							$_SESSION['sql_console_base'] = $this->base;
						}
						$this->result = "Query OK, ({$this->time} сек)".chr(10);
					}
					break;
			}

			if($_GET['sql_code'] != ''){
				$_GET['sql_code'] = sql_edit_command($_GET['sql_code']);
				sql_console_command($_GET['sql_code']);
			}
			$full = 'mysql> '.sql_tab($_GET['sql_code']).chr(10).$this->result.chr(10).$_SESSION['sql_console_log'];
			$this->result = $_SESSION['sql_console_log'] = $full;
		}

		if($this->dynamic){
			if(add_script()==false){
				$this->error_script = 'Скрипты не подключены!';
				$this->dynamic = false;
			}
			if(isset($_GET['sql_dynamic'])){$this->dynamicView=true;}
		}

		if(isset($_SESSION['sql_console_setting'])){
			$this->view = $this->viewSetting();
		}else{
			$this->view = $this->viewHome();
		}
		if($this->dynamicView){echo $this->view; exit;}
	}//start() - Стартовая Функция для обработки Функционала.

	public function view(){
		echo <<<NEC
<style>
//body{background:#222;}
#sql_form{text-align:center;background:#335;}
.b1{padding:2px 0; }
#text{width:98%;height:110px;color:#ff0;background:#222;border:1px solid #777;outline:none;}
//#text:hover{border-color:#f99;}
#text:focus{border:1px solid #99f;}
#pre{background:#222;padding:0 10px; margin:0; color:#99f;text-align:left;}
.base{text-shadow:0 0 3px #000,0 0 3px #000,0 0 3px #000,0 0 3px #000; color:#99f;}
.key{background:#222; color:#99f; border:1px solid #99f; outline:none;padding:1px 5px;}
.key{display:inline-block; margin:0; min-width:15px;}
.key:hover{background:#000;}
.key:active{color:#00f;}
#table{;margin:0 auto;}
#table input[type='text']{background:#222; color:#99f; border:1px solid #99f; outline:none;padding:1px 5px;}
#table input[type='text']:hover{border-color:#f99;}
#table input[type='text']:focus{border-color:#ff9;color:#ff9;}
</style>
<div style='background:#f00;color:#fff;text-align:center;'>{$this->error_script}</div>
<form method='GET' id='sql_form' name='SQL'>$this->view</form>
NEC;
	}//view() - Функция для вывода содержимого на экран.

	private function viewHome(){
		$ok="type='submit' name='send' value='ok'";
		$up="type='submit' name='sql_key' value='up'";
		$down="type='submit' name='sql_key' value='down'";
		$clear="type='submit' name='sql_key' value='clear'";
		$setting_on="type='submit' name='sql_key' value='setting_on'";
		if($this->dynamic){
			$ok="type='button' onclick=sql_key('send=ok',SQL)";
			$up="type='button' onclick=sql_key('sql_key=up',SQL)";
			$down="type='button' onclick=sql_key('sql_key=down',SQL)";
			$clear="type='button' onclick=sql_key('sql_key=clear',SQL)";
			$setting_on="type='button' onclick=sql_key('sql_key=setting_on',SQL)";
		}
		$base = ''; $number='';
		if($this->base!=''){$base = "Database: <b>{$this->base}</b>;";}
		if($this->number!=-3){$number="<input type='hidden' name='sql_number' value='{$this->number}'>";}
		return <<<NEC
	<div class='b1'></div>
	<textarea id='text' name='sql_code'>{$this->code}</textarea>
	<span class='base'>$base</span>
	<button class='key' $ok >Отправить</button>
	<button class='key' $up >↓</button>
	<button class='key' $down >↑</button>
	<button class='key' $clear >Очистить</button>
	<button class='key' $setting_on >Настройки</button>
	<div class='b1'></div>
	<pre id='pre'>{$this->result}</pre>
	{$number}
NEC;
	}//viewHome() -

	private function viewSetting(){
		$setting_test="type='submit' name='sql_key' value='setting_test'";
		$setting_default="type='submit' name='sql_key' value='setting_default'";
		$setting_off="type='submit' name='sql_key' value='setting_off'";
		if($this->dynamic){
			$setting_test="type='button' onclick=sql_key('sql_key=setting_test',SQL)";
			$setting_default="type='button' onclick=sql_key('sql_key=setting_default',SQL)";
			$setting_off="type='button' onclick=sql_key('sql_key=setting_off',SQL)";
		}
		$this->sql_connect();
		$error = $this->error_connect;
		switch($error) {
			case 0: $error = "Успешно соединено!"; break;
			case 1045: $error = "Ошибка ($error): Неправильный логин или пароль!"; break;
			case 1049: $error = "Ошибка ($error): Неправильное имя базы данных!"; break;
			case 2002: $error = "Ошибка ($error): Неправильно указан хост!"; break;
		}
		return <<<NEC
	<div class='base'>$error</div>
	<table id='table'>
		<tr><td class='base'>Host:</td><td><input type='text' name='host' value='{$this->host}'></td></tr>
		<tr><td class='base'>User:</td><td><input type='text' name='user' value='{$this->user}'></td></tr>
		<tr><td class='base'>Pass:</td><td><input type='text' name='pass' value='{$this->pass}'></td></tr>
		<tr><td class='base'>Base:</td><td><input type='text' name='base' value='{$this->base}'></td></tr>
	</table>
	<button class='key' $setting_test >Сохранить</button>
	<button class='key' $setting_default >Стандартные</button>
	<button class='key' $setting_off >Назад</button>
	<div class='b1'></div>
NEC;
	}//viewSetting()

	public function script(){
		if(isset($_SESSION['sql_console_script'])){
			$script = <<<NEC
function sql_key(key,form){
	var str = _form(form);
	_xml('?'+key+'&sql_dynamic=ok'+str,'sql_form');
}
NEC;
			return $_SESSION['sql_console_script'].$script;
		}
		return '';
	}//script() - 
}//end class SQL_Console;
/*<|SQL_Console*/

/*>|RecBios*/
class RecBios{
	public $dynamic=false;
	public $ver="1.4"; //от 12.12.16
	public $name="Rec Bios";
	public $info="Установка и переустановка Bios.";
	public $desc=<<<NEC
21.12.16 v1.1 - Адаптация под оболочку PhpWav4 v1.1
22.12.16 v1.2 - Доб. имя и описание, исправлен вывода формы, доб. стили .
12.04.17 v1.3 - Доб.описание bios.
20.06.17 v1.4 - Переделан вывод, переделан под метод GET, добавлен динамический режим.
NEC;

	private $view="";
	private $error='';

private function deleted(){
	$cont=file(__FILE__);
	$t=""; $z=true;
	foreach($cont as $v){
		if(substr($v,0,9)=='/*|bios*/'){$t.='/*|bios*/ $biosPW4="";'.chr(10); continue;}
		if(substr($v,0,9)=='/*|Bios*/'){$z=false; $t.=$v; continue;}
		if(substr($v,0,12)=='/*|EndBios*/'){$z=true;}
		if($z==true){$t.=$v;}
	}
	$f=fopen(__FILE__,'w'); fwrite($f,$t); fclose($f);
	$this->view=$this->messageView('Bios удален!');;
}

private function install($p,$k){
	$cont=file(__FILE__);
	$t=""; $z=true;
	foreach($cont as $v){
		if(substr($v,0,9)=='/*|bios*/'){$t.='/*|bios*/ $biosPW4="'.$k.'";'.chr(10); continue;}
		if(substr($v,0,9)=='/*|Bios*/'){$z=false; $t.=$v; continue;}
		if(substr($v,0,12)=='/*|EndBios*/'){$z=true; $t.=$p.chr(10);}
		if($z==true){$t.=$v;}
	}
	$f=fopen(__FILE__,'w'); fwrite($f,$t); fclose($f);
	$this->view=$this->messageView('Установка завершена!');
}

public function script(){
		$s="function xmlRB(s){_xml('?dynamic=ok&'+s,'formRB');}";
		if($_SESSION['RecBios_script']!==false){return $_SESSION['RecBios_script'].$s;}
		return '';
	}

public function start(){
	function add_script(){
		$s='ScriptPack';
		if(class_exists($s) and method_exists($s,$s)){
			$s = (new $s)->$s('_xml');
			if($s!==false){
				$_SESSION['RecBios_script']=$s;
				return true;
			}
		}
		return false;
	}//add_script() - Загружаем скрипты из ScriptPack
	if(session_id()==false){session_start();}
	$_SESSION['RecBios_script'] = false;
	if($this->dynamic and add_script()==false){
		$this->error = "Скрипты не подгружены!";
		$this->dynamic = false;
	}
	if(isset($_GET['keyRB'])){
		switch($_GET['keyRB']){
			case 'insView': $this->view=$this->installView(); break;
			case 'delView': $this->view=$this->deletedView(); break;
			case 'deleted': $this->deleted(); break;
			case 'descView': $this->view=$this->descriptionView(); break;
		}
	}
	if(!empty($_GET['insRB'])){
		if(file_exists($_GET['insRB'])){
			include($_GET['insRB']);
			if(isset($packBios) and isset($keyBios)){
				$this->install($packBios,$keyBios);
			}
		}
	}
	if($this->view==''){$this->view = $this->homeView();}
	if(isset($_GET['dynamic'])){echo $this->view; exit;}
}//start() - Стартовая Функция для обработки Функционала.

public function view(){
	echo <<<NEC
<style>
#formRB{border:1px solid #333; background:#444;padding:5px; color:#bfb;}
#formRB b{color:#ffb;}
#formRB button,#formRB input{
border:1px solid #999; background:#333; color:#fff;
outline:none; margin:5px 2px 0 2px; padding:5px;
}
#formRB button:hover,#formRB input:hover{color:#ffb; background:#222;}
#formRB button:active,#formRB input:active{color:#bfb;}
#formRB input{width:100%; padding:2px 5px; margin:1px; text-align:left;}
</style>
<div style='color:#fff;background:#f00;text-align:center;'>{$this->error}</div>
<form id='formRB' method='GET'>{$this->view}</form>
NEC;
}//view() - Функция для вывода содержимого на экран.

private function descriptionView(){
	global $biosPW4;
	if(class_exists($biosPW4) and property_exists($biosPW4,'desc')){
		$o=new $biosPW4;
		$t ="<b>$biosPW4</b> v{$o->ver}<br>";
		foreach(explode(chr(10),$o->desc) as $v){
			$a=explode('-',$v);
			$t.="<b>".$a[0]."</b>-".$a[1]."<br>";
		}
		$key = "name='keyRB' value='home'";
		if($this->dynamic){$key="type='button' onclick=xmlRB('keyRB=nome')";}
		return $t."<button style='padding:5px 10px;' $key >Ок</button>";
	}
	return '';
}//descriptionView()

private function messageView($s){
	$key="name='keyRB' value='home'";
	if($this->dynamic){$key="type='button' onclick=xmlRB('keyRB=nome')";}
	return "<b>$s</b><br><button style='padding:5px 10px;' $key >Ок</button>";
}//messageView()

private function deletedView(){
	$t ="<b>Удалить bios?</b><br>";
	$key="name='keyRB' value='home'";
	$del="name='keyRB' value='deleted'";
	if($this->dynamic){
		$key="type='button' onclick=xmlRB('keyRB=nome')";
		$del="type='button' onclick=xmlRB('keyRB=deleted')";
	}
	$t.="<button $del >Удалить</button>";
	return $t."<button $key >Отмена</button>";
}//deletedView()

private function installView(){
	$cont=scandir('.');
	$p="<b>Выберите файл для установки:</b>";
	foreach($cont as $v){
		if(is_file($v)){
			$f=fopen($v,'r'); $t=fread($f,13); fclose($f);
			if(substr($t,8)=='00|00'){
				if($this->dynamic){
					$p.="<input type='button' onclick=xmlRB('insRB=$v') value='$v'>";
				}else{
					$p.="<input type='submit' name='insRB' value='$v'>";
				}
			}
		}
	}
	$key="name='keyRB' value='home'";
	if($this->dynamic){$key="type='button' onclick=xmlRB('keyRB=nome')";}
	return ($p."<button style='padding:5px 10px;' $key >Отмена</button>");
}//installView()

private function homeView(){
	global $biosPW4;
	$ins="name='keyRB' value='insView'";
	$del="name='keyRB' value='delView'";
	$desc="name='keyRB' value='descView'";
	if($this->dynamic){
		$ins="type='button' onclick=xmlRB('keyRB=insView')";
		$del="type='button' onclick=xmlRB('keyRB=delView')";
		$desc="type='button' onclick=xmlRB('keyRB=descView')";
	}
	if(!empty($biosPW4) and class_exists($biosPW4)){
		$ver="???";
		if(property_exists($biosPW4,'ver')){$o=new $biosPW4; $ver=$o->ver;}
		$t ="<b>Bios имя:</b> $biosPW4, <b>версия:</b> $ver .<br>";
		$t.="<button $ins >Переустановить</button>";
		$t.="<button  $del >Удалить</button>";
		if(property_exists($biosPW4,'desc')){
			$t.="<button  $desc >Описание</button>";
		}
		return $t;
	}else{
		return "Нет Bios!<br> <button $ins >Установить</button>";
	}

}//homeView()

}//end class rec_bios;
/*<|RecBios*/

/*>|PackNEC*/
class PackNEC{
	public $dynamic = false;
	public $name='PackNEC';
	public $info="Упаковщик программ для PhpWav4";
	public $ver="1.14";
	public $desc=<<<NEC
10.11.16 v1.01 - Добавлено сохранение boot .
07.12.16 v1.02 - Исправлен boot из за переименования /*|boot*/ .
09.12.16 v1.03 - Добавлена версия в имени сохраняемого файла .
10.12.16 v1.04 - Добавлена упаковка bios .
12.12.16 v1.05 - Исправлена упаковка bios .
21.12.16 v1.06 - Адаптация под оболочку PhpWav4 v1.1
22.12.16 v1.07 - Добавление и правка стилей.
24.12.16 v1.08 - Добавлено описание программы и правка стилей.
28.12.16 v1.09 - Исправлено добавления версии к имени файла для сохранения.
07.02.17 v1.10 - Испр. упаковка пустого БИОС.
08.02.17 v1.11 - Доб. проверка сохраняемого файла на существование.
12.02.17 v1.12 - Доб. запись имени, версии и информации в упакованный файл для дальнейшего пред просмотра.
12.04.17 v1.13 - Доб. проверка имени сохраняемого БИОС на перезапись.
07.06.17 v1.14 - Переделан под метод GET, переделан вывод, добавлен динамический режим.
NEC;

	private $view='home';
	private $error='';

private function saveProgram(){
	if(!empty($_GET['nameFile']) and !empty($_GET['nameClass']) and !empty($_GET['key'])){
		$key=$_GET['key']; $p=false; $g=false; $nec=true; $dec=true; $text=''; global $bootPW4;
		foreach(file(__FILE__) as $v){
			if(substr($v,0,strlen($key)+6)=="/*>|$key*/"){$p=true; $g=true; continue;}
			if(substr($v,0,strlen($key)+6)=="/*<|$key*/"){$p=false;}
			if($g){$g=false; continue;}
			if($p==true){
				for($i=0;$i<strlen($v);$i++){
					switch(substr($v,$i,1)){case '\\': case '$': $text.='\\';}
					$text.=substr($v,$i,1);
				}
			}
			if(substr($v,0,4)=='NEC;'){$nec=false;}
			if(substr($v,0,4)=='DEC;'){$dec=false;}
		}//foreach

		if(!$p){
			$key=$_GET['nameClass'];
			if($nec){$nec="NEC";}elseif($dec){$nec='DEC';}else{$nec='TEC';}
			$t="<?php /*000||*/".chr(10)."\$key='$key';".chr(10);
			if(property_exists($key,'name')){$o=new $key; $t.='$name="'.$o->name.'";'.chr(10);}
			if(property_exists($key,'ver')){$o=new $key; $t.='$ver="'.$o->ver.'";'.chr(10);}
			if(property_exists($key,'info')){$o=new $key; $t.='$info="'.$o->info.'";'.chr(10);}
			if(in_array($_GET['key'],$bootPW4)){$t.='$boot=true;'.chr(10);}
			$t.="\$program=<<<$nec".chr(10)."class $key{";
			$t.=chr(10).$text."$nec;".chr(10);
			if(isset($_GET['installPack'])){
				$t=$t.chr(10).'//test';
			}
			$f=fopen($_GET['nameFile'],'w'); fwrite($f,$t); fclose($f);
			$this->messageView('Сохранено');
		}//if $p
	}
}//saveProgram() - сохранение программы

private function saveBios($s=null){
	$cont=file(__FILE__);
	global $biosPW4;
	$ver=""; $t=''; $nec=true; $dec=true; $z=false;
	foreach($cont as $v){
		if(substr($v,0,9)=='/*|Bios*/'){$z=true; continue;}
		if(substr($v,0,12)=='/*|EndBios*/'){break;}
		if($z==true){
			for($i=0;$i<strlen($v);$i++){
				switch(substr($v,$i,1)){case '\\': case '$': $t.='\\';}
				$t.=substr($v,$i,1);
			}
			if(substr($v,0,4)=='NEC;'){$nec=false;}
			if(substr($v,0,4)=='DEC;'){$dec=false;}
		}

	}
	if($z==true){
		if(empty($t)){
			$this->messageView('Bios не найден!');
		}else{
			if($nec==true){$nec="NEC";}elseif($dec==true){$nec='DEC';}else{$nec='TEC';}
			$t='<?php /*00|00*/'.chr(10)
				.'$keyBios="'.$biosPW4.'";'.chr(10)
				.'$packBios=<<<'.$nec .chr(10).$t."$nec;".chr(10);
			if(property_exists($biosPW4,'ver')){$o=new $biosPW4; $ver="_v".$o->ver;}
			$file=$biosPW4.$ver."_pack.php";
			if(!file_exists($file) or $s=='remove'){
				$f=fopen($file,'w'); fwrite($f,$t); fclose($f);
				$this->messageView('Сохранено');
			}else{
				$this->file=$file;
				$this->removeBios();
			}
		}

	}
}//saveBios() - сохранение БИОС.

private function nameFileTest(){
	if(!empty($_GET['nameFile'])){
		if(file_exists($_GET['nameFile'])){
			$this->removeView();
		}else{
			$this->saveProgram();
		}
	}
}//nameFileTest() - Проверка на существования файла по имени.

private function script_pack(){
	$scr = 'ScriptPack';
	if(class_exists($scr) and method_exists($scr,$scr)){
		$scr = (new $scr)->$scr(['_xml','_form']);
		if($scr !== false){
			$_SESSION['PackNEC_script'] = $scr;
			return true;
		}
	}
	return false;
}//script_pack() - подключение скриптов из ScriptPack

public function script(){
	$script = <<<NEC
function packNec(key,form){
	var url = _form(form);
	_xml(key+url,'packNec');
}
NEC;
	if($this->dynamic==true and $_SESSION['PackNEC_script']!=false){
		return $_SESSION['PackNEC_script'].$script;
	}
	return 'var PackNEC = "Not script";';
}//script() - Скрипты.

public function start(){
	if(session_id() == false){session_start();}
	$_SESSION['PackNEC_script'] = false;
	if($this->dynamic == true){
		if($this->script_pack() == false){
			$this->dynamic = false;
			$this->error='Скрипты не подключены!';
		}
	}
	$view = true;
	if(isset($_GET['keyNecDynamic'])){$_GET['keyNec']=$_GET['keyNecDynamic']; $view = false;}
	if(isset($_GET['keysNecDynamic'])){$_GET['keysNec']=$_GET['keysNecDynamic']; $view = false;}
	ob_start();
	if(isset($_GET['keyNec'])){$this->viewForm($_GET['keyNec']);}
	if(isset($_GET['keysNec'])){
		switch($_GET['keysNec']){
			case 'saveProgram': $this->nameFileTest(); break;
			case 'home': $this->viewHome(); break;
			case 'saveBios': $this->saveBios(); break;
			case 'info': $this->info(); break;
			case 'remove': $this->saveProgram(); break;
			case 'removeBios': $this->saveBios('remove'); break;
			case 'errorScript': $this->messageView('Скрипты не подключены!'); break;
		}
	}
	$this->view = ob_get_clean();
	if($this->view == ''){
		ob_start();
		$this->viewHome();
		$this->view = ob_get_clean();
	}
	if($this->error!=''){
		$error = "<div id=error>{$this->error}</div>";
		$this->view = $error.$this->view;
	}
	if($this->dynamic and $view === false){echo $this->view; exit;}
}//start() - Стартовая Функция для обработки Функционала.

public function view(){
	echo <<<NEC
<style>
#packNec{background:#ffc;padding:2px;}
#headNec,#footerNec,#messageNec,#formNec{background:#444;padding:2px 5px;color:#fff;}
#keysNec{margin:0;}
.keyNec{
	display:block; border:1px solid #333; outline:none; background:#777; padding:1px 5px;
	width:100%; margin:1px 0; color:#ff9; text-align:left;
	}
#footerNec{font-size:14px;}
#footerNec span{float:right; text-align:right; display:inline-block;}
.keyFooNec{outline:none;background:none;border:none;padding:0;text-decoration:underline;color:#ff9;}
.keyNec:hover,.keyNecOk:hover,#formNec button:hover{color:#9f9;background:#666;}
.keyFooNec:hover{color:#9f9;}
.keyNec:active,.keyFooNec:active,.keyNecOk:active,#formNec button:active{color:#faa;}
#messageNec{border:1px solid #333; padding:5px 5px; text-align:center;}
.keyNecOk,#formNec button{
	margin:5px 0; outline:none; border:1px solid #333; background:#777; color:#ff9; padding:5px 10px;
	}
#formNec{padding:10px 0px;}
#formNec div{width:260px; margin:0 5px;}
#formNec input{
	display:block; width:97%; margin:0 0 10px 0;
	background:#555; color:#fff;
	border:1px solid #ff9; border-radius:5px;
	padding: 2px 5px;
}
#formNec input:focus,#formNec input:hover{
	outline:none; background:#777; border-color:#333;
}
#formNec label{color:#ff9; font-size:14px;}
#formNec button{margin:0;}
#infoNec{
	outline:none; background:none; border:none; color:#fff;
	float:right; text-align:right; padding:0;
}
#infoNec:hover{text-decoration:underline;}
#infoNec:active{color:#99f;}
#error{background:#f00; color:#fff;text-align:center; margin-bottom:2px;}
</style>
NEC;

	echo "<form id=packNec name=NEC method=GET>{$this->view}</form>";
}//view() - Функция для вывода содержимого на экран.

private function info(){
	$t="<span style='color:#9f9;'><b>$this->name ver $this->ver</b></span>"
		." - <span style='color:#f99;'>$this->info</span> <br>";
	foreach(explode(chr(10),$this->desc) as $v){
		$arr=explode("-",$v);
		$arr[0]="<span style='color:#ff9;'>".$arr[0]."</span>";
		$t.=implode("-",$arr)."<br>";
	}
	$this->messageView("<div style='text-align:left;font-size:13px; margin-bottom:-10px;'>".$t."</div>");
}//info() - Вывод информации о программе.

private function viewHome(){
	global $keysPW4;
	echo '<div id=headNec>Выберите программу для упаковки:</div><div id=keysNec>';
	foreach($keysPW4 as $v){
		$o=new $v; if(isset($o->name)){$n=$o->name;}else{$n=$v;}
		if($this->dynamic){
			echo "<button class=keyNec type=button onclick=_xml('?keyNecDynamic=$v','packNec') >$n</button>";
		}else{
			echo "<button class=keyNec type=submit name='keyNec' value='$v'>$n</button>";
		}
	}
	$saveBios ="type='submit' name='keysNec' value='saveBios'";
	$info ='name=keysNec type=submit value=info';
	if($this->dynamic){
		$saveBios ="type=button onclick=_xml('?keysNecDynamic=saveBios','packNec')";
		$info ="type=button onclick=_xml('?keysNecDynamic=info','packNec')";
	}
	echo "</div><div id='footerNec'>
		<button class='keyFooNec' $saveBios >Упаковать Bios</button>
		<button id=infoNec $info>{$this->name} v:{$this->ver}</button>
	</div>";
}//viewHome() -

private function removeView(){
	if(!empty($_GET['nameFile']) and !empty($_GET['nameClass']) and !empty($_GET['key'])) {
		$key = $_GET['key'];
		$nameFile = $_GET['nameFile'];
		$nameClass = $_GET['nameClass'];
		$keyNec = "type=submit value='$key' name=keyNec";
		$remove = "type=submit value=remove name=keysNec";
		if($this->dynamic){
			$s="&nameFile=$nameFile&nameClass=$nameClass&key=$key";
			$keyNec = "type=button onclick=_xml('?keyNecDynamic={$key}','packNec')";
			$remove = "type=button onclick=_xml('?keysNecDynamic=remove{$s}','packNec')";
		}
		echo <<<NEC
<div id='messageNec' style='color:#fff;'>Файл с именем <span style='color:#ff9;'>$nameFile</span> существует!!!<br>
	<button class='keyNecOk' $remove >Перезаписать</button>
	<button class='keyNecOk' $keyNec >Назад</button>
	<input type='hidden' name='nameFile' value='$nameFile'>
	<input type='hidden' name='nameClass' value='$nameClass'>
	<input type='hidden' name='key' value='$key'>
</div>
NEC;
	}
}//removeView() - Вывод сообщения на перезапись файла.

private function messageView($str){
	echo "<div id='messageNec'>$str<br>";
	if($this->dynamic){
		echo "<button class='keyNecOk' type=button onclick=_xml('?keysNecDynamic=home','packNec') >Ок</button>";
	}else{
		echo "<button class='keyNecOk' type=submit value=home name=keysNec>Ок</button>";
	}
	echo "</div>";
}//messageView() - Вывод сообщения с кнопкой Ок.

private function viewForm($key){
	$ver=''; $o=new $key; if(isset($o->ver)){$ver="_v".$o->ver;} unset($o);
	$home = "type=submit name=keysNec value='home'";
	$save = "type=submit name=keysNec value='saveProgram'";
	if($this->dynamic){
		$home = "type=button onclick=_xml('?keysNecDynamic=home','packNec')";
		$save = "type=button onclick=packNec('?keysNecDynamic=saveProgram',NEC)";
	}
	echo <<<NEC
<div id="formNec"><div>
<label>Имя класса:</label>
<input type=text name='nameClass' value='$key'>
<label>Имя сохраняемого файла:</label>
<input type=text name='nameFile' value='{$key}{$ver}_pack.php'>
<!--name prog: <input type=text name=name><br>-->
<!--Упаковать в установщик: <input type='checkbox' name='installPack'><br-->
<button $save >Сохранить</button>
<button $home >Отмена</button>
<input type='hidden' name='key' value='$key'>
</div></div>
NEC;
}//viewForm

private function removeBios(){
	if(empty($this->file)){$this->file=null;}
	$home = "type=submit name=keysNec value='home'";
	$removeBios = "type=submit value='removeBios' name=keysNec";
	if($this->dynamic){
		$home = "type=button onclick=_xml('?keysNecDynamic=home','packNec')";
		$removeBios = "type=button onclick=_xml('?keysNecDynamic=removeBios','packNec')";
	}
	echo <<<NEC
<div id='messageNec' style='color:#fff;'>Файл с именем <span style='color:#ff9;'>{$this->file}</span> существует!!!
	<br>
	<button class='keyNecOk' $removeBios >Перезаписать</button>
	<button class='keyNecOk' $home >Отмена</button>
</div>
NEC;
}
}//class packNec;
/*<|PackNEC*/

/*>|File_Editor*/
class File_Editor{
	//public $style=true; //- ??? пока применений нет!
    public $dynamic=false; //- Для динамического отображения.
    public $name="File Editor"; //- Имя программы для отображения.
	public $info="Редактор файлов"; //- Описание программы.
	public $ver="1.04_beta"; //- Версия программы.
	public $desc=<<<NEC
19.06.17 v1.00 - Выпуск File Editor .
20.06.17 v1.01 - Исправлен вывод скриптов, исправлено сохранение, добавлено кнопка 'Новый' файл и вывод кнопок.
20.06.17 v1.02 - Переделан вывод ошибки, добавлен фильтр для Амперсанд.
21.06.17 v1.03 - Добавлен вывод ошибки подключения скриптов скриптов.
03.07.17 v1.04 - Правка стилей, добавлен переход по деректориям
NEC;

	private $view='';
	private $text='';
	private $nameFile='';
	private $dirFile='';
	private $errorScript=false;

	private function open($n){
		if(file_exists($n)){
			if(filesize($n)>(1024*64)){
				unset($_SESSION['FileEditor_file']);
				$this->view = $this->message("Превышен допустимый размер файла!");
			}else{
				if(filesize($n)>0){
					$f=fopen($n,'r'); $s = fread($f,filesize($n)); fclose($f);
					$s = str_replace('&','&amp;',$s);
					$s = str_replace('<','&lt;',$s);
					$this->text = $s;
				}
				$this->nameFile = $_SESSION['FileEditor_file'] = $n;
			}
		}else{
			unset($_SESSION['FileEditor_file']);
			$this->view = $this->message('Файл не найден!');
		}
	}//open()

	public function start(){
		function add_script(){
			$s='ScriptPack'; //v1.02
			if(class_exists($s) and method_exists($s,$s)){
				$s = (new $s)->$s(['_xml','_form']);
				if($s!==false){
					$_SESSION['FileEditor_script']=$s;
					return true;
				}
			}
			return false;
		}//add_script() - Загружаем скрипты из ScriptPack
		if(session_id()==false){session_start();}
		$_SESSION['FileEditor_script']=false;
		if(empty($_SESSION['FileEditor_dir'])){$_SESSION['FileEditor_dir']=__DIR__.DIRECTORY_SEPARATOR;}
		if(isset($_POST['text'])){$_SESSION['FileEditor_text']=$_POST['text'];}
		if($this->dynamic and add_script()==false){$this->dynamic=false; $this->errorScript=true;}
		if(isset($_POST['key'])){
			switch($_POST['key']){
				case 'new':
					unset($_SESSION['FileEditor_file']);
					break;
				case 'open': $this->view = $this->openView(); break;
				case 'saveAs': $this->view=$this->saveAsView(); break;
				case 'save':
					if(!empty($_POST['file']) and isset($_SESSION['FileEditor_text'])){
						$_SESSION['FileEditor_file'] = $_POST['file'];
						$f=fopen($_POST['file'],'w');
						fwrite($f,$_SESSION['FileEditor_text']); fclose($f);
						$this->view=$this->message('Файл сохранен');
					}else{
						$this->view=$this->message('Ошибка сохранения!');
					}
					break;
			}
		}//if isset POST key
		if(isset($_POST['open']) and file_exists($_SESSION['FileEditor_dir'].$_POST['open'])){
//			$_SESSION['FileEditor_nameFile'] = $_POST['open'];
//			$_SESSION['FileEditor_dirFile'] = $_SESSION['FileEditor_dir'];
			$_SESSION['FileEditor_file'] = $_SESSION['FileEditor_dir'].$_POST['open'];
		}
		if(isset($_POST['dir'])){
			if($_POST['dir']=='..'){
				$arr=explode(DIRECTORY_SEPARATOR,$_SESSION['FileEditor_dir']);
				unset($arr[(count($arr)-2)]);
				$_SESSION['FileEditor_dir']=implode(DIRECTORY_SEPARATOR,$arr);
			}else{
				$_SESSION['FileEditor_dir']=$_SESSION['FileEditor_dir'].$_POST['dir'].DIRECTORY_SEPARATOR;
			}
			$this->view=$this->openView();
		}

		if(isset($_SESSION['FileEditor_file'])){$this->open($_SESSION['FileEditor_file']);}

		if($this->view == ''){$this->view = $this->homeView();}

		if($this->dynamic==false){
			$e='Включите поддержку динамического режима!';
			if($this->errorScript){$e='Скрипты не подключены!';}
			$style="style='color:#fff;background:#f00;text-align:center;'";
			$this->view = "<div $style>$e</div>";
		}
		if(isset($_POST['dynamic'])){echo $this->view; exit;}
	}//start() - Стартовая Функция для обработки Функционала.

	public function view(){
		echo <<<NEC
<style>
#formFE{background:#ffd;padding:3px;}
.input{outline:none; border:1px solid #999; padding:2px; border-radius:3px;}
.line{height:3px;}
.text{color:#ff9;text-shadow:0 0 3px #000,0 0 3px #000,0 0 3px #000}
.key{background:#333;color:#ff9;border:1px solid #000; outline:none;padding:3px 5px;display:inline-block;}
.key:hover{background:#555;}
.key:active{color:#ffd;}
#textarea{width:98%;min-height:70vh;}
#keysOpen{background:#555; padding:1px 2px;}
.keyOpen,.keyDir{
	display:block; background:#333; color:#ff9; width:100%; outline:none;
	text-align:left; border:1px solid #000; margin:1px 0; padding:1px 2px;
}
.keyDir{color:#fc9;}
.keyOpen:hover,.keyDir:hover{background:#555;}
.keyOpen:active,.keyDir:active{color:#ffd;}
</style>
<form id="formFE" name='FFE' method='POST'>
	{$this->view}
</form>
NEC;
	}//view() - Функция для вывода содержимого на экран.

	private function homeView(){
		$save=''; $new="";
		$file="<input type='hidden' name='file' value='new.txt'>";
		if($this->nameFile!==''){
			$file="<input type='hidden' name='file' value='{$this->nameFile}'>";
			$save="<button class='key' type='button' onclick=form_FE('key=save',FFE) >Сохранить</button>";
			$new="<button class='key' type='button' onclick=form_FE('key=new',FFE) >Новый</button>";
		}
		return <<<NEC
	<div>
		$new
		<button class='key' type='button' onclick=form_FE('key=open',FFE) >Открыть</button>
		$save
		<button class='key' type='button' onclick=form_FE('key=saveAs',FFE) >Сохранить как</button>
		<span class='text'>{$this->nameFile}</span>
		$file
	</div>
	<div class='line'></div>
	<div style='text-align:center;'>
		<textarea id='textarea' name='text'>{$this->text}</textarea>
	</div>
NEC;
	}//homeView()

	private function openView(){
		$dir=$file=[];
		foreach(scandir($_SESSION['FileEditor_dir']) as $v){
			if(is_dir($_SESSION['FileEditor_dir'].$v) and $v!='.'){$dir[$v]=['keyDir','dir',$v];}
			if(is_file($_SESSION['FileEditor_dir'].$v)){$file[$v]=['keyOpen','open',$v];}
		}
		$res='';
		foreach(array_merge($dir,$file) as $k=>$v){
			$res.="<button class='{$v[0]}' type='button' onclick=form_FE('{$v[1]}=$k',FFE) >$k</button>";
		}
		return <<<NEC
			<button class='key' type='button' onclick=form_FE("key=home",FFE)>Отмена</button>
			<span class='text'>open file: {$_SESSION['FileEditor_dir']}</span>
			<div class='line'></div>
			<div id='keysOpen'>
				$res
			</div>
NEC;
	}// openView()

	private function message($s){
		return <<<NEC
	<div style='text-align:center;'>
		<div class='text'>$s</div>
		<div class='line'></div>
		<button class='key' type='button' onclick=form_FE("key=home",FFE) >ok</button>
	</div>
NEC;
	}//message()

	private function saveAsView(){
		$name=''; if(isset($_POST['file'])){$name = $_POST['file'];}
		return <<<NEC
	<div style='text-align:center;'>
		<div class='text'>Сохранить файл как:</div>
		<div class='line'></div>
		<input class='input' type='text' name='file' value='$name'>
		<div class='line'></div>
		<div>
			<button class='key' type='button' onclick=form_FE("key=save",FFE) >Сохранить</button>
			<button class='key' type='button' onclick=form_FE("key=home",FFE) >Отмена</button>
		</div>
	</div>
NEC;
	}//saveAsView()

//	public function boot(){}//boot() - Boot приложение.

	public function script(){
		$script = <<<NEC
function form_FE(key,form){
	var str = _form(form);
	_xml(key+"&dynamic=ok"+str,"formFE",true);
}
NEC;
		if($_SESSION['FileEditor_script']!==false){return $_SESSION['FileEditor_script'].$script;}
		return '';
	}//script() - Скрипты.
}//end class File_Editor;
/*<|File_Editor*/

/*>|PhpWav4_v3*/
class PhpWav4_v3{
	public $name="PhpWav4 v3";
	public $ver="3.08";//от 22.12.16;
	public $info="PhpWav v3 OC";
	public $desc=<<<NEC
26.12.16 v3.01 - Добавлена домашняя страница
06.01.17 v3.02 - Переим. функц. PW4v3() на _xml(), добв. центральный блок, Функц. _message, _form
12.01.17 v3.03 - Список программ выводит только программы и + мелкие исправления в коде.
30.04.17 v3.04 - Доб. динамическая загрузка скриптов, удалены: _xml(), _message, _form .
07.05.17 v3.05 - Переделан вывод через буфер вывода.
07.06.17 v3.06 - Доб. поддержка динамических приложений.
23.06.17 v3.07 - Добавлены настройки!
30.06.17 v3.08 - Добавлена кроссбраузерность xmlHttp запросам.
NEC;

	private $view="";
	private $script="";

	private $PW4v3dynamic = true;

private function keys(){
	global $keysPW4; $keys=''; $head='';
	foreach ($keysPW4 as $v){
		if(property_exists($v,'name')){$o=new $v; $n=$o->name; unset($o);}else{$n=$v;}
		if(property_exists($v,'info')){$o=new $v; $t=$o->info;}else{$t='';}
		if(isset($_SESSION['PW4v3']) and $v==$_SESSION['PW4v3']){
			$head="<div id='headPW4v3'>$n</div>";
			$h=" hoverPW4";
		}else{$h='';}
		if(method_exists($v,'start') and method_exists($v,'view')){
			$keys .= "<a title='$t' class='keyPW4$h' onclick=PW4v3('$v')>$n</a>";
		}
	}
	echo <<<NEC
<div id="menuUpPW4">
	<div class="menuKeyPW4">
		<div class="nameMenuKeyPW4">PhpWav4</div>
		<div class="menuOpenPW4">
			<a class="keyPW4" onclick=PW4v3("|setting")>Настройки</a>
			<a class="keyPW4" onclick=PW4v3("|reset")>Перезапуск</a>
			<a class="keyPW4" href="/">Выход</a>
		</div>
	</div>
	<div class="menuKeyPW4">
	<div class="nameMenuKeyPW4">Программы</div>
		<div class="menuOpenPW4" id="keysPW4">
			$keys
		</div>
	</div>
</div>
$head
NEC;
}

private function setting(){
	$c=''; if($this->PW4v3dynamic){$c='checked';}
	$this->keys();
	echo <<<NEC
<style>
tr{background:#edf; color:#005;}
td{padding:5px;}
button{background:#757;color:#fff;margin:0;padding:5px 5px; outline:none; border:1px solid #535;}
button:hover{background:#99f;}
button:active{color:#006;}
</style>
<form id='messagePW4v3' name='PW4v3form'>
	<table style='margin:0 auto;'>
		<tr><td colspan='2'><b>Настройки</b></td></tr>
		<tr>
			<td><input name='PW4v3dynamic' type='checkbox' $c></td>
			<td>Включить поддержку динамического режима для программ</td>
		</tr>
		<tr>
			<td colspan='2'>
				<button type='button' class='' onclick=PW4v3_save_setting(PW4v3form) >Сохранить</button>
			</td>
		</tr>
	</table>
</form>
NEC;
}

private function message($str){
	unset($_SESSION['PW4v3']);
	$this->keys();
	echo "<div id='messagePW4v3'><b>Сообщение:</b> $str <b>!</b></div>";
}

public function boot(){
	if(session_id()==false){session_start();}
	if(isset($_COOKIE['PW4v3_setting'])){
		foreach(unserialize($_COOKIE['PW4v3_setting']) as $k=>$v){$this->$k=$v;}
	}
	if(isset($_GET['PW4v3s'])){
		if(class_exists($_GET['PW4v3s'])){
			$o=new $_GET['PW4v3s'];
			if(isset($o->dynamic) and $this->PW4v3dynamic){$o->dynamic = true;}
			if(method_exists($o,'script')){
				echo $o->script();
			}
		}
		exit;
	}
	if(isset($_GET['PW4v3'])){
		if($_GET['PW4v3']=='|reset'){
			unset($_SESSION['PW4v3']);
			$this->message('Перезапуск выполнен');
		}else if($_GET['PW4v3']=='|setting_save'){
			$this->PW4v3dynamic = (isset($_GET['PW4v3dynamic'])) ? true : false;
			$setting['PW4v3dynamic']=$this->PW4v3dynamic;
			setcookie('PW4v3_setting',serialize($setting),time()+(60*60*24*360));
			$this->message('Настройки сохранены');
		}else if($_GET['PW4v3']=='|setting'){
			$this->setting();
		}else if(class_exists($_GET['PW4v3'])){
			$o=new $_GET['PW4v3'];
			if(method_exists($o,'start') and method_exists($o,'view')){
				$_SESSION['PW4v3']=$_GET['PW4v3'];
				if(property_exists($o,'reset')){header('localion:');}
				if(isset($o->dynamic) and $this->PW4v3dynamic){$o->dynamic = true;}
				$o->start();
				$this->keys();
				$o->view();
			}else if(method_exists($o,'boot')){
				$this->message('Загрузочная программа');
			}else{
				$this->message('Не найдены: boot, start, view');
			}
		}else{
			$this->message("Неизвестная команда '<b>{$_GET['PW4v3']}</b>'");
		}
		exit;
	}
	if(!empty($_SESSION['PW4v3']) and class_exists($_SESSION['PW4v3'])){
		$o=new $_SESSION['PW4v3'];
		if(method_exists($o,'start') and method_exists($o,'view')){
			if(isset($o->dynamic) and $this->PW4v3dynamic){$o->dynamic = true;}
			$o->start();
			ob_start();
			$this->keys();
			$o->view();
			$this->view=ob_get_clean();
		}
		if(method_exists($o,"script")){
			$this->script="PW4v3_xml('?PW4v3s={$_SESSION['PW4v3']}','script');";
		}
	}else{
		ob_start();
		$this->keys();
		echo "<div id='home'>Добро пожаловать в <b>PhpWav4</b> ver {$this->ver}</div>";
		$this->view=ob_get_clean();
	}

	/*=== view ============================*/
	echo <<<NEC
<!DOCTYPE html>
<html>
<head>
<title>PhpWav4</title>
<meta charset="utf-8">
<meta name=viewport content="width=device-width, initial-scale=1">
<style>
	body,html{margin:0;height:100%;}
	#headPW4v3{background:#535;text-align:center;color:#ffd;font-weight:900;}
	#messagePW4v3{padding:5px; text-align:center; border:1px solid #444; background:#fff;}
	#menuUpPW4{background:#757; font-size:14px;}
	.menuKeyPW4{display:inline-block;color:#fff;}
	.nameMenuKeyPW4{padding:2px 4px;}
	.keyPW4{padding:2px 8px; display:block; text-decoration:none; color:#fff;}
	.hoverPW4,.keyPW4:active{color:#006;}
	.menuOpenPW4{display:none;background:#979;border:1px solid #444;position:absolute;z-index:9999;}
	.menuKeyPW4:hover,.keyPW4:hover{background:#99f;cursor:pointer;}
	.menuKeyPW4:hover .menuOpenPW4{display:block;}
	#home{background:#fff; border:1px solid #444; font-size:20px;text-align:center; padding: 10px;}
	#PW4v3{background:#eed; min-height:100%;}
</style>
</head>
<body>
<div id="PW4v3">
	{$this->view}
</div><!--PW4v3-->
<script>
var PW4v3s=null;
function PW4v3_xml(url,s){
	var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
	if(window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
		 	switch(s){
				case "block":
					document.getElementById('PW4v3').innerHTML=xmlhttp.responseText;
					break;
				case "script":
					if(PW4v3s!=null){
						PW4v3s.parentNode.removeChild(PW4v3s);
					}
					PW4v3s=document.createElement('script');
					var text=document.createTextNode(xmlhttp.responseText);
					document.body.appendChild(PW4v3s);
					PW4v3s.appendChild(text);
					break;
			}
		}
	}
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
}
function PW4v3_form(form){
	var text="",g=true,arr=[].slice.call(form);
	for(var i=0; i<arr.length; i++){
		switch(arr[i].type){
			case "checkbox": if(arr[i].checked!=true){break;}
			default:
				if(arr[i].name) {// && arr[i].value
					if(g){text+="&";}else{text+="?"; g=true;}
					text+=encodeURIComponent(arr[i].name)+"="+encodeURIComponent(arr[i].value);
				}
		}
	}
	return text;
}
function PW4v3(str){
	PW4v3_xml('?PW4v3='+str,"block");
	PW4v3_xml('?PW4v3s='+str,"script");
}
function PW4v3_save_setting(form){
	var str = PW4v3_form(form);
	PW4v3_xml('?PW4v3=|setting_save'+str,'block');
}
{$this->script}
</script>
</body>
</html>
NEC;
	exit;
}//boot() -
}//end class PhpWav4_v3;
/*<|PhpWav4_v3*/

/*>|ScriptPack*/
class ScriptPack{
	public $name="ScriptPack"; //- Имя программы для отображения.
	public $info="Скрипты для программ"; //- Описание программы.
	public $ver="1.03"; //- Версия программы.
	public $desc=<<<NEC
07.06.17 v1.0 - Выпуск скриптов общего назначения ScriptPack
16.06.17 v1.01 - _form() убрана проверка пустых значений
18.06.17 v1.02 - _form() добавлен encodeURIComponent() и добавлен поддержка POST
30.06.17 v1.03 - _xml() добавлена кроссбраузерность xmlHttp запросам и добавлена надпись Loading... при запросе.
NEC;

	public function ScriptPack($s=null){
		$script['_xml']=<<<NEC
function _xml(str,id,post){
	document.getElementById(id).innerHTML='<div style="background:#fff;">Loading...</div>'
	if(post!==true){post=false;}
	var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
		 	document.getElementById(id).innerHTML=xmlhttp.responseText;
		}
	}
	if(post){
		xmlhttp.open("POST","?",true);///????
		xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
		xmlhttp.send(str);
	}else{
		xmlhttp.open("GET",str,true);
		xmlhttp.send();
	}
	console.log(str+' | '+id);
}
NEC;
		$script['_form']=<<<NEC
function _form(form){
	var text="",g=true,arr=[].slice.call(form);
	for(var i=0; i<arr.length; i++){
		switch(arr[i].type){
			case "checkbox": if(arr[i].checked!=true){break;}
			default:
				if(arr[i].name) {// && arr[i].value
					if(g){text+="&";}else{text+="?"; g=true;}
					text+=encodeURIComponent(arr[i].name)+"="+encodeURIComponent(arr[i].value);
				}
		}
	}
	return text;
}
NEC;
		if(is_array($s)){
			$result='';
			foreach($s as $v){
				if(isset($script[$v])){
					$result.=$script[$v].chr(10);
				}else{
					return false;
				}
			}
			return $result;
		}else if(is_string($s)){
			if(isset($script[$s])){return $script[$s].chr(10);}
		}
		return false;
	}//script_pack() -
}//end class script_pack;
/*<|ScriptPack*/

/*>|Program_Manager*/
class Program_Manager{
	public $dynamic = false;
	public $name='Program Manager';
	public $ver='1.05';//>09.02.17-11.02.17;
	public $info='Менеджер программ.';
	public $desc=<<<NEC
09.02.17 по 12.02.17 v1.0 - Разработка и создание программы.
09.06.17 v1.01 - Исп. стили, переделан под метод GET, переделан вывод, переделан вывод сообщений, доб. динамический режим.
19.06.17 v1.02 - Исправлен вывод сообщений с кнопкой 'Ok' для динамического режима.
20.06.17 v1.03 - Переделан вывод скриптов, добавлен перезапуск для PhpWav4_v3
23.06.17 v1.04 - Исправлен перезапуск для PW4v3.07, добавлена кнопка запуска программы в описании.
02.07.17 v1.05 - Работа на ошибками, добавлен boot режим.
NEC;
	private $view='';
	private $error='';
	private $PW4v3=false;

public function boot(){
	$this->error='Boot режим!';
	$this->start();
	$this->view();
}

public function remove($name){
	if(!empty($name) and file_exists($name)){
		$f=fopen($name,'r'); $t=fread($f,13); fclose($f);
		if(substr($t,8,5)=='000||'){
			include($name);
			unset($t);
			if(!empty($key) and !empty($program) and class_exists($key)){
				$t=''; $z=true;
				foreach(file(__FILE__) as $v){
					if(substr($v,0,strlen($key)+6)=="/*>|$key*/"){$z=false; $t.=$v;}
					if(substr($v,0,strlen($key)+6)=="/*<|$key*/"){$z=true; $t.=$program.chr(10);}
					if($z==true){$t.=$v;}
				}
				if(!$z){
					$this->message('Ошибка перезаписи: Не найдено окончание программы!');
				}else{
					$f=fopen(__FILE__,'w'); fwrite($f,$t); fclose($f);
					$this->message('Программа перезаписана.',['reset']);
				}
			}
		}
	}
}//remove() - Перезапись программы.

public function install($name){
	if(file_exists($name)){
		$f=fopen($name,'r'); $t=fread($f,13); fclose($f);
		if(substr($t,8,5)=='000||'){
			include($name);
			if(!empty($key) and !empty($program)){
				global $keysPW4,$bootPW4;
				$arr=file(__FILE__);
				$t=''; $keyE=$bootE=$programE=true;
				foreach($arr as $v){
					if($keyE and substr($v,0,9)=='/*|keys*/'){
						$t.='/*|keys*/ $keysPW4=array('."'$key','".implode("','",$keysPW4)."');".chr(10);
						$keyE=false; continue;
					}
					if($bootE and isset($boot) and $boot==true and substr($v,0,9)=='/*|boot*/'){
						$t.='/*|boot*/ $bootPW4=array('."'$key','".implode("','",$bootPW4)."');".chr(10);
						$bootE=false; continue;
					}
					if($programE and substr($v,0,13)=='/*|programs*/'){
						$t.=$v.chr(10)."/*>|$key*/".chr(10).$program.chr(10)."/*<|$key*/".chr(10);
						$programE=false; continue;
					}
					$t.=$v;
				}
				if($keyE){
					$this->message('Ошибка Установки: Не найден массив ключей для записи ключа!');
				}else if($programE){
					$this->message('Ошибка Установки: Не найдено место для установки программы!');
				}else if($bootE and isset($boot) and $boot==true){
					$this->message('Ошибка Установки: Не найден массив boot для записи ключа!');
				}else{
					$f=fopen(__FILE__,'w'); fwrite($f,$t); fclose($f);
					$this->message('Программа установлена.',['reset']);
				}
			}else{
				$this->message('Ошибка Установки: Нет ключа или программы для установки!');
			}
		}else{
			$this->message("Ошибка Установки: Нет подписи файла!");
		}
	}
}//install() - Установка программы.

public function delete($key){
	global $keysPW4,$bootPW4;
	function keys($n,$key,$keys){
		unset($keys[array_search($key,$keys)]);
		if($keys==array()){$t='';}else{$t="'".implode("','",$keys)."'";}
		return '/*|'.$n.'*/ $'.$n.'PW4=array('.$t.');'.chr(10);
	}
	if(class_exists($key) and in_array($key,$keysPW4)){
		$arr=file(__FILE__);
		$t=''; $z=$k=$y=true; $b=false; $g=0;
		if(in_array($key,$bootPW4)){$b=true;}
		foreach($arr as $v){
			if($g){$g--; continue;}
			if(substr($v,0,strlen($key)+6)=="/*>|$key*/"){$z=false; continue;}
			if(substr($v,0,strlen($key)+6)=="/*<|$key*/"){$z=true; $g=1; continue;}
			if($z==true){
				if($b==true and substr($v,0,9)=='/*|boot*/'){
					$t.=keys('boot',$key,$bootPW4); $b=false;
					$y=false; continue;
				}
				if(substr($v,0,9)=='/*|keys*/'){
					$t.=keys('keys',$key,$keysPW4);
					$k=false; continue;
				}
				$t.=$v;
			}
		}
		if(!$z){
			$this->message('Ошибка удаления: Не найдено окончание программы!');
		}else if($k){
			$this->message('Ошибка удаления: Не найден массив ключей для удаления ключа!');
		}elseif($y and $b){
			$this->message('Ошибка удаления: Не найден массив boot для удаления ключа!');
		}else{
			$f=fopen(__FILE__,'w'); fwrite($f,$t); fclose($f);
			$this->message('Программа удалена.',['reset']);
		}
	}
}//delete() - Удаление программы.

public function script(){
	$script=<<<NEC
function xmlPM(str){
	_xml(str+'&dynamicPM=ok','formPM');
}
NEC;
	if($this->dynamic and $_SESSION['ProgramManagerScript'] != false) {
		return $_SESSION['ProgramManagerScript'].$script;
	}
	return '';
}//script() -

public function start(){
	if(session_id() == false){session_start();}
	$var=debug_backtrace();
	if(isset($var[1]['class']) and $var[1]['class']=='PhpWav4_v3'){$this->PW4v3 = true;}
	$_SESSION['ProgramManagerScript'] = false;
	function script(){
		$s = 'ScriptPack';
		if(class_exists($s) and method_exists($s,$s)){
			$s = (new $s)->$s('_xml');
			if($s !== false){
				$_SESSION['ProgramManagerScript'] = $s;
				return true;
			}
		}
		return false;
	}
	if($this->dynamic){
		if(script() == false){
			$this->dynamic = false;
			$this->error = 'Скрипты не подключены!';
		}
	}
	ob_start();
	if(isset($_GET['keyPM'])){
		switch($_GET['keyPM']){
			case 'home': $this->viewHome(); break;
			case 'install': $this->viewInstall(); break;
		}
	}
	if(isset($_GET['infoPM']) and class_exists($_GET['infoPM'])){$this->viewInfo();}
	if(isset($_GET['installPM'])){$this->installProgram();}
	if(isset($_GET['keyInsPM'])){$this->install($_GET['keyInsPM']);}
	if(isset($_GET['deletePM'])){$this->deleteProgram();}
	if(isset($_GET['keyDelPM'])){$this->delete($_GET['keyDelPM']);}
	if(isset($_GET['removePM'])){$this->remove($_GET['removePM']);}
	$this->view = ob_get_clean();
	if($this->view==''){
		ob_start();
		$this->viewHome();
		$this->view = ob_get_clean();
	}
	if(isset($_GET['dynamicPM'])){echo $this->view; exit;}
}//start() - Стартовая Функция для обработки Функционала.

public function view(){
	echo <<<NEC
<style>
#headPM{padding:0 2px; padding-top:2px; color:#fff;}
#headPM div{display:inline-block; float:right; color:#fff; margin:0 5px;}
#footer{ padding:0 2px; color:#fff;}
#tablePM{border:solid 2px #333; background:#777; border-collapse:collapse;}
#tablePM td{border:solid 1px #333;}
.keysPM,.keysDelPM,.keysInsPM,.keysMesPM{
	border:0; width:100%; height:20px;text-align:left; background:#555; color:#eef;outline:none; padding:1px 5px;
}
.keysMesPM{margin:2px;width:auto;border:1px solid #777;}
.keysInsPM{width:auto; border:1px solid #777;}
.keysDelPM{color:#fee;} .keysDelPM:hover{background:#877;} .keysDelPM:active{background:#977; color:#fdd;}
.keysPM:hover,.keysInsPM:hover,.keysMesPM:hover{background:#888;}
.keysPM:active,.keysInsPM:active,.keysMesPM:active{background:#444;}
</style>
NEC;
	echo "<div style='background:#f00;color:#fff;text-align:center;'>{$this->error}</div>";
	echo "<form id='formPM' style='background:#333;' method='GET'>{$this->view}</form>";
}//view() - Функция для вывода содержимого на экран.

private function installProgram(){
	if(isset($_GET['installPM']) and file_exists($_GET['installPM'])){
		include($_GET['installPM']);
		if(!empty($key) and class_exists($key)){
			if(property_exists($key,'ver')){$o=new $key; $v=" <b>v".$o->ver."</b>"; unset($o);}else{$v='';}
			if(empty($name)){$name=$key;}
			if(!empty($ver)){
				$ver=", перезаписать на: <b>v$ver</b>";
			}else{
				$ver=" перезаписать на: <b>{$_GET['installPM']}</b>";
			}
			$t="<style>b{color:#cfc;font-weight:500;}</style>";
			$t.="Программа уже установлена: $name".$v."$ver?";
			$k[]=['Перезаписать','removePM',$_GET['installPM']];
			$k[]=['Назад','keyPM','install'];
			$this->message($t,$k);
		}else if(!empty($key)){
			if(empty($name)){$name=$key;}
			if(!empty($ver)){$ver=' v'.$ver;}else{$ver='';}
			$t="Установить программу: $name"."$ver?";
			$k[]=['Установить','keyInsPM',$_GET['installPM']];
			$k[]=['Назад','keyPM','install'];
			$this->message($t,$k);
		}else{
			$k[]=['Назад','keyPM','install'];
			$this->message('Нет ключа для установки!!!',$k);
		}
	}
}//installProgram() -

private function deleteProgram(){
	if(isset($_GET['deletePM']) and class_exists($_GET['deletePM'])){
		$v=$t=$_GET['deletePM'];
		if(property_exists($t,'name')){$o=new $t; $t=$o->name; unset($o);}
		$t="Удалить: $t !!!";
		$k[]=['Удалить','keyDelPM',$v];
		$k[]=['Отмена'];
		$this->message($t,$k);
	}
}//deleteProgram() -

private function message($str,array $keys=['home']){
	echo "<div id='footer' style='background:#333;padding:2px;'>";
	echo "<div style='text-align:center;background:#444;color:#fff;'><div>$str</div>";
	if($keys == ['home'] or $keys == ['reset']){
		$s="name='keyPM' value='home'"; $n='OK'; $ke='';
		if($this->dynamic){
			$s="type=button onclick=xmlPM('?keyPM=home')";
			if($keys==['reset'] and $this->PW4v3){
				$ke="<button class='keysMesPM' $s>Отмена</button>";
				$n='Перезапустить';
				$s="type=button onclick=PW4v3('|reset')";
			}
		}
		echo "<button class='keysMesPM' $s >$n</button> $ke";
	}else{
		foreach($keys as $v){
			if(empty($v[1])){$v[1]='keyPM';$v[2]='home';}
			$s="type=submit name='{$v[1]}' value='{$v[2]}'";
			if($this->dynamic){$s ="type=button onclick=xmlPM('?{$v[1]}={$v[2]}')";}
			echo "<button class='keysMesPM' $s >{$v[0]}</button>";
		}
	}
	echo "</div></div>";
}//message() - Функция вывода сообщений.

private function viewInfo(){
	if(!empty($_GET['infoPM']) and class_exists($_GET['infoPM'])){
		$name=$_GET['infoPM']; $o=new $name; $ver=''; $desc=''; $info='';
		if(property_exists($o,'name')){$name=$o->name;}
		if(property_exists($o,'ver')){$ver='<span style="color:#cfc;">v'.$o->ver.'</span>';}
		if(property_exists($o,'info')){$info='- '.$o->info;}
		if(property_exists($o,'desc')){
			$desc.="<div style='color:#aaa;text-align:center;'>Описание</div><div>";
			foreach(explode(chr(10),$o->desc) as $v){
				$arr=explode('-',$v);
				$arr[0]="<span style='color:#ddf;'>{$arr[0]}</span>";
				$desc.=implode('-',$arr)."<br>";
			}
			$desc.='</div>';
		}
		$key1 = 'name="keyPM" value="home"'; $key2='';
		$key2 = ($this->PW4v3) ?
			"<button type=button class=keysInsPM onclick=PW4v3('{$_GET['infoPM']}') >Запустить</button>" : '';
		if($this->dynamic){$key1 = "type=button onclick=xmlPM('?keyPM=home')";}
		echo <<<NEC
<style>
#content{padding:2px; background:#333;color:#fff; font-size:14px;}
</style>
<div id="headPM">
	<button class="keysInsPM" $key1 >Назад</button>
	$key2
</div>

<div id="content">
	<div><b style='color:#ffd;'>$name</b> $ver $info.</div>
	$desc
</div>

NEC;
	}
}//viewInfo() - Вывод информации о программе.

private function viewInstall(){
	$table='';
	foreach(scandir('.') as $v){
		if(is_file($v) and substr($v,strlen($v)-3,3)=='php' and filesize($v)>0){
			$f=fopen($v,'r'); $t=fread($f,13); fclose($f);
			if(substr($t,8)=='000||'){
				$key = "name='installPM' value='$v'";
				if($this->dynamic){$key = "type=button onclick=xmlPM('?installPM=$v')";}
				$table.="<tr><td style='width:100%;'>
					<button class='keysPM' $key >$v</button></td></tr>";
			}
		}
	}
	$key = "name='keyPM' value='home'";
	if($this->dynamic){$key = "type=button onclick=xmlPM('?keyPM=home')";}
	echo <<<NEC
<div id='headPM'>
	<button class='keysInsPM' $key >Назад</button>
	<span style='padding:0 4px;'>Выберите программу для установки:</span>
</div>
<table id='tablePM' style='width:100%;'>
$table
</table>
<div id='footer'> - все установочные файлы должны находится вместе с файлом PhpWav4.php .</div>
NEC;
}//viewInstall() - Просмотр упакованных файлов.

private function viewHome(){
	global $keysPW4;
	$table='';
	foreach($keysPW4 as $v){
		if(property_exists($v,'name')){$o=new $v; $n=$o->name; unset($o);}else{$n=$v;}
		$key1 = "name='infoPM' value='$v'";
		$key2 = "name='deletePM' value='$v'";
		if($this->dynamic){
			$key1="type=button onclick=xmlPM('?infoPM=$v')";
			$key2="type=button onclick=xmlPM('?deletePM=$v')";
		}
		$table.="<tr><td style='width:100%;'>
			<button class='keysPM' $key1 >$n</button></td><td>
			<button class='keysDelPM' $key2 >Удалить</button></td></tr>";
	}
	$n=count($keysPW4);
	$key = "name='keyPM' value='install'";
	if($this->dynamic){$key="type=button onclick=xmlPM('?keyPM=install')";}
	echo <<<NEC
<div id='headPM'>
	<button class='keysInsPM' $key >Установить программу</button>
	<div>Кол-во программ: $n.</div>
</div>
<table id='tablePM'>$table</table>
NEC;
}//viewHome() - домашняя страница.

	//public function boot(){}//boot() - Boot приложение.
}//end class ProgramManager;
/*<|Program_Manager*/

/*endProgram*/

/*|Bios*/
/*|EndBios*/

function bootPW4(){
	global $bootPW4,$biosPW4;
	if($bootPW4!=array()){foreach ($bootPW4 as $v){
		if(class_exists($v) and method_exists($v,'boot')){$v=new $v; $v->boot(); exit;}
	}}
	if(class_exists($biosPW4) and method_exists($biosPW4,'boot')){$biosPW4=new $biosPW4; $biosPW4->boot(); exit;}
	echo '<b>Not boot !</b>';
}//bootPW4()
bootPW4();
/*PhpWav4 v1.1 WavixSan*/
//21.12.16 v1.1 - Переделан boot и bios;