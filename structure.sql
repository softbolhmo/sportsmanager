DROP TABLE IF EXISTS wp_sportsmanager_clubs;
CREATE TABLE IF NOT EXISTS wp_sportsmanager_clubs (
  id int(11) NOT NULL AUTO_INCREMENT,
  league_id int(11) NOT NULL,
  sport varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  slug varchar(100) NOT NULL,
  description text NOT NULL,
  small_logo_url varchar(256) NOT NULL,
  large_logo_url varchar(256) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wp_sportsmanager_games;
CREATE TABLE IF NOT EXISTS wp_sportsmanager_games (
  id int(11) NOT NULL AUTO_INCREMENT,
  league_id int(11) NOT NULL,
  season year(4) NOT NULL,
  sport varchar(100) NOT NULL,
  home_team_id int(11) NOT NULL,
  away_team_id int(11) NOT NULL,
  home_score int(11) NOT NULL,
  away_score int(11) NOT NULL,
  winner_team_id int(11) NOT NULL,
  `date` datetime NOT NULL,
  `type` varchar(2) NOT NULL,
  location_id int(11) NOT NULL,
  cancelled int(1) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wp_sportsmanager_leagues;
CREATE TABLE IF NOT EXISTS wp_sportsmanager_leagues (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  slug varchar(100) NOT NULL,
  description text NOT NULL,
  small_logo_url varchar(256) NOT NULL,
  large_logo_url varchar(256) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wp_sportsmanager_locations;
CREATE TABLE IF NOT EXISTS wp_sportsmanager_locations (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  slug varchar(100) NOT NULL,
  description text NOT NULL,
  small_logo_url varchar(256) NOT NULL,
  large_logo_url varchar(256) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wp_sportsmanager_players;
CREATE TABLE IF NOT EXISTS wp_sportsmanager_players (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wp_sportsmanager_scoresheets;
CREATE TABLE IF NOT EXISTS wp_sportsmanager_scoresheets (
  id int(11) NOT NULL AUTO_INCREMENT,
  league_id int(11) NOT NULL,
  season year(4) NOT NULL,
  sport varchar(100) NOT NULL,
  game_id int(11) NOT NULL,
  player_id int(11) NOT NULL,
  stats text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wp_sportsmanager_teams;
CREATE TABLE IF NOT EXISTS wp_sportsmanager_teams (
  id int(11) NOT NULL AUTO_INCREMENT,
  club_id int(11) NOT NULL,
  season year(4) NOT NULL,
  players_id text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
