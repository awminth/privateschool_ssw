<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');
$action = $_POST["action"];
$userid=$_SESSION['userid'];

//Income Year
if($action == 'showIncome'){  

    $limit_per_page=""; 
    if($_POST['entryvalue']==""){
        $limit_per_page=10; 
    } else{
        $limit_per_page=$_POST['entryvalue']; 
    }
    
    $page="";
    $no=0;
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
        $a = " and (YearName like '%$search%') ";
    } 
    $sql="select * from tblallowanceyear where AID is not null ".$a."
        order by AID desc limit {$offset},{$limit_per_page}";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>';
        while($row = mysqli_fetch_array($result)){
            $out .= '
            <tr>
                <th class="text-center">
                    <a href="#" id="btngo" class="dropdown-item" style="font-size:20px;"
                    data-aid='.$row["AID"].'><i class="fas fa-atom text-primary"
                    style="font-size:16px;"></i>
                    '.$row["YearName"].'</a>           
                </th>
                <th class="text-center">
                    <button type="button" class="btn btn-success" id="btnedit"
                    data-aid='.$row["AID"].'
                    data-yearname='.$row["YearName"].' ><i class="fas fa-edit text-light p-2"
                        style="font-size:13px;"></i>Edit YearName</button>          
                </th>
            </tr>
            ';
        }
        $out.="</thead>";
        $out.="</table>";

        $sql_total="";
        $sql_total="select * from tblallowanceyear where AID is not null ".$a."
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
                if($next_id >= $total_links){
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
        
    }
    else{
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>Year Name</th>
            <th width="10%;">Action</th>           
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}

if($action == 'goincome'){ 
    $incomeyearid=$_POST['aid'];
    $_SESSION['incomeyearid']=$incomeyearid;
    echo 1;
}

if($action == 'saveincomeyear'){
    $yearname = $_POST["yearname"];
    $sql = "insert into tblallowanceyear (YearName) values ('{$yearname}')";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် Allowance Income Year အားအသစ်သွင်းသွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}

if($action == 'updateincomeyear'){
    $aid = $_POST["aid"];
    $yearname = $_POST["yearname"];
    $sql = "update tblallowanceyear set YearName='{$yearname}' where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]."သည် Allowance Income Year အား update လုပ်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
}


?>