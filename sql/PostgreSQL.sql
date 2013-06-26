-- PostgreSQL table for user_activity

CREATE TABLE user_activity (
	ip varchar(40) NOT NULL PRIMARY KEY,
	first timestamp with time zone NOT NULL,
	last timestamp with time zone NOT NULL,
	counter integer NOT NULL
);

CREATE INDEX u_a_last_idx ON user_activity(last);
CREATE INDEX u_a_counter_idx ON user_activity(counter);
