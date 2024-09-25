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


CREATE USER IF NOT EXISTS dbadmin@localhost;
GRANT ALL PRIVILEGES ON CaReDB.* TO dbadmin@localhost;



INSERT INTO `user` (username, password_hash, role, full_name) VALUES
('johndoe', '$2y$10$CIdLJDnFmIKmSAjrtRp4sORAsl6ZM4PZJPKa2q2w1ORZ01JmbYE4m', 'patient', 'John Doe'), -- password: hashed_password1
('jane', 'hashed_pass$2y$10$E.3po59bHp/cJk/cMAW41e3Mnkzx5mOVUL2Nt6DmllAXKZCP3nVUeword2', 'patient', 'Jane Roe'), -- password: hashed_password2
('jimS', '$2y$10$YV3C92ns48GAR.UJzCdSReYX/i5KLvbMRyDXKuTosM3BkzZY109Ey', 'patient', 'Jim Smith'), -- password: hashed_password3
('alicebrn', '$2y$10$FnHhtvjhmBbg4oYNA88ebOXb7.SPrBrTLF9NqzRgPOFvEjKXLdyAK', 'therapist', 'Dr. Alice Brown'), -- password: hashed_password4
('bob', '$2y$10$H4udxioH4Wq4ppLCCeizzuvkMHUb6P08Q9TRDc4lKDXvK3.iha3T6', 'therapist', 'Dr. Bob White'), -- password: hashed_password5
('green', '$2y$10$nCzP5V0p2n.ujhEjKGpT1OMT2p.Weg5BAj8WaUUy3jl59wZd/pMq.', 'therapist', 'Dr. Charlie Green'), -- password: hashed_password6
('joycelynn', '$2y$10$7ld3VxqVH8ww/lUe9sP9PedPOI3/55jmpyvFB.lasN1aTAmLDgPC2', 'auditor', 'randon Jones'), -- password: hashed_password7
('stanleyrivera', '$2y$10$ghFfF0.tmuucQSUDbbwzGOYP.BMLur1zt7ulY1Knm0hiRJ7Gg2Eym', 'professional_staff', 'Jeffrey Ruiz'); -- password: hashed_password8


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

