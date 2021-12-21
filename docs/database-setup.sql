-- Create empty database ready for migrations
-- DESTRUCTIVE, run once for new environment setup

DROP DATABASE IF EXISTS budgets;
CREATE DATABASE budgets;

-- Generate a password and update the local .env file
CREATE USER 'budgets'@'localhost' IDENTIFIED BY 'Generated_Environment_Password';
GRANT ALL PRIVILEGES ON budgets.* to 'budgets'@'localhost';
GRANT SELECT ON shared.* TO 'budgets'@'localhost';

FLUSH PRIVILEGES;
QUIT;
