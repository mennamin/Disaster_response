-- Disaster Response: Backup Request + Best Route starter schema
-- SAFE FOR TESTING: uses a separate database and does not drop any tables.
CREATE DATABASE IF NOT EXISTS disaster_response_features
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE disaster_response_features;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  role ENUM('admin','department_head','responder','dispatcher') NOT NULL DEFAULT 'responder',
  department VARCHAR(80) NOT NULL DEFAULT 'General',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS incidents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  description TEXT NULL,
  department VARCHAR(80) NOT NULL DEFAULT 'General',
  priority ENUM('Low','Medium','High','Critical') NOT NULL DEFAULT 'Medium',
  status ENUM('Reported','Assigned','In Progress','Resolved') NOT NULL DEFAULT 'Reported',
  location_name VARCHAR(255) NOT NULL,
  latitude DECIMAL(10,7) NULL,
  longitude DECIMAL(10,7) NULL,
  assigned_team VARCHAR(150) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS backup_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  incident_id INT NOT NULL,
  requested_by INT NOT NULL,
  department VARCHAR(80) NOT NULL,
  resource_type ENUM('Ambulance','Fire engine','Rescue team','Medical supplies','Food and water','Other') NOT NULL,
  quantity INT UNSIGNED NOT NULL DEFAULT 1,
  priority ENUM('Low','Medium','High','Critical') NOT NULL DEFAULT 'Medium',
  reason TEXT NOT NULL,
  status ENUM('Waiting','Approved','Solved') NOT NULL DEFAULT 'Waiting',
  needed_by DATETIME NULL,
  reviewed_by INT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_brf_incident FOREIGN KEY (incident_id) REFERENCES incidents(id),
  CONSTRAINT fk_brf_requester FOREIGN KEY (requested_by) REFERENCES users(id),
  CONSTRAINT fk_brf_reviewer FOREIGN KEY (reviewed_by) REFERENCES users(id),
  INDEX idx_brf_status (status),
  INDEX idx_brf_priority (priority),
  INDEX idx_brf_created (created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS route_searches (
  id INT AUTO_INCREMENT PRIMARY KEY,
  requested_by INT NOT NULL,
  origin_name VARCHAR(255) NOT NULL,
  destination_name VARCHAR(255) NOT NULL,
  origin_lat DECIMAL(10,7) NOT NULL,
  origin_lng DECIMAL(10,7) NOT NULL,
  destination_lat DECIMAL(10,7) NOT NULL,
  destination_lng DECIMAL(10,7) NOT NULL,
  distance_km DECIMAL(10,2) NOT NULL,
  duration_minutes DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_route_user_brf FOREIGN KEY (requested_by) REFERENCES users(id),
  INDEX idx_route_created (created_at)
) ENGINE=InnoDB;

INSERT IGNORE INTO users (id, full_name, email, role, department)
VALUES (1, 'Demo Dispatcher', 'demo@example.com', 'admin', 'Emergency Coordination');

INSERT INTO incidents (title, description, department, priority, status, location_name, latitude, longitude, assigned_team)
SELECT 'Building fire', 'Smoke reported near a residential building. Fire and rescue support may be required.', 'Fire & Rescue', 'Critical', 'Assigned', 'Tahrir Square, Cairo', 30.0444200, 31.2357120, 'Fire Team A'
WHERE NOT EXISTS (SELECT 1 FROM incidents WHERE title = 'Building fire' AND location_name = 'Tahrir Square, Cairo');

INSERT INTO incidents (title, description, department, priority, status, location_name, latitude, longitude, assigned_team)
SELECT 'Road accident', 'Multi-vehicle accident; medical assistance requested.', 'Medical', 'High', 'In Progress', 'Cairo Tower, Cairo', 30.0459000, 31.2243000, 'Ambulance Team 2'
WHERE NOT EXISTS (SELECT 1 FROM incidents WHERE title = 'Road accident' AND location_name = 'Cairo Tower, Cairo');

INSERT INTO backup_requests (incident_id, requested_by, department, resource_type, quantity, priority, reason, status)
SELECT i.id, 1, 'Fire Department', 'Fire engine', 2, 'Critical', 'Additional fire suppression support needed at the scene.', 'Waiting'
FROM incidents i
WHERE i.title = 'Building fire' AND i.location_name = 'Tahrir Square, Cairo'
AND NOT EXISTS (SELECT 1 FROM backup_requests br WHERE br.reason = 'Additional fire suppression support needed at the scene.');
