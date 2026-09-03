-- ================================================================
-- 013c: CBC Sample Data - Assessments (Term 1 & 2) + Behaviour
-- Sunrise Academy (branch_id=3, session_id=10)
-- Exam IDs: 10=Term 1, 11=Term 2
-- Teachers: 101=Mary Wanjiku, 102=Peter Ochieng,
--           103=Grace Akinyi, 104=David Kipchoge, 105=Faith Njeri
-- Run AFTER 013b_cbc_strands.sql
-- ================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ================================================================
-- SECTION 7: CBC ASSESSMENTS - TERM 1 (exam_id = 10)
-- Lower Primary students (classes 100-104): LAs 100-107
-- Upper Primary students (classes 105-107): LAs 108-116
-- Junior Secondary students (classes 108-110): LAs 117-124
-- assessed_by: 105=Faith Njeri (lower), 102=Peter (upper), 103=Grace (junior)
-- ================================================================

-- PP1 East (class 100, section 10): students 49-53 | LAs 100-107
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(49,10,100,'ME',100,10,'Good oral participation',105,10,3),
(49,10,101,'ME',100,10,'Learning Kiswahili sounds well',105,10,3),
(49,10,102,'EE',100,10,'Speaks English confidently',105,10,3),
(49,10,103,'ME',100,10,'Counts to 20 correctly',105,10,3),
(49,10,104,'ME',100,10,'Knows living vs non-living things',105,10,3),
(49,10,105,'EE',100,10,'Very hygienic and health conscious',105,10,3),
(49,10,106,'ME',100,10,'Participates in prayers well',105,10,3),
(49,10,107,'ME',100,10,'Enjoys movement activities',105,10,3),

(50,10,100,'AE',100,10,'Needs more oral practice',105,10,3),
(50,10,101,'ME',100,10,'Recognises Kiswahili vowels',105,10,3),
(50,10,102,'AE',100,10,'Still learning letter sounds',105,10,3),
(50,10,103,'ME',100,10,'Counts confidently to 10',105,10,3),
(50,10,104,'AE',100,10,'Needs support identifying nature',105,10,3),
(50,10,105,'ME',100,10,'Practises handwashing regularly',105,10,3),
(50,10,106,'EE',100,10,'Very spiritually engaged',105,10,3),
(50,10,107,'ME',100,10,'Enjoys singing and dancing',105,10,3),

(51,10,100,'EE',100,10,'Outstanding oral expression',105,10,3),
(51,10,101,'EE',100,10,'Excellent Kiswahili speaker',105,10,3),
(51,10,102,'ME',100,10,'Reads simple English words',105,10,3),
(51,10,103,'EE',100,10,'Strong number sense',105,10,3),
(51,10,104,'ME',100,10,'Good environmental awareness',105,10,3),
(51,10,105,'ME',100,10,'Maintains good hygiene',105,10,3),
(51,10,106,'ME',100,10,'Respectful in RE sessions',105,10,3),
(51,10,107,'EE',100,10,'Very creative and energetic',105,10,3),

(52,10,100,'ME',100,10,'Communicates ideas clearly',105,10,3),
(52,10,101,'AE',100,10,'Working on Kiswahili sounds',105,10,3),
(52,10,102,'ME',100,10,'Reads simple sentences',105,10,3),
(52,10,103,'AE',100,10,'Needs counting support',105,10,3),
(52,10,104,'ME',100,10,'Identifies common plants',105,10,3),
(52,10,105,'ME',100,10,'Good food choices made',105,10,3),
(52,10,106,'ME',100,10,'Participates in class devotion',105,10,3),
(52,10,107,'AE',100,10,'Needs encouragement in art',105,10,3),

(53,10,100,'ME',100,10,'Good listening and speaking',105,10,3),
(53,10,101,'ME',100,10,'Learning Kiswahili steadily',105,10,3),
(53,10,102,'ME',100,10,'Makes progress in phonics',105,10,3),
(53,10,103,'ME',100,10,'Understands basic addition',105,10,3),
(53,10,104,'EE',100,10,'Loves nature and animals',105,10,3),
(53,10,105,'ME',100,10,'Good hygiene habits',105,10,3),
(53,10,106,'ME',100,10,'Active in RE activities',105,10,3),
(53,10,107,'ME',100,10,'Participates well in PE',105,10,3);

-- PP2 East (class 101, section 10): students 54-58 | LAs 100-107
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(54,10,100,'ME',101,10,'Good phonological awareness',105,10,3),
(54,10,101,'ME',101,10,'Reads Kiswahili syllables well',105,10,3),
(54,10,102,'ME',101,10,'Recognises letter sounds',105,10,3),
(54,10,103,'ME',101,10,'Counts and writes to 50',105,10,3),
(54,10,104,'ME',101,10,'Knows food chains basics',105,10,3),
(54,10,105,'EE',101,10,'Excellent hygiene champion',105,10,3),
(54,10,106,'ME',101,10,'Respectful and prayerful',105,10,3),
(54,10,107,'ME',101,10,'Good coordination in PE',105,10,3),

(55,10,100,'EE',101,10,'Excellent oral storytelling',105,10,3),
(55,10,101,'EE',101,10,'Reads Kiswahili fluently',105,10,3),
(55,10,102,'ME',101,10,'Reads simple English books',105,10,3),
(55,10,103,'EE',101,10,'Adds and subtracts well',105,10,3),
(55,10,104,'ME',101,10,'Good environmental knowledge',105,10,3),
(55,10,105,'ME',101,10,'Understands nutrition well',105,10,3),
(55,10,106,'ME',101,10,'Active in moral lessons',105,10,3),
(55,10,107,'EE',101,10,'Very creative in art',105,10,3),

(56,10,100,'AE',101,10,'Shy; needs confidence building',105,10,3),
(56,10,101,'ME',101,10,'Progressing in Kiswahili',105,10,3),
(56,10,102,'AE',101,10,'Needs reading support',105,10,3),
(56,10,103,'ME',101,10,'Good number recognition',105,10,3),
(56,10,104,'ME',101,10,'Enjoys outdoor activities',105,10,3),
(56,10,105,'ME',101,10,'Practises good hygiene',105,10,3),
(56,10,106,'EE',101,10,'Very devout and respectful',105,10,3),
(56,10,107,'ME',101,10,'Active in music sessions',105,10,3),

(57,10,100,'ME',101,10,'Speaks clearly in class',105,10,3),
(57,10,101,'ME',101,10,'Reads Kiswahili with teacher',105,10,3),
(57,10,102,'EE',101,10,'Strong phonics foundation',105,10,3),
(57,10,103,'ME',101,10,'Confident with numbers',105,10,3),
(57,10,104,'ME',101,10,'Curious about environment',105,10,3),
(57,10,105,'ME',101,10,'Healthy eating choices',105,10,3),
(57,10,106,'ME',101,10,'Good values and conduct',105,10,3),
(57,10,107,'ME',101,10,'Participates in movement',105,10,3),

(58,10,100,'ME',101,10,'Good listening skills',105,10,3),
(58,10,101,'AE',101,10,'Learning Kiswahili steadily',105,10,3),
(58,10,102,'ME',101,10,'Reading progressing well',105,10,3),
(58,10,103,'AE',101,10,'Needs maths reinforcement',105,10,3),
(58,10,104,'ME',101,10,'Identifies local plants',105,10,3),
(58,10,105,'ME',101,10,'Maintains personal cleanliness',105,10,3),
(58,10,106,'ME',101,10,'Participates in devotion',105,10,3),
(58,10,107,'ME',101,10,'Enjoys creative activities',105,10,3);

-- Grade 1 East (class 102, section 10): students 2,11-14 | LAs 100-107
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
-- Cynthia (2) - existing student, good profile
(2,10,100,'EE',102,10,'Outstanding reader and speaker',105,10,3),
(2,10,101,'ME',102,10,'Good Kiswahili progress',105,10,3),
(2,10,102,'EE',102,10,'Excellent English foundation',105,10,3),
(2,10,103,'ME',102,10,'Solid number sense',105,10,3),
(2,10,104,'ME',102,10,'Curious about environment',105,10,3),
(2,10,105,'EE',102,10,'Excellent hygiene habits',105,10,3),
(2,10,106,'ME',102,10,'Active in RE',105,10,3),
(2,10,107,'EE',102,10,'Very creative learner',105,10,3),
-- James Adhiambo (11)
(11,10,100,'ME',102,10,'Speaks well in discussions',105,10,3),
(11,10,101,'EE',102,10,'Excellent Kiswahili speaker',105,10,3),
(11,10,102,'ME',102,10,'Reads simple sentences',105,10,3),
(11,10,103,'ME',102,10,'Good basic maths',105,10,3),
(11,10,104,'ME',102,10,'Knows local environment',105,10,3),
(11,10,105,'ME',102,10,'Good personal hygiene',105,10,3),
(11,10,106,'ME',102,10,'Participates in RE',105,10,3),
(11,10,107,'ME',102,10,'Enjoys physical activities',105,10,3),
-- Rose Njenga (12)
(12,10,100,'AE',102,10,'Needs oral practice',105,10,3),
(12,10,101,'ME',102,10,'Learning Kiswahili well',105,10,3),
(12,10,102,'ME',102,10,'Progressing in English',105,10,3),
(12,10,103,'AE',102,10,'Struggles with subtraction',105,10,3),
(12,10,104,'ME',102,10,'Identifies living things',105,10,3),
(12,10,105,'ME',102,10,'Good hygiene practices',105,10,3),
(12,10,106,'EE',102,10,'Very spiritual and kind',105,10,3),
(12,10,107,'ME',102,10,'Enjoys art activities',105,10,3),
-- Samuel Ochieng (13)
(13,10,100,'ME',102,10,'Communicates ideas well',105,10,3),
(13,10,101,'ME',102,10,'Good Kiswahili progress',105,10,3),
(13,10,102,'ME',102,10,'Reads with guidance',105,10,3),
(13,10,103,'EE',102,10,'Outstanding in maths',105,10,3),
(13,10,104,'ME',102,10,'Good environmental knowledge',105,10,3),
(13,10,105,'ME',102,10,'Practises good hygiene',105,10,3),
(13,10,106,'ME',102,10,'Participates in devotion',105,10,3),
(13,10,107,'ME',102,10,'Active in PE',105,10,3),
-- Faith Kimani (14)
(14,10,100,'ME',102,10,'Good oral expression',105,10,3),
(14,10,101,'ME',102,10,'Progressing in Kiswahili',105,10,3),
(14,10,102,'EE',102,10,'Excellent phonics skills',105,10,3),
(14,10,103,'ME',102,10,'Understands addition',105,10,3),
(14,10,104,'AE',102,10,'Needs environmental support',105,10,3),
(14,10,105,'ME',102,10,'Good nutrition choices',105,10,3),
(14,10,106,'ME',102,10,'Respectful in RE',105,10,3),
(14,10,107,'ME',102,10,'Enjoys music',105,10,3);

-- Grade 2 West (class 103, section 11): students 4,15-18 | LAs 100-107
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(4,10,100,'ME',103,11,'Good listening skills',105,10,3),
(4,10,101,'ME',103,11,'Steady Kiswahili progress',105,10,3),
(4,10,102,'ME',103,11,'Reads with support',105,10,3),
(4,10,103,'ME',103,11,'Good number work',105,10,3),
(4,10,104,'AE',103,11,'Needs environmental support',105,10,3),
(4,10,105,'ME',103,11,'Good hygiene habits',105,10,3),
(4,10,106,'ME',103,11,'Participates in RE',105,10,3),
(4,10,107,'ME',103,11,'Enjoys movement',105,10,3),
(15,10,100,'EE',103,11,'Excellent communicator',105,10,3),
(15,10,101,'ME',103,11,'Good Kiswahili reading',105,10,3),
(15,10,102,'ME',103,11,'Reads independently',105,10,3),
(15,10,103,'EE',103,11,'Strong maths foundation',105,10,3),
(15,10,104,'ME',103,11,'Knows plants and animals',105,10,3),
(15,10,105,'ME',103,11,'Healthy lifestyle habits',105,10,3),
(15,10,106,'ME',103,11,'Good moral values',105,10,3),
(15,10,107,'EE',103,11,'Loves sports and art',105,10,3),
(16,10,100,'ME',103,11,'Clear spoken communication',105,10,3),
(16,10,101,'EE',103,11,'Outstanding Kiswahili',105,10,3),
(16,10,102,'ME',103,11,'Good English reading',105,10,3),
(16,10,103,'ME',103,11,'Competent in maths',105,10,3),
(16,10,104,'ME',103,11,'Environmental awareness good',105,10,3),
(16,10,105,'EE',103,11,'Excellent hygiene ambassador',105,10,3),
(16,10,106,'ME',103,11,'Respectful in RE',105,10,3),
(16,10,107,'ME',103,11,'Good movement skills',105,10,3),
(17,10,100,'ME',103,11,'Participates in discussions',105,10,3),
(17,10,101,'ME',103,11,'Reading Kiswahili well',105,10,3),
(17,10,102,'AE',103,11,'Needs reading guidance',105,10,3),
(17,10,103,'ME',103,11,'Good with numbers',105,10,3),
(17,10,104,'ME',103,11,'Identifies local ecosystem',105,10,3),
(17,10,105,'ME',103,11,'Good dental hygiene',105,10,3),
(17,10,106,'ME',103,11,'Active in worship',105,10,3),
(17,10,107,'AE',103,11,'Needs coordination support',105,10,3),
(18,10,100,'AE',103,11,'Needs speaking confidence',105,10,3),
(18,10,101,'ME',103,11,'Progressing steadily',105,10,3),
(18,10,102,'ME',103,11,'Reads simple passages',105,10,3),
(18,10,103,'AE',103,11,'Maths needs reinforcement',105,10,3),
(18,10,104,'ME',103,11,'Good basic knowledge',105,10,3),
(18,10,105,'ME',103,11,'Clean and organised',105,10,3),
(18,10,106,'ME',103,11,'Good moral conduct',105,10,3),
(18,10,107,'ME',103,11,'Enjoys creative play',105,10,3);

-- Grade 3 East (class 104, section 10): students 1,19-22 | LAs 100-107
-- (Brian=1 already has T1 data; INSERT IGNORE skips duplicates)
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(1,10,100,'ME',104,10,'Good progress in literacy',101,10,3),
(1,10,101,'EE',104,10,'Excellent Kiswahili skills',101,10,3),
(1,10,102,'ME',104,10,'Meets expectations in English',101,10,3),
(1,10,103,'AE',104,10,'Needs more maths practice',101,10,3),
(1,10,104,'ME',104,10,'Good environmental awareness',101,10,3),
(1,10,105,'EE',104,10,'Very health conscious',101,10,3),
(1,10,106,'ME',104,10,'Participates well in CRE',101,10,3),
(1,10,107,'EE',104,10,'Very active and creative',101,10,3),
(19,10,100,'ME',104,10,'Reads and writes confidently',101,10,3),
(19,10,101,'ME',104,10,'Kiswahili improving well',101,10,3),
(19,10,102,'EE',104,10,'Exceptional English progress',101,10,3),
(19,10,103,'ME',104,10,'Good problem solving',101,10,3),
(19,10,104,'ME',104,10,'Knows local environment',101,10,3),
(19,10,105,'ME',104,10,'Maintains personal hygiene',101,10,3),
(19,10,106,'ME',104,10,'Respectful in RE',101,10,3),
(19,10,107,'ME',104,10,'Active in PE',101,10,3),
(20,10,100,'EE',104,10,'Outstanding literacy skills',101,10,3),
(20,10,101,'EE',104,10,'Excellent Kiswahili reader',101,10,3),
(20,10,102,'ME',104,10,'Good English comprehension',101,10,3),
(20,10,103,'ME',104,10,'Handles fractions well',101,10,3),
(20,10,104,'EE',104,10,'Exceptional environmental knowledge',101,10,3),
(20,10,105,'ME',104,10,'Good hygiene practices',101,10,3),
(20,10,106,'ME',104,10,'Active RE participant',101,10,3),
(20,10,107,'ME',104,10,'Creative and artistic',101,10,3),
(21,10,100,'AE',104,10,'Reads slowly; needs support',101,10,3),
(21,10,101,'ME',104,10,'Good Kiswahili oral work',101,10,3),
(21,10,102,'AE',104,10,'Needs English reading support',101,10,3),
(21,10,103,'ME',104,10,'Good number operations',101,10,3),
(21,10,104,'ME',104,10,'Basic env knowledge present',101,10,3),
(21,10,105,'ME',104,10,'Practises good hygiene',101,10,3),
(21,10,106,'EE',104,10,'Very active in RE',101,10,3),
(21,10,107,'ME',104,10,'Participates in movement',101,10,3),
(22,10,100,'ME',104,10,'Speaks clearly and fluently',101,10,3),
(22,10,101,'ME',104,10,'Learning Kiswahili well',101,10,3),
(22,10,102,'ME',104,10,'Good English foundation',101,10,3),
(22,10,103,'EE',104,10,'Excellent maths performance',101,10,3),
(22,10,104,'ME',104,10,'Good local area knowledge',101,10,3),
(22,10,105,'ME',104,10,'Healthy choices demonstrated',101,10,3),
(22,10,106,'ME',104,10,'Good moral values',101,10,3),
(22,10,107,'AE',104,10,'Needs creative arts support',101,10,3);

-- Grade 4 East (class 105, section 10): students 3,23-26 | LAs 108-116
-- (Kevin=3 already has T1; INSERT IGNORE skips duplicates)
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(3,10,108,'EE',105,10,'Outstanding English',102,10,3),
(3,10,109,'ME',105,10,'Good Kiswahili',102,10,3),
(3,10,110,'EE',105,10,'Excellent Mathematics',102,10,3),
(3,10,111,'ME',105,10,'Good Science concepts',102,10,3),
(3,10,112,'AE',105,10,'Needs Social Studies improvement',102,10,3),
(3,10,113,'ME',105,10,'Steady in RE',102,10,3),
(3,10,114,'ME',105,10,'Creative and artistic',102,10,3),
(3,10,115,'EE',105,10,'Very sporty',102,10,3),
(3,10,116,'ME',105,10,'Shows interest in Agriculture',102,10,3),
(23,10,108,'ME',105,10,'Good English skills',102,10,3),
(23,10,109,'ME',105,10,'Speaks Kiswahili fluently',102,10,3),
(23,10,110,'ME',105,10,'Good maths foundation',102,10,3),
(23,10,111,'EE',105,10,'Exceptional Science curiosity',102,10,3),
(23,10,112,'ME',105,10,'Good civic awareness',102,10,3),
(23,10,113,'ME',105,10,'Participates in RE',102,10,3),
(23,10,114,'ME',105,10,'Enjoys creative activities',102,10,3),
(23,10,115,'ME',105,10,'Active in sports',102,10,3),
(23,10,116,'ME',105,10,'Interested in farming',102,10,3),
(24,10,108,'EE',105,10,'Outstanding English writer',102,10,3),
(24,10,109,'EE',105,10,'Excellent Kiswahili composition',102,10,3),
(24,10,110,'ME',105,10,'Good maths skills',102,10,3),
(24,10,111,'ME',105,10,'Understands Science well',102,10,3),
(24,10,112,'EE',105,10,'Strong Social Studies knowledge',102,10,3),
(24,10,113,'ME',105,10,'Good RE performance',102,10,3),
(24,10,114,'EE',105,10,'Exceptional art talent',102,10,3),
(24,10,115,'ME',105,10,'Good in PE',102,10,3),
(24,10,116,'ME',105,10,'Good agri knowledge',102,10,3),
(25,10,108,'ME',105,10,'Good English comprehension',102,10,3),
(25,10,109,'ME',105,10,'Reads Kiswahili well',102,10,3),
(25,10,110,'AE',105,10,'Needs maths support',102,10,3),
(25,10,111,'ME',105,10,'Basic Science grasped',102,10,3),
(25,10,112,'ME',105,10,'Good community knowledge',102,10,3),
(25,10,113,'ME',105,10,'Respectful in RE',102,10,3),
(25,10,114,'AE',105,10,'Needs creative encouragement',102,10,3),
(25,10,115,'ME',105,10,'Participates in games',102,10,3),
(25,10,116,'ME',105,10,'Practical agri interest',102,10,3),
(26,10,108,'ME',105,10,'Communicates well in English',102,10,3),
(26,10,109,'ME',105,10,'Good Kiswahili skills',102,10,3),
(26,10,110,'ME',105,10,'Solid maths understanding',102,10,3),
(26,10,111,'ME',105,10,'Science concepts understood',102,10,3),
(26,10,112,'AE',105,10,'Social Studies needs work',102,10,3),
(26,10,113,'EE',105,10,'Outstanding RE values',102,10,3),
(26,10,114,'ME',105,10,'Good performing arts',102,10,3),
(26,10,115,'EE',105,10,'Excellent athletics skills',102,10,3),
(26,10,116,'ME',105,10,'Enjoys animal husbandry',102,10,3);

-- Grade 5 East (class 106, section 10): students 5,27-30 | LAs 108-116
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(5,10,108,'EE',106,10,'Excellent English',102,10,3),(5,10,109,'ME',106,10,'Good Kiswahili',102,10,3),(5,10,110,'EE',106,10,'Top maths performance',102,10,3),(5,10,111,'EE',106,10,'Exceptional Science',102,10,3),(5,10,112,'ME',106,10,'Good Social Studies',102,10,3),(5,10,113,'ME',106,10,'Good RE conduct',102,10,3),(5,10,114,'ME',106,10,'Creative learner',102,10,3),(5,10,115,'EE',106,10,'Outstanding athlete',102,10,3),(5,10,116,'ME',106,10,'Good agri skills',102,10,3),
(27,10,108,'ME',106,10,'Good English skills',102,10,3),(27,10,109,'ME',106,10,'Kiswahili progressing',102,10,3),(27,10,110,'ME',106,10,'Good maths',102,10,3),(27,10,111,'ME',106,10,'Science understood',102,10,3),(27,10,112,'ME',106,10,'Good civic sense',102,10,3),(27,10,113,'ME',106,10,'Good RE values',102,10,3),(27,10,114,'AE',106,10,'Needs art support',102,10,3),(27,10,115,'ME',106,10,'Active in sports',102,10,3),(27,10,116,'ME',106,10,'Interested in crops',102,10,3),
(28,10,108,'ME',106,10,'Reads well',102,10,3),(28,10,109,'EE',106,10,'Outstanding Kiswahili',102,10,3),(28,10,110,'ME',106,10,'Steady maths progress',102,10,3),(28,10,111,'ME',106,10,'Good science interest',102,10,3),(28,10,112,'EE',106,10,'Excellent citizenship',102,10,3),(28,10,113,'ME',106,10,'Good RE participation',102,10,3),(28,10,114,'EE',106,10,'Talented in music',102,10,3),(28,10,115,'ME',106,10,'Enjoys team games',102,10,3),(28,10,116,'ME',106,10,'Good agri interest',102,10,3),
(29,10,108,'AE',106,10,'Needs English support',102,10,3),(29,10,109,'ME',106,10,'Kiswahili adequate',102,10,3),(29,10,110,'ME',106,10,'Understands maths',102,10,3),(29,10,111,'AE',106,10,'Science needs attention',102,10,3),(29,10,112,'ME',106,10,'Good social knowledge',102,10,3),(29,10,113,'ME',106,10,'Respectful in RE',102,10,3),(29,10,114,'ME',106,10,'Enjoys drawing',102,10,3),(29,10,115,'AE',106,10,'Needs fitness improvement',102,10,3),(29,10,116,'ME',106,10,'Basic agri knowledge',102,10,3),
(30,10,108,'ME',106,10,'Good English writing',102,10,3),(30,10,109,'ME',106,10,'Good Kiswahili',102,10,3),(30,10,110,'EE',106,10,'Excellent maths',102,10,3),(30,10,111,'ME',106,10,'Good Science',102,10,3),(30,10,112,'ME',106,10,'Good Social Studies',102,10,3),(30,10,113,'ME',106,10,'Active RE learner',102,10,3),(30,10,114,'ME',106,10,'Creative in art',102,10,3),(30,10,115,'ME',106,10,'Good athletics',102,10,3),(30,10,116,'EE',106,10,'Exceptional agri skills',102,10,3);

-- Grade 6 East (class 107, section 10): students 31-35 | LAs 108-116
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(31,10,108,'ME',107,10,'Good English',102,10,3),(31,10,109,'ME',107,10,'Good Kiswahili',102,10,3),(31,10,110,'ME',107,10,'Solid maths',102,10,3),(31,10,111,'ME',107,10,'Good Science',102,10,3),(31,10,112,'ME',107,10,'Good Soc Studies',102,10,3),(31,10,113,'ME',107,10,'Good RE',102,10,3),(31,10,114,'ME',107,10,'Creative Arts good',102,10,3),(31,10,115,'EE',107,10,'Outstanding in sports',102,10,3),(31,10,116,'ME',107,10,'Good in Agri',102,10,3),
(32,10,108,'EE',107,10,'Excellent English writer',102,10,3),(32,10,109,'EE',107,10,'Top Kiswahili student',102,10,3),(32,10,110,'ME',107,10,'Good maths skills',102,10,3),(32,10,111,'ME',107,10,'Science concepts solid',102,10,3),(32,10,112,'EE',107,10,'Outstanding Social Studies',102,10,3),(32,10,113,'ME',107,10,'Good RE conduct',102,10,3),(32,10,114,'EE',107,10,'Exceptional art talent',102,10,3),(32,10,115,'ME',107,10,'Good in PE',102,10,3),(32,10,116,'ME',107,10,'Good Agri basics',102,10,3),
(33,10,108,'ME',107,10,'Reads well',102,10,3),(33,10,109,'ME',107,10,'Kiswahili adequate',102,10,3),(33,10,110,'AE',107,10,'Maths needs support',102,10,3),(33,10,111,'ME',107,10,'Science grasped',102,10,3),(33,10,112,'ME',107,10,'Civic awareness ok',102,10,3),(33,10,113,'EE',107,10,'Outstanding RE',102,10,3),(33,10,114,'ME',107,10,'Enjoys music',102,10,3),(33,10,115,'ME',107,10,'Active in games',102,10,3),(33,10,116,'ME',107,10,'Good crops knowledge',102,10,3),
(34,10,108,'ME',107,10,'Good comprehension',102,10,3),(34,10,109,'ME',107,10,'Steady Kiswahili',102,10,3),(34,10,110,'ME',107,10,'Maths satisfactory',102,10,3),(34,10,111,'EE',107,10,'Exceptional Science',102,10,3),(34,10,112,'ME',107,10,'Good Soc Studies',102,10,3),(34,10,113,'ME',107,10,'Respects RE values',102,10,3),(34,10,114,'ME',107,10,'Creative Arts enjoyed',102,10,3),(34,10,115,'EE',107,10,'Excellent health education',102,10,3),(34,10,116,'AE',107,10,'Needs Agri improvement',102,10,3),
(35,10,108,'AE',107,10,'English needs work',102,10,3),(35,10,109,'ME',107,10,'Kiswahili satisfactory',102,10,3),(35,10,110,'ME',107,10,'Steady maths',102,10,3),(35,10,111,'ME',107,10,'Science adequate',102,10,3),(35,10,112,'AE',107,10,'Social Studies needs work',102,10,3),(35,10,113,'ME',107,10,'Good RE participation',102,10,3),(35,10,114,'ME',107,10,'Enjoys performing arts',102,10,3),(35,10,115,'ME',107,10,'Active student',102,10,3),(35,10,116,'ME',107,10,'Good Agri interest',102,10,3);

-- Grade 7 East (class 108, section 10): students 6,36-39 | LAs 117-124
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(6,10,117,'EE',108,10,'Outstanding English',103,10,3),(6,10,118,'ME',108,10,'Good Kiswahili',103,10,3),(6,10,119,'ME',108,10,'Good Mathematics',103,10,3),(6,10,120,'EE',108,10,'Excellent Integrated Science',103,10,3),(6,10,121,'ME',108,10,'Good Social Studies',103,10,3),(6,10,122,'ME',108,10,'Pre-Tech satisfactory',103,10,3),(6,10,123,'ME',108,10,'Good Agriculture',103,10,3),(6,10,124,'EE',108,10,'Exceptional Creative Arts',103,10,3),
(36,10,117,'ME',108,10,'Good English analysis',103,10,3),(36,10,118,'ME',108,10,'Kiswahili adequate',103,10,3),(36,10,119,'EE',108,10,'Exceptional Mathematics',103,10,3),(36,10,120,'ME',108,10,'Science concepts grasped',103,10,3),(36,10,121,'ME',108,10,'Good Soc Studies',103,10,3),(36,10,122,'EE',108,10,'Excellent Pre-Tech',103,10,3),(36,10,123,'ME',108,10,'Good Agri practice',103,10,3),(36,10,124,'ME',108,10,'Good Creative Arts',103,10,3),
(37,10,117,'ME',108,10,'Good English skills',103,10,3),(37,10,118,'EE',108,10,'Top Kiswahili',103,10,3),(37,10,119,'ME',108,10,'Good maths',103,10,3),(37,10,120,'ME',108,10,'Good Science',103,10,3),(37,10,121,'EE',108,10,'Outstanding Soc Studies',103,10,3),(37,10,122,'ME',108,10,'Pre-Tech progressing',103,10,3),(37,10,123,'ME',108,10,'Agri interest shown',103,10,3),(37,10,124,'ME',108,10,'Enjoys sports',103,10,3),
(38,10,117,'AE',108,10,'English needs attention',103,10,3),(38,10,118,'ME',108,10,'Kiswahili ok',103,10,3),(38,10,119,'AE',108,10,'Maths needs support',103,10,3),(38,10,120,'ME',108,10,'Science grasped',103,10,3),(38,10,121,'ME',108,10,'Social Studies adequate',103,10,3),(38,10,122,'ME',108,10,'Pre-Tech satisfactory',103,10,3),(38,10,123,'ME',108,10,'Good Agri basics',103,10,3),(38,10,124,'ME',108,10,'Enjoys music',103,10,3),
(39,10,117,'ME',108,10,'Good English',103,10,3),(39,10,118,'ME',108,10,'Steady Kiswahili',103,10,3),(39,10,119,'ME',108,10,'Maths satisfactory',103,10,3),(39,10,120,'EE',108,10,'Science star',103,10,3),(39,10,121,'ME',108,10,'Good Soc Studies',103,10,3),(39,10,122,'ME',108,10,'Pre-Tech adequate',103,10,3),(39,10,123,'EE',108,10,'Exceptional Agri student',103,10,3),(39,10,124,'ME',108,10,'Good in arts',103,10,3);

-- Grade 8 West (class 109, section 11): students 7,40-43 | LAs 117-124
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(7,10,117,'ME',109,11,'Good English',103,10,3),(7,10,118,'ME',109,11,'Good Kiswahili',103,10,3),(7,10,119,'ME',109,11,'Steady maths',103,10,3),(7,10,120,'ME',109,11,'Good Science',103,10,3),(7,10,121,'AE',109,11,'Soc Studies needs work',103,10,3),(7,10,122,'ME',109,11,'Pre-Tech ok',103,10,3),(7,10,123,'EE',109,11,'Top Agriculture student',103,10,3),(7,10,124,'ME',109,11,'Good Creative Arts',103,10,3),
(40,10,117,'EE',109,11,'Excellent English essay writer',103,10,3),(40,10,118,'ME',109,11,'Good Kiswahili',103,10,3),(40,10,119,'ME',109,11,'Good maths performance',103,10,3),(40,10,120,'ME',109,11,'Science grasped',103,10,3),(40,10,121,'EE',109,11,'Outstanding Soc Studies',103,10,3),(40,10,122,'ME',109,11,'Pre-Tech satisfactory',103,10,3),(40,10,123,'ME',109,11,'Good Agri knowledge',103,10,3),(40,10,124,'EE',109,11,'Exceptional in dance',103,10,3),
(41,10,117,'ME',109,11,'Good English',103,10,3),(41,10,118,'ME',109,11,'Kiswahili steady',103,10,3),(41,10,119,'EE',109,11,'Top maths student',103,10,3),(41,10,120,'ME',109,11,'Good Science',103,10,3),(41,10,121,'ME',109,11,'Soc Studies ok',103,10,3),(41,10,122,'EE',109,11,'Excellent Pre-Tech',103,10,3),(41,10,123,'ME',109,11,'Good Agri',103,10,3),(41,10,124,'ME',109,11,'Active in sports',103,10,3),
(42,10,117,'ME',109,11,'Good English analysis',103,10,3),(42,10,118,'EE',109,11,'Top Kiswahili',103,10,3),(42,10,119,'ME',109,11,'Good maths',103,10,3),(42,10,120,'EE',109,11,'Science outstanding',103,10,3),(42,10,121,'ME',109,11,'Good civic knowledge',103,10,3),(42,10,122,'ME',109,11,'Pre-Tech adequate',103,10,3),(42,10,123,'ME',109,11,'Agri practicals good',103,10,3),(42,10,124,'ME',109,11,'Enjoys visual arts',103,10,3),
(43,10,117,'AE',109,11,'English needs improvement',103,10,3),(43,10,118,'ME',109,11,'Kiswahili satisfactory',103,10,3),(43,10,119,'AE',109,11,'Maths revision needed',103,10,3),(43,10,120,'ME',109,11,'Science concepts present',103,10,3),(43,10,121,'ME',109,11,'Soc Studies adequate',103,10,3),(43,10,122,'ME',109,11,'Pre-Tech ok',103,10,3),(43,10,123,'ME',109,11,'Agri basics known',103,10,3),(43,10,124,'ME',109,11,'Creative Arts satisfactory',103,10,3);

-- Grade 9 East (class 110, section 10): students 44-48 | LAs 117-124
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(44,10,117,'EE',110,10,'Outstanding English',103,10,3),(44,10,118,'ME',110,10,'Good Kiswahili',103,10,3),(44,10,119,'EE',110,10,'Top Mathematics',103,10,3),(44,10,120,'ME',110,10,'Science solid',103,10,3),(44,10,121,'EE',110,10,'Excellent Soc Studies',103,10,3),(44,10,122,'ME',110,10,'Pre-Tech adequate',103,10,3),(44,10,123,'ME',110,10,'Good Agri',103,10,3),(44,10,124,'ME',110,10,'Good Creative Arts',103,10,3),
(45,10,117,'ME',110,10,'Good English',103,10,3),(45,10,118,'EE',110,10,'Excellent Kiswahili',103,10,3),(45,10,119,'ME',110,10,'Good maths',103,10,3),(45,10,120,'ME',110,10,'Science grasped',103,10,3),(45,10,121,'ME',110,10,'Soc Studies adequate',103,10,3),(45,10,122,'EE',110,10,'Top Pre-Tech student',103,10,3),(45,10,123,'ME',110,10,'Good Agri practice',103,10,3),(45,10,124,'ME',110,10,'Enjoys sports',103,10,3),
(46,10,117,'ME',110,10,'Good English comprehension',103,10,3),(46,10,118,'ME',110,10,'Kiswahili steady',103,10,3),(46,10,119,'ME',110,10,'Maths adequate',103,10,3),(46,10,120,'EE',110,10,'Science exceptional',103,10,3),(46,10,121,'ME',110,10,'Good Soc Studies',103,10,3),(46,10,122,'ME',110,10,'Pre-Tech ok',103,10,3),(46,10,123,'ME',110,10,'Good Agri',103,10,3),(46,10,124,'EE',110,10,'Exceptional in music',103,10,3),
(47,10,117,'ME',110,10,'English satisfactory',103,10,3),(47,10,118,'ME',110,10,'Good Kiswahili',103,10,3),(47,10,119,'AE',110,10,'Maths needs support',103,10,3),(47,10,120,'ME',110,10,'Science concepts present',103,10,3),(47,10,121,'ME',110,10,'Civic knowledge good',103,10,3),(47,10,122,'ME',110,10,'Pre-Tech adequate',103,10,3),(47,10,123,'EE',110,10,'Outstanding Agri',103,10,3),(47,10,124,'ME',110,10,'Good in arts',103,10,3),
(48,10,117,'ME',110,10,'Good English',103,10,3),(48,10,118,'ME',110,10,'Kiswahili good',103,10,3),(48,10,119,'ME',110,10,'Maths satisfactory',103,10,3),(48,10,120,'ME',110,10,'Science grasped',103,10,3),(48,10,121,'AE',110,10,'Soc Studies needs attention',103,10,3),(48,10,122,'ME',110,10,'Pre-Tech ok',103,10,3),(48,10,123,'ME',110,10,'Agri basics known',103,10,3),(48,10,124,'ME',110,10,'Enjoys creative arts',103,10,3);

-- ================================================================
-- SECTION 8: CBC ASSESSMENTS - TERM 2 (exam_id = 11)
-- Students show general improvement in Term 2
-- ================================================================

-- PP1 Term 2
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(49,11,100,'EE',100,10,'Great improvement in oral skills',105,10,3),(49,11,101,'ME',100,10,'Kiswahili improving',105,10,3),(49,11,102,'EE',100,10,'Excellent English speaker',105,10,3),(49,11,103,'ME',100,10,'Counts to 50 now',105,10,3),(49,11,104,'ME',100,10,'Good env knowledge',105,10,3),(49,11,105,'EE',100,10,'Hygiene champion',105,10,3),(49,11,106,'ME',100,10,'Good RE',105,10,3),(49,11,107,'EE',100,10,'Very active and creative',105,10,3),
(50,11,100,'ME',100,10,'Growing confidence',105,10,3),(50,11,101,'ME',100,10,'Kiswahili progressing',105,10,3),(50,11,102,'ME',100,10,'Sounds improving',105,10,3),(50,11,103,'ME',100,10,'Counts to 30',105,10,3),(50,11,104,'ME',100,10,'Env awareness growing',105,10,3),(50,11,105,'ME',100,10,'Good hygiene habits',105,10,3),(50,11,106,'EE',100,10,'Spiritually engaged',105,10,3),(50,11,107,'ME',100,10,'Enjoys music',105,10,3),
(51,11,100,'EE',100,10,'Outstanding communicator',105,10,3),(51,11,101,'EE',100,10,'Top Kiswahili speaker',105,10,3),(51,11,102,'EE',100,10,'Reads simple books',105,10,3),(51,11,103,'EE',100,10,'Excellent number work',105,10,3),(51,11,104,'ME',100,10,'Good env knowledge',105,10,3),(51,11,105,'EE',100,10,'Excellent hygiene',105,10,3),(51,11,106,'ME',100,10,'Good RE values',105,10,3),(51,11,107,'EE',100,10,'Most creative student',105,10,3),
(52,11,100,'ME',100,10,'Confidence growing',105,10,3),(52,11,101,'ME',100,10,'Kiswahili improving',105,10,3),(52,11,102,'ME',100,10,'Reads with teacher',105,10,3),(52,11,103,'ME',100,10,'Counts to 20',105,10,3),(52,11,104,'ME',100,10,'Env awareness ok',105,10,3),(52,11,105,'ME',100,10,'Good food choices',105,10,3),(52,11,106,'ME',100,10,'Active in devotion',105,10,3),(52,11,107,'ME',100,10,'Enjoys art',105,10,3),
(53,11,100,'ME',100,10,'Good progress',105,10,3),(53,11,101,'ME',100,10,'Kiswahili steady',105,10,3),(53,11,102,'ME',100,10,'Phonics improving',105,10,3),(53,11,103,'ME',100,10,'Good addition',105,10,3),(53,11,104,'EE',100,10,'Loves nature',105,10,3),(53,11,105,'ME',100,10,'Good hygiene',105,10,3),(53,11,106,'ME',100,10,'Good RE participation',105,10,3),(53,11,107,'EE',100,10,'Very active in PE',105,10,3);

-- PP2 Term 2
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(54,11,100,'ME',101,10,'Reading with support',105,10,3),(54,11,101,'ME',101,10,'Kiswahili improving',105,10,3),(54,11,102,'ME',101,10,'Letter sounds known',105,10,3),(54,11,103,'ME',101,10,'Counts to 70',105,10,3),(54,11,104,'ME',101,10,'Env knowledge growing',105,10,3),(54,11,105,'EE',101,10,'Hygiene model student',105,10,3),(54,11,106,'ME',101,10,'Good RE conduct',105,10,3),(54,11,107,'ME',101,10,'Good movement',105,10,3),
(55,11,100,'EE',101,10,'Outstanding storytelling',105,10,3),(55,11,101,'EE',101,10,'Reads Kiswahili books',105,10,3),(55,11,102,'EE',101,10,'Reads English independently',105,10,3),(55,11,103,'EE',101,10,'Excellent maths',105,10,3),(55,11,104,'ME',101,10,'Good env knowledge',105,10,3),(55,11,105,'ME',101,10,'Good nutrition',105,10,3),(55,11,106,'ME',101,10,'Good moral values',105,10,3),(55,11,107,'EE',101,10,'Very creative',105,10,3),
(56,11,100,'ME',101,10,'More confident now',105,10,3),(56,11,101,'ME',101,10,'Kiswahili growing',105,10,3),(56,11,102,'ME',101,10,'Reading improving',105,10,3),(56,11,103,'ME',101,10,'Numbers improving',105,10,3),(56,11,104,'ME',101,10,'Env concepts growing',105,10,3),(56,11,105,'ME',101,10,'Good hygiene',105,10,3),(56,11,106,'EE',101,10,'Excellent RE student',105,10,3),(56,11,107,'ME',101,10,'Enjoys music',105,10,3),
(57,11,100,'ME',101,10,'Speaks well',105,10,3),(57,11,101,'EE',101,10,'Top Kiswahili reader',105,10,3),(57,11,102,'EE',101,10,'Excellent phonics',105,10,3),(57,11,103,'ME',101,10,'Numbers confident',105,10,3),(57,11,104,'ME',101,10,'Good env curiosity',105,10,3),(57,11,105,'ME',101,10,'Healthy eating habits',105,10,3),(57,11,106,'ME',101,10,'Good values',105,10,3),(57,11,107,'ME',101,10,'Active in movement',105,10,3),
(58,11,100,'ME',101,10,'Good listening',105,10,3),(58,11,101,'ME',101,10,'Kiswahili improving',105,10,3),(58,11,102,'ME',101,10,'Reading progressing',105,10,3),(58,11,103,'ME',101,10,'Maths improving',105,10,3),(58,11,104,'ME',101,10,'Knows local plants',105,10,3),(58,11,105,'ME',101,10,'Good cleanliness',105,10,3),(58,11,106,'ME',101,10,'Participates in devotion',105,10,3),(58,11,107,'ME',101,10,'Enjoys creative play',105,10,3);

-- Grade 1 Term 2 (students 2,11-14)
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(2,11,100,'EE',102,10,'Excellent reading progress',105,10,3),(2,11,101,'EE',102,10,'Top Kiswahili speaker',105,10,3),(2,11,102,'EE',102,10,'Outstanding English',105,10,3),(2,11,103,'ME',102,10,'Good maths progress',105,10,3),(2,11,104,'ME',102,10,'Good env knowledge',105,10,3),(2,11,105,'EE',102,10,'Hygiene exemplary',105,10,3),(2,11,106,'ME',102,10,'Good RE',105,10,3),(2,11,107,'EE',102,10,'Very creative',105,10,3),
(11,11,100,'ME',102,10,'Oral skills improving',105,10,3),(11,11,101,'EE',102,10,'Top Kiswahili student',105,10,3),(11,11,102,'ME',102,10,'Reads independently',105,10,3),(11,11,103,'ME',102,10,'Good maths',105,10,3),(11,11,104,'ME',102,10,'Env awareness good',105,10,3),(11,11,105,'ME',102,10,'Good hygiene',105,10,3),(11,11,106,'ME',102,10,'RE good',105,10,3),(11,11,107,'ME',102,10,'Active in PE',105,10,3),
(12,11,100,'ME',102,10,'More confident speaking',105,10,3),(12,11,101,'ME',102,10,'Kiswahili improving',105,10,3),(12,11,102,'ME',102,10,'Reading improving',105,10,3),(12,11,103,'ME',102,10,'Maths progressing',105,10,3),(12,11,104,'ME',102,10,'Env knowledge growing',105,10,3),(12,11,105,'ME',102,10,'Good hygiene',105,10,3),(12,11,106,'EE',102,10,'Very kind and spiritual',105,10,3),(12,11,107,'ME',102,10,'Enjoys art',105,10,3),
(13,11,100,'ME',102,10,'Good oral skills',105,10,3),(13,11,101,'ME',102,10,'Kiswahili progressing',105,10,3),(13,11,102,'ME',102,10,'Reading improving',105,10,3),(13,11,103,'EE',102,10,'Excellent maths',105,10,3),(13,11,104,'ME',102,10,'Good env knowledge',105,10,3),(13,11,105,'ME',102,10,'Good hygiene',105,10,3),(13,11,106,'ME',102,10,'Good RE',105,10,3),(13,11,107,'ME',102,10,'Active in PE',105,10,3),
(14,11,100,'ME',102,10,'Oral skills growing',105,10,3),(14,11,101,'ME',102,10,'Kiswahili steady',105,10,3),(14,11,102,'EE',102,10,'Excellent phonics',105,10,3),(14,11,103,'ME',102,10,'Good maths',105,10,3),(14,11,104,'ME',102,10,'Env knowledge adequate',105,10,3),(14,11,105,'ME',102,10,'Good nutrition choices',105,10,3),(14,11,106,'ME',102,10,'Good RE conduct',105,10,3),(14,11,107,'ME',102,10,'Enjoys music',105,10,3);

-- Grade 2 Term 2 (students 4,15-18)
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(4,11,100,'ME',103,11,'Good listening',105,10,3),(4,11,101,'ME',103,11,'Kiswahili progressing',105,10,3),(4,11,102,'ME',103,11,'Reads with confidence',105,10,3),(4,11,103,'ME',103,11,'Number work improving',105,10,3),(4,11,104,'ME',103,11,'Env knowledge growing',105,10,3),(4,11,105,'ME',103,11,'Good hygiene',105,10,3),(4,11,106,'ME',103,11,'Good RE',105,10,3),(4,11,107,'ME',103,11,'Enjoys movement',105,10,3),
(15,11,100,'EE',103,11,'Outstanding communicator',105,10,3),(15,11,101,'ME',103,11,'Good Kiswahili',105,10,3),(15,11,102,'EE',103,11,'Reads independently',105,10,3),(15,11,103,'EE',103,11,'Top maths student',105,10,3),(15,11,104,'ME',103,11,'Good env knowledge',105,10,3),(15,11,105,'ME',103,11,'Healthy habits',105,10,3),(15,11,106,'ME',103,11,'Good values',105,10,3),(15,11,107,'EE',103,11,'Very active',105,10,3),
(16,11,100,'ME',103,11,'Good oral skills',105,10,3),(16,11,101,'EE',103,11,'Excellent Kiswahili',105,10,3),(16,11,102,'ME',103,11,'Good English',105,10,3),(16,11,103,'ME',103,11,'Good maths',105,10,3),(16,11,104,'ME',103,11,'Env awareness good',105,10,3),(16,11,105,'EE',103,11,'Excellent hygiene',105,10,3),(16,11,106,'ME',103,11,'Good RE',105,10,3),(16,11,107,'ME',103,11,'Good PE skills',105,10,3),
(17,11,100,'ME',103,11,'Participating more',105,10,3),(17,11,101,'ME',103,11,'Kiswahili progressing',105,10,3),(17,11,102,'ME',103,11,'Reading improving',105,10,3),(17,11,103,'ME',103,11,'Maths improving',105,10,3),(17,11,104,'ME',103,11,'Env knowledge adequate',105,10,3),(17,11,105,'ME',103,11,'Good hygiene',105,10,3),(17,11,106,'ME',103,11,'Good RE values',105,10,3),(17,11,107,'ME',103,11,'Coordination improving',105,10,3),
(18,11,100,'ME',103,11,'Speaking with confidence',105,10,3),(18,11,101,'ME',103,11,'Kiswahili steady',105,10,3),(18,11,102,'ME',103,11,'Reads simple passages',105,10,3),(18,11,103,'ME',103,11,'Maths improving',105,10,3),(18,11,104,'ME',103,11,'Env concepts growing',105,10,3),(18,11,105,'ME',103,11,'Good personal hygiene',105,10,3),(18,11,106,'ME',103,11,'Good conduct',105,10,3),(18,11,107,'ME',103,11,'Enjoys creative activities',105,10,3);

-- Grade 3 Term 2 (students 1,19-22)
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(1,11,100,'ME',104,10,'Consistent literacy progress',101,10,3),(1,11,101,'EE',104,10,'Top Kiswahili speaker',101,10,3),(1,11,102,'EE',104,10,'Outstanding English',101,10,3),(1,11,103,'ME',104,10,'Maths improving steadily',101,10,3),(1,11,104,'ME',104,10,'Good env knowledge',101,10,3),(1,11,105,'EE',104,10,'Hygiene excellence',101,10,3),(1,11,106,'ME',104,10,'Good RE values',101,10,3),(1,11,107,'EE',104,10,'Creative and sporty',101,10,3),
(19,11,100,'ME',104,10,'Reads and writes well',101,10,3),(19,11,101,'ME',104,10,'Kiswahili good',101,10,3),(19,11,102,'EE',104,10,'Excellent English progress',101,10,3),(19,11,103,'ME',104,10,'Problem solving improving',101,10,3),(19,11,104,'ME',104,10,'Good env knowledge',101,10,3),(19,11,105,'ME',104,10,'Hygiene maintained',101,10,3),(19,11,106,'ME',104,10,'Good RE conduct',101,10,3),(19,11,107,'ME',104,10,'Active in PE',101,10,3),
(20,11,100,'EE',104,10,'Outstanding literacy',101,10,3),(20,11,101,'EE',104,10,'Excellent Kiswahili',101,10,3),(20,11,102,'EE',104,10,'Top English student',101,10,3),(20,11,103,'ME',104,10,'Fractions well handled',101,10,3),(20,11,104,'EE',104,10,'Outstanding env knowledge',101,10,3),(20,11,105,'ME',104,10,'Good hygiene',101,10,3),(20,11,106,'ME',104,10,'Good RE',101,10,3),(20,11,107,'ME',104,10,'Creative and artistic',101,10,3),
(21,11,100,'ME',104,10,'Reading improving',101,10,3),(21,11,101,'ME',104,10,'Kiswahili oral good',101,10,3),(21,11,102,'ME',104,10,'English reading better',101,10,3),(21,11,103,'ME',104,10,'Number operations good',101,10,3),(21,11,104,'ME',104,10,'Env knowledge present',101,10,3),(21,11,105,'ME',104,10,'Good hygiene',101,10,3),(21,11,106,'EE',104,10,'Very active in RE',101,10,3),(21,11,107,'ME',104,10,'Active in movement',101,10,3),
(22,11,100,'ME',104,10,'Fluent speaker',101,10,3),(22,11,101,'ME',104,10,'Kiswahili steady',101,10,3),(22,11,102,'ME',104,10,'Good English',101,10,3),(22,11,103,'EE',104,10,'Top maths performance',101,10,3),(22,11,104,'ME',104,10,'Env knowledge good',101,10,3),(22,11,105,'ME',104,10,'Healthy choices',101,10,3),(22,11,106,'ME',104,10,'Good moral values',101,10,3),(22,11,107,'ME',104,10,'Creative arts improving',101,10,3);

-- Grade 4 Term 2 (students 3,23-26)
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(3,11,108,'EE',105,10,'Consistently outstanding',102,10,3),(3,11,109,'EE',105,10,'Excellent Kiswahili',102,10,3),(3,11,110,'EE',105,10,'Top maths student',102,10,3),(3,11,111,'ME',105,10,'Science improving',102,10,3),(3,11,112,'ME',105,10,'Soc Studies improving',102,10,3),(3,11,113,'ME',105,10,'Good RE',102,10,3),(3,11,114,'ME',105,10,'Creative arts good',102,10,3),(3,11,115,'EE',105,10,'Outstanding athlete',102,10,3),(3,11,116,'ME',105,10,'Good agri',102,10,3),
(23,11,108,'ME',105,10,'English improving',102,10,3),(23,11,109,'ME',105,10,'Good Kiswahili',102,10,3),(23,11,110,'ME',105,10,'Good maths',102,10,3),(23,11,111,'EE',105,10,'Science star',102,10,3),(23,11,112,'ME',105,10,'Civic awareness good',102,10,3),(23,11,113,'ME',105,10,'Good RE',102,10,3),(23,11,114,'ME',105,10,'Enjoys creative arts',102,10,3),(23,11,115,'ME',105,10,'Good sportsmanship',102,10,3),(23,11,116,'ME',105,10,'Agri practicals good',102,10,3),
(24,11,108,'EE',105,10,'Outstanding English',102,10,3),(24,11,109,'EE',105,10,'Top Kiswahili writer',102,10,3),(24,11,110,'ME',105,10,'Good maths',102,10,3),(24,11,111,'ME',105,10,'Good Science',102,10,3),(24,11,112,'EE',105,10,'Top Social Studies',102,10,3),(24,11,113,'ME',105,10,'Good RE',102,10,3),(24,11,114,'EE',105,10,'Exceptional artist',102,10,3),(24,11,115,'ME',105,10,'Good in PE',102,10,3),(24,11,116,'ME',105,10,'Agri knowledge good',102,10,3),
(25,11,108,'ME',105,10,'English improving',102,10,3),(25,11,109,'ME',105,10,'Kiswahili good',102,10,3),(25,11,110,'ME',105,10,'Maths progressing',102,10,3),(25,11,111,'ME',105,10,'Science improving',102,10,3),(25,11,112,'ME',105,10,'Soc Studies improving',102,10,3),(25,11,113,'ME',105,10,'Good RE',102,10,3),(25,11,114,'ME',105,10,'Creative arts growing',102,10,3),(25,11,115,'ME',105,10,'Active in games',102,10,3),(25,11,116,'ME',105,10,'Agri knowledge present',102,10,3),
(26,11,108,'ME',105,10,'Good English',102,10,3),(26,11,109,'ME',105,10,'Kiswahili good',102,10,3),(26,11,110,'ME',105,10,'Solid maths',102,10,3),(26,11,111,'ME',105,10,'Science concepts solid',102,10,3),(26,11,112,'ME',105,10,'Soc Studies adequate',102,10,3),(26,11,113,'EE',105,10,'Outstanding RE values',102,10,3),(26,11,114,'ME',105,10,'Performing arts enjoyed',102,10,3),(26,11,115,'EE',105,10,'Excellent athlete',102,10,3),(26,11,116,'ME',105,10,'Good agri practicals',102,10,3);

-- Grade 5 Term 2 (students 5,27-30)
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(5,11,108,'EE',106,10,'English excellence',102,10,3),(5,11,109,'EE',106,10,'Top Kiswahili',102,10,3),(5,11,110,'EE',106,10,'Mathematics outstanding',102,10,3),(5,11,111,'EE',106,10,'Science star',102,10,3),(5,11,112,'ME',106,10,'Soc Studies good',102,10,3),(5,11,113,'ME',106,10,'Good RE',102,10,3),(5,11,114,'ME',106,10,'Creative arts good',102,10,3),(5,11,115,'EE',106,10,'Outstanding athlete',102,10,3),(5,11,116,'ME',106,10,'Good agri',102,10,3),
(27,11,108,'ME',106,10,'Good English',102,10,3),(27,11,109,'ME',106,10,'Kiswahili good',102,10,3),(27,11,110,'ME',106,10,'Maths steady',102,10,3),(27,11,111,'ME',106,10,'Science improving',102,10,3),(27,11,112,'ME',106,10,'Good civic sense',102,10,3),(27,11,113,'ME',106,10,'Good RE',102,10,3),(27,11,114,'ME',106,10,'Art improving',102,10,3),(27,11,115,'ME',106,10,'Good in sports',102,10,3),(27,11,116,'ME',106,10,'Agri interest shown',102,10,3),
(28,11,108,'ME',106,10,'English good',102,10,3),(28,11,109,'EE',106,10,'Outstanding Kiswahili',102,10,3),(28,11,110,'ME',106,10,'Maths solid',102,10,3),(28,11,111,'ME',106,10,'Science good',102,10,3),(28,11,112,'EE',106,10,'Top citizenship',102,10,3),(28,11,113,'ME',106,10,'Good RE',102,10,3),(28,11,114,'EE',106,10,'Music talent outstanding',102,10,3),(28,11,115,'ME',106,10,'Good team player',102,10,3),(28,11,116,'ME',106,10,'Agri practice good',102,10,3),
(29,11,108,'ME',106,10,'English improving',102,10,3),(29,11,109,'ME',106,10,'Kiswahili adequate',102,10,3),(29,11,110,'ME',106,10,'Maths steady',102,10,3),(29,11,111,'ME',106,10,'Science improving',102,10,3),(29,11,112,'ME',106,10,'Social Studies ok',102,10,3),(29,11,113,'ME',106,10,'Good RE',102,10,3),(29,11,114,'ME',106,10,'Drawing improving',102,10,3),(29,11,115,'ME',106,10,'Fitness improving',102,10,3),(29,11,116,'ME',106,10,'Agri basics known',102,10,3),
(30,11,108,'ME',106,10,'English good',102,10,3),(30,11,109,'ME',106,10,'Kiswahili good',102,10,3),(30,11,110,'EE',106,10,'Top maths student',102,10,3),(30,11,111,'ME',106,10,'Science good',102,10,3),(30,11,112,'ME',106,10,'Soc Studies good',102,10,3),(30,11,113,'ME',106,10,'Good RE',102,10,3),(30,11,114,'ME',106,10,'Creative arts ok',102,10,3),(30,11,115,'ME',106,10,'Good athletics',102,10,3),(30,11,116,'EE',106,10,'Outstanding agri student',102,10,3);

-- Grade 6 Term 2 (students 31-35)
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(31,11,108,'ME',107,10,'Good English',102,10,3),(31,11,109,'ME',107,10,'Kiswahili good',102,10,3),(31,11,110,'ME',107,10,'Maths solid',102,10,3),(31,11,111,'ME',107,10,'Science solid',102,10,3),(31,11,112,'ME',107,10,'Soc Studies good',102,10,3),(31,11,113,'ME',107,10,'Good RE',102,10,3),(31,11,114,'ME',107,10,'Creative arts steady',102,10,3),(31,11,115,'EE',107,10,'Sports excellent',102,10,3),(31,11,116,'ME',107,10,'Agri good',102,10,3),
(32,11,108,'EE',107,10,'Outstanding English',102,10,3),(32,11,109,'EE',107,10,'Top Kiswahili',102,10,3),(32,11,110,'ME',107,10,'Good maths',102,10,3),(32,11,111,'ME',107,10,'Science good',102,10,3),(32,11,112,'EE',107,10,'Outstanding Soc Studies',102,10,3),(32,11,113,'ME',107,10,'Good RE',102,10,3),(32,11,114,'EE',107,10,'Exceptional visual arts',102,10,3),(32,11,115,'ME',107,10,'Good PE',102,10,3),(32,11,116,'ME',107,10,'Good agri knowledge',102,10,3),
(33,11,108,'ME',107,10,'English improving',102,10,3),(33,11,109,'ME',107,10,'Kiswahili steady',102,10,3),(33,11,110,'ME',107,10,'Maths improving',102,10,3),(33,11,111,'ME',107,10,'Science grasped',102,10,3),(33,11,112,'ME',107,10,'Civic knowledge good',102,10,3),(33,11,113,'EE',107,10,'Outstanding RE',102,10,3),(33,11,114,'ME',107,10,'Music improving',102,10,3),(33,11,115,'ME',107,10,'Active in games',102,10,3),(33,11,116,'ME',107,10,'Agri concepts ok',102,10,3),
(34,11,108,'ME',107,10,'Good comprehension',102,10,3),(34,11,109,'ME',107,10,'Kiswahili steady',102,10,3),(34,11,110,'ME',107,10,'Maths satisfactory',102,10,3),(34,11,111,'EE',107,10,'Science exceptional',102,10,3),(34,11,112,'ME',107,10,'Soc Studies good',102,10,3),(34,11,113,'ME',107,10,'Good RE',102,10,3),(34,11,114,'ME',107,10,'Creative arts enjoyed',102,10,3),(34,11,115,'EE',107,10,'Health education excellent',102,10,3),(34,11,116,'ME',107,10,'Agri knowledge growing',102,10,3),
(35,11,108,'ME',107,10,'English improving',102,10,3),(35,11,109,'ME',107,10,'Kiswahili adequate',102,10,3),(35,11,110,'ME',107,10,'Maths steady',102,10,3),(35,11,111,'ME',107,10,'Science adequate',102,10,3),(35,11,112,'ME',107,10,'Soc Studies improving',102,10,3),(35,11,113,'ME',107,10,'Good RE',102,10,3),(35,11,114,'ME',107,10,'Performing arts enjoyed',102,10,3),(35,11,115,'ME',107,10,'Active student',102,10,3),(35,11,116,'ME',107,10,'Agri basics known',102,10,3);

-- Grade 7 Term 2 (students 6,36-39)
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(6,11,117,'EE',108,10,'Outstanding English',103,10,3),(6,11,118,'EE',108,10,'Top Kiswahili',103,10,3),(6,11,119,'ME',108,10,'Maths solid',103,10,3),(6,11,120,'EE',108,10,'Science outstanding',103,10,3),(6,11,121,'ME',108,10,'Soc Studies good',103,10,3),(6,11,122,'ME',108,10,'Pre-Tech improving',103,10,3),(6,11,123,'ME',108,10,'Agri good',103,10,3),(6,11,124,'EE',108,10,'Top creative arts',103,10,3),
(36,11,117,'ME',108,10,'English good',103,10,3),(36,11,118,'ME',108,10,'Kiswahili steady',103,10,3),(36,11,119,'EE',108,10,'Top maths',103,10,3),(36,11,120,'ME',108,10,'Science solid',103,10,3),(36,11,121,'ME',108,10,'Soc Studies ok',103,10,3),(36,11,122,'EE',108,10,'Pre-Tech excellent',103,10,3),(36,11,123,'ME',108,10,'Agri practicals good',103,10,3),(36,11,124,'ME',108,10,'Creative arts ok',103,10,3),
(37,11,117,'ME',108,10,'English good',103,10,3),(37,11,118,'EE',108,10,'Outstanding Kiswahili',103,10,3),(37,11,119,'ME',108,10,'Maths steady',103,10,3),(37,11,120,'ME',108,10,'Science good',103,10,3),(37,11,121,'EE',108,10,'Top Soc Studies',103,10,3),(37,11,122,'ME',108,10,'Pre-Tech progressing',103,10,3),(37,11,123,'ME',108,10,'Agri interest maintained',103,10,3),(37,11,124,'ME',108,10,'Enjoys sports',103,10,3),
(38,11,117,'ME',108,10,'English improving',103,10,3),(38,11,118,'ME',108,10,'Kiswahili steady',103,10,3),(38,11,119,'ME',108,10,'Maths improving',103,10,3),(38,11,120,'ME',108,10,'Science grasped',103,10,3),(38,11,121,'ME',108,10,'Soc Studies adequate',103,10,3),(38,11,122,'ME',108,10,'Pre-Tech improving',103,10,3),(38,11,123,'ME',108,10,'Agri basics ok',103,10,3),(38,11,124,'ME',108,10,'Music improving',103,10,3),
(39,11,117,'ME',108,10,'English good',103,10,3),(39,11,118,'ME',108,10,'Kiswahili steady',103,10,3),(39,11,119,'ME',108,10,'Maths satisfactory',103,10,3),(39,11,120,'EE',108,10,'Science star',103,10,3),(39,11,121,'ME',108,10,'Soc Studies good',103,10,3),(39,11,122,'ME',108,10,'Pre-Tech ok',103,10,3),(39,11,123,'EE',108,10,'Outstanding agri',103,10,3),(39,11,124,'ME',108,10,'Good in arts',103,10,3);

-- Grade 8 Term 2 (students 7,40-43)
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(7,11,117,'ME',109,11,'English improving',103,10,3),(7,11,118,'ME',109,11,'Kiswahili steady',103,10,3),(7,11,119,'ME',109,11,'Maths improving',103,10,3),(7,11,120,'ME',109,11,'Science solid',103,10,3),(7,11,121,'ME',109,11,'Soc Studies improving',103,10,3),(7,11,122,'ME',109,11,'Pre-Tech adequate',103,10,3),(7,11,123,'EE',109,11,'Outstanding agri',103,10,3),(7,11,124,'ME',109,11,'Creative arts good',103,10,3),
(40,11,117,'EE',109,11,'Excellent English',103,10,3),(40,11,118,'ME',109,11,'Kiswahili good',103,10,3),(40,11,119,'ME',109,11,'Maths solid',103,10,3),(40,11,120,'ME',109,11,'Science good',103,10,3),(40,11,121,'EE',109,11,'Top Soc Studies',103,10,3),(40,11,122,'ME',109,11,'Pre-Tech satisfactory',103,10,3),(40,11,123,'ME',109,11,'Agri knowledge good',103,10,3),(40,11,124,'EE',109,11,'Outstanding dance',103,10,3),
(41,11,117,'ME',109,11,'English good',103,10,3),(41,11,118,'ME',109,11,'Kiswahili steady',103,10,3),(41,11,119,'EE',109,11,'Top maths',103,10,3),(41,11,120,'ME',109,11,'Science good',103,10,3),(41,11,121,'ME',109,11,'Soc Studies ok',103,10,3),(41,11,122,'EE',109,11,'Top Pre-Tech',103,10,3),(41,11,123,'ME',109,11,'Agri good',103,10,3),(41,11,124,'ME',109,11,'Active in sports',103,10,3),
(42,11,117,'ME',109,11,'Good English',103,10,3),(42,11,118,'EE',109,11,'Outstanding Kiswahili',103,10,3),(42,11,119,'ME',109,11,'Maths good',103,10,3),(42,11,120,'EE',109,11,'Science outstanding',103,10,3),(42,11,121,'ME',109,11,'Civic knowledge good',103,10,3),(42,11,122,'ME',109,11,'Pre-Tech adequate',103,10,3),(42,11,123,'ME',109,11,'Agri practicals good',103,10,3),(42,11,124,'ME',109,11,'Visual arts good',103,10,3),
(43,11,117,'ME',109,11,'English steady',103,10,3),(43,11,118,'ME',109,11,'Kiswahili satisfactory',103,10,3),(43,11,119,'ME',109,11,'Maths improving',103,10,3),(43,11,120,'ME',109,11,'Science improving',103,10,3),(43,11,121,'ME',109,11,'Soc Studies adequate',103,10,3),(43,11,122,'ME',109,11,'Pre-Tech ok',103,10,3),(43,11,123,'ME',109,11,'Agri basics solid',103,10,3),(43,11,124,'ME',109,11,'Creative arts satisfactory',103,10,3);

-- Grade 9 Term 2 (students 44-48)
INSERT IGNORE INTO `cbc_assessment` (`student_id`,`exam_id`,`learning_area_id`,`competency_level`,`class_id`,`section_id`,`remarks`,`assessed_by`,`session_id`,`branch_id`) VALUES
(44,11,117,'EE',110,10,'Outstanding English',103,10,3),(44,11,118,'EE',110,10,'Top Kiswahili',103,10,3),(44,11,119,'EE',110,10,'Top maths',103,10,3),(44,11,120,'ME',110,10,'Science solid',103,10,3),(44,11,121,'EE',110,10,'Outstanding Soc Studies',103,10,3),(44,11,122,'ME',110,10,'Pre-Tech improving',103,10,3),(44,11,123,'ME',110,10,'Agri knowledge good',103,10,3),(44,11,124,'ME',110,10,'Creative arts good',103,10,3),
(45,11,117,'ME',110,10,'English good',103,10,3),(45,11,118,'EE',110,10,'Top Kiswahili',103,10,3),(45,11,119,'ME',110,10,'Maths good',103,10,3),(45,11,120,'ME',110,10,'Science grasped',103,10,3),(45,11,121,'ME',110,10,'Soc Studies adequate',103,10,3),(45,11,122,'EE',110,10,'Outstanding Pre-Tech',103,10,3),(45,11,123,'ME',110,10,'Agri practice good',103,10,3),(45,11,124,'ME',110,10,'Enjoys sports',103,10,3),
(46,11,117,'ME',110,10,'English good',103,10,3),(46,11,118,'ME',110,10,'Kiswahili steady',103,10,3),(46,11,119,'ME',110,10,'Maths adequate',103,10,3),(46,11,120,'EE',110,10,'Science exceptional',103,10,3),(46,11,121,'ME',110,10,'Soc Studies good',103,10,3),(46,11,122,'ME',110,10,'Pre-Tech adequate',103,10,3),(46,11,123,'ME',110,10,'Agri good',103,10,3),(46,11,124,'EE',110,10,'Outstanding music',103,10,3),
(47,11,117,'ME',110,10,'English satisfactory',103,10,3),(47,11,118,'ME',110,10,'Kiswahili good',103,10,3),(47,11,119,'ME',110,10,'Maths improving',103,10,3),(47,11,120,'ME',110,10,'Science solid',103,10,3),(47,11,121,'ME',110,10,'Civic knowledge good',103,10,3),(47,11,122,'ME',110,10,'Pre-Tech adequate',103,10,3),(47,11,123,'EE',110,10,'Outstanding agri',103,10,3),(47,11,124,'ME',110,10,'Arts good',103,10,3),
(48,11,117,'ME',110,10,'English good',103,10,3),(48,11,118,'ME',110,10,'Kiswahili good',103,10,3),(48,11,119,'ME',110,10,'Maths satisfactory',103,10,3),(48,11,120,'ME',110,10,'Science grasped',103,10,3),(48,11,121,'ME',110,10,'Soc Studies improving',103,10,3),(48,11,122,'ME',110,10,'Pre-Tech ok',103,10,3),(48,11,123,'ME',110,10,'Agri basics solid',103,10,3),(48,11,124,'ME',110,10,'Creative arts enjoyed',103,10,3);

-- ================================================================
-- SECTION 9: BEHAVIOUR ASSESSMENTS (Terms 1 & 2)
-- Categories: Social, Spiritual, Emotional, Physical, Creative
-- ================================================================
INSERT IGNORE INTO `cbc_behaviour_assessment` (`student_id`,`exam_id`,`category`,`rating`,`remarks`,`session_id`,`branch_id`) VALUES
-- PP1 T1
(49,10,'Social','ME','Gets along well with peers',10,3),(49,10,'Spiritual','ME','Participates in morning prayers',10,3),(49,10,'Emotional','ME','Good self-regulation',10,3),(49,10,'Physical','EE','Very energetic and active',10,3),(49,10,'Creative','ME','Enjoys art activities',10,3),
(50,10,'Social','AE','Still adapting to school',10,3),(50,10,'Spiritual','ME','Learning prayers',10,3),(50,10,'Emotional','ME','Settling in well',10,3),(50,10,'Physical','ME','Active in outdoor play',10,3),(50,10,'Creative','ME','Shows imagination',10,3),
(51,10,'Social','EE','Natural leader among peers',10,3),(51,10,'Spiritual','ME','Respectful and devout',10,3),(51,10,'Emotional','EE','Mature for age',10,3),(51,10,'Physical','EE','Excellent motor skills',10,3),(51,10,'Creative','EE','Very creative mind',10,3),
(52,10,'Social','ME','Friendly and cooperative',10,3),(52,10,'Spiritual','ME','Good moral conduct',10,3),(52,10,'Emotional','AE','Needs emotional support',10,3),(52,10,'Physical','ME','Participates in play',10,3),(52,10,'Creative','ME','Enjoys drawing',10,3),
(53,10,'Social','ME','Works well in groups',10,3),(53,10,'Spiritual','ME','Respectful of others',10,3),(53,10,'Emotional','ME','Good self-control',10,3),(53,10,'Physical','ME','Active learner',10,3),(53,10,'Creative','EE','Very creative student',10,3),
-- PP2 T1
(54,10,'Social','ME','Good social interaction',10,3),(54,10,'Spiritual','EE','Very prayerful student',10,3),(54,10,'Emotional','ME','Manages emotions well',10,3),(54,10,'Physical','ME','Good coordination',10,3),(54,10,'Creative','ME','Creative thinker',10,3),
(55,10,'Social','EE','Excellent social skills',10,3),(55,10,'Spiritual','ME','Participates in RE',10,3),(55,10,'Emotional','EE','Very confident',10,3),(55,10,'Physical','EE','Outstanding gross motor',10,3),(55,10,'Creative','EE','Most creative in class',10,3),
(56,10,'Social','AE','Shy but improving',10,3),(56,10,'Spiritual','EE','Very devout',10,3),(56,10,'Emotional','ME','Manages feelings ok',10,3),(56,10,'Physical','ME','Participates in movement',10,3),(56,10,'Creative','ME','Enjoys music',10,3),
(57,10,'Social','ME','Good team player',10,3),(57,10,'Spiritual','ME','Good moral values',10,3),(57,10,'Emotional','ME','Good self-regulation',10,3),(57,10,'Physical','ME','Energetic learner',10,3),(57,10,'Creative','ME','Enjoys art and craft',10,3),
(58,10,'Social','ME','Friendly student',10,3),(58,10,'Spiritual','ME','Respectful of all',10,3),(58,10,'Emotional','ME','Good emotional control',10,3),(58,10,'Physical','AE','Needs PE encouragement',10,3),(58,10,'Creative','ME','Enjoys creative play',10,3),
-- Grade 1-3 T1
(2,10,'Social','EE','Class leader',10,3),(2,10,'Spiritual','ME','Good moral values',10,3),(2,10,'Emotional','EE','Very confident',10,3),(2,10,'Physical','EE','Excellent athlete',10,3),(2,10,'Creative','EE','Exceptionally creative',10,3),
(11,10,'Social','ME','Friendly and helpful',10,3),(11,10,'Spiritual','ME','Good RE participation',10,3),(11,10,'Emotional','ME','Handles conflict well',10,3),(11,10,'Physical','ME','Active in sports',10,3),(11,10,'Creative','ME','Enjoys art',10,3),
(12,10,'Social','ME','Works well with others',10,3),(12,10,'Spiritual','EE','Spiritually active',10,3),(12,10,'Emotional','AE','Needs emotional guidance',10,3),(12,10,'Physical','ME','Good PE skills',10,3),(12,10,'Creative','ME','Creative student',10,3),
(13,10,'Social','ME','Good team player',10,3),(13,10,'Spiritual','ME','Participates in devotion',10,3),(13,10,'Emotional','ME','Good self-control',10,3),(13,10,'Physical','EE','Outstanding athlete',10,3),(13,10,'Creative','ME','Enjoys craft',10,3),
(14,10,'Social','ME','Friendly personality',10,3),(14,10,'Spiritual','ME','Respectful student',10,3),(14,10,'Emotional','ME','Good emotional maturity',10,3),(14,10,'Physical','ME','Active learner',10,3),(14,10,'Creative','EE','Very creative in music',10,3),
(1,10,'Social','EE','Works well with others',10,3),(1,10,'Spiritual','ME','Respectful of all faiths',10,3),(1,10,'Emotional','ME','Good self-control',10,3),(1,10,'Physical','EE','Very active in sports',10,3),(1,10,'Creative','EE','Loves drawing and music',10,3),
(19,10,'Social','ME','Good social skills',10,3),(19,10,'Spiritual','ME','Good RE conduct',10,3),(19,10,'Emotional','ME','Handles emotions well',10,3),(19,10,'Physical','ME','Active student',10,3),(19,10,'Creative','ME','Enjoys art',10,3),
(20,10,'Social','EE','Outstanding leadership',10,3),(20,10,'Spiritual','ME','Good moral character',10,3),(20,10,'Emotional','EE','Very mature student',10,3),(20,10,'Physical','ME','Good PE skills',10,3),(20,10,'Creative','EE','Excellent in creative arts',10,3),
(21,10,'Social','AE','Needs social confidence',10,3),(21,10,'Spiritual','EE','Very active in RE',10,3),(21,10,'Emotional','ME','Good emotional control',10,3),(21,10,'Physical','ME','Participates in games',10,3),(21,10,'Creative','ME','Enjoys craft activities',10,3),
(22,10,'Social','ME','Cooperates well',10,3),(22,10,'Spiritual','ME','Good moral values',10,3),(22,10,'Emotional','ME','Handles peer pressure well',10,3),(22,10,'Physical','ME','Good sports skills',10,3),(22,10,'Creative','ME','Creative writer',10,3),
-- Grade 4-6 T1
(3,10,'Social','ME','Friendly and helpful',10,3),(3,10,'Spiritual','ME','Good moral values',10,3),(3,10,'Emotional','EE','Mature and composed',10,3),(3,10,'Physical','ME','Participates in PE',10,3),(3,10,'Creative','AE','Can improve in art',10,3),
(23,10,'Social','ME','Good team player',10,3),(23,10,'Spiritual','ME','Good RE values',10,3),(23,10,'Emotional','ME','Handles situations well',10,3),(23,10,'Physical','ME','Active student',10,3),(23,10,'Creative','ME','Enjoys science projects',10,3),
(24,10,'Social','EE','Class social star',10,3),(24,10,'Spiritual','ME','Good moral character',10,3),(24,10,'Emotional','EE','Very confident leader',10,3),(24,10,'Physical','ME','Good in PE',10,3),(24,10,'Creative','EE','Exceptional artist',10,3),
(25,10,'Social','ME','Friendly student',10,3),(25,10,'Spiritual','ME','Respects all beliefs',10,3),(25,10,'Emotional','ME','Good self-regulation',10,3),(25,10,'Physical','ME','Active in games',10,3),(25,10,'Creative','AE','Needs creative guidance',10,3),
(26,10,'Social','ME','Works well in groups',10,3),(26,10,'Spiritual','EE','Very active in RE',10,3),(26,10,'Emotional','ME','Handles conflict peacefully',10,3),(26,10,'Physical','EE','Outstanding athlete',10,3),(26,10,'Creative','ME','Enjoys performing arts',10,3),
(5,10,'Social','EE','Natural leader',10,3),(5,10,'Spiritual','ME','Good values',10,3),(5,10,'Emotional','EE','Very composed and mature',10,3),(5,10,'Physical','EE','Top athlete',10,3),(5,10,'Creative','ME','Creative learner',10,3),
(27,10,'Social','ME','Good social skills',10,3),(27,10,'Spiritual','ME','Participates in RE',10,3),(27,10,'Emotional','ME','Good emotional control',10,3),(27,10,'Physical','ME','Active',10,3),(27,10,'Creative','ME','Creative ideas',10,3),
(28,10,'Social','ME','Very friendly',10,3),(28,10,'Spiritual','ME','Good moral conduct',10,3),(28,10,'Emotional','ME','Mature student',10,3),(28,10,'Physical','ME','Good team sports',10,3),(28,10,'Creative','EE','Music talent excellent',10,3),
(29,10,'Social','ME','Sociable student',10,3),(29,10,'Spiritual','ME','Respectful',10,3),(29,10,'Emotional','AE','Needs emotional support',10,3),(29,10,'Physical','ME','Participates in games',10,3),(29,10,'Creative','ME','Enjoys drawing',10,3),
(30,10,'Social','ME','Good group member',10,3),(30,10,'Spiritual','ME','Good RE participation',10,3),(30,10,'Emotional','ME','Handles pressure well',10,3),(30,10,'Physical','ME','Good athletics',10,3),(30,10,'Creative','ME','Creative work good',10,3),
(31,10,'Social','ME','Good social skills',10,3),(31,10,'Spiritual','ME','Good moral values',10,3),(31,10,'Emotional','ME','Emotionally mature',10,3),(31,10,'Physical','EE','Sports excellence',10,3),(31,10,'Creative','ME','Creative arts enjoyed',10,3),
(32,10,'Social','EE','Excellent social leader',10,3),(32,10,'Spiritual','ME','Good RE participation',10,3),(32,10,'Emotional','EE','Very confident',10,3),(32,10,'Physical','ME','Good in PE',10,3),(32,10,'Creative','EE','Exceptional visual artist',10,3),
(33,10,'Social','ME','Works well with others',10,3),(33,10,'Spiritual','EE','Outstanding RE student',10,3),(33,10,'Emotional','ME','Good self-control',10,3),(33,10,'Physical','ME','Active in games',10,3),(33,10,'Creative','ME','Creative musician',10,3),
(34,10,'Social','ME','Friendly and warm',10,3),(34,10,'Spiritual','ME','Good moral character',10,3),(34,10,'Emotional','ME','Calm and focused',10,3),(34,10,'Physical','EE','Health education leader',10,3),(34,10,'Creative','ME','Enjoys performing arts',10,3),
(35,10,'Social','ME','Good team player',10,3),(35,10,'Spiritual','ME','Respectful student',10,3),(35,10,'Emotional','ME','Manages emotions ok',10,3),(35,10,'Physical','ME','Active student',10,3),(35,10,'Creative','ME','Enjoys drama',10,3),
-- Grade 7-9 T1
(6,10,'Social','EE','Excellent peer leader',10,3),(6,10,'Spiritual','ME','Good moral values',10,3),(6,10,'Emotional','EE','Very composed',10,3),(6,10,'Physical','ME','Good in PE',10,3),(6,10,'Creative','EE','Outstanding performing arts',10,3),
(36,10,'Social','ME','Good social skills',10,3),(36,10,'Spiritual','ME','Participates in RE',10,3),(36,10,'Emotional','ME','Manages emotions well',10,3),(36,10,'Physical','ME','Good coordination',10,3),(36,10,'Creative','ME','Creative problem solver',10,3),
(37,10,'Social','EE','Outstanding social student',10,3),(37,10,'Spiritual','ME','Good moral conduct',10,3),(37,10,'Emotional','ME','Emotionally stable',10,3),(37,10,'Physical','ME','Active in games',10,3),(37,10,'Creative','ME','Enjoys sports',10,3),
(38,10,'Social','ME','Friendly personality',10,3),(38,10,'Spiritual','ME','Respectful student',10,3),(38,10,'Emotional','AE','Needs emotional guidance',10,3),(38,10,'Physical','ME','Participates in PE',10,3),(38,10,'Creative','ME','Creative in music',10,3),
(39,10,'Social','ME','Good team member',10,3),(39,10,'Spiritual','ME','Good RE values',10,3),(39,10,'Emotional','ME','Good self-regulation',10,3),(39,10,'Physical','ME','Sporty student',10,3),(39,10,'Creative','EE','Exceptional agri creativity',10,3),
(7,10,'Social','ME','Friendly and cooperative',10,3),(7,10,'Spiritual','ME','Good moral values',10,3),(7,10,'Emotional','ME','Handles stress well',10,3),(7,10,'Physical','ME','Active in sports',10,3),(7,10,'Creative','ME','Creative in design',10,3),
(40,10,'Social','EE','Class social leader',10,3),(40,10,'Spiritual','ME','Good values',10,3),(40,10,'Emotional','EE','Excellent leadership',10,3),(40,10,'Physical','ME','Good athlete',10,3),(40,10,'Creative','EE','Outstanding performer',10,3),
(41,10,'Social','ME','Good social skills',10,3),(41,10,'Spiritual','ME','Good RE conduct',10,3),(41,10,'Emotional','ME','Emotionally balanced',10,3),(41,10,'Physical','ME','Sporty student',10,3),(41,10,'Creative','ME','Enjoys technical projects',10,3),
(42,10,'Social','ME','Very social student',10,3),(42,10,'Spiritual','ME','Good moral character',10,3),(42,10,'Emotional','ME','Manages emotions well',10,3),(42,10,'Physical','ME','Good in PE',10,3),(42,10,'Creative','ME','Visual arts talented',10,3),
(43,10,'Social','AE','Working on social skills',10,3),(43,10,'Spiritual','ME','Respectful student',10,3),(43,10,'Emotional','ME','Handles situations ok',10,3),(43,10,'Physical','ME','Active student',10,3),(43,10,'Creative','ME','Creative in design',10,3),
(44,10,'Social','EE','Outstanding student leader',10,3),(44,10,'Spiritual','ME','Good moral values',10,3),(44,10,'Emotional','EE','Excellent composure',10,3),(44,10,'Physical','ME','Good in PE',10,3),(44,10,'Creative','ME','Creative academic',10,3),
(45,10,'Social','ME','Good social skills',10,3),(45,10,'Spiritual','EE','Very active in RE',10,3),(45,10,'Emotional','ME','Manages emotions well',10,3),(45,10,'Physical','ME','Active in sports',10,3),(45,10,'Creative','EE','Excellent Pre-Tech projects',10,3),
(46,10,'Social','ME','Good team player',10,3),(46,10,'Spiritual','ME','Good moral conduct',10,3),(46,10,'Emotional','ME','Emotionally mature',10,3),(46,10,'Physical','ME','Good fitness level',10,3),(46,10,'Creative','EE','Exceptional musician',10,3),
(47,10,'Social','ME','Friendly personality',10,3),(47,10,'Spiritual','ME','Good RE participation',10,3),(47,10,'Emotional','ME','Handles peer situations well',10,3),(47,10,'Physical','ME','Active student',10,3),(47,10,'Creative','ME','Enjoys agri projects',10,3),
(48,10,'Social','ME','Works well in class',10,3),(48,10,'Spiritual','ME','Good moral values',10,3),(48,10,'Emotional','ME','Good self-regulation',10,3),(48,10,'Physical','ME','Active in PE',10,3),(48,10,'Creative','ME','Creative arts enjoyed',10,3);

-- Term 2 Behaviour (selected key students — same pattern, slight improvement)
INSERT IGNORE INTO `cbc_behaviour_assessment` (`student_id`,`exam_id`,`category`,`rating`,`remarks`,`session_id`,`branch_id`) VALUES
(1,11,'Social','EE','Outstanding peer role model',10,3),(1,11,'Spiritual','ME','Consistent moral values',10,3),(1,11,'Emotional','EE','Excellent emotional maturity',10,3),(1,11,'Physical','EE','Sports captain material',10,3),(1,11,'Creative','EE','Most creative student',10,3),
(3,11,'Social','ME','Good social skills',10,3),(3,11,'Spiritual','ME','Good moral conduct',10,3),(3,11,'Emotional','EE','Very composed leader',10,3),(3,11,'Physical','ME','Active in PE',10,3),(3,11,'Creative','ME','Creative improving',10,3),
(5,11,'Social','EE','Outstanding leader',10,3),(5,11,'Spiritual','ME','Good values',10,3),(5,11,'Emotional','EE','Excellent maturity',10,3),(5,11,'Physical','EE','Top athlete',10,3),(5,11,'Creative','ME','Creative learner',10,3),
(6,11,'Social','EE','Class representative',10,3),(6,11,'Spiritual','ME','Role model in RE',10,3),(6,11,'Emotional','EE','Excellent composure',10,3),(6,11,'Physical','ME','Good in PE',10,3),(6,11,'Creative','EE','Outstanding arts leader',10,3),
(44,11,'Social','EE','School prefect material',10,3),(44,11,'Spiritual','ME','Good moral values',10,3),(44,11,'Emotional','EE','Very mature leader',10,3),(44,11,'Physical','ME','Good athlete',10,3),(44,11,'Creative','ME','Creative academic',10,3),
(32,11,'Social','EE','Excellent social skills',10,3),(32,11,'Spiritual','ME','Good RE',10,3),(32,11,'Emotional','EE','Confident and mature',10,3),(32,11,'Physical','ME','Good in PE',10,3),(32,11,'Creative','EE','Outstanding visual artist',10,3);

SET FOREIGN_KEY_CHECKS = 1;
-- ================================================================
-- END OF 013c_cbc_assessments.sql
-- Next: run 013d_cbc_activities.sql
-- ================================================================
