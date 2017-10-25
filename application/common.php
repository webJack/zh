<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Request;

function getCateTree($cateList){
	
	$treeList = [];
	$cate2nd;
	foreach ($cateList as $key => $value) {
		if($value['level'] == 1){
			$treeList[$value['cate_id']] = $value;
		}else if($value['level'] == 2){
			$cate2nd[$value['cate_id']] = $value['parent_id'];
			$treeList[$value['parent_id']]['child_menu'][$value['cate_id']] = $value;
		}else if($value['level'] == 3){
			$treeList[$cate2nd[$value['parent_id']]]['child_menu'][$value['parent_id']]['child_menu'][] = $value;
		}
	}

	return $treeList;
}

function getPage($total_list,$page_num=5)
{	
	//总页数
	$tol_page = ceil(count($total_list)/$page_num)==0?1:ceil(count($total_list)/$page_num);
	// echo "<br>";
	// echo $tol_page;
	// exit();
	$cur_page = intval(input('cur_page'))>0?intval(input('cur_page')):0;
	$page_list =[];
	for($i=1;$i<=$tol_page;$i++){
		$page_list [] = array_slice($total_list, $i*$cur_page,$page_num,true);
	}
	$last_list = array_slice($total_list, ($cur_page-1)*$cur_page,$page_num,true);
	return [
		'tol_page'=>$tol_page,
		'cur_page'=>$cur_page,
		'last_list'=>$last_list,
		'page_list'=>$page_list
	];
	
}

function saveAndgetImgSrc($path,$imgName){
    /*
    Array ( 
        [image-thumb] => Array ( 
            [name] => 01.jpg 
            [type] => image/jpeg 
            [tmp_name] => E:\wamp64\tmp\php4252.tmp 
            [error] => 0 
            [size] => 13996 
        ) 
    )
    */
    $file_type = explode('/',$_FILES[$imgName]['type']);
    $name = time().rand(10000,999999).'.'.$file_type[1];
    $save_path = $path.$name;
    copy($_FILES[$imgName]['tmp_name'], $save_path);
    return $name;
}