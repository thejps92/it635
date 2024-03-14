-- Creates the manufacturers table
CREATE TABLE manufacturers (
    manufacturer_id SERIAL PRIMARY KEY,
    manufacturer_name VARCHAR(255),
    manufacturer_street VARCHAR(255),
    manufacturer_city VARCHAR(255),
    manufacturer_state CHAR(2),
    manufacturer_zip VARCHAR(10)
);

-- Creates the retailers table
CREATE TABLE retailers (
    retailer_id SERIAL PRIMARY KEY,
    retailer_name VARCHAR(255),
    retailer_website VARCHAR(255)
);

-- Creates the parts table
CREATE TABLE parts (
    part_id SERIAL PRIMARY KEY,
    part_name VARCHAR(255),
    part_type VARCHAR(255),
    part_price NUMERIC(10, 2),
    part_inventory INT,
    manufacturer_id INT REFERENCES manufacturers(manufacturer_id),
    retailer_id INT REFERENCES retailers(retailer_id)
);

--  Inserts sample data into the manufacturers table
INSERT INTO manufacturers (manufacturer_name, manufacturer_street, manufacturer_city, manufacturer_state, manufacturer_zip) VALUES
    ('Intel', '2200 Mission College Blvd', 'Santa Clara', 'CA', '95052'),
    ('NVIDIA', '2788 San Tomas Expressway', 'Santa Clara', 'CA', '95051'),
    ('ASUS', '48720 Kato Rd', 'Fremont', 'CA', '94538');

-- Inserts sample data into the retailers table
INSERT INTO retailers (retailer_name, retailer_website) VALUES
    ('Amazon', 'www.amazon.com'),
    ('Newegg', 'www.newegg.com'),
    ('Micro Center', 'www.microcenter.com');

-- Inserts sample data into the parts table
INSERT INTO parts (part_name, part_type, part_price, part_inventory, manufacturer_id, retailer_id) VALUES
    ('Intel Core i9-14900K', 'Processor', 649.99, 1000, 1, 1),
    ('GeForce RTX 4090', 'Graphics Card', 1599.00, 500, 2, 2),
    ('ASUS Z790-V Prime', 'Motherboard', 219.99, 750, 3, 3);