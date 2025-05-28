CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `file_path` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
);

CREATE TABLE comments (
  'id' INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  'content' TEXT NOT NULL,
  'post_id' INT NOT NULL,
  'user_id' INT NOT NULL,
  'created_at' TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  'parent_id' INT DEFAULT NULL,
  FOREIGN KEY (post_id) REFERENCES posts(id)
    ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,
  FOREIGN KEY (parent_id) REFERENCES comments(id)
    ON DELETE CASCADE
);

INSERT INTO users (username, nickname, password, is_admin)
VALUES ('admin', 'admin', '1234', 1);

