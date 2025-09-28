-- Create database
CREATE DATABASE IF NOT EXISTS vulnlab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vulnlab;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Posts table
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    author_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id)
);

-- Comments table
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Files table
CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    path VARCHAR(500) NOT NULL,
    user_id INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Seed data with hashed passwords for login security
INSERT INTO users (username, password, role, email) VALUES 
('alice', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'alice@vulnlab.test'),
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'admin@vulnlab.test'),
('bob', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'bob@vulnlab.test');

-- Password for all: password123

INSERT INTO posts (title, body, author_id) VALUES 
('Welcome to VulnLab', 'This is a test post with <script>alert("XSS")</script> for demonstration.', 1),
('Security Testing', 'Another post with <img src=x onerror=alert(1)> for XSS testing.', 2);

INSERT INTO comments (post_id, user_id, content) VALUES 
(1, 2, 'Great post! <script>alert("Comment XSS")</script>'),
(1, 3, 'Nice article! <img src="x" onerror="alert(1)">'),
(2, 1, 'Interesting content with JavaScript: <a href="javascript:alert(1)">click me</a>');
