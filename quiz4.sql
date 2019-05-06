create table enrolments (
    student text,
    course  text,
    mark    integer check (mark between 0 and 100),
    grade   char(1) check (grade between 'A' and 'E'),
    primary key (student,course)
);


INSERT INTO enrolments  VALUES ('james', 'COMP1917 12s1', '50', 'D');
INSERT INTO enrolments  VALUES ('peter', 'COMP1917 12s1', '45', 'E');
INSERT INTO enrolments  VALUES ('john', 'COMP1917 12s1', '90', 'A');
INSERT INTO enrolments  VALUES ('peter', 'COMP1917 12s2', '40', 'E');
INSERT INTO enrolments  VALUES ('john', 'COMP1927 12s2', '85', 'A');
INSERT INTO enrolments  VALUES ('james', 'COMP1927 12s2', '55', 'D');
INSERT INTO enrolments  VALUES ('james', 'COMP2911 13s1', '50', 'D');
INSERT INTO enrolments  VALUES ('john', 'COMP2911 13s1', '85', 'A');
INSERT INTO enrolments  VALUES ('john', 'COMP3311 13s2', '70', 'B');