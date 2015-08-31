<?php

$query = "


DROP TABLE IF EXISTS `Schedule`, `Timesheet`, `Tutor`, `User`;



CREATE TABLE `Schedule` (
  `userID` int(11) NOT NULL,
  `day` varchar(10) NOT NULL,
  `hour` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Timesheet` (
`timesheetID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `dateCompleted` date NOT NULL,
  `filename` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Tutor` (
  `userID` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` int(10) NOT NULL,
  `bio` text NOT NULL,
  `picture` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `Tutor` (`userID`, `email`, `phone`, `bio`, `picture`) VALUES
(1, 'email2', 1112, 'bio2', ''),
(3, 'email 3', 0, '', ''),
(4, 'email 4', 0, '', '');


CREATE TABLE `User` (
`userID` int(11) NOT NULL,
  `PIN` int(4) NOT NULL,
  `name` varchar(30) NOT NULL,
  `role` set('supervisor','tutor') NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO `User` (`userID`, `PIN`, `name`, `role`) VALUES
(1, 1234, 'User 12', 'tutor'),
(2, 1234, 'User 2', 'supervisor'),
(3, 1234, 'User 3', 'tutor'),
(4, 1234, 'User 4', 'tutor');

CREATE TABLE 'Report' (
'userID' int(11) NOT NULL,
'date' date NOT NULL,
'studentName' varchar(30) NOT NULL,
'report' text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `Schedule`
 ADD PRIMARY KEY (`userID`,`day`,`hour`);

ALTER TABLE `Timesheet`
 ADD PRIMARY KEY (`timesheetID`);


ALTER TABLE `Tutor`
 ADD PRIMARY KEY (`userID`);

ALTER TABLE `User`
 ADD PRIMARY KEY (`userID`);

ALTER TABLE `Timesheet`
 MODIFY `timesheetID` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `User`
 MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;


ALTER TABLE 'Report'
 ADD PRIMARY KEY ('userID');
    ";


echo $query;
    
require_once __DIR__.'/oop/manager/Database.php';
$db = new Database();
$db->query($query);

echo 'done';

?>