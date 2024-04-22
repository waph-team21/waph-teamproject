-- DROP TABLE IF EXISTS users;

-- CREATE TABLE users (
--     username VARCHAR(50) PRIMARY KEY,
--     password VARCHAR(100) NOT NULL
-- );

-- LOCK TABLES `users` WRITE;
-- INSERT INTO users(username,password) VALUES ('team21',md5('Pa$$w0rd'));
-- UNLOCK TABLES;



-- -- database-data.sql
-- CREATE TABLE posts (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id VARCHAR(50) NOT NULL,
--     title VARCHAR(255) NOT NULL,
--     content TEXT NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (user_id) REFERENCES users(username)
-- );


DROP TABLE IF EXISTS users;

CREATE TABLE users (
    username VARCHAR(30) PRIMARY KEY,
    password VARCHAR(50) NOT NULL,
    fullname VARCHAR(50) NOT NULL,
    mail VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    profile_disabled BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE superusers (
    username VARCHAR(255) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    additional_email VARCHAR(255),
    phone VARCHAR(20),
    profile_disabled BOOLEAN NOT NULL DEFAULT FALSE
);


CREATE TABLE messages (
    message_ID SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    type VARCHAR(50),
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    sender_username VARCHAR(255),
    receiver_username VARCHAR(255),
    FOREIGN KEY (sender_username) REFERENCES users(username),
    FOREIGN KEY (receiver_username) REFERENCES users(username)
);

CREATE TABLE posts (
    post_ID SERIAL PRIMARY KEY,
    owner VARCHAR(255),
    content TEXT NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner) REFERENCES users(username)
);


CREATE TABLE comments (
    comment_ID BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_ID BIGINT UNSIGNED NOT NULL,  -- Matching type with `post_ID` in `posts`
    username VARCHAR(255) NOT NULL,
    comment TEXT NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_ID) REFERENCES posts(post_ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;
