-- auto-generated definition
create table champions
(
  id       varchar(256) not null,
  version  varchar(256) null,
  `key`    int          null,
  name     varchar(256) null,
  title    varchar(256) null,
  blurb    text         null,
  info     text         null,
  image    text         null,
  tags     text         null,
  partype varchar(256) null,
  stats    text         null,
  constraint champions_id_uindex
    unique (id)
);

alter table champions
  add primary key (id);

