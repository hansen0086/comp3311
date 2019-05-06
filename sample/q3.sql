--number of player who scored a goal 392
drop view if EXISTS a3;
drop view if exists b3;
drop view if exists c3;
drop view if exists q3;
create view a3 as select distinct scoredby from goals order by scoredBy;

--players never socred a goals
create view b3 as  select id from players left join a3 on players.id = a3.scoredBy where a3.scoredBy is null;
--select count( *) from(select * from players left join  a3 on players.name = a3.name where a3.name is null);

create view c3 as select  memberof , count(memberof) as team from b3 join players on players.id = b3.id group by memberof;

create view q3 as select country as teams, team as players from (select country, team  from teams join c3 on teams.id = c3.memberOf )  where team =( select max(team)from c3)