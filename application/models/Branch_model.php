<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Branch_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function save($data)
    {
        $arrayBranch = array(
            'name' => $data['branch_name'],
            'school_name' => $data['school_name'],
            'email' => $data['email'],
            'mobileno' => $data['mobileno'],
            'currency' => $data['currency'],
            'symbol' => $data['currency_symbol'],
            'city' => $data['city'],
            'state' => $data['state'],
            'address' => $data['address'],
        );
        if (!isset($data['branch_id'])) {
            if (!empty($data['university_template'])) {
                $arrayBranch['branch_type'] = 'university';
            }
            $this->db->insert('branch', $arrayBranch);
            $branchId = $this->db->insert_id();
            if (isset($data['kenya_template']) && $data['kenya_template']) {
                $this->seedKenyanDefaults($branchId);
            }
            if (isset($data['university_template']) && $data['university_template']) {
                $this->seedUniversityDefaults($branchId);
            }
        } else {
            $this->db->where('id', $data['branch_id']);
            $this->db->update('branch', $arrayBranch);
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function seedKenyanDefaults($branchId)
    {
        $sessionId = $this->db->select('id')->order_by('id', 'DESC')->limit(1)->get('schoolyear')->row();
        $sid = $sessionId ? $sessionId->id : 1;

        // 1. SECTIONS (Streams)
        $sections = array('A', 'B', 'C');
        foreach ($sections as $sec) {
            $exists = $this->db->get_where('section', array('name' => $sec, 'branch_id' => $branchId))->num_rows();
            if ($exists == 0) {
                $this->db->insert('section', array('name' => $sec, 'capacity' => 40, 'branch_id' => $branchId));
            }
        }
        $sectionIds = array();
        foreach ($this->db->where('branch_id', $branchId)->get('section')->result() as $s) {
            $sectionIds[] = $s->id;
        }

        // 2. CLASSES (PP1 to Form 4)
        $classes = array(
            array('name' => 'PP1', 'name_numeric' => '0', 'curriculum_type' => 'cbc', 'level' => 'pp'),
            array('name' => 'PP2', 'name_numeric' => '0', 'curriculum_type' => 'cbc', 'level' => 'pp'),
            array('name' => 'Grade 1', 'name_numeric' => '1', 'curriculum_type' => 'cbc', 'level' => 'lower_primary'),
            array('name' => 'Grade 2', 'name_numeric' => '2', 'curriculum_type' => 'cbc', 'level' => 'lower_primary'),
            array('name' => 'Grade 3', 'name_numeric' => '3', 'curriculum_type' => 'cbc', 'level' => 'lower_primary'),
            array('name' => 'Grade 4', 'name_numeric' => '4', 'curriculum_type' => 'cbc', 'level' => 'upper_primary'),
            array('name' => 'Grade 5', 'name_numeric' => '5', 'curriculum_type' => 'cbc', 'level' => 'upper_primary'),
            array('name' => 'Grade 6', 'name_numeric' => '6', 'curriculum_type' => 'cbc', 'level' => 'upper_primary'),
            array('name' => 'Grade 7', 'name_numeric' => '7', 'curriculum_type' => 'cbc', 'level' => 'junior_secondary'),
            array('name' => 'Grade 8', 'name_numeric' => '8', 'curriculum_type' => 'cbc', 'level' => 'junior_secondary'),
            array('name' => 'Grade 9', 'name_numeric' => '9', 'curriculum_type' => 'cbc', 'level' => 'junior_secondary'),
            array('name' => 'Form 1', 'name_numeric' => '10', 'curriculum_type' => '844', 'level' => 'senior_secondary'),
            array('name' => 'Form 2', 'name_numeric' => '11', 'curriculum_type' => '844', 'level' => 'senior_secondary'),
            array('name' => 'Form 3', 'name_numeric' => '12', 'curriculum_type' => '844', 'level' => 'senior_secondary'),
            array('name' => 'Form 4', 'name_numeric' => '13', 'curriculum_type' => '844', 'level' => 'senior_secondary'),
        );
        foreach ($classes as $cls) {
            $cls['branch_id'] = $branchId;
            $this->db->insert('class', $cls);
            $classId = $this->db->insert_id();
            foreach ($sectionIds as $secId) {
                $this->db->insert('sections_allocation', array('class_id' => $classId, 'section_id' => $secId));
            }
        }

        // 3. STUDENT CATEGORIES
        $categories = array('Regular', 'Scholarship', 'Bursary', 'Special Needs');
        foreach ($categories as $cat) {
            $this->db->insert('student_category', array('name' => $cat, 'branch_id' => $branchId));
        }

        // 4. DEPARTMENTS
        $departments = array('Administration', 'Teaching - Lower Primary', 'Teaching - Upper Primary', 'Teaching - Junior Secondary', 'Teaching - Senior Secondary', 'Accounts', 'Support Staff');
        foreach ($departments as $dept) {
            $this->db->insert('staff_department', array('name' => $dept, 'branch_id' => $branchId));
        }

        // 5. DESIGNATIONS
        $designations = array('Head Teacher', 'Deputy Head Teacher', 'Class Teacher', 'Subject Teacher', 'Accountant', 'Librarian', 'Secretary');
        foreach ($designations as $des) {
            $this->db->insert('staff_designation', array('name' => $des, 'branch_id' => $branchId));
        }

        // 6. SUBJECTS (8-4-4 for Form 1-4)
        $subjects = array(
            array('English', 'ENG', 'Theory'), array('Kiswahili', 'KIS', 'Theory'),
            array('Mathematics', 'MAT', 'Theory'), array('Biology', 'BIO', 'Theory'),
            array('Chemistry', 'CHE', 'Theory'), array('Physics', 'PHY', 'Theory'),
            array('History', 'HIS', 'Theory'), array('Geography', 'GEO', 'Theory'),
            array('CRE', 'CRE', 'Theory'), array('IRE', 'IRE', 'Theory'),
            array('Business Studies', 'BUS', 'Theory'), array('Agriculture', 'AGR', 'Theory'),
            array('Computer Studies', 'COM', 'Practical'), array('Home Science', 'HOM', 'Practical'),
            array('Art and Design', 'ART', 'Practical'), array('Music', 'MUS', 'Practical'),
            array('French', 'FRE', 'Theory'), array('German', 'GER', 'Theory'),
        );
        foreach ($subjects as $sub) {
            $this->db->insert('subject', array('name' => $sub[0], 'subject_code' => $sub[1], 'subject_type' => $sub[2], 'subject_author' => '', 'branch_id' => $branchId));
        }

        // 7. CBC LEARNING AREAS (including PP)
        $learningAreas = array(
            'pp' => array('Language Activities', 'Mathematical Activities', 'Environmental Activities', 'Psychomotor and Creative Activities', 'Religious Education Activities', 'Nutrition and Hygiene Activities'),
            'lower_primary' => array('Literacy Activities', 'Kiswahili Language Activities', 'English Language Activities', 'Mathematics Activities', 'Environmental Activities', 'Hygiene and Nutrition Activities', 'Religious Education Activities', 'Movement and Creative Activities'),
            'upper_primary' => array('English', 'Kiswahili', 'Mathematics', 'Science and Technology', 'Social Studies', 'Religious Education', 'Creative Arts', 'Physical and Health Education', 'Agriculture'),
            'junior_secondary' => array('English', 'Kiswahili', 'Mathematics', 'Integrated Science', 'Social Studies', 'Pre-Technical Studies', 'Agriculture', 'Creative Arts and Sports', 'Religious Education'),
        );
        $areaIds = array();
        foreach ($learningAreas as $level => $areas) {
            foreach ($areas as $areaName) {
                $this->db->insert('cbc_learning_areas', array('name' => $areaName, 'level' => $level, 'branch_id' => $branchId));
                $areaIds[$level . '_' . $areaName] = $this->db->insert_id();
            }
        }

        // 7C. SENIOR SECONDARY PATHWAYS + LEARNING AREAS
        $seniorPathways = array(
            'STEM'                  => 'Science, Technology, Engineering and Mathematics pathway',
            'Arts & Sports Science' => 'Arts and Sports Science pathway',
            'Social Sciences'       => 'Humanities and Social Sciences pathway',
        );
        $pathwayIds = array();
        foreach ($seniorPathways as $pName => $pDesc) {
            $this->db->insert('cbc_pathways', array('name' => $pName, 'description' => $pDesc, 'branch_id' => $branchId));
            $pathwayIds[$pName] = $this->db->insert_id();
        }
        // Core Senior Secondary (all pathways — pathway_id = null)
        $seniorCore = array('English', 'Kiswahili', 'Community Service Learning', 'Physical Education', 'Life Skills Education', 'Religious Education');
        foreach ($seniorCore as $laName) {
            $this->db->insert('cbc_learning_areas', array('name' => $laName, 'level' => 'senior_secondary', 'pathway_id' => null, 'branch_id' => $branchId));
            $areaIds['senior_secondary_' . $laName] = $this->db->insert_id();
        }
        // STEM-specific
        $stemAreas = array('Mathematics', 'Physics', 'Chemistry', 'Biology', 'Computer Science', 'Agriculture and Nutrition', 'Technical Drawing');
        foreach ($stemAreas as $laName) {
            $this->db->insert('cbc_learning_areas', array('name' => $laName, 'level' => 'senior_secondary', 'pathway_id' => $pathwayIds['STEM'], 'branch_id' => $branchId));
            $areaIds['senior_secondary_' . $laName] = $this->db->insert_id();
        }
        // Arts & Sports Science-specific
        $artsAreas = array('Visual Arts', 'Performing Arts', 'Sports Science', 'Music', 'Theatre and Film');
        foreach ($artsAreas as $laName) {
            $this->db->insert('cbc_learning_areas', array('name' => $laName, 'level' => 'senior_secondary', 'pathway_id' => $pathwayIds['Arts & Sports Science'], 'branch_id' => $branchId));
            $areaIds['senior_secondary_' . $laName] = $this->db->insert_id();
        }
        // Social Sciences-specific
        $socialAreas = array('History & Government', 'Geography', 'Business Studies', 'Economics', 'Foreign Languages', 'Sociology');
        foreach ($socialAreas as $laName) {
            $this->db->insert('cbc_learning_areas', array('name' => $laName, 'level' => 'senior_secondary', 'pathway_id' => $pathwayIds['Social Sciences'], 'branch_id' => $branchId));
            $areaIds['senior_secondary_' . $laName] = $this->db->insert_id();
        }

        // 7B. CBC STRANDS (sub-topics per learning area)
        $strands = array(
            'lower_primary_Literacy Activities' => array('Reading', 'Writing', 'Oral Communication', 'Handwriting'),
            'lower_primary_Kiswahili Language Activities' => array('Kusikiliza na Kuzungumza', 'Kusoma', 'Kuandika', 'Sarufi'),
            'lower_primary_English Language Activities' => array('Listening and Speaking', 'Reading', 'Writing', 'Grammar'),
            'lower_primary_Mathematics Activities' => array('Numbers', 'Measurement', 'Geometry', 'Data Handling'),
            'lower_primary_Environmental Activities' => array('Living Things', 'The Environment', 'Weather', 'Resources'),
            'lower_primary_Hygiene and Nutrition Activities' => array('Personal Hygiene', 'Food and Nutrition', 'Safety', 'Health'),
            'lower_primary_Religious Education Activities' => array('God\'s Creation', 'Moral Values', 'Prayer', 'Holy Books'),
            'lower_primary_Movement and Creative Activities' => array('Movement', 'Music', 'Art and Craft', 'Games'),
            'upper_primary_English' => array('Listening and Speaking', 'Reading', 'Writing', 'Grammar'),
            'upper_primary_Kiswahili' => array('Kusikiliza na Kuzungumza', 'Kusoma', 'Kuandika', 'Sarufi'),
            'upper_primary_Mathematics' => array('Numbers', 'Measurement', 'Geometry', 'Algebra', 'Statistics'),
            'upper_primary_Science and Technology' => array('Living Things', 'Energy', 'Matter', 'Environment', 'Technology'),
            'upper_primary_Social Studies' => array('History', 'Geography', 'Government', 'Citizenship'),
            'upper_primary_Agriculture' => array('Crop Farming', 'Animal Farming', 'Soil and Water', 'Farm Tools'),
            'junior_secondary_English' => array('Listening and Speaking', 'Reading', 'Writing', 'Grammar in Use'),
            'junior_secondary_Kiswahili' => array('Kusikiliza na Kuzungumza', 'Kusoma', 'Kuandika', 'Sarufi na Matumizi'),
            'junior_secondary_Mathematics' => array('Numbers', 'Algebra', 'Geometry', 'Measurements', 'Statistics and Probability'),
            'junior_secondary_Integrated Science' => array('Biology', 'Chemistry', 'Physics', 'Earth Science'),
        );
        $strandIds = array();
        foreach ($strands as $key => $strandList) {
            if (isset($areaIds[$key])) {
                foreach ($strandList as $strandName) {
                    $this->db->insert('cbc_strands', array('learning_area_id' => $areaIds[$key], 'name' => $strandName, 'branch_id' => $branchId));
                    $strandIds[$key . '_' . $strandName] = $this->db->insert_id();
                }
            }
        }

        // 7C. CBC SUB-STRANDS (official KICD 3rd level)
        $subStrands = array(
            // Lower Primary — Mathematics Activities
            'lower_primary_Mathematics Activities_Numbers'      => array('Whole Numbers', 'Place Value', 'Fractions', 'Decimals'),
            'lower_primary_Mathematics Activities_Measurement'  => array('Length', 'Mass', 'Capacity', 'Time', 'Area'),
            'lower_primary_Mathematics Activities_Geometry'     => array('2D Shapes', '3D Objects', 'Lines and Angles', 'Position and Movement'),
            'lower_primary_Mathematics Activities_Data Handling' => array('Data Collection', 'Data Representation', 'Interpretation'),
            // Lower Primary — English Language Activities
            'lower_primary_English Language Activities_Listening and Speaking' => array('Oral Drills', 'Rhymes and Songs', 'Storytelling', 'Conversation'),
            'lower_primary_English Language Activities_Reading'  => array('Phonics', 'Sight Words', 'Fluency', 'Comprehension'),
            'lower_primary_English Language Activities_Writing'  => array('Handwriting', 'Spelling', 'Composition', 'Punctuation'),
            'lower_primary_English Language Activities_Grammar'  => array('Word Classes', 'Sentence Structure', 'Tenses', 'Punctuation'),
            // Lower Primary — Kiswahili Language Activities
            'lower_primary_Kiswahili Language Activities_Kusikiliza na Kuzungumza' => array('Mazungumzo', 'Hadithi', 'Nyimbo', 'Matamshi'),
            'lower_primary_Kiswahili Language Activities_Kusoma'    => array('Herufi', 'Maneno', 'Sentensi', 'Ufahamu'),
            'lower_primary_Kiswahili Language Activities_Kuandika'  => array('Maandishi', 'Insha', 'Tahajia', 'Alama za Uakifishaji'),
            'lower_primary_Kiswahili Language Activities_Sarufi'    => array('Nomino', 'Kitenzi', 'Vivumishi', 'Vihusishi'),
            // Lower Primary — Literacy Activities
            'lower_primary_Literacy Activities_Reading'             => array('Phonemic Awareness', 'Phonics', 'Fluency', 'Comprehension'),
            'lower_primary_Literacy Activities_Writing'             => array('Letter Formation', 'Word Building', 'Composition', 'Spelling'),
            'lower_primary_Literacy Activities_Oral Communication'  => array('Listening Skills', 'Speaking Skills', 'Presentation'),
            'lower_primary_Literacy Activities_Handwriting'         => array('Posture', 'Pencil Grip', 'Letter Formation', 'Spacing'),
            // Lower Primary — Environmental Activities
            'lower_primary_Environmental Activities_Living Things'  => array('Plants', 'Animals', 'Human Body', 'Habitats'),
            'lower_primary_Environmental Activities_The Environment' => array('Soil', 'Water', 'Air', 'Conservation'),
            'lower_primary_Environmental Activities_Weather'        => array('Weather Patterns', 'Seasons', 'Climate'),
            'lower_primary_Environmental Activities_Resources'      => array('Natural Resources', 'Energy', 'Conservation'),
            // Lower Primary — Movement and Creative Activities
            'lower_primary_Movement and Creative Activities_Movement' => array('Body Awareness', 'Locomotor Skills', 'Non-Locomotor Skills'),
            'lower_primary_Movement and Creative Activities_Music'    => array('Singing', 'Rhythm', 'Instruments'),
            'lower_primary_Movement and Creative Activities_Art and Craft' => array('Drawing', 'Painting', 'Modelling', 'Craft'),
            'lower_primary_Movement and Creative Activities_Games'    => array('Indoor Games', 'Outdoor Games', 'Team Games'),
            // Upper Primary — Mathematics
            'upper_primary_Mathematics_Numbers'     => array('Whole Numbers', 'Fractions', 'Decimals', 'Percentages', 'Ratio and Proportion'),
            'upper_primary_Mathematics_Measurement' => array('Length', 'Area', 'Volume', 'Mass', 'Time', 'Money'),
            'upper_primary_Mathematics_Geometry'    => array('Angles', '2D Shapes', '3D Objects', 'Symmetry', 'Coordinates'),
            'upper_primary_Mathematics_Algebra'     => array('Patterns', 'Expressions', 'Simple Equations', 'Formulae'),
            'upper_primary_Mathematics_Statistics'  => array('Data Collection', 'Data Representation', 'Mean, Mode and Median', 'Probability'),
            // Upper Primary — English
            'upper_primary_English_Listening and Speaking' => array('Listening Skills', 'Speaking Skills', 'Debate and Discussion', 'Oral Presentation'),
            'upper_primary_English_Reading'          => array('Reading Strategies', 'Comprehension', 'Literature', 'Critical Reading'),
            'upper_primary_English_Writing'          => array('Creative Writing', 'Essay Writing', 'Letter Writing', 'Revision and Editing'),
            'upper_primary_English_Grammar'          => array('Parts of Speech', 'Tenses', 'Sentence Construction', 'Punctuation'),
            // Upper Primary — Science and Technology
            'upper_primary_Science and Technology_Living Things' => array('Cells', 'Plants', 'Animals', 'Human Body Systems'),
            'upper_primary_Science and Technology_Energy'        => array('Forms of Energy', 'Light', 'Sound', 'Heat', 'Electricity'),
            'upper_primary_Science and Technology_Matter'        => array('Properties of Matter', 'States of Matter', 'Mixtures and Solutions'),
            'upper_primary_Science and Technology_Environment'   => array('Ecosystems', 'Pollution', 'Conservation', 'Climate Change'),
            'upper_primary_Science and Technology_Technology'    => array('Simple Machines', 'ICT Basics', 'Innovation and Invention'),
            // Upper Primary — Social Studies
            'upper_primary_Social Studies_History'      => array('Kenya History', 'African History', 'World History'),
            'upper_primary_Social Studies_Geography'    => array('Physical Geography', 'Human Geography', 'Maps and Mapping'),
            'upper_primary_Social Studies_Government'   => array('Government Systems', 'Constitution', 'Devolution'),
            'upper_primary_Social Studies_Citizenship'  => array('Rights and Responsibilities', 'National Values', 'Patriotism'),
            // Junior Secondary — Mathematics
            'junior_secondary_Mathematics_Numbers'                => array('Integers', 'Fractions and Decimals', 'Surds', 'Indices and Logarithms'),
            'junior_secondary_Mathematics_Algebra'                => array('Algebraic Expressions', 'Linear Equations', 'Inequalities', 'Sequences and Series'),
            'junior_secondary_Mathematics_Geometry'               => array('Polygons', 'Circles', 'Constructions', 'Transformations'),
            'junior_secondary_Mathematics_Measurements'           => array('Area', 'Volume and Surface Area', 'Trigonometry', 'Vectors'),
            'junior_secondary_Mathematics_Statistics and Probability' => array('Data Analysis', 'Probability', 'Distributions', 'Sampling'),
            // Junior Secondary — Integrated Science
            'junior_secondary_Integrated Science_Biology'     => array('Cells and Tissues', 'Nutrition', 'Reproduction', 'Genetics', 'Ecology'),
            'junior_secondary_Integrated Science_Chemistry'   => array('Elements and Compounds', 'Chemical Reactions', 'Acids, Bases and Salts', 'Organic Chemistry'),
            'junior_secondary_Integrated Science_Physics'     => array('Motion and Forces', 'Energy', 'Waves', 'Electricity and Magnetism'),
            'junior_secondary_Integrated Science_Earth Science' => array('Geology', 'Atmosphere', 'Space and Solar System', 'Natural Disasters'),
            // Junior Secondary — English
            'junior_secondary_English_Listening and Speaking' => array('Listening Comprehension', 'Oral Presentation', 'Debate', 'Drama'),
            'junior_secondary_English_Reading'                => array('Intensive Reading', 'Extensive Reading', 'Critical Analysis', 'Literature'),
            'junior_secondary_English_Writing'                => array('Formal Writing', 'Creative Writing', 'Research Writing', 'Editing'),
            'junior_secondary_English_Grammar in Use'         => array('Advanced Grammar', 'Vocabulary', 'Style and Register', 'Discourse'),
        );
        foreach ($subStrands as $key => $subList) {
            // key format: level_LearningAreaName_StrandName
            $parts = explode('_', $key, 3);
            if (count($parts) < 3) continue;
            $level    = $parts[0] . '_' . $parts[1]; // restore two-part level
            // Actually key is: level_LA_Strand — but level itself can have underscore
            // Rebuild: strandIds key = 'level_LA_StrandName'
            // strandIds were stored as: $key . '_' . $strandName where $key = level_LA
            // So strandIds key = e.g. 'lower_primary_Mathematics Activities_Numbers'
            // subStrands key = 'lower_primary_Mathematics Activities_Numbers'
            // So strand key = everything except last segment after last underscore? No.
            // Actually subStrands key = level_LA_Strand, strandIds key = level_LA_Strand too. They match directly.
            $strandKey = $key; // same key format
            if (!isset($strandIds[$strandKey])) continue;
            $strandId = $strandIds[$strandKey];
            // Get learning_area_id: level_LA key
            // level_LA = everything before last '_StrandName'
            $lastUnderscore = strrpos($key, '_');
            $laKey = substr($key, 0, $lastUnderscore);
            $laId = isset($areaIds[$laKey]) ? $areaIds[$laKey] : null;
            if (!$laId) continue;
            foreach ($subList as $subName) {
                $this->db->insert('cbc_sub_strands', array(
                    'name'             => $subName,
                    'strand_id'        => $strandId,
                    'learning_area_id' => $laId,
                    'branch_id'        => $branchId,
                ));
            }
        }

        // 7D. CBC LEARNING OUTCOMES (key KICD outcomes per sub-strand)
        // Format: 'level_LA_Strand_SubStrand' => array of outcome strings
        // We look up sub-strand IDs from the sub-strand insertion done above
        // Build a sub-strand lookup: 'level_LA_Strand_SubStrand' => id
        $subStrandIds = array();
        foreach ($subStrands as $key => $subList) {
            $parts = explode('_', $key, 3);
            if (count($parts) < 3) continue;
            $strandKey = $key;
            if (!isset($strandIds[$strandKey])) continue;
            $strandId = $strandIds[$strandKey];
            $lastUnderscore = strrpos($key, '_');
            $laKey = substr($key, 0, $lastUnderscore);
            $laId = isset($areaIds[$laKey]) ? $areaIds[$laKey] : null;
            if (!$laId) continue;
            foreach ($subList as $subName) {
                $ssKey = $key . '_' . $subName;
                // find the inserted ID by querying
                $ssRow = $this->db->get_where('cbc_sub_strands', array('name' => $subName, 'strand_id' => $strandId, 'branch_id' => $branchId))->row();
                if ($ssRow) {
                    $subStrandIds[$ssKey] = array('id' => $ssRow->id, 'strand_id' => $strandId, 'la_id' => $laId);
                }
            }
        }

        $learningOutcomes = array(
            // Lower Primary — Mathematics — Numbers — Whole Numbers
            'lower_primary_Mathematics Activities_Numbers_Whole Numbers' => array(
                array('LO1', 'Count objects up to 999'),
                array('LO2', 'Read and write numbers up to 999 in numerals and words'),
                array('LO3', 'Compare and order numbers up to 999 using <, > and ='),
                array('LO4', 'Add numbers up to 999 with and without regrouping'),
                array('LO5', 'Subtract numbers up to 999 with and without regrouping'),
            ),
            'lower_primary_Mathematics Activities_Numbers_Fractions' => array(
                array('LO1', 'Identify and name unit fractions (½, ¼, ⅓)'),
                array('LO2', 'Compare unit fractions using objects and diagrams'),
                array('LO3', 'Add and subtract fractions with the same denominator'),
            ),
            // Lower Primary — Mathematics — Measurement — Length
            'lower_primary_Mathematics Activities_Measurement_Length' => array(
                array('LO1', 'Measure length using non-standard units'),
                array('LO2', 'Measure length using centimetres and metres'),
                array('LO3', 'Convert between centimetres and metres'),
                array('LO4', 'Estimate and compare lengths of objects'),
            ),
            'lower_primary_Mathematics Activities_Measurement_Time' => array(
                array('LO1', 'Read time on analogue and digital clocks to the hour and half hour'),
                array('LO2', 'Name days of the week and months of the year in order'),
                array('LO3', 'Read and interpret a simple calendar'),
            ),
            // Lower Primary — English — Reading — Phonics
            'lower_primary_English Language Activities_Reading_Phonics' => array(
                array('LO1', 'Identify and produce all letter sounds (phonemes)'),
                array('LO2', 'Blend sounds to read three-letter CVC words'),
                array('LO3', 'Segment spoken words into individual phonemes'),
                array('LO4', 'Read simple decodable texts using phonic knowledge'),
            ),
            'lower_primary_English Language Activities_Reading_Comprehension' => array(
                array('LO1', 'Answer literal questions about a text read aloud'),
                array('LO2', 'Retell the main events of a short story in sequence'),
                array('LO3', 'Predict what might happen next in a story'),
                array('LO4', 'Identify the main character, setting, and problem in a story'),
            ),
            // Lower Primary — English — Writing
            'lower_primary_English Language Activities_Writing_Handwriting' => array(
                array('LO1', 'Sit correctly and hold a pencil with the correct grip'),
                array('LO2', 'Form all uppercase and lowercase letters correctly'),
                array('LO3', 'Write words and short sentences with consistent letter size and spacing'),
            ),
            'lower_primary_English Language Activities_Writing_Composition' => array(
                array('LO1', 'Write a simple sentence using a capital letter and full stop'),
                array('LO2', 'Write three or more sentences about a familiar topic'),
                array('LO3', 'Use descriptive words to add detail to writing'),
            ),
            // Upper Primary — Mathematics — Numbers
            'upper_primary_Mathematics_Numbers_Whole Numbers' => array(
                array('LO1', 'Read, write and order numbers up to 1,000,000'),
                array('LO2', 'Multiply whole numbers up to 4 digits by 2-digit numbers'),
                array('LO3', 'Divide whole numbers using long division'),
                array('LO4', 'Apply BODMAS/PEMDAS in multi-step calculations'),
            ),
            'upper_primary_Mathematics_Numbers_Fractions' => array(
                array('LO1', 'Add and subtract fractions with different denominators'),
                array('LO2', 'Multiply and divide fractions'),
                array('LO3', 'Convert between fractions, decimals and percentages'),
            ),
            'upper_primary_Mathematics_Algebra_Patterns' => array(
                array('LO1', 'Identify, describe and extend number patterns'),
                array('LO2', 'Use symbols and letters to represent unknown values'),
                array('LO3', 'Solve simple linear equations with one unknown'),
            ),
            // Upper Primary — English — Reading
            'upper_primary_English_Reading_Comprehension' => array(
                array('LO1', 'Read and understand narrative and expository texts'),
                array('LO2', 'Identify the main idea and supporting details in a passage'),
                array('LO3', 'Infer meaning of unfamiliar words from context'),
                array('LO4', 'Evaluate the author\'s purpose and viewpoint'),
            ),
            // Upper Primary — Science — Living Things
            'upper_primary_Science and Technology_Living Things_Cells' => array(
                array('LO1', 'Describe the structure of plant and animal cells'),
                array('LO2', 'Explain the functions of the main cell organelles'),
                array('LO3', 'Distinguish between unicellular and multicellular organisms'),
            ),
            'upper_primary_Science and Technology_Living Things_Human Body Systems' => array(
                array('LO1', 'Describe the structure and functions of the digestive system'),
                array('LO2', 'Explain how the circulatory system transports substances'),
                array('LO3', 'Describe the role of the respiratory system in gas exchange'),
            ),
            // Junior Secondary — Mathematics
            'junior_secondary_Mathematics_Numbers_Integers' => array(
                array('LO1', 'Add, subtract, multiply and divide integers'),
                array('LO2', 'Apply the rules of indices for integer exponents'),
                array('LO3', 'Simplify expressions involving integers and order of operations'),
            ),
            'junior_secondary_Mathematics_Algebra_Algebraic Expressions' => array(
                array('LO1', 'Expand and simplify algebraic expressions'),
                array('LO2', 'Factorise algebraic expressions by common factor and grouping'),
                array('LO3', 'Apply the difference of two squares and perfect square identities'),
            ),
            'junior_secondary_Mathematics_Algebra_Linear Equations' => array(
                array('LO1', 'Solve linear equations in one unknown'),
                array('LO2', 'Solve simultaneous linear equations graphically and algebraically'),
                array('LO3', 'Formulate and solve linear equations from real-life situations'),
            ),
            // Junior Secondary — Integrated Science — Biology
            'junior_secondary_Integrated Science_Biology_Cells and Tissues' => array(
                array('LO1', 'Describe cell structure using light and electron microscopy'),
                array('LO2', 'Explain cell division by mitosis and meiosis'),
                array('LO3', 'Describe how cells are organised into tissues, organs and systems'),
            ),
            'junior_secondary_Integrated Science_Biology_Nutrition' => array(
                array('LO1', 'Identify the classes of food and their sources'),
                array('LO2', 'Describe the process of digestion in humans'),
                array('LO3', 'Explain the consequences of nutritional deficiencies'),
            ),
        );

        foreach ($learningOutcomes as $key => $outcomes) {
            // key: level_LA_Strand_SubStrand
            // Find sub_strand_id from $subStrandIds
            $ssData = isset($subStrandIds[$key]) ? $subStrandIds[$key] : null;
            if (!$ssData) {
                // Try to find without sub-strand (strand level only)
                // key might be level_LA_Strand — not applicable here, skip
                continue;
            }
            foreach ($outcomes as $lo) {
                $this->db->insert('cbc_learning_outcomes', array(
                    'code'             => $lo[0],
                    'name'             => $lo[1],
                    'sub_strand_id'    => $ssData['id'],
                    'strand_id'        => $ssData['strand_id'],
                    'learning_area_id' => $ssData['la_id'],
                    'branch_id'        => $branchId,
                ));
            }
        }

        // 8. EXAM TERMS
        $terms = array('Term 1', 'Term 2', 'Term 3');
        foreach ($terms as $term) {
            $this->db->insert('exam_term', array('name' => $term, 'branch_id' => $branchId, 'session_id' => $sid));
        }

        // 9. MARK DISTRIBUTIONS
        $distributions = array('CAT 1', 'CAT 2', 'End Term Exam');
        foreach ($distributions as $dist) {
            $this->db->insert('exam_mark_distribution', array('name' => $dist, 'branch_id' => $branchId));
        }

        // 10. EXAM HALLS
        $halls = array(array('Hall A', 50), array('Hall B', 50), array('Hall C', 30));
        foreach ($halls as $hall) {
            $this->db->insert('exam_hall', array('hall_no' => $hall[0], 'seats' => $hall[1], 'branch_id' => $branchId));
        }

        // 11. KCSE GRADING SCALE
        $grades = array(
            array('A', '12', 80, 100, 'Excellent'), array('A-', '11', 75, 79, 'Very Good'),
            array('B+', '10', 70, 74, 'Good'), array('B', '9', 65, 69, 'Good'),
            array('B-', '8', 60, 64, 'Above Average'), array('C+', '7', 55, 59, 'Average'),
            array('C', '6', 50, 54, 'Average'), array('C-', '5', 45, 49, 'Below Average'),
            array('D+', '4', 40, 44, 'Below Average'), array('D', '3', 35, 39, 'Weak'),
            array('D-', '2', 30, 34, 'Weak'), array('E', '1', 0, 29, 'Very Weak'),
        );
        foreach ($grades as $g) {
            $this->db->insert('grade', array('name' => $g[0], 'grade_point' => $g[1], 'lower_mark' => $g[2], 'upper_mark' => $g[3], 'remark' => $g[4], 'branch_id' => $branchId));
        }

        // 12. FEE TYPES
        $feeTypes = array(
            array('Tuition Fee', 'TUI', 'Termly tuition fee'),
            array('Activity Fee', 'ACT', 'Co-curricular activities'),
            array('Lunch Programme', 'LUN', 'School lunch per term'),
            array('Exam Fee', 'EXM', 'Examination charges'),
            array('Development Levy', 'DEV', 'Infrastructure development'),
            array('Transport Fee', 'TRN', 'School bus transport'),
            array('Boarding Fee', 'BRD', 'Hostel accommodation'),
            array('Stationery Fee', 'STN', 'Exercise books, pens'),
            array('Medical Fee', 'MED', 'School clinic'),
            array('Library Fee', 'LIB', 'Library services'),
        );
        foreach ($feeTypes as $ft) {
            $this->db->insert('fees_type', array('name' => $ft[0], 'fee_code' => $ft[1], 'description' => $ft[2], 'branch_id' => $branchId));
        }

        // 13. LEAVE CATEGORIES
        $leaves = array(
            array('Annual Leave', 21), array('Sick Leave', 14), array('Maternity Leave', 90),
            array('Paternity Leave', 14), array('Compassionate Leave', 5), array('Study Leave', 30),
        );
        foreach ($leaves as $lv) {
            $this->db->insert('leave_category', array('name' => $lv[0], 'role_id' => 0, 'days' => $lv[1], 'branch_id' => $branchId));
        }

        // 14. EVENT TYPES
        $events = array(
            array('Sports', 'fas fa-running'), array('Cultural', 'fas fa-music'),
            array('Academic', 'fas fa-book'), array('Parents Meeting', 'fas fa-users'),
            array('Holiday', 'fas fa-calendar'), array('Fundraising', 'fas fa-hand-holding-usd'),
        );
        foreach ($events as $ev) {
            $this->db->insert('event_types', array('name' => $ev[0], 'icon' => $ev[1], 'branch_id' => $branchId));
        }

        // 15. VOUCHER HEADS (Accounting categories)
        $vouchers = array(
            array('Student Fees Collection', 'income'), array('Government Grants', 'income'),
            array('Donations & Fundraising', 'income'), array('Other Income', 'income'),
            array('Staff Salaries', 'expense'), array('Stationery & Supplies', 'expense'),
            array('Electricity Bill', 'expense'), array('Water Bill', 'expense'),
            array('Internet & Telephone', 'expense'), array('Transport & Fuel', 'expense'),
            array('Maintenance & Repairs', 'expense'), array('Cleaning Supplies', 'expense'),
            array('Food & Catering', 'expense'), array('Textbooks & Learning Materials', 'expense'),
            array('Exam Materials', 'expense'), array('Sports Equipment', 'expense'),
            array('Medical Supplies', 'expense'), array('Insurance', 'expense'),
            array('Bank Charges', 'expense'), array('Miscellaneous', 'expense'),
        );
        foreach ($vouchers as $vh) {
            $this->db->insert('voucher_head', array('name' => $vh[0], 'type' => $vh[1], 'system' => 0, 'branch_id' => $branchId));
        }

        // 16. BOOK CATEGORIES
        $bookCats = array('Textbooks', 'Fiction', 'Reference', 'Kiswahili Literature', 'English Literature', 'Science', 'History & Geography', 'Religious Education', 'Magazines & Newspapers');
        foreach ($bookCats as $bc) {
            $this->db->insert('book_category', array('name' => $bc, 'branch_id' => $branchId));
        }

        // 17. SALARY TEMPLATES (with Kenyan statutory deductions)
        $salaryTemplates = array(
            array('Head Teacher', 85000), array('Deputy Head Teacher', 70000),
            array('Senior Teacher', 55000), array('Teacher', 45000),
            array('Accountant', 40000), array('Librarian', 35000),
            array('Secretary', 30000), array('Support Staff', 25000),
        );
        foreach ($salaryTemplates as $st) {
            $this->db->insert('salary_template', array('name' => $st[0], 'basic_salary' => $st[1], 'overtime_salary' => '0', 'branch_id' => $branchId));
            $templateId = $this->db->insert_id();
            $basic = $st[1];
            $paye = max(0, round(($basic - 24000) * 0.1));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'House Allowance', 'amount' => round($basic * 0.15), 'type' => 1));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'Transport Allowance', 'amount' => round($basic * 0.08), 'type' => 1));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'NHIF', 'amount' => 1700, 'type' => 2));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'NSSF', 'amount' => 200, 'type' => 2));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'PAYE', 'amount' => $paye, 'type' => 2));
        }

        // 18. PAYMENT CONFIG (empty, ready for M-Pesa setup)
        $exists = $this->db->get_where('payment_config', array('branch_id' => $branchId))->num_rows();
        if ($exists == 0) {
            $this->db->insert('payment_config', array(
                'branch_id' => $branchId, 'paypal_username' => '', 'paypal_password' => '',
                'paypal_signature' => '', 'paypal_email' => '', 'paypal_sandbox' => 1, 'paypal_status' => 0,
                'stripe_secret' => '', 'stripe_demo' => 1, 'stripe_status' => 0,
                'mpesa_consumer_key' => '', 'mpesa_consumer_secret' => '', 'mpesa_shortcode' => '',
                'mpesa_passkey' => '', 'mpesa_sandbox' => 1, 'mpesa_status' => 0,
            ));
        }

        // 19. ATTACHMENTS TYPES
        $attachTypes = array('Homework', 'Notes', 'Past Papers', 'Syllabus', 'Announcements', 'Newsletters', 'Circulars');
        foreach ($attachTypes as $at) {
            $this->db->insert('attachments_type', array('name' => $at, 'branch_id' => $branchId));
        }

        // 20. HOSTEL CATEGORIES
        $hostelCheck = $this->db->get_where('hostel_category', array('branch_id' => $branchId))->num_rows();
        if ($hostelCheck == 0) {
            $this->db->insert('hostel_category', array('name' => 'Boys Hostel', 'description' => 'Male students dormitory', 'branch_id' => $branchId, 'type' => 'Boys'));
            $this->db->insert('hostel_category', array('name' => 'Girls Hostel', 'description' => 'Female students dormitory', 'branch_id' => $branchId, 'type' => 'Girls'));
        }

        // 21. SMS TEMPLATES
        $smsTemplates = $this->db->get('sms_template')->result();
        foreach ($smsTemplates as $tpl) {
            $exists = $this->db->get_where('sms_template_details', array('template_id' => $tpl->id, 'branch_id' => $branchId))->num_rows();
            if ($exists == 0) {
                $bodies = array(
                    1 => 'Dear {guardian_name}, {student_name} has been admitted to {school_name}. Class: {class}, Login: {email}. Welcome!',
                    2 => 'Dear {guardian_name}, Fee payment of KES {amount} received for {student_name} on {paid_date}. Thank you. - {school_name}',
                    3 => 'Dear {guardian_name}, {student_name} was marked {status} on {date}. - {school_name}',
                    4 => 'Dear {guardian_name}, {student_name} was marked {status} for the exam. - {school_name}',
                    5 => 'Dear {guardian_name}, Exam results for {student_name} are now available. Login to view. - {school_name}',
                    6 => 'Dear {guardian_name}, Homework assigned to {class}. Subject: {subject}. Due: {due_date}. - {school_name}',
                    7 => 'Dear {guardian_name}, Live class scheduled for {class} on {date} at {time}. - {school_name}',
                );
                $body = isset($bodies[$tpl->id]) ? $bodies[$tpl->id] : '';
                $this->db->insert('sms_template_details', array(
                    'template_id' => $tpl->id, 'notify_student' => 1, 'notify_parent' => 1,
                    'template_body' => $body, 'branch_id' => $branchId,
                ));
            }
        }

        // 22. EMAIL TEMPLATES
        $emailTemplates = $this->db->get('email_templates')->result();
        foreach ($emailTemplates as $etpl) {
            $exists = $this->db->get_where('email_templates_details', array('template_id' => $etpl->id, 'branch_id' => $branchId))->num_rows();
            if ($exists == 0) {
                $subjects = array(
                    1 => 'Account Created - {institute_name}',
                    2 => 'Password Reset - {institute_name}',
                    3 => 'Password Changed - {institute_name}',
                    4 => 'New Message - {institute_name}',
                    5 => 'Salary Payment - {institute_name}',
                    6 => 'Award Notification - {institute_name}',
                    7 => 'Leave Approved - {institute_name}',
                    8 => 'Leave Rejected - {institute_name}',
                );
                $bodies = array(
                    1 => '<p>Dear {name},</p><p>Your account has been created at {institute_name}.</p><p>Login Email: {email}<br>Password: {password}</p><p>Login at the school portal to access your dashboard.</p><p>Regards,<br>{institute_name}</p>',
                    2 => '<p>Dear {username},</p><p>You requested a password reset.</p><p>Click here to reset: <a href="{reset_url}">{reset_url}</a></p><p>If you did not request this, ignore this email.</p><p>Regards,<br>{institute_name}</p>',
                    3 => '<p>Dear {name},</p><p>Your password has been changed successfully.</p><p>Regards,<br>{institute_name}</p>',
                    4 => '<p>Dear {name},</p><p>You have a new message. Login to view it.</p><p>Regards,<br>{institute_name}</p>',
                    5 => '<p>Dear {name},</p><p>Your salary for {month} has been processed.</p><p>Net Amount: {amount}</p><p>Regards,<br>{institute_name}</p>',
                    6 => '<p>Dear {name},</p><p>Congratulations! You have received an award.</p><p>Regards,<br>{institute_name}</p>',
                    7 => '<p>Dear {name},</p><p>Your leave request has been approved.</p><p>Regards,<br>{institute_name}</p>',
                    8 => '<p>Dear {name},</p><p>Your leave request has been rejected.</p><p>Regards,<br>{institute_name}</p>',
                );
                $subj = isset($subjects[$etpl->id]) ? $subjects[$etpl->id] : $etpl->name;
                $body = isset($bodies[$etpl->id]) ? $bodies[$etpl->id] : '';
                $this->db->insert('email_templates_details', array(
                    'template_id' => $etpl->id, 'subject' => $subj,
                    'template_body' => $body, 'notified' => 1, 'branch_id' => $branchId,
                ));
            }
        }

        // 23. COMMON KENYAN TEXTBOOKS
        $bookCatIds = array();
        foreach ($this->db->where('branch_id', $branchId)->get('book_category')->result() as $bc) {
            $bookCatIds[$bc->name] = $bc->id;
        }
        $textbookCat = isset($bookCatIds['Textbooks']) ? $bookCatIds['Textbooks'] : 0;
        $fictionCat = isset($bookCatIds['Fiction']) ? $bookCatIds['Fiction'] : 0;
        $kiswCat = isset($bookCatIds['Kiswahili Literature']) ? $bookCatIds['Kiswahili Literature'] : 0;
        $refCat = isset($bookCatIds['Reference']) ? $bookCatIds['Reference'] : 0;

        $books = array(
            array('KLB Primary Mathematics Grade 4', 'KLB', $textbookCat, 'Kenya Literature Bureau', 750, 30),
            array('KLB Primary Mathematics Grade 5', 'KLB', $textbookCat, 'Kenya Literature Bureau', 750, 30),
            array('KLB Primary Mathematics Grade 6', 'KLB', $textbookCat, 'Kenya Literature Bureau', 750, 30),
            array('KLB Primary Science Grade 4', 'KLB', $textbookCat, 'Kenya Literature Bureau', 800, 25),
            array('KLB Primary Science Grade 5', 'KLB', $textbookCat, 'Kenya Literature Bureau', 800, 25),
            array('KLB Primary English Grade 4', 'KLB', $textbookCat, 'Kenya Literature Bureau', 700, 30),
            array('KLB Primary Kiswahili Grade 4', 'KLB', $textbookCat, 'Kenya Literature Bureau', 700, 30),
            array('Secondary English Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 30),
            array('Secondary English Form 2', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 30),
            array('Secondary English Form 3', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 30),
            array('Secondary English Form 4', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 30),
            array('Secondary Mathematics Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 900, 30),
            array('Secondary Mathematics Form 2', 'KLB', $textbookCat, 'Kenya Literature Bureau', 900, 30),
            array('Secondary Mathematics Form 3', 'KLB', $textbookCat, 'Kenya Literature Bureau', 900, 30),
            array('Secondary Mathematics Form 4', 'KLB', $textbookCat, 'Kenya Literature Bureau', 900, 30),
            array('KLB Biology Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 800, 25),
            array('KLB Biology Form 2', 'KLB', $textbookCat, 'Kenya Literature Bureau', 800, 25),
            array('KLB Chemistry Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 25),
            array('KLB Chemistry Form 2', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 25),
            array('KLB Physics Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 25),
            array('KLB Physics Form 2', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 25),
            array('KLB History Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 750, 25),
            array('KLB Geography Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 750, 25),
            array('KLB CRE Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 700, 25),
            array('Blossoms of the Savannah', 'Henry Ole Kulet', $fictionCat, 'Longhorn', 650, 20),
            array('The River and the Source', 'Margaret Ogola', $fictionCat, 'Focus Publishers', 550, 20),
            array('A Doll\'s House', 'Henrik Ibsen', $fictionCat, 'Penguin', 450, 15),
            array('Inheritance', 'David Mulwa', $fictionCat, 'Oxford', 500, 15),
            array('The Pearl', 'John Steinbeck', $fictionCat, 'Penguin', 400, 20),
            array('Chozi la Heri', 'Assumpta K. Matei', $kiswCat, 'Vide Muwa', 500, 20),
            array('Kidagaa Kimemwozea', 'Ken Walibora', $kiswCat, 'Longhorn', 550, 20),
            array('Kigogo', 'Pauline Kea', $kiswCat, 'Vide Muwa', 500, 15),
            array('Oxford English Dictionary', 'Oxford', $refCat, 'Oxford University Press', 1200, 10),
            array('Kamusi ya Kiswahili', 'TUKI', $refCat, 'Oxford University Press', 1000, 10),
            array('Atlas of Kenya', 'Survey of Kenya', $refCat, 'Survey of Kenya', 950, 10),
            array('Kenya Constitution 2010', 'Government', $refCat, 'Government Press', 300, 10),
        );
        foreach ($books as $bk) {
            $this->db->insert('book', array(
                'title' => $bk[0], 'author' => $bk[1], 'category_id' => $bk[2],
                'publisher' => $bk[3], 'price' => $bk[4], 'total_stock' => $bk[5],
                'issued_copies' => '0', 'purchase_date' => date('Y-m-d'),
                'isbn_no' => '', 'edition' => '', 'description' => '', 'branch_id' => $branchId,
            ));
        }

        // 24. KENYAN PUBLIC HOLIDAYS AS EVENTS
        $year = date('Y');
        $holidays = array(
            array("New Year's Day", "$year-01-01"), array('Mashujaa Day', "$year-10-20"),
            array('Jamhuri Day', "$year-12-12"), array('Madaraka Day', "$year-06-01"),
            array('Labour Day', "$year-05-01"), array('Christmas Day', "$year-12-25"),
            array('Boxing Day', "$year-12-26"), array('Good Friday', "$year-04-03"),
            array('Easter Monday', "$year-04-06"), array('Eid ul-Fitr (Tentative)', "$year-04-10"),
            array('Utamaduni Day', "$year-10-10"),
        );
        $holidayTypeId = $this->db->select('id')->where(array('name' => 'Holiday', 'branch_id' => $branchId))->get('event_types')->row();
        $htId = $holidayTypeId ? $holidayTypeId->id : 0;
        foreach ($holidays as $h) {
            $this->db->insert('event', array(
                'title' => $h[0], 'remark' => 'Public Holiday - ' . $h[0],
                'status' => 1, 'type' => $htId, 'audition' => '[]', 'selected_list' => '[]',
                'start_date' => $h[1], 'end_date' => $h[1], 'created_by' => '1', 'branch_id' => $branchId,
            ));
        }

        // 25. FEE GROUPS (Day Scholars + Boarders with amounts)
        $feeTypeIds = array();
        foreach ($this->db->where('branch_id', $branchId)->get('fees_type')->result() as $ft) {
            $feeTypeIds[$ft->fee_code] = $ft->id;
        }
        $termDue = date('Y-m-d', strtotime('+21 days'));
        $dayFees = array('TUI' => 15000, 'ACT' => 1500, 'LUN' => 5000, 'EXM' => 500, 'DEV' => 1000, 'STN' => 500, 'MED' => 500);
        $boardFees = array('TUI' => 15000, 'ACT' => 1500, 'LUN' => 5000, 'EXM' => 500, 'DEV' => 1000, 'STN' => 500, 'MED' => 500, 'BRD' => 12000);

        $this->db->insert('fee_groups', array('name' => 'Day Scholar — Term Fees', 'description' => 'Standard term fee structure for day scholars', 'session_id' => $sid, 'branch_id' => $branchId));
        $dayGroupId = $this->db->insert_id();
        foreach ($dayFees as $code => $amount) {
            if (isset($feeTypeIds[$code])) {
                $this->db->insert('fee_groups_details', array('fee_groups_id' => $dayGroupId, 'fee_type_id' => $feeTypeIds[$code], 'amount' => $amount, 'due_date' => $termDue));
            }
        }
        $this->db->insert('fee_groups', array('name' => 'Boarding — Term Fees', 'description' => 'Full term fees for boarding students', 'session_id' => $sid, 'branch_id' => $branchId));
        $boardGroupId = $this->db->insert_id();
        foreach ($boardFees as $code => $amount) {
            if (isset($feeTypeIds[$code])) {
                $this->db->insert('fee_groups_details', array('fee_groups_id' => $boardGroupId, 'fee_type_id' => $feeTypeIds[$code], 'amount' => $amount, 'due_date' => $termDue));
            }
        }

        // Fee fines
        if (isset($feeTypeIds['TUI'])) {
            $this->db->insert('fee_fine', array('group_id' => $dayGroupId, 'type_id' => $feeTypeIds['TUI'], 'fine_value' => '500', 'fine_type' => 'fixed', 'fee_frequency' => 'monthly', 'branch_id' => $branchId, 'session_id' => $sid));
            $this->db->insert('fee_fine', array('group_id' => $boardGroupId, 'type_id' => $feeTypeIds['TUI'], 'fine_value' => '500', 'fine_type' => 'fixed', 'fee_frequency' => 'monthly', 'branch_id' => $branchId, 'session_id' => $sid));
        }

        // 26. FEES REMINDERS
        $this->db->insert('fees_reminder', array('frequency' => 'Weekly', 'days' => '7', 'message' => 'Dear {guardian_name}, this is a reminder that {student_name} has an outstanding fee balance. Please settle at your earliest convenience. — {school_name}', 'student' => 0, 'guardian' => 1, 'branch_id' => $branchId));
        $this->db->insert('fees_reminder', array('frequency' => 'Monthly', 'days' => '30', 'message' => 'Dear {guardian_name}, monthly fee reminder for {student_name}. Please contact the school bursar if you need a payment plan. — {school_name}', 'student' => 0, 'guardian' => 1, 'branch_id' => $branchId));

        // 27. TRANSPORT ROUTES, VEHICLES & STOPPAGES
        $routes = array(
            array('Westlands Route', 'School Gate', 'Westlands Shopping Centre', 'Morning pickup & afternoon drop-off via Westlands, Parklands, Muthaiga'),
            array('Eastleigh Route',  'School Gate', 'Eastleigh Section 2',       'Morning pickup & afternoon drop-off via Ngara, Pangani, Eastleigh'),
            array('Karen Route',      'School Gate', 'Karen Shopping Centre',      'Morning pickup & afternoon drop-off via Dagoretti, Kawangware, Karen'),
        );
        foreach ($routes as $rt) {
            $this->db->insert('transport_route', array('name' => $rt[0], 'start_place' => $rt[1], 'stop_place' => $rt[2], 'remarks' => $rt[3], 'branch_id' => $branchId));
        }
        $stoppages = array(
            array('Westlands Junction',    '06:30:00', 3000.00),
            array('Parklands Roundabout',  '06:45:00', 3000.00),
            array('Muthaiga Shops',        '06:50:00', 3200.00),
            array('Ngara Market',          '06:35:00', 2800.00),
            array('Pangani Stage',         '06:40:00', 2800.00),
            array('Eastleigh Section 1',   '06:50:00', 3000.00),
            array('Dagoretti Corner',      '06:25:00', 3500.00),
            array('Kawangware Centre',     '06:35:00', 3500.00),
            array('Karen Roundabout',      '06:55:00', 4000.00),
        );
        foreach ($stoppages as $st) {
            $this->db->insert('transport_stoppage', array('stop_position' => $st[0], 'stop_time' => $st[1], 'route_fare' => $st[2], 'branch_id' => $branchId));
        }
        $vehicles = array(
            array('KXX 001A', '52', date('Y+2') . '-12-31', 'Driver 1 (TBD)', '', ''),
            array('KXX 002B', '45', date('Y+2') . '-06-30', 'Driver 2 (TBD)', '', ''),
            array('KXX 003C', '33', date('Y+1') . '-09-30', 'Driver 3 (TBD)', '', ''),
        );
        foreach ($vehicles as $veh) {
            $this->db->insert('transport_vehicle', array('vehicle_no' => $veh[0], 'capacity' => $veh[1], 'insurance_renewal' => $veh[2], 'driver_name' => $veh[3], 'driver_phone' => $veh[4], 'driver_license' => $veh[5], 'branch_id' => $branchId));
        }

        // 28. SCHOOL BUSES (GPS tracking)
        $this->db->insert('school_buses', array('bus_name' => 'Bus 1 — Westlands Route', 'reg_number' => 'KXX 001A', 'capacity' => 52, 'driver_name' => 'TBD', 'driver_phone' => '', 'route_description' => 'Westlands > Parklands > Muthaiga > School', 'is_active' => 1, 'branch_id' => $branchId));
        $this->db->insert('school_buses', array('bus_name' => 'Bus 2 — Eastleigh Route',  'reg_number' => 'KXX 002B', 'capacity' => 45, 'driver_name' => 'TBD', 'driver_phone' => '', 'route_description' => 'Eastleigh > Pangani > Ngara > School',   'is_active' => 1, 'branch_id' => $branchId));

        // 29. HOSTELS + ROOMS
        $hostelCats = $this->db->where('branch_id', $branchId)->get('hostel_category')->result();
        foreach ($hostelCats as $hcat) {
            $this->db->insert('hostel', array('name' => $hcat->name, 'category_id' => $hcat->id, 'address' => 'School Compound', 'watchman' => 'To be assigned', 'remarks' => '6-bed dormitory rooms', 'branch_id' => $branchId));
            $hostelId = $this->db->insert_id();
            for ($r = 1; $r <= 6; $r++) {
                $roomLabel = str_pad($r, 2, '0', STR_PAD_LEFT);
                $this->db->insert('hostel_room', array('name' => 'Room ' . $roomLabel, 'hostel_id' => $hostelId, 'no_beds' => 6, 'category_id' => $hcat->id, 'bed_fee' => 4000.00, 'remarks' => 'Standard 6-bed dormitory room', 'branch_id' => $branchId));
            }
        }

        // 30. BANK ACCOUNTS
        $this->db->insert('accounts', array('name' => 'School Fees Collection Account', 'number' => 'ACC-001', 'description' => 'Main account for student fee collection — Equity Bank', 'balance' => 0.00, 'branch_id' => $branchId));
        $this->db->insert('accounts', array('name' => 'Operating Expenses Account',     'number' => 'ACC-002', 'description' => 'Account for daily operational expenses — Equity Bank', 'balance' => 0.00, 'branch_id' => $branchId));
        $this->db->insert('accounts', array('name' => 'School Development Fund',        'number' => 'ACC-003', 'description' => 'Ring-fenced account for infrastructure projects', 'balance' => 0.00, 'branch_id' => $branchId));
        $this->db->insert('accounts', array('name' => 'Petty Cash',                     'number' => 'ACC-004', 'description' => 'Petty cash float for small expenses', 'balance' => 0.00, 'branch_id' => $branchId));

        // 31. CANTEEN / POS MENU
        $canteenItems = array(
            array('Chapati',         'Snacks',    20.00, 500), array('Mandazi',        'Snacks',    10.00, 300),
            array('Rice & Beans',    'Lunch',     80.00, 100), array('Ugali & Sukuma', 'Lunch',     60.00, 120),
            array('Githeri',         'Lunch',     50.00,  80), array('Milk (500 ml)', 'Beverages', 40.00, 200),
            array('Juice Box',       'Beverages', 30.00, 150), array('Bread (slice)', 'Breakfast', 15.00, 400),
            array('Boiled Egg',      'Breakfast', 20.00, 200), array('Water (500 ml)','Beverages', 20.00, 500),
            array('Maziwa Lala',     'Beverages', 30.00, 100), array('Nduma & Ndengu','Lunch',     45.00, 100),
        );
        foreach ($canteenItems as $ci) {
            $this->db->insert('canteen_items', array('name' => $ci[0], 'category' => $ci[1], 'price' => $ci[2], 'quantity_in_stock' => $ci[3], 'is_available' => 1, 'branch_id' => $branchId));
        }

        // 32. ASSET CATEGORIES + ASSETS
        $assetCatNames = array('ICT Equipment', 'Furniture', 'Sports Equipment', 'Laboratory Equipment', 'Library Equipment');
        $assetCatIds   = array();
        foreach ($assetCatNames as $acn) {
            $this->db->insert('asset_categories', array('name' => $acn, 'description' => $acn . ' owned by the school', 'branch_id' => $branchId));
            $assetCatIds[$acn] = $this->db->insert_id();
        }
        $assets = array(
            array('ICT-001', 'Desktop Computer',       'ICT Equipment',        'HP',      'EliteDesk', '2023-01-01', 45000, 'Computer Lab'),
            array('ICT-002', 'Digital Projector',      'ICT Equipment',        'Epson',   'EB-X41',    '2023-01-01', 35000, 'Main Hall'),
            array('ICT-003', 'Laser Printer',          'ICT Equipment',        'HP',      'LJ Pro',    '2022-06-01', 18000, 'Admin Office'),
            array('FRN-001', 'Teacher Desk',           'Furniture',            'Local',   NULL,        '2020-01-01',  8000, 'Staffroom'),
            array('FRN-002', '4-Drawer Filing Cabinet','Furniture',            'Steelcase',NULL,       '2021-01-01', 15000, 'Admin Office'),
            array('SPT-001', 'Football',               'Sports Equipment',     'Adidas',  'Tango',     '2023-08-01',  3500, 'Sports Store'),
            array('SPT-002', 'Volleyball Net',         'Sports Equipment',     'Mikasa',  NULL,        '2023-08-01',  4500, 'Sports Store'),
            array('LAB-001', 'Binocular Microscope',   'Laboratory Equipment', 'Olympus', 'CX23',      '2022-04-01', 22000, 'Science Lab'),
            array('LIB-001', 'Library Shelving Unit',  'Library Equipment',    'Metal',   NULL,        '2019-01-01', 12000, 'Library'),
        );
        foreach ($assets as $a) {
            $this->db->insert('assets', array('asset_tag' => $a[0], 'name' => $a[1], 'category_id' => isset($assetCatIds[$a[2]]) ? $assetCatIds[$a[2]] : NULL, 'brand' => $a[3], 'model' => $a[4], 'purchase_date' => $a[5], 'purchase_cost' => $a[6], 'location' => $a[7], 'condition_status' => 'good', 'branch_id' => $branchId));
        }

        // 33. INVENTORY ITEMS
        $inventoryItems = array(
            array('A4 Printing Paper',    'Stationery',        'Ream',   50, 10,  550, 'Office Mart Kenya', 'Admin Store'),
            array('Blue Pens',            'Stationery',        'Box',    20,  5,  320, 'Office Mart Kenya', 'Admin Store'),
            array('Chalk (White)',        'Classroom Supplies','Box',    30, 10,   80, 'Local Supplier',    'Store Room'),
            array('Marker Pens',          'Classroom Supplies','Box',    15,  4,  450, 'Office Mart Kenya', 'Store Room'),
            array('Exercise Books',       'Stationery',        'Piece', 500,100,   35, 'Text Book Centre',  'Store Room'),
            array('Detergent (5 L)',      'Cleaning Supplies', 'Bottle', 15,  5,  650, 'Nakumatt',          'Cleaning Store'),
            array('Toilet Paper',         'Cleaning Supplies', 'Roll',  120, 30,   45, 'Nakumatt',          'Cleaning Store'),
            array('Hand Sanitiser (1 L)', 'Health Supplies',   'Bottle', 10,  3,  850, 'Pharmacies Ltd',    'Clinic'),
            array('Liquid Soap (1 L)',    'Cleaning Supplies', 'Bottle', 20,  5,  300, 'Nakumatt',          'Cleaning Store'),
            array('Dustbin Liners',       'Cleaning Supplies', 'Pack',   25,  8,  180, 'Nakumatt',          'Store Room'),
        );
        foreach ($inventoryItems as $iv) {
            $this->db->insert('inventory_items', array('name' => $iv[0], 'category' => $iv[1], 'unit' => $iv[2], 'quantity_in_stock' => $iv[3], 'reorder_level' => $iv[4], 'unit_cost' => $iv[5], 'supplier' => $iv[6], 'location' => $iv[7], 'branch_id' => $branchId));
        }

        // 34. NOTICE BOARD (Opening notices)
        $this->db->insert('notice_board', array('title' => 'Welcome to the ' . date('Y') . ' Academic Year', 'details' => 'Welcome back! We are excited to begin this academic year together. Parents are invited to the opening meeting in the first week of term. Please ensure all fees are cleared before your child reports.', 'notice_date' => date('Y-m-d'), 'expiry_date' => date('Y-m-d', strtotime('+30 days')), 'audience' => 'all', 'priority' => 'important', 'posted_by' => 1, 'branch_id' => $branchId));
        $this->db->insert('notice_board', array('title' => 'Fee Payment Reminder', 'details' => 'All parents are reminded to clear outstanding fee balances. Accepted payment methods: M-Pesa (use student registration number as reference), bank transfer, or cash at the bursar\'s office.', 'notice_date' => date('Y-m-d'), 'expiry_date' => date('Y-m-d', strtotime('+45 days')), 'audience' => 'parents', 'priority' => 'urgent', 'posted_by' => 1, 'branch_id' => $branchId));
        $this->db->insert('notice_board', array('title' => 'Staff Briefing — Term Planning', 'details' => 'All teaching staff are required to submit their lesson plans and schemes of work for the term by the end of the first week. CBC assessment records from the previous term must also be updated.', 'notice_date' => date('Y-m-d'), 'expiry_date' => date('Y-m-d', strtotime('+14 days')), 'audience' => 'staff', 'priority' => 'important', 'posted_by' => 1, 'branch_id' => $branchId));

        // 35. STAFF APPRAISAL TEMPLATE (TSC-aligned)
        $this->db->insert('appraisal_templates', array('name' => 'Annual Teacher Performance Appraisal', 'description' => 'TSC-aligned annual appraisal for all teaching staff covering lesson delivery, student outcomes, professionalism and community engagement.', 'appraisal_type' => 'annual', 'status' => 'active', 'branch_id' => $branchId));
        $tplId = $this->db->insert_id();
        $criteria = array(
            array('Lesson Planning & Scheme of Work',     10, 1.5, 'Teaching Quality'),
            array('Classroom Management & Delivery',      10, 2.0, 'Teaching Quality'),
            array('Student Performance Improvement',      10, 2.0, 'Results'),
            array('Attendance & Punctuality',             10, 1.0, 'Professionalism'),
            array('Professional Development',             10, 1.0, 'Professionalism'),
            array('Extra-Curricular & Community',         10, 0.5, 'Community'),
        );
        foreach ($criteria as $cr) {
            $this->db->insert('appraisal_criteria', array('template_id' => $tplId, 'criterion' => $cr[0], 'max_score' => $cr[1], 'weight' => $cr[2], 'category' => $cr[3], 'branch_id' => $branchId));
        }
        $this->db->insert('appraisal_templates', array('name' => 'TSC Performance Contracting', 'description' => 'TSC performance contract template for submission to the Teachers Service Commission.', 'appraisal_type' => 'tsc', 'status' => 'active', 'branch_id' => $branchId));

        // 36. KNEC CENTRES
        $this->db->insert('knec_centres', array('centre_code' => 'XXX/001', 'centre_name' => 'School KCPE Examination Centre', 'exam_type' => 'KCPE',       'branch_id' => $branchId));
        $this->db->insert('knec_centres', array('centre_code' => 'XXX/002', 'centre_name' => 'School KCSE Examination Centre', 'exam_type' => 'KCSE',       'branch_id' => $branchId));
        $this->db->insert('knec_centres', array('centre_code' => 'XXX/003', 'centre_name' => 'School CBC Grade 9 Centre',      'exam_type' => 'CBC_Grade9', 'branch_id' => $branchId));

        // 37. BURSARY PROGRAMME
        $this->db->insert('bursary_programmes', array('name' => 'NG-CDF Bursary ' . date('Y'), 'provider' => 'National Government Constituency Development Fund', 'provider_type' => 'ngcdf', 'description' => 'Annual government bursary for needy and deserving students. Priority given to orphans, students from single-parent households and very low income families.', 'total_allocation' => 500000.00, 'academic_year' => date('Y'), 'application_deadline' => date('Y') . '-06-30', 'status' => 'open', 'branch_id' => $branchId));
        $this->db->insert('bursary_programmes', array('name' => 'School Scholarship Fund ' . date('Y'), 'provider' => 'School Board of Management', 'provider_type' => 'private', 'description' => 'Internal scholarship for outstanding students based on academic merit and good conduct.', 'total_allocation' => 200000.00, 'academic_year' => date('Y'), 'application_deadline' => date('Y') . '-03-31', 'status' => 'open', 'branch_id' => $branchId));

        // 38. PTM SESSION TEMPLATE
        $this->db->insert('ptm_sessions', array('title' => 'Term 1 ' . date('Y') . ' Parent-Teacher Meeting', 'session_date' => date('Y-m-d', strtotime('+45 days')), 'start_time' => '08:00:00', 'end_time' => '13:00:00', 'venue' => 'School Hall', 'slot_duration_mins' => 15, 'notes' => 'Parents are invited to discuss their child\'s academic progress with class teachers. Please bring the last report card.', 'branch_id' => $branchId));

        // 39. CBT SAMPLE QUIZ
        $this->db->insert('cbt_quiz', array('title' => 'Mathematics Practice Quiz', 'duration_mins' => 30, 'total_marks' => 10, 'pass_marks' => 5, 'instructions' => 'Answer all 10 questions. Each correct answer carries 1 mark. No negative marking.', 'shuffle_questions' => 1, 'shuffle_options' => 1, 'show_result' => 1, 'status' => 'draft', 'created_by' => 1, 'branch_id' => $branchId));
        $quizId = $this->db->insert_id();
        $sampleQns = array(
            array('What is 15% of 240?',               'mcq', '30', '36', '48', '42', 'B'),
            array('Simplify: 3x + 5x − 2x',           'mcq', '4x', '6x', '8x','10x', 'B'),
            array('What is the HCF of 36 and 48?',     'mcq', '6', '12', '18', '24',  'B'),
            array('2 is a prime number.',          'true_false', 'True','False', NULL, NULL,'A'),
            array('Sum of angles in a triangle = 180°.','true_false','True','False',NULL,NULL,'A'),
        );
        foreach ($sampleQns as $q) {
            $this->db->insert('cbt_questions', array('quiz_id' => $quizId, 'question_text' => $q[0], 'question_type' => $q[1], 'option_a' => $q[2], 'option_b' => $q[3], 'option_c' => $q[4], 'option_d' => $q[5], 'correct_answer' => $q[6], 'marks' => 1, 'branch_id' => $branchId));
        }

        // 40. VIRTUAL CLASS SAMPLE
        $this->db->insert('virtual_classes', array('title' => 'Mathematics Online Revision', 'description' => 'Online revision session for exam preparation. Link will be shared before class.', 'platform' => 'meet', 'meeting_link' => 'https://meet.google.com/placeholder', 'scheduled_at' => date('Y-m-d H:i:s', strtotime('+7 days 15:00:00')), 'duration_mins' => 60, 'status' => 'upcoming', 'branch_id' => $branchId));

        // 41. HOLISTIC DEVELOPMENT PROFILE — 7 CBC domains + 21 indicators
        // Seed per-branch only when global (branch_id=0) domains don't exist (i.e. migration 008 not yet run)
        $hdTableExists = $this->db->query("SHOW TABLES LIKE 'cbc_holistic_domains'")->num_rows() > 0;
        if ($hdTableExists) {
            $globalHdCount = $this->db->where('branch_id', 0)->count_all_results('cbc_holistic_domains');
            if ($globalHdCount === 0) {
                $holisticDomains = array(
                    array('Communication and Collaboration',       'Ability to express ideas clearly and work effectively with others',     1, array('Expresses ideas clearly in speech and writing', 'Listens actively and respects others\' views', 'Collaborates effectively in group tasks')),
                    array('Creativity and Imagination',            'Ability to generate novel ideas and produce original work',              2, array('Generates original and innovative ideas', 'Shows initiative and takes creative risks', 'Produces work that demonstrates imagination')),
                    array('Critical Thinking and Problem Solving', 'Ability to analyse situations and make informed decisions',              3, array('Identifies and analyses problems systematically', 'Makes informed and reasoned decisions', 'Evaluates evidence before drawing conclusions')),
                    array('Citizenship',                           'Values, attitudes and participation in community and civic life',        4, array('Demonstrates respect and integrity towards others', 'Actively participates in school and community activities', 'Upholds national values and responsible citizenship')),
                    array('Digital Literacy',                      'Responsible and effective use of digital technologies',                  5, array('Uses digital tools effectively for learning', 'Accesses and evaluates online information responsibly', 'Demonstrates online safety and ethical digital behaviour')),
                    array('Learning to Learn',                     'Self-management, goal setting and reflection on learning',               6, array('Sets personal learning goals and works toward them', 'Manages time and organises tasks independently', 'Reflects on own learning and seeks improvement')),
                    array('Physical Health and Wellbeing',         'Physical fitness, health habits and personal wellbeing',                 7, array('Participates actively in physical education and sport', 'Maintains personal hygiene and healthy habits', 'Makes healthy food and lifestyle choices')),
                );
                foreach ($holisticDomains as $dom) {
                    $this->db->insert('cbc_holistic_domains', array('branch_id' => $branchId, 'name' => $dom[0], 'description' => $dom[1], 'sort_order' => $dom[2], 'is_active' => 1));
                    $domId = $this->db->insert_id();
                    foreach ($dom[3] as $i => $indName) {
                        $this->db->insert('cbc_holistic_indicators', array('domain_id' => $domId, 'name' => $indName, 'sort_order' => $i + 1));
                    }
                }
            }
        }

        // 42. PP-LEVEL STRANDS (Pre-Primary: PP1 & PP2)
        $ppAreas = $this->db->where('branch_id', $branchId)->where('level', 'pp')->get('cbc_learning_areas')->result();
        $ppAreaIds = array();
        foreach ($ppAreas as $pa) { $ppAreaIds[$pa->name] = $pa->id; }
        $ppStrands = array(
            'Language Activities'                  => array('Listening and Speaking', 'Reading Readiness', 'Early Writing', 'Oral Expression'),
            'Mathematical Activities'              => array('Numbers and Counting', 'Measurement', 'Shapes and Patterns', 'Data Handling'),
            'Environmental Activities'             => array('Living Things', 'Non-Living Things', 'Weather and Seasons', 'Our Community'),
            'Psychomotor and Creative Activities'  => array('Movement and Motor Skills', 'Music and Rhythm', 'Art and Craft', 'Games and Play'),
            'Religious Education Activities'       => array("God's Creation", 'Moral Values', 'Prayer and Worship'),
            'Nutrition and Hygiene Activities'     => array('Personal Hygiene', 'Food and Nutrition', 'Safety and First Aid'),
        );
        foreach ($ppStrands as $laName => $strandList) {
            if (!isset($ppAreaIds[$laName])) continue;
            $laId = $ppAreaIds[$laName];
            foreach ($strandList as $sName) {
                $exists = $this->db->get_where('cbc_strands', array('name' => $sName, 'learning_area_id' => $laId, 'branch_id' => $branchId))->num_rows();
                if ($exists == 0) {
                    $this->db->insert('cbc_strands', array('name' => $sName, 'learning_area_id' => $laId, 'branch_id' => $branchId));
                }
            }
        }

        // 43. SUBJECT-CLASS ALLOCATIONS (Form 1-4, all sections)
        $subjectMap = array();
        foreach ($this->db->where('branch_id', $branchId)->get('subject')->result() as $sub) {
            $subjectMap[$sub->name] = $sub->id;
        }
        $classMap = array();
        foreach ($this->db->where('branch_id', $branchId)->get('class')->result() as $cls) {
            $classMap[$cls->name] = $cls->id;
        }
        $allSections = $this->db->where('branch_id', $branchId)->get('section')->result();
        $formSubjects = array(
            'Form 1' => array('English','Kiswahili','Mathematics','Biology','Chemistry','Physics','History','Geography','CRE','Business Studies','Agriculture','Computer Studies'),
            'Form 2' => array('English','Kiswahili','Mathematics','Biology','Chemistry','Physics','History','Geography','CRE','Business Studies','Agriculture','Computer Studies'),
            'Form 3' => array('English','Kiswahili','Mathematics','Biology','Chemistry','Physics','History','Geography','CRE','Business Studies','Agriculture','Computer Studies'),
            'Form 4' => array('English','Kiswahili','Mathematics','Biology','Chemistry','Physics','History','Geography','CRE','Business Studies','Agriculture','Computer Studies'),
        );
        foreach ($formSubjects as $formName => $subjectList) {
            if (!isset($classMap[$formName])) continue;
            $classId = $classMap[$formName];
            foreach ($allSections as $sec) {
                foreach ($subjectList as $subName) {
                    if (!isset($subjectMap[$subName])) continue;
                    $subId = $subjectMap[$subName];
                    $exists = $this->db->get_where('subject_assign', array(
                        'class_id' => $classId, 'section_id' => $sec->id,
                        'subject_id' => $subId, 'branch_id' => $branchId, 'session_id' => $sid,
                    ))->num_rows();
                    if ($exists == 0) {
                        $this->db->insert('subject_assign', array(
                            'class_id' => $classId, 'section_id' => $sec->id,
                            'subject_id' => $subId, 'teacher_id' => 0,
                            'branch_id' => $branchId, 'session_id' => $sid,
                        ));
                    }
                }
            }
        }

        // 44. CLASS TIMETABLE (Form 1, Section A — Monday to Friday)
        $f1Id = isset($classMap['Form 1']) ? $classMap['Form 1'] : 0;
        $secARow = $this->db->where('branch_id', $branchId)->where('name', 'A')->get('section')->row();
        $secAId  = $secARow ? $secARow->id : 0;
        if ($f1Id && $secAId) {
            // [day, time_start, time_end, subject_name, is_break]
            $ttEntries = array(
                array('Monday',    '07:00:00','07:40:00','English',          false),
                array('Monday',    '07:40:00','08:20:00','Mathematics',       false),
                array('Monday',    '08:20:00','08:40:00','',                  true),
                array('Monday',    '08:40:00','09:20:00','Biology',           false),
                array('Monday',    '09:20:00','10:00:00','Chemistry',         false),
                array('Monday',    '10:00:00','10:40:00','Kiswahili',         false),
                array('Monday',    '10:40:00','11:00:00','',                  true),
                array('Monday',    '11:00:00','11:40:00','History',           false),
                array('Monday',    '11:40:00','12:20:00','Geography',         false),
                array('Tuesday',   '07:00:00','07:40:00','Mathematics',       false),
                array('Tuesday',   '07:40:00','08:20:00','English',           false),
                array('Tuesday',   '08:20:00','08:40:00','',                  true),
                array('Tuesday',   '08:40:00','09:20:00','Physics',           false),
                array('Tuesday',   '09:20:00','10:00:00','CRE',               false),
                array('Tuesday',   '10:00:00','10:40:00','Agriculture',       false),
                array('Tuesday',   '10:40:00','11:00:00','',                  true),
                array('Tuesday',   '11:00:00','11:40:00','Business Studies',  false),
                array('Tuesday',   '11:40:00','12:20:00','Computer Studies',  false),
                array('Wednesday', '07:00:00','07:40:00','Kiswahili',         false),
                array('Wednesday', '07:40:00','08:20:00','Biology',           false),
                array('Wednesday', '08:20:00','08:40:00','',                  true),
                array('Wednesday', '08:40:00','09:20:00','Mathematics',       false),
                array('Wednesday', '09:20:00','10:00:00','English',           false),
                array('Wednesday', '10:00:00','10:40:00','Chemistry',         false),
                array('Wednesday', '10:40:00','11:00:00','',                  true),
                array('Wednesday', '11:00:00','11:40:00','Geography',         false),
                array('Wednesday', '11:40:00','12:20:00','History',           false),
                array('Thursday',  '07:00:00','07:40:00','Physics',           false),
                array('Thursday',  '07:40:00','08:20:00','Kiswahili',         false),
                array('Thursday',  '08:20:00','08:40:00','',                  true),
                array('Thursday',  '08:40:00','09:20:00','CRE',               false),
                array('Thursday',  '09:20:00','10:00:00','Mathematics',       false),
                array('Thursday',  '10:00:00','10:40:00','Agriculture',       false),
                array('Thursday',  '10:40:00','11:00:00','',                  true),
                array('Thursday',  '11:00:00','11:40:00','Biology',           false),
                array('Thursday',  '11:40:00','12:20:00','Business Studies',  false),
                array('Friday',    '07:00:00','07:40:00','English',           false),
                array('Friday',    '07:40:00','08:20:00','Kiswahili',         false),
                array('Friday',    '08:20:00','08:40:00','',                  true),
                array('Friday',    '08:40:00','09:20:00','Geography',         false),
                array('Friday',    '09:20:00','10:00:00','History',           false),
                array('Friday',    '10:00:00','10:40:00','Computer Studies',  false),
                array('Friday',    '10:40:00','11:00:00','',                  true),
                array('Friday',    '11:00:00','11:40:00','Physics',           false),
                array('Friday',    '11:40:00','12:20:00','Chemistry',         false),
            );
            foreach ($ttEntries as $te) {
                $subId = (!$te[4] && isset($subjectMap[$te[3]])) ? $subjectMap[$te[3]] : 0;
                $this->db->insert('timetable_class', array(
                    'class_id' => $f1Id, 'section_id' => $secAId,
                    'break' => $te[4] ? 'true' : 'false',
                    'subject_id' => $subId, 'teacher_id' => 0, 'class_room' => 'Room F1A',
                    'time_start' => $te[1], 'time_end' => $te[2],
                    'day' => $te[0], 'session_id' => $sid, 'branch_id' => $branchId,
                ));
            }
        }

        // 45. SAMPLE HOMEWORK (Form 1, Section A)
        if ($f1Id && $secAId) {
            $hwData = array(
                array('English',     '+7 days', 'Write a 300-word essay: "The Role of Technology in Education in Kenya". Use real-life examples. Submit as a handwritten essay.'),
                array('Mathematics', '+5 days', 'Complete Exercise 3.4 (Q1-20) on Linear Equations from KLB Mathematics Form 1 textbook. Show all working clearly.'),
                array('Kiswahili',   '+4 days', 'Soma hadithi fupi "Ndoto ya Mtoto" ukurasa 45-52 kwenye kitabu chako cha Kiswahili. Jibu maswali yote ya ufahamu ukurasa 53.'),
                array('Biology',     '+6 days', 'Draw and label the structure of a plant cell and an animal cell. List three differences between the two cells.'),
                array('History',     '+7 days', 'Write brief notes on the causes and effects of the 1895 British Protectorate declaration over Kenya. Minimum one page.'),
            );
            foreach ($hwData as $hw) {
                if (!isset($subjectMap[$hw[0]])) continue;
                $this->db->insert('homework', array(
                    'class_id' => $f1Id, 'section_id' => $secAId, 'session_id' => $sid,
                    'subject_id'         => $subjectMap[$hw[0]],
                    'date_of_homework'   => date('Y-m-d'),
                    'date_of_submission' => date('Y-m-d', strtotime($hw[1])),
                    'description'        => $hw[2],
                    'created_by'         => 1,
                    'create_date'        => date('Y-m-d'),
                    'status'             => 'active',
                    'sms_notification'   => 1,
                    'schedule_date'      => null,
                    'document'           => '',
                    'evaluation_date'    => null,
                    'evaluated_by'       => 0,
                    'branch_id'          => $branchId,
                ));
            }
        }

        // 46. EXAM SCHEDULE — Term 1 Exam, Form 1 Section A
        $f1ExamRow = $this->db->where('branch_id', $branchId)->where('grading_system', 'traditional')->order_by('id', 'ASC')->limit(1)->get('exam')->row();
        $hallRow   = $this->db->where('branch_id', $branchId)->order_by('id', 'ASC')->limit(1)->get('exam_hall')->row();
        $distRow   = $this->db->where('branch_id', $branchId)->where('name', 'End Term Exam')->get('exam_mark_distribution')->row();
        if ($f1Id && $secAId && $f1ExamRow && $hallRow) {
            $f1ExamId = $f1ExamRow->id;
            $hallId   = $hallRow->id;
            $distId   = $distRow ? $distRow->id : 0;
            $examDays = array(
                array('English',          1,  '08:00', '10:00'),
                array('Mathematics',      2,  '08:00', '10:30'),
                array('Biology',          3,  '08:00', '10:00'),
                array('Kiswahili',        4,  '08:00', '10:00'),
                array('Chemistry',        5,  '08:00', '10:00'),
                array('Physics',          6,  '08:00', '10:00'),
                array('History',          7,  '08:00', '10:00'),
                array('Geography',        8,  '08:00', '10:00'),
                array('CRE',              9,  '08:00', '09:30'),
                array('Agriculture',      10, '08:00', '09:30'),
                array('Business Studies', 11, '08:00', '10:00'),
                array('Computer Studies', 12, '08:00', '10:00'),
            );
            foreach ($examDays as $ed) {
                if (!isset($subjectMap[$ed[0]])) continue;
                $this->db->insert('timetable_exam', array(
                    'exam_id'           => $f1ExamId,
                    'class_id'          => $f1Id,
                    'section_id'        => $secAId,
                    'subject_id'        => $subjectMap[$ed[0]],
                    'time_start'        => $ed[2],
                    'time_end'          => $ed[3],
                    'mark_distribution' => json_encode($distId ? array($distId) : array()),
                    'hall_id'           => $hallId,
                    'exam_date'         => date('Y-m-d', strtotime('+' . $ed[1] . ' days')),
                    'branch_id'         => $branchId,
                    'session_id'        => $sid,
                ));
            }
        }

        // 47. 1-MONTH TRIAL SUBSCRIPTION
        $subExists = $this->db->get_where('branch_subscriptions', array('branch_id' => $branchId))->num_rows();
        if ($subExists == 0) {
            $planRow = $this->db->order_by('id', 'ASC')->limit(1)->get('subscription_plan')->row();
            $planId  = $planRow ? $planRow->id : 1;
            $this->db->insert('branch_subscriptions', array(
                'branch_id'     => $branchId,
                'plan_id'       => $planId,
                'billing_cycle' => 'monthly',
                'start_date'    => date('Y-m-d'),
                'end_date'      => date('Y-m-d', strtotime('+1 month')),
                'status'        => 'active',
            ));
        }
    }

    public function seedUniversityDefaults($branchId)
    {
        $sessionId = $this->db->select('id')->order_by('id', 'DESC')->limit(1)->get('schoolyear')->row();
        $sid = $sessionId ? $sessionId->id : 1;

        // 1. SECTIONS (Intakes / Groups)
        $sections = array(
            array('January Intake', 100), array('May Intake', 100), array('September Intake', 100),
            array('Group A', 50), array('Group B', 50),
        );
        foreach ($sections as $sec) {
            $this->db->insert('section', array('name' => $sec[0], 'capacity' => $sec[1], 'branch_id' => $branchId));
        }
        $sectionIds = array();
        foreach ($this->db->where('branch_id', $branchId)->get('section')->result() as $s) {
            $sectionIds[] = $s->id;
        }

        // 2. CLASSES (Programmes / Courses - Year levels)
        $programmes = array(
            // School of Business
            array('Bachelor of Commerce - Year 1', '1', '844', 'senior_secondary'),
            array('Bachelor of Commerce - Year 2', '2', '844', 'senior_secondary'),
            array('Bachelor of Commerce - Year 3', '3', '844', 'senior_secondary'),
            array('Bachelor of Commerce - Year 4', '4', '844', 'senior_secondary'),
            // School of IT
            array('BSc. Information Technology - Year 1', '1', '844', 'senior_secondary'),
            array('BSc. Information Technology - Year 2', '2', '844', 'senior_secondary'),
            array('BSc. Information Technology - Year 3', '3', '844', 'senior_secondary'),
            array('BSc. Information Technology - Year 4', '4', '844', 'senior_secondary'),
            // School of Education
            array('Bachelor of Education - Year 1', '1', '844', 'senior_secondary'),
            array('Bachelor of Education - Year 2', '2', '844', 'senior_secondary'),
            array('Bachelor of Education - Year 3', '3', '844', 'senior_secondary'),
            array('Bachelor of Education - Year 4', '4', '844', 'senior_secondary'),
            // School of Engineering
            array('BSc. Engineering - Year 1', '1', '844', 'senior_secondary'),
            array('BSc. Engineering - Year 2', '2', '844', 'senior_secondary'),
            array('BSc. Engineering - Year 3', '3', '844', 'senior_secondary'),
            array('BSc. Engineering - Year 4', '4', '844', 'senior_secondary'),
            array('BSc. Engineering - Year 5', '5', '844', 'senior_secondary'),
            // Diploma Programmes
            array('Diploma in Business Management - Year 1', '1', '844', 'senior_secondary'),
            array('Diploma in Business Management - Year 2', '2', '844', 'senior_secondary'),
            array('Diploma in ICT - Year 1', '1', '844', 'senior_secondary'),
            array('Diploma in ICT - Year 2', '2', '844', 'senior_secondary'),
            // Certificate Programmes
            array('Certificate in IT - Semester 1', '1', '844', 'senior_secondary'),
            array('Certificate in IT - Semester 2', '2', '844', 'senior_secondary'),
            array('Certificate in Business - Semester 1', '1', '844', 'senior_secondary'),
            array('Certificate in Business - Semester 2', '2', '844', 'senior_secondary'),
        );
        foreach ($programmes as $cls) {
            $this->db->insert('class', array('name' => $cls[0], 'name_numeric' => $cls[1], 'curriculum_type' => $cls[2], 'level' => $cls[3], 'branch_id' => $branchId));
            $classId = $this->db->insert_id();
            foreach ($sectionIds as $secId) {
                $this->db->insert('sections_allocation', array('class_id' => $classId, 'section_id' => $secId));
            }
        }

        // 3. STUDENT CATEGORIES
        $categories = array('Regular (Self-Sponsored)', 'Government Sponsored (KUCCPS)', 'Scholarship', 'International Student', 'Part-Time', 'Distance Learning');
        foreach ($categories as $cat) {
            $this->db->insert('student_category', array('name' => $cat, 'branch_id' => $branchId));
        }

        // 4. DEPARTMENTS (Schools / Faculties)
        $departments = array(
            'Office of the Vice Chancellor', 'Academic Affairs', 'School of Business & Economics',
            'School of Information Technology', 'School of Education', 'School of Engineering',
            'School of Health Sciences', 'School of Arts & Social Sciences', 'School of Law',
            'School of Agriculture', 'Finance & Administration', 'Library Services',
            'Student Affairs', 'Research & Innovation', 'ICT Department', 'Support Staff',
        );
        foreach ($departments as $dept) {
            $this->db->insert('staff_department', array('name' => $dept, 'branch_id' => $branchId));
        }

        // 5. DESIGNATIONS
        $designations = array(
            'Vice Chancellor', 'Deputy Vice Chancellor', 'Dean', 'Associate Dean',
            'Head of Department', 'Professor', 'Associate Professor', 'Senior Lecturer',
            'Lecturer', 'Tutorial Fellow', 'Graduate Assistant', 'Lab Technician',
            'Registrar', 'Finance Officer', 'Librarian', 'ICT Officer', 'Administrator', 'Secretary',
        );
        foreach ($designations as $des) {
            $this->db->insert('staff_designation', array('name' => $des, 'branch_id' => $branchId));
        }

        // 6. SUBJECTS (Units / Modules)
        $units = array(
            // Common University Units
            array('Communication Skills', 'CMS 100', 'Theory'),
            array('HIV/AIDS Education', 'HIV 100', 'Theory'),
            array('Development Studies', 'DST 100', 'Theory'),
            array('Introduction to Computers', 'COM 100', 'Practical'),
            array('Entrepreneurship', 'ENT 100', 'Theory'),
            array('Research Methods', 'RES 300', 'Theory'),
            array('Business Ethics', 'BET 200', 'Theory'),
            // Business Units
            array('Principles of Management', 'BUS 101', 'Theory'),
            array('Financial Accounting I', 'ACC 101', 'Theory'),
            array('Financial Accounting II', 'ACC 201', 'Theory'),
            array('Business Mathematics', 'BMA 101', 'Theory'),
            array('Business Statistics', 'BST 201', 'Theory'),
            array('Microeconomics', 'ECO 101', 'Theory'),
            array('Macroeconomics', 'ECO 102', 'Theory'),
            array('Marketing Management', 'MKT 201', 'Theory'),
            array('Human Resource Management', 'HRM 201', 'Theory'),
            array('Business Law', 'BLW 101', 'Theory'),
            array('Corporate Finance', 'FIN 301', 'Theory'),
            array('Strategic Management', 'STM 401', 'Theory'),
            array('Operations Management', 'OPM 301', 'Theory'),
            // IT Units
            array('Introduction to Programming', 'ICS 101', 'Practical'),
            array('Data Structures & Algorithms', 'ICS 201', 'Practical'),
            array('Database Systems', 'ICS 202', 'Practical'),
            array('Computer Networks', 'ICS 203', 'Theory'),
            array('Web Development', 'ICS 204', 'Practical'),
            array('Object Oriented Programming', 'ICS 301', 'Practical'),
            array('Software Engineering', 'ICS 302', 'Theory'),
            array('Operating Systems', 'ICS 303', 'Theory'),
            array('Cyber Security', 'ICS 401', 'Theory'),
            array('Mobile Application Development', 'ICS 402', 'Practical'),
            array('Cloud Computing', 'ICS 403', 'Theory'),
            array('Artificial Intelligence', 'ICS 404', 'Theory'),
            array('IT Project Management', 'ICS 405', 'Theory'),
            // Education Units
            array('Philosophy of Education', 'EDU 101', 'Theory'),
            array('Psychology of Education', 'EDU 102', 'Theory'),
            array('Curriculum Development', 'EDU 201', 'Theory'),
            array('Educational Technology', 'EDU 202', 'Practical'),
            array('Teaching Practice', 'EDU 300', 'Practical'),
            array('Guidance & Counselling', 'EDU 301', 'Theory'),
            array('Special Needs Education', 'EDU 302', 'Theory'),
            // Engineering Units
            array('Engineering Mathematics I', 'EMA 101', 'Theory'),
            array('Engineering Mathematics II', 'EMA 201', 'Theory'),
            array('Engineering Drawing', 'EDR 101', 'Practical'),
            array('Mechanics of Machines', 'EME 201', 'Theory'),
            array('Thermodynamics', 'ETH 201', 'Theory'),
            array('Electrical Engineering', 'EEE 201', 'Theory'),
            array('Fluid Mechanics', 'EFM 301', 'Theory'),
            // Health Sciences Units
            array('Human Anatomy', 'HAN 101', 'Theory'),
            array('Human Physiology', 'HPH 101', 'Theory'),
            array('Biochemistry', 'BCH 101', 'Theory'),
            array('Pharmacology', 'PHA 201', 'Theory'),
            array('Community Health', 'CHE 201', 'Theory'),
            array('Nursing Fundamentals', 'NUR 101', 'Theory'),
        );
        foreach ($units as $sub) {
            $this->db->insert('subject', array('name' => $sub[0], 'subject_code' => $sub[1], 'subject_type' => $sub[2], 'subject_author' => '', 'branch_id' => $branchId));
        }

        // 7. EXAM TERMS (Semesters)
        $terms = array('Semester 1', 'Semester 2', 'Semester 3 (Trimester)');
        foreach ($terms as $term) {
            $this->db->insert('exam_term', array('name' => $term, 'branch_id' => $branchId, 'session_id' => $sid));
        }

        // 8. MARK DISTRIBUTIONS
        $distributions = array('CAT 1', 'CAT 2', 'Assignment', 'Practical', 'Final Exam');
        foreach ($distributions as $dist) {
            $this->db->insert('exam_mark_distribution', array('name' => $dist, 'branch_id' => $branchId));
        }

        // 9. EXAM HALLS
        $halls = array(
            array('Lecture Hall 1', 200), array('Lecture Hall 2', 200), array('Lecture Hall 3', 150),
            array('Exam Hall A', 300), array('Exam Hall B', 300),
            array('Computer Lab 1', 50), array('Computer Lab 2', 50),
            array('Science Lab', 40),
        );
        foreach ($halls as $hall) {
            $this->db->insert('exam_hall', array('hall_no' => $hall[0], 'seats' => $hall[1], 'branch_id' => $branchId));
        }

        // 10. UNIVERSITY GRADING SCALE
        $grades = array(
            array('A', '4.0', 70, 100, 'First Class Honours'),
            array('B+', '3.5', 65, 69, 'Second Class Honours (Upper)'),
            array('B', '3.0', 60, 64, 'Second Class Honours (Upper)'),
            array('B-', '2.7', 55, 59, 'Second Class Honours (Lower)'),
            array('C+', '2.3', 50, 54, 'Second Class Honours (Lower)'),
            array('C', '2.0', 45, 49, 'Pass'),
            array('C-', '1.7', 40, 44, 'Pass'),
            array('D', '1.0', 35, 39, 'Supplementary'),
            array('E', '0.0', 0, 34, 'Fail / Retake'),
        );
        foreach ($grades as $g) {
            $this->db->insert('grade', array('name' => $g[0], 'grade_point' => $g[1], 'lower_mark' => $g[2], 'upper_mark' => $g[3], 'remark' => $g[4], 'branch_id' => $branchId));
        }

        // 11. FEE TYPES
        $feeTypes = array(
            array('Tuition Fee', 'TUI', 'Semester tuition fee'),
            array('Registration Fee', 'REG', 'Annual registration'),
            array('Library Fee', 'LIB', 'Library and e-resources'),
            array('ICT Fee', 'ICT', 'Computer lab and internet'),
            array('Student Activity Fee', 'ACT', 'Clubs, sports, events'),
            array('Medical Fee', 'MED', 'University clinic/NHIF'),
            array('Examination Fee', 'EXM', 'Exam administration'),
            array('Caution Money', 'CAU', 'Refundable deposit'),
            array('ID Card Fee', 'IDC', 'Student ID card'),
            array('Accommodation Fee', 'ACC', 'Hostel per semester'),
            array('Meals/Catering Fee', 'CAT', 'Cafeteria meals'),
            array('Industrial Attachment Fee', 'ATT', 'Internship coordination'),
            array('Graduation Fee', 'GRD', 'Graduation ceremony'),
            array('Transcripts Fee', 'TRN', 'Academic transcripts'),
            array('Late Registration Fee', 'LRG', 'Late registration penalty'),
        );
        foreach ($feeTypes as $ft) {
            $this->db->insert('fees_type', array('name' => $ft[0], 'fee_code' => $ft[1], 'description' => $ft[2], 'branch_id' => $branchId));
        }

        // 12. LEAVE CATEGORIES
        $leaves = array(
            array('Annual Leave', 21), array('Sick Leave', 14), array('Maternity Leave', 90),
            array('Paternity Leave', 14), array('Sabbatical Leave', 180), array('Study Leave', 365),
            array('Compassionate Leave', 5), array('Conference Leave', 10),
        );
        foreach ($leaves as $lv) {
            $this->db->insert('leave_category', array('name' => $lv[0], 'role_id' => 0, 'days' => $lv[1], 'branch_id' => $branchId));
        }

        // 13. EVENT TYPES
        $events = array(
            array('Academic', 'fas fa-book'), array('Sports', 'fas fa-running'),
            array('Cultural', 'fas fa-music'), array('Graduation', 'fas fa-graduation-cap'),
            array('Orientation', 'fas fa-users'), array('Career Fair', 'fas fa-briefcase'),
            array('Research Seminar', 'fas fa-flask'), array('Workshop', 'fas fa-tools'),
            array('Alumni Event', 'fas fa-handshake'), array('Holiday', 'fas fa-calendar'),
        );
        foreach ($events as $ev) {
            $this->db->insert('event_types', array('name' => $ev[0], 'icon' => $ev[1], 'branch_id' => $branchId));
        }

        // 14. VOUCHER HEADS
        $vouchers = array(
            array('Student Tuition Collection', 'income'), array('Government Capitation', 'income'),
            array('Research Grants', 'income'), array('Donations & Endowments', 'income'),
            array('Facility Hire Income', 'income'), array('Consultancy Income', 'income'),
            array('Staff Salaries', 'expense'), array('Stationery & Office Supplies', 'expense'),
            array('Electricity & Water', 'expense'), array('Internet & Telecommunications', 'expense'),
            array('Equipment & Furniture', 'expense'), array('Maintenance & Repairs', 'expense'),
            array('Library Acquisitions', 'expense'), array('Research Expenditure', 'expense'),
            array('Student Welfare', 'expense'), array('Transport & Travel', 'expense'),
            array('Insurance', 'expense'), array('Marketing & Recruitment', 'expense'),
            array('Cleaning & Sanitation', 'expense'), array('Bank Charges', 'expense'),
            array('Lab Supplies & Chemicals', 'expense'), array('Sports & Recreation', 'expense'),
        );
        foreach ($vouchers as $vh) {
            $this->db->insert('voucher_head', array('name' => $vh[0], 'type' => $vh[1], 'system' => 0, 'branch_id' => $branchId));
        }

        // 15. BOOK CATEGORIES
        $bookCats = array(
            'Textbooks', 'Reference Books', 'Journals & Periodicals', 'E-Books',
            'Theses & Dissertations', 'Conference Proceedings', 'Law Reports',
            'Engineering Manuals', 'Medical References', 'Fiction & Literature',
        );
        foreach ($bookCats as $bc) {
            $this->db->insert('book_category', array('name' => $bc, 'branch_id' => $branchId));
        }

        // 16. SALARY TEMPLATES
        $salaryTemplates = array(
            array('Vice Chancellor', 500000), array('Deputy Vice Chancellor', 400000),
            array('Professor', 250000), array('Associate Professor', 200000),
            array('Senior Lecturer', 150000), array('Lecturer', 120000),
            array('Tutorial Fellow', 80000), array('Graduate Assistant', 55000),
            array('Registrar', 180000), array('Finance Officer', 150000),
            array('Dean', 200000), array('Head of Department', 170000),
            array('Librarian', 90000), array('ICT Officer', 100000),
            array('Lab Technician', 60000), array('Administrator', 70000),
            array('Secretary', 45000), array('Support Staff', 30000),
        );
        foreach ($salaryTemplates as $st) {
            $this->db->insert('salary_template', array('name' => $st[0], 'basic_salary' => $st[1], 'overtime_salary' => '0', 'branch_id' => $branchId));
            $templateId = $this->db->insert_id();
            $basic = $st[1];
            $paye = max(0, round(($basic - 24000) * 0.25));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'House Allowance', 'amount' => round($basic * 0.20), 'type' => 1));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'Transport Allowance', 'amount' => round($basic * 0.10), 'type' => 1));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'Research Allowance', 'amount' => round($basic * 0.05), 'type' => 1));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'NHIF', 'amount' => 1700, 'type' => 2));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'NSSF', 'amount' => 200, 'type' => 2));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'PAYE', 'amount' => $paye, 'type' => 2));
        }

        // 17. HOSTEL CATEGORIES
        $hostelCats = array(
            array('Male Hostel', 'Male student accommodation', 'Male'),
            array('Female Hostel', 'Female student accommodation', 'Female'),
            array('Postgraduate Hostel', 'Masters and PhD students', 'Mixed'),
        );
        foreach ($hostelCats as $hc) {
            $this->db->insert('hostel_category', array('name' => $hc[0], 'description' => $hc[1], 'branch_id' => $branchId, 'type' => $hc[2]));
        }

        // 18. PAYMENT CONFIG
        $exists = $this->db->get_where('payment_config', array('branch_id' => $branchId))->num_rows();
        if ($exists == 0) {
            $this->db->insert('payment_config', array(
                'branch_id' => $branchId, 'paypal_username' => '', 'paypal_password' => '',
                'paypal_signature' => '', 'paypal_email' => '', 'paypal_sandbox' => 1, 'paypal_status' => 0,
                'stripe_secret' => '', 'stripe_demo' => 1, 'stripe_status' => 0,
                'mpesa_consumer_key' => '', 'mpesa_consumer_secret' => '', 'mpesa_shortcode' => '',
                'mpesa_passkey' => '', 'mpesa_sandbox' => 1, 'mpesa_status' => 0,
            ));
        }

        // 19. ATTACHMENTS TYPES
        $attachTypes = array('Lecture Notes', 'Assignment', 'Past Papers', 'Course Outline', 'Research Papers', 'Timetable', 'Announcements');
        foreach ($attachTypes as $at) {
            $this->db->insert('attachments_type', array('name' => $at, 'branch_id' => $branchId));
        }

        // 20. FEE GROUPS (Undergraduate + Postgraduate per semester)
        $feeTypeIds = array();
        foreach ($this->db->where('branch_id', $branchId)->get('fees_type')->result() as $ft) {
            $feeTypeIds[$ft->fee_code] = $ft->id;
        }
        $semDue = date('Y-m-d', strtotime('+21 days'));
        $ugFees  = array('TUI' => 55000, 'REG' => 5000, 'LIB' => 3000, 'ICT' => 3000, 'ACT' => 2000, 'MED' => 1500, 'EXM' => 2000);
        $pgFees  = array('TUI' => 80000, 'REG' => 8000, 'LIB' => 3000, 'ICT' => 3000, 'MED' => 1500, 'EXM' => 3000);
        $accFees = array('ACC' => 25000, 'CAT' => 15000);

        $this->db->insert('fee_groups', array('name' => 'Undergraduate — Semester Fees', 'description' => 'Standard semester fees for undergraduate students', 'session_id' => $sid, 'branch_id' => $branchId));
        $ugGroupId = $this->db->insert_id();
        foreach ($ugFees as $code => $amount) {
            if (isset($feeTypeIds[$code])) {
                $this->db->insert('fee_groups_details', array('fee_groups_id' => $ugGroupId, 'fee_type_id' => $feeTypeIds[$code], 'amount' => $amount, 'due_date' => $semDue));
            }
        }
        $this->db->insert('fee_groups', array('name' => 'Postgraduate — Semester Fees', 'description' => 'Semester fees for Masters and PhD students', 'session_id' => $sid, 'branch_id' => $branchId));
        $pgGroupId = $this->db->insert_id();
        foreach ($pgFees as $code => $amount) {
            if (isset($feeTypeIds[$code])) {
                $this->db->insert('fee_groups_details', array('fee_groups_id' => $pgGroupId, 'fee_type_id' => $feeTypeIds[$code], 'amount' => $amount, 'due_date' => $semDue));
            }
        }
        $this->db->insert('fee_groups', array('name' => 'Residential — Semester (Accommodation + Meals)', 'description' => 'Hostel and meals package for resident students', 'session_id' => $sid, 'branch_id' => $branchId));
        $resGroupId = $this->db->insert_id();
        foreach ($accFees as $code => $amount) {
            if (isset($feeTypeIds[$code])) {
                $this->db->insert('fee_groups_details', array('fee_groups_id' => $resGroupId, 'fee_type_id' => $feeTypeIds[$code], 'amount' => $amount, 'due_date' => $semDue));
            }
        }
        if (isset($feeTypeIds['TUI'])) {
            $this->db->insert('fee_fine', array('group_id' => $ugGroupId, 'type_id' => $feeTypeIds['TUI'], 'fine_value' => '1000', 'fine_type' => 'fixed', 'fee_frequency' => 'monthly', 'branch_id' => $branchId, 'session_id' => $sid));
        }

        // 21. FEES REMINDERS
        $this->db->insert('fees_reminder', array('frequency' => 'Weekly',  'days' => '7',  'message' => 'Dear {guardian_name}, this is a reminder that {student_name} has an outstanding balance. Please clear fees to avoid deregistration. — {institution_name}', 'student' => 1, 'guardian' => 1, 'branch_id' => $branchId));
        $this->db->insert('fees_reminder', array('frequency' => 'Monthly', 'days' => '30', 'message' => 'Dear {student_name}, your monthly fee statement shows an outstanding balance. Please contact the Finance Office to discuss a payment plan. — {institution_name}', 'student' => 1, 'guardian' => 0, 'branch_id' => $branchId));

        // 22. CAMPUS TRANSPORT (Shuttle routes)
        $campusRoutes = array(
            array('Town Shuttle — CBD Route',     'Main Gate', 'CBD Bus Station',    'Morning & evening campus–CBD shuttle'),
            array('Town Shuttle — Westlands',     'Main Gate', 'Westlands Stage',    'Morning & evening campus–Westlands shuttle'),
            array('Airport Shuttle',              'Main Gate', 'JKIA Arrivals Gate', 'International student airport transfers'),
        );
        foreach ($campusRoutes as $rt) {
            $this->db->insert('transport_route', array('name' => $rt[0], 'start_place' => $rt[1], 'stop_place' => $rt[2], 'remarks' => $rt[3], 'branch_id' => $branchId));
        }
        $campusStops = array(
            array('Main Gate Stop',        '06:30:00', 0.00),
            array('Library Block',         '06:35:00', 0.00),
            array('Student Centre',        '06:40:00', 0.00),
            array('CBD Bus Station',       '07:00:00', 200.00),
            array('Westlands Roundabout',  '07:15:00', 200.00),
        );
        foreach ($campusStops as $st) {
            $this->db->insert('transport_stoppage', array('stop_position' => $st[0], 'stop_time' => $st[1], 'route_fare' => $st[2], 'branch_id' => $branchId));
        }
        $this->db->insert('transport_vehicle', array('vehicle_no' => 'KYY 001A', 'capacity' => '25', 'insurance_renewal' => date('Y') . '-12-31', 'driver_name' => 'TBD', 'driver_phone' => '', 'driver_license' => '', 'branch_id' => $branchId));
        $this->db->insert('transport_vehicle', array('vehicle_no' => 'KYY 002B', 'capacity' => '25', 'insurance_renewal' => date('Y') . '-12-31', 'driver_name' => 'TBD', 'driver_phone' => '', 'driver_license' => '', 'branch_id' => $branchId));

        // 23. CAMPUS BUSES (GPS tracking)
        $this->db->insert('school_buses', array('bus_name' => 'Campus Shuttle 1 — CBD Route',      'reg_number' => 'KYY 001A', 'capacity' => 25, 'driver_name' => 'TBD', 'driver_phone' => '', 'route_description' => 'Main Gate > Library > Student Centre > CBD',       'is_active' => 1, 'branch_id' => $branchId));
        $this->db->insert('school_buses', array('bus_name' => 'Campus Shuttle 2 — Westlands Route','reg_number' => 'KYY 002B', 'capacity' => 25, 'driver_name' => 'TBD', 'driver_phone' => '', 'route_description' => 'Main Gate > Library > Student Centre > Westlands', 'is_active' => 1, 'branch_id' => $branchId));

        // 24. HOSTELS + ROOMS
        $uniHostelCats = $this->db->where('branch_id', $branchId)->get('hostel_category')->result();
        $roomsPerHostel = array('Male Hostel' => 8, 'Female Hostel' => 8, 'Postgraduate Hostel' => 4);
        foreach ($uniHostelCats as $hcat) {
            $this->db->insert('hostel', array('name' => $hcat->name, 'category_id' => $hcat->id, 'address' => 'Campus Compound', 'watchman' => 'To be assigned', 'remarks' => '4-bed ensuite rooms', 'branch_id' => $branchId));
            $hostelId = $this->db->insert_id();
            $rooms = isset($roomsPerHostel[$hcat->name]) ? $roomsPerHostel[$hcat->name] : 6;
            for ($r = 1; $r <= $rooms; $r++) {
                $this->db->insert('hostel_room', array('name' => 'Room ' . str_pad($r, 3, '0', STR_PAD_LEFT), 'hostel_id' => $hostelId, 'no_beds' => 4, 'category_id' => $hcat->id, 'bed_fee' => 12500.00, 'remarks' => '4-bed ensuite room', 'branch_id' => $branchId));
            }
        }

        // 25. BANK / ACCOUNTING SETUP
        $this->db->insert('accounts', array('name' => 'Student Fees Collection Account', 'number' => 'ACC-001', 'description' => 'Main account for tuition and other student fee collection — Equity Bank', 'balance' => 0.00, 'branch_id' => $branchId));
        $this->db->insert('accounts', array('name' => 'Operating Expenses Account',      'number' => 'ACC-002', 'description' => 'Day-to-day operational expenses — Equity Bank', 'balance' => 0.00, 'branch_id' => $branchId));
        $this->db->insert('accounts', array('name' => 'Research & Development Fund',     'number' => 'ACC-003', 'description' => 'Ring-fenced account for research grants and R&D projects', 'balance' => 0.00, 'branch_id' => $branchId));
        $this->db->insert('accounts', array('name' => 'Capital Projects Account',        'number' => 'ACC-004', 'description' => 'Infrastructure and capital expenditure fund', 'balance' => 0.00, 'branch_id' => $branchId));
        $this->db->insert('accounts', array('name' => 'Petty Cash',                      'number' => 'ACC-005', 'description' => 'Petty cash float for small day-to-day expenses', 'balance' => 0.00, 'branch_id' => $branchId));

        // 26. CAFETERIA / CANTEEN MENU
        $cafeteriaItems = array(
            array('Chapati & Beans',     'Lunch',      80.00, 200), array('Rice & Beef Stew', 'Lunch',     150.00, 150),
            array('Ugali & Chicken',     'Lunch',     180.00, 100), array('Vegetable Soup',  'Lunch',      60.00, 200),
            array('Spaghetti Bolognese', 'Lunch',     200.00,  80), array('Tea (500 ml)',    'Beverages',   30.00, 500),
            array('Coffee',              'Beverages',  50.00, 200), array('Juice (300 ml)',  'Beverages',   60.00, 300),
            array('Mineral Water',       'Beverages',  30.00, 500), array('Soda (300 ml)',   'Beverages',   60.00, 200),
            array('Bread & Egg (2)',     'Breakfast',  80.00, 300), array('Mandazi (3 pcs)', 'Breakfast',   30.00, 400),
            array('Yoghurt (200 ml)',    'Beverages',  60.00, 150), array('Samosa (2 pcs)',  'Snacks',      50.00, 200),
            array('Cake Slice',          'Snacks',     80.00, 100),
        );
        foreach ($cafeteriaItems as $ci) {
            $this->db->insert('canteen_items', array('name' => $ci[0], 'category' => $ci[1], 'price' => $ci[2], 'quantity_in_stock' => $ci[3], 'is_available' => 1, 'branch_id' => $branchId));
        }

        // 27. ASSET CATEGORIES + ASSETS
        $uniCatNames = array('ICT Equipment', 'Furniture & Fittings', 'Laboratory Equipment', 'Library Equipment', 'Sports & Recreation', 'Audio Visual Equipment');
        $uniCatIds   = array();
        foreach ($uniCatNames as $acn) {
            $this->db->insert('asset_categories', array('name' => $acn, 'description' => $acn . ' owned by the institution', 'branch_id' => $branchId));
            $uniCatIds[$acn] = $this->db->insert_id();
        }
        $uniAssets = array(
            array('ICT-001', 'Desktop Computer (Lab)',      'ICT Equipment',          'Dell',    'OptiPlex',  '2023-01-01', 55000, 'Computer Lab 1'),
            array('ICT-002', 'Server (Main)',               'ICT Equipment',          'HP',      'ProLiant',  '2022-06-01',350000, 'Server Room'),
            array('ICT-003', 'Network Switch (48-port)',    'ICT Equipment',          'Cisco',   'SG350',     '2022-06-01', 45000, 'Server Room'),
            array('AV-001',  'Smart Projector',             'Audio Visual Equipment', 'Epson',   'EB-2265U',  '2023-03-01', 65000, 'Lecture Hall 1'),
            array('AV-002',  'PA Sound System',             'Audio Visual Equipment', 'Yamaha',  'DXR12',     '2021-09-01', 85000, 'Main Hall'),
            array('FRN-001', 'Lecture Hall Chairs (50)',    'Furniture & Fittings',   'Local',   NULL,        '2020-01-01', 75000, 'Lecture Hall 1'),
            array('LAB-001', 'Centrifuge Machine',          'Laboratory Equipment',   'Eppendorf','5430R',    '2022-01-01',180000, 'Science Lab'),
            array('LAB-002', 'Binocular Microscopes (10)', 'Laboratory Equipment',   'Olympus', 'CX23',      '2022-01-01',220000, 'Science Lab'),
            array('SPT-001', 'Football Goal Posts (pair)', 'Sports & Recreation',    'Vinex',   NULL,        '2021-08-01', 35000, 'Sports Ground'),
            array('LIB-001', 'Library Shelving Units (20)','Library Equipment',      'Metal',   NULL,        '2019-01-01',120000, 'Library'),
        );
        foreach ($uniAssets as $a) {
            $this->db->insert('assets', array('asset_tag' => $a[0], 'name' => $a[1], 'category_id' => isset($uniCatIds[$a[2]]) ? $uniCatIds[$a[2]] : NULL, 'brand' => $a[3], 'model' => $a[4], 'purchase_date' => $a[5], 'purchase_cost' => $a[6], 'location' => $a[7], 'condition_status' => 'good', 'branch_id' => $branchId));
        }

        // 28. INVENTORY ITEMS
        $uniInventory = array(
            array('A4 Printing Paper',      'Stationery',        'Ream',  100, 20,   550, 'Office Mart Kenya', 'Admin Store'),
            array('Whiteboard Markers',     'Classroom Supplies','Box',    30,  8,   450, 'Office Mart Kenya', 'Store Room'),
            array('Printer Toner (HP)',     'ICT Supplies',      'Piece',   5,  2,  4500, 'ICT Supplier',      'Store Room'),
            array('Exercise Books',         'Stationery',        'Piece', 500,100,    35, 'Text Book Centre',  'Store Room'),
            array('Scientific Calculators', 'Stationery',        'Piece',  30,  5,  1200, 'Office Mart Kenya', 'Store Room'),
            array('Detergent (5 L)',        'Cleaning Supplies', 'Bottle', 30,  8,   650, 'Nakumatt',          'Cleaning Store'),
            array('Toilet Paper',           'Cleaning Supplies', 'Roll',  200, 50,    45, 'Nakumatt',          'Cleaning Store'),
            array('Hand Sanitiser (5 L)',   'Health Supplies',   'Bottle', 20,  5,  2500, 'Pharmacies Ltd',    'Clinic'),
            array('Lab Gloves (100 pcs)',   'Lab Supplies',      'Box',    25,  5,  1800, 'Lab Supplies Kenya','Science Lab'),
            array('Lab Safety Goggles',     'Lab Supplies',      'Piece',  40, 10,   850, 'Lab Supplies Kenya','Science Lab'),
        );
        foreach ($uniInventory as $iv) {
            $this->db->insert('inventory_items', array('name' => $iv[0], 'category' => $iv[1], 'unit' => $iv[2], 'quantity_in_stock' => $iv[3], 'reorder_level' => $iv[4], 'unit_cost' => $iv[5], 'supplier' => $iv[6], 'location' => $iv[7], 'branch_id' => $branchId));
        }

        // 29. NOTICE BOARD (Orientation & general notices)
        $this->db->insert('notice_board', array('title' => 'Welcome — New Student Orientation', 'details' => 'Welcome to the ' . date('Y') . ' intake! Orientation for all new students will be held in Week 1. All students must register with their Student ID card at the Academic Registrar before the deadline.', 'notice_date' => date('Y-m-d'), 'expiry_date' => date('Y-m-d', strtotime('+30 days')), 'audience' => 'students', 'priority' => 'important', 'posted_by' => 1, 'branch_id' => $branchId));
        $this->db->insert('notice_board', array('title' => 'Fee Payment — Semester Deadline', 'details' => 'All students are reminded that semester fees must be cleared by the end of Week 2. Students with outstanding balances risk deregistration from units. Contact the Finance Office for payment plans.', 'notice_date' => date('Y-m-d'), 'expiry_date' => date('Y-m-d', strtotime('+45 days')), 'audience' => 'students', 'priority' => 'urgent', 'posted_by' => 1, 'branch_id' => $branchId));
        $this->db->insert('notice_board', array('title' => 'Semester Timetable Published', 'details' => 'The lecture and practical timetable for this semester is now available. Students are advised to check their unit allocations and report any conflicts to their respective Heads of Department by Friday.', 'notice_date' => date('Y-m-d'), 'expiry_date' => date('Y-m-d', strtotime('+14 days')), 'audience' => 'all', 'priority' => 'normal', 'posted_by' => 1, 'branch_id' => $branchId));
        $this->db->insert('notice_board', array('title' => 'Academic Staff — Semester Plans Due', 'details' => 'All academic staff must submit course outlines, assessment schedules, and CAT dates to the Dean of School by end of Week 1. Kindly ensure all units are uploaded on the LMS before the first lecture.', 'notice_date' => date('Y-m-d'), 'expiry_date' => date('Y-m-d', strtotime('+10 days')), 'audience' => 'staff', 'priority' => 'important', 'posted_by' => 1, 'branch_id' => $branchId));

        // 30. STAFF APPRAISAL TEMPLATES
        $this->db->insert('appraisal_templates', array('name' => 'Annual Academic Staff Appraisal', 'description' => 'Comprehensive annual performance appraisal for all academic (teaching) staff covering research, teaching quality, student outcomes and community service.', 'appraisal_type' => 'annual', 'status' => 'active', 'branch_id' => $branchId));
        $acadTplId = $this->db->insert_id();
        $acadCriteria = array(
            array('Teaching Quality & Student Feedback', 10, 2.0, 'Teaching'),
            array('Research Output (Papers/Projects)',   10, 2.5, 'Research'),
            array('Student Pass Rate & Performance',    10, 2.0, 'Results'),
            array('Attendance, Punctuality & Deadlines',10, 1.0, 'Professionalism'),
            array('Community Service & Outreach',       10, 0.5, 'Service'),
            array('Curriculum Development',             10, 1.0, 'Academic'),
            array('Postgraduate Supervision',           10, 1.0, 'Research'),
        );
        foreach ($acadCriteria as $cr) {
            $this->db->insert('appraisal_criteria', array('template_id' => $acadTplId, 'criterion' => $cr[0], 'max_score' => $cr[1], 'weight' => $cr[2], 'category' => $cr[3], 'branch_id' => $branchId));
        }
        $this->db->insert('appraisal_templates', array('name' => 'Non-Teaching Staff Appraisal', 'description' => 'Annual performance appraisal for administrative, support and technical staff.', 'appraisal_type' => 'annual', 'status' => 'active', 'branch_id' => $branchId));
        $this->db->insert('appraisal_templates', array('name' => 'Probation Review (3-Month)', 'description' => 'Probation assessment at the 3-month mark for all newly recruited staff.', 'appraisal_type' => 'probation', 'status' => 'active', 'branch_id' => $branchId));

        // 31. RESEARCH GRANT / BURSARY PROGRAMME
        $this->db->insert('bursary_programmes', array('name' => 'Student Scholarship Fund ' . date('Y'), 'provider' => 'University Board of Directors', 'provider_type' => 'private', 'description' => 'Merit-based scholarship for outstanding students with GPA ≥ 3.5. Covers full tuition for the academic year.', 'total_allocation' => 1000000.00, 'academic_year' => date('Y'), 'application_deadline' => date('Y') . '-03-31', 'status' => 'open', 'branch_id' => $branchId));
        $this->db->insert('bursary_programmes', array('name' => 'Research Grant ' . date('Y'), 'provider' => 'National Research Fund', 'provider_type' => 'government', 'description' => 'Government-funded research grant for postgraduate and undergraduate research projects.', 'total_allocation' => 5000000.00, 'academic_year' => date('Y'), 'application_deadline' => date('Y') . '-04-30', 'status' => 'open', 'branch_id' => $branchId));
        $this->db->insert('bursary_programmes', array('name' => 'HELB Bursary Coordination ' . date('Y'), 'provider' => 'Higher Education Loans Board', 'provider_type' => 'government', 'description' => 'HELB loan and bursary coordination for eligible needy students. Students apply directly at helb.co.ke.', 'total_allocation' => 2000000.00, 'academic_year' => date('Y'), 'application_deadline' => date('Y') . '-05-31', 'status' => 'open', 'branch_id' => $branchId));

        // 32. PTM / PARENT CONSULTATION SESSION
        $this->db->insert('ptm_sessions', array('title' => 'Semester 1 ' . date('Y') . ' Academic Consultation Day', 'session_date' => date('Y-m-d', strtotime('+60 days')), 'start_time' => '09:00:00', 'end_time' => '15:00:00', 'venue' => 'Academic Block — Consultation Rooms', 'slot_duration_mins' => 20, 'notes' => 'Students and guardians may book slots to meet lecturers and academic advisors to discuss academic progress.', 'branch_id' => $branchId));

        // 33. CBT / ONLINE ASSESSMENT SAMPLE
        $this->db->insert('cbt_quiz', array('title' => 'Communication Skills — Sample Quiz', 'duration_mins' => 45, 'total_marks' => 20, 'pass_marks' => 10, 'instructions' => 'Answer all questions. Each question carries 1 mark. Time allowed: 45 minutes.', 'shuffle_questions' => 1, 'shuffle_options' => 1, 'show_result' => 1, 'status' => 'draft', 'created_by' => 1, 'branch_id' => $branchId));
        $uniQuizId = $this->db->insert_id();
        $uniQns = array(
            array('Which of the following is NOT a barrier to effective communication?', 'mcq', 'Clear language', 'Noise', 'Language differences', 'Poor listening', 'A'),
            array('A formal letter must include a subject line.',                       'true_false', 'True', 'False', NULL, NULL, 'A'),
            array('Active listening involves:',                                          'mcq', 'Interrupting the speaker', 'Maintaining eye contact and focusing', 'Thinking of your response while others speak', 'Checking your phone', 'B'),
        );
        foreach ($uniQns as $q) {
            $this->db->insert('cbt_questions', array('quiz_id' => $uniQuizId, 'question_text' => $q[0], 'question_type' => $q[1], 'option_a' => $q[2], 'option_b' => $q[3], 'option_c' => $q[4], 'option_d' => $q[5], 'correct_answer' => $q[6], 'marks' => 1, 'branch_id' => $branchId));
        }

        // 34. VIRTUAL CLASS SAMPLE
        $this->db->insert('virtual_classes', array('title' => 'Introduction to Programming — Online Lecture', 'description' => 'First online lecture for ICS 101. Please install Python before the session. Recording will be available on LMS.', 'platform' => 'meet', 'meeting_link' => 'https://meet.google.com/placeholder', 'scheduled_at' => date('Y-m-d H:i:s', strtotime('+7 days 10:00:00')), 'duration_mins' => 90, 'status' => 'upcoming', 'branch_id' => $branchId));

        // No auto-subscription: school admin must select a plan and pay (or superadmin activates manually).
    }
}
