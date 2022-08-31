<?php

$MAX_OP_TIME = 150;

$MaxTime = time() + $MAX_OP_TIME;
register_tick_function('pig');
function pig()
{
global $MaxTime;

    $time = time();
    //RecordCommand("PIG $time $MaxTime");
    if ($time > $MaxTime)
    {
        RecordCommand("PIG Seen - Exit");
        ReportError("Sorry - this conversion took too long to process. Try with smaller or fewer images perhaps");
        exit();
    } 
}


?>
