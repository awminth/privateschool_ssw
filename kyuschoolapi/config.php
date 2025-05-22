<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type:application/json');

//$con=new mysqli("localhost","root","root","schoolms");
//$con=new mysqli("108.178.44.242","kyawmtun_admin","kyoungunity007","kyawmtun_kyuschoolms");

$con=new mysqli("108.178.44.242","kfskyur_admin","kyoungunity*007*","kfskyur_khingfamily");

mysqli_set_charset($con,"utf8");

date_default_timezone_set("Asia/Rangoon");

$dir=dirname(__FILE__);
define('root',__DIR__.'/');

define('roothtml','http://localhost/restaurantapi/');

$jsondata="php://input";
$phpjson=file_get_contents($jsondata);
$data=json_decode($phpjson,true);

function NumtoText($number)
{
        $array = [
            '1' => 'First',
            '2' => 'Second',
            '3' => 'Third',
            '4' => 'Four',
            '5' => 'Five',
            '6' => 'Six',
            '7' => 'Seven',
            '8' => 'Eight',
            '9' => 'Nine',
            '10' => 'Ten',
        ];
        return strtr($number, $array);
}


function toMyanmar($number)
{
        $array = [
            '0' => '၀',
            '1' => '၁',
            '2' => '၂',
            '3' => '၃',
            '4' => '၄',
            '5' => '၅',
            '6' => '၆',
            '7' => '၇',
            '8' => '၈',
            '9' => '၉',
        ];
        return strtr($number, $array);
}


function toEnglish($number)
{
        $array = [
            '၀' => '0',
            '၁' => '1',
            '၂' => '2',
            '၃' => '3',
            '၄' => '4',
            '၅' => '5',
            '၆' => '6',
            '၇' => '7',
            '၈' => '8',
            '၉' => '9',
        ];
        return strtr($number, $array);
}

function GetString($sql)
{
    global $con;
    $str="";   
    $result=mysqli_query($con,$sql) or die("Query Fail");
    if(mysqli_num_rows($result)>0){

        $row = mysqli_fetch_array($result);
       $str= $row[0];
    }
    return $str;
}


function GetInt($sql)
{
    global $con;
    $str=0;     
    $result=mysqli_query($con,$sql) or die("Query Fail");
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_array($result);
       $str= $row[0];
    }
    return $str;
}





?>