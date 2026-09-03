-- ================================================================
-- 013b: CBC Sample Data - Strands, Sub-Strands, Learning Outcomes
-- Sunrise Academy (branch_id=3)
-- Run AFTER 013a_cbc_students.sql
-- ================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ================================================================
-- SECTION 4: CBC STRANDS (IDs 100-169)
-- Learning Areas: Lower Primary 100-107, Upper Primary 108-116,
--                 Junior Secondary 117-124
-- ================================================================
INSERT INTO `cbc_strands` (`id`,`learning_area_id`,`name`,`branch_id`,`created_at`,`updated_at`) VALUES
-- LOWER PRIMARY: Literacy Activities (LA 100)
(100,100,'Listening and Speaking',3,NOW(),NOW()),
(101,100,'Reading',3,NOW(),NOW()),
(102,100,'Writing',3,NOW(),NOW()),
-- LOWER PRIMARY: Kiswahili Language Activities (LA 101)
(103,101,'Kusikiliza na Kuzungumza',3,NOW(),NOW()),
(104,101,'Kusoma',3,NOW(),NOW()),
(105,101,'Kuandika',3,NOW(),NOW()),
-- LOWER PRIMARY: English Language Activities (LA 102)
(106,102,'Listening and Speaking',3,NOW(),NOW()),
(107,102,'Reading',3,NOW(),NOW()),
(108,102,'Writing',3,NOW(),NOW()),
-- LOWER PRIMARY: Mathematics Activities (LA 103)
(109,103,'Numbers',3,NOW(),NOW()),
(110,103,'Measurement',3,NOW(),NOW()),
(111,103,'Geometry and Spatial Sense',3,NOW(),NOW()),
-- LOWER PRIMARY: Environmental Activities (LA 104)
(112,104,'Living and Non-Living Things',3,NOW(),NOW()),
(113,104,'Our Environment',3,NOW(),NOW()),
-- LOWER PRIMARY: Hygiene and Nutrition Activities (LA 105)
(114,105,'Personal Hygiene',3,NOW(),NOW()),
(115,105,'Food and Nutrition',3,NOW(),NOW()),
-- LOWER PRIMARY: Religious Education Activities (LA 106)
(116,106,'Faith and Spiritual Growth',3,NOW(),NOW()),
(117,106,'Social Responsibilities',3,NOW(),NOW()),
-- LOWER PRIMARY: Movement and Creative Activities (LA 107)
(118,107,'Movement Skills',3,NOW(),NOW()),
(119,107,'Creative Arts',3,NOW(),NOW()),
(120,107,'Music and Rhythm',3,NOW(),NOW()),

-- UPPER PRIMARY: English (LA 108)
(121,108,'Listening and Speaking',3,NOW(),NOW()),
(122,108,'Reading',3,NOW(),NOW()),
(123,108,'Writing',3,NOW(),NOW()),
-- UPPER PRIMARY: Kiswahili (LA 109)
(124,109,'Kusikiliza na Kuzungumza',3,NOW(),NOW()),
(125,109,'Kusoma',3,NOW(),NOW()),
(126,109,'Kuandika',3,NOW(),NOW()),
-- UPPER PRIMARY: Mathematics (LA 110)
(127,110,'Numbers and Operations',3,NOW(),NOW()),
(128,110,'Algebra',3,NOW(),NOW()),
(129,110,'Measurement and Geometry',3,NOW(),NOW()),
(130,110,'Data Handling',3,NOW(),NOW()),
-- UPPER PRIMARY: Science and Technology (LA 111)
(131,111,'Scientific Investigation',3,NOW(),NOW()),
(132,111,'Living Things and Their Environment',3,NOW(),NOW()),
(133,111,'Technology and Innovation',3,NOW(),NOW()),
-- UPPER PRIMARY: Social Studies (LA 112)
(134,112,'Our Community and Country',3,NOW(),NOW()),
(135,112,'Global Citizenship',3,NOW(),NOW()),
-- UPPER PRIMARY: Religious Education (LA 113)
(136,113,'Faith and Values',3,NOW(),NOW()),
(137,113,'Moral Development',3,NOW(),NOW()),
-- UPPER PRIMARY: Creative Arts (LA 114)
(138,114,'Visual Arts',3,NOW(),NOW()),
(139,114,'Music',3,NOW(),NOW()),
(140,114,'Performing Arts',3,NOW(),NOW()),
-- UPPER PRIMARY: Physical and Health Education (LA 115)
(141,115,'Athletics and Physical Fitness',3,NOW(),NOW()),
(142,115,'Games and Sports',3,NOW(),NOW()),
(143,115,'Health and Wellness',3,NOW(),NOW()),
-- UPPER PRIMARY: Agriculture (LA 116)
(144,116,'Crop Production',3,NOW(),NOW()),
(145,116,'Animal Husbandry',3,NOW(),NOW()),

-- JUNIOR SECONDARY: English (LA 117)
(146,117,'Listening and Speaking',3,NOW(),NOW()),
(147,117,'Reading',3,NOW(),NOW()),
(148,117,'Writing',3,NOW(),NOW()),
-- JUNIOR SECONDARY: Kiswahili (LA 118)
(149,118,'Kusikiliza na Kuzungumza',3,NOW(),NOW()),
(150,118,'Kusoma',3,NOW(),NOW()),
(151,118,'Kuandika',3,NOW(),NOW()),
-- JUNIOR SECONDARY: Mathematics (LA 119)
(152,119,'Numbers and Operations',3,NOW(),NOW()),
(153,119,'Algebra and Functions',3,NOW(),NOW()),
(154,119,'Geometry and Measurement',3,NOW(),NOW()),
(155,119,'Statistics and Probability',3,NOW(),NOW()),
-- JUNIOR SECONDARY: Integrated Science (LA 120)
(156,120,'Physical Sciences',3,NOW(),NOW()),
(157,120,'Life Sciences',3,NOW(),NOW()),
(158,120,'Earth and Environment',3,NOW(),NOW()),
-- JUNIOR SECONDARY: Social Studies (LA 121)
(159,121,'History and Government',3,NOW(),NOW()),
(160,121,'Geography and Citizenship',3,NOW(),NOW()),
-- JUNIOR SECONDARY: Pre-Technical Studies (LA 122)
(161,122,'Engineering and Design',3,NOW(),NOW()),
(162,122,'Electricity and Electronics',3,NOW(),NOW()),
(163,122,'Woodwork and Metalwork',3,NOW(),NOW()),
-- JUNIOR SECONDARY: Agriculture (LA 123)
(164,123,'Crop Production',3,NOW(),NOW()),
(165,123,'Soil Science and Water Management',3,NOW(),NOW()),
(166,123,'Animal Production',3,NOW(),NOW()),
-- JUNIOR SECONDARY: Creative Arts and Sports (LA 124)
(167,124,'Visual Arts and Design',3,NOW(),NOW()),
(168,124,'Music and Dance',3,NOW(),NOW()),
(169,124,'Sports and Athletics',3,NOW(),NOW());

-- ================================================================
-- SECTION 5: CBC SUB-STRANDS (IDs 100-239, 2 per strand)
-- ================================================================
INSERT INTO `cbc_sub_strands` (`id`,`name`,`strand_id`,`learning_area_id`,`branch_id`) VALUES
-- Strand 100: Listening and Speaking (Literacy)
(100,'Oral Communication',100,100,3),
(101,'Phonological Awareness',100,100,3),
-- Strand 101: Reading (Literacy)
(102,'Word Recognition and Decoding',101,100,3),
(103,'Reading Comprehension',101,100,3),
-- Strand 102: Writing (Literacy)
(104,'Pre-Writing and Letter Formation',102,100,3),
(105,'Creative Writing',102,100,3),
-- Strand 103: Kusikiliza na Kuzungumza (Kiswahili)
(106,'Mazungumzo ya Kila Siku',103,101,3),
(107,'Ufahamu wa Kusikia',103,101,3),
-- Strand 104: Kusoma (Kiswahili)
(108,'Kusoma kwa Sauti',104,101,3),
(109,'Ufahamu wa Kusoma',104,101,3),
-- Strand 105: Kuandika (Kiswahili)
(110,'Uandishi wa Herufi',105,101,3),
(111,'Uandishi wa Sentensi',105,101,3),
-- Strand 106: Listening and Speaking (English)
(112,'Oral Fluency',106,102,3),
(113,'Listening Skills',106,102,3),
-- Strand 107: Reading (English)
(114,'Phonics and Decoding',107,102,3),
(115,'Reading for Meaning',107,102,3),
-- Strand 108: Writing (English)
(116,'Handwriting and Spelling',108,102,3),
(117,'Sentence and Paragraph Writing',108,102,3),
-- Strand 109: Numbers (Mathematics)
(118,'Counting and Place Value',109,103,3),
(119,'Addition and Subtraction',109,103,3),
-- Strand 110: Measurement (Mathematics)
(120,'Length, Mass and Capacity',110,103,3),
(121,'Time and Money',110,103,3),
-- Strand 111: Geometry (Mathematics)
(122,'2D and 3D Shapes',111,103,3),
(123,'Patterns and Symmetry',111,103,3),
-- Strand 112: Living and Non-Living Things
(124,'Characteristics of Living Things',112,104,3),
(125,'Properties of Non-Living Things',112,104,3),
-- Strand 113: Our Environment
(126,'Local Environment',113,104,3),
(127,'Environmental Conservation',113,104,3),
-- Strand 114: Personal Hygiene
(128,'Body Cleanliness',114,105,3),
(129,'Dental and Eye Care',114,105,3),
-- Strand 115: Food and Nutrition
(130,'Food Groups',115,105,3),
(131,'Healthy Eating Habits',115,105,3),
-- Strand 116: Faith and Spiritual Growth
(132,'Prayer and Worship',116,106,3),
(133,'Virtues and Values',116,106,3),
-- Strand 117: Social Responsibilities
(134,'Family and Community Roles',117,106,3),
(135,'Respect and Responsibility',117,106,3),
-- Strand 118: Movement Skills
(136,'Locomotor Skills',118,107,3),
(137,'Non-Locomotor Skills',118,107,3),
-- Strand 119: Creative Arts
(138,'Drawing and Painting',119,107,3),
(139,'Craft and Modelling',119,107,3),
-- Strand 120: Music and Rhythm
(140,'Singing and Chanting',120,107,3),
(141,'Percussion and Rhythm',120,107,3),

-- UPPER PRIMARY sub-strands
-- Strand 121: Listening and Speaking (English UP)
(142,'Oral Presentation',121,108,3),
(143,'Critical Listening',121,108,3),
-- Strand 122: Reading (English UP)
(144,'Intensive Reading',122,108,3),
(145,'Extensive Reading',122,108,3),
-- Strand 123: Writing (English UP)
(146,'Functional Writing',123,108,3),
(147,'Creative Writing',123,108,3),
-- Strand 124: Kusikiliza na Kuzungumza (Kiswahili UP)
(148,'Uwasilishaji wa Mdomo',124,109,3),
(149,'Kusikiliza kwa Makini',124,109,3),
-- Strand 125: Kusoma (Kiswahili UP)
(150,'Kusoma kwa Ufahamu',125,109,3),
(151,'Kusoma Kwa Anasa',125,109,3),
-- Strand 126: Kuandika (Kiswahili UP)
(152,'Uandishi wa Kiutendaji',126,109,3),
(153,'Uandishi wa Ubunifu',126,109,3),
-- Strand 127: Numbers and Operations
(154,'Whole Numbers and Fractions',127,110,3),
(155,'Multiplication and Division',127,110,3),
-- Strand 128: Algebra
(156,'Expressions and Equations',128,110,3),
(157,'Patterns and Sequences',128,110,3),
-- Strand 129: Measurement and Geometry
(158,'Perimeter, Area and Volume',129,110,3),
(159,'Angles and Constructions',129,110,3),
-- Strand 130: Data Handling
(160,'Data Collection and Organisation',130,110,3),
(161,'Interpretation of Graphs',130,110,3),
-- Strand 131: Scientific Investigation
(162,'Observation and Inquiry',131,111,3),
(163,'Experimentation and Recording',131,111,3),
-- Strand 132: Living Things
(164,'Plants and Animals',132,111,3),
(165,'Human Body Systems',132,111,3),
-- Strand 133: Technology
(166,'Basic Computing',133,111,3),
(167,'Technology in Daily Life',133,111,3),
-- Strand 134: Our Community
(168,'Kenya: People and Culture',134,112,3),
(169,'National Government and Leadership',134,112,3),
-- Strand 135: Global Citizenship
(170,'Africa and the World',135,112,3),
(171,'Human Rights and Responsibilities',135,112,3),
-- Strand 136: Faith and Values (RE UP)
(172,'Scripture and Doctrine',136,113,3),
(173,'Religious Practices',136,113,3),
-- Strand 137: Moral Development
(174,'Ethics and Decision Making',137,113,3),
(175,'Peace and Conflict Resolution',137,113,3),
-- Strand 138: Visual Arts
(176,'Drawing and Design',138,114,3),
(177,'Painting and Colour Theory',138,114,3),
-- Strand 139: Music (UP)
(178,'Singing and Voice',139,114,3),
(179,'Instruments and Composition',139,114,3),
-- Strand 140: Performing Arts
(180,'Drama and Theatre',140,114,3),
(181,'Dance and Movement',140,114,3),
-- Strand 141: Athletics
(182,'Running and Jumping',141,115,3),
(183,'Throwing Events',141,115,3),
-- Strand 142: Games and Sports
(184,'Ball Games',142,115,3),
(185,'Net and Wall Games',142,115,3),
-- Strand 143: Health and Wellness
(186,'Personal and Community Health',143,115,3),
(187,'First Aid and Safety',143,115,3),
-- Strand 144: Crop Production (UP)
(188,'Planting and Crop Care',144,116,3),
(189,'Harvesting and Post-Harvest',144,116,3),
-- Strand 145: Animal Husbandry
(190,'Common Farm Animals',145,116,3),
(191,'Animal Feeds and Health',145,116,3),

-- JUNIOR SECONDARY sub-strands
-- Strand 146: Listening and Speaking (English JS)
(192,'Debates and Discussions',146,117,3),
(193,'Oral Literature',146,117,3),
-- Strand 147: Reading (English JS)
(194,'Literary Texts',147,117,3),
(195,'Non-Literary Texts',147,117,3),
-- Strand 148: Writing (English JS)
(196,'Essay and Report Writing',148,117,3),
(197,'Creative and Imaginative Writing',148,117,3),
-- Strand 149: Kusikiliza na Kuzungumza (JS)
(198,'Midahalo na Majadiliano',149,118,3),
(199,'Fasihi ya Mdomo',149,118,3),
-- Strand 150: Kusoma (JS)
(200,'Fasihi ya Kiswahili',150,118,3),
(201,'Matini Yasiyo ya Fasihi',150,118,3),
-- Strand 151: Kuandika (JS)
(202,'Insha na Ripoti',151,118,3),
(203,'Uandishi wa Ubunifu',151,118,3),
-- Strand 152: Numbers and Operations (JS)
(204,'Integers and Rational Numbers',152,119,3),
(205,'Indices and Surds',152,119,3),
-- Strand 153: Algebra
(206,'Linear and Quadratic Equations',153,119,3),
(207,'Inequalities and Graphs',153,119,3),
-- Strand 154: Geometry and Measurement
(208,'Plane Geometry',154,119,3),
(209,'Trigonometry Basics',154,119,3),
-- Strand 155: Statistics and Probability
(210,'Data and Statistical Measures',155,119,3),
(211,'Probability',155,119,3),
-- Strand 156: Physical Sciences
(212,'Forces and Motion',156,120,3),
(213,'Energy: Heat, Light and Sound',156,120,3),
-- Strand 157: Life Sciences
(214,'Cell Biology',157,120,3),
(215,'Ecology and Food Webs',157,120,3),
-- Strand 158: Earth and Environment
(216,'Rocks, Minerals and Soils',158,120,3),
(217,'Climate and Weather',158,120,3),
-- Strand 159: History and Government
(218,'Pre-Colonial Kenya',159,121,3),
(219,'Colonial and Post-Colonial Kenya',159,121,3),
-- Strand 160: Geography and Citizenship
(220,'Physical Geography of Kenya',160,121,3),
(221,'Civic Rights and Duties',160,121,3),
-- Strand 161: Engineering and Design
(222,'Drawing and Technical Design',161,122,3),
(223,'Materials and Construction',161,122,3),
-- Strand 162: Electricity and Electronics
(224,'Basic Electrical Circuits',162,122,3),
(225,'Electronics Components',162,122,3),
-- Strand 163: Woodwork and Metalwork
(226,'Hand Tools and Safety',163,122,3),
(227,'Joints and Finishes',163,122,3),
-- Strand 164: Crop Production (JS)
(228,'Land Preparation and Planting',164,123,3),
(229,'Crop Nutrition and Pest Control',164,123,3),
-- Strand 165: Soil Science
(230,'Soil Types and Properties',165,123,3),
(231,'Irrigation and Drainage',165,123,3),
-- Strand 166: Animal Production (JS)
(232,'Livestock Breeds',166,123,3),
(233,'Animal Health and Feeding',166,123,3),
-- Strand 167: Visual Arts and Design
(234,'Design Elements and Principles',167,124,3),
(235,'Mixed Media and Crafts',167,124,3),
-- Strand 168: Music and Dance
(236,'Kenyan Traditional Music',168,124,3),
(237,'Contemporary Music',168,124,3),
-- Strand 169: Sports and Athletics
(238,'Track and Field',169,124,3),
(239,'Team Sports',169,124,3);

-- ================================================================
-- SECTION 6: CBC LEARNING OUTCOMES (IDs 100-239, 1 per sub-strand)
-- ================================================================
INSERT INTO `cbc_learning_outcomes` (`id`,`code`,`name`,`sub_strand_id`,`strand_id`,`learning_area_id`,`branch_id`) VALUES
(100,'LO1','Learner can communicate orally using appropriate vocabulary',100,100,100,3),
(101,'LO2','Learner demonstrates phonological awareness through rhymes and syllables',101,100,100,3),
(102,'LO3','Learner recognises and reads common words and simple sentences',102,101,100,3),
(103,'LO4','Learner reads with comprehension and answers questions',103,101,100,3),
(104,'LO5','Learner forms letters correctly and writes own name',104,102,100,3),
(105,'LO6','Learner writes simple sentences and short stories',105,102,100,3),
(106,'LO7','Learner communicates in Kiswahili in daily situations',106,103,101,3),
(107,'LO8','Learner understands spoken Kiswahili instructions',107,103,101,3),
(108,'LO9','Learner reads Kiswahili words and sentences aloud accurately',108,104,101,3),
(109,'LO10','Learner reads and answers questions on Kiswahili passages',109,104,101,3),
(110,'LO11','Learner writes Kiswahili letters and words neatly',110,105,101,3),
(111,'LO12','Learner writes simple Kiswahili sentences correctly',111,105,101,3),
(112,'LO13','Learner speaks English fluently in class discussions',112,106,102,3),
(113,'LO14','Learner follows spoken English instructions accurately',113,106,102,3),
(114,'LO15','Learner decodes new words using phonics strategies',114,107,102,3),
(115,'LO16','Learner reads and understands simple English texts',115,107,102,3),
(116,'LO17','Learner writes legibly with correct spelling',116,108,102,3),
(117,'LO18','Learner writes clear sentences and short paragraphs',117,108,102,3),
(118,'LO19','Learner counts, reads and writes numbers up to 1000',118,109,103,3),
(119,'LO20','Learner adds and subtracts numbers with regrouping',119,109,103,3),
(120,'LO21','Learner measures length, mass and capacity using standard units',120,110,103,3),
(121,'LO22','Learner tells time and uses money in practical situations',121,110,103,3),
(122,'LO23','Learner identifies and describes 2D and 3D shapes',122,111,103,3),
(123,'LO24','Learner identifies and continues patterns',123,111,103,3),
(124,'LO25','Learner distinguishes between living and non-living things',124,112,104,3),
(125,'LO26','Learner describes properties of common non-living materials',125,112,104,3),
(126,'LO27','Learner describes features of the local environment',126,113,104,3),
(127,'LO28','Learner practises conservation in daily activities',127,113,104,3),
(128,'LO29','Learner maintains personal cleanliness daily',128,114,105,3),
(129,'LO30','Learner practises proper dental and eye hygiene',129,114,105,3),
(130,'LO31','Learner identifies and classifies food groups',130,115,105,3),
(131,'LO32','Learner makes healthy food choices',131,115,105,3),
(132,'LO33','Learner participates in prayer and worship activities',132,116,106,3),
(133,'LO34','Learner demonstrates core virtues in daily life',133,116,106,3),
(134,'LO35','Learner understands family and community roles',134,117,106,3),
(135,'LO36','Learner shows respect and responsibility in school',135,117,106,3),
(136,'LO37','Learner performs basic locomotor skills confidently',136,118,107,3),
(137,'LO38','Learner performs balancing and stretching activities',137,118,107,3),
(138,'LO39','Learner creates simple drawings and paintings',138,119,107,3),
(139,'LO40','Learner makes simple craft items using local materials',139,119,107,3),
(140,'LO41','Learner sings songs in tune and with correct words',140,120,107,3),
(141,'LO42','Learner claps and taps rhythmic patterns accurately',141,120,107,3),
-- Upper Primary outcomes
(142,'LO43','Learner presents orally with clarity and confidence',142,121,108,3),
(143,'LO44','Learner listens critically and responds appropriately',143,121,108,3),
(144,'LO45','Learner reads literary texts with expression',144,122,108,3),
(145,'LO46','Learner reads widely and independently for pleasure',145,122,108,3),
(146,'LO47','Learner writes letters, reports and summaries',146,123,108,3),
(147,'LO48','Learner writes imaginative stories and poems',147,123,108,3),
(148,'LO49','Learner communicates effectively in Kiswahili discussions',148,124,109,3),
(149,'LO50','Learner listens attentively and identifies key ideas',149,124,109,3),
(150,'LO51','Learner reads Kiswahili literature with understanding',150,125,109,3),
(151,'LO52','Learner reads for enjoyment and information in Kiswahili',151,125,109,3),
(152,'LO53','Learner writes functional Kiswahili texts accurately',152,126,109,3),
(153,'LO54','Learner writes creative compositions in Kiswahili',153,126,109,3),
(154,'LO55','Learner works with whole numbers and fractions fluently',154,127,110,3),
(155,'LO56','Learner multiplies and divides with accuracy',155,127,110,3),
(156,'LO57','Learner forms and solves simple algebraic equations',156,128,110,3),
(157,'LO58','Learner identifies and extends number patterns',157,128,110,3),
(158,'LO59','Learner calculates perimeter, area and volume',158,129,110,3),
(159,'LO60','Learner constructs angles and geometric figures',159,129,110,3),
(160,'LO61','Learner collects and organises data in tables and charts',160,130,110,3),
(161,'LO62','Learner interprets graphs and draws conclusions',161,130,110,3),
(162,'LO63','Learner conducts simple investigations and records results',162,131,111,3),
(163,'LO64','Learner designs and carries out fair experiments',163,131,111,3),
(164,'LO65','Learner classifies plants and animals by characteristics',164,132,111,3),
(165,'LO66','Learner describes human body systems and functions',165,132,111,3),
(166,'LO67','Learner uses basic computing skills appropriately',166,133,111,3),
(167,'LO68','Learner identifies technology that improves daily life',167,133,111,3),
(168,'LO69','Learner describes Kenya''s people, culture and regions',168,134,112,3),
(169,'LO70','Learner explains the structure of national government',169,134,112,3),
(170,'LO71','Learner identifies Kenya''s position in Africa and the world',170,135,112,3),
(171,'LO72','Learner advocates for human rights and responsibilities',171,135,112,3),
(172,'LO73','Learner recites and applies scripture in daily life',172,136,113,3),
(173,'LO74','Learner participates in religious practices respectfully',173,136,113,3),
(174,'LO75','Learner makes ethical decisions based on values',174,137,113,3),
(175,'LO76','Learner applies peace-building strategies in conflicts',175,137,113,3),
(176,'LO77','Learner draws and designs using elements of art',176,138,114,3),
(177,'LO78','Learner mixes colours and applies painting techniques',177,138,114,3),
(178,'LO79','Learner sings in parts with correct pitch and tone',178,139,114,3),
(179,'LO80','Learner plays simple instruments in a group',179,139,114,3),
(180,'LO81','Learner performs in school drama productions',180,140,114,3),
(181,'LO82','Learner performs traditional and modern dances',181,140,114,3),
(182,'LO83','Learner participates in running and jumping events',182,141,115,3),
(183,'LO84','Learner throws accurately in athletics activities',183,141,115,3),
(184,'LO85','Learner plays team ball games following rules',184,142,115,3),
(185,'LO86','Learner participates in net and wall game activities',185,142,115,3),
(186,'LO87','Learner practises personal and community health habits',186,143,115,3),
(187,'LO88','Learner applies first aid for common injuries',187,143,115,3),
(188,'LO89','Learner plants, waters and tends crops correctly',188,144,116,3),
(189,'LO90','Learner harvests and stores crops appropriately',189,144,116,3),
(190,'LO91','Learner identifies and cares for common farm animals',190,145,116,3),
(191,'LO92','Learner prepares appropriate animal feeds',191,145,116,3),
-- Junior Secondary outcomes
(192,'LO93','Learner debates and discusses issues persuasively',192,146,117,3),
(193,'LO94','Learner appreciates and performs oral literature',193,146,117,3),
(194,'LO95','Learner analyses literary texts for themes and style',194,147,117,3),
(195,'LO96','Learner extracts information from non-literary texts',195,147,117,3),
(196,'LO97','Learner writes well-structured essays and reports',196,148,117,3),
(197,'LO98','Learner composes creative fiction and poetry',197,148,117,3),
(198,'LO99','Learner leads and participates in Kiswahili debates',198,149,118,3),
(199,'LO100','Learner recites and performs Kiswahili oral literature',199,149,118,3),
(200,'LO101','Learner analyses Kiswahili literary works critically',200,150,118,3),
(201,'LO102','Learner reads Kiswahili non-literary texts for information',201,150,118,3),
(202,'LO103','Learner writes Kiswahili essays and formal letters',202,151,118,3),
(203,'LO104','Learner writes Kiswahili short stories and compositions',203,151,118,3),
(204,'LO105','Learner works with integers, fractions and decimals',204,152,119,3),
(205,'LO106','Learner applies laws of indices and surds',205,152,119,3),
(206,'LO107','Learner solves linear and quadratic equations',206,153,119,3),
(207,'LO108','Learner graphs inequalities on number lines',207,153,119,3),
(208,'LO109','Learner applies Pythagoras theorem and circle theorems',208,154,119,3),
(209,'LO110','Learner applies basic trigonometric ratios',209,154,119,3),
(210,'LO111','Learner calculates mean, median, mode and range',210,155,119,3),
(211,'LO112','Learner calculates simple and combined probabilities',211,155,119,3),
(212,'LO113','Learner explains forces, motion and Newton''s laws',212,156,120,3),
(213,'LO114','Learner investigates energy forms and transformations',213,156,120,3),
(214,'LO115','Learner describes cell structure and functions',214,157,120,3),
(215,'LO116','Learner constructs and interprets food webs',215,157,120,3),
(216,'LO117','Learner identifies rock types and soil formation',216,158,120,3),
(217,'LO118','Learner explains weather patterns and climate change',217,158,120,3),
(218,'LO119','Learner describes pre-colonial Kenyan communities',218,159,121,3),
(219,'LO120','Learner analyses the impact of colonialism on Kenya',219,159,121,3),
(220,'LO121','Learner interprets physical maps of Kenya',220,160,121,3),
(221,'LO122','Learner explains civic rights and national responsibilities',221,160,121,3),
(222,'LO123','Learner produces technical drawings using instruments',222,161,122,3),
(223,'LO124','Learner selects and uses appropriate construction materials',223,161,122,3),
(224,'LO125','Learner connects simple series and parallel circuits',224,162,122,3),
(225,'LO126','Learner identifies and uses basic electronic components',225,162,122,3),
(226,'LO127','Learner uses hand tools safely in woodwork',226,163,122,3),
(227,'LO128','Learner applies joints and surface finishes in projects',227,163,122,3),
(228,'LO129','Learner prepares land and plants crops correctly',228,164,123,3),
(229,'LO130','Learner applies crop nutrition and pest management',229,164,123,3),
(230,'LO131','Learner identifies and describes soil types',230,165,123,3),
(231,'LO132','Learner designs simple irrigation schemes',231,165,123,3),
(232,'LO133','Learner identifies livestock breeds and their products',232,166,123,3),
(233,'LO134','Learner manages animal health and feeding programmes',233,166,123,3),
(234,'LO135','Learner applies design elements in artwork',234,167,124,3),
(235,'LO136','Learner creates mixed media art pieces',235,167,124,3),
(236,'LO137','Learner performs Kenyan traditional songs and dances',236,168,124,3),
(237,'LO138','Learner composes simple contemporary music',237,168,124,3),
(238,'LO139','Learner competes in track and field events',238,169,124,3),
(239,'LO140','Learner plays team sports applying rules and tactics',239,169,124,3);

SET FOREIGN_KEY_CHECKS = 1;
-- ================================================================
-- END OF 013b_cbc_strands.sql
-- Next: run 013c_cbc_assessments.sql
-- ================================================================
