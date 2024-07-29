DROP DATABASE IF EXISTS Links;

CREATE DATABASE IF NOT EXISTS Links;

USE Links;

CREATE TABLE links (
    linkID      INT AUTO_INCREMENT PRIMARY KEY,
    Link        VARCHAR(255) UNIQUE,
    Username    VARCHAR(100),
    userIP      VARCHAR(20),
    Date        VARCHAR(29)
);

INSERT INTO links (Link, Username, userIP, Date) 
VALUES ('https://youtu.be/xvFZjo5PgG0?si=mWhC94yeSyCt2Nst?autoplay=1&mute=0', 'Initiator', 'localhost', NOW());