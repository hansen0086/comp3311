create view a2 as select * from(select scoredBy, count(rating) as goals from Goals where rating = 'amazing' group by scoredBy) where goals >1;

create view q2 as select name as players , goals from players join a2 on players.id = a2.scoredby;
