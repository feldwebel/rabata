CREATE TABLE public.emails (
	email varchar NULL,
	checked int4 NULL DEFAULT 0,
	"valid" int4 NULL DEFAULT 0,
	confirmed int4 NULL DEFAULT 0
);
CREATE UNIQUE INDEX emails_email_idx ON public.emails USING btree (email);
