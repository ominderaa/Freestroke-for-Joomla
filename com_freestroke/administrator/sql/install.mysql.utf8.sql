
CREATE TABLE IF NOT EXISTS `#__freestroke_meets` 
( 
	`id` INTEGER  NOT NULL AUTO_INCREMENT, 
	`name` VARCHAR(250) CHARACTER SET utf8 NOT NULL, 
	`poolname` VARCHAR(160) CHARACTER SET utf8 NOT NULL, 
	`place` VARCHAR(160) CHARACTER SET utf8 NOT NULL, 
	`organiser` VARCHAR(160) CHARACTER SET utf8, 
	`mindate` DATE NOT NULL, 
	`maxdate` DATE , 
	`agedate` DATE , 
	`agecalctype` ENUM('YEAR', 'DATE', 'POR', 'CAN.FNQ', 'LUX'),
	`meettype` SMALLINT , 
	`deadline` DATE , 
	`course` ENUM('LCM', 'SCM', 'SCY', 'SCM16', 'SCM20', 'SCM33', 'SCY20', 'SCY27', 'SCY33', 'SCY36', 'OPEN'),
	`qualifyfrom` DATE, 
	`qualifyto` DATE, 
	`teamlead`  VARCHAR(400) CHARACTER SET utf8, 
	`ordering` INT(11)  NOT NULL ,
	`state` TINYINT(1)  NOT NULL DEFAULT '1',
	`checked_out` INT(11)  NOT NULL ,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(11)  NOT NULL ,
	CONSTRAINT `meets_pk` PRIMARY KEY ( `id` ) 
);


CREATE INDEX `#__fs_meets_deadline_maxdate_idx` ON `#__freestroke_meets` 
( 
     `deadline` ASC , 
     `maxdate` ASC 
);

CREATE INDEX `#__fs_meets_maxdate_idx` ON `#__freestroke_meets` 
( 
     `maxdate` ASC 
);

CREATE INDEX `#__fs_meets_place_idx` ON `#__freestroke_meets` 
( 
     `place` ASC 
);

CREATE TABLE IF NOT EXISTS `#__freestroke_meetsessions` 
( 
	`id` INTEGER  NOT NULL AUTO_INCREMENT, 
	`meets_id` INTEGER  NOT NULL , 
	`sessionnumber` INTEGER , 
	`name` VARCHAR(250) CHARACTER SET utf8,
	`startdate` DATE , 
	`starttime` CHAR(5), 
	`officialmeeting` CHAR(5),
	`teamleadermeeting` CHAR(5),
	`warmupfrom` CHAR(5),
	`warmupuntil` CHAR(5),
	
	`message` VARCHAR(400) CHARACTER SET utf8,
	`judges` VARCHAR(400) CHARACTER SET utf8,
	`transport` VARCHAR(400) CHARACTER SET utf8,
	`carpool` VARCHAR(400) CHARACTER SET utf8,
	`teamlead` VARCHAR(400) CHARACTER SET utf8,
	`cancelling` VARCHAR(400) CHARACTER SET utf8,

	CONSTRAINT `meetsessions_pk` PRIMARY KEY ( id )
);


CREATE INDEX `#__fs_meetsessions_meet_id_numb_idx` ON `#__freestroke_meetsessions` 
( 
     `meets_id` ASC , 
     `sessionnumber` ASC 
);

CREATE TABLE IF NOT EXISTS `#__freestroke_events` 
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT, 
	`meets_id` INTEGER NOT NULL, 
	`daytime` DATETIME not null,
	`sessionnumber` INTEGER NOT NULL, 
	`programnumber` INTEGER NOT NULL, 
	`programorder` INTEGER NOT NULL,
	`eventtype` SMALLINT NOT NULL, 
	`swimstyles_id` INTEGER  NOT NULL , 
	`minage` INTEGER NOT NULL, 
	`maxage` INTEGER NOT NULL,
	`gender` ENUM('U','M', 'F','X') NOT NULL,
	
	`limitmax1` INTEGER,
	`limitmax2` INTEGER,
	`limitmax3` INTEGER,
	`limitmin1` INTEGER,
	`limitmin2` INTEGER,
	`limitmin3` INTEGER,
 
CONSTRAINT `events_pk` PRIMARY KEY ( `id` ) 
);


CREATE INDEX `#__fs_events_meet_id_numb_idx` ON `#__freestroke_events` 
( 
     `meets_id` ASC , 
     `sessionnumber` ASC,
     `programnumber` ASC 
);

CREATE INDEX `#__fs_events_swimstyle_id_idx` ON `#__freestroke_events` 
( 
     `swimstyles_id` ASC 
);

CREATE TABLE IF NOT EXISTS `#__freestroke_members` 
( 
	`id` INTEGER  NOT NULL AUTO_INCREMENT, 
	`lastname` VARCHAR(60) CHARACTER SET utf8  NOT NULL, 
	`firstname` VARCHAR(30) CHARACTER SET utf8  NOT NULL, 
	`nameprefix` VARCHAR(15) CHARACTER SET utf8  NOT NULL, 
	`initials` VARCHAR(10) , 
	`gender` ENUM('U','M', 'F','X') NOT NULL, 
	`birthdate` DATE  NOT NULL, 
	`street` VARCHAR(80) CHARACTER SET utf8 , 
	`zip` VARCHAR(20) CHARACTER SET utf8 , 
	`place` VARCHAR(50) CHARACTER SET utf8, 
	`phonenumber` VARCHAR(20) CHARACTER SET utf8, 
	`mobile` VARCHAR(20) CHARACTER SET utf8, 
	`email` VARCHAR(60) CHARACTER SET utf8, 
	`registrationid` VARCHAR(30) CHARACTER SET utf8, 
	`regvaliddate` DATE, 
	`entrydate` DATE, 
	`exitdate` DATE, 
	`isactive` CHAR(1)  NOT NULL, 
	`isjudge` CHAR(1) NOT NULL , 
	`competence` VARCHAR(20) CHARACTER SET utf8,
	
	`ordering` INT(11)  NOT NULL ,
	`state` TINYINT(1)  NOT NULL DEFAULT '1',
	`checked_out` INT(11)  NOT NULL ,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(11)  NOT NULL ,
	
	CONSTRAINT `members_pk` PRIMARY KEY ( id ) 
);


CREATE INDEX `#__fs_members_lastname_firstname_idx` ON `#__freestroke_members` 
( 
     lastname ASC , 
     firstname ASC 
);


CREATE TABLE IF NOT EXISTS  `#__freestroke_entries`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT, 
	`members_id` INTEGER  NOT NULL, 
	`meets_id` INTEGER  NOT NULL,
	`swimstyles_id` INTEGER NOT NULL,
	`sessionnumber` INTEGER NOT NULL,
	`eventnumber` INTEGER  NOT NULL,
	`entrytime` INTEGER  NOT NULL,
	`entrytimedate` DATE,
	CONSTRAINT `entries_pk` PRIMARY KEY (id) 
);


CREATE TABLE IF NOT EXISTS  `#__freestroke_relayentries`
(
    id INTEGER NOT NULL AUTO_INCREMENT, 
    teamnumber SMALLINT NOT NULL DEFAULT 1,
	meets_id INTEGER  NOT NULL,
	swimstyles_id INTEGER  NOT NULL,
	sessionnumber INTEGER  NOT NULL,
	eventnumber INTEGER  NOT NULL,
	entrytime INTEGER  NOT NULL,
    CONSTRAINT entries_pk PRIMARY KEY (id) 
);

CREATE TABLE IF NOT EXISTS `#__freestroke_relayposentries` 
(
    id INTEGER  NOT NULL AUTO_INCREMENT, 
    relayentries_id INTEGER  NOT NULL ,
    members_id INTEGER  NOT NULL , 
    ordernumber SMALLINT  NOT NULL , 
	entrytime INTEGER  NOT NULL,
    CONSTRAINT relayposentries_pk PRIMARY KEY (id) 
);

CREATE TABLE IF NOT EXISTS `#__freestroke_results` 
( 
     `id` INTEGER  NOT NULL AUTO_INCREMENT, 
     `members_id` INTEGER  NOT NULL , 
     `meets_id` INTEGER  NOT NULL , 
     `eventdate` DATE , 
     `swimstyles_id` INTEGER  NOT NULL , 
	 
     `totaltime` INTEGER , 
     `points` INTEGER,
     `eventnumber` INTEGER , 
     `entrytime` INTEGER , 
     `rank` INTEGER , 
     `eventtype` SMALLINT , 
     `resulttype` ENUM('EXH','DSQ','DNS','DNF','WDR','SICK','FIN'),
     `comment` VARCHAR(1024) CHARACTER SET utf8,
     `course` ENUM('LCM', 'SCM', 'SCY', 'SCM16', 'SCM20', 'SCM33', 'SCY20', 'SCY27', 'SCY33', 'SCY36', 'OPEN'),  		
     CONSTRAINT `results_pk` PRIMARY KEY ( `id` )
);

CREATE INDEX `#__fs_results_meet_id_idx` ON `#__freestroke_results`(meets_id ASC);
CREATE INDEX `#__fs_results_member_id_idx` ON `#__freestroke_results`(members_id ASC);
CREATE INDEX `#__fs_results_swimstyle_id_idx` ON `#__freestroke_results`(swimstyles_id ASC);
CREATE INDEX `#__fs_results_totaltime_idx` ON `#__freestroke_results`(totaltime ASC);

CREATE TABLE IF NOT EXISTS `#__freestroke_splits` 
( 
    id INTEGER  NOT NULL AUTO_INCREMENT, 
    results_id INTEGER  NOT NULL , 
    distance INTEGER NOT NULL , 
    splittime INTEGER,
    CONSTRAINT splits_pk PRIMARY KEY ( id ) 
);

CREATE UNIQUE INDEX `#__fs_splits_result_distance` ON `#__freestroke_splits`(results_id,distance);

CREATE TABLE IF NOT EXISTS `#__freestroke_relayresults` 
( 
	`id` INTEGER  NOT NULL AUTO_INCREMENT, 
	`meets_id` INTEGER  NOT NULL , 
	`eventdate` DATE , 
	`teamnumber` SMALLINT , 
	`swimstyles_id` INTEGER  NOT NULL , 
	
	`totaltime` INTEGER , 
	`eventnumber` INTEGER , 
	`entrytime` INTEGER , 
	`rank` INTEGER , 
	`eventtype` SMALLINT , 
	`resulttype` ENUM('EXH','DSQ','DNS','DNF','WDR','SICK','FIN'), 
	`comment` VARCHAR(1024) CHARACTER SET utf8,

	CONSTRAINT `relayresults_pk` PRIMARY KEY ( id ) 
);

CREATE INDEX `#__fs_relays_meet_id_idx` ON `#__freestroke_relayresults`(meets_id ASC);
CREATE INDEX `#__fs_relays_swimstyle_id_idx` ON `#__freestroke_relayresults`(swimstyles_id ASC);

CREATE TABLE IF NOT EXISTS `#__freestroke_relaysplits`
(
    id INTEGER  NOT NULL AUTO_INCREMENT, 
    ordernumber SMALLINT  NOT NULL , 
    relayresults_id INTEGER  NOT NULL, 
    members_id INTEGER  NOT NULL , 
    distance INTEGER  NOT NULL , 
    splittime INTEGER,
    CONSTRAINT relaysplits_pk PRIMARY KEY ( id ) 
);

CREATE UNIQUE INDEX `#__fs_relaysplits_ordernumber` ON `#__freestroke_relaysplits`(ordernumber ASC);
CREATE UNIQUE INDEX `#__fs_relaysplits_result` ON `#__freestroke_relaysplits`(relayresults_id ASC);

CREATE TABLE IF NOT EXISTS `#__freestroke_relayposresults` 
(
    id INTEGER  NOT NULL AUTO_INCREMENT, 
    relayresults_id INTEGER  NOT NULL ,
    members_id INTEGER  NOT NULL , 
    ordernumber SMALLINT NOT NULL , 
	reactiontime INTEGER NOT NULL,
    resulttype ENUM('EXH','DSQ','DNS','DNF','WDR','SICK','FIN'), 
    CONSTRAINT relayposresults_pk PRIMARY KEY (id) 
);


CREATE TABLE IF NOT EXISTS `#__freestroke_swimstyles` 
( 
     id INTEGER  NOT NULL AUTO_INCREMENT, 
     code VARCHAR (10) , 
     distance INTEGER , 
     name VARCHAR (50) , 
     relaycount INTEGER , 
     strokecode ENUM('FREE','BACK','BREAST','FLY','MEDLEY','APNEA','SURFACE','IMMERSION','UNKNOWN'), 
     ordering INTEGER, 
     CONSTRAINT PRIMARY KEY (id) 
);

CREATE INDEX `#__fs_swimstyles_idx1` ON `#__freestroke_swimstyles` 
( 
     distance ASC,
     relaycount asc,
     strokecode asc
);

insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (1,'',50,'vrije slag',1,'FREE',1);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (2,'',100,'vrije slag',1,'FREE',2);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (3,'',200,'vrije slag',1,'FREE',3);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (4,'',300,'vrije slag',1,'FREE',4);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (5,'',400,'vrije slag',1,'FREE',5);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (6,'',800,'vrije slag',1,'FREE',6);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (7,'',1000,'vrije slag',1,'FREE',7);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (8,'',1500,'vrije slag',1,'FREE',8);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (9,'',50,'rugslag',1,'BACK',9);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (10,'',100,'rugslag',1,'BACK',10);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (11,'',200,'rugslag',1,'BACK',11);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (12,'',50,'schoolslag',1,'BREAST',12);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (13,'',100,'schoolslag',1,'BREAST',13);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (14,'',200,'schoolslag',1,'BREAST',14);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (15,'',50,'vlinderslag',1,'FLY',15);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (16,'',100,'vlinderslag',1,'FLY',16);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (17,'',200,'vlinderslag',1,'FLY',17);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (18,'',200,'wisselslag',1,'MEDLEY',18);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (19,'',400,'wisselslag',1,'MEDLEY',19);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (20,'',100,'wisselslag',1,'MEDLEY',20);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (21,'',50,'vrije slag',4,'FREE',21);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (22,'',50,'vrije slag',5,'FREE',22);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (23,'',50,'vrije slag',10,'FREE',23);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (24,'',100,'vrije slag',4,'FREE',24);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (25,'',100,'vrije slag',10,'FREE',25);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (26,'',200,'vrije slag',4,'FREE',26);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (27,'',50,'rugslag',4,'BACK',27);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (28,'',100,'rugslag',4,'BACK',28);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (29,'',200,'rugslag',4,'BACK',29);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (30,'',50,'schoolslag',4,'BREAST',30);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (31,'',100,'schoolslag',4,'BREAST',31);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (32,'',200,'schoolslag',4,'BREAST',32);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (33,'',50,'vlinderslag',4,'FLY',33);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (34,'',100,'vlinderslag',4,'FLY',34);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (35,'',200,'vlinderslag',4,'FLY',35);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (36,'',50,'wisselslag',4,'MEDLEY',36);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (37,'',100,'wisselslag',4,'MEDLEY',37);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (38,'',100,'vrije slag',4,'FREE',38);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (39,'',200,'vrije slag',4,'FREE',39);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (40,'',100,'wisselslag',4,'MEDLEY',40);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (41,'',25,'vrije slag',1,'FREE',41);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (42,'',25,'rugslag',1,'BACK',42);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (43,'',25,'schoolslag',1,'BREAST',43);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (44,'',25,'vlinderslag',1,'FLY',44);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (45,'',25,'vrije slag',8,'FREE',45);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (46,'',25,'wisselslag',8,'MEDLEY',46);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (47,'',50,'vrije slag',8,'FREE',47);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (48,'',50,'wisselslag',8,'MEDLEY',48);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (49,'',500,'vrije slag',1,'FREE',49);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (50,'',1650,'vrije slag',1,'FREE',50);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (51,'',2000,'schoolslag',1,'BREAST',51);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (52,'',25,'vrije slag',10,'FREE',52);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (53,'',25,'vrije slag',4,'FREE',53);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (54,'',25,'vrije slag',4,'FREE',54);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (55,'',25,'vlinderslag',4,'FLY',55);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (56,'',25,'rugslag',4,'BACK',56);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (57,'',25,'schoolslag',4,'BREAST',57);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (58,'',25,'wisselslag',4,'MEDLEY',58);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (59,'',50,'rugslag',8,'BACK',59);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (60,'',2000,'vrije slag',1,'FREE',60);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (61,'',1000,'schoolslag',1,'BREAST',61);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (62,'',25,'anders',1,'UNKNOWN',62);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (63,'',25,'anders',1,'UNKNOWN',63);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (64,'',25,'25m Achteruit',1,'UNKNOWN',64);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (65,'',25,'vrije slag',5,'FREE',65);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (66,'',25,'schoolslag',5,'BREAST',66);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (68,'',50,'schoolslag',8,'BREAST',68);
insert into `#__freestroke_swimstyles` (id,code,distance,name,relaycount,strokecode,ordering) values (71,'',50,'vlinderslag',8,'FLY',71);


CREATE TABLE IF NOT EXISTS `#__freestroke_venues` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(160) CHARACTER SET utf8 NOT NULL ,
	`address` VARCHAR(160) CHARACTER SET utf8 NOT NULL ,
	`place` VARCHAR(160) CHARACTER SET utf8 NOT NULL ,
    `course` ENUM('LCM', 'SCM', 'SCY', 'SCM16', 'SCM20', 'SCM33', 'SCY20', 'SCY27', 'SCY33', 'SCY36', 'OPEN') NOT NULL,  		
	`website` VARCHAR(256)  CHARACTER SET utf8 NOT NULL ,
	
	`ordering` INT(11)  NOT NULL ,
	`state` TINYINT(1)  NOT NULL ,
	`checked_out` INT(11)  NOT NULL ,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(11)  NOT NULL ,
	PRIMARY KEY (`id`)
);


ALTER TABLE `#__freestroke_events` 
    ADD CONSTRAINT `#__freestroke_events_meets_fk` FOREIGN KEY(meets_id) 
	REFERENCES `#__freestroke_meets` (id) ON DELETE CASCADE;

ALTER TABLE `#__freestroke_events` 
    ADD CONSTRAINT `#__freestroke_events_swimstyles_fk` FOREIGN KEY(swimstyles_id) 
	REFERENCES `#__freestroke_swimstyles` (id) ON DELETE RESTRICT;

ALTER TABLE `#__freestroke_meetsessions` 
    ADD CONSTRAINT `#__freestroke_meetsessions_meets_fk` FOREIGN KEY(meets_id) 
    REFERENCES `#__freestroke_meets` (id) ON DELETE CASCADE;

ALTER TABLE `#__freestroke_results` 
    ADD CONSTRAINT `#__freestroke_results_meets_fk` FOREIGN KEY(meets_id) 
	REFERENCES `#__freestroke_meets` (id) ON DELETE CASCADE;

ALTER TABLE `#__freestroke_results` 
    ADD CONSTRAINT `#__freestroke_results_members_fk` FOREIGN KEY(members_id) 
	REFERENCES `#__freestroke_members` (id) ON DELETE RESTRICT;

ALTER TABLE `#__freestroke_results` 
    ADD CONSTRAINT `#__freestroke_results_swimstyles_fk` FOREIGN KEY(swimstyles_id) 
    REFERENCES `#__freestroke_swimstyles`(id) ON DELETE RESTRICT;

ALTER TABLE `#__freestroke_relayresults` 
    ADD CONSTRAINT `#__freestroke_relayresults_meets_fk` FOREIGN KEY(meets_id) 
	REFERENCES `#__freestroke_meets` (id) ON DELETE CASCADE;

ALTER TABLE `#__freestroke_relayresults` 
    ADD CONSTRAINT `#__freestroke_relayresults_swimstyles_fk` FOREIGN KEY(swimstyles_id) 
    REFERENCES `#__freestroke_swimstyles`(id) ON DELETE RESTRICT;

ALTER TABLE `#__freestroke_splits` 
    ADD CONSTRAINT `#__freestroke_splits_results_fk` FOREIGN KEY(results_id) 
    REFERENCES `#__freestroke_results` (id) ON DELETE CASCADE;

ALTER TABLE `#__freestroke_relaysplits` 
    ADD CONSTRAINT `#__freestroke_relaysplits_results_fk` FOREIGN KEY(relayresults_id) 
    REFERENCES `#__freestroke_relayresults` (id) ON DELETE CASCADE;

ALTER TABLE  `#__freestroke_entries`
	ADD CONSTRAINT `#__freestroke_entries_members_fk` FOREIGN KEY(members_id)
	REFERENCES `#__freestroke_members`(id) ON DELETE RESTRICT;
	
ALTER TABLE  `#__freestroke_entries`
	ADD CONSTRAINT `#__freestroke_entries_meets_fk` FOREIGN KEY (meets_id)
	REFERENCES `#__freestroke_meets` (id) ON DELETE CASCADE;

ALTER TABLE  `#__freestroke_entries`
	ADD CONSTRAINT `#__freestroke_entries_swimstyles_fk` FOREIGN KEY (swimstyles_id)
	REFERENCES `#__freestroke_swimstyles` (id) ON DELETE RESTRICT; 

ALTER TABLE  `#__freestroke_relayentries`
ADD CONSTRAINT `#__freestroke_relayentries_meets_fk` FOREIGN KEY (meets_id)
	REFERENCES `#__freestroke_meets` (id) ON DELETE CASCADE; 

ALTER TABLE  `#__freestroke_relayentries`
ADD CONSTRAINT `#__freestroke_relayentries_swimstyles_fk` FOREIGN KEY (swimstyles_id)
	REFERENCES `#__freestroke_swimstyles` (id) ON DELETE RESTRICT; 

ALTER TABLE  `#__freestroke_relayposentries`
ADD CONSTRAINT `#__freestroke_relayposentries_relay_fk` FOREIGN KEY (relayentries_id)
	REFERENCES `#__freestroke_relayentries` (id) ON DELETE CASCADE; 

ALTER TABLE  `#__freestroke_relayposentries`
ADD CONSTRAINT `#__freestroke_relayposentries_member_fk` FOREIGN KEY (members_id)
	REFERENCES `#__freestroke_members` (id) ON DELETE RESTRICT; 

ALTER TABLE  `#__freestroke_relayposresults`
ADD CONSTRAINT `#__freestroke_relayposresults_relay_fk` FOREIGN KEY (relayresults_id)
	REFERENCES `#__freestroke_relayresults` (id) ON DELETE CASCADE; 

ALTER TABLE  `#__freestroke_relayposresults`
ADD CONSTRAINT `#__freestroke_relayposresults_member_fk` FOREIGN KEY (members_id)
	REFERENCES `#__freestroke_members` (id) ON DELETE RESTRICT; 

