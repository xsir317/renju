<?php
/**
 * Created by PhpStorm.
 */

namespace console\modules\queue;
use common\services\QueueService;
use yii\console\Controller;


abstract class Queue extends Controller {

    protected $queueName = "empty_queue_name";

    protected $fifo = true;
    protected $maxTask = 100;

    protected $taskId = "";
    protected abstract function handle($data) ;


    public function actionStart() {
        try {
            $done_count = 0;
            for($i = 1; $i<= $this->maxTask; ++$i) {
                $data = QueueService::pop($this->queueName);
                if($data) {
                    $this->taskId = uniqid();

                    $delay = isset($data['created_time']) ? time() - $data['created_time'] : "unknown";

                    if( $this->handle($data['data'])) {
                        $this->log("handle task success,task delay: {$delay} s");
                    } else {
                        $this->errorLog("handle task fail,task delay: {$delay} s",1);
                    }
                    $done_count ++;
                } else {
                    $this->log("task cleared" , false);
                    break;
                }
            }
            $this->log("done {$done_count} tasks");
            return true;
        } catch ( \Exception $e) {
            $this->errorLog($e->getMessage(), 0);
            return false;
        }
    }

    protected function errorLog($str, $error_level = 1) {
        $level = ($error_level) ? "[warning]" :"[fatal error]";
        $this->log($level . $str);
    }

    protected function log($str, $fileLog = true) {
        global $argv;
        $date = '['.date("Y-m-d H:i:s")."]";
        $str = $date."[{$this->taskId}]".$str;

        if( $fileLog && !empty($argv[2]) ) {
            $logFileName = $argv[2].$this->queueName."-".date("Ymd").".log";
            error_log("$str\n", 3, $logFileName);
        }
        echo "{$date}[{$argv[1]}]$str\n";
    }

}