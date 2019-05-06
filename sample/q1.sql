

--q1
create view q1 as select country as team, count(match) as matches from teams join involves on teams.id = involves.team group by country;


