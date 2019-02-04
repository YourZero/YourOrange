create table summoners
(
  id              varchar(256) not null
    primary key,
  accountId       varchar(256) not null,
  puuid           varchar(256) not null,
  name            varchar(256) not null,
  profileIconId   int          not null,
  updatedAt       bigint       not null,
  summonerLevel   int          not null,
  nameKey         varchar(256) not null,
  revisionDate    bigint       not null,
  matchListUpdate bigint       null
);

create index summoners_accountId_index
  on summoners (accountId);

create index summoners_matchListUpdate_index
  on summoners (matchListUpdate);

create index summoners_name_index
  on summoners (name);

create index summoners_puuid_index
  on summoners (puuid);

