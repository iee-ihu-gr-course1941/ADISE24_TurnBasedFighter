CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,  -- for storing hashed passwords
    wins INT DEFAULT 0,
    losses INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DELIMITER //

CREATE PROCEDURE RegisterUser(
    IN p_username VARCHAR(50),
    IN p_email VARCHAR(100),
    IN p_password_hash VARCHAR(255)
)
BEGIN
    INSERT INTO users (username, email, password_hash)
    VALUES (p_username, p_email, p_password_hash);
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE GetUserByUsername(
    IN p_username VARCHAR(50)
)
BEGIN
    SELECT user_id, username, email, wins, losses, created_at
    FROM users
    WHERE username = p_username;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE UpdateUserStats(
    IN p_user_id INT,
    IN p_wins INT,
    IN p_losses INT
)
BEGIN
    UPDATE users
    SET wins = wins + p_wins,
        losses = losses + p_losses
    WHERE user_id = p_user_id;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE CheckUserLogin(
    IN p_username VARCHAR(50),
    IN p_password_hash VARCHAR(255)
)
BEGIN
    SELECT user_id, username
    FROM users
    WHERE username = p_username AND password_hash = p_password_hash;
END //

DELIMITER ;