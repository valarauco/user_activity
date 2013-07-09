-- PostgreSQL table for user_activity

CREATE TABLE user_activity (
  ip_address varchar(40) NOT NULL,
  user_id integer NOT NULL
        REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE,
  first timestamp with time zone NOT NULL,
  last timestamp with time zone NOT NULL,
  counter integer NOT NULL
  PRIMARY KEY (ip_address, user_id)
);

CREATE INDEX user_activity_last_idx ON user_activity(last);
CREATE INDEX user_activity_counter_idx ON user_activity(counter);
CREATE INDEX user_activity_key_idx ON user_activity(ip_address, user_id);
