CREATE TABLE subscription (
    id                     UUID              PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id                UUID              NOT NULL,
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