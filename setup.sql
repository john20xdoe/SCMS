
--To the Database Administrator,
--please paste these code into the MySQL Console or phpMyAdmin
--

create database scmsDB default character set latin7 collate latin7_general_cs;
use scmsDB;
create table Subjects(subjectCode varchar(25) not null primary key,descTitle varchar(50) not null, units mediumint(20) unsigned not null, withLab bool not null) engine = InnoDB character set latin7 collate latin7_general_cs;
create table Course(courseID int(10) unsigned not null auto_increment primary key,courseInitials varchar(15) not null, courseName varchar(60) not null, courseDesc varchar(280) null) engine = InnoDB character set latin7 collate latin7_general_cs;
create table UserType(userTypeID int(10) unsigned not null auto_increment primary key, userTypeDesc varchar(40) not null) engine = InnoDB character set latin7 collate latin7_general_cs;
create table Department(deptID int(10) unsigned not null auto_increment primary key, deptInitials varchar(20) not null, deptDesc varchar(50) not null) engine = InnoDB character set latin7 collate latin7_general_cs;

create table Curriculum(currID int(20) unsigned primary key not null auto_increment, currYearEffective year(4) not null, semNoEffective int(1) unsigned not null default 1, course int(10) unsigned not null references Course(courseID) on delete no action on update restrict, isReady bool not null default '0', noOfYears int(2) unsigned not null) engine = InnoDB character set latin7 collate latin7_general_cs;

create table Students(
studentID varchar(20) primary key not null,
lastName varchar(40) not null,
firstName varchar(50) not null,
middleName varchar(40) null,
enrolmentClassif enum('regular','irregular','transferee') not null,
scholarship varchar(60) null,
underCurriculum int(10) unsigned null references Curriculum(currID) on delete no action,
course int(10) unsigned not null,
sectionYearLvl int(1) unsigned null,
gender enum('m','f') null,
civilStatus enum('Single','Married','Divorced','Widowed') null,
religion varchar(60) null,
nationality varchar(25) null,
placeOfBirth varchar(80) null,
dOB date null,
address VARCHAR(70) null,
contactNo varchar(40) null,
highSchool varchar(60) null,
highSchoolGPA decimal(5, 2) null,
parentGuardian varchar(70) null,
emergencyContactNo varchar(40) null,
isStillActive bool not null) engine = InnoDB character set latin7 collate latin7_general_cs;

create table StudentsWithSpouse(studentID varchar(20) not null primary key references Students(studentID) on delete no action, spouseName varchar(70) not null,
spouseReligion varchar(60) null, spouseContactNo varchar(40) null) engine = InnoDB character set latin7 collate latin7_general_cs;
create table Instructors(instructorID int(10) unsigned primary key not null auto_increment, lastName varchar(50) not null, firstName varchar(50) not null, middleName varchar(40) null, employeeID varchar(20) not null,
departmentID int(10) unsigned null references Department(deptID), contractType enum('part-timer','regular') not null ) engine = InnoDB character set latin7 collate latin7_general_cs;
create table PreRequisites(subjectCode varchar(25) not null, preReqCode varchar(140) not null references Subjects(subjectCode), currID int(20) not null references Curriculum(currID), primary key (subjectCode,currID)) engine = InnoDB character set latin7 collate latin7_general_cs;
create table InstructorsPerSubject(instructorID int(20) unsigned not null references Instructors(instructorID), subjectCode varchar(25) not null references Subjects(subjectCode), schoolYear year(4) not null, semNo int(2) unsigned not null) engine = InnoDB character set latin7 collate latin7_general_cs;
create table SubjectByCurr(currID int(10) unsigned not null references Curriculum(currID), subjectCode varchar(25) not null references Subjects(subjectCode), yearLvl int(1) unsigned not null, semNo int(3) unsigned not null, primary key(subjectCode,currID,yearlvl,semno)) engine = InnoDB character set latin7 collate latin7_general_cs;
create table SubjectsTakenByStudent(studentID varchar(20) not null references Students(studentID), subjectCode varchar(25) not null references Subjects(subjectCode),
schoolYear year(4) not null, semNo int(2) unsigned not null, prelimGrade decimal(3, 2) unsigned, finalGrade decimal(3, 2) unsigned, primary key (studentID,subjectCode,schoolYear,semNo)) engine = InnoDB character set latin7 collate latin7_general_cs;
create table ScmsAdmin(userName varchar(20) not null primary key, password varchar(34) not null, firstName varchar(40) not null, middleName varchar(30) null, lastName varchar(40) not null,
userTypeID int(10) unsigned not null references UserType(userTypeID)) engine = InnoDB character set latin7 collate latin7_general_cs;

insert into UserType values ('','system administrator'),('','web administrator'),('','database administrator'),('','office staff');
insert into Department values ('','CCS','College of Computer Studies'), ('','COT','College of Technology'),('','COTED','College of Teacher Education'),('','CAS','College of Arts and Sciences'),('','CEA','College of Engineering and Architecture'),
('','CMED','College of Maritime Education'),('','CBM','College of Business Management'),('','LHS','Laboratory High School');
insert into ScmsAdmin values ('dean','administrator','Administrator','','SCMS','1');