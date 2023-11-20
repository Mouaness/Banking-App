CREATE DATABASE bank;

USE bank;

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    type ENUM('client', 'agent') NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE accounts (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    currency ENUM('LBP', 'EUR', 'USD') NOT NULL,
    balance INT NOT NULL,
    status ENUM('active', 'rejected', 'pending', 'banned') NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE requests (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    receiver_id INT,
    type ENUM('account creation','deposit', 'withdrawal', 'transfer') NOT NULL,
    currency ENUM('LBP', 'EUR', 'USD') NOT NULL,
    amount INT NOT NULL,
    timestamp VARCHAR(255) DEFAULT now() NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
    FOREIGN KEY (receiver_id) REFERENCES users(id)
);

CREATE TABLE agent_operations(
    id INT NOT NULL AUTO_INCREMENT,
    agent_id INT NOT NULL,
    request_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (agent_id) REFERENCES users(id),
    FOREIGN KEY (request_id) REFERENCES requests(id)
);















