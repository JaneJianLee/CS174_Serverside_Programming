CREATE DATABASE hw6;
USE hw6;
GRANT ALL PRIVILEGES ON hw6.* TO 'user1'@'localhost' IDENTIFIED BY 'm5Lc%6+smj8yUJQV';

#table stores mapping information between advisor and student
CREATE TABLE advstu(
    AdvisorName VARCHAR(100) NOT NULL,
    StudentName VARCHAR(100) NOT NULL,
    StudentID INT(9) NOT NULL, 
    ClassCode CHAR(10) NOT NULL
);

);