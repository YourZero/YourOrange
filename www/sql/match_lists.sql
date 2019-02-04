create table match_lists
(
  lane       varchar(256) null,
  gameId     bigint       not null
    primary key,
  champion   int          null,
  platformId varchar(256) null,
  timestamp  bigint       null,
  queue      int          null,
  role       varchar(256) null,
  season     int          null
);

