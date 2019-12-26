<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/12/9
 * Time: 18:14
 */

namespace console\controllers;


use common\models\Liyi;
use yii\BaseYii;
use yii\console\Controller;

class ToolsController extends Controller
{
    public function actionCombine_js()
    {
        $files = ['page','board','swfobject','web_socket','md5.min','websocket'];
        $folder = dirname(\Yii::$app->getBasePath()).'/frontend/web/js/';
        file_put_contents($folder.'all.js',"/*".date('Y-m-d H:i:s')."*/\n");
        foreach ($files as $_f)
        {
            $file_name = $folder.$_f.'.js';
            $tmp = file_get_contents($file_name);
            file_put_contents($folder.'all.js',"/* {$_f}.js */\n\n {$tmp}\n",FILE_APPEND);
        }
    }

    public function actionLiyi()
    {
        $articles = Liyi::find()->all();
        foreach ($articles as $article)
        {
            $file_to = \Yii::getAlias('@app').'/../frontend/web/tieba_static/'.$article->id.'.html';
            $content = '
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>'. $article->title .'</title>
    <link href="./static.css" type="text/css" media="screen" rel="stylesheet" />
    </head>
<body>
<div class="container">' . "\n"
               .'<h3>' . $article->title .'</h3>'
                .'<div class="content_body">' . $article->format_content .'</div>'
                . "\n" .'</div></body>
</html>';
            file_put_contents($file_to,$content);
        }
    }

    private function format($content,$id)
    {
        $content = preg_replace('/VM\d+:5/','',$content);
        $content = preg_replace('/(\<br\>)+/',"\n",$content);
        $content = preg_replace('/\s*(\r|\n)+\s*/',"\n",$content);
        $content = preg_replace('/\s*(\r|\n)+\s*/',"\n",$content);
        $content = str_replace("\n","\n<br />\n",$content);
        $content = trim($content);
        //提取img
        preg_match_all('/<img\s[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i',$content,$match);
        if(!empty($match))
        {
            foreach ($match[1] as $k=>$_url)
            {
                $saved = $this->save_img($_url,$id);
                $content = str_replace($match[0][$k],"<img src=\"{$saved}\" />",$content);
            }
        }
        return $content;
    }

    private function save_img($url,$id)
    {
        $save_path = \Yii::getAlias('@app').'/../frontend/web/images/save_as/';
        $save_url = '/images/save_as/';
        $save_file = '';
        $parsed = parse_url($url);
        if(substr($parsed['path'],0,18) == '/tb/editor/images/')
        {
            $save_file = str_replace('/','_',substr($parsed['path'],18));
        }
        else
        {
            $ext = substr($parsed['path'],strrpos($parsed['path'],'.')+1);
            $save_file = $id . '_' . md5($url) . '.' . $ext;
        }
        if(!file_exists($save_path.$save_file))
        {
            file_put_contents($save_path.$save_file,file_get_contents($url));
        }

        return $save_url.$save_file;
    }
}