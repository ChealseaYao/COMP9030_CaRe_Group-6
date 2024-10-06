SET @@AUTOCOMMIT = 1;

DROP DATABASE IF EXISTS CaReDB;
CREATE DATABASE CaReDB;
USE CaReDB;

CREATE TABLE `user` (
    user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('patient', 'therapist', 'professional_staff', 'auditor') NOT NULL,
    full_name VARCHAR(100), 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) AUTO_INCREMENT = 1;

CREATE TABLE therapist (
    therapist_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE, 
    therapist_title VARCHAR(50),
    therapist_image VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES user(user_id)
) AUTO_INCREMENT = 1;


CREATE TABLE patient (
    patient_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,  
    age INT,
    gender VARCHAR(10),
    height DECIMAL(4,1),
    weight DECIMAL(4,1),
    email VARCHAR(100),
    badge ENUM('good status', 'bad status', 'danger status'),  
    therapist_id INT,  
    FOREIGN KEY (user_id) REFERENCES user(user_id),
    FOREIGN KEY (therapist_id) REFERENCES therapist(therapist_id)
) AUTO_INCREMENT = 1;


CREATE TABLE `group` (
    group_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(100) NOT NULL,
    therapist_id INT,  
    FOREIGN KEY (therapist_id) REFERENCES therapist(therapist_id)
) AUTO_INCREMENT = 1;


CREATE TABLE group_patient (
    group_patient_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    group_id INT,   
    patient_id INT, 
    FOREIGN KEY (group_id) REFERENCES `group`(group_id),
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id)
) AUTO_INCREMENT = 1;


CREATE TABLE affirmation (
    affirmation_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    affirmation VARCHAR(255) NOT NULL
) AUTO_INCREMENT = 1;


CREATE TABLE patient_affirmation (
    patient_affirmation_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    patient_id INT, 
    affirmation_id INT, 
    selection_date DATE,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id),
    FOREIGN KEY (affirmation_id) REFERENCES affirmation(affirmation_id)
) AUTO_INCREMENT = 1;


CREATE TABLE consultation (
    consultation_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    consultation_date DATE,
    duration_minutes INT,
    case_type VARCHAR(50), 
    therapist_id INT,
    patient_id INT,
    FOREIGN KEY (therapist_id) REFERENCES therapist(therapist_id),
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id)
) AUTO_INCREMENT = 1;


CREATE TABLE journal (
    journal_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    journal_date DATE NOT NULL,
    journal_content TEXT,
    food TEXT,
    exercise TEXT,
    sleep_time TIME,
    wake_time TIME,
    highlight BOOLEAN DEFAULT 0, 
    file_path VARCHAR(255),       
    original_name VARCHAR(255),   
    file_size INT,                
    file_type VARCHAR(50),        
    patient_id INT,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id)
) AUTO_INCREMENT = 1;


CREATE TABLE note (
    note_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    note_date DATE NOT NULL,
    note_content TEXT,
    patient_id INT,
    therapist_id INT,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id),
    FOREIGN KEY (therapist_id) REFERENCES therapist(therapist_id)
) AUTO_INCREMENT = 1;

CREATE VIEW professional_patient_view AS
SELECT 
    p.patient_id, 
    p.age, 
    p.gender, 
    p.height, 
    p.weight, 
    t.therapist_title,
    u.full_name AS therapist_name
FROM patient p
JOIN therapist t ON p.therapist_id = t.therapist_id
JOIN `user` u ON t.user_id = u.user_id;

GRANT SELECT ON professional_patient_view TO 'professional_staff_user'@'localhost';


CREATE USER IF NOT EXISTS dbadmin@localhost;
GRANT ALL PRIVILEGES ON CaReDB.* TO dbadmin@localhost;



INSERT INTO `user` (username, password_hash, role, full_name) VALUES
('johndoe', 'hashed_password1', 'patient', 'John Doe'), -- password: hashed_password1
('jane', 'hashed_password2', 'patient', 'Jane Roe'), -- password: hashed_password2
('jimS', 'hashed_password3', 'patient', 'Jim Smith'), -- password: hashed_password3
('alicebrn', 'hashed_password4', 'therapist', 'Dr. Alice Brown'), -- password: hashed_password4
('bob', 'hashed_password5', 'therapist', 'Dr. Bob White'), -- password: hashed_password5
('green', 'hashed_password6', 'therapist', 'Dr. Charlie Green'), -- password: hashed_password6
('joycelynn', 'hashed_password7', 'auditor', 'randon Jones'), -- password: hashed_password7
('stanleyrivera', 'hashed_password8', 'professional_staff', 'Jeffrey Ruiz'); -- password: hashed_password8



INSERT INTO therapist (user_id, therapist_title, therapist_image) VALUES
(4, 'Clinical Psychologist', 'images/dr_alice_brown.jpg'),
(5, 'Counseling Psychologist', 'images/dr_bob_white.jpg'),
(6, 'Mental Health Counselor', 'images/dr_charlie_green.jpg');


INSERT INTO patient (user_id, age, gender, height, weight, email, badge, therapist_id) VALUES
(1, 30, 'male', 175.5, 75.0, 'john.doe@example.com', 'good status', 1),
(2, 25, 'female', 165.0, 60.0, 'jane.roe@example.com', 'bad status', 2),
(3, 40, 'male', 180.0, 85.0, 'jim.smith@example.com', 'danger status', 3);


INSERT INTO `group` (group_name, therapist_id) VALUES
('Stress Management Group', 1),
('Healthy Living Group', 2),
('Anxiety Support Group', 3);


INSERT INTO group_patient (group_id, patient_id) VALUES
(1, 1), 
(2, 2), 
(3, 3); 


INSERT INTO affirmation (affirmation) VALUES
('I am strong and capable'),
('I face challenges with confidence'),
('I am worthy of respect and love');


INSERT INTO patient_affirmation (patient_id, affirmation_id, selection_date) VALUES
(1, 1, '2024-09-01'),
(1, 2, '2024-09-02'),
(2, 1, '2024-09-03'),
(2, 3, '2024-09-04'),
(3, 2, '2024-09-05'),
(3, 3, '2024-09-06');


INSERT INTO consultation (consultation_date, duration_minutes, case_type, therapist_id, patient_id) VALUES
('2024-09-10', 60, 'anxiety', 1, 1),
('2024-09-12', 45, 'stress management', 2, 2),
('2024-09-15', 50, 'depression', 3, 3);


INSERT INTO journal (journal_date, journal_content, food, exercise, sleep_time, wake_time, highlight, patient_id) VALUES
('2024-09-10', 'Today was a productive day', 'Salad, Chicken', 'Yoga', '22:00:00', '06:00:00', 0, 1),
('2024-09-11', 'Feeling a bit stressed today', 'Pasta, Bread', 'Walking', '23:00:00', '07:30:00', 1, 2),
('2024-09-12', 'Had a great workout', 'Steak, Vegetables', 'Running', '21:30:00', '05:30:00', 0, 3);


INSERT INTO note (note_date, note_content, patient_id, therapist_id) VALUES
('2024-09-10', 'Patient showed progress with anxiety management techniques', 1, 1),
('2024-09-12', 'Discussed coping strategies for stress', 2, 2),
('2024-09-15', 'Patient responded well to depression treatment', 3, 3);

