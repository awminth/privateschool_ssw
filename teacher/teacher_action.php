<?php
include('../config.php');
require(root.'lib/excelReader/excel_reader2.php');
require(root.'lib/excelReader/SpreadsheetReader.php');
include(root.'lib/vendor/autoload.php');

$action = $_POST["action"];
$userid = $_SESSION['userid'];

if($action == 'show'){  
    unset($_SESSION["last_teacher_id"]);

    $limit_per_page=""; 
    if($_POST['entryvalue']==""){
        $limit_per_page=10; 
    } else{
        $limit_per_page=$_POST['entryvalue']; 
    }
    
    $page="";
    if(isset($_POST["page_no"])){
        $page=$_POST["page_no"];
    }
    else{
        $page=1;
    }

    $offset = ($page-1) * $limit_per_page;                                               
   
    $search = $_POST['search'];
    $a = "";
    if($search != ''){     
        $a = " and (Name like '%$search%' or StaffID like '%$search%' or PhoneNo like '%$search%') ";
    }    
    $sql = "select * from tblstaff  
    where Status=0 ".$a." 
    order by AID desc limit $offset,$limit_per_page";
    $result = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped table-responsive">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["tea_teacherid"].'</th>
            <th>'.$lang["tea_name"].'</th>
            <th>'.$lang["tea_namemm"].'</th>
            <th>'.$lang["tea_dob"].'</th>
            <th>'.$lang["tea_gender"].'</th>
            <th>'.$lang["tea_phoneno"].'</th>
            <th>'.$lang["tea_address"].'</th>
            <th>'.$lang["tea_email"].'</th>
            <th>'.$lang["tea_education"].'</th>
            <th>'.$lang["tea_salary"].'</th>
            <th>'.$lang["tea_startdate"].'</th>
            <th>'.$lang["tea_edulevel"].'</th>
            <th class="text-center">'.$lang["tea_photo"].'</th>
            <th width="8%;">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["StaffID"]}</td>
                <td>{$row["Name"]}</td>
                <td>{$row["NameMM"]}</td>
                <td>".enDate($row["DOB"])."</td>
                <td>{$row["Gender"]}</td> 
                <td>{$row["PhoneNo"]}</td>
                <td>{$row["Address"]}</td>
                <td>{$row["Email"]}</td>
                <td>{$row["Education"]}</td>
                <td>".number_format($row["Salary"])."</td>
                <td>".enDate($row["StartDate"])."</td>
                <td>".$row["EducationLevel"]."</td>
                <td class='text-center'>
                    <a href='#' id='btnview' 
                        data-path='{$row['Img']}'>
                        <i class='fas fa-photo'></i>
                    </a>
                </td>
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                            <a href='#' id='btnedit' class='dropdown-item'
                                data-aid='{$row['AID']}'>
                                <i class='fas fa-edit text-primary' style='font-size:13px;'></i>
                                {$lang['staff_edit']}</a>
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}' 
                                data-path='{$row['Img']}'><i
                                class='fas fa-trash text-danger'
                                style='font-size:13px;'></i>
                                {$lang['staff_delete']}</a>
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btncard' class='dropdown-item'
                                data-aid='{$row['AID']}'
                                data-path='{$row['Img']}'>
                                <i class='fas fa-id-card text-success' style='font-size:13px;'></i>
                                Issue Card</a>                           
                        </div>
                    </div>
                </td>     
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select AID from tblstaff  
        where Status=0 ".$a." 
        order by AID desc";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>'.$lang['staff_totalrecord'].' -  ';
        $out.=$total_record;
        $out.='</p></div>';

        $out.='<div class="float-right">
                <ul class="pagination">
            ';      
        
        $previous_link = '';
        $next_link = '';
        $page_link = '';

        if($total_links > 4){
            if($page < 5){
                for($count = 1; $count <= 5; $count++)
                {
                    $page_array[] = $count;
                }
                $page_array[] = '...';
                $page_array[] = $total_links;
            }else{
                $end_limit = $total_links - 5;
                if($page > $end_limit){
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for($count = $end_limit; $count <= $total_links; $count++)
                    {
                        $page_array[] = $count;
                    }
                }else{
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for($count = $page - 1; $count <= $page + 1; $count++)
                    {
                        $page_array[] = $count;
                    }
                    $page_array[] = '...';
                    $page_array[] = $total_links;
                }
            }            

        }else{
            for($count = 1; $count <= $total_links; $count++)
            {
                $page_array[] = $count;
            }
        }

        for($count = 0; $count < count($page_array); $count++){
            if($page == $page_array[$count]){
                $page_link .= '<li class="page-item active">
                                    <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
                                </li>';

                $previous_id = $page_array[$count] - 1;
                if($previous_id > 0){
                    $previous_link = '<li class="page-item">
                                            <a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a>
                                    </li>';
                }
                else{
                    $previous_link = '<li class="page-item disabled">
                                            <a class="page-link" href="#">Previous</a>
                                    </li>';
                }

                $next_id = $page_array[$count] + 1;
                if($next_id > $total_links){
                    $next_link = '<li class="page-item disabled">
                                        <a class="page-link" href="#">Next</a>
                                </li>';
                }else{
                    $next_link = '<li class="page-item">
                                    <a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a>
                                </li>';
                }
            }else{
                if($page_array[$count] == '...')
                {
                    $page_link .= '<li class="page-item disabled">
                                        <a class="page-link" href="#">...</a>
                                    </li> ';
                }else{
                    $page_link .= '<li class="page-item">
                                        <a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
                                    </li> ';
                }
            }
        }

        $out .= $previous_link . $page_link . $next_link;

        $out .= '</ul></div>';

        echo $out; 
        
    }else{
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["tea_teacherid"].'</th>
            <th>'.$lang["tea_name"].'</th>
            <th>'.$lang["tea_namemm"].'</th>
            <th>'.$lang["tea_dob"].'</th>
            <th>'.$lang["tea_gender"].'</th>
            <th>'.$lang["tea_phoneno"].'</th>
            <th>'.$lang["tea_address"].'</th>
            <th>'.$lang["tea_email"].'</th>
            <th>'.$lang["tea_education"].'</th>
            <th>'.$lang["tea_salary"].'</th>
            <th>'.$lang["tea_startdate"].'</th>
            <th>'.$lang["tea_edulevel"].'</th>
            <th class="text-center">'.$lang["tea_photo"].'</th>
            <th width="8%;">Action</th>          
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="13" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}

if($action == 'save'){    
    $staffid = $_POST["staffid"];
    $name = $_POST["name"];
    $namemm = $_POST["namemm"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $phno = $_POST["phno"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $education = $_POST["education"];
    $salary = $_POST["salary"];
    $startdt = $_POST["startdt"];
    $edulevel = $_POST["edulevel"];

    if($_FILES['file']['name'] != ''){
        $filename = $_FILES['file']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $valid_extension = array("jpg","jpeg","png","JPG","PNG","JPEG");
        if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/staff/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                $sql = "insert into tblstaff (LoginID,StaffID,Name,DOB,Gender,PhoneNo,
                Address,Email,Education,Salary,StartDate,Img,NameMM,EducationLevel)  
                values ('{$userid}','{$staffid}','{$name}','{$dob}','{$gender}','{$phno}',
                '{$address}','{$email}','{$education}','{$salary}','{$startdt}','{$new_filename}','{$namemm}',
                '{$edulevel}')";
                if(mysqli_query($con,$sql)){
                    // get last insert id
                    $last_id = mysqli_insert_id($con);
                    $_SESSION["last_teacher_id"] = $last_id; 

                    save_log($_SESSION["username"]." သည် teacher အားအသစ်သွင်းသွားသည်။");
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }
    }else{
        $sql = "insert into tblstaff (LoginID,StaffID,Name,DOB,Gender,PhoneNo,
        Address,Email,Education,Salary,StartDate,NameMM,EducationLevel)  
        values ('{$userid}','{$staffid}','{$name}','{$dob}','{$gender}','{$phno}',
        '{$address}','{$email}','{$education}','{$salary}','{$startdt}','{$namemm}','{$edulevel}')";
        if(mysqli_query($con,$sql)){
            // get last insert id
            $last_id = mysqli_insert_id($con);
            $_SESSION["last_teacher_id"] = $last_id; 

            save_log($_SESSION["username"]." သည် teacher အားအသစ်သွင်းသွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
}

if($action == "prepare"){
    $aid = $_POST["aid"];
    $_SESSION["last_teacher_id"] = $aid;
    echo 1;
}

if($action == 'preparecard'){
    $aid = $_POST['aid'];
    $img = $_POST['path'];
    $path = roothtml.'upload/noimage.png';
    if ($img != "") {
        $path = roothtml.'upload/staff/'.$img;
    }
    $sql = "select * from tblstaff where AID='{$aid}' and Status=0";
    $result = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_array($result);
        $out.= "<div id='printdata'>
                    <div class='modal-body'>
                        <div class='rounded border border-dark'>
                            <div class='bg-primary w-100'>
                                <div class='text-center p-2'>
                                    <img src=".$_SESSION["shoplogo"]." style='width:100px;height:100px;display:inline-block;padding-right:10px;'>
                                    <h2 style='display:inline-block;vertical-align: top;'>
                                        {$_SESSION["shopname"]}
                                    </h2>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-3 d-flex align-items-center justify-content-center'>   
                                    <div class='pl-2'>
                                        <img src='{$path}' class='rounded position-relative' alt='Cinque Terre' width='90%' height='90%' />
                                    </div>
                                </div> 
                                <div class='col-6'>
                                    <table class='table table-borderless'>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th>TeacherName:</th>
                                                <th>{$row["Name"]}</th>
                                            </tr>
                                            <tr>
                                                <th>Gender:</th>
                                                <th>{$row["Gender"]}</th>
                                            </tr>
                                            <tr>
                                                <th>PhNo:</th>
                                                <th>{$row["PhoneNo"]}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div> 
                                <div class='col-3'>
                                    <div class='form-group'>
                                        <label for='usr'>TeacherID:</label>
                                        <label for='usr'>{$row["StaffID"]}</label>
                                    </div>
                                    <div class='form-group'>
                                        <label for='usr'></label>
                                    </div>
                                    <div class='form-group'>
                                        <label for='usr'></label>
                                    </div>
                                    <div class='form-group'>
                                        <label for='usr'>Principle</label>
                                    </div>
                                </div>
                            </div>
                            <div class='bg-primary w-100 text-center'>
                                <span>{$_SESSION["shopaddress"]}</span>
                                <span>&</span>
                                <span>{$_SESSION["shopphno"]}</span>
                            </div>
                        </div>                       
                    </div>
                </div>
                <div class='modal-footer'>
                    <button id='btnprintcard' class='btn btn-success'><i class='fas fa-print'></i>  Print</button>
                </div>";
    }
    echo $out;
}

if($action == 'edit'){   
    $aid = $_POST["aid"]; 
    $staffid = $_POST["staffid"];
    $name = $_POST["name"];
    $namemm = $_POST["namemm"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $phno = $_POST["phno"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $education = $_POST["education"];
    $salary = $_POST["salary"];
    $startdt = $_POST["startdt"];
    $edulevel = $_POST["edulevel"];
    $path = $_POST["path"];

    if($_FILES['file']['name'] != ''){
        $filename = $_FILES['file']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $valid_extension = array("jpg","jpeg","png","JPG","PNG","JPEG");
        if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/staff/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                if($path != "" || $path != NULL){
                    unlink(root."upload/staff/".$path);
                }

                $sql = "update tblstaff set StaffID='{$staffid}',Name='{$name}',DOB='{$dob}',Gender='{$gender}',
                PhoneNo='{$phno}',Address='{$address}',Email='{$email}',
                Education='{$education}',Salary='{$salary}',StartDate='{$startdt}',Img='{$new_filename}',
                NameMM='{$namemm}',EducationLevel='{$edulevel}' where AID={$aid}";
                if(mysqli_query($con,$sql)){
                    save_log($_SESSION["username"]." သည် teacher အား update လုပ်သွားသည်။");
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }
    }else{
        $sql = "update tblstaff set StaffID='{$staffid}',Name='{$name}',DOB='{$dob}',Gender='{$gender}',
        PhoneNo='{$phno}',Address='{$address}',Email='{$email}',
        Education='{$education}',Salary='{$salary}',StartDate='{$startdt}',NameMM='{$namemm}',
        EducationLevel='{$edulevel}' where AID={$aid}";
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["username"]." သည် teacher အား update လုပ်သွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
}

if($action == 'delete'){
    $aid = $_POST["aid"];
    $path = $_POST["path"];
    $sql = "delete from tblstaff where AID={$aid}";
    if(mysqli_query($con,$sql)){
        if($path != ""){
            unlink(root.'upload/staff/'.$path);
        }
        save_log($_SESSION["username"]." သည် teacher အားဖျက်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
    
}

if($_POST["action"] == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){     
        $a = " and (Name like '%$search%' or StaffID like '%$search%' or PhoneNo like '%$search%') ";
    }    
    $sql = "select * from tblstaff  
    where Status=0 ".$a." 
    order by AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "TeacherReport_".date('d_m_Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="11" align="center"><h3>Teacher Information</h3></td>
            </tr>
            <tr><td colspan="11"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>  
                <th style="border: 1px solid ;">'.$lang["tea_teacherid"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_name"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_namemm"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_dob"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_gender"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_phoneno"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_address"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_email"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_education"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_salary"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_startdate"].'</th>  
                <th style="border: 1px solid ;">'.$lang["tea_edulevel"].'</th>     
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["StaffID"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>  
                    <td style="border: 1px solid ;">'.$row["NameMM"].'</td>  
                    <td style="border: 1px solid ;">'.enDate($row["DOB"]).'</td>
                    <td style="border: 1px solid ;">'.$row["Gender"].'</td>  
                    <td style="border: 1px solid ;">'.$row["PhoneNo"].'</td>
                    <td style="border: 1px solid ;">'.$row["Address"].'</td>
                    <td style="border: 1px solid ;">'.$row["Email"].'</td>
                    <td style="border: 1px solid ;">'.$row["Education"].'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Salary"]).'</td>
                    <td style="border: 1px solid ;">'.enDate($row["StartDate"]).'</td>
                    <td style="border: 1px solid ;">'.$row["EducationLevel"].'</td>
                </tr>';
        }
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }else{
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="11" align="center"><h3>Teacher Information</h3></td>
            </tr>
            <tr><td colspan="11"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>  
                <th style="border: 1px solid ;">'.$lang["tea_teacherid"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_name"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_namemm"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_dob"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_gender"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_phoneno"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_address"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_email"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_education"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_salary"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_startdate"].'</th>   
                <th style="border: 1px solid ;">'.$lang["tea_edulevel"].'</th>       
            </tr>
            <tr>
                <td style="border: 1px solid ;" colspan="11" align="center">No record found.</td>
            </tr>
        </table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}

if($_POST["action"] == 'pdf'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){     
        $a = " and (Name like '%$search%' or StaffID like '%$search%' or PhoneNo like '%$search%') ";
    }    
    $sql = "select * from tblstaff  
    where Status=0 ".$a." 
    order by AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "TeacherReport_".date('d_m_Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="11" align="center"><h3>Teacher Information</h3></td>
            </tr>
            <tr><td colspan="11"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>  
                <th style="border: 1px solid ;">'.$lang["tea_teacherid"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_name"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_namemm"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_dob"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_gender"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_phoneno"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_address"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_email"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_education"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_salary"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_startdate"].'</th>     
                <th style="border: 1px solid ;">'.$lang["tea_edulevel"].'</th>     
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["StaffID"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>  
                    <td style="border: 1px solid ;">'.$row["NameMM"].'</td>  
                    <td style="border: 1px solid ;">'.enDate($row["DOB"]).'</td>
                    <td style="border: 1px solid ;">'.$row["Gender"].'</td>  
                    <td style="border: 1px solid ;">'.$row["PhoneNo"].'</td>
                    <td style="border: 1px solid ;">'.$row["Address"].'</td>
                    <td style="border: 1px solid ;">'.$row["Email"].'</td>
                    <td style="border: 1px solid ;">'.$row["Education"].'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Salary"]).'</td>
                    <td style="border: 1px solid ;">'.enDate($row["StartDate"]).'</td>
                    <td style="border: 1px solid ;">'.$row["EducationLevel"].'</td>
                </tr>';
        }
        $out .= '</table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'TeacherPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="11" align="center"><h3>Teacher Information</h3></td>
            </tr>
            <tr><td colspan="11"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>  
                <th style="border: 1px solid ;">'.$lang["tea_teacherid"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_name"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_namemm"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_dob"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_gender"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_phoneno"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_address"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_email"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_education"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_salary"].'</th>
                <th style="border: 1px solid ;">'.$lang["tea_startdate"].'</th>    
                <th style="border: 1px solid ;">'.$lang["tea_edulevel"].'</th>       
            </tr>
            <tr>
                <td style="border: 1px solid ;" colspan="11" align="center">No record found.</td>
            </tr>
        </table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'TeacherPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }   
    
}

if($action == "import"){    
    if($_FILES['file']['name'] != ''){
        $filename = $_FILES['file']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $valid_extension = array("xlsx","XLSX","xls","XLS");
        if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".".$extension;
            $new_path = root."upload/student/".$new_filename;

            move_uploaded_file($file,$new_path);
            error_reporting(0);
            ini_set("display errors",0);
            $reader = new SpreadsheetReader($new_path);
            foreach($reader as $key => $row){
                $staffid = $row[1];
                $name = $row[2];

                $dt = strtotime($row[3]);
                $dob = date('Y-m-d',$dt);

                $gender = $row[4];
                $phno = $row[5];
                $address = $row[6];
                $email = $row[7];
                $education = $row[8];
                $salary = $row[9];

                $dt1 = strtotime($row[10]);
                $startdt = date('Y-m-d',$dt1);

                $sql = "insert into tblstaff (LoginID,StaffID,Name,DOB,Gender,PhoneNo,
                Address,Email,Education,Salary,StartDate)  
                values ('{$userid}','{$staffid}','{$name}','{$dob}','{$gender}','{$phno}',
                '{$address}','{$email}','{$education}','{$salary}','{$startdt}')";
                // echo $sql;
                mysqli_query($con,$sql);
            }
            
            unlink(root.'upload/student/'.$new_filename);

            echo 1;
        }
    }
}


?>