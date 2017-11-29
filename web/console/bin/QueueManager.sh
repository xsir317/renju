#!/bin/bash
#echo "TaskManager monitor v1.0"


SCRIPTS=("notices/game");
BASE_DIR=$(dirname $(dirname $(cd "$(dirname "$0")"; pwd)))"/";

LOG_DIR="$BASE_DIR""console/runtime/logs/queue/";

L=${#SCRIPTS[*]}

C=$#
S=$0

show_help() {

    echo -e "\nusage:\n"
    echo -e "$0 [start|stop] [script1] [script2] [script3] [...]"

    echo -e "\n\nexamples:\n"
    echo -e "\tcd $LOG_DIR\n"
    echo -e "\tnohup sh $0 start &"
    echo -e "\tnohup sh $0 start dm1 &"
    echo -e "\tnohup sh $0 start dm1 dm2 &"

    echo -e "\t$0 stop"
    echo -e "\t$0 stop dm1"
    echo -e "\t$0 stop dm1 dm2"

    echo $1
    exit
}

in_scripts() {

    i=0
    while [ $i -lt $L ]
    do
        if [ "${SCRIPTS[$i]}" = "$1" ]; then
            return 1
        fi

        let i++
    done

    return 0
}

if [ $C -lt 1 ]; then
   show_help
fi

# validate params
if [ $C -gt 1 ]; then

    for ((m=2;m<=$#;m++)); do
        eval "tmp=`echo \\$$m`"
        in_scripts $tmp

        if [ "$?" = "0" ]; then
            echo -e "\ndameon script:$tmp is not defined in SCRIPTS"
            exit
        fi
        #shift
    done
fi

if [ "$1" = "stop" ]; then

    # stop all
    if [ "$C" = "1" ]; then
        rm -f $LOG_DIR*.pid
        echo "stop ok!"
        exit
    else

        for((n=2;n<=$#;n++)); do
            eval "tmp=`echo \\$$n`"
            pid_file=${tmp//\//_}
            rm -f $LOG_DIR$pid_file.pid
            echo "stop $pid_file ok!"
            #shift
        done
    fi
fi

if [ "$1" = "start" ]; then

    #start all
    if [ "$C" = "1" ]; then
        i=0
        while [ $i -lt $L ]
        do
            /bin/bash $S start ${SCRIPTS[$i]} &
            let i++
        done
    fi

    #start one
    if [ "$C" = "2" ]; then
        pid_file=${2//\//_}
        if [ -e "$LOG_DIR$pid_file.pid" ]; then
            echo "$2 is already running"
            exit
        else
            echo "start runing $2..."
            touch "$LOG_DIR$pid_file.pid"
        fi

        #do running!
        while [ -e "$LOG_DIR$pid_file.pid" ]; do
            php "$BASE_DIR""yii" "queue/"$2"/start" "$LOG_DIR""log/"
            sleep 1
        done
    fi
fi
