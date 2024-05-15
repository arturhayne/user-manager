CREATE TABLE populations (
    id SERIAL PRIMARY KEY,
    name varchar(100)
);

CREATE TABLE population_fields (
    id SERIAL PRIMARY KEY,
    type varchar(100) NOT NULL,
    required boolean NOT NULL DEFAULT FALSE,
    isunique boolean NOT NULL DEFAULT FALSE,
    multi boolean NOT NULL DEFAULT FALSE,
    sensitive boolean NOT NULL DEFAULT FALSE,
    name varchar(100),
    dname varchar(100),
    population_id int REFERENCES populations
);

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    population_id int REFERENCES populations,
    username varchar(100) NOT NULL,
    password varchar(100) NOT NULL
);

CREATE TABLE user_values (
    id SERIAL PRIMARY KEY,
    field_id int REFERENCES population_fields,
    user_id int REFERENCES users,
    value varchar(100)
);
