CREATE TABLE short_url (
    long_url VARCHAR(256) PRIMARY KEY NOT NULL,
    short_url VARCHAR(256) NOT NULL,
    creation_date INT NOT NULL
);