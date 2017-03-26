<title>XHPROF TEST</title>
<style>
body{margin-left:40px;}
td{padding: 3px;}
tr:hover{background-color: #D8E0DA;}
.date{color:gray}
a{color:#333333}
td a{display: block; text-decoration: none;}
.on{background-color: yellow}
</style>
<?php
#require "/ssd/www/zencart/website/xhprof.php";
# $str = 'a:5:{s:35:"main()==>register_shutdown_function";a:5:{s:2:"ct";i:1;s:2:"wt";i:3;s:3:"cpu";i:0;s:2:"mu";i:1176;s:3:"pmu";i:1048;}s:22:"shutdown==>str_replace";a:5:{s:2:"ct";i:1;s:2:"wt";i:1;s:3:"cpu";i:0;s:2:"mu";i:872;s:3:"pmu";i:872;}s:25:"shutdown==>xhprof_disable";a:5:{s:2:"ct";i:1;s:2:"wt";i:0;s:3:"cpu";i:0;s:2:"mu";i:768;s:3:"pmu";i:624;}s:17:"main()==>shutdown";a:5:{s:2:"ct";i:1;s:2:"wt";i:8;s:3:"cpu";i:0;s:2:"mu";i:3024;s:3:"pmu";i:2976;}s:6:"main()";a:5:{s:2:"ct";i:1;s:2:"wt";i:22;s:3:"cpu";i:0;s:2:"mu";i:5168;s:3:"pmu";i:5424;}}';
# 
# echo $str."<br/><br/>";
# 
# var_dump( unserialize($str));
# 
# echo "<br/>";
# 
# echo ( unserialize($str)["main()"]["wt"]);

$gui_host = '{{ xhprof_gui_domain }}';

$more_than = 0.5; # 0.5s
if(isset($_GET['more_than']) && !empty($_GET['more_than'])){
    $more_than = $_GET['more_than'];
}
$kw = "";
if(isset($_REQUEST['kw']) && !empty($_REQUEST['kw'])){
    $kw = $_REQUEST['kw'];
}

function more_than_class($more_than,$more_than_now){
    if($more_than == $more_than_now){
        return "on";
    }
}


echo "<h3>XHPROF - More Than: ${more_than}s</h3>";
echo "<div>";
echo "<a class='".more_than_class(0.0, $more_than)."' href='?more_than=0.0&kw=${kw}'>All</a> ";
echo "<a class='".more_than_class(0.3, $more_than)."' href='?more_than=0.3&kw=${kw}'>0.3s</a> ";
echo "<a class='".more_than_class(0.5, $more_than)."' href='?more_than=0.5&kw=${kw}'>0.5s</a> ";
echo "<a class='".more_than_class(0.8, $more_than)."' href='?more_than=0.8&kw=${kw}'>0.8s</a> ";
echo "<a class='".more_than_class(2, $more_than)."' href='?more_than=2&kw=${kw}'>2s</a> ";
echo "</div>";
echo "<br/>";
echo "<div>
    <form action='?more_than=${more_than}' method='post'>
    <input type='text' name='kw' value='${kw}'/>
    <input type='submit' value='Search Page'/>
    </form>
    </div>
    ";


$dir = "/data/xhprof_result/";
$file = scandir($dir);
krsort($file);   // 根据key倒叙排列（时间倒叙输出）
#print_r($file[2]);

echo '<div><table  cellpadding="0" cellspacing="0" border="1px" style="border-color:rgba(153, 153, 153, 0.15);marging:2px;">
    <th>Date</th><th>Page</th><th>Time(s)</th>
    ';
foreach($file as $value){
    if($value != "." && $value != ".."){
        $file_content = file_get_contents($dir.$value);
        #$total_incl_wall_time = unserialize($file_content)["main()"]["wt"]/1000/1000;


        if(xcache_isset($value)){
            $total_incl_wall_time = xcache_get($value)['time_cost'];
            $file_ctime = xcache_get($value)['time_create'];
        }else{
            $total_incl_wall_time = unserialize($file_content)["main()"]["wt"]/1000/1000;
            # file create time
            $file_ctime = filectime($dir.$value);
            $file_ctime = date("Y-m-d H:i:s",$file_ctime);
            xcache_set($value,['time_cost'=>$total_incl_wall_time, 'time_create'=>$file_ctime], 1200);
        }
        # 当获取的记录时间大于 过滤时间，且包含过滤关键字时，则输出记录
        if($total_incl_wall_time > $more_than && str_include($value, $kw)){
            if($total_incl_wall_time > 3.0){
                $load_color='#CC0000';
            }elseif($total_incl_wall_time > 1.0){
                $load_color='#FF7911';
            }else{
                $load_color='#009933';
            }

            $id = explode(".",$value)[0];
            $source = explode(".",$value)[1];
            $link = "index.php?run=${id}&source=${source}&sort=excl_wt";

            echo "<tr>
                <td class='date'>".$file_ctime."</td>
                <td><a target='_blank' href='${link}'>".$value."</a></td>
                <td style='color: ${load_color}'>".$total_incl_wall_time ."</td>
                </tr>";
        }
    }
}
echo '</table></div>';

function str_include($source, $inc){
    # echo $source;
    if(stripos($source,$inc)!==false || $inc == ""){
        return true;
    }else{
        return false;
    }
}

