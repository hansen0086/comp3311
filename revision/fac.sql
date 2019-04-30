create function fac (n integer) returns INTEGER
as $$
BEGIN
    IF n=1 then
        return 1;
    end if;

    return n*fac(n-1);

END;
$$ language plpgsql;