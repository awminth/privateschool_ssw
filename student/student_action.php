<?php
include('../config.php');
require(root.'lib/excelReader/excel_reader2.php');
require(root.'lib/excelReader/SpreadsheetReader.php');
include(root.'lib/vendor/autoload.php');

$action = $_POST["action"];
$userid = $_SESSION['userid'];

if($action == 'show'){  
    unset($_SESSION["last_student_id"]);
    unset($_SESSION["attachment_stuid"]);
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
        $a = " and (p.Name like '%$search%' or p.StudentID like '%$search%' 
        or p.FatherName like '%$search%' or p.MotherName like '%$search%'
        or p.RealGrade like '%$search%' or p.NameMM like '%$search%') ";
    }    
    $sql = "select p.*,r.Name as rname,n.Name as nname  
    from tblstudentprofile p  
    left join tblreligion r on p.ReligionID=r.AID  
    left join tblnational n on p.NationalID=n.AID    
    where  p.AID is not null ".$a." 
    order by p.AID desc limit $offset,$limit_per_page";    
    $result = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
  
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped table-responsive nowarp">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["stu_id"].'</th>
            <th>'.$lang["stu_name"].'</th> 
            <th>'.$lang["stu_namemm"].'</th> 
            <th>'.$lang["stu_dob"].'</th>
            <th>'.$lang["stu_age"].'</th>  
            <th>'.$lang["stu_place"].'</th>
            <th>'.$lang["stu_religion"].'</th> 
            <th>'.$lang["stu_attachment_doc"].'</th> 
            <th>'.$lang["stu_attend_grade"].'</th>  
            <th>'.$lang["stu_gender"].'</th>    
            <th>'.$lang["stu_fname"].'</th>
            <th>Father NRC No</th>
            <th>'.$lang["stu_mname"].'</th>
            <th>Mother NRC No</th>
            <th>'.$lang["stu_mwork"].'</th>
            <th>'.$lang["stu_phno"].'</th>
            <th>'.$lang["stu_cell_phone"].'</th>
            <th>'.$lang["stu_email"].'</th>
            <th>'.$lang["stu_emergence"].'</th>            
            <th>'.$lang["stu_address"].'</th> 
            <th class="text-center">'.$lang["stu_attachment"].'</th>
            <th width="8%;">'.$lang["btn_action"].'</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out.="<tr>";
                if(isset($_SESSION['la'])){
                    if($_SESSION['la']=='my'){
                        $out.="<td class='text-center'>".toMyanmar($no)."</td>";
                    }else{
                        $out.="<td class='text-center'>{$no}</td>";
                    }
                }else{
                    $out.="<td class='text-center'>{$no}</td>";
                }
            $out.="
                <td>{$row["StudentID"]}</td>
                <td>{$row["Name"]}</td>           
                <td>{$row["NameMM"]}</td>             
                <td>".enDate($row["DOB"])."</td>
                <td>{$row["Age"]}</td> 
                <td>{$row["BirthPlace"]}</td>
                <td>{$row["rname"]}</td>
                <td>{$row["AttachDoc"]}</td>
                <td>{$row["RealGrade"]}</td>
                <td>{$row["Gender"]}</td> 
                <td>{$row["FatherName"]}</td>
                <td>{$row["Fnrc"]}</td>
                <td>{$row["MotherName"]}</td>
                <td>{$row["Mnrc"]}</td>
                <td>{$row["MotherWork"]}</td>
                <td>{$row["PhoneNo"]}</td>
                <td>{$row["CellPhone"]}</td>
                <td>{$row["Email"]}</td>
                <td>{$row["Emergence"]}</td>
                <td>{$row["ParentAddress"]}</td> 
                <td class='text-center'>
                    <a href='#' id='btnattachment' 
                        data-aid='{$row['AID']}' 
                        data-name='{$row['Name']}'>
                        <i class='fas fa-file'></i>
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
                                ".$lang['btnedit']."</a>
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}' 
                                data-path='{$row['Img']}'><i
                                class='fas fa-trash text-danger'
                                style='font-size:13px;'></i>
                                ".$lang['btndelete']."</a>                           
                        </div>
                    </div>
                </td>     
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table><br>";

        $sql_total="select p.AID   
        from tblstudentprofile p  
        left join tblreligion r on p.ReligionID=r.AID  
        left join tblnational n on p.NationalID=n.AID    
        where  p.AID is not null ".$a." 
        order by p.AID desc";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>'.$lang["totalrecord"].' -  ';
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
        <table class="table table-bordered table-striped table-responsive nowarp">
        <thead>
        <tr>
        <th width="7%;">'.$lang["no"].'</th>
        <th>'.$lang["stu_id"].'</th>
        <th>'.$lang["stu_name"].'</th> 
        <th>'.$lang["stu_namemm"].'</th> 
        <th>'.$lang["stu_dob"].'</th>
        <th>'.$lang["stu_age"].'</th>  
        <th>'.$lang["stu_place"].'</th>
        <th>'.$lang["stu_religion"].'</th> 
        <th>'.$lang["stu_attachment_doc"].'</th> 
        <th>'.$lang["stu_attend_grade"].'</th>  
        <th>'.$lang["stu_gender"].'</th>    
        <th>'.$lang["stu_fname"].'</th>
        <th>'.$lang["stu_mname"].'</th>
        <th>'.$lang["stu_mwork"].'</th>
        <th>'.$lang["stu_phno"].'</th>
        <th>'.$lang["stu_cell_phone"].'</th>
        <th>'.$lang["stu_email"].'</th>
        <th>'.$lang["stu_emergence"].'</th>            
        <th>'.$lang["stu_address"].'</th> 
        <th class="text-center">'.$lang["stu_attachment"].'</th>
        <th width="8%;">'.$lang["btn_action"].'</th>           
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="22" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}

if($action == "check_stuid"){
    $stuid = $_POST["stuid"];
    $sql = "select AID from tblstudentprofile where StudentID='{$stuid}'";
    if(GetBool($sql)){
        echo 1;
    }else{
        echo 0;
    }
}

if($action == 'save'){    
    $studentid = $_POST["studentid"];
    $name = $_POST["name"];
    $dob = $_POST["dob"];
    $age = $_POST["age"];
    $nationality = $_POST["nationality"];
    $religion = $_POST["religion"];
    $fname = $_POST["fname"];
    $fnrc = $_POST["fnrc"];
    $fwork = $_POST["fwork"];
    $mname = $_POST["mname"];
    $mnrc = $_POST["mnrc"];
    $mwork = $_POST["mwork"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $schooldt = $_POST["schooldt"];
    $lastschoolname = $_POST["lastschoolname"];
    $allowgrade = $_POST["allowgrade"];
    $topgrade = $_POST["topgrade"];
    $KG = $_POST["KG"];
    $G1 = $_POST["G1"];
    $G2 = $_POST["G2"];
    $G3 = $_POST["G3"];
    $G4 = $_POST["G4"];
    $G5 = $_POST["G5"];
    $G6 = $_POST["G6"];
    $G7 = $_POST["G7"];
    $G8 = $_POST["G8"];
    $G9 = $_POST["G9"];
    $G10 = $_POST["G10"];
    $G11 = $_POST["G11"];
    $G12 = $_POST["G12"];
    $outreason = $_POST["outreason"];
    $rmk = $_POST["rmk"];
    $place = $_POST["place"];
    $phno = $_POST["phno"];
    $email = $_POST["email"];
    $emergence = $_POST["emergence"]; 
    $namemm = $_POST["namemm"];
    $attachdoc = $_POST["attachdoc"];
    $cellphone = $_POST["cellphone"];
    $realgrade = $_POST["realgrade"];

    if($_FILES['file']['name'] != ''){
        $filename = $_FILES['file']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $valid_extension = array("jpg","jpeg","png","JPG","PNG","JPEG");
        if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/student/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                $sql = "insert into tblstudentprofile (LoginID,StudentID,Name,DOB,Age,NationalID,
                ReligionID,FatherName,FatherWork,MotherName,MotherWork,Img,Gender,SchoolDate,ParentAddress,
                LastSchoolName,AllowGrade,TopGrade,KG,G1,G2,G3,G4,G5,G6,G7,G8,G9,G10,G11,G12,OutReason,Rmk,
                BirthPlace,PhoneNo,Email,Emergence,NameMM,AttachDoc,RealGrade,CellPhone,Fnrc,Mnrc)  
                values ('{$userid}','{$studentid}','{$name}','{$dob}','{$age}','{$nationality}',
                '{$religion}','{$fname}','{$fwork}','{$mname}','{$mwork}','{$new_filename}',
                '{$gender}','{$schooldt}','{$address}','{$lastschoolname}','{$allowgrade}',
                '{$topgrade}','{$KG}','{$G1}','{$G2}','{$G3}','{$G4}','{$G5}','{$G6}','{$G7}',
                '{$G8}','{$G9}','{$G10}','{$G11}','{$G12}','{$outreason}','{$rmk}',
                '{$place}','{$phno}','{$email}','{$emergence}','{$namemm}','{$attachdoc}'
                ,'{$realgrade}','{$cellphone}','{$fnrc}','{$mnrc}')";

                if(mysqli_query($con,$sql)){
                    // get last insert id
                    $last_id = mysqli_insert_id($con);
                    $_SESSION["last_student_id"] = $last_id; 

                    save_log($_SESSION["username"]." သည် student အားအသစ်သွင်းသွားသည်။");
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }
    }
    else{
        $sql = "insert into tblstudentprofile (LoginID,StudentID,Name,DOB,Age,NationalID,
        ReligionID,FatherName,FatherWork,MotherName,MotherWork,Gender,SchoolDate,ParentAddress,
        LastSchoolName,AllowGrade,TopGrade,KG,G1,G2,G3,G4,G5,G6,G7,G8,G9,G10,G11,G12,OutReason,Rmk,
        BirthPlace,PhoneNo,Email,Emergence,NameMM,AttachDoc,RealGrade,CellPhone,Fnrc,Mnrc)  
        values ('{$userid}','{$studentid}','{$name}','{$dob}','{$age}','{$nationality}',
        '{$religion}','{$fname}','{$fwork}','{$mname}','{$mwork}',
        '{$gender}','{$schooldt}','{$address}','{$lastschoolname}','{$allowgrade}',
        '{$topgrade}','{$KG}','{$G1}','{$G2}','{$G3}','{$G4}','{$G5}','{$G6}','{$G7}',
        '{$G8}','{$G9}','{$G10}','{$G11}','{$G12}','{$outreason}','{$rmk}',
        '{$place}','{$phno}','{$email}','{$emergence}','{$namemm}','{$attachdoc}'
        ,'{$realgrade}','{$cellphone}','{$fnrc}','{$mnrc}')";

       
        if(mysqli_query($con,$sql)){
            // get last insert id
            $last_id = mysqli_insert_id($con);
            $_SESSION["last_student_id"] = $last_id;

            save_log($_SESSION["username"]." သည် student အားအသစ်သွင်းသွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
}

if($action == "prepare"){
    $aid = $_POST["aid"];
    $_SESSION["last_student_id"] = $aid;
    echo 1;
}

if($action == 'edit'){   
    $aid = $_POST["aid"]; 
    $studentid = $_POST["studentid"];
    $name = $_POST["name"];
    $dob = $_POST["dob"];
    $age = $_POST["age"];
    $nationality = $_POST["nationality"];
    $religion = $_POST["religion"];
    $fname = $_POST["fname"];
    $fwork = $_POST["fwork"];
    $fnrc = $_POST["fnrc"];
    $mname = $_POST["mname"];
    $mnrc = $_POST["mnrc"];
    $mwork = $_POST["mwork"];
    $path = $_POST["path"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $schooldt = $_POST["schooldt"];
    $lastschoolname = $_POST["lastschoolname"];
    $allowgrade = $_POST["allowgrade"];
    $topgrade = $_POST["topgrade"];
    $KG = $_POST["KG"];
    $G1 = $_POST["G1"];
    $G2 = $_POST["G2"];
    $G3 = $_POST["G3"];
    $G4 = $_POST["G4"];
    $G5 = $_POST["G5"];
    $G6 = $_POST["G6"];
    $G7 = $_POST["G7"];
    $G8 = $_POST["G8"];
    $G9 = $_POST["G9"];
    $G10 = $_POST["G10"];
    $G11 = $_POST["G11"];
    $G12 = $_POST["G12"];
    $outreason = $_POST["outreason"];
    $rmk = $_POST["rmk"];
    $place = $_POST["place"];
    $phno = $_POST["phno"];
    $email = $_POST["email"];
    $emergence = $_POST["emergence"];
    $namemm = $_POST["namemm"];
    $attachdoc = $_POST["attachdoc"];
    $cellphone = $_POST["cellphone"];
    $realgrade = $_POST["realgrade"];

    if($_FILES['file']['name'] != ''){
        $filename = $_FILES['file']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $valid_extension = array("jpg","jpeg","png","JPG","PNG","JPEG");
        if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/student/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                if($path != ""){
                    unlink(root."upload/student/".$path);
                }

                $sql = "update tblstudentprofile set Name='{$name}',StudentID='{$studentid}',DOB='{$dob}',Age='{$age}',
                NationalID='{$nationality}',ReligionID='{$religion}',FatherName='{$fname}',
                FatherWork='{$fwork}',MotherName='{$mname}',MotherWork='{$mwork}',Img='{$new_filename}',
                Gender='{$gender}',ParentAddress='{$address}',SchoolDate='{$schooldt}',
                LastSchoolName='{$lastschoolname}',AllowGrade='{$allowgrade}',TopGrade='{$topgrade}',
                KG='{$KG}',G1='{$G1}',G2='{$G2}',G3='{$G3}',G4='{$G4}',G5='{$G5}',G6='{$G6}',
                G7='{$G7}',G8='{$G8}',G9='{$G9}',G10='{$G10}',G11='{$G11}',G12='{$G12}',OutReason='{$outreason}',
                Rmk='{$rmk}',BirthPlace='{$place}',PhoneNo='{$phno}',Email='{$email}',Emergence='{$emergence}' 
                ,NameMM='{$namemm}',AttachDoc='{$attachdoc}',RealGrade='{$realgrade}',CellPhone='{$cellphone}' 
                ,Fnrc='{$fnrc}',Mnrc='{$mnrc}' where AID={$aid}";
                if(mysqli_query($con,$sql)){
                    save_log($_SESSION["username"]." သည် student အား update လုပ်သွားသည်။");
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }
    }else{
        $sql = "update tblstudentprofile set Name='{$name}',StudentID='{$studentid}',DOB='{$dob}',Age='{$age}',
        NationalID='{$nationality}',ReligionID='{$religion}',FatherName='{$fname}',
        FatherWork='{$fwork}',MotherName='{$mname}',MotherWork='{$mwork}',
        Gender='{$gender}',ParentAddress='{$address}',SchoolDate='{$schooldt}',
        LastSchoolName='{$lastschoolname}',AllowGrade='{$allowgrade}',TopGrade='{$topgrade}',
        KG='{$KG}',G1='{$G1}',G2='{$G2}',G3='{$G3}',G4='{$G4}',G5='{$G5}',G6='{$G6}',
        G7='{$G7}',G8='{$G8}',G9='{$G9}',G10='{$G10}',G11='{$G11}',G12='{$G12}',OutReason='{$outreason}',
        Rmk='{$rmk}',BirthPlace='{$place}',PhoneNo='{$phno}',Email='{$email}',Emergence='{$emergence}'  
        ,NameMM='{$namemm}',AttachDoc='{$attachdoc}',RealGrade='{$realgrade}',CellPhone='{$cellphone}' 
        ,Fnrc='{$fnrc}',Mnrc='{$mnrc}' where AID={$aid}";
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["username"]." သည် student အား update လုပ်သွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
}

if($action == 'delete'){
    $aid = $_POST["aid"];
    $path = $_POST["path"];
    $sql = "delete from tblstudentprofile where AID={$aid}";
    if(mysqli_query($con,$sql)){
        if($path != ""){
            unlink(root.'upload/student/'.$path);
        }
        save_log($_SESSION["username"]." သည် student အားဖျက်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
    
}

if($action == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){     
        $a = " and (p.Name like '%$search%' or p.StudentID like '%$search%' 
        or p.FatherName like '%$search%' or p.MotherName like '%$search%') ";
    }    
    $sql = "select p.*,r.Name as rname,n.Name as nname  
    from tblstudentprofile p,tblreligion r,tblnational n   
    where  p.ReligionID=r.AID and p.NationalID=n.AID ".$a." 
    order by p.AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StudentReport_".date('d_m_Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="38" align="center"><h3>'.$lang["home_student"].'</h3></td>
            </tr>
            <tr><td colspan="38"><td></tr>
            <tr>    
                <th style="border: 1px solid ;">'.$lang["no"].'</th> 
                <th style="border: 1px solid ;">'.$lang["stu_id"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_name"].'</th> 
                <th style="border: 1px solid ;">'.$lang["stu_namemm"].'</th>            
                <th style="border: 1px solid ;">'.$lang["stu_dob"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_age"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_place"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_religion"].'</th> 
                <th style="border: 1px solid ;">'.$lang["stu_attachment_doc"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_attend_grade"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_gender"].'</th>               
                <th style="border: 1px solid ;">'.$lang["stu_fname"].'</th>
                <th style="border: 1px solid ;">Father NRC No</th>
                <th style="border: 1px solid ;">'.$lang["stu_mname"].'</th>
                <th style="border: 1px solid ;">Mother NRC No</th>
                <th style="border: 1px solid ;">'.$lang["stu_mwork"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_phno"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_cell_phone"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_email"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_emergence"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_address"].'</th>   
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>';
                if(isset($_SESSION['la'])){
                    if($_SESSION['la']=='my'){
                        $out.='<td style="border: 1px solid ;">'.toMyanmar($no).'</td> ';
                    }else{
                        $out.='<td style="border: 1px solid ;">'.$no.'</td> ';
                    }
                }else{
                    $out.='<td style="border: 1px solid ;">'.$no.'</td> ';
                }  
                $out.='  
                    <td style="border: 1px solid ;">'.$row["StudentID"].'</td>
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>
                    <td style="border: 1px solid ;">'.$row["NameMM"].'</td> 
                    <td style="border: 1px solid ;">'.enDate($row["DOB"]).'</td>
                    <td style="border: 1px solid ;">'.$row["Age"].'</td>                     
                    <td style="border: 1px solid ;">'.$row["BirthPlace"].'</td> 
                    <td style="border: 1px solid ;">'.$row["rname"].'</td>
                    <td style="border: 1px solid ;">'.$row["AttachDoc"].'</td>
                    <td style="border: 1px solid ;">'.$row["RealGrade"].'</td>
                    <td style="border: 1px solid ;">'.$row["Gender"].'</td>                    
                    <td style="border: 1px solid ;">'.$row["FatherName"].'</td>
                    <td style="border: 1px solid ;">'.$row["Fnrc"].'</td>
                    <td style="border: 1px solid ;">'.$row["MotherName"].'</td>
                    <td style="border: 1px solid ;">'.$row["Mnrc"].'</td>
                    <td style="border: 1px solid ;">'.$row["MotherWork"].'</td>
                    <td style="border: 1px solid ;">'.$row["PhoneNo"].'</td>
                    <td style="border: 1px solid ;">'.$row["CellPhone"].'</td>
                    <td style="border: 1px solid ;">'.$row["Email"].'</td>
                    <td style="border: 1px solid ;">'.$row["Emergence"].'</td>
                    <td style="border: 1px solid ;">'.$row["ParentAddress"].'</td>
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
                <td colspan="36" align="center"><h3>'.$lang["home_student"].'</h3></td>
            </tr>
            <tr><td colspan="36"><td></tr>
            <tr>    
            <th style="border: 1px solid ;">'.$lang["no"].'</th> 
            <th style="border: 1px solid ;">'.$lang["stu_id"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_name"].'</th> 
            <th style="border: 1px solid ;">'.$lang["stu_namemm"].'</th>            
            <th style="border: 1px solid ;">'.$lang["stu_dob"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_age"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_place"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_religion"].'</th> 
            <th style="border: 1px solid ;">'.$lang["stu_attachment_doc"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_attend_grade"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_gender"].'</th>               
            <th style="border: 1px solid ;">'.$lang["stu_fname"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_mname"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_mwork"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_phno"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_cell_phone"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_email"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_emergence"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_address"].'</th>         
            </tr>
            <tr>
                <td style="border: 1px solid ;" colspan="36" align="center">No record found.</td>
            </tr>
        </table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}

if($action == 'pdf'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){     
        $a = " and (p.Name like '%$search%' or p.StudentID like '%$search%' 
        or p.FatherName like '%$search%' or p.MotherName like '%$search%') ";
    }     
    $sql = "select p.*,r.Name as rname,n.Name as nname  
    from tblstudentprofile p,tblreligion r,tblnational n   
    where  p.ReligionID=r.AID and p.NationalID=n.AID ".$a." 
    order by p.AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StudentReport_".date('d_m_Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="38" align="center"><h3>'.$lang["home_student"].'</h3></td>
            </tr>
            <tr><td colspan="38"><td></tr>
            <tr>    
                <th style="border: 1px solid ;">'.$lang["no"].'</th> 
                <th style="border: 1px solid ;">'.$lang["stu_id"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_name"].'</th>  
                <th style="border: 1px solid ;">'.$lang["stu_namemm"].'</th>            
                <th style="border: 1px solid ;">'.$lang["stu_dob"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_age"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_place"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_religion"].'</th> 
                <th style="border: 1px solid ;">'.$lang["stu_attachment_doc"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_attend_grade"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_gender"].'</th>               
                <th style="border: 1px solid ;">'.$lang["stu_fname"].'</th>
                <th style="border: 1px solid ;">Father NRC No</th>
                <th style="border: 1px solid ;">'.$lang["stu_mname"].'</th>
                <th style="border: 1px solid ;">Mother NRC No</th>
                <th style="border: 1px solid ;">'.$lang["stu_mwork"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_phno"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_cell_phone"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_email"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_emergence"].'</th>
                <th style="border: 1px solid ;">'.$lang["stu_address"].'</th>   
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>';
                if(isset($_SESSION['la'])){
                    if($_SESSION['la']=='my'){
                        $out.='<td style="border: 1px solid ;">'.toMyanmar($no).'</td> ';
                    }else{
                        $out.='<td style="border: 1px solid ;">'.$no.'</td> ';
                    }
                }else{
                    $out.='<td style="border: 1px solid ;">'.$no.'</td> ';
                }  
                $out.='  
                    <td style="border: 1px solid ;">'.$row["StudentID"].'</td>
                    <td style="border: 1px solid ;">'.$row["Name"].'</td> 
                    <td style="border: 1px solid ;">'.$row["NameMM"].'</td> 
                    <td style="border: 1px solid ;">'.enDate($row["DOB"]).'</td>
                    <td style="border: 1px solid ;">'.$row["Age"].'</td>                     
                    <td style="border: 1px solid ;">'.$row["BirthPlace"].'</td> 
                    <td style="border: 1px solid ;">'.$row["rname"].'</td>
                    <td style="border: 1px solid ;">'.$row["AttachDoc"].'</td>
                    <td style="border: 1px solid ;">'.$row["RealGrade"].'</td>
                    <td style="border: 1px solid ;">'.$row["Gender"].'</td>                    
                    <td style="border: 1px solid ;">'.$row["FatherName"].'</td>
                    <td style="border: 1px solid ;">'.$row["Fnrc"].'</td>
                    <td style="border: 1px solid ;">'.$row["MotherName"].'</td>
                    <td style="border: 1px solid ;">'.$row["Mnrc"].'</td>
                    <td style="border: 1px solid ;">'.$row["MotherWork"].'</td>
                    <td style="border: 1px solid ;">'.$row["PhoneNo"].'</td>
                    <td style="border: 1px solid ;">'.$row["CellPhone"].'</td>
                    <td style="border: 1px solid ;">'.$row["Email"].'</td>
                    <td style="border: 1px solid ;">'.$row["Emergence"].'</td>
                    <td style="border: 1px solid ;">'.$row["ParentAddress"].'</td>
                </tr>';
        }
        $out .= '</table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'StudentPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="36" align="center"><h3>'.$lang["home_student"].'</h3></td>
            </tr>
            <tr><td colspan="36"><td></tr>
            <tr>    
            <th style="border: 1px solid ;">'.$lang["no"].'</th> 
            <th style="border: 1px solid ;">'.$lang["stu_id"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_name"].'</th>  
            <th style="border: 1px solid ;">'.$lang["stu_namemm"].'</th>            
            <th style="border: 1px solid ;">'.$lang["stu_dob"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_age"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_place"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_religion"].'</th> 
            <th style="border: 1px solid ;">'.$lang["stu_attachment_doc"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_attend_grade"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_gender"].'</th>               
            <th style="border: 1px solid ;">'.$lang["stu_fname"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_mname"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_mwork"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_phno"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_cell_phone"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_email"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_emergence"].'</th>
            <th style="border: 1px solid ;">'.$lang["stu_address"].'</th>          
            </tr>
            <tr>
                <td style="border: 1px solid ;" colspan="36" align="center">No record found.</td>
            </tr>
        </table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'StudentPDF'.date("d_m_Y").'.pdf';
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
            
            $reader = new SpreadsheetReader($new_path);
            $count = 0;
            foreach($reader as $key => $row){
                if($count > 0){
                    $studentid = $row[1];
                    $name_en = $row[2];
                    $name_mm = $row[3];
                    $dt = strtotime($row[4]);
                    $dob = date('Y-m-d',$dt);
                    $age = $row[5];
                    $birthplace = $row[6];
                    $gender = $row[7];
                    $nationality = 0;
                    if($row[8] != ""){
                        $nationality = GetInt("select AID from tblnational where Name='{$row[8]}'");
                    }
                    $religion = 0;    
                    if($row[9] != ""){
                        $religion = GetInt("select AID from tblreligion where Name='{$row[9]}'");
                    }
                    $fname = $row[10];
                    $mname = $row[11];
                    $occupation = $row[12];//mother work
                    $workphno = $row[13];
                    $email = $row[14];
                    $emergency = $row[15];
                    $address = $row[16];
                    // $sql = "insert into tblstudentprofile (LoginID,StudentID,Name,DOB,Age,Gender)  
                    // values ('{$userid}','{$studentid}','{$name}','{$dob}','{$age}','{$gender}')";
                    // mysqli_query($con,$sql);
                    $sql = 'insert into tblstudentprofile (StudentID,Name,NameMM,DOB,Age,BirthPlace,
                    Gender,NationalID,ReligionID,FatherName,MotherName,MotherWork,PhoneNo,Email,
                    Emergence,ParentAddress)  
                    values ("'.$studentid.'","'.$name_en.'","'.$name_mm.'","'.$dob.'","'.$age.'",
                    "'.$birthplace.'","'.$gender.'","'.$nationality.'","'.$religion.'","'.$fname.'",
                    "'.$mname.'","'.$occupation.'","'.$workphno.'","'.$email.'","'.$emergency.'",
                    "'.$address.'")';
                    mysqli_query($con,$sql);
                    // echo $sql;
                }
                else{
                    $count = 1;
                }                
            }
            
            unlink(root.'upload/student/'.$new_filename);

            echo 1;
        }
    }
}

if($action == "attachment"){
    $aid = $_POST["aid"];
    $_SESSION["attachment_stuid"] = $aid;
    echo 1;
}

// for attachment
if($action == "show_attachment"){
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
        $a = " and (t.Title like '%$search%' or p.Name like '%$search%') ";
    }  
    $stuid = $_SESSION["attachment_stuid"];  
    $sql = "select t.*,p.Name from tblattachment t,tblstudentprofile p  
    where t.StudentID={$stuid} and t.StudentID=p.AID ".$a." 
    order by t.AID desc limit $offset,$limit_per_page";
    $result = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowarp">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["stu_name"].'</th>
            <th>'.$lang["stu_atttitle"].'</th>            
            <th class="text-center">'.$lang["stu_attupload"].'</th>
            <th class="text-center">Download</th>
            <th width="8%;">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $url = roothtml.'upload/student/'.$row["File"];
            $out.="<tr>";
                if(isset($_SESSION['la'])){
                    if($_SESSION['la']=='my'){
                        $out.="<td class='text-center'>".toMyanmar($no)."</td>";
                    }else{
                        $out.="<td class='text-center'>{$no}</td>";
                    }
                }else{
                    $out.="<td class='text-center'>{$no}</td>";
                }
            $out.="
                <td>{$row["Name"]}</td>
                <td>{$row["Title"]}</td>
                <td class='text-center'>{$row["File"]}</td>
                <td class='text-center'>
                    <a href='{$url}' download><i class='fas fa-download'></i></a>
                </td>
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                            <a href='#' id='btnedit' class='dropdown-item'
                                data-aid='{$row['AID']}' 
                                data-title='{$row['Title']}' 
                                data-path='{$row['File']}' >
                                <i class='fas fa-edit text-primary' style='font-size:13px;'></i>
                                ".$lang['btnedit']."</a>
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}' 
                                data-path='{$row['File']}'><i
                                class='fas fa-trash text-danger'
                                style='font-size:13px;'></i>
                                ".$lang['btndelete']."</a>                           
                        </div>
                    </div>
                </td>     
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table><br><br>";

        $sql_total="select t.AID from tblattachment t,tblstudentprofile p  
        where t.StudentID={$stuid} and t.StudentID=p.AID ".$a." 
        order by t.AID desc";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>'.$lang["totalrecord"].' -  ';
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
        <table class="table table-bordered table-striped responsive nowarp">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["stu_name"].'</th>
            <th>'.$lang["stu_atttitle"].'</th>            
            <th class="text-center">'.$lang["stu_attupload"].'</th>
            <th class="text-center">Download</th>
            <th width="8%;">Action</th>         
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }
}

if($action == "save_attachment"){    
    $stuid = $_SESSION["attachment_stuid"];
    $title = $_POST["title"];
    if($_FILES['file']['name'] != ''){
        $filename = $_FILES['file']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $valid_extension = array("jpg","jpeg","png","JPG","PNG","JPEG");
        // if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/student/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                $sql = "insert into tblattachment (StudentID,Title,File)  
                values ('{$stuid}','{$title}','{$new_filename}')";
                if(mysqli_query($con,$sql)){
                    save_log($_SESSION["username"]." သည် student attachment အားအသစ်သွင်းသွားသည်။");
                    echo 1;
                }else{
                    echo 0;
                }
            }
        // }
    }
}

if($action == "edit_attachment"){    
    $aid = $_POST["eaid"];
    $title = $_POST["etitle"];
    $path = $_POST["epath"];
    if($_FILES['efile']['name'] != ''){
        $filename = $_FILES['efile']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['efile']['tmp_name'];
        $valid_extension = array("jpg","jpeg","png","JPG","PNG","JPEG");
        // if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/student/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                if($path != ""){
                    unlink(root."upload/student/".$path);
                }
                $sql = "update tblattachment set Title='{$title}',File='{$new_filename}' 
                where AID={$aid}";
                if(mysqli_query($con,$sql)){
                    save_log($_SESSION["username"]." သည် student attachment အား edit သွားသည်။");
                    echo 1;
                }else{
                    echo 0;
                }
            }
        // }
    }else{
        $sql = "update tblattachment set Title='{$title}'  
        where AID={$aid}";
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["username"]." သည် student attachment အား edit သွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
}

if($action == 'delete_attachment'){
    $aid = $_POST["aid"];
    $path = $_POST["path"];
    $sql = "delete from tblattachment where AID={$aid}";
    if(mysqli_query($con,$sql)){
        if($path != ""){
            unlink(root.'upload/student/'.$path);
        }
        save_log($_SESSION["username"]." သည် student attachment အားဖျက်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
    
}

?>