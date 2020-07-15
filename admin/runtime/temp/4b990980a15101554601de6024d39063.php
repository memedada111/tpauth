<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"C:\myphp_www\PHPTutorial\WWW\tpauth\admin\index\view\goods\product_category.html";i:1594193649;}*/ ?>
﻿<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="/static/admin/lib/html5.js"></script>
<script type="text/javascript" src="/static/admin/lib/respond.min.js"></script>
<script type="text/javascript" src="/static/admin/lib/PIE_IE678.js"></script>
<![endif]-->

<script type="text/javascript" src="http://www.tpauth.com/static/admin/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="http://www.tpauth.com/static/admin/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="http://www.tpauth.com/static/admin/lib/zTree/v3/js/jquery.ztree.all-3.5.min.js"></script> 
<script type="text/javascript" src="http://www.tpauth.com/static/admin/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="http://www.tpauth.com/static/admin/static/h-ui/js/H-ui.admin.js"></script> 

<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui/css/style.css" />
<link rel="stylesheet" href="/static/admin/lib/zTree/v3/css/zTreeStyle/zTreeStyle.css" type="text/css">
<!--[if IE 6]>
<script type="text/javascript" src="/static/admin/http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>产品分类</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 产品管理 <span class="c-gray en">&gt;</span> 产品分类 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div>
	<form action="<?php echo U('Send/index'); ?>" method="post" class="form form-horizontal" id="form-article-add">
        
	    <input type="text" name="msg">
		<input  class="btn btn-primary radius " type="submit" value="发送消息">
			

	</form>
</div>

<span style="color:red;">*点击分类名称，可立即删除</span>

<div>
	<input type="button" name="刷新树" style="margin-left: 100px;width: 41px" id="sx" onclick="sxs()">
</div>

<table class="table">
	<tr>
		<td width="200" class="va-t"><ul id="treeDemo" class="ztree"></ul></td>
		<td class="va-t"><IFRAME ID="testIframe" Name="testIframe" FRAMEBORDER=0 SCROLLING=AUTO width=100%  height=390px SRC="<?php echo U('product_category_add'); ?>"></IFRAME></td>
	</tr>
</table>


<script type="text/javascript">


var zNodes;

	$.ajax({
             //
             url:"<?php echo U('product_category_ajax'); ?>",
             type:'get',
             
             dataType:'json',
             async: false,
             success:function(data){
           			 zNodes=data;
           		// console.log(data);
             }	

            });

var setting = {

	// async:{  
 //            enable:true,  
 //            url:"product_category_ajax",  
 //            autoParam:["id"],  
 //            dataType:"json",  
 //        },  
        view: {  
            selectedMulti: false  
        },
	edit:{
           enable:true,
           editNameSelectAll:true,
           removeTitle :"删",
		   renameTitle :"修",

	},
	check: {

			chkStyle: "checkbox", //复选框类型
			enable: true //每个节点上是否显示 CheckBox
	},
	view: {
		dblClickExpand: false,
		showLine: false,
		selectedMulti: false
	},
	data: {
		simpleData: {
			enable:true,
			idKey: "id",
			pIdKey: "pid",
			rootPId: ""
		}
	},
	callback: {
		//删除分类
		beforeClick: function(treeId, treeNode) {

				
			 if (confirm("确定要删除吗？")) {
							$.ajax({
				             //
				             url:"product_category_del",
				             type:'get',
				             data:{id:treeNode.id},
				             dataType:'json',
				             async: true,
				             success:function(data){
				           		if(data==1){
				           			alert("删除成功");
				           			 parent.location.href="product_category";
				           			 // refresh;
				           		}else{
				           			alert(data);parent.location.href="<?php echo U('product_category'); ?>";
				           		}
				             }
				            })

						}
		},
		onRename: zTreeOnRename,



	}
};


function zTreeOnRename(event, treeId, treeNode, isCancel) {

	// alert(treeNode.id + ", " + treeNode.name);
	$.post('upda', {
		'id':treeNode.id,
		'name':treeNode.name
	}, function(data) {
		
		if (data == "1") {
			alert("修改成功");
			
			//
			
			   // var zTree = $.fn.zTree.getZTreeObj("treeDemo");
      //           zTree.reAsyncChildNodes(null, "refresh");
		// parent.location.href="<?php echo U('product_category'); ?>";
			
		} else {
			alert("修改失败");
		}
	});
}



		
var code;
		
function showCode(str) {
	if (!code) code = $("#code");
	code.empty();
	code.append("<li>"+str+"</li>");
}
		
$(document).ready(function(){
	var t = $("#treeDemo");
	t = $.fn.zTree.init(t, setting, zNodes);
	demoIframe = $("#testIframe");
	demoIframe.bind("load", loadReady);
	// 选中ztre对象
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");

	// 默认选中那个节点
	zTree.selectNode(zTree.getNodeByParam("id",'0'));
});
</script>

     
</body>
</html>