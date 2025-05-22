<?php
include('../config.php');

$action = $_POST["action"];
$userid = $_SESSION["userid"];

if($action == 'show'){  
    $limit_per_page=10;     
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
        $a = " and (i.Name like '%$search%' or c.Name like '%$search%') ";
    }    
    $sql="select i.*,c.Name as cname from tblitemuniform i,tblcategoryuniform c  
    where i.CategoryID=c.AID ".$a." 
    order by i.AID desc limit {$offset},{$limit_per_page}";
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>Item Name</th>
            <th>Category</th>
            <th>Size</th>
            <th>Price</th>          
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $out.="<tr style='cursor:pointer;' 
                id='btnchooseitem' 
                data-aid='{$row['AID']}' 
                data-itemname='{$row['Name']}'
                data-size='{$row['Size']}'
                data-price='{$row['Price']}'
                data-categoryid='{$row['CategoryID']}' >
                <td>{$no}</td>
                <td>{$row["Name"]}</td>   
                <td>{$row["cname"]}</td> 
                <td>{$row["Size"]}</td> 
                <td>".number_format($row["Price"])."</td>                 
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select i.AID from tblitemuniform i,tblcategoryuniform c  
        where i.CategoryID=c.AID ".$a." 
        order by i.AID desc";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>Total Record -  ';
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
        
    }
    else{
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>Item Name</th>
            <th>Category</th>
            <th>Size</th>
            <th>Price</th>          
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}

if($action == "add_item"){
    $aid = $_POST["aid"];
    $itemname = $_POST["itemname"];
    $size = $_POST["size"];
    $price = $_POST["price"];
    $categoryid = $_POST["categoryid"];
    $chk = "select AID,Qty from tblselluniform_temp  
    where ItemID='{$aid}' and UserID='{$userid}'";
    $res = mysqli_query($con,$chk);
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $qty = $row["Qty"] + 1;
        $sql_upd = "update tblselluniform_temp set Qty='{$qty}'  
        where ItemID='{$aid}' and UserID='{$userid}'";
        mysqli_query($con,$sql_upd);
    }else{
        $sql_in = "insert into tblselluniform_temp (ItemID,Name,Price,Size,CategoryID,UserID,Qty)  
        values ('{$aid}','{$itemname}','{$price}','{$size}','{$categoryid}','{$userid}',1)";
        mysqli_query($con,$sql_in);
    }
}

if($action == "delete_item"){
    $aid = $_POST["aid"];
    $sql = "delete from tblselluniform_temp where AID={$aid}";
    if(mysqli_query($con,$sql)){
        echo 1;
    }else{
        echo 0;
    }
}

if($action == "choose_item"){
    $sql="select * from tblselluniform_temp where UserID='{$userid}'";    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>Name</th>
            <th class="text-right">Price</th>
            <th class="text-center">Size</th>
            <th class="text-center">Qty</th>   
            <th class="text-right">Total</th>   
            <th><i class="fas fa-trash"></i></th>     
        </tr>
        </thead>
        <tbody>
        ';
        $no = 0;
        $total = 0;
        $all_total = 0;
        while($row = mysqli_fetch_array($result)){
            $no = $no+1;
            $total = $row["Price"]*$row["Qty"];
            $all_total = $all_total + $total;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["Name"]}</td>   
                <td class='text-right'>".number_format($row["Price"])."</td> 
                <td class='text-center'>{$row["Size"]}</td> 
                <td class='text-center'>{$row["Qty"]}</td> 
                <td class='text-right'>".number_format($total)."</td>     
                <td>
                    <a href='#' id='btndelete' 
                        data-aid='{$row['AID']}' ><i class='fas fa-trash text-danger'></i></a>
                </td>            
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";
        $out.='
        <div class="pr-2">
            <div class="form-group row">
                <label for="usr" class="col-sm-8 text-right">Total</label>
                <input type="number" class="form-control form-control-sm col-sm-4" style="font-size:18px;" 
                    name="total" value="'.$all_total.'" readonly>
            </div>
            <div class="form-group row">
                <label for="usr" class="col-sm-8 text-right">Disc(%)</label>
                <input type="number" class="form-control form-control-sm col-sm-4" style="font-size:18px;" 
                    name="disc" id="disc" value="0" >
            </div>
            <div class="form-group row">
                <label for="usr" class="col-sm-8 text-right">Tax(%)</label>
                <input type="number" class="form-control form-control-sm col-sm-4" style="font-size:18px;" 
                    name="tax" id="tax" value="0" >
            </div>
            <div class="form-group row">
                <label for="usr" class="col-sm-8 text-right">Grand Total</label>
                <input type="number" class="form-control form-control-sm col-sm-4" style="font-size:18px;" 
                    name="grandtotal" value="'.$all_total.'" readonly>
            </div>
            <div class="text-right pb-2">
                <button type="button" id="btnsave" 
                    class="btn btn-primary btn-sm text-right"><i class="fas fa-save"></i>
                    Save</button>
            </div>
        </div>
        ';
        echo $out;         
    }else{
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>Name</th>
            <th class="text-right">Price</th>
            <th class="text-center">Size</th>
            <th class="text-center">Qty</th>   
            <th class="text-right">Total</th>          
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="text-center">No choose data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }
}

if($action == "save"){
    $student = $_POST["student"];
    $disc = $_POST["disc"];
    $tax = $_POST["tax"];
    $total = $_POST["total"];
    $grandtotal = $_POST["grandtotal"];
    $vno = date("Ymd-His");
    $dt = date("Y-m-d");
    $grade = $_POST["grade"];
    $out = "";
    $sql_s = "insert into tblselluniform (VNO,ItemID,Name,Price,Size,CategoryID,Qty,Date)  
    select '{$vno}',ItemID,Name,Price,Size,CategoryID,Qty,'{$dt}'  
    from tblselluniform_temp 
    where UserID='{$userid}'";
    if(mysqli_query($con,$sql_s)){
        $sql_v = "insert into tblvoucheruniform (VNO,StudentID,Total,Disc,Tax,Amount,
        UserID,Date,GradeID) 
        values ('{$vno}','{$student}','{$total}','{$disc}','{$tax}','{$grandtotal}','{$userid}',
        '{$dt}','{$grade}')";
        if(mysqli_query($con,$sql_v)){
            save_log($_SESSION["username"]." သည် uniform item အား sale သွားသည်။");
            // delete temp
            $sql_del = "delete from tblselluniform_temp where UserID='{$userid}'";
            mysqli_query($con,$sql_del);
            // show voucher ///////
            $stu_name = GetString("select Name from tblstudentprofile where AID='{$student}'");
            $stu_grade = GetString("select Name from tblgrade where AID='{$grade}'");
            $out .= "
            <div id='printdata'>
                <h5 class='text-center'>
                    ".$_SESSION["shopname"]."<br>
                    ".$_SESSION["shopaddress"]."
                </h5>
                <p class='txtl fs'>
                    VNO : {$vno} <br>
                    Student : {$stu_name} <br>
                    Grade : {$stu_grade} <br>
                    Date : ".enDate($dt)."
                </p>
                <table class='table table\-bordered text\-sm' frame=hsides rules=rows width='100%'>
                    <tr>
                        <th class='text-center txtc'>No</th>
                        <th class='txtl'>Name</th>
                        <th class='text-right txtr'>Price</th>
                        <th class='text-center txtc'>Size</th>
                        <th class='text-center txtc'>Qty</th>
                        <th class='text-right txtr'>Total</th>
                    </tr>
                ";
                $sql_item = "select * from tblselluniform where VNO='{$vno}'";
                $res_item = mysqli_query($con,$sql_item);
                $no = 0;
                if(mysqli_num_rows($res_item) > 0){
                    while($row_item = mysqli_fetch_array($res_item)){
                        $no = $no + 1;
                        $out.="                   
                        <tr>
                            <td class='text-center txtc'>{$no}</td>
                            <td class='txtl'>{$row_item['Name']}</td>
                            <td class='text-right txtr'>".number_format($row_item['Price'])."</td>
                            <td class='text-center txtc'>{$row_item['Size']}</td>
                            <td class='text-center txtc'>{$row_item['Qty']}</td>
                            <td class='text-right txtr'>".number_format($row_item['Price']*$row_item['Qty'])."</td>
                        </tr>                    
                        ";
                    }
                }
                $out.="<tr>
                        <td colspan='5' class='text-right txtr'>
                            Total :<br>
                            Disc(%) :<br>
                            Tax(%) :<br>
                            Grand Total :<br>
                        </td>
                        <td class='text-right txtr'>
                            ".number_format($total)."<br>
                            ".number_format($disc)."<br>
                            ".number_format($tax)."<br>
                            ".number_format($grandtotal)."<br>
                        </td>
                    </tr>                             
                    <tr class='text-center txtc'>
                        <td colspan='6'>----Thank You----</td>   
                    </tr>
                </table>
                <br>
                ";
                $out.="
            </div>
            <br>
            <button class='btn btn-{$color}' id='btnprint' >Print</button>
            ";  
            /////////
            echo $out;
        }else{
            echo 0;
        }
    }else{
        echo 0;
    }
}


?>