create database rd5;

use rd5;

CREATE TABLE `user` (
  `id` int NOT NULL auto_increment primary key,
  `username` varchar(20) NOT NULL,
  `password` varchar(30) NOT NULL,
  `tid` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `cash` int 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `detail`(
  `did` int NOT NULL auto_increment primary key,
  `uid` int not null,
  `muser` varchar(30) not null,
  `cash` int,
  `decash` int default 0,
  `dcash` int default 0,
  `date` varchar(20),
  FOREIGN KEY (`uid`) REFERENCES `user`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


