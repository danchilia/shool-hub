<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Analytics extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $branchId = get_loggedin_branch_id();

        // Students via enroll (branch filtering through enroll table)
        $students = $this->db
            ->select('s.id AS student_id, s.first_name, s.last_name, s.register_no, c.name AS class, se.name AS section_name, e.class_id')
            ->from('student s')
            ->join('enroll e', 'e.student_id = s.id AND e.branch_id = '.(int)$branchId)
            ->join('class c', 'c.id = e.class_id', 'left')
            ->join('section se', 'se.id = e.section_id', 'left')
            ->group_by('s.id')
            ->get()->result_array();

        $atRisk        = array();
        $feeDefaulters = array();

        foreach ($students as $s) {
            $sid = (int)$s['student_id'];

            // ── Attendance risk (last 30 days) ─────────────────────────────
            $totalDays  = $this->db
                ->where('student_id', $sid)
                ->where('branch_id', $branchId)
                ->where('date >=', date('Y-m-d', strtotime('-30 days')))
                ->count_all_results('student_attendance');
            $absentDays = $this->db
                ->where('student_id', $sid)
                ->where('branch_id', $branchId)
                ->where('status', 'A')
                ->where('date >=', date('Y-m-d', strtotime('-30 days')))
                ->count_all_results('student_attendance');
            $attendanceRisk = ($totalDays > 0) ? min(40, (int)round(($absentDays / $totalDays) * 40)) : 0;

            // ── Performance risk (avg mark across all subjects) ────────────
            $markRow = $this->db->query("
                SELECT AVG(CAST(m.mark AS DECIMAL(10,2))) AS avg_mark
                FROM `mark` m
                WHERE m.student_id = {$sid}
                  AND m.branch_id  = {$branchId}
                  AND m.mark IS NOT NULL
                  AND m.mark != ''
                  AND m.mark REGEXP '^[0-9]'
            ")->row_array();
            $avgMark     = isset($markRow['avg_mark']) && $markRow['avg_mark'] !== null ? (float)$markRow['avg_mark'] : null;
            $performRisk = ($avgMark !== null && $avgMark < 40) ? min(40, (int)round(40 - $avgMark)) : 0;

            // ── Fee risk ────────────────────────────────────────────────────
            $feeRow = $this->db->query("
                SELECT
                    COALESCE((SELECT SUM(fgd.amount)
                              FROM fee_groups_details fgd
                              JOIN fee_allocation fa ON fa.group_id = fgd.fee_groups_id
                              WHERE fa.student_id = {$sid} AND fa.branch_id = {$branchId}), 0) AS total_fee,
                    COALESCE((SELECT SUM(fph.amount - COALESCE(fph.discount, 0))
                              FROM fee_payment_history fph
                              JOIN fee_allocation fa ON fa.id = fph.allocation_id
                              WHERE fa.student_id = {$sid} AND fa.branch_id = {$branchId}), 0) AS paid
            ")->row_array();
            $totalFee    = (float)($feeRow['total_fee'] ?? 0);
            $paid        = (float)($feeRow['paid'] ?? 0);
            $outstanding = max(0, $totalFee - $paid);
            $feeRisk     = $outstanding > 0 ? min(20, (int)round($outstanding / 500)) : 0;

            $riskScore = $attendanceRisk + $performRisk + $feeRisk;

            $s['attendance_risk'] = $attendanceRisk;
            $s['perform_risk']    = $performRisk;
            $s['fee_risk']        = $feeRisk;
            $s['risk_score']      = $riskScore;
            $s['absent_days']     = $absentDays;
            $s['total_days']      = $totalDays;
            $s['avg_mark']        = $avgMark;
            $s['outstanding']     = $outstanding;

            if ($riskScore >= 20) {
                $atRisk[] = $s;
            }
            if ($outstanding >= 1000) {
                $feeDefaulters[] = $s;
            }
        }

        usort($atRisk, function($a, $b) { return $b['risk_score'] - $a['risk_score']; });
        usort($feeDefaulters, function($a, $b) { return $b['outstanding'] - $a['outstanding']; });

        // Attendance trend (last 14 days)
        $trend = $this->db->query("
            SELECT `date` AS attendance_date,
                   COUNT(*) AS total,
                   SUM(CASE WHEN status = 'P' THEN 1 ELSE 0 END) AS present
            FROM student_attendance
            WHERE branch_id = {$branchId}
              AND `date` >= CURDATE() - INTERVAL 14 DAY
            GROUP BY `date`
            ORDER BY `date`
        ")->result_array();

        $this->data['atRisk']           = array_slice($atRisk, 0, 50);
        $this->data['feeDefaulters']    = array_slice($feeDefaulters, 0, 50);
        $this->data['totalStudents']    = count($students);
        $this->data['totalAtRisk']      = count($atRisk);
        $this->data['totalDefaulters']  = count($feeDefaulters);
        $this->data['totalOutstanding'] = array_sum(array_column($feeDefaulters, 'outstanding'));
        $this->data['trend']            = $trend;
        $this->data['sub_page']         = 'analytics/index';
        $this->data['main_menu']        = 'analytics';
        $this->data['title']            = 'Performance Analytics';
        $this->load->view('layout/index', $this->data);
    }
}
