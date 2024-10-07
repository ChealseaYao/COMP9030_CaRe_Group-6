USE CaReDB;
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

-- Insert new users with actual names
INSERT INTO `user` (username, password_hash, role, full_name) VALUES
('meganfox', 'hashed_password11', 'patient', 'Megan Fox'),
('chriswong', 'hashed_password12', 'patient', 'Chris Wong'),
('sophiebaker', 'hashed_password13', 'patient', 'Sophie Baker'),
('richarddoe', 'hashed_password14', 'patient', 'Richard Doe'),
('emilyclark', 'hashed_password15', 'patient', 'Emily Clark'),
('michaellee', 'hashed_password16', 'patient', 'Michael Lee'),
('annakim', 'hashed_password17', 'patient', 'Anna Kim'),
('johntaylor', 'hashed_password18', 'patient', 'John Taylor'),
('juliedavis', 'hashed_password19', 'patient', 'Julie Davis'),
('alexsmith', 'hashed_password20', 'therapist', 'Dr. Alex Smith'),
('sarawilliams', 'hashed_password21', 'therapist', 'Dr. Sara Williams'),
('mattjohnson', 'hashed_password22', 'therapist', 'Dr. Matt Johnson'),
('lucybrown', 'hashed_password23', 'therapist', 'Dr. Lucy Brown'),
('davidlee', 'hashed_password24', 'therapist', 'Dr. David Lee'),
('lisamartin', 'hashed_password25', 'therapist', 'Dr. Lisa Martin'),
('jameswhite', 'hashed_password26', 'therapist', 'Dr. James White'),
('emilydavis', 'hashed_password27', 'therapist', 'Dr. Emily Davis'),
('thomasmiller', 'hashed_password28', 'therapist', 'Dr. Thomas Miller'),
('susanthompson', 'hashed_password29', 'therapist', 'Dr. Susan Thompson'),
('brianharris', 'hashed_password30', 'therapist', 'Dr. Brian Harris'),
('kimberlynguyen', 'hashed_password31', 'therapist', 'Dr. Kimberly Nguyen'),
('ryanwilliams', 'hashed_password32', 'therapist', 'Dr. Ryan Williams'),
('laurawilson', 'hashed_password33', 'therapist', 'Dr. Laura Wilson'),
('georgeyoung', 'hashed_password34', 'therapist', 'Dr. George Young'),
('oliviaperez', 'hashed_password35', 'patient', 'Olivia Perez'),
('danieljohnson', 'hashed_password36', 'patient', 'Daniel Johnson'),
('jessicarodriguez', 'hashed_password37', 'patient', 'Jessica Rodriguez'),
('ethanjones', 'hashed_password38', 'patient', 'Ethan Jones'),
('nataliegreen', 'hashed_password39', 'patient', 'Natalie Green'),
('liamthomas', 'hashed_password40', 'patient', 'Liam Thomas'),
('gracemiller', 'hashed_password41', 'patient', 'Grace Miller'),
('noahbrown', 'hashed_password42', 'patient', 'Noah Brown'),
('miawilson', 'hashed_password43', 'patient', 'Mia Wilson'),
('jacksonlee', 'hashed_password44', 'patient', 'Jackson Lee'),
('emilynguyen', 'hashed_password45', 'patient', 'Emily Nguyen'),
('loganscott', 'hashed_password46', 'patient', 'Logan Scott'),
('averywhite', 'hashed_password47', 'patient', 'Avery White'),
('harperdavis', 'hashed_password48', 'patient', 'Harper Davis'),
('jamesyoung', 'hashed_password49', 'patient', 'James Young'),
('abigailallen', 'hashed_password50', 'patient', 'Abigail Allen'),
('lucasmartin', 'hashed_password51', 'patient', 'Lucas Martin'),
('ellagray', 'hashed_password52', 'patient', 'Ella Gray'),
('masonking', 'hashed_password53', 'patient', 'Mason King'),
('sophiataylor', 'hashed_password54', 'patient', 'Sophia Taylor');

-- Insert more therapists into the therapist table
INSERT INTO therapist (user_id, therapist_title, therapist_image) 
VALUES
(20, 'Clinical Psychologist', 'images/dr_alex_smith.jpg'),
(21, 'Counseling Psychologist', 'images/dr_sara_williams.jpg'),
(22, 'Mental Health Counselor', 'images/dr_matt_johnson.jpg'),
(23, 'Family Therapist', 'images/dr_lucy_brown.jpg'),
(24, 'Cognitive Behavioral Therapist', 'images/dr_david_lee.jpg'),
(25, 'Pediatric Psychologist', 'images/dr_lisa_martin.jpg'),
(26, 'Addiction Specialist', 'images/dr_james_white.jpg'),
(27, 'Marriage and Family Therapist', 'images/dr_emily_davis.jpg'),
(28, 'Clinical Neuropsychologist', 'images/dr_thomas_miller.jpg'),
(29, 'Psychiatric Nurse', 'images/dr_susan_thompson.jpg'),
(30, 'Behavioral Therapist', 'images/dr_brian_harris.jpg'),
(31, 'Clinical Social Worker', 'images/dr_kimberly_nguyen.jpg'),
(32, 'Forensic Psychologist', 'images/dr_ryan_williams.jpg'),
(33, 'Occupational Therapist', 'images/dr_laura_wilson.jpg'),
(34, 'Trauma Therapist', 'images/dr_george_young.jpg');

-- Insert more patients into the patient table
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
(19, 26, 'female', 161.0, 58.0, 'julie.davis@example.com', 'good status', 4),
(35, 29, 'female', 164.0, 58.0, 'olivia.perez@example.com', 'good status', 4),
(36, 34, 'male', 176.0, 82.0, 'daniel.johnson@example.com', 'good status', 5),
(37, 27, 'female', 160.0, 52.0, 'jessica.rodriguez@example.com', 'bad status', 6),
(38, 31, 'male', 182.0, 88.0, 'ethan.jones@example.com', 'good status', 7),
(39, 24, 'female', 168.0, 62.0, 'natalie.green@example.com', 'good status', 8),
(40, 36, 'male', 178.0, 85.0, 'liam.thomas@example.com', 'danger status', 9),
(41, 28, 'female', 170.0, 65.0, 'grace.miller@example.com', 'bad status', 11),
(42, 32, 'male', 185.0, 90.0, 'noah.brown@example.com', 'good status', 12),
(43, 25, 'female', 162.0, 56.0, 'mia.wilson@example.com', 'good status', 13),
(44, 33, 'male', 180.0, 84.0, 'jackson.lee@example.com', 'bad status', 14),
(45, 29, 'female', 166.0, 60.0, 'emily.nguyen@example.com', 'good status', 15),
(46, 30, 'male', 172.0, 77.0, 'logan.scott@example.com', 'good status', 16),
(47, 22, 'female', 159.0, 53.0, 'avery.white@example.com', 'danger status', 17),
(48, 35, 'female', 163.0, 61.0, 'harper.davis@example.com', 'good status', 18),
(49, 38, 'male', 184.0, 87.0, 'james.young@example.com', 'bad status', 5),
(50, 28, 'female', 169.0, 64.0, 'abigail.allen@example.com', 'good status', 6),
(51, 31, 'male', 177.0, 80.0, 'lucas.martin@example.com', 'good status', 8),
(52, 26, 'female', 161.0, 57.0, 'ella.gray@example.com', 'good status', 12),
(53, 29, 'male', 174.0, 79.0, 'mason.king@example.com', 'bad status', 19),
(54, 34, 'female', 165.0, 63.0, 'sophia.taylor@example.com', 'good status', 11);

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
('2024-10-01', 'Made progress in work', 'Salad', 'Walking', '21:30:00', '05:30:00', 1, 4),
('2024-10-02', 'Focused on relaxation', 'Rice & Beans', 'Stretching', '22:15:00', '06:45:00', 0, 4),
('2024-10-03', 'Feeling refreshed', 'Fruit Smoothie', 'Cycling', '21:00:00', '06:00:00', 0, 4),
('2024-10-04', 'Good day overall', 'Chicken Wrap', 'Running', '23:00:00', '07:15:00', 1, 4),
('2024-10-05', 'Strong focus today', 'Salmon Salad', 'Walking', '22:30:00', '06:30:00', 1, 4),
('2024-10-05', 'Had a good day overall, feeling positive', 'Pasta with veggies', 'Yoga', '22:30:00', '06:30:00', 0, 11),
('2024-10-05', 'Struggled a bit with anxiety', 'Chicken salad', 'Walking', '23:00:00', '07:00:00', 1, 12),
('2024-10-05', 'Focused on relaxation techniques', 'Fruit smoothie', 'Meditation', '21:00:00', '05:30:00', 0, 13),
('2024-10-06', 'Had a productive day at work', 'Grilled fish and rice', 'Running', '23:30:00', '07:30:00', 1, 14),
('2024-10-06', 'Feeling much better today', 'Vegetable stir fry', 'Cycling', '22:00:00', '06:00:00', 0, 15),
('2024-10-06', 'Managed to complete my tasks', 'Salad and chicken', 'Weightlifting', '21:45:00', '05:45:00', 0, 16),
('2024-10-07', 'Feeling refreshed and motivated', 'Smoothie bowl', 'Swimming', '22:15:00', '06:45:00', 1, 17),
('2024-10-07', 'Worked on some coping strategies', 'Soup and bread', 'Pilates', '23:00:00', '07:15:00', 0, 18),
('2024-10-07', 'Therapy session was helpful today', 'Grilled chicken wrap', 'Jogging', '21:30:00', '06:00:00', 1, 19),
('2024-10-07', 'Worked on focus techniques', 'Pasta and vegetables', 'Stretching', '22:30:00', '07:00:00', 0, 11),
('2024-10-08', 'Feeling calm today', 'Fruit salad', 'Yoga', '23:15:00', '07:15:00', 0, 12),
('2024-10-08', 'Had some struggles, but feeling okay', 'Grilled veggies', 'Walking', '22:00:00', '06:30:00', 1, 13),
('2024-10-08', 'Focused on breathing exercises', 'Chicken and rice', 'Running', '21:45:00', '06:15:00', 0, 14),
('2024-10-08', 'Feeling positive and relaxed', 'Salmon with quinoa', 'Cycling', '22:30:00', '06:45:00', 0, 15),
('2024-10-08', 'Meditated for 20 minutes, felt good', 'Vegetable curry', 'Weightlifting', '23:00:00', '07:30:00', 1, 16);

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
('2024-10-01', 60, 'Stress', 4, 4),
('2024-10-02', 45, 'Anxiety', 4, 5),
('2024-10-03', 50, 'Depression', 4, 6),
('2024-10-04', 60, 'Trauma', 4, 7),
('2024-10-05', 55, 'Mental Health Check', 4, 8),
('2024-10-06', 40, 'Family Issues', 4, 9),
('2024-10-07', 60, 'Addiction', 4, 10),
('2024-10-08', 30, 'Behavioral', 4, 11),
('2024-10-09', 50, 'Relationship', 4, 12),
('2024-10-10', 45, 'Stress Management', 4, 13),
('2024-10-11', 60, 'Career Stress', 5, 15),
('2024-10-12', 35, 'Phobia', 5, 28),
('2024-10-13', 60, 'Anger Issues', 6, 16),
('2024-10-14', 50, 'Trauma', 6, 29),
('2024-10-15', 55, 'Depression', 7, 17),
('2024-10-16', 60, 'Family Therapy', 8, 18),
('2024-10-17', 45, 'Work-Related Stress', 8, 30),
('2024-10-18', 50, 'Trauma Counseling', 9, 19),
('2024-10-19', 40, 'Phobia Treatment', 11, 20),
('2024-10-20', 55, 'Grief Counseling', 11, 33),
('2024-10-21', 35, 'Relationship Issues', 12, 21),
('2024-10-22', 60, 'Depression', 12, 31),
('2024-10-23', 45, 'Anger Management', 13, 22),
('2024-10-24', 50, 'Panic Disorder', 14, 23),
('2024-10-25', 55, 'Anxiety', 15, 24),
('2024-10-26', 60, 'Trauma', 16, 25),
('2024-10-27', 45, 'Mental Health Check', 17, 26),
('2024-10-28', 40, 'Behavioral Therapy', 18, 27),
('2024-10-29', 50, 'Stress Management', 19, 32),
('2024-10-30', 60, 'Anxiety Management', 4, 4);

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