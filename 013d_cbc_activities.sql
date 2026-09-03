-- ================================================================
-- 013d: CBC Sample Data - Portfolio, Projects & Holistic Ratings
-- Sunrise Academy (branch_id=3, session_id=10)
-- Run AFTER 013c_cbc_assessments.sql
-- ================================================================
-- NOTE: Holistic indicator IDs 1-21 assume sequential auto-increment
--       from 008_holistic_profile.sql seed (3 indicators × 7 domains)
--       Domain 1 (Communication): indicators 1-3
--       Domain 2 (Creativity):    indicators 4-6
--       Domain 3 (Critical Think):indicators 7-9
--       Domain 4 (Citizenship):   indicators 10-12
--       Domain 5 (Digital Lit):   indicators 13-15
--       Domain 6 (Learning-Learn):indicators 16-18
--       Domain 7 (Physical):      indicators 19-21
-- ================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ================================================================
-- SECTION 10: PORTFOLIO ENTRIES
-- Students: 1,2,3,5,6,20,24,32,36,40,44,55 (top performers)
-- Strands 100-169 seeded in 013b; LAs 100-124 seeded in sample_school_data.sql
-- created_by = teacher for the class
-- ================================================================

INSERT INTO `cbc_portfolio` (`student_id`,`learning_area_id`,`strand_id`,`title`,`description`,`competency_level`,`evidence_file`,`entry_date`,`session_id`,`branch_id`,`created_by`) VALUES

-- Brian (1) Grade 3 — Literacy and Environmental
(1,100,100,'My Favourite Story','Brian wrote and illustrated a short story about a lion and a mouse showing phonemic awareness and creativity.','EE',NULL,'2026-02-10',10,3,101),
(1,104,108,'Visit to the Nature Park','Portfolio of observations made during a school trip to Karura Forest; includes sketches and a written report.','ME',NULL,'2026-03-15',10,3,101),
(1,107,134,'Kalimba Performance','Brian performed a traditional Kenyan song on a kalimba at the class music concert.','EE',NULL,'2026-04-05',10,3,101),

-- Cynthia (2) Grade 1 — Reading and Math
(2,102,112,'My ABC Book','Cynthia created an illustrated alphabet book with one word and picture per letter.','EE',NULL,'2026-02-20',10,3,105),
(2,103,118,'Counting Patterns','Cynthia arranged bottle tops in skip-counting patterns of 2s and 5s and photographed each pattern.','ME',NULL,'2026-03-12',10,3,105),

-- Kevin (3) Grade 4 — English and Maths
(3,108,136,'Creative Writing: The Market','Kevin wrote a descriptive essay about a busy Kenyan market using varied sentence structures.','EE',NULL,'2026-02-18',10,3,102),
(3,110,148,'Fractions in Real Life','Kevin created a poster showing equivalent fractions using folded paper and food items.','EE',NULL,'2026-03-20',10,3,102),
(3,115,168,'Athletics Training Diary','Kevin logged his 100m sprint times over 8 weeks, showing consistent improvement.','EE',NULL,'2026-04-10',10,3,102),

-- Cynthia G5 (5) — Science and Maths
(5,111,152,'Plant Growth Experiment','Cynthia planted beans under different light conditions and recorded growth daily for 3 weeks.','EE',NULL,'2026-02-22',10,3,102),
(5,110,148,'Mental Maths Challenges','Collection of 20 self-created mental maths puzzles with solutions.','EE',NULL,'2026-03-28',10,3,102),
(5,115,168,'Cross Country Race Report','Cynthia finished 2nd in the inter-class cross country race; includes her training plan.','EE',NULL,'2026-04-18',10,3,102),

-- Grade 7 student 6 — English, Science, Creative Arts
(6,117,100,'Argumentative Essay: Technology','An essay arguing for increased technology access in rural Kenyan schools.','EE',NULL,'2026-02-25',10,3,103),
(6,120,156,'Photosynthesis Diagram','A hand-drawn labelled diagram of the photosynthesis process with a written explanation.','EE',NULL,'2026-03-18',10,3,103),
(6,124,164,'Dance Choreography','Cynthia choreographed and performed a traditional Kikuyu dance for the school assembly.','EE',NULL,'2026-04-22',10,3,103),

-- Rose (20) Grade 3 — Exceptional literacy student
(20,100,100,'Oral Story: The Hare and the Hyena','Performed an original version of a Kenyan folk tale to the class.','EE',NULL,'2026-03-05',10,3,101),
(20,101,106,'Kiswahili Poem: Mto wa Amani','Composed and recited an original Kiswahili poem about peace.','EE',NULL,'2026-04-02',10,3,101),

-- Alice (24) Grade 4 — Art and Social Studies star
(24,114,162,'Batik Fabric Design','Alice created a batik design using traditional Kenyan patterns representing her community.','EE',NULL,'2026-03-10',10,3,102),
(24,112,156,'Community Map Project','Detailed hand-drawn map of the school neighbourhood with legend and compass rose.','EE',NULL,'2026-04-08',10,3,102),

-- Grace (32) Grade 6 — Visual arts talent
(32,114,162,'Landscape Painting in Oils','A landscape painting depicting Mount Kenya at sunrise.','EE',NULL,'2026-02-28',10,3,102),
(32,108,136,'Short Story: Drought and Hope','A short story exploring climate change and community resilience.','EE',NULL,'2026-04-01',10,3,102),

-- Peter (36) Grade 7 — Pre-Technical talent
(36,122,160,'Simple Machine Model','Peter built a working pulley model from locally available materials.','EE',NULL,'2026-03-22',10,3,103),
(36,119,148,'Algebra Story Problems','Peter created 15 real-life algebra word problems related to farming.','EE',NULL,'2026-04-15',10,3,103),

-- James (40) Grade 8 — Social Studies and Dance
(40,121,156,'History of Luo Community','A research project on the cultural heritage of the Luo people.','EE',NULL,'2026-03-08',10,3,103),
(40,124,164,'Traditional Dance Festival','James led a group of students in performing Ohangla dance at the annual school festival.','EE',NULL,'2026-04-20',10,3,103),

-- Timothy (44) Grade 9 — Top academic student
(44,117,136,'Persuasive Speech: Climate Action','Delivered a 5-minute persuasive speech on climate action at the school debate.','EE',NULL,'2026-02-15',10,3,103),
(44,119,148,'Mathematics Exploration: Fibonacci','Research paper on Fibonacci sequences found in nature with local examples.','EE',NULL,'2026-03-30',10,3,103),
(44,121,156,'Civic Project: School Parliament','Timothy initiated and led the school student parliament for Term 1.','EE',NULL,'2026-04-25',10,3,103),

-- Amina (55) PP2 — Most creative pre-primary
(55,107,134,'My Favourite Colours Collage','Amina created a colourful collage using cut paper, fabric scraps and natural materials.','EE',NULL,'2026-03-01',10,3,105),
(55,100,100,'Telling Time: A Play','Amina performed a short skit with two friends about morning routines.','EE',NULL,'2026-03-25',10,3,105);

-- ================================================================
-- SECTION 11: CLASS PROJECTS
-- 3 projects per school level (lower primary, upper primary, junior secondary)
-- ================================================================

INSERT INTO `cbc_projects` (`name`,`description`,`learning_area_id`,`class_id`,`section_id`,`due_date`,`max_score`,`session_id`,`branch_id`) VALUES
-- Lower Primary projects (Grades 1-3)
('Our Community Helpers','Students draw and present on a community helper of their choice',100,102,10,'2026-03-28',100.00,10,3),
('Counting Nature Walk','Collect and count natural objects, sort into groups and present findings',103,104,10,'2026-04-10',100.00,10,3),
('Healthy Eating Plate','Create a model healthy plate using clay or drawings showing food groups',105,103,11,'2026-03-20',100.00,10,3),
-- Upper Primary projects (Grades 4-6)
('Water Conservation Poster','Design a campaign poster urging water conservation in the community',111,105,10,'2026-04-05',100.00,10,3),
('Kenyan Entrepreneurs Research','Research a successful Kenyan entrepreneur and present findings',108,106,10,'2026-03-30',100.00,10,3),
('Agri Science: Soil Types','Collect 3 soil samples, test water retention and present results',116,107,10,'2026-04-18',100.00,10,3),
-- Junior Secondary projects (Grades 7-9)
('Integrated Science: Ecosystems','Build a terrarium demonstrating a local ecosystem with food web',120,108,10,'2026-04-08',100.00,10,3),
('Pre-Technical: Cardboard Chair','Design and build a cardboard chair that supports a textbook',122,109,11,'2026-04-15',100.00,10,3),
('Social Studies: Kenya@60','Research project on 60 years of Kenya independence; newspaper-style write-up',121,110,10,'2026-04-22',100.00,10,3);

-- ================================================================
-- PROJECT SCORES
-- Project IDs: auto-increment from whatever current max is.
-- Using a temp variable approach for safety.
-- ================================================================

-- Grab the IDs of the projects we just inserted
SET @lp1 = (SELECT id FROM cbc_projects WHERE name='Our Community Helpers' AND branch_id=3 LIMIT 1);
SET @lp2 = (SELECT id FROM cbc_projects WHERE name='Counting Nature Walk' AND branch_id=3 LIMIT 1);
SET @lp3 = (SELECT id FROM cbc_projects WHERE name='Healthy Eating Plate' AND branch_id=3 LIMIT 1);
SET @up1 = (SELECT id FROM cbc_projects WHERE name='Water Conservation Poster' AND branch_id=3 LIMIT 1);
SET @up2 = (SELECT id FROM cbc_projects WHERE name='Kenyan Entrepreneurs Research' AND branch_id=3 LIMIT 1);
SET @up3 = (SELECT id FROM cbc_projects WHERE name='Agri Science: Soil Types' AND branch_id=3 LIMIT 1);
SET @js1 = (SELECT id FROM cbc_projects WHERE name='Integrated Science: Ecosystems' AND branch_id=3 LIMIT 1);
SET @js2 = (SELECT id FROM cbc_projects WHERE name='Pre-Technical: Cardboard Chair' AND branch_id=3 LIMIT 1);
SET @js3 = (SELECT id FROM cbc_projects WHERE name='Kenya@60' AND branch_id=3 LIMIT 1);

-- LP1: Our Community Helpers (Grade 1 East, students 2,11,12,13,14)
INSERT IGNORE INTO `cbc_project_scores` (`project_id`,`student_id`,`score`,`competency_level`,`remarks`,`branch_id`) VALUES
(@lp1,2,95.00,'EE','Outstanding presentation; depicted a nurse with detailed labelling',3),
(@lp1,11,78.00,'ME','Good drawing of a teacher with clear speech',3),
(@lp1,12,65.00,'ME','Presented a market trader; needs more detail',3),
(@lp1,13,88.00,'ME','Depicted a police officer with a well-structured oral',3),
(@lp1,14,82.00,'ME','Farmer presentation was creative and practical',3);

-- LP2: Counting Nature Walk (Grade 3 East, students 1,19,20,21,22)
INSERT IGNORE INTO `cbc_project_scores` (`project_id`,`student_id`,`score`,`competency_level`,`remarks`,`branch_id`) VALUES
(@lp2,1,80.00,'ME','Good collection and sorting; counts accurate to 50',3),
(@lp2,19,85.00,'ME','Well organised; creative presentation with leaf prints',3),
(@lp2,20,98.00,'EE','Exceptional! Sorted 10 categories and graphed the results',3),
(@lp2,21,70.00,'ME','Sorted correctly; oral explanation needed prompting',3),
(@lp2,22,92.00,'EE','Excellent maths connection; explained place value',3);

-- LP3: Healthy Eating Plate (Grade 2 West, students 4,15,16,17,18)
INSERT IGNORE INTO `cbc_project_scores` (`project_id`,`student_id`,`score`,`competency_level`,`remarks`,`branch_id`) VALUES
(@lp3,4,75.00,'ME','Good clay model; identified 3 food groups',3),
(@lp3,15,90.00,'EE','Outstanding! Included all food groups and explained nutrients',3),
(@lp3,16,88.00,'ME','Creative collage with correct food groups labelled',3),
(@lp3,17,72.00,'ME','Good effort; needed guidance on food categories',3),
(@lp3,18,68.00,'ME','Basic plate with correct proportions',3);

-- UP1: Water Conservation Poster (Grade 4 East, students 3,23,24,25,26)
INSERT IGNORE INTO `cbc_project_scores` (`project_id`,`student_id`,`score`,`competency_level`,`remarks`,`branch_id`) VALUES
(@up1,3,92.00,'EE','Poster included statistics and compelling slogans',3),
(@up1,23,88.00,'ME','Good infographic with local water sources mapped',3),
(@up1,24,96.00,'EE','Outstanding visual design; won class display',3),
(@up1,25,74.00,'ME','Clear message; limited visual detail',3),
(@up1,26,85.00,'ME','Good research on local water scarcity',3);

-- UP2: Kenyan Entrepreneurs (Grade 5 East, students 5,27,28,29,30)
INSERT IGNORE INTO `cbc_project_scores` (`project_id`,`student_id`,`score`,`competency_level`,`remarks`,`branch_id`) VALUES
(@up2,5,97.00,'EE','Researched Mama Ngina Kenyatta; excellent oral presentation',3),
(@up2,27,80.00,'ME','Good research on a local business owner',3),
(@up2,28,88.00,'ME','Researched a tech entrepreneur; well-structured report',3),
(@up2,29,70.00,'ME','Basic research; needed more depth on challenges faced',3),
(@up2,30,83.00,'ME','Good presentation on agri-business entrepreneur',3);

-- UP3: Agri Science: Soil Types (Grade 6 East, students 31,32,33,34,35)
INSERT IGNORE INTO `cbc_project_scores` (`project_id`,`student_id`,`score`,`competency_level`,`remarks`,`branch_id`) VALUES
(@up3,31,82.00,'ME','Correct procedure; compared loam and clay',3),
(@up3,32,90.00,'EE','Excellent experiment design and data presentation',3),
(@up3,33,78.00,'ME','Good results; conclusion needed more detail',3),
(@up3,34,88.00,'ME','Detailed write-up with annotated diagrams',3),
(@up3,35,72.00,'ME','Basic experiment completed correctly',3);

-- JS1: Integrated Science: Ecosystems (Grade 7 East, students 6,36,37,38,39)
INSERT IGNORE INTO `cbc_project_scores` (`project_id`,`student_id`,`score`,`competency_level`,`remarks`,`branch_id`) VALUES
(@js1,6,95.00,'EE','Beautiful terrarium with accurate food web diagram',3),
(@js1,36,88.00,'ME','Good ecosystem model; Pre-Tech skills evident in construction',3),
(@js1,37,82.00,'ME','Well-researched ecosystem; clear labels',3),
(@js1,38,70.00,'ME','Basic terrarium; food web had one error',3),
(@js1,39,90.00,'EE','Incorporated agricultural plants; excellent data recording',3);

-- JS2: Pre-Technical: Cardboard Chair (Grade 8 West, students 7,40,41,42,43)
INSERT IGNORE INTO `cbc_project_scores` (`project_id`,`student_id`,`score`,`competency_level`,`remarks`,`branch_id`) VALUES
(@js2,7,85.00,'ME','Functional chair design; good material efficiency',3),
(@js2,40,78.00,'ME','Stable chair; creative decoration',3),
(@js2,41,96.00,'EE','Most structurally sound design in class',3),
(@js2,42,80.00,'ME','Good engineering; slight lean corrected',3),
(@js2,43,72.00,'ME','Basic design; functional but plain',3);

-- JS3: Kenya@60 (Grade 9 East, students 44,45,46,47,48)
INSERT IGNORE INTO `cbc_project_scores` (`project_id`,`student_id`,`score`,`competency_level`,`remarks`,`branch_id`) VALUES
(@js3,44,98.00,'EE','Outstanding journalism; included interviews and infographics',3),
(@js3,45,84.00,'ME','Well-researched article with good structure',3),
(@js3,46,88.00,'ME','Strong article on Kenya\'s scientific achievements',3),
(@js3,47,80.00,'ME','Good article; well-cited sources',3),
(@js3,48,75.00,'ME','Decent write-up; needed more historical depth',3);

-- ================================================================
-- SECTION 12: HOLISTIC RATINGS
-- Indicators 1-21 (from 008_holistic_profile.sql sequential seed)
-- Selected students across all levels, Terms 1 and 2
-- Ratings: EE / ME / AE (same CBC competency scale)
-- ================================================================

-- Term 1 holistic ratings — Grade 3-9 students (covers upper primary and junior)
INSERT IGNORE INTO `cbc_holistic_ratings` (`branch_id`,`student_id`,`exam_id`,`indicator_id`,`rating`,`remarks`,`created_by`) VALUES
-- Brian (1) Grade 3 T1
(3,1,10,1,'EE','Expresses himself very well in both English and Kiswahili',101),
(3,1,10,2,'ME','Listens attentively; occasionally interrupts',101),
(3,1,10,3,'EE','Natural group leader; supports struggling peers',101),
(3,1,10,4,'EE','Very creative stories and drawings',101),
(3,1,10,5,'ME','Takes appropriate creative risks',101),
(3,1,10,6,'EE','Imagination evident in all written work',101),
(3,1,10,7,'ME','Identifies problems well; solutions need more depth',101),
(3,1,10,8,'ME','Makes reasonable decisions when given guidance',101),
(3,1,10,9,'ME','Evidence review improving',101),
(3,1,10,10,'ME','Respectful and well-mannered',101),
(3,1,10,11,'EE','Always participates in school activities',101),
(3,1,10,12,'ME','Good national values demonstrated',101),
(3,1,10,13,'ME','Uses classroom tablet effectively',101),
(3,1,10,14,'ME','Navigates appropriate websites',101),
(3,1,10,15,'ME','Online safety rules understood',101),
(3,1,10,16,'ME','Sets simple learning targets',101),
(3,1,10,17,'ME','Manages time adequately',101),
(3,1,10,18,'ME','Reflects on work after feedback',101),
(3,1,10,19,'EE','Top athlete in class',101),
(3,1,10,20,'ME','Good hygiene habits',101),
(3,1,10,21,'ME','Balanced diet choices',101),

-- Kevin (3) Grade 4 T1
(3,3,10,1,'EE','Articulate writer and speaker',102),
(3,3,10,2,'ME','Active listener',102),
(3,3,10,3,'ME','Works well in pairs; improving in groups',102),
(3,3,10,4,'ME','Creative in English writing',102),
(3,3,10,5,'ME','Takes some initiative in projects',102),
(3,3,10,6,'ME','Good imagination in stories',102),
(3,3,10,7,'ME','Identifies problems systematically',102),
(3,3,10,8,'EE','Excellent decision-making',102),
(3,3,10,9,'ME','Evaluates evidence carefully',102),
(3,3,10,10,'ME','Respectful of peers and teachers',102),
(3,3,10,11,'ME','Participates in school events',102),
(3,3,10,12,'ME','Good citizenship values',102),
(3,3,10,13,'ME','Good use of digital tools',102),
(3,3,10,14,'ME','Responsible online behaviour',102),
(3,3,10,15,'ME','Demonstrates digital ethics',102),
(3,3,10,16,'EE','Sets and achieves learning goals',102),
(3,3,10,17,'EE','Excellent time management',102),
(3,3,10,18,'ME','Reflects meaningfully on learning',102),
(3,3,10,19,'ME','Active in PE and sports',102),
(3,3,10,20,'EE','Excellent personal hygiene',102),
(3,3,10,21,'ME','Good dietary choices',102),

-- Cynthia (5) Grade 5 T1
(3,5,10,1,'EE','Outstanding communication skills',102),
(3,5,10,2,'EE','Exemplary listener; respects all views',102),
(3,5,10,3,'EE','Natural team builder',102),
(3,5,10,4,'EE','Highly creative in all subjects',102),
(3,5,10,5,'EE','Fearless creative risk-taker',102),
(3,5,10,6,'EE','Work consistently demonstrates imagination',102),
(3,5,10,7,'EE','Analyses problems thoroughly',102),
(3,5,10,8,'EE','Makes excellent informed decisions',102),
(3,5,10,9,'EE','Always evaluates before concluding',102),
(3,5,10,10,'EE','Role model for peers in values',102),
(3,5,10,11,'EE','Class representative and ambassador',102),
(3,5,10,12,'EE','Champions national values',102),
(3,5,10,13,'ME','Good digital tool use',102),
(3,5,10,14,'ME','Responsible online user',102),
(3,5,10,15,'ME','Understands digital safety',102),
(3,5,10,16,'EE','Sets ambitious learning goals',102),
(3,5,10,17,'EE','Excellent self-organisation',102),
(3,5,10,18,'EE','Deep reflection on learning',102),
(3,5,10,19,'EE','Top class athlete',102),
(3,5,10,20,'ME','Good hygiene practices',102),
(3,5,10,21,'ME','Healthy eating habits',102),

-- Grade 7 student (6) T1
(3,6,10,1,'EE','Exceptional communicator; leads class discussions',103),
(3,6,10,2,'EE','Outstanding active listener',103),
(3,6,10,3,'EE','Leads group projects effectively',103),
(3,6,10,4,'EE','Highly imaginative writer and performer',103),
(3,6,10,5,'EE','Takes bold creative risks; always tries new approaches',103),
(3,6,10,6,'EE','Every piece of work shows originality',103),
(3,6,10,7,'EE','Analyses complex problems with maturity',103),
(3,6,10,8,'EE','Decisions are well-reasoned and evidence-based',103),
(3,6,10,9,'EE','Evaluates multiple sources of evidence',103),
(3,6,10,10,'EE','Model citizen; respected by all',103),
(3,6,10,11,'EE','Initiates and leads community projects',103),
(3,6,10,12,'EE','Champions Kenyan national values',103),
(3,6,10,13,'ME','Good digital literacy skills',103),
(3,6,10,14,'ME','Responsible internet user',103),
(3,6,10,15,'ME','Understands online safety well',103),
(3,6,10,16,'EE','Sets and consistently achieves goals',103),
(3,6,10,17,'EE','Excellent time-management and planning',103),
(3,6,10,18,'EE','Reflects deeply after every task',103),
(3,6,10,19,'ME','Active in sports and PE',103),
(3,6,10,20,'ME','Good hygiene standards',103),
(3,6,10,21,'ME','Healthy lifestyle choices',103),

-- Timothy (44) Grade 9 T1
(3,44,10,1,'EE','School debate captain; exceptional communication',103),
(3,44,10,2,'EE','Exemplary listener and discussion facilitator',103),
(3,44,10,3,'EE','Collaborates and leads groups with maturity',103),
(3,44,10,4,'EE','Mathematics and writing show exceptional creativity',103),
(3,44,10,5,'EE','Initiated student parliament; takes bold initiative',103),
(3,44,10,6,'EE','All work reflects deep imaginative engagement',103),
(3,44,10,7,'EE','Systematic thinker; mentors peers in problem-solving',103),
(3,44,10,8,'EE','Reasoned and mature decision-maker',103),
(3,44,10,9,'EE','Evaluates evidence with great precision',103),
(3,44,10,10,'EE','Integrity and respect are hallmarks of character',103),
(3,44,10,11,'EE','School parliament leader; active in all activities',103),
(3,44,10,12,'EE','Outstanding national values ambassador',103),
(3,44,10,13,'EE','Uses digital tools expertly for research',103),
(3,44,10,14,'EE','Critically evaluates online information',103),
(3,44,10,15,'EE','Digital ethics role model for peers',103),
(3,44,10,16,'EE','Sets high learning goals and exceeds them',103),
(3,44,10,17,'EE','Exceptional self-management and planning',103),
(3,44,10,18,'EE','Insightful and regular self-reflection',103),
(3,44,10,19,'ME','Active sports participant',103),
(3,44,10,20,'ME','Good personal hygiene',103),
(3,44,10,21,'ME','Good healthy lifestyle choices',103),

-- A few more students at ME level (G8 students)
(3,7,10,1,'ME','Communicates adequately; writing improving',103),
(3,7,10,2,'ME','Good listener',103),
(3,7,10,3,'ME','Works well in groups',103),
(3,7,10,4,'ME','Shows creativity in agri projects',103),
(3,7,10,5,'ME','Takes initiative in practical work',103),
(3,7,10,6,'ME','Imagination shown in design projects',103),
(3,7,10,7,'ME','Identifies problems methodically',103),
(3,7,10,8,'ME','Makes reasonable decisions',103),
(3,7,10,9,'ME','Evaluates evidence with guidance',103),
(3,7,10,10,'ME','Respectful and responsible',103),
(3,7,10,11,'ME','Participates in school events',103),
(3,7,10,12,'ME','Good national values',103),
(3,7,10,13,'ME','Uses digital tools for learning',103),
(3,7,10,14,'ME','Responsible internet use',103),
(3,7,10,15,'ME','Understands digital safety rules',103),
(3,7,10,16,'ME','Sets learning targets with teacher',103),
(3,7,10,17,'ME','Manages time adequately',103),
(3,7,10,18,'ME','Reflects after feedback',103),
(3,7,10,19,'EE','Outstanding in agriculture practicals',103),
(3,7,10,20,'ME','Good hygiene',103),
(3,7,10,21,'ME','Good lifestyle choices',103),

-- James (40) Grade 8 T1
(3,40,10,1,'EE','Excellent verbal communicator and writer',103),
(3,40,10,2,'ME','Good listener',103),
(3,40,10,3,'EE','Strong collaborative leader',103),
(3,40,10,4,'EE','Creative performer and writer',103),
(3,40,10,5,'ME','Takes initiative in performance projects',103),
(3,40,10,6,'EE','Dance performances show deep imagination',103),
(3,40,10,7,'ME','Identifies problems well',103),
(3,40,10,8,'ME','Makes good decisions',103),
(3,40,10,9,'ME','Evaluates evidence adequately',103),
(3,40,10,10,'EE','Outstanding community values',103),
(3,40,10,11,'EE','Very active in school community',103),
(3,40,10,12,'EE','Strong sense of cultural citizenship',103),
(3,40,10,13,'ME','Good digital skills',103),
(3,40,10,14,'ME','Responsible online',103),
(3,40,10,15,'ME','Good digital safety habits',103),
(3,40,10,16,'ME','Sets learning goals',103),
(3,40,10,17,'ME','Good time management',103),
(3,40,10,18,'ME','Reflects on performance',103),
(3,40,10,19,'ME','Active in sports and dance',103),
(3,40,10,20,'ME','Good hygiene',103),
(3,40,10,21,'ME','Healthy food choices',103);

-- Term 2 holistic ratings (key students — showing improvement)
INSERT IGNORE INTO `cbc_holistic_ratings` (`branch_id`,`student_id`,`exam_id`,`indicator_id`,`rating`,`remarks`,`created_by`) VALUES
-- Brian (1) T2 — slight improvement in decision-making
(3,1,11,1,'EE','Consistently expresses himself with clarity and confidence',101),
(3,1,11,2,'ME','Improved at letting others finish speaking',101),
(3,1,11,3,'EE','Strong leader in all group work',101),
(3,1,11,4,'EE','Creativity outstanding this term',101),
(3,1,11,5,'EE','Now takes risks with creative approaches',101),
(3,1,11,6,'EE','Every piece of work shows imagination',101),
(3,1,11,7,'ME','Problem identification clear',101),
(3,1,11,8,'EE','Decision-making improved greatly',101),
(3,1,11,9,'ME','Evaluates evidence with some independence',101),
(3,1,11,10,'ME','Remains respectful at all times',101),
(3,1,11,11,'EE','Volunteers for all school activities',101),
(3,1,11,12,'ME','Good national identity demonstrated',101),
(3,1,11,13,'ME','Uses tablet confidently',101),
(3,1,11,14,'ME','Good internet use habits',101),
(3,1,11,15,'ME','Digital safety rules followed',101),
(3,1,11,16,'EE','Now sets weekly learning targets',101),
(3,1,11,17,'ME','Time management improving',101),
(3,1,11,18,'EE','Excellent reflective learner',101),
(3,1,11,19,'EE','Sports captain for term',101),
(3,1,11,20,'ME','Excellent hygiene habits',101),
(3,1,11,21,'ME','Good diet choices maintained',101),

-- Timothy (44) T2 — consistently excellent
(3,44,11,1,'EE','School debate finalist; communication at senior level',103),
(3,44,11,2,'EE','Respected facilitator of class discussions',103),
(3,44,11,3,'EE','Led multiple successful group projects',103),
(3,44,11,4,'EE','Research projects show exceptional original thinking',103),
(3,44,11,5,'EE','Continues to take bold creative and intellectual risks',103),
(3,44,11,6,'EE','Fibonacci research paper demonstrated outstanding imagination',103),
(3,44,11,7,'EE','Systematic analytical thinker',103),
(3,44,11,8,'EE','Mature decision-maker under pressure',103),
(3,44,11,9,'EE','Evaluates complex evidence independently',103),
(3,44,11,10,'EE','School parliament president; integrity exemplary',103),
(3,44,11,11,'EE','Led climate action project school-wide',103),
(3,44,11,12,'EE','Top citizenship ambassador',103),
(3,44,11,13,'EE','Uses digital tools for research and presentation',103),
(3,44,11,14,'EE','Teaches peers responsible internet use',103),
(3,44,11,15,'EE','Digital ethics example for whole school',103),
(3,44,11,16,'EE','Exceeded all personal learning targets',103),
(3,44,11,17,'EE','Organises peers and manages own time perfectly',103),
(3,44,11,18,'EE','Deep and consistent self-reflection',103),
(3,44,11,19,'ME','Maintains active sports participation',103),
(3,44,11,20,'ME','Good hygiene maintained',103),
(3,44,11,21,'ME','Healthy lifestyle maintained',103),

-- Cynthia G5 (5) T2
(3,5,11,1,'EE','Communication continues to be outstanding',102),
(3,5,11,2,'EE','Active and respectful listener',102),
(3,5,11,3,'EE','Excellent team collaborator',102),
(3,5,11,4,'EE','Creativity at highest level',102),
(3,5,11,5,'EE','Initiates new ideas without prompting',102),
(3,5,11,6,'EE','Plant experiment showed deep imagination',102),
(3,5,11,7,'EE','Critical thinker across all subjects',102),
(3,5,11,8,'EE','Informed decisions made consistently',102),
(3,5,11,9,'EE','Evidence-based thinking exemplary',102),
(3,5,11,10,'EE','School values champion',102),
(3,5,11,11,'EE','Leads in all school events',102),
(3,5,11,12,'EE','Champions national citizenship',102),
(3,5,11,13,'ME','Good digital skills developing',102),
(3,5,11,14,'ME','Responsible digital citizen',102),
(3,5,11,15,'ME','Online safety rules modelled',102),
(3,5,11,16,'EE','Self-directed learner; exceeds own targets',102),
(3,5,11,17,'EE','Excellent self-management',102),
(3,5,11,18,'EE','Reflects and adjusts learning strategy',102),
(3,5,11,19,'EE','Cross-country 2nd place; top athlete',102),
(3,5,11,20,'ME','Good hygiene habits',102),
(3,5,11,21,'ME','Healthy eating maintained',102);

SET FOREIGN_KEY_CHECKS = 1;

-- ================================================================
-- END OF 013d_cbc_activities.sql
-- All 4 CBC sample data files are now complete.
-- Run order: 013a → 013b → 013c → 013d
-- ================================================================
