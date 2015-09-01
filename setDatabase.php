<?php

$query = "

DROP TABLE IF EXISTS `Schedule`, `Timesheet`, `Tutor`, `User`, 'Report';

CREATE TABLE `Schedule` (
  `userID` int(11) NOT NULL,
  `day` varchar(10) NOT NULL,
  `hour` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `Schedule`
 ADD PRIMARY KEY (`userID`,`day`,`hour`);


CREATE TABLE `Timesheet` (
`timesheetID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `dateCompleted` date NOT NULL,
  `filename` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `Timesheet`
 ADD PRIMARY KEY (`timesheetID`);

 ALTER TABLE `Timesheet`
 MODIFY `timesheetID` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `Tutor` (
  `userID` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `bio` text NOT NULL,
  `picture` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `Tutor`
 ADD PRIMARY KEY (`userID`);


CREATE TABLE `User` (
`userID` int(11) NOT NULL,
  `PIN` varchar(40) NOT NULL,
  `name` varchar(30) NOT NULL,
  `role` set('supervisor','tutor') NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

ALTER TABLE `User`
 ADD PRIMARY KEY (`userID`);

ALTER TABLE `User`
 MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;

INSERT INTO `User` (`userID`, `PIN`, `name`, `role`) VALUES
(0, '9ba7a7240f4e591d55f173e83faa73d7a92aeeec', 'Admin', 'supervisor');


CREATE TABLE 'Report' (
'userID' int(11) NOT NULL,
'date' date NOT NULL,
'studentName' varchar(30) NOT NULL,
'report' text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE 'Report'
 ADD PRIMARY KEY ('userID');
 
    ";


echo $query;
    
require_once __DIR__.'/oop/manager/Database.php';
$db = new Database();
$db->query($query);

echo 'done';
?>