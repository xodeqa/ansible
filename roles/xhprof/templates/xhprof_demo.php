<?php
# desc: xhprof test func
# author: dbquan
# usage: require this file as needed

function shutdown(){
    // get main_page
    # $main_page = $_SERVER['REQUEST_URI'];
    $main_page = "not_set";
    if(isset($_GET['main_page']) && $_GET['main_page']!=''){
        $main_page = "main_page:".$_GET['main_page'];
    } elseif(isset($_GET['__page_id']) && $_GET['__page_id']!=''){
        $main_page = "__page_id:".$_GET['__page_id'];
    } elseif(isset($_GET['__api__']) && $_GET['__api__']!=''){
        $main_page = "__api__:".$_GET['__api__'];
    }

    $main_page = str_replace("/","-",$main_page);

    $xhprof_test_data = xhprof_disable();
    $XHPROF_LIB_ROOT = '/usr/share/xhprof/xhprof_lib';
    include_once $XHPROF_LIB_ROOT . '/utils/xhprof_lib.php';
    include_once $XHPROF_LIB_ROOT . '/utils/xhprof_runs.php';
    $xhprof_runs = new XHProfRuns_Default('/data/xhprof_result');
    $run_id = $xhprof_runs->save_run($xhprof_test_data, $main_page);
    echo "<hr><h1>XHProf Executed: <a style='color:red' target='_blank' href='{{ xhprof_gui_domain }}/xxhprof/index.php?run=" . $run_id . "&source=".$main_page."&sort=excl_wt'>".$main_page."</a></h1>";
}


if((isset($_SERVER["HTTP_USER_AGENT"]) && $_SERVER["HTTP_USER_AGENT"]=="xhprof_enable") || (isset($_GET['xhprof']) && $_GET['xhprof']=='yes')){
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

    register_shutdown_function('shutdown');
}
