CREATE TABLE short_urls (
  long_url VARCHAR(256) PRIMARY KEY NOT NULL,
  short_hash VARCHAR(256) NOT NULL
);
CREATE INDEX short_url_idx ON short_urls(short_hash);
