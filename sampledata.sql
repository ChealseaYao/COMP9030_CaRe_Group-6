
-- Add Vivian Harper (Main Therapist) as a new user
INSERT INTO `user` (username, password_hash, role, full_name) 
VALUES ('vivianharper', '123', 'therapist', 'Dr. Vivian Harper');

-- Insert Vivian Harper into the therapist table
INSERT INTO therapist (user_id, therapist_title, therapist_image) 
VALUES (9, 'Lead Clinical Psychologist', 'images/dr_vivian_harper.jpg');

-- Add Kage Wong (Main Patient) as a new user
INSERT INTO `user` (username, password_hash, role, full_name) 
VALUES ('kagewong', '123', 'patient', 'Kage Wong');

-- Insert Kage Wong into the patient table, assign to Vivian Harper
INSERT INTO patient (user_id, age, gender, height, weight, email, badge, therapist_id) 
VALUES (10, 28, 'male', 175.0, 70.0, 'kage.wong@example.com', 'good status', 4);

-- Insert new users for the patients with actual names
INSERT INTO `user` (username, password_hash, role, full_name) VALUES
('meganfox', 'hashed_password11', 'patient', 'Megan Fox'),
('chriswong', 'hashed_password12', 'patient', 'Chris Wong'),
('sophiebaker', 'hashed_password13', 'patient', 'Sophie Baker'),
('richarddoe', 'hashed_password14', 'patient', 'Richard Doe'),
('emilyclark', 'hashed_password15', 'patient', 'Emily Clark'),
('michaellee', 'hashed_password16', 'patient', 'Michael Lee'),
('annakim', 'hashed_password17', 'patient', 'Anna Kim'),
('johntaylor', 'hashed_password18', 'patient', 'John Taylor'),
('juliedavis', 'hashed_password19', 'patient', 'Julie Davis');

-- Insert 9 more patients for Vivian Harper (therapist_id = 4)
INSERT INTO patient (user_id, age, gender, height, weight, email, badge, therapist_id) 
VALUES
(11, 24, 'female', 160.0, 50.0, 'megan.fox@example.com', 'good status', 4),
(12, 30, 'male', 180.0, 75.0, 'chris.wong@example.com', 'good status', 4),
(13, 32, 'female', 165.0, 60.0, 'sophie.baker@example.com', 'bad status', 4),
(14, 40, 'male', 185.0, 90.0, 'richard.doe@example.com', 'danger status', 4),
(15, 27, 'female', 168.0, 65.0, 'emily.clark@example.com', 'good status', 4),
(16, 29, 'male', 178.0, 72.0, 'michael.lee@example.com', 'bad status', 4),
(17, 33, 'female', 162.0, 55.0, 'anna.kim@example.com', 'good status', 4),
(18, 25, 'male', 170.0, 68.0, 'john.taylor@example.com', 'good status', 4),
(19, 26, 'female', 161.0, 58.0, 'julie.davis@example.com', 'good status', 4);

INSERT INTO journal (journal_date, journal_content, food, exercise, sleep_time, wake_time, highlight, patient_id) 
VALUES
('2024-09-01', 'Had a productive meeting', 'Chicken Salad', 'Running', '22:00:00', '06:00:00', 1, 4),
('2024-09-02', 'Felt a bit stressed', 'Pasta', 'Yoga', '23:00:00', '07:00:00', 0, 4),
('2024-09-03', 'Good progress in therapy', 'Steak', 'Cycling', '21:00:00', '05:30:00', 1, 4),
('2024-09-04', 'Had a long workout', 'Salmon', 'Weightlifting', '22:30:00', '07:30:00', 1, 4),
('2024-09-05', 'Feeling optimistic', 'Grilled Veggies', 'Jogging', '21:30:00', '06:30:00', 0, 4),
('2024-09-06', 'A bit anxious today', 'Soup', 'Swimming', '23:00:00', '07:15:00', 1, 4),
('2024-09-07', 'Struggled with tasks', 'Sandwich', 'Pilates', '22:00:00', '06:00:00', 0, 4),
('2024-09-08', 'Therapy went well', 'Pancakes', 'Running', '21:45:00', '07:00:00', 1, 4),
('2024-09-09', 'Busy but positive', 'Grilled Chicken', 'Gym', '22:30:00', '06:30:00', 1, 4),
('2024-09-10', 'Meditated today', 'Fish Tacos', 'Hiking', '23:00:00', '07:00:00', 0, 4),
('2024-09-11', 'Made progress in work', 'Salad', 'Walking', '21:30:00', '05:30:00', 1, 4),
('2024-09-12', 'Focused on relaxation', 'Rice & Beans', 'Stretching', '22:15:00', '06:45:00', 0, 4),
('2024-09-13', 'Feeling refreshed', 'Fruit Smoothie', 'Cycling', '21:00:00', '06:00:00', 0, 4),
('2024-09-14', 'Good day overall', 'Chicken Wrap', 'Running', '23:00:00', '07:15:00', 1, 4),
('2024-09-15', 'Strong focus today', 'Salmon Salad', 'Walking', '22:30:00', '06:30:00', 1, 4);

-- Add notes for Kage Wong 15 Sep has 3 notes
INSERT INTO note (note_date, note_content, patient_id, therapist_id) 
VALUES
('2024-09-01', 'Great start to therapy', 4, 4),
('2024-09-02', 'Addressed stress issues', 4, 4),
('2024-09-03', 'Good progress noted', 4, 4),
('2024-09-04', 'Improvement in exercise routine', 4, 4),
('2024-09-05', 'Patient feeling positive', 4, 4),
('2024-09-06', 'Anxiety management discussed', 4, 4),
('2024-09-07', 'Some struggles noted', 4, 4),
('2024-09-08', 'Therapy session successful', 4, 4),
('2024-09-15', 'Patient showed excellent focus', 4, 4),
('2024-09-15', 'Discussed daily habits', 4, 4),
('2024-09-15', 'Planned for next session', 4, 4);

-- Insert 5 groups for Vivian, 3 of them will have more than one member
INSERT INTO `group` (group_name, therapist_id) VALUES
('Depression Support Group', 4),
('Anxiety Management Group', 4),
('Exercise & Mental Health Group', 4),
('Stress Relief Group', 4),
('Mindfulness Meditation Group', 4);

-- Insert patients into the groups (3 groups with more than one member)
INSERT INTO group_patient (group_id, patient_id) VALUES
(4, 4), -- Kage is in Group 4
(1, 5),
(1, 6),
(2, 7),
(2, 8),
(3, 9),
(3, 10),
(4, 11),
(5, 12),
(5, 13);

-- Insert 5 consultations for Vivian Harper
INSERT INTO consultation (consultation_date, duration_minutes, case_type, therapist_id, patient_id) 
VALUES
('2024-09-15', 60, 'Anxiety', 4, 4),
('2024-09-16', 45, 'Depression', 4, 11),
('2024-09-17', 50, 'Stress', 4, 12),
('2024-09-18', 60, 'Mental Health Check', 4, 13),
('2024-09-19', 55, 'Exercise & Wellness', 4, 7);

-- Insert 1 consultation for each other therapists
INSERT INTO consultation (consultation_date, duration_minutes, case_type, therapist_id, patient_id) 
VALUES
('2024-09-10', 60, 'Stress', 1, 1),
('2024-09-12', 45, 'Anxiety', 2, 2),
('2024-09-15', 50, 'Depression', 3, 3);

-- Insert 17 more affirmations
INSERT INTO affirmation (affirmation) VALUES
('I am worthy of success'),
('I embrace new challenges'),
('I am at peace with myself'),
('I am in control of my emotions'),
('I am capable of achieving my goals'),
('I radiate positivity and confidence'),
('I deserve happiness and love'),
('I have the strength to overcome obstacles'),
('I trust myself to make the right decisions'),
('I am proud of who I am becoming'),
('I choose to be happy today'),
('I am free from worry and stress'),
('I welcome positive energy into my life'),
('I let go of negativity and embrace peace'),
('I am brave and fearless'),
('I have the power to create change'),
('I am surrounded by supportive people');