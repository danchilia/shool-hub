-- CBC Step 8: Holistic Development Profile
-- Tables: cbc_holistic_domains, cbc_holistic_indicators, cbc_holistic_ratings

CREATE TABLE IF NOT EXISTS `cbc_holistic_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(150) NOT NULL,
  `description` text,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_hd_branch` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cbc_holistic_indicators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_hi_domain` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cbc_holistic_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `indicator_id` int(11) NOT NULL,
  `rating` varchar(3) DEFAULT NULL,
  `remarks` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_holistic` (`student_id`,`exam_id`,`indicator_id`),
  KEY `idx_hr_branch_student` (`branch_id`,`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Seed 7 standard CBC Holistic Development domains (branch_id = 0 = global)
INSERT INTO `cbc_holistic_domains` (`branch_id`, `name`, `description`, `sort_order`) VALUES
(0, 'Communication and Collaboration', 'Ability to express ideas clearly and work effectively with others', 1),
(0, 'Creativity and Imagination', 'Ability to generate novel ideas and produce original work', 2),
(0, 'Critical Thinking and Problem Solving', 'Ability to analyse situations and make informed decisions', 3),
(0, 'Citizenship', 'Values, attitudes and participation in community and civic life', 4),
(0, 'Digital Literacy', 'Responsible and effective use of digital technologies', 5),
(0, 'Learning to Learn', 'Self-management, goal setting and reflection on learning', 6),
(0, 'Physical Health and Wellbeing', 'Physical fitness, health habits and personal wellbeing', 7);

-- Indicators for Communication and Collaboration
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Expresses ideas clearly in speech and writing', 1 FROM `cbc_holistic_domains` WHERE `name` = 'Communication and Collaboration' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Listens actively and respects others\' views', 2 FROM `cbc_holistic_domains` WHERE `name` = 'Communication and Collaboration' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Collaborates effectively in group tasks', 3 FROM `cbc_holistic_domains` WHERE `name` = 'Communication and Collaboration' AND branch_id = 0 LIMIT 1;

-- Indicators for Creativity and Imagination
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Generates original and innovative ideas', 1 FROM `cbc_holistic_domains` WHERE `name` = 'Creativity and Imagination' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Shows initiative and takes creative risks', 2 FROM `cbc_holistic_domains` WHERE `name` = 'Creativity and Imagination' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Produces work that demonstrates imagination', 3 FROM `cbc_holistic_domains` WHERE `name` = 'Creativity and Imagination' AND branch_id = 0 LIMIT 1;

-- Indicators for Critical Thinking and Problem Solving
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Identifies and analyses problems systematically', 1 FROM `cbc_holistic_domains` WHERE `name` = 'Critical Thinking and Problem Solving' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Makes informed and reasoned decisions', 2 FROM `cbc_holistic_domains` WHERE `name` = 'Critical Thinking and Problem Solving' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Evaluates evidence before drawing conclusions', 3 FROM `cbc_holistic_domains` WHERE `name` = 'Critical Thinking and Problem Solving' AND branch_id = 0 LIMIT 1;

-- Indicators for Citizenship
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Demonstrates respect and integrity towards others', 1 FROM `cbc_holistic_domains` WHERE `name` = 'Citizenship' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Actively participates in school and community activities', 2 FROM `cbc_holistic_domains` WHERE `name` = 'Citizenship' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Upholds national values and responsible citizenship', 3 FROM `cbc_holistic_domains` WHERE `name` = 'Citizenship' AND branch_id = 0 LIMIT 1;

-- Indicators for Digital Literacy
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Uses digital tools effectively for learning', 1 FROM `cbc_holistic_domains` WHERE `name` = 'Digital Literacy' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Accesses and evaluates online information responsibly', 2 FROM `cbc_holistic_domains` WHERE `name` = 'Digital Literacy' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Demonstrates online safety and ethical digital behaviour', 3 FROM `cbc_holistic_domains` WHERE `name` = 'Digital Literacy' AND branch_id = 0 LIMIT 1;

-- Indicators for Learning to Learn
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Sets personal learning goals and works toward them', 1 FROM `cbc_holistic_domains` WHERE `name` = 'Learning to Learn' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Manages time and organises tasks independently', 2 FROM `cbc_holistic_domains` WHERE `name` = 'Learning to Learn' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Reflects on own learning and seeks improvement', 3 FROM `cbc_holistic_domains` WHERE `name` = 'Learning to Learn' AND branch_id = 0 LIMIT 1;

-- Indicators for Physical Health and Wellbeing
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Participates actively in physical education and sport', 1 FROM `cbc_holistic_domains` WHERE `name` = 'Physical Health and Wellbeing' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Maintains personal hygiene and healthy habits', 2 FROM `cbc_holistic_domains` WHERE `name` = 'Physical Health and Wellbeing' AND branch_id = 0 LIMIT 1;
INSERT INTO `cbc_holistic_indicators` (`domain_id`, `name`, `sort_order`)
SELECT id, 'Makes healthy food and lifestyle choices', 3 FROM `cbc_holistic_domains` WHERE `name` = 'Physical Health and Wellbeing' AND branch_id = 0 LIMIT 1;

-- Permission
INSERT INTO `permission` (`module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`)
SELECT 20, 'CBC Holistic Profile', 'cbc_holistic', 1, 1, 1, 1, NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permission` WHERE `prefix` = 'cbc_holistic');
