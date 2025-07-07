
CREATE TABLE standings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  team_name VARCHAR(50),
  matches_played INT DEFAULT 0,
  wins INT DEFAULT 0,
  draws INT DEFAULT 0,
  losses INT DEFAULT 0,
  goals_for INT DEFAULT 0,
  goals_against INT DEFAULT 0,
  goal_difference INT DEFAULT 0,
  points INT DEFAULT 0
);

CREATE TABLE fixtures (
  id INT AUTO_INCREMENT PRIMARY KEY,
  round INT,
  home_team VARCHAR(50),
  away_team VARCHAR(50)
);

CREATE TABLE match_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  match_date DATE DEFAULT CURRENT_DATE,
  round INT,
  home_team VARCHAR(50),
  away_team VARCHAR(50),
  home_score INT,
  away_score INT
);
