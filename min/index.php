<?php
//目录数据
$link = \MyObjSummary\FileCache::indexJson();
$title = '目录浏览' ;
?>
<html >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="<?php echo APP_STATIC ;?>common/img/favicon.png" type="image/png">
    <title>PHP</title>
    <!--图标样式-->
    <link rel="stylesheet" type="text/css" href="<?php echo APP_STATIC ;?>common/css/bootstrap.min.css"/>
    <!--主要样式-->
    <link rel="stylesheet" type="text/css" href="<?php echo APP_STATIC ;?>tree/css/style.css"/>
    <script type="text/javascript" src="<?php echo APP_STATIC ;?>common/js/jquery-1.7.2.min.js"></script>
    <style>
        .back-top {
            position: fixed;
            right: 8px;
            bottom: 51px;
            z-index: 999;
            width: 35px;
            font-size: 21px;
        }
    </style>
</head>
<body>
<!--目录-->
<div class="tree well">
    <ul id="rootUL">
        <li style="font-size: 21px;color: black;font-weight: bold"> <?php echo $title;?> </li>
        <li> <input type="button" class="btn btn-default" value="Collapse"> </li>
    </ul>
</div>
<!--返回top-->
<div class="back-top"><a href="#" style="text-decoration:none" title="Top">Top</a></div>
</body>
<script type="text/javascript">
    var changeButton = false ;
    $(function () {
        //数据
        var list = eval('<?= $link; ?>');
        /*var list =
            [{
                "name": "Index",
                "code":"INDEX",
                "icon": "icon-th",
                "child": [
                    {
                        "name": "php",
                        "icon": "icon-minus-sign",
                        "code":"INDEX-PHP",
                        "parentCode": "INDEX",
                        "child": [
                            {
                                "name": "designMode",
                                "code":"designMode",
                                "icon": "icon-minus-sign",
                                "parentCode": "INDEX-PHP",
                                "child": [
                                    {
                                        "name": "element",
                                        "code":"element",
                                        "icon": "",
                                        "parentCode": "designMode",
                                        "child": [

                                        ]
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "name": "designMode2",
                        "code":"designMode2",
                        "icon": "icon-minus-sign",
                        "parentCode": "INDEX",
                        "child": [
                            {
                                "name": "element2",
                                "code":"element2",
                                "icon": "",
                                "parentCode": "designMode2",
                                "child": [

                                ]
                            }
                        ]
                    }
                ]
            }];*/
        function tree(data) {
            for (var i = 0; i < data.length; i++) {
                var data2 = data[i];
                if (data[i].icon == "icon-th") {
                    $("#rootUL").append("<li data-name='" + data[i].code + "'><span><i class='" + data[i].icon + "'></i> " + data[i].name + "</span></li>");
                } else {
                    var children = $("li[data-name='" + data[i].parentCode + "']").children("ul");
                    if (children.length == 0) {
                        $("li[data-name='" + data[i].parentCode + "']").append("<ul></ul>")
                    }
                    //父类末端跳转标签
                    var hrefUrl = '' ;
                    if(data[i].href) {
                        hrefUrl = '<a target="_blank" href="'+data[i].href+'">'+data[i].name +'</a>' ;
                    }else {
                        hrefUrl = data[i].name ;
                    }
                    $("li[data-name='" + data[i].parentCode + "'] > ul").append(
                        "<li data-name='" + data[i].code + "'>" +
                        "<span>" +
                        "<i class='" + data[i].icon + "'></i> " +
                        hrefUrl +
                        "</span>" +
                        "</li>")
                }
                for (var j = 0; j < data[i].child.length; j++) {
                    var child = data[i].child[j];
                    var children = $("li[data-name='" + child.parentCode + "']").children("ul");
                    if (children.length == 0) {
                        $("li[data-name='" + child.parentCode + "']").append("<ul></ul>")
                    }
                    //子类末端跳转标签
                    var childUrl = '' ;
                    if(child.href) {
                        childUrl = '<a target="_blank" href="'+child.href+'">'+child.name +'</a>' ;
                    }else {
                        childUrl = child.name ;
                    }
                    $("li[data-name='" + child.parentCode + "'] > ul").append(
                        "<li data-name='" + child.code + "'>" +
                        "<span>" +
                        "<i class='" + child.icon + "'></i> " +
                        childUrl +
                        "</span>" +
                        "</li>");
                    var child2 = data[i].child[j].child;
                    tree(child2)
                }
                tree(data[i]);
            }

        }
        //渲染
        tree(list) ;

        //样式
        $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', '关闭');
        $('.tree li.parent_li > span').on('click', function (e) {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if (children.is(":visible")) {
                children.hide('fast');
                $(this).attr('title', '展开').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
            } else {
                children.show('fast');
                $(this).attr('title', '关闭').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
            }
            e.stopPropagation();
        });

        //折叠样式
        $('#rootUL li > input').on('click',function (e) {
            if(changeButton) {
                $('.tree li.parent_li > span > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
                $('.tree li.parent_li > ul > li').show();
                changeButton = false ;
            }else {
                $('.tree li.parent_li > span > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
                $('.tree li.parent_li > ul > li').hide();
                changeButton = true ;
            }
        });
    });
</script>
</html>