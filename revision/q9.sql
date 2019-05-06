create trigger AddCourseEnrolmentTrigger
after insert on CourseEnrolments
execute procedure fixCoursesOnAddCourseEnrolment();


create function fixCoursesOnAddCourseEnrolment() returns trigger AS
$$
declare _nS integer; _nE integer; _avgEval integer; _sum integer;

BEGIN
    select nS, nE, avgEval into  _nS, _nE ,_avgEval from Course where Course.id = new.course ;
    
    _nS = _nS +1;

    if (new.stueval is not null) THEN
        if(_avgEval is not null) then
            _sum = _nE * _avgEval;
            _nE = _nE +1;
            _avgEval = _sum/_nE;
        else
            select sum(stueval),count(stueval) into _sum, _nE from CourseEnrolments where CourseEnrolments.course = new.course group by course;
        end if;



    end if;


    update Courses
    set ns = _nS, ne= _nE, avgEval = _avgEval
    where Courses.id = new.course

    return new;

end;
$$
language plpgsql;




create trigger DropCourseEnrolmentTrigger
after delete on CourseEnrolments
execute procedure fixCoursesOnDropCourseEnrolment();


create function fixCoursesOnDropCourseEnrolment() returns trigger as $$
declare
_nS integer; _nE integer; _avgEval integer;_sum integer;


begin

select nS, nE, avgEval into _nS, _nE, _avgEval from Courses  where old.course = Courses.id;

if(_nS > 0) THEN
    _nS = nS-1;
    if(old.stueval != null) THEN
    
        _sum = _avgEval * nE;
        _nE = _nE -1;
        _avgEval = _sum - old.studeval/ nE


    end if;
end if;

update Courses
set nS = _nS, nE=_nE, avgEval = _avgEval
WHERE Courses.id = old.course

return old;





end;
$$
language plpgsql;