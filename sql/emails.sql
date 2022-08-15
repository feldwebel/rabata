CREATE TABLE public.emails (
	email varchar NULL,
	checked int4 NULL DEFAULT 0,
	"valid" int4 NULL DEFAULT 0,
	confirmed int4 NULL DEFAULT 0
);
CREATE UNIQUE INDEX emails_email_idx ON public.emails USING btree (email);

INSERT INTO public.emails (email,checked,"valid",confirmed) VALUES
	 ('email4@sample.com',0,0,0),
	 ('email7@sample.com',0,0,0),
	 ('email6@sample.com',0,0,0),
	 ('email5@sample.com',0,0,0),
	 ('email3@sample.com',0,0,0),
	 ('email2@sample.com',0,0,0),
	 ('email1@sample.com',1,1,1);

