CREATE TABLE public.users (
	user_id int8 NOT NULL GENERATED BY DEFAULT AS IDENTITY,
	user_name varchar NOT NULL,
	email varchar NOT NULL,
	validts timestamptz NOT NULL
);
CREATE UNIQUE INDEX users_email_idx ON public.users USING btree (email);