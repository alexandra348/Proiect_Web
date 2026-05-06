
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
    address VARCHAR(255),
    city VARCHAR(255),
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


-- PROVIDERS
INSERT INTO providers (name, email, password, type, address, city) VALUES
('Urban Brew', 'urban@brew.com', 'pass', 'Cafe', 'Str. Palas 1', 'Iasi'),
('Daily Dose', 'dose@coffee.com', 'pass', 'Cafe', 'Str. Stefan 10', 'Iasi'),
('Fresh Corner', 'fresh@corner.com', 'pass', 'Juice Bar', 'Str. Unirii 5', 'Cluj'),
('Green Spot', 'green@spot.com', 'pass', 'Healthy Bar', 'Str. Republicii 8', 'Cluj'),
('Relax Tea House', 'tea@relax.com', 'pass', 'Tea House', 'Str. Florilor 3', 'Brasov');

-- USERS
INSERT INTO users (name, email, password) VALUES
('Ana Popescu','ana@test.com','123'),
('Ion Ionescu','ion@test.com','123'),
('Maria Vasilescu','maria@test.com','123'),
('George Marin','george@test.com','123'),
('Elena Dumitru','elena@test.com','123'),
('Paul Radu','paul@test.com','123'),
('Ioana Nistor','ioana@test.com','123'),
('Andrei Pavel','andrei@test.com','123'),
('Diana Muresan','diana@test.com','123'),
('Cristi Pop','cristi@test.com','123');

-- CATEGORIES
INSERT INTO categories (name) VALUES
('Coffee'),('Tea'),('Fresh Juice'),('Smoothie'),('Milkshake');

-- INGREDIENTS
INSERT INTO ingredients (name) VALUES
('Espresso'),('Milk'),('Foamed Milk'),('Sugar'),
('Green Tea Leaves'),('Black Tea Leaves'),
('Orange'),('Apple'),('Banana'),('Strawberry'),
('Mango'),('Honey'),('Ice'),('Chocolate Syrup'),
('Yogurt'),('Oat Milk');

-------------------------------------------------
-- DRINKS
-------------------------------------------------

-- Provider 1
INSERT INTO drinks (name, price, provider_id, category_id) VALUES
('Espresso',8,1,1),
('Cappuccino',12,1,1),
('Latte',13,1,1),
('Iced Latte',14,1,1),
('Orange Juice',14,1,3),
('Apple Juice',13,1,3),
('Strawberry Smoothie',17,1,4);

-- Provider 2
INSERT INTO drinks (name, price, provider_id, category_id) VALUES
('Flat White',13,2,1),
('Americano',9,2,1),
('Green Tea',10,2,2),
('Black Tea',10,2,2),
('Banana Smoothie',16,2,4),
('Chocolate Milkshake',18,2,5);

-- Provider 3
INSERT INTO drinks (name, price, provider_id, category_id) VALUES
('Fresh Orange Juice',15,3,3),
('Apple & Carrot Juice',15,3,3),
('Mango Smoothie',18,3,4),
('Berry Smoothie',18,3,4),
('Green Detox Juice',17,3,3);

-- Provider 4
INSERT INTO drinks (name, price, provider_id, category_id) VALUES
('Protein Smoothie',20,4,4),
('Strawberry Banana Smoothie',19,4,4),
('Oat Latte',14,4,1),
('Honey Tea',12,4,2);

-- Provider 5
INSERT INTO drinks (name, price, provider_id, category_id) VALUES
('Chamomile Tea',11,5,2),
('Mint Tea',11,5,2),
('Lemon Tea',12,5,2),
('Iced Tea',12,5,2);

-------------------------------------------------
-- DRINK INGREDIENTS
-------------------------------------------------

-- 1 Espresso
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (1,1);

-- 2 Cappuccino
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (2,1),(2,2),(2,3);

-- 3 Latte
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (3,1),(3,2);

-- 4 Iced Latte
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (4,1),(4,2),(4,13);

-- 5 Orange Juice
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (5,7);

-- 6 Apple Juice
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (6,8);

-- 7 Strawberry Smoothie
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (7,10),(7,2),(7,13);

-- 8 Flat White
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (8,1),(8,2);

-- 9 Americano
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (9,1),(9,13);

-- 10 Green Tea
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (10,5);

-- 11 Black Tea
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (11,6);

-- 12 Banana Smoothie
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (12,9),(12,2);

-- 13 Chocolate Milkshake
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (13,2),(13,14),(13,13);

-- 14 Fresh Orange Juice
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (14,7);

-- 15 Apple & Carrot Juice
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (15,8);

-- 16 Mango Smoothie
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (16,11),(16,15);

-- 17 Berry Smoothie
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (17,10),(17,15);

-- 18 Green Detox Juice
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (18,7),(18,8);

-- 19 Protein Smoothie
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (19,9),(19,15);

-- 20 Strawberry Banana Smoothie
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (20,10),(20,9),(20,15);

-- 21 Oat Latte
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (21,1),(21,16);

-- 22 Honey Tea
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (22,5),(22,12);

-- 23 Chamomile Tea
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (23,5);

-- 24 Mint Tea
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (24,5);

-- 25 Lemon Tea
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (25,5);

-- 26 Iced Tea
INSERT INTO drink_ingredients (drink_id, ingredient_id) VALUES (26,5),(26,13);



-------------------------------------------------
-- RESTRICTIONS
-------------------------------------------------
INSERT INTO restrictions (name) VALUES
('Vegan'),('Lactose-Free'),('Sugar-Free'),('Caffeine-Free');

-------------------------------------------------
-- USER PREFERENCES
-------------------------------------------------

-- favorite categories
INSERT INTO user_favorite_categories VALUES
(1,1),(2,2),(3,3),(4,4),(5,5),
(6,1),(7,3),(8,4),(9,2),(10,1);

-- favorite ingredients
INSERT INTO user_favorite_ingredients VALUES
(1,1),(2,5),(3,7),(4,9),(5,14),
(6,2),(7,10),(8,11),(9,6),(10,8);

-- avoided
INSERT INTO user_avoided_ingredients VALUES
(1,4),(2,2),(3,14),(4,7),(5,1),
(6,3),(7,5),(8,8),(9,10),(10,11);

-- restrictions
INSERT INTO user_restrictions VALUES
(1,2),(2,1),(3,3),(4,4),(5,2),
(6,1),(7,4),(8,2),(9,3),(10,1);

-- favorite providers
INSERT INTO user_favorite_providers VALUES
(1,1),(2,2),(3,3),(4,4),(5,5),
(6,1),(7,3),(8,4),(9,2),(10,5);

-------------------------------------------------
-- TRIED DRINKS
-------------------------------------------------
INSERT INTO tried_drinks (user_id, drink_id, rating, notes) VALUES

-- User 1
(1,2,5,'Foarte bun cappuccino'),
(1,5,4,'Suc fresh, placut'),
(1,10,5,'Ceai relaxant'),

-- User 2
(2,10,4,'Relaxant seara'),
(2,11,5,'Foarte aromat'),
(2,14,3,'Cam acru pentru gustul meu'),

-- User 3
(3,5,5,'Fresh si natural'),
(3,7,4,'Smoothie gustos'),
(3,16,5,'Foarte bun mango smoothie'),

-- User 4
(4,12,4,'Energizant bun'),
(4,13,3,'Prea dulce pentru mine'),
(4,1,5,'Espresso excelent'),

-- User 5
(5,13,3,'Prea dulce'),
(5,20,5,'Smoothie delicios'),
(5,18,4,'Detox bun'),

-- User 6
(6,3,5,'Latte foarte bun'),
(6,9,4,'Americano ok'),
(6,21,5,'Oat latte interesant'),

-- User 7
(7,6,4,'Suc de mere fresh'),
(7,8,5,'Flat white perfect'),
(7,17,4,'Berry smoothie bun'),

-- User 8
(8,2,5,'Cappuccino perfect'),
(8,4,4,'Iced latte racoritor'),
(8,25,3,'Ceai cam slab'),

-- User 9
(9,19,5,'Protein smoothie excelent'),
(9,22,4,'Ceai cu miere bun'),
(9,15,3,'Suc ok dar simplu'),

-- User 10
(10,1,5,'Espresso foarte bun'),
(10,24,4,'Mint tea relaxant'),
(10,26,5,'Iced tea perfect vara');

-------------------------------------------------
-- WISHLIST
-------------------------------------------------
INSERT INTO wishlist (user_id, drink_id) VALUES
(1,3),(2,7),(3,16),(4,8),(5,11);