<?php

require_once __DIR__.'/../oop/manager/ScheduleManager.php';
        
$scheduleManager = new ScheduleManager();
$schedule = $scheduleManager->getAll();

echo json_encode($schedule->getSchedule());

?>