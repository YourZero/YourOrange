create table items
(
  id               int                  not null,
  name             varchar(256)         null,
  description      text                 null,
  colloq           varchar(256)         null,
  plaintext        text                 null,
  `into`           text                 null,
  image            text                 null,
  gold             text                 null,
  tags             text                 null,
  maps             text                 null,
  stats            text                 null,
  `from`           text                 null,
  effect           text                 null,
  depth            int                  null,
  requiredAlly     varchar(256)         null,
  inStore          tinyint(1) default 1 null,
  specialRecipe    int                  null,
  hideFromAll      tinyint(1) default 0 null,
  stacks           int                  null,
  consumed         tinyint(1) default 0 null,
  consumeOnFull    tinyint(1) default 0 null,
  requiredChampion varchar(256)         null
);

