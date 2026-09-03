-- ================================================================
-- 013a: CBC Sample Data - Parents, Students, Enrollments
-- Sunrise Academy (branch_id=3, session_id=10)
-- Run AFTER sample_school_data.sql
-- All passwords: 123456
-- ================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ================================================================
-- SECTION 1: NEW PARENTS (IDs 6-10)
-- ================================================================
INSERT INTO `parent` (`id`,`name`,`relation`,`father_name`,`mother_name`,`occupation`,`income`,`education`,`email`,`mobileno`,`address`,`city`,`state`,`branch_id`,`photo`) VALUES
(6,  'Elizabeth Adhiambo', 'Mother', 'John Adhiambo',  'Elizabeth Adhiambo', 'Nurse',          '55000',  'Degree',    'elizabeth.adhiambo@gmail.com', '+254722200006', 'Umoja, Nairobi',     'Nairobi', 'Nairobi', 3, 'defualt.png'),
(7,  'Thomas Njenga',      'Father', 'Thomas Njenga',  'Agnes Njenga',       'Driver',         '35000',  'Secondary', 'thomas.njenga@gmail.com',      '+254722200007', 'Mathare, Nairobi',   'Nairobi', 'Nairobi', 3, 'defualt.png'),
(8,  'Catherine Wambua',   'Mother', 'James Wambua',   'Catherine Wambua',   'Shopkeeper',     '42000',  'Secondary', 'catherine.wambua@gmail.com',   '+254722200008', 'Kayole, Nairobi',    'Nairobi', 'Nairobi', 3, 'defualt.png'),
(9,  'Francis Koech',      'Father', 'Francis Koech',  'Lilian Koech',       'Accountant',     '90000',  'Degree',    'francis.koech@gmail.com',      '+254722200009', 'Kileleshwa, Nairobi','Nairobi', 'Nairobi', 3, 'defualt.png'),
(10, 'Beatrice Auma',      'Mother', 'Michael Auma',   'Beatrice Auma',      'Civil Servant',  '70000',  'Degree',    'beatrice.auma@gmail.com',      '+254722200010', 'Ruiru, Kiambu',      'Nairobi', 'Nairobi', 3, 'defualt.png');

INSERT INTO `login_credential` (`user_id`,`username`,`password`,`role`,`active`) VALUES
(6,  'elizabeth.adhiambo@gmail.com', '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe', 6, 1),
(7,  'thomas.njenga@gmail.com',      '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe', 6, 1),
(8,  'catherine.wambua@gmail.com',   '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe', 6, 1),
(9,  'francis.koech@gmail.com',      '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe', 6, 1),
(10, 'beatrice.auma@gmail.com',      '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe', 6, 1);

-- ================================================================
-- SECTION 2: NEW STUDENTS (IDs 11-58)
-- All passwords: 123456
-- ================================================================
INSERT INTO `student` (`id`,`register_no`,`upi_number`,`admission_date`,`first_name`,`last_name`,`gender`,`birthday`,`religion`,`caste`,`blood_group`,`mother_tongue`,`current_address`,`permanent_address`,`city`,`state`,`mobileno`,`category_id`,`email`,`parent_id`,`route_id`,`vehicle_id`,`hostel_id`,`room_id`,`previous_details`,`photo`) VALUES
-- Grade 1 East (class 102) — born ~2020
(11,'SAN-00011','UPI2026011','2026-01-06','James',    'Adhiambo', 'male',  '2020-03-14','Christian','','A+', 'English',  'Umoja, Nairobi',     'Umoja, Nairobi',     'Nairobi','Nairobi','+254722300011',10,'james.adhiambo@student.sunrise.ke',    6,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(12,'SAN-00012','UPI2026012','2026-01-06','Rose',     'Njenga',   'female','2020-07-22','Christian','','O+', 'Kiswahili','Mathare, Nairobi',   'Mathare, Nairobi',   'Nairobi','Nairobi','+254722300012',10,'rose.njenga@student.sunrise.ke',        7,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(13,'SAN-00013','UPI2026013','2026-01-06','Samuel',   'Ochieng',  'male',  '2019-11-05','Christian','','B+', 'Dholuo',   'Kayole, Nairobi',    'Kayole, Nairobi',    'Nairobi','Nairobi','+254722300013',10,'samuel.ochieng@student.sunrise.ke',     8,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(14,'SAN-00014','UPI2026014','2026-01-06','Faith',    'Kimani',   'female','2020-01-18','Christian','','A-', 'English',  'Kileleshwa, Nairobi','Kileleshwa, Nairobi','Nairobi','Nairobi','+254722300014',10,'faith.kimani@student.sunrise.ke',       9,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
-- Grade 2 West (class 103) — born ~2019
(15,'SAN-00015','UPI2026015','2026-01-06','Thomas',   'Kamau',    'male',  '2019-04-10','Christian','','O+', 'English',  'Ruiru, Kiambu',      'Ruiru, Kiambu',      'Nairobi','Nairobi','+254722300015',10,'thomas.kamau@student.sunrise.ke',       10,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(16,'SAN-00016','UPI2026016','2026-01-06','Grace',    'Oloo',     'female','2019-08-25','Christian','','B+', 'Dholuo',   'Umoja, Nairobi',     'Umoja, Nairobi',     'Nairobi','Nairobi','+254722300016',10,'grace.oloo@student.sunrise.ke',          6,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(17,'SAN-00017','UPI2026017','2026-01-06','Kenneth',  'Waweru',   'male',  '2019-02-14','Christian','','A+', 'Kiswahili','Mathare, Nairobi',   'Mathare, Nairobi',   'Nairobi','Nairobi','+254722300017',10,'kenneth.waweru@student.sunrise.ke',     7,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(18,'SAN-00018','UPI2026018','2026-01-06','Lydia',    'Mutua',    'female','2019-06-30','Christian','','O-', 'Kamba',    'Kayole, Nairobi',    'Kayole, Nairobi',    'Nairobi','Nairobi','+254722300018',10,'lydia.mutua@student.sunrise.ke',         8,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
-- Grade 3 East (class 104) — born ~2018
(19,'SAN-00019','UPI2026019','2026-01-06','Anthony',  'Nganga',   'male',  '2018-05-20','Christian','','B-', 'Kiswahili','Kileleshwa, Nairobi','Kileleshwa, Nairobi','Nairobi','Nairobi','+254722300019',10,'anthony.nganga@student.sunrise.ke',     9,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(20,'SAN-00020','UPI2026020','2026-01-06','Beatrice', 'Achieng',  'female','2018-09-12','Christian','','A+', 'Dholuo',   'Ruiru, Kiambu',      'Ruiru, Kiambu',      'Nairobi','Nairobi','+254722300020',10,'beatrice.achieng@student.sunrise.ke',   10,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(21,'SAN-00021','UPI2026021','2026-01-06','Collins',  'Mutura',   'male',  '2018-01-08','Christian','','O+', 'English',  'Umoja, Nairobi',     'Umoja, Nairobi',     'Nairobi','Nairobi','+254722300021',10,'collins.mutura@student.sunrise.ke',     6,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(22,'SAN-00022','UPI2026022','2026-01-06','Dorothy',  'Kerubo',   'female','2018-11-03','Christian','','AB+','Gusii',    'Mathare, Nairobi',   'Mathare, Nairobi',   'Nairobi','Nairobi','+254722300022',10,'dorothy.kerubo@student.sunrise.ke',     7,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
-- Grade 4 East (class 105) — born ~2017
(23,'SAN-00023','UPI2026023','2026-01-06','Emmanuel', 'Kipkurui', 'male',  '2017-03-22','Christian','','B+', 'Kalenjin', 'Kayole, Nairobi',    'Kayole, Nairobi',    'Nairobi','Nairobi','+254722300023',10,'emmanuel.kipkurui@student.sunrise.ke',  8,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(24,'SAN-00024','UPI2026024','2026-01-06','Felicity', 'Wangari',  'female','2017-07-16','Christian','','A-', 'Kikuyu',   'Kileleshwa, Nairobi','Kileleshwa, Nairobi','Nairobi','Nairobi','+254722300024',10,'felicity.wangari@student.sunrise.ke',   9,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(25,'SAN-00025','UPI2026025','2026-01-06','George',   'Maina',    'male',  '2017-11-29','Christian','','O+', 'English',  'Ruiru, Kiambu',      'Ruiru, Kiambu',      'Nairobi','Nairobi','+254722300025',10,'george.maina@student.sunrise.ke',       10,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(26,'SAN-00026','UPI2026026','2026-01-06','Harriet',  'Anyango',  'female','2017-05-08','Christian','','B-', 'Dholuo',   'Umoja, Nairobi',     'Umoja, Nairobi',     'Nairobi','Nairobi','+254722300026',10,'harriet.anyango@student.sunrise.ke',    6,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
-- Grade 5 East (class 106) — born ~2016
(27,'SAN-00027','UPI2026027','2026-01-06','Isaac',    'Njoroge',  'male',  '2016-02-14','Christian','','A+', 'Kikuyu',   'Mathare, Nairobi',   'Mathare, Nairobi',   'Nairobi','Nairobi','+254722300027',10,'isaac.njoroge@student.sunrise.ke',      7,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(28,'SAN-00028','UPI2026028','2026-01-06','Joyce',    'Cherono',  'female','2016-06-20','Christian','','O+', 'Kalenjin', 'Kayole, Nairobi',    'Kayole, Nairobi',    'Nairobi','Nairobi','+254722300028',10,'joyce.cherono@student.sunrise.ke',      8,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(29,'SAN-00029','UPI2026029','2026-01-06','Lawrence', 'Mugo',     'male',  '2016-10-05','Christian','','B+', 'Kikuyu',   'Kileleshwa, Nairobi','Kileleshwa, Nairobi','Nairobi','Nairobi','+254722300029',10,'lawrence.mugo@student.sunrise.ke',     9,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(30,'SAN-00030','UPI2026030','2026-01-06','Margaret', 'Cherop',   'female','2016-04-18','Christian','','AB-','Kalenjin', 'Ruiru, Kiambu',      'Ruiru, Kiambu',      'Nairobi','Nairobi','+254722300030',10,'margaret.cherop@student.sunrise.ke',    10,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
-- Grade 6 East (class 107) — born ~2015
(31,'SAN-00031','UPI2026031','2026-01-06','Nathan',   'Kamande',  'male',  '2015-01-25','Christian','','O+', 'English',  'Umoja, Nairobi',     'Umoja, Nairobi',     'Nairobi','Nairobi','+254722300031',10,'nathan.kamande@student.sunrise.ke',     6,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(32,'SAN-00032','UPI2026032','2026-01-06','Olivia',   'Nyambura', 'female','2015-05-12','Christian','','A-', 'Kikuyu',   'Mathare, Nairobi',   'Mathare, Nairobi',   'Nairobi','Nairobi','+254722300032',10,'olivia.nyambura@student.sunrise.ke',    7,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(33,'SAN-00033','UPI2026033','2026-01-06','Paul',     'Ochieng',  'male',  '2015-09-30','Christian','','B+', 'Dholuo',   'Kayole, Nairobi',    'Kayole, Nairobi',    'Nairobi','Nairobi','+254722300033',10,'paul.ochieng@student.sunrise.ke',       8,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(34,'SAN-00034','UPI2026034','2026-01-06','Queen',    'Akinyi',   'female','2015-03-07','Christian','','O-', 'Dholuo',   'Kileleshwa, Nairobi','Kileleshwa, Nairobi','Nairobi','Nairobi','+254722300034',10,'queen.akinyi@student.sunrise.ke',       9,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(35,'SAN-00035','UPI2026035','2026-01-06','Raymond',  'Kiptoo',   'male',  '2015-07-19','Christian','','A+', 'Kalenjin', 'Ruiru, Kiambu',      'Ruiru, Kiambu',      'Nairobi','Nairobi','+254722300035',10,'raymond.kiptoo@student.sunrise.ke',     10,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
-- Grade 7 East (class 108) — born ~2014
(36,'SAN-00036','UPI2026036','2026-01-06','Sarah',    'Gacheri',  'female','2014-02-08','Christian','','B+', 'Meru',     'Umoja, Nairobi',     'Umoja, Nairobi',     'Nairobi','Nairobi','+254722300036',10,'sarah.gacheri@student.sunrise.ke',      6,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(37,'SAN-00037','UPI2026037','2026-01-06','Timothy',  'Ngang''a', 'male',  '2014-06-15','Christian','','O+', 'Kikuyu',   'Mathare, Nairobi',   'Mathare, Nairobi',   'Nairobi','Nairobi','+254722300037',10,'timothy.nganga@student.sunrise.ke',     7,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(38,'SAN-00038','UPI2026038','2026-01-06','Ursula',   'Adhiambo', 'female','2014-10-22','Christian','','A-', 'Dholuo',   'Kayole, Nairobi',    'Kayole, Nairobi',    'Nairobi','Nairobi','+254722300038',10,'ursula.adhiambo@student.sunrise.ke',    8,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(39,'SAN-00039','UPI2026039','2026-01-06','Vincent',  'Kariuki',  'male',  '2014-04-11','Christian','','AB+','Kikuyu',   'Kileleshwa, Nairobi','Kileleshwa, Nairobi','Nairobi','Nairobi','+254722300039',10,'vincent.kariuki@student.sunrise.ke',    9,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
-- Grade 8 West (class 109) — born ~2013
(40,'SAN-00040','UPI2026040','2026-01-06','Wendy',    'Atieno',   'female','2013-01-17','Christian','','O+', 'Dholuo',   'Ruiru, Kiambu',      'Ruiru, Kiambu',      'Nairobi','Nairobi','+254722300040',10,'wendy.atieno@student.sunrise.ke',       10,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(41,'SAN-00041','UPI2026041','2026-01-06','Xavier',   'Mutuku',   'male',  '2013-05-28','Christian','','B+', 'Kamba',    'Umoja, Nairobi',     'Umoja, Nairobi',     'Nairobi','Nairobi','+254722300041',10,'xavier.mutuku@student.sunrise.ke',      6,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(42,'SAN-00042','UPI2026042','2026-01-06','Yvonne',   'Ndunge',   'female','2013-09-04','Christian','','A+', 'Kamba',    'Mathare, Nairobi',   'Mathare, Nairobi',   'Nairobi','Nairobi','+254722300042',10,'yvonne.ndunge@student.sunrise.ke',      7,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(43,'SAN-00043','UPI2026043','2026-01-06','Zachary',  'Kirui',    'male',  '2013-03-19','Christian','','O-', 'Kalenjin', 'Kayole, Nairobi',    'Kayole, Nairobi',    'Nairobi','Nairobi','+254722300043',10,'zachary.kirui@student.sunrise.ke',      8,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
-- Grade 9 East (class 110) — born ~2012
(44,'SAN-00044','UPI2026044','2026-01-06','Abigail',  'Chesang',  'female','2012-02-25','Christian','','A-', 'Kalenjin', 'Kileleshwa, Nairobi','Kileleshwa, Nairobi','Nairobi','Nairobi','+254722300044',10,'abigail.chesang@student.sunrise.ke',    9,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(45,'SAN-00045','UPI2026045','2026-01-06','Benjamin', 'Koros',    'male',  '2012-06-11','Christian','','B+', 'Kalenjin', 'Ruiru, Kiambu',      'Ruiru, Kiambu',      'Nairobi','Nairobi','+254722300045',10,'benjamin.koros@student.sunrise.ke',     10,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(46,'SAN-00046','UPI2026046','2026-01-06','Christine','Odero',    'female','2012-10-30','Christian','','O+', 'Dholuo',   'Umoja, Nairobi',     'Umoja, Nairobi',     'Nairobi','Nairobi','+254722300046',10,'christine.odero@student.sunrise.ke',    6,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(47,'SAN-00047','UPI2026047','2026-01-06','Daniel',   'Onyango',  'male',  '2012-04-16','Christian','','AB-','Dholuo',   'Mathare, Nairobi',   'Mathare, Nairobi',   'Nairobi','Nairobi','+254722300047',10,'daniel.onyango@student.sunrise.ke',     7,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(48,'SAN-00048','UPI2026048','2026-01-06','Esther',   'Chepngeno','female','2012-08-23','Christian','','A+', 'Kalenjin', 'Kayole, Nairobi',    'Kayole, Nairobi',    'Nairobi','Nairobi','+254722300048',10,'esther.chepngeno@student.sunrise.ke',   8,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
-- PP1 East (class 100) — born ~2022
(49,'SAN-00049','UPI2026049','2026-01-06','Felix',    'Kimani',   'male',  '2022-03-10','Christian','','O+', 'Kikuyu',   'Kileleshwa, Nairobi','Kileleshwa, Nairobi','Nairobi','Nairobi','+254722300049',10,'felix.kimani@student.sunrise.ke',       9,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(50,'SAN-00050','UPI2026050','2026-01-06','Gloria',   'Wanjiru',  'female','2022-07-19','Christian','','A-', 'Kikuyu',   'Ruiru, Kiambu',      'Ruiru, Kiambu',      'Nairobi','Nairobi','+254722300050',10,'gloria.wanjiru@student.sunrise.ke',     10,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(51,'SAN-00051','UPI2026051','2026-01-06','Henry',    'Odhiambo', 'male',  '2022-01-05','Christian','','B+', 'Dholuo',   'Umoja, Nairobi',     'Umoja, Nairobi',     'Nairobi','Nairobi','+254722300051',10,'henry.odhiambo@student.sunrise.ke',     6,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(52,'SAN-00052','UPI2026052','2026-01-06','Irene',    'Chepkoech','female','2022-05-28','Christian','','O+', 'Kalenjin', 'Mathare, Nairobi',   'Mathare, Nairobi',   'Nairobi','Nairobi','+254722300052',10,'irene.chepkoech@student.sunrise.ke',    7,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(53,'SAN-00053','UPI2026053','2026-01-06','Julius',   'Karanja',  'male',  '2021-11-14','Christian','','A+', 'Kikuyu',   'Kayole, Nairobi',    'Kayole, Nairobi',    'Nairobi','Nairobi','+254722300053',10,'julius.karanja@student.sunrise.ke',     8,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
-- PP2 East (class 101) — born ~2021
(54,'SAN-00054','UPI2026054','2026-01-06','Kendra',   'Njoki',    'female','2021-02-08','Christian','','B-', 'Kikuyu',   'Kileleshwa, Nairobi','Kileleshwa, Nairobi','Nairobi','Nairobi','+254722300054',10,'kendra.njoki@student.sunrise.ke',       9,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(55,'SAN-00055','UPI2026055','2026-01-06','Liam',     'Wekesa',   'male',  '2021-06-24','Christian','','O+', 'Luhya',    'Ruiru, Kiambu',      'Ruiru, Kiambu',      'Nairobi','Nairobi','+254722300055',10,'liam.wekesa@student.sunrise.ke',        10,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(56,'SAN-00056','UPI2026056','2026-01-06','Monica',   'Otieno',   'female','2021-10-17','Christian','','A+', 'Dholuo',   'Umoja, Nairobi',     'Umoja, Nairobi',     'Nairobi','Nairobi','+254722300056',10,'monica.otieno@student.sunrise.ke',      6,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(57,'SAN-00057','UPI2026057','2026-01-06','Nixon',    'Kipchoge', 'male',  '2021-04-03','Christian','','AB+','Kalenjin', 'Mathare, Nairobi',   'Mathare, Nairobi',   'Nairobi','Nairobi','+254722300057',10,'nixon.kipchoge@student.sunrise.ke',     7,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png'),
(58,'SAN-00058','UPI2026058','2026-01-06','Olive',    'Gakii',    'female','2021-08-30','Christian','','O-', 'Meru',     'Kayole, Nairobi',    'Kayole, Nairobi',    'Nairobi','Nairobi','+254722300058',10,'olive.gakii@student.sunrise.ke',        8,0,0,0,0,'{"school_name":"","qualification":"","remarks":""}','defualt.png');

-- Student login credentials
INSERT INTO `login_credential` (`user_id`,`username`,`password`,`role`,`active`) VALUES
(11,'james.adhiambo@student.sunrise.ke',    '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(12,'rose.njenga@student.sunrise.ke',        '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(13,'samuel.ochieng@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(14,'faith.kimani@student.sunrise.ke',       '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(15,'thomas.kamau@student.sunrise.ke',       '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(16,'grace.oloo@student.sunrise.ke',          '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(17,'kenneth.waweru@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(18,'lydia.mutua@student.sunrise.ke',         '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(19,'anthony.nganga@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(20,'beatrice.achieng@student.sunrise.ke',   '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(21,'collins.mutura@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(22,'dorothy.kerubo@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(23,'emmanuel.kipkurui@student.sunrise.ke',  '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(24,'felicity.wangari@student.sunrise.ke',   '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(25,'george.maina@student.sunrise.ke',       '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(26,'harriet.anyango@student.sunrise.ke',    '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(27,'isaac.njoroge@student.sunrise.ke',      '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(28,'joyce.cherono@student.sunrise.ke',      '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(29,'lawrence.mugo@student.sunrise.ke',      '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(30,'margaret.cherop@student.sunrise.ke',    '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(31,'nathan.kamande@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(32,'olivia.nyambura@student.sunrise.ke',    '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(33,'paul.ochieng@student.sunrise.ke',       '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(34,'queen.akinyi@student.sunrise.ke',       '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(35,'raymond.kiptoo@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(36,'sarah.gacheri@student.sunrise.ke',      '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(37,'timothy.nganga@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(38,'ursula.adhiambo@student.sunrise.ke',    '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(39,'vincent.kariuki@student.sunrise.ke',    '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(40,'wendy.atieno@student.sunrise.ke',       '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(41,'xavier.mutuku@student.sunrise.ke',      '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(42,'yvonne.ndunge@student.sunrise.ke',      '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(43,'zachary.kirui@student.sunrise.ke',      '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(44,'abigail.chesang@student.sunrise.ke',    '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(45,'benjamin.koros@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(46,'christine.odero@student.sunrise.ke',    '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(47,'daniel.onyango@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(48,'esther.chepngeno@student.sunrise.ke',   '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(49,'felix.kimani@student.sunrise.ke',       '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(50,'gloria.wanjiru@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(51,'henry.odhiambo@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(52,'irene.chepkoech@student.sunrise.ke',    '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(53,'julius.karanja@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(54,'kendra.njoki@student.sunrise.ke',       '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(55,'liam.wekesa@student.sunrise.ke',        '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(56,'monica.otieno@student.sunrise.ke',      '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(57,'nixon.kipchoge@student.sunrise.ke',     '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1),
(58,'olive.gakii@student.sunrise.ke',        '$2y$10$GDVr5IXICpEnHpElnPlQJ.pmqCZZKOj6y4mBxNzJveR/vKShwQBRe',7,1);

-- ================================================================
-- SECTION 3: STUDENT ENROLLMENTS
-- ================================================================
INSERT INTO `enroll` (`student_id`,`class_id`,`section_id`,`roll`,`session_id`,`branch_id`) VALUES
-- Grade 1 East (class 102, section 10)
(11,102,10,2,10,3),(12,102,10,3,10,3),(13,102,10,4,10,3),(14,102,10,5,10,3),
-- Grade 2 West (class 103, section 11)
(15,103,11,2,10,3),(16,103,11,3,10,3),(17,103,11,4,10,3),(18,103,11,5,10,3),
-- Grade 3 East (class 104, section 10)
(19,104,10,2,10,3),(20,104,10,3,10,3),(21,104,10,4,10,3),(22,104,10,5,10,3),
-- Grade 4 East (class 105, section 10)
(23,105,10,3,10,3),(24,105,10,4,10,3),(25,105,10,5,10,3),(26,105,10,6,10,3),
-- Grade 5 East (class 106, section 10)
(27,106,10,4,10,3),(28,106,10,5,10,3),(29,106,10,6,10,3),(30,106,10,7,10,3),
-- Grade 6 East (class 107, section 10)
(31,107,10,1,10,3),(32,107,10,2,10,3),(33,107,10,3,10,3),(34,107,10,4,10,3),(35,107,10,5,10,3),
-- Grade 7 East (class 108, section 10)
(36,108,10,2,10,3),(37,108,10,3,10,3),(38,108,10,4,10,3),(39,108,10,5,10,3),
-- Grade 8 West (class 109, section 11)
(40,109,11,2,10,3),(41,109,11,3,10,3),(42,109,11,4,10,3),(43,109,11,5,10,3),
-- Grade 9 East (class 110, section 10)
(44,110,10,1,10,3),(45,110,10,2,10,3),(46,110,10,3,10,3),(47,110,10,4,10,3),(48,110,10,5,10,3),
-- PP1 East (class 100, section 10)
(49,100,10,1,10,3),(50,100,10,2,10,3),(51,100,10,3,10,3),(52,100,10,4,10,3),(53,100,10,5,10,3),
-- PP2 East (class 101, section 10)
(54,101,10,1,10,3),(55,101,10,2,10,3),(56,101,10,3,10,3),(57,101,10,4,10,3),(58,101,10,5,10,3);

-- Fee allocations for new students
INSERT INTO `fee_allocation` (`student_id`,`group_id`,`session_id`,`branch_id`) VALUES
(11,10,10,3),(12,10,10,3),(13,10,10,3),(14,10,10,3),
(15,10,10,3),(16,10,10,3),(17,10,10,3),(18,10,10,3),
(19,10,10,3),(20,10,10,3),(21,10,10,3),(22,10,10,3),
(23,10,10,3),(24,10,10,3),(25,10,10,3),(26,10,10,3),
(27,10,10,3),(28,10,10,3),(29,10,10,3),(30,10,10,3),
(31,10,10,3),(32,10,10,3),(33,10,10,3),(34,10,10,3),(35,10,10,3),
(36,10,10,3),(37,10,10,3),(38,10,10,3),(39,10,10,3),
(40,10,10,3),(41,10,10,3),(42,10,10,3),(43,10,10,3),
(44,10,10,3),(45,10,10,3),(46,10,10,3),(47,10,10,3),(48,10,10,3),
(49,10,10,3),(50,10,10,3),(51,10,10,3),(52,10,10,3),(53,10,10,3),
(54,10,10,3),(55,10,10,3),(56,10,10,3),(57,10,10,3),(58,10,10,3);

SET FOREIGN_KEY_CHECKS = 1;
-- ================================================================
-- END OF 013a_cbc_students.sql
-- Next: run 013b_cbc_strands.sql
-- ================================================================
