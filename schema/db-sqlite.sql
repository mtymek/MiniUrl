CREATE TABLE short_urls (
  long_url VARCHAR(256) PRIMARY KEY NOT NULL,
  short_url VARCHAR(256) NOT NULL,
  creation_date INT NOT NULL
);
CREATE INDEX short_url_idx ON short_urls(short_url);
