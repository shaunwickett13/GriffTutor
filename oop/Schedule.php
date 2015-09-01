<?php

class Schedule {

    const MONDAY = 'monday', TUESDAY = 'tuesday', WEDNESDAY = 'wednesday', THURSDAY = 'thursday', FRIDAY = 'friday';
    public static $DAYS = array(self::MONDAY, self::TUESDAY, self::WEDNESDAY, self::THURSDAY, self::FRIDAY);

    const AM_8_00 = 800, AM_8_30 = 830,
        AM_9_00 = 900, AM_9_30 = 930,
        AM_10_00 = 1000, AM_10_30 = 1030,
        AM_11_00 = 1100, AM_11_30 = 1130,
        PM_12_00 = 1200, PM_12_30 = 1230,
        PM_1_00 = 1300, PM_1_30 = 1330,
        PM_2_00 = 1400, PM_2_30 = 1430,
        PM_3_00 = 1500, PM_3_30 = 1530,
        PM_4_00 = 1600, PM_4_30 = 1630,
        PM_5_00 = 1700, PM_5_30 = 1730;

    public static $HOURS = array(
                            self::AM_8_00, self::AM_8_30,
                            self::AM_9_00, self::AM_9_30,
                            self::AM_10_00, self::AM_10_30,
                            self::AM_11_00, self::AM_11_30,
                            self::PM_12_00, self::PM_12_30,
                            self::PM_1_00, self::PM_1_30,
                            self::PM_2_00, self::PM_2_30,
                            self::PM_3_00, self::PM_3_30,
                            self::PM_4_00, self::PM_4_30,
                            self::PM_5_00, self::PM_5_30);

    // All arrays of times
    private $schedule = array(
                            self::MONDAY => array(),
                            self::TUESDAY => array(),
                            self::WEDNESDAY => array(),
                            self::THURSDAY => array(),
                            self::FRIDAY => array());

    public function __construct($schedule = array()) {
        foreach ($schedule as $day=>$hours) {
            if (in_array($day,self::$DAYS) && is_array($hours)) {
                foreach ($hours as $h)
                    $this->addHour($day,$h);
            }
        }
    }

    public function getSchedule() {
        return $this->schedule;
    }

    public function addHour($day, $hour) {
        if (in_array($day,self::$DAYS)  &&  in_array($hour,self::$HOURS)  &&  !in_array($hour,$this->schedule[$day])) {
            $this->schedule[$day][] = $hour;
        }
    }

    public function removeHour($day, $hour) {
        if (in_array($day,self::$DAYS)  &&  in_array($hour,$this->schedule[$day])) {
            $index = array_search($hour,$this->schedule[$day]);
            unset($this->schedule[$day][$index]);
        }
    }

    public function hourExists($day, $hour) {
        return isset($this->schedule[$day]) && in_array($hour,$this->schedule[$day]);
    }

}