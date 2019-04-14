--Q1
--List all the company names (and countries) that are incorporated outside Australia.
	create or replace view Q1(Name, Country) as 
	select name, country from company where country not similar to 'Australia';


--Q2
--List all the company codes that have more than five executive members on record (i.e., at least six).
    create or replace view Q2(Code) as 
	select code,count(code) from executive group by code having count(code)>5;
--Q3
--List all the company names that are in the sector of "Technology"
    create or replace view Q3(Name) as 
	select name from company join category on company.code = category.code where sector like 'Technology';


--Q4
--Find the number of Industries in each Sector
    
    create or replace view Q4(Sector, Number) as
	select sector, count(distinct(industry)) from category group by sector order by sector;
--Q5
--Find all the executives (i.e., their names) that are affiliated with companies in the sector of "Technology". If an executive is affiliated with more than one company, he/she is counted if one of these companies is in the sector of "Technology".
    create or replace view Q5(Name) as 
    select person from executive join category on(executive.code = category.code) where sector like 'Technology' order by person;
--Q6
--List all the company names in the sector of "Services" that are located in Australia with the first digit of their zip code being 2.
    create or replace view Q6(Name) as 
    select name from company join category on (company.code = category.code) where sector like'Services' and country like 'Australia' and zip like '2%';
--**
--Q7
--Create a database view of the ASX table that contains previous Price, Price change (in amount, can be negative) and Price gain (in percentage, can be negative). (Note that the first trading day should be excluded in your result.) For example, if the PrevPrice is 1.00, Price is 0.85; then Change is -0.15 and Gain is -15.00 (in percentage but you do not need to print out the percentage sign).
    --create or replace view Q7("Date", Code, Volume, PrevPrice, Price, Change, Gain) as ...
    --select curr."Date", curr.code,  prev.price as prevprice,curr.price, curr.price-prev.price as change ,(curr.price-prev.price)*100/prev.price as gain from asx curr join asx prev on prev.code = curr.code and prev."Date" = curr."Date" -1 order by code , "Date";


--Q7 correct  way
    create or replace view shifted ("Date",Code, Volume, price, newDate) AS
    select distinct a1.*, max(a2."Date") over (partition by a1."Date") 
    from asx as a1 join asx as a2 on a1.code = a2.code and a1."Date" > a2."Date"  order by code, "Date";

    create or replace view Q7("Date", Code, Volume, PrevPrice, Price, Change, Gain) as
    select shifted."Date", shifted.code, shifted.volume, asx.price as preprice, shifted.price, (shifted.price - asx.price) as change,100* (shifted.price-asx.price)/asx.price as gain from shifted join asx on asx."Date"= shifted.newdate and  asx.code = shifted.code;




--Q8
--Find the most active trading stock (the one with the maximum trading volume; if more than one, output all of them) on every trading day. Order your output by "Date" and then by Code.
    create or replace view Q8("Date", Code, Volume) as
    select "Date",code, volume from (select "Date" as "newDate",   max(volume) as newvolume from asx group by asx."Date" order by "Date") a join asx on asx."Date" = a."newDate" and asx.volume = a.newvolume;
--Q9
--Find the number of companies per Industry. Order your result by Sector and then by Industry.
    create or replace view Q9(Sector, Industry, Number) as 
    select distinct sector, industry, count(code) over(partition by industry) from category order by sector, industry;


--Q10
--List all the companies (by their Code) that are the only ocreate or replace view temp8("Date, Volume") as

    create or replace view Q10(Code, Industry) as 
    select code, industry from category where industry in(select industry from category group by industry having count(industry)=1);






--Q11
--List all sectors ranked by their average ratings in descending order. AvgRating is calculated by finding the average AvgCompanyRating for each sector (where AvgCompanyRating is the average rating of a company).
    create or replace view Q11(Sector, AvgRating) as
    select sector, avg(star) as avgrating from rating join category on rating.code = category.code group by sector order by avgrating desc;



--Q12
--Output the person names of the executives that are affiliated with more than one company.
    create or replace view Q12(Name) as 
    select person from executive group by person  having count(person)>1;


--Q13
--Find all the companies with a registered address in Australia, in a Sector where there are no overseas companies in the same Sector. i.e., they are in a Sector that all companies there have local Australia address.
    create or replace view Q13(Code, Name, Address, Zip, Sector) as 
    --this is all the companies in au, with name, sector and country
    --select  name, sector,country from company  left join category on company.code = category.code where country like 'Australia';
    -- the sector that contains country(s) that are not in Australia
    -- select  distinct(sector) from category join company  on category.code = company.code  where country <> 'Australia' order by sector;
    
    select  company.code, name, address, zip,sector 
    from company  left join category on company.code = category.code 
    where country like 'Australia' and sector not in (select  distinct(sector) 
    from category 
    join company  
    on category.code = company.code  
    where country <> 'Australia' order by sector);





--Q14
--Calculate stock gains based on their prices of the first trading day and last trading day (i.e., the oldest "Date" and the most recent "Date" of the records stored in the ASX table). Order your result by Gain in descending order and then by Code in ascending order.
    
    --this is the start date and end date of each code 
    --select min("Date") as startdate, max("Date") as enddate, code  from asx group by code;
    --create or replace view temp14(startdate, enddate, newcode)as




    --select min("Date") as startdate, max("Date") as enddate, code  from asx group by code;
    
    
    
    
    
    
    --select  newcode as code, asx.price as beginprice, asx2.price as endprice, (asx2.price-  asx.price) as change,100*(asx2.price - asx.price)/asx.price as gain from temp14 join asx on asx."Date" = startdate and  asx.code = newcode join asx as asx2 on asx2."Date" = enddate and asx2.code = newcode;
    create or replace view Q14(Code, BeginPrice, EndPrice, Change, Gain) as 
    select  asx.code , asx.price as beginprice, asx2.price as endprice, 
    (asx2.price-  asx.price) as change,100*(asx2.price - asx.price)/asx.price 
    as gain from (select min("Date") as startdate, max("Date") as enddate, code  
    from asx group by code)as temp14 join asx on asx."Date" = startdate and  asx.code = temp14.code 
    join asx as asx2 on asx2."Date" = enddate 
    and asx2.code = temp14.code order by gain desc, code desc;


   --select  newcode as code, asx.price as beginprice, asx2.price as endprice, (asx2.price-  asx.price) as change,100*(asx2.price - asx.price)/asx.price as gain from (select min("Date") as startdate, max("Date") as enddate, code  from asx group by code)as temp14 join asx on asx."Date" = startdate and  asx.code = newcode join asx as asx2 on asx2."Date" = enddate and asx2.code = newcode;


    






--Q15
--For all the trading records in the ASX table, produce the following statistics as a database view (where Gain is measured in percentage). AvgDayGain is defined as the summation of all the daily gains (in percentage) then divided by the number of trading days (as noted above, the total number of days here should exclude the first trading day).
   
    create or replace view daygain(Code, MinDayGain, AvgDayGain,MaxDayGain) as
    select prev.code, min(100*(curr.price- prev.price)/prev.price) as mindaygain, avg(100*(curr.price - prev.price)/prev.price) as  avgdaygain, max(100*(curr.price - prev.price)/prev.price) as maxdaygain from asx as prev, shifted as curr where prev."Date"= curr.newdate and prev.code = curr.code group by prev.code order by code;


    create or replace view first(Code, Minprice, Avgprice, MaxPrice) as
    select asx1.code, min(price) as minprice, avg(price) as avgprice, max(price) as maxprice from asx as asx1 group by code;
   
   
    create or replace view Q15(Code, MinPrice, AvgPrice, MaxPrice, MinDayGain, AvgDayGain, MaxDayGain) as 
    select f.code, f.minprice,f.avgprice, f.maxprice, s.mindaygain, s.avgdaygain, s.maxdaygain from first as f, daygain as s where f.code = s.code order by f.code;
    --the frist few column
    --select code, min(price) as minprice, avg(price) as avgprice, max(price) as maxprice  from asx group by code order by code;



--Q16
--Create a trigger on the Executive table, to check and disallow any insert or update of a Person in the Executive table to be an executive of more than one company. 

create trigger q16 before insert or update
on executive for each row execute PROCEDURE checkDup();

create  or replace function checkDup() returns trigger 
as $$
BEGIN
    
    IF new.person in(select * from q12) THEN
    RAISE EXCEPTION 'insertion not allowed %', new.person;
    RETURN NULL;
    END IF;
    RETURN NEW;
    end;
    $$ language plpgsql;






--Q17
--Suppose more stock trading data are incoming into the ASX table. 
--Create a trigger to increase the stock's rating (as Star's) to 5 
--when the stock has made a maximum daily price gain (when compared with 
--the price on the previous trading day) in percentage within its sector. 
--For example, for a given day and a given sector, if Stock A has the maximum 
--price gain in the sector, its rating should then be updated to 5. 
--If it happens to have more than one stock with the same maximum price gain, 
--update all these stocks' ratings to 5. Otherwise, decrease the stock's 
--rating to 1 when the stock has performed the worst in the sector in terms of daily 
--percentage price gain. If there are more than one record of rating for a given stock 
--that need to be updated, update (not insert) all these records. You may assume that 
--there are at least two trading records for each stock in the existing ASX table, and 
--do not worry about the case that when the ASX table is initially empty. 

create trigger q17 before insert or update
on asx for each row execute PROCEDURE ratenew();

create  or replace function ratenew() returns trigger 
as $$
DECLARE
lastprice float;
minimum float;
maximum float;

BEGIN

    select endprice into lastprice from q14 where new.code = q14.code
    --set maximum = select maxdaygain from q15 where new.code = q15.code
             -- rate it as a five star
    if(1.0)
    then
        RAISE EXCEPTION 'wow this is huge %',new.code;
    end if;
        return new;
    END;
    $$ language plpgsql;









--Stock price and trading volume data are usually incoming data and seldom involve updating existing data. However, updates are allowed in order to correct data errors. All such updates (instead of data insertion) are logged and stored in the ASXLog table. Create a trigger to log any updates on Price and/or Voume in the ASX table and log these updates (only for update, not inserts) into the ASXLog table. Here we assume that Date and Code cannot be corrected and will be the same as their original, old values. Timestamp is the date and time that the correction takes place. Note that it is also possible that a record is corrected more than once, i.e., same Date and Code but different Timestamp.

