-- Football/Soccer Schema

create domain GameTime as integer check (value between 0 and 120);
create domain CardColour as varchar(6) check (value in ('red','yellow'));

-- Tables

create table Matches (
	id          integer,
	city        varchar(50) not null,
	playedOn    date not null,
	primary key (id)
);

create table Teams (
	id          integer,
	country     varchar(50) not null,
	primary key (id)
);

create table Involves (
	match       integer not null,
	team        integer not null,
	primary key (match,team),
	foreign key (match) references Matches(id),
	foreign key (team) references Teams(id)
);

create table Players (
	id          integer,
	name        varchar(50) not null,
	birthday    date,
	memberOf    integer not null,
	position    varchar(20),
	primary key (id),
	foreign key (memberOf) references Teams(id)
);

create table Goals (
	id          integer,
	scoredIn    integer not null,
	scoredBy    integer not null,
	timeScored  GameTime not null,
	rating      varchar(20),
	primary key (id),
	foreign key (scoredIn) references Matches(id),
	foreign key (scoredBy) references Players(id)
);

create table Cards (
	id          integer,
	givenIn     integer not null,
	givenTo     integer not null,
	timeGiven   GameTime not null,
	cardType    CardColour not null,
	primary key (id),
	foreign key (givenIn) references Matches(id),
	foreign key (givenTo) references Players(id)
);