-- Create database and tables for Online Quiz (multiple quizzes)
CREATE DATABASE IF NOT EXISTS online_quiz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE online_quiz;

-- Admins
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Quizzes (categories)
CREATE TABLE IF NOT EXISTS quizzes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Questions
CREATE TABLE IF NOT EXISTS questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quiz_id INT NOT NULL,
  question_text TEXT NOT NULL,
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Options
CREATE TABLE IF NOT EXISTS options (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question_id INT NOT NULL,
  option_text VARCHAR(255) NOT NULL,
  is_correct TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Results
CREATE TABLE IF NOT EXISTS results (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_name VARCHAR(255) NOT NULL,
  quiz_id INT NOT NULL,
  score INT NOT NULL,
  total INT NOT NULL,
  taken_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample quiz + data
INSERT INTO quizzes (name, description) VALUES
('General Knowledge', 'A short general knowledge quiz'),
('Programming Basics', 'Questions about programming fundamentals');

-- Sample questions for quiz 1
INSERT INTO questions (quiz_id, question_text) VALUES
(1, 'What is the capital of France?'),
(1, 'Which planet is known as the Red Planet?'),
(2, 'Which language is primarily used for web front-end?');

INSERT INTO options (question_id, option_text, is_correct) VALUES
(1, 'Paris', 1),
(1, 'London', 0),
(1, 'Berlin', 0),
(1, 'Madrid', 0),

(2, 'Earth', 0),
(2, 'Mars', 1),
(2, 'Jupiter', 0),
(2, 'Venus', 0),

(3, 'Python', 0),
(3, 'C++', 0),
(3, 'JavaScript', 1),
(3, 'Java', 0);
