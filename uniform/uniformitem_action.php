<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');
$action = $_POST["action"];
$userid=$_SESSION['userid'];

if($action == 'show'){  

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
            <th>Date</th>
            <th width="10%;">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["Name"]}</td>   
                <td>{$row["cname"]}</td> 
                <td>{$row["Size"]}</td> 
                <td>".number_format($row["Price"])."</td> 
                <td>".enDate($row["Date"])."</td> 
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                        <a href='#' id='btnedit' class='dropdown-item'
                        data-aid='{$row['AID']}' data-toggle='modal'
                        data-target='#editmodal'><i class='fas fa-edit text-primary'
                        style='font-size:13px;'></i>
                    {$lang['staff_edit']}</a>
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}'><i
                                class='fas fa-trash text-danger'
                                style='font-size:13px;'></i>
                            {$lang['staff_delete']}</a>                        
                        </div>
                    </div>
                </td> 
                
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
            <th>Date</th>
            <th width="10%;">Action</th>             
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}

if($action == 'save'){
    $itemname = $_POST["itemname"];
    $category = $_POST["category"];
    $size = $_POST["size"];
    $price = $_POST["price"];
    $dt = $_POST["dt"];
    $rmk = $_POST["rmk"];
    $chk = "select AID from tblitemuniform where Name='{$itemname}' and Size='{$size}' and 
    CategoryID='{$category}'";
    $res = mysqli_query($con,$chk);
    if(mysqli_num_rows($res) > 0){
        $upd = "update tblitemuniform set Price='{$price}',Date='{$dt}',Rmk='{$rmk}'    
        where Name='{$itemname}' and Size='{$size}' and CategoryID='{$category}'";
        if(mysqli_query($con,$upd)){
            save_log($_SESSION["username"]." သည် uniform item အားအသစ်သွင်းသွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }else{
        $sql = "insert into tblitemuniform  (Name,CategoryID,Size,Price,Date,Rmk) 
        values ('{$itemname}','{$category}','{$size}','{$price}','{$dt}','{$rmk}')";
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["username"]." သည် uniform item အားအသစ်သွင်းသွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
    
}


if($action == 'editprepare'){
    $aid = $_POST["aid"];
    $sql = "select i.*,c.Name as cname from tblitemuniform i,tblcategoryuniform c  
    where i.CategoryID=c.AID and i.AID=$aid";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="
                <div class='modal-body'>
                    <input type='hidden' name='eaid' value='{$row['AID']}'/> 
                    <input type='hidden' name='action' value='editsave' />                              
                    <div class='form-group'>
                        <label for='usr'> ItemName :</label>
                        <input type='text' class='form-control border-success' name='eitemname' value='{$row['Name']}'>
                    </div>
                    <div class='form-group'>
                        <label for='usr'> Category :</label>
                        <select class='form-control boder-success' name='ecategory'>
                            <option value='{$row['CategoryID']}'>{$row['cname']}</option>
                            ".load_uniformcategory()."
                        </select>
                    </div>
                    <div class='form-group'>
                        <label for='usr'> Size :</label>
                        <input type='text' class='form-control border-success' name='esize' value='{$row['Size']}'>
                    </div>
                    <div class='form-group'>
                        <label for='usr'> Price :</label>
                        <input type='number' class='form-control border-success' name='eprice' value='{$row['Price']}'>
                    </div>
                    <div class='form-group'>
                        <label for='usr'> Date :</label>
                        <input type='date' class='form-control border-success' name='edt' value='{$row['Date']}'>
                    </div>
                    <div class='form-group'>
                        <label for='usr'> Remark :</label>
                        <input type='text' class='form-control border-success' name='ermk' value='{$row['Rmk']}'>
                    </div>                              
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnupdate' class='btn btn-{$color}'><i class='fas fa-edit'></i>  {$lang['staff_edit']}</button>
                </div>";
        }
        echo $out;
    }
}


if($action == 'update'){
    $aid = $_POST["aid"];
    $itemname = $_POST["itemname"];
    $category = $_POST["category"];
    $size = $_POST["size"];
    $price = $_POST["price"];
    $dt = $_POST["dt"];
    $rmk = $_POST["rmk"];
    
    $sql = "update tblitemuniform  set Name='{$itemname}',CategoryID='{$category}',
    Size='{$size}',Price='{$price}',Date='{$dt}',Rmk='{$rmk}' where AID={$aid}";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]."သည် Uniform Item အား update လုပ်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
}


if($action == 'delete'){

    $aid = $_POST["aid"];
    $sql = "delete from tblitemuniform  where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် Uniform Item အားဖျက်သွားသည်။");
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
        $a = " and (i.Name like '%$search%' or c.Name like '%$search%') ";
    }    
    $sql="select i.*,c.Name as cname from tblitemuniform i,tblcategoryuniform c  
    where i.CategoryID=c.AID ".$a." 
    order by i.AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "UniformItem-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="7" align="center"><h3>Uniform Item</h3></td>
            </tr>
            <tr><td colspan="7"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">Item Name</th>      
                <th style="border: 1px solid ;">Category </th>    
                <th style="border: 1px solid ;">Size</th>    
                <th style="border: 1px solid ;">Price</th>    
                <th style="border: 1px solid ;">Date</th>    
                <th style="border: 1px solid ;">Remark</th>     
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>     
                    <td style="border: 1px solid ;">'.$row["cname"].'</td>       
                    <td style="border: 1px solid ;">'.$row["Size"].'</td>       
                    <td style="border: 1px solid ;">'.number_format($row["Price"]).'</td>       
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>       
                    <td style="border: 1px solid ;">'.$row["Rmk"].'</td>                 
                </tr>';
        }
        $out .= '</table>';

        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }else{
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="7" align="center"><h3>Uniform Item</h3></td>
            </tr>
            <tr><td colspan="7"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">Item Name</th>      
                <th style="border: 1px solid ;">Category </th>    
                <th style="border: 1px solid ;">Size</th>    
                <th style="border: 1px solid ;">Price</th>    
                <th style="border: 1px solid ;">Date</th>    
                <th style="border: 1px solid ;">Remark</th>     
            </tr>
            <tr>
                <td colspan="7" align="center">No data</td>
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
        $a = " and (i.Name like '%$search%' or c.Name like '%$search%') ";
    }    
    $sql="select i.*,c.Name as cname from tblitemuniform i,tblcategoryuniform c  
    where i.CategoryID=c.AID ".$a." 
    order by i.AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "UniformItem-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="7" align="center"><h3>Uniform Item</h3></td>
            </tr>
            <tr><td colspan="7"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">Item Name</th>      
                <th style="border: 1px solid ;">Category </th>    
                <th style="border: 1px solid ;">Size</th>    
                <th style="border: 1px solid ;">Price</th>    
                <th style="border: 1px solid ;">Date</th>    
                <th style="border: 1px solid ;">Remark</th>     
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>     
                    <td style="border: 1px solid ;">'.$row["cname"].'</td>       
                    <td style="border: 1px solid ;">'.$row["Size"].'</td>       
                    <td style="border: 1px solid ;">'.number_format($row["Price"]).'</td>       
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>       
                    <td style="border: 1px solid ;">'.$row["Rmk"].'</td>                 
                </tr>';
        }
        $out .= '</table>';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'UniformItemPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="7" align="center"><h3>Uniform Item</h3></td>
            </tr>
            <tr><td colspan="7"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">Item Name</th>      
                <th style="border: 1px solid ;">Category </th>    
                <th style="border: 1px solid ;">Size</th>    
                <th style="border: 1px solid ;">Price</th>    
                <th style="border: 1px solid ;">Date</th>    
                <th style="border: 1px solid ;">Remark</th>     
            </tr>
            <tr>
                <td colspan="7" align="center">No data</td>
            </tr>
        </table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'UniformItemPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }   
    
}


?>