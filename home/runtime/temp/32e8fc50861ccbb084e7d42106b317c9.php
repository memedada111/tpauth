<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"C:\myphp_www\PHPTutorial\WWW\tpauth\home\index\view\index\sow.html";i:1594366310;}*/ ?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>消息通知</title>
    <link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui/css/style.css" />
<link rel="stylesheet" href="/static/admin/lib/zTree/v3/css/zTreeStyle/zTreeStyle.css" type="text/css">
</head>
<body>

<button type="button" class="layui-btn layui-btn-normal layui-btn-radius btn-login-uid">百搭按钮</button>
<strong id="count" style="margin-top: 10px"></strong>
<div id="target" style="background-color: green;margin-top: 20px"></div>

<div id="show" style="margin-top: 15px">
<table class="table table-border table-bordered table-bg table-hover table-sort" style="width:700px">
                <thead>
                    <tr class="text-c">
                        <th width="10%"><input name="" type="checkbox" value=""></th>
                        <th width="10%">ID</th>
                    
                        <th width="50%">消息内容</th>
                        <th width="30%">发送时间</th>
                  
                    </tr>
                </thead>
                <tbody>

                 <?php foreach($data  as $vo): ?>
                    <tr class="text-c va-m">
                        <td><input name="" type="checkbox" value=""></td>
                        <td><?php echo $vo['id']; ?></td>
                        <td><?php echo $vo['mes']; ?></td>
                        <td class="text-l"><?php echo $vo['created_at']; ?></td>
                      
                    </tr>

                 <?php endforeach; ?>
                </tbody>
            </table>
</div>
</body>
</html>
<script src="http://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
<script src='http://cdn.bootcss.com/socket.io/1.3.7/socket.io.js'></script>
<script>
    jQuery(function ($) {
 
        // 连接服务端
        var socket = io('http://www.tpauth.com:2120'); //这里当然填写真实的地址了
        // uid可以是自己网站的用户id，以便针对uid推送以及统计在线人数
        uid = 6;
        // socket连接后以uid登录
        socket.on('connect', function () {
            socket.emit('login', uid);
        });

//显示用户id
            socket.on('connect', function(){
                socket.emit('login', uid);
                $(".btn-login-uid").html("用户ID:"+uid);
                console.log("connect");
            });

        // 后端推送来消息时
        socket.on('new_msg', function (msg) {
            console.log("收到消息：" + msg);
            $('#target').append('<a href="http://www.baidu.com">'+msg+'</a>').append('<br>');
        });
        // 后端推送来在线数据时
        socket.on('update_online_count', function (online_stat) {
            console.log(online_stat);
            $('#count').html(online_stat);
        });
    })
 
</script>
