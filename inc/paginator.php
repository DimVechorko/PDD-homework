<?php
class paginator{

    public static function getPaginator()
    {   $data='data/data.txt';
        $pageSize=isset($_GET['Size']) ? $_GET['Size'] : 10;
        $currenPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $db = file($data);
        $dbSize = count($db);
        $pageNum = ceil($dbSize / $pageSize);
            $html=array('PAGE'=>"<div><ul class=\"pagination\">");
        if($currenPage == 1){
            $html['PAGE'].= "<li class=\"disabled\"><a href=\"#\">&laquo;</a></li>"; //ссылка для перехода на предыдущую страницу
        }else {
            $html['PAGE'].= "<li><a href=\"?page=".($currenPage-1)."&Size=".$pageSize."\">&laquo;</a></li>";
        }
        for ($i = 1; $i <= $pageNum; $i++) {
            if($i != $currenPage)
            {
                $html['PAGE'] .= "<li><a href=\"?page=".$i."&Size=".$pageSize."\">$i</a></li>";//ссылка для перехода на другие страницы
            } else {
                $html['PAGE'] .= "<li class='active'><a href=\"#\">$i</a></li>";//ссылка для перехода на текущую страницу
            }
        }
        if($currenPage == $pageNum){
            $html['PAGE'] .= "<li class=\"disabled\"><a href=\"#\">&raquo;</a></li>"; //ссылка для перехода на следующую страницу
        } else{
            $html['PAGE'] .= "<li><a href=\"?page=".($currenPage+1)."&Size=".$pageSize."\">&raquo;</a></li>";
        }
        $html['PAGE'].='</ul></div>';
        $html['numberOutput'] = "<a href=\"?page=".$currenPage."&Size="."5"."\"><button type=\"button\" class=\"btn btn-default\">5</button></a>";
        $html['numberOutput'] .= "<a href=\"?page=".$currenPage."&Size="."10"."\"><button type=\"button\" class=\"btn btn-default\">10</button></a>";
        $html['numberOutput'] .= "<a href=\"?page=".$currenPage."&Size="."15"."\"><button type=\"button\" class=\"btn btn-default\">15</button></a>";
        return $html;
    }

    public static function getCurrentData($pageSize = 10){
        // Если размер страницы не задан устанавливаем стандартный равный 10
        if($pageSize == null){$pageSize = 10;}
        $currenPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $num = count(file($data));
        $arr['StartIndex'] = ($num - 1) - ($currenPage-1)*$pageSize;
        if(($arr['StartIndex'] - $pageSize + 1) > 0){
            $arr['LastIndex'] = $arr['StartIndex'] - $pageSize + 1;
        } else{$arr['LastIndex'] = 0;}
        //$arr['LastIndex'] = $arr['StartIndex'] - $pageSize;
        return $arr;

    }
}