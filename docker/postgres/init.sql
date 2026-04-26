
DROP TABLE IF EXISTS user_favorite_providers CASCADE;
DROP TABLE IF EXISTS user_restrictions CASCADE;
DROP TABLE IF EXISTS restrictions CASCADE;
DROP TABLE IF EXISTS user_avoided_ingredients CASCADE;
DROP TABLE IF EXISTS user_favorite_ingredients CASCADE;
DROP TABLE IF EXISTS user_favorite_categories CASCADE;

DROP TABLE IF EXISTS wishlist CASCADE;
DROP TABLE IF EXISTS tried_drinks CASCADE;
DROP TABLE IF EXISTS drink_ingredients CASCADE;

DROP TABLE IF EXISTS drinks CASCADE;
DROP TABLE IF EXISTS ingredients CASCADE;
DROP TABLE IF EXISTS categories CASCADE;

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS providers CASCADE;



CREATE TABLE providers (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password TEXT NOT NULL,
    type VARCHAR(100),
    location VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);



CREATE TABLE drinks (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price NUMERIC(10,2),
    provider_id INT REFERENCES providers(id) ON DELETE CASCADE,
    category_id INT REFERENCES categories(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE ingredients (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255)
);



CREATE TABLE drink_ingredients (
    drink_id INT REFERENCES drinks(id) ON DELETE CASCADE,
    ingredient_id INT REFERENCES ingredients(id) ON DELETE CASCADE,
    PRIMARY KEY (drink_id, ingredient_id)
);



CREATE TABLE tried_drinks (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    drink_id INT REFERENCES drinks(id) ON DELETE CASCADE,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    notes TEXT,
    tried_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, drink_id)
);



CREATE TABLE wishlist (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    drink_id INT REFERENCES drinks(id) ON DELETE CASCADE,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, drink_id)
);



CREATE TABLE user_favorite_categories (
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    category_id INT REFERENCES categories(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, category_id)
);



CREATE TABLE user_favorite_ingredients (
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    ingredient_id INT REFERENCES ingredients(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, ingredient_id)
);



CREATE TABLE user_avoided_ingredients (
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    ingredient_id INT REFERENCES ingredients(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, ingredient_id)
);



CREATE TABLE restrictions (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);



CREATE TABLE user_restrictions (
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    restriction_id INT REFERENCES restrictions(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, restriction_id)
);



CREATE TABLE user_favorite_providers (
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    provider_id INT REFERENCES providers(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, provider_id)
);