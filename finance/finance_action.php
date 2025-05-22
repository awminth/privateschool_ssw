<?php
include('../config.php');
$userid = $_SESSION['userid'];

if($_POST["action"] == 'show'){  

    $from=$_POST['from'];
    $to=$_POST['to'];
    $income=0;
    $uniform=0;
    $totalincome=0;
    $salary=0;
    $otherexpense=0;
    $totalexpense=0;
    $profit=0;
    $out="";

    $sqlincome="select sum(TotalPay) as samt from tblfee where Date(Date)>='{$from}' and 
    Date(Date)<='{$to}' ";
    $income=GetInt($sqlincome);

    $sqluniform="select Sum(Amount) from tblvoucheruniform where Date(Date)>='{$from}' and 
    Date(Date)<='{$to}' ";
    $uniform=GetInt($sqluniform);

    $totalincome=$income+$uniform;

    $sqlexpense="select sum(Amt) as samt from tblexpense where Date>='{$from}' and 
    Date<='{$to}'";
    $otherexpense=GetInt($sqlexpense);

    $sqlsalary="select sum(Amt) as samt from tblpaysalary where Date>='{$from}' and 
    Date<='{$to}'  ";
    $salary=GetInt($sqlsalary);
    $totalexpense=$salary+$otherexpense;
    $profit=$totalincome-$totalexpense;
    $txtcolor="text-danger";
    if($profit>0){
        $txtcolor="text-success";
    }


    $out.="
        <div class='card card-primary card-outline p-3'>
            <div class='form-group row'>
                <label class='col-sm-4' for='usr'>{$lang['finance_income']} :</label>
                <input type='number' class='col-sm-8 form-control border-primary text-right' name='income' value='{$income}'
                    readonly>
            </div>
            <div class='form-group row'>
                <label class='col-sm-4' for='usr'>Uniform Fee :</label>
                <input type='number' class='col-sm-8 form-control border-primary text-right' name='uniform' value='{$uniform}'
                    readonly>
            </div>
            <div class='form-group row'>
                <label class='col-sm-4' for='usr'>{$lang['finance_totalincome']} :</label>
                <input type='number' class='col-sm-8 form-control border-primary text-right' name='income' value='{$totalincome}'
                    readonly>
            </div>
            <div class='form-group row'>
                <label class='col-sm-4' for='usr'>{$lang['finance_salary']} :</label>
                <input type='number' class='col-sm-8 form-control border-primary text-right' value='{$salary}'
                     readonly>
            </div>
            <div class='form-group row'>
                <label class='col-sm-4' for='usr'>{$lang['finance_otherexpense']} :</label>
                <input type='number' class='col-sm-8 form-control border-primary text-right' value='{$otherexpense}'
                   readonly>
            </div>
            <div class='form-group row'>
                <label class='col-sm-4' for='usr'>{$lang['finance_totalexpense']} :</label>
                <input type='number' class='col-sm-8 form-control border-primary text-right' value='{$totalexpense}'
                     readonly>
            </div>
            <div class='form-group row'>
                <label class='col-sm-4' for='usr'>{$lang['finance_profit']} :</label>
                <input type='number' class='col-sm-8 form-control border-primary text-right {$txtcolor}' name='profit' value='{$profit}'
                    readonly>
            </div>
        </div>
        ";

    echo $out;
   

}



?>