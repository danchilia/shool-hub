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
            $this->db->insert('branch', $arrayBranch);
            $branchId = $this->db->insert_id();
            if (isset($data['kenya_template']) && $data['kenya_template']) {
                $this->seedKenyanDefaults($branchId);
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
        $sections = array('A', 'B', 'C');
        foreach ($sections as $sec) {
            $exists = $this->db->get_where('section', array('name' => $sec, 'branch_id' => $branchId))->num_rows();
            if ($exists == 0) {
                $this->db->insert('section', array('name' => $sec, 'capacity' => 40, 'branch_id' => $branchId));
            }
        }
        $sectionIds = array();
        $secRows = $this->db->where('branch_id', $branchId)->get('section')->result();
        foreach ($secRows as $s) {
            $sectionIds[] = $s->id;
        }

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

        $learningAreas = array(
            'lower_primary' => array('Literacy Activities', 'Kiswahili Language Activities', 'English Language Activities', 'Mathematics Activities', 'Environmental Activities', 'Hygiene and Nutrition Activities', 'Religious Education Activities', 'Movement and Creative Activities'),
            'upper_primary' => array('English', 'Kiswahili', 'Mathematics', 'Science and Technology', 'Social Studies', 'Religious Education', 'Creative Arts', 'Physical and Health Education', 'Agriculture'),
            'junior_secondary' => array('English', 'Kiswahili', 'Mathematics', 'Integrated Science', 'Social Studies', 'Pre-Technical Studies', 'Agriculture', 'Creative Arts and Sports', 'Religious Education'),
        );
        foreach ($learningAreas as $level => $areas) {
            foreach ($areas as $areaName) {
                $this->db->insert('cbc_learning_areas', array('name' => $areaName, 'level' => $level, 'branch_id' => $branchId));
            }
        }

        $terms = array('Term 1', 'Term 2', 'Term 3');
        $sessionId = $this->db->select('id')->order_by('id', 'DESC')->limit(1)->get('schoolyear')->row();
        $sessionIdVal = $sessionId ? $sessionId->id : 1;
        foreach ($terms as $term) {
            $this->db->insert('exam_term', array('name' => $term, 'branch_id' => $branchId, 'session_id' => $sessionIdVal));
        }
    }
}
