<?
if (Init_FairLinks!='Ok') die ('Not init!');		//если скрипт был запущен отдельно от системы, то останавливаемся с сообщением об ошибке.

//############################################################################
//#		Работаем с капчей
//#					Kelkos 2006
//############################################################################
// спасибо ребятам с www.captcha.ru за алгоритм порчи картинки.

//комплект функций для работы с captcha
//неиспользуются сессии, а используется таблица хранения выданных номеров.

//определяем параметры для создания каринок.
$captcha_max_live_time=600;	//максимальное время хранения выданных номеров.


//==============================================
function captcha_get_rand_number ($len)
{
//функция генерит номерок для капчи
//$len - дляина сгенерированного номера. (не больше 15!!!)

//формируем строку с символами для капчи.. делаем так, чтобы числа преобладали над буквами для упрощения для пользователя.
$symb_str='123456789123456789123456789ABCDEF123456789QWERT123456789YUPZX123456789CVB123456789NMLK123456789JHGFD123456789SA123456789123456789';
$captcha_num='';
for ($i=0; $i<$len; $i++) $captcha_num.=$symb_str[rand(0, strlen($symb_str)-1)];
return $captcha_num;
}
//==============================================

function captcha_reg_new_number ($captcha_num)
{
//функция регистрирует в базе новый номер капчи $captcha_num
//возвращает записанный id этого номера (для перезачи в форме)
//также удаляет просроченные номера.
global $system_db;
catpcha_drop_old_num ();	//удаляем просроченные номера.

SI_sql_query("insert into ".Base_Prefix."captcha (FIELD_DATE, FIELD_NUMBER) values('".$system_db['THIS_TIME']."', '".adds($captcha_num)."')");
return mysql_insert_id();
}
//==============================================

function catpcha_drop_old_num ()
{
//функция удаляет просроченные выданные номера
global $system_db, $captcha_max_live_time;

captcha_create_table ();	//проверяем, есть ли таблица для капчей, и если нет, то создаём её.

SI_sql_query ("DELETE FROM ".Base_Prefix."captcha WHERE FIELD_DATE<'".($system_db['THIS_TIME']-$captcha_max_live_time)."'");

}
//==============================================

function catpcha_drop_id ($captcha_id)
{
//функция удаляет запись в таблице с id=$captcha_id
SI_sql_query ("DELETE FROM ".Base_Prefix."captcha WHERE id='".intval ($captcha_id)."'");
}
//==============================================

function captcha_create_table ()
{
//функция создаёт таблицу для капчей, если она ещё не была создана.
global $system_db;

SI_sql_query("create table IF NOT EXISTS ".Base_Prefix."captcha 
        (id int UNSIGNED NOT NULL auto_increment PRIMARY KEY,
        FIELD_DATE  		int NOT NULL,	#время создания
        FIELD_NUMBER  		varchar (15),	#номер
	#индексы
	INDEX fastfind1 (FIELD_DATE)
	)");

}
//==============================================

function captcha_verify ($captcha_id, $captcha_num)
{
//функция проверяет правильность совпадения $captcha_id с $captcha_num
//если капча введена правильно, то возвращает true
//иначе false
$captcha_db=SI_sql_query("select FIELD_NUMBER from ".Base_Prefix."captcha WHERE id='".intval($captcha_id)."'");
if (!$captcha_db) return false;

//делаем регистронезависимое сравнение.
if (trim(strtolower($captcha_num))==trim(strtolower($captcha_db[0]['FIELD_NUMBER']))) return true;

return false;
}
//==============================================

function captcha_build_image ($captcha_num)
{
//функция создаёт изображение с номером $captcha_num для капчи
//возвращает картинку $img в формате jpg.

//задаём параметры шрифта
$FONT_NAME=Root_Dir.'_shell/captcha/verdana.ttf';
$FONT_SIZE=20;

//отступы
$otstup_x=35;
$otstup_y=30;

//определяем размер области, необходимый для нанесения теста
$coord = imagettfbbox($FONT_SIZE, 0, $FONT_NAME, $captcha_num);

//размеры картинки  к которым прибавляем оступы
$width =  $coord[2] - $coord[0]+$otstup_x;
$height =  $coord[1] - $coord[7]+$otstup_y+30;

//учитывая отступы располагаем текст по центру.
$X = floor ($otstup_x / 2);
$Y = $height-floor ($otstup_y / 2)-30;
// DanFreeman
$Y1 = $height-floor ($otstup_y / 2)-5;
$PredupredRas = 7;
$PredupredCol = "0xCC0000";
$PredupredOtst = 15;

//формируем фон картинки.
$img = ImageCreateTrueColor($width, $height);

imagefilledrectangle($img, 0, 0, $width, $height, 0xFFFFFF);

//наносим надпись
imagettftext($img, $FONT_SIZE, 0, $X, $Y,  0x000000, $FONT_NAME, $captcha_num);

//колбасим картинку.
$img=captha_kolbasa ($img);

//ноносим точки
for ($i=0; $i<1600; $i++) imagesetpixel ($img, rand (0, $width), rand (0, $height-30), 0x000000);

// DanFreeman - - добавил угрожающюю надпись
imagettftext($img, $PredupredRas, 0, $X-$PredupredOtst, $Y1,  $PredupredCol, $FONT_NAME, "ВАШ САЙТ ДОЛЖЕН");
imagettftext($img, $PredupredRas, 0, $X-$PredupredOtst, $Y1+$PredupredRas+2,  $PredupredCol, $FONT_NAME, "СООТВЕТСТВОВАТЬ");
imagettftext($img, $PredupredRas, 0, $X-$PredupredOtst, $Y1+2*$PredupredRas+4,  $PredupredCol, $FONT_NAME, "НАШЕЙ ТЕМАТИКЕ!");
return $img;
}
//==============================================

function captha_kolbasa ($img)
{
//функция колбасит изображение, так, чобы было не разобрать.
//возвращает картинку.

$width=imagesx($img);
$height=imagesy($img);

$img2 = ImageCreateTrueColor($width, $height);

// случайные параметры (можно поэкспериментировать с коэффициентами):
// частоты
$rand1 = mt_rand(700000, 1000000) / 15000000;
$rand2 = mt_rand(700000, 1000000) / 15000000;
$rand3 = mt_rand(700000, 1000000) / 15000000;
$rand4 = mt_rand(700000, 1000000) / 15000000;
// фазы
$rand5 = mt_rand(0, 3141592) / 1000000;
$rand6 = mt_rand(0, 3141592) / 1000000;
$rand7 = mt_rand(0, 3141592) / 1000000;
$rand8 = mt_rand(0, 3141592) / 1000000;
// амплитуды
$rand9 = mt_rand(400, 600) / 100;
$rand10 = mt_rand(400, 600) / 100;
 
for($x = 0; $x < $width; $x++){
  for($y = 0; $y < $height; $y++){
    // координаты пикселя-первообраза.
    $sx = $x + ( sin($x * $rand1 + $rand5) + sin($y * $rand3 + $rand6) ) * $rand9;
    $sy = $y + ( sin($x * $rand2 + $rand7) + sin($y * $rand4 + $rand8) ) * $rand10;
 
    // первообраз за пределами изображения
    if($sx < 0 || $sy < 0 || $sx >= $width - 1 || $sy >= $height - 1){ 
      $color = 255;
      $color_x = 255;
      $color_y = 255;
      $color_xy = 255;
    }else{ // цвета основного пикселя и его 3-х соседей для лучшего антиалиасинга
      $color = (imagecolorat($img, $sx, $sy) >> 16) & 0xFF;
      $color_x = (imagecolorat($img, $sx + 1, $sy) >> 16) & 0xFF;
      $color_y = (imagecolorat($img, $sx, $sy + 1) >> 16) & 0xFF;
      $color_xy = (imagecolorat($img, $sx + 1, $sy + 1) >> 16) & 0xFF;
    }



    // сглаживаем только точки, цвета соседей которых отличается
    if($color == $color_x && $color == $color_y && $color == $color_xy){
      $newcolor=$color;
    }else{
      $frsx = $sx - floor($sx); //отклонение координат первообраза от целого
      $frsy = $sy - floor($sy);
      $frsx1 = 1 - $frsx;
      $frsy1 = 1 - $frsy;

      // вычисление цвета нового пикселя как пропорции от цвета основного пикселя и его соседей
      $newcolor = floor( $color    * $frsx1 * $frsy1 +
                         $color_x  * $frsx  * $frsy1 +
                         $color_y  * $frsx1 * $frsy  +
                         $color_xy * $frsx  * $frsy );
    }
    imagesetpixel($img2, $x, $y, imagecolorallocate($img2, $newcolor, $newcolor, $newcolor));
  }
}

imagedestroy($img);
return $img2;
}
//==============================================

?>