-- =============================================
-- EXTENSIONS
-- =============================================
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- =============================================
-- ENUMS
-- =============================================
CREATE TYPE frequency_type AS ENUM ('ONE_TIME', 'RECURRING');
CREATE TYPE subscription_type AS ENUM ('FREE', 'PLUS', 'PRO');
CREATE TYPE transaction_type AS ENUM ('expense', 'income');

-- =============================================
-- TABLE : user
-- =============================================

-- Table: public.user

-- DROP TABLE IF EXISTS public."user";

CREATE TABLE IF NOT EXISTS public."user"
(
    id integer NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1 ),
    name character varying(255) COLLATE pg_catalog."default",
    last_name character varying(255) COLLATE pg_catalog."default",
    email character varying(320) COLLATE pg_catalog."default" NOT NULL,
    password text COLLATE pg_catalog."default" NOT NULL,
    is_active boolean DEFAULT false,
    created_at timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_admin boolean DEFAULT false,
    CONSTRAINT user_pkey PRIMARY KEY (id)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public."user"
    OWNER to devuser;



-- Table: public.email_verification

-- DROP TABLE IF EXISTS public.email_verification;

CREATE TABLE IF NOT EXISTS public.email_verification
(
    user_id integer NOT NULL,
    token character varying(255) COLLATE pg_catalog."default" NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT email_verification_pkey PRIMARY KEY (user_id),
    CONSTRAINT fk_user FOREIGN KEY (user_id)
        REFERENCES public."user" (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE CASCADE
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.email_verification
    OWNER to devuser;
-- =============================================
-- TABLE : subscription
-- =============================================
CREATE TABLE subscription (
    id                     INTEGER           GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    user_id                INTEGER           NOT NULL,
    type                   subscription_type NOT NULL DEFAULT 'FREE',
    stripe_customer_id     TEXT,
    stripe_subscription_id TEXT,
    start_date             TIMESTAMP         DEFAULT CURRENT_TIMESTAMP,
    expiration_date        TIMESTAMP,
    created_at             TIMESTAMP         DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_subscription_user
        FOREIGN KEY (user_id)
        REFERENCES "user"(id)
        ON DELETE CASCADE
);

CREATE INDEX idx_subscription_user ON subscription(user_id);

-- =============================================
-- TABLE : account
-- =============================================
CREATE TABLE account (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    user_id              INTEGER        NOT NULL,
    short_name           VARCHAR(100)   NOT NULL,
    description          TEXT,
    creation_date        DATE           NOT NULL,
    annual_interest_rate NUMERIC(5,2)   DEFAULT 0,
    tax_rate             NUMERIC(5,2)   DEFAULT 0,
    registered_at        TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    solde                DECIMAL(15, 2) DEFAULT 0.00,

    CONSTRAINT fk_account_user
        FOREIGN KEY (user_id)
        REFERENCES "user"(id)
        ON DELETE CASCADE
);

CREATE INDEX idx_account_user ON account(user_id);

-- =============================================
-- TABLE : transaction
-- =============================================
CREATE TABLE "transaction" (
    id              UUID             PRIMARY KEY DEFAULT uuid_generate_v4(),
    account_id      INTEGER          NOT NULL,
    type            transaction_type NOT NULL,
    short_name      VARCHAR(100)     NOT NULL,
    description     TEXT,
    category        VARCHAR(50),
    frequency       frequency_type   NOT NULL,
    interval_months INTEGER          DEFAULT NULL,
    amount          NUMERIC(12,2)    NOT NULL CHECK (amount >= 0),
    start_date      DATE             NOT NULL,
    end_date        DATE,
    created_at      TIMESTAMP        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_transaction_account
        FOREIGN KEY (account_id)
        REFERENCES account(id)
        ON DELETE CASCADE
);

CREATE INDEX idx_transaction_account ON "transaction"(account_id);
CREATE INDEX idx_transaction_dates   ON "transaction"(start_date, end_date);