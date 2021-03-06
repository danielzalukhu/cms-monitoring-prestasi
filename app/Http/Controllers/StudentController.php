<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Url;
use App\Student;
use App\Absent;
use App\ViolationRecord;
use App\AchievementRecord;
use App\AcademicYear;
use App\Subject;
use App\Staff;
use App\DepartmentStaff;
use App\Grade;
use App\Achievement;
use App\SubjectReport;
use App\GradeStudent;
use DB;
use Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {       
        $kelas = Grade::all();

        $min_grade_id = Grade::select(DB::raw('MIN(id) as id'))->get()[0]->id;
        
        if($request->has('gradeId')){                
            $grade_id = $request->gradeId;
        }
        else{
            $grade_id = $min_grade_id;
        }
        
        $selected_student = GradeStudent::select(DB::raw('MAX(ACADEMIC_YEAR_ID) AS id'))->limit(1)->first()->id;

        if(Auth::guard('web')->user()->staff->ROLE === "TEACHER"){                    
            $kelas_guru = Grade::where('STAFFS_ID', $request->session()->get('session_user_id'))->first()->id;
            
            $siswa = GradeStudent::where('GRADES_ID', $kelas_guru)->where('ACADEMIC_YEAR_ID', $selected_student)->get();
        }
        else{
            $siswa = GradeStudent::where('GRADES_ID', $grade_id)->where('ACADEMIC_YEAR_ID', $selected_student)->get();
        }

        return view('student.index', compact('kelas', 'siswa', 'grade_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $siswa = Student::whereId($id)->firstOrFail();
        $siswa->delete();
        return redirect(action('StudentController@index'))->with('sukses', 'Student has been deleted');
    }

    public function profile(Request $request, $id)
    {
        $siswa = Student::find($id);

        $student_grade_name = $siswa->getGradeName();
        
        $siswaku = Student::where('STUDENTS_ID', $id);

        $absen = Absent::where('STUDENTS_ID', $id)
                    ->orderBy('START_DATE', 'END_DATE', 'ASC')
                    ->get();

        $catatan_absen = Absent::select('TYPE', DB::raw('COUNT(TYPE) AS TOTAL'))
                            ->where('STUDENTS_ID', $id)
                            ->where('ACADEMIC_YEAR_ID', $request->session()->get('session_academic_year_id'))
                            ->groupBy('TYPE')
                            ->get();

        $x = new DashboardController();
        $kehadiran =  $x->showAbsentKu($siswa->id);        

        $selected_student_ay = Student::select('ACADEMIC_YEAR_ID AS AY_ID')->where('id', $siswa->id) ->first()->AY_ID;                        

        $tahun_ajaran = AcademicYear::where('id', '>=', $selected_student_ay)->get(); 
                                           
        $catatan_pelanggaran = ViolationRecord::join('violations', 'violation_records.VIOLATIONS_ID', 'violations.id')
                                            ->select('violation_records.*', 'violations.*')
                                            ->where('STUDENTS_ID', '=', $id)
                                            ->where('ACADEMIC_YEAR_ID', '=', $request->session()->get('session_academic_year_id'))
                                            ->where('violations.NAME', 'NOT LIKE', 'TTS%')
                                            ->orderBy('violation_records.DATE', 'DESC')
                                            ->get();

        $violation_point = ViolationRecord::select(DB::raw('SUM(TOTAL) AS POINT'))                        
                            ->where('STUDENTS_ID', $id)
                            ->first()->POINT;    
                
        $achievement_point = AchievementRecord::join('achievements', 'achievement_records.ACHIEVEMENTS_ID', 'achievements.id')
                                        ->select(DB::raw('SUM(POINT) AS POINT'))
                                        ->where('achievement_records.STUDENTS_ID', $id)
                                        ->first()->POINT;            
        
        $catatan_penghargaan = AchievementRecord::join('achievements', 'achievement_records.ACHIEVEMENTS_ID', 'achievements.id')                                        
                                            ->select('achievements.*', 'achievement_records.*')
                                            ->where('STUDENTS_ID', '=', $id)
                                            ->where('ACADEMIC_YEAR_ID', '=', $request->session()->get('session_academic_year_id'))
                                            ->orderBy('achievement_records.DATE', 'DESC')
                                            ->get();
            
        $maxId = AcademicYear::select(DB::raw('MAX(id) as id'))->get()[0]->id;

        if($request->has('academicYearId')){
            $academic_year_id = $request->academicYearId;
        }
        else{
            $academic_year_id = $maxId;
        }
        
        $selected_tahun_ajaran = AcademicYear::select(DB::raw('MONTH(START_DATE) AS STARTMONTH'), 
                                                      DB::raw('MONTH(END_DATE) AS ENDMONTH'))                                            
                                            ->where('id', $academic_year_id)
                                            ->get()[0];   

        // GRAFIK TAB VIOLATION

        $kategori = DB::select("SELECT (CASE WHEN v.NAME LIKE 'R%' THEN 'RINGAN'
                                        WHEN v.NAME LIKE 'B%' THEN 'BERAT'
                                        WHEN v.NAME LIKE 'SB%' THEN 'SANGATBERAT'
                                END) AS KATEGORI
                                FROM violation_records vr INNER JOIN violations v ON vr.VIOLATIONS_ID = v.id
                                WHERE v.NAME NOT LIKE 'TTS%'
                                GROUP BY KATEGORI ");

        $data = ViolationRecord::join('violations', 'violation_records.VIOLATIONS_ID', 'violations.id')
                            ->select(DB::raw('(CASE
                                        WHEN violations.NAME LIKE "R%" THEN "RINGAN"
                                        WHEN violations.NAME LIKE "B%" THEN "BERAT" 
                                        WHEN violations.NAME LIKE "SB%" THEN "SANGATBERAT"
                                        END) AS KATEGORI'),
                                    DB::raw('MONTH(violation_records.DATE) AS BULAN'),
                                    DB::raw('COUNT(*) AS JUMLAH'))
                            ->where('violation_records.ACADEMIC_YEAR_ID', $academic_year_id)
                            ->where('violation_records.STUDENTS_ID', $id)
                            ->where('violations.NAME', 'NOT LIKE', 'TTS%')
                            ->groupBy('KATEGORI')->groupBy('BULAN')
                            ->orderBy('violation_records.id', 'DESC')->get();                                                                                                   

        // GRAFIK TAB ACHIEVEMENT

        $type = Achievement::select(DB::raw('GRADE AS TINGKAT'))
                        ->groupBy('TINGKAT')
                        ->get();

        $dataAchievement = DB::select("SELECT a.GRADE AS TINGKAT, MONTH(ass.DATE) AS BULAN , COUNT(*) AS JUMLAH 
                                       FROM achievements a INNER JOIN achievement_records ass ON a.id = ass.ACHIEVEMENTS_ID
                                       WHERE ACADEMIC_YEAR_ID = " . $academic_year_id ."  AND STUDENTS_ID = " . $id . "
                                       GROUP BY TINGKAT, BULAN
                                       ORDER BY BULAN ASC");  
          
        
        // GRAFIK TAB ABSENT 

        $tipeAbsen = Absent::select(DB::raw('TYPE AS TIPE'))
                        ->groupBy('TIPE')
                        ->get();
        
        $dataAbsen = Absent::select(DB::raw('TYPE AS TIPE'), DB::raw('ACADEMIC_YEAR_ID AS TAHUNAJARAN'), DB::raw('COUNT(*) AS JUMLAH'))
                        ->where('ACADEMIC_YEAR_ID', $academic_year_id)
                        ->where('STUDENTS_ID', $id)                                    
                        ->groupBy('TIPE', 'TAHUNAJARAN')
                        ->get();                      
        
        $count_total_day_each_ay = AcademicYear::select(DB::raw('DATEDIFF(END_DATE, START_DATE) AS TOTALHARI'))
                                            ->where('id', $academic_year_id)->first()->TOTALHARI;                          

        // RETURN VIEW 

        if(Auth::guard('web')->user()->ROLE === "STAFF")
            return view('student.profile', compact('siswa', 'student_grade_name', 'absen', 'catatan_absen', 'kehadiran', 'tahun_ajaran',
                                                   'catatan_pelanggaran', 'violation_point',
                                                   'catatan_penghargaan', 'achievement_point',
                                                   'kategori', 'data', 'selected_tahun_ajaran', 'academic_year_id',
                                                   'type', 'dataAchievement',
                                                   'tipeAbsen', 'dataAbsen', 'count_total_day_each_ay'));
        elseif(Auth::guard('web')->user()->ROLE === "PARENT")
            return view('student.profile', compact('siswa', 'absen', 'student_grade_name' , 'catatan_absen', 'kehadiran', 'tahun_ajaran',
                                                'catatan_pelanggaran', 'violation_point',
                                                'catatan_penghargaan', 'achievement_point',
                                                'kategori', 'data', 'selected_tahun_ajaran', 'academic_year_id',
                                                'type', 'dataAchievement',
                                                'tipeAbsen', 'dataAbsen', 'count_total_day_each_ay'));        
        else                                               
            return view('student.profile', compact('siswa', 'absen', 'catatan_absen', 'kehadiran', 'tahun_ajaran',
                                                   'catatan_pelanggaran', 'violation_point',
                                                   'catatan_penghargaan', 'achievement_point',
                                                   'kategori', 'data', 'selected_tahun_ajaran', 'academic_year_id',
                                                   'type', 'dataAchievement',
                                                   'tipeAbsen', 'dataAbsen', 'count_total_day_each_ay'));

    }

    public function showDetailAbsent(Request $request)
    {        
        $absen = Absent::select('TYPE', 'START_DATE', 'END_DATE', 'DESCRIPTION')
                        ->where('STUDENTS_ID', $request->studentId)
                        ->where('TYPE', $request->absentType)
                        ->where('ACADEMIC_YEAR_ID', $request->academicYearId)
                        ->get();

        return $absen;                               
    }

    public function returnDataViolationChart(Request $request)
    {
        $category = DB::select("SELECT (CASE WHEN v.NAME LIKE 'R%' THEN 'RINGAN'
                                        WHEN v.NAME LIKE 'B%' THEN 'BERAT'
                                        WHEN v.NAME LIKE 'SB%' THEN 'SANGATBERAT'
                                        WHEN v.NAME LIKE 'TTS%' THEN 'KETIDAKTUNTASAN'
                                END) AS KATEGORI
                                FROM violation_records vr INNER JOIN violations v ON vr.VIOLATIONS_ID = v.id
                                GROUP BY KATEGORI ");

        $dataViolation = DB::select("SELECT (CASE WHEN v.NAME LIKE 'R%' THEN 'RINGAN'
                                    WHEN v.NAME LIKE 'B%' THEN 'BERAT'
                                    WHEN v.NAME LIKE 'SB%' THEN 'SANGATBERAT'
                                    WHEN v.NAME LIKE 'TTS%' THEN 'KETIDAKTUNTASAN'
                            END) AS KATEGORI, MONTH(vr.DATE) AS BULAN , COUNT(*) AS JUMLAH 
                            FROM violation_records vr INNER JOIN violations v ON vr.VIOLATIONS_ID = v.id 
                            WHERE ACADEMIC_YEAR_ID = " . $request->academicYearId  . " AND STUDENTS_ID = " . $request->studentId . "
                            GROUP BY KATEGORI, BULAN
                            ORDER BY BULAN ASC ");

        $selected_tahun_ajaran = AcademicYear::select(DB::raw('MONTH(START_DATE) AS STARTMONTH'), 
                                                      DB::raw('MONTH(END_DATE) AS ENDMONTH'))                                            
                                        ->where('id', $request->academicYearId)
                                        ->get()[0]; 
                            
        $data['category'] = $category;
        $data['dataViolation'] = $dataViolation;
        $data['selected_tahun_ajaran'] = $selected_tahun_ajaran;

        return $data;                   
    }

    public function returnDataAchievementChart(Request $request)
    {        
        $type = Achievement::select(DB::raw('GRADE AS TINGKAT'))
                        ->groupBy('TINGKAT')
                        ->get();
        
        $dataAchievement = DB::select("SELECT a.GRADE AS TINGKAT, MONTH(ass.DATE) AS BULAN , COUNT(*) AS JUMLAH 
                                       FROM achievements a INNER JOIN achievement_records ass ON a.id = ass.ACHIEVEMENTS_ID
                                       WHERE ACADEMIC_YEAR_ID = " . $request->academicYearId ."  AND STUDENTS_ID = " . $request->studentId . "
                                       GROUP BY TINGKAT, BULAN
                                       ORDER BY BULAN ASC");                  
                
        $selected_tahun_ajaran = AcademicYear::select(DB::raw('MONTH(START_DATE) AS STARTMONTH'), 
                                                      DB::raw('MONTH(END_DATE) AS ENDMONTH'))                                            
                                        ->where('id', $request->academicYearId)
                                        ->get()[0]; 
        $data['type'] = $type;
        $data['dataAchievement'] = $dataAchievement;
        $data['selected_tahun_ajaran'] = $selected_tahun_ajaran;

        return $data;                   
    }

    public function returnDataAbsentChart(Request $request)
    {
        $tipeAbsen = Absent::select(DB::raw('TYPE AS TIPE'))
                        ->groupBy('TIPE')
                        ->get();
                        
        $dataAbsen = Absent::select(DB::raw('TYPE AS TIPE'), DB::raw('ACADEMIC_YEAR_ID AS TAHUNAJARAN'), DB::raw('COUNT(*) AS JUMLAH'))
                        ->where('ACADEMIC_YEAR_ID', $request->academicYearId)
                        ->where('STUDENTS_ID', $request->studentId)                                    
                        ->groupBy('TIPE', 'TAHUNAJARAN')
                        ->get();  

        $count_total_day_each_ay = AcademicYear::select(DB::raw('DATEDIFF(END_DATE, START_DATE) AS TOTALHARI'))
                                            ->where('id', $request->academicYearId)->first()->TOTALHARI;

        $data['type'] = $tipeAbsen;
        $data['dataAbsent'] = $dataAbsen;
        $data['count_total_day_each_ay'] = $count_total_day_each_ay;

        return $data;
    }

    public function mapelku(Request $request)
    {                        
        if(Auth::guard('web')->user()->ROLE === "STUDENT"){
            $grade_record = GradeStudent::select('GRADES_ID')
                                ->where('ACADEMIC_YEAR_ID', '<=', $request->session()->get('session_academic_year_id'))
                                ->where('STUDENTS_ID', $request->session()->get('session_student_id'))
                                ->get();    

            $student_class = $request->session()->get('session_student_class');                                
        }
        elseif(Auth::guard('web')->user()->ROLE === "PARENT"){
            $grade_record = GradeStudent::select('GRADES_ID')
                                ->where('ACADEMIC_YEAR_ID', '<=', $request->session()->get('session_academic_year_id'))
                                ->where('STUDENTS_ID', $request->session()->get('session_guardian_id'))
                                ->get();           
            
            $student_class = $request->session()->get('session_guardian_student_class');      
        }
        
        $selected_student = GradeStudent::select(DB::raw('MAX(ACADEMIC_YEAR_ID) AS id'))->limit(1)->first()->id;                      

        if($request->has('gradeName')){
            $grade_name = $request->gradeName;            
        }
        else{
            $grade_name = $student_class;
        }

        $get_grade_id = Grade::select('id')
                            ->where('NAME', $grade_name)
                            ->first()->id;
                
        $first_query = Subject::leftJoin('subject_reports', 'subject_reports.SUBJECTS_ID', 'subjects.id')
                            ->select('subjects.id AS ID', 'subjects.CODE', 'subjects.DESCRIPTION', 'subjects.MINIMALPOIN',
                                     'subject_reports.TUGAS', 'subject_reports.PH', 
                                     'subject_reports.PTS', 'subject_reports.PAS', 'subject_reports.FINAL_SCORE')
                            ->where('subjects.CODE', 'LIKE', '%' . $grade_name . '%')
                            ->whereNull('subject_reports.SUBJECTS_ID');
        
        if(Auth::guard('web')->user()->ROLE === "STUDENT"){
            $sub_query = GradeStudent::select(DB::raw('grades_students.ACADEMIC_YEAR_ID + 1 AS LASTID'))
                                    ->where('grades_students.STUDENTS_ID', $request->session()->get('session_student_id'))
                                    ->where('grades_students.GRADES_ID', $get_grade_id)
                                    ->first()->LASTID;
            
            $subject = Subject::leftJoin('subject_reports', 'subject_reports.SUBJECTS_ID', 'subjects.id')
                                    ->join('subject_records', 'subject_reports.SUBJECT_RECORD_ID', 'subject_records.id')
                                    ->select('subjects.id AS ID', 'subjects.CODE', 'subjects.DESCRIPTION', 'subjects.MINIMALPOIN',
                                            'subject_reports.TUGAS', 'subject_reports.PH', 
                                            'subject_reports.PTS', 'subject_reports.PAS', 'subject_reports.FINAL_SCORE')
                                    ->where('subjects.CODE', 'LIKE', '%' . $grade_name . '%')
                                    ->where('subject_records.STUDENTS_ID', $request->session()->get('session_student_id'))
                                    ->where('subject_records.ACADEMIC_YEAR_ID', $sub_query)
                                    ->union($first_query)
                                    ->orderBy('ID')
                                    ->get();                                    
        }
        elseif(Auth::guard('web')->user()->ROLE === "PARENT"){
            $sub_query = GradeStudent::select(DB::raw('grades_students.ACADEMIC_YEAR_ID + 1 AS LASTID'))
                                    ->where('grades_students.STUDENTS_ID', $request->session()->get('session_guardian_id'))
                                    ->where('grades_students.GRADES_ID', $get_grade_id)
                                    ->first()->LASTID;
            
            $subject = Subject::leftJoin('subject_reports', 'subject_reports.SUBJECTS_ID', 'subjects.id')
                                    ->join('subject_records', 'subject_reports.SUBJECT_RECORD_ID', 'subject_records.id')
                                    ->select('subjects.id AS ID', 'subjects.CODE', 'subjects.DESCRIPTION', 'subjects.MINIMALPOIN',
                                            'subject_reports.TUGAS', 'subject_reports.PH', 
                                            'subject_reports.PTS', 'subject_reports.PAS', 'subject_reports.FINAL_SCORE')
                                    ->where('subjects.CODE', 'LIKE', '%' . $grade_name . '%')
                                    ->where('subject_records.STUDENTS_ID', $request->session()->get('session_guardian_id'))
                                    ->where('subject_records.ACADEMIC_YEAR_ID', $sub_query)
                                    ->union($first_query)
                                    ->orderBy('ID')
                                    ->get();
        }
        
        return view('student.mapel-ku', compact('subject', 'grade_record', 'grade_name'));
    }

    public function mapelguru(Request $request)
    {
        $get_department_id = DepartmentStaff::select('DEPARTMENTS_ID')
                                    ->where('STAFFS_ID', $request->session()->get('session_user_id'))
                                    ->get();
        
        $get_grade_id = Grade::select('NAME')
                            ->where('STAFFS_ID', $request->session()->get('session_user_id'))
                            ->get();
        
        $a = array($get_department_id, $get_grade_id);
        
        $tmp_subject = [];

        foreach($get_department_id as $deparment_id){
            foreach($get_grade_id as $grade_id){
                $selected_mapel = Subject::select('id', 'CODE', 'DESCRIPTION')
                            ->where('DEPARTMENTS_ID', $deparment_id->DEPARTMENTS_ID)
                            ->where('CODE', 'LIKE', '%' . $grade_id->NAME . '%')
                            ->get();

                array_push($tmp_subject, $selected_mapel);
            }
        }

        $kelas = Grade::all();
        
        $gname = Grade::select('NAME')
                            ->where('id', Grade::select(DB::raw('MIN(id) as id'))->first()->id)
                            ->get()[0]->NAME;
        
        $gid = Grade::select(DB::raw('MIN(id) as id'))->first()->id;
        
        if($request->has('gradeName')){                
            $grade_name = $request->gradeName;
        }
        else{
            $grade_name = $gname;
        }
        
        if(Auth::guard('web')->user()->staff->ROLE === "TEACHER")
            $subject = $tmp_subject[0];
        elseif(Auth::guard('web')->user()->staff->ROLE === "HEADMASTER")
            $subject = Subject::where('CODE', 'LIKE', '%' . $grade_name  . '%')->get();
        
        return view('student.mapel-guru', compact('subject', 'kelas', 'grade_name', 'gid'));
    }

    public function teacherGetStudentSubject(Request $request, $idsiswa, $kodelkelas)
    {
        $siswa = Student::find($idsiswa);
        
        $grade_record = GradeStudent::select('GRADES_ID')
                                    ->where('ACADEMIC_YEAR_ID', '<=', $request->session()->get('session_academic_year_id'))
                                    ->where('STUDENTS_ID',$siswa->id)
                                    ->get();                        
        
        $student_class = $request->session()->get('session_student_class');    
        foreach($grade_record as $gr){
            if($request->has('filterGrade')){
                $grade_name = $request->filterGrade;            
            }
            else{
                $grade_name = $gr->grade->NAME;
            }  

            $get_grade_id = Grade::select('id')
                                ->where('NAME', $grade_name)
                                ->first()->id;

            $first_query = Subject::leftJoin('subject_reports', 'subject_reports.SUBJECTS_ID', 'subjects.id')
                                ->select('subjects.id AS ID', 'subjects.CODE', 'subjects.DESCRIPTION', 'subjects.MINIMALPOIN',
                                        'subject_reports.TUGAS', 'subject_reports.PH', 
                                        'subject_reports.PTS', 'subject_reports.PAS', 'subject_reports.FINAL_SCORE')
                                ->where('subjects.CODE', 'LIKE', '%' . $grade_name . '%')
                                ->whereNull('subject_reports.SUBJECTS_ID');
            
            $sub_query = GradeStudent::select(DB::raw('grades_students.ACADEMIC_YEAR_ID + 1 AS LASTID'))
                                    ->where('grades_students.STUDENTS_ID', $siswa->id)
                                    ->where('grades_students.GRADES_ID', $get_grade_id)
                                    ->first()->LASTID;
                
            $subject = Subject::leftJoin('subject_reports', 'subject_reports.SUBJECTS_ID', 'subjects.id')
                            ->join('subject_records', 'subject_reports.SUBJECT_RECORD_ID', 'subject_records.id')
                            ->select('subjects.id AS ID', 'subjects.CODE', 'subjects.DESCRIPTION', 'subjects.MINIMALPOIN',
                                    'subject_reports.TUGAS', 'subject_reports.PH', 
                                    'subject_reports.PTS', 'subject_reports.PAS', 'subject_reports.FINAL_SCORE')
                            ->where('subjects.CODE', 'LIKE', '%' . $grade_name . '%')
                            ->where('subject_records.STUDENTS_ID', $siswa->id)
                            ->where('subject_records.ACADEMIC_YEAR_ID', $sub_query)
                            ->union($first_query)
                            ->orderBy('ID')
                            ->get();     
            
        }
        
        return view('teacher.teacher-get-student-subject', compact('siswa', 'subject', 'grade_record', 'grade_name'));
    }    

    public function getStudentSubjectDetail(Request $request, $idsiswa, $idmapel)
    {
        $siswa = Student::find($idsiswa);

        $mapel = Subject::find($idmapel);            

        $max_academic_year_id = AcademicYear::select(DB::raw('MAX(id) as id'))->get()[0]->id;

        if($request->has('academicYearId')){
            $academic_year_id = $request->academicYearId;
        }
        else{
            $academic_year_id = $max_academic_year_id;
        }
        
        $kkm = Subject::select('MINIMALPOIN')->where('id', $mapel->id)->get();

        $x = explode("-", $mapel->CODE, 3);
        $y = $x[0] . "-" . $x[1];
        
        $select_grade_id = Grade::select('id')->where('NAME', $y)->first()->id;
        
        $url = parse_url(url()->previous(), PHP_URL_QUERY);    
        $get_url_param = explode("=", $url);
        
        if(isset($url)){
            $select_grade_id = Grade::select('id')->where('NAME', $get_url_param[1])->first()->id;
            $selected_student_in_ay = GradeStudent::select('ACADEMIC_YEAR_ID')
                                                ->where('STUDENTS_ID', $siswa->id)
                                                ->where('GRADES_ID', $select_grade_id)->first()->ACADEMIC_YEAR_ID;                                                      
        }
        else{
            $select_grade_id = Grade::select('id')->where('NAME', $y)->first()->id;
            $selected_student_in_ay = GradeStudent::select(DB::raw('MAX(ACADEMIC_YEAR_ID) AS id'))->limit(1)->first()->id;                      
        }
        
        $rata_kelas = SubjectReport::join('subject_records', 'subject_reports.SUBJECT_RECORD_ID', 'subject_records.id')
                                ->join('students', 'subject_records.STUDENTS_ID', 'students.id')
                                ->join('subjects', 'subject_reports.SUBJECTS_ID', 'subjects.id')
                                ->join('grades_students', 'students.id', 'grades_students.STUDENTS_ID')
                                ->select(DB::raw('ROUND(SUM(subject_reports.FINAL_SCORE)/COUNT(subject_records.STUDENTS_ID), 2) AS RATAKELAS'), 'subject_records.*')
                                ->where('subjects.DESCRIPTION', $mapel->DESCRIPTION)
                                ->where('grades_students.GRADES_ID', '=', $select_grade_id)
                                ->where('grades_students.ACADEMIC_YEAR_ID', $selected_student_in_ay)
                                ->groupBy('subject_records.ACADEMIC_YEAR_ID')
                                ->get();                                                                                                    
        
        $selected_student_ay = Student::select('ACADEMIC_YEAR_ID AS AY_ID')->where('id', $siswa->id) ->first()->AY_ID;                        

        $tahun_ajaran = AcademicYear::where('id', '>=', $selected_student_ay)->get(); 

        $detail_mapel_ku = SubjectReport::join('subject_records', 'subject_reports.SUBJECT_RECORD_ID', 'subject_records.id')
                                        ->join('subjects', 'subject_reports.SUBJECTS_ID', 'subjects.id')
                                        ->select('subjects.*', 'subject_records.*', 'subject_reports.*')
                                        ->where('subjects.DESCRIPTION', $mapel->DESCRIPTION)
                                        // ->where('subject_records.ACADEMIC_YEAR_ID', $academic_year_id)
                                        ->where('subject_records.STUDENTS_ID', $siswa->id)
                                        ->get();  
            
        $data_final_score = SubjectReport::join('subject_records', 'subject_reports.SUBJECT_RECORD_ID', 'subject_records.id')
                                        ->join('subjects', 'subject_reports.SUBJECTS_ID', 'subjects.id')
                                        ->select('subject_records.*', 'subject_reports.*')
                                        ->where('subjects.DESCRIPTION', $mapel->DESCRIPTION)                                        
                                        ->where('subject_records.STUDENTS_ID', $siswa->id)                                        
                                        ->get();   
                                      
        return view('teacher.teacher-get-student-detail-subject',
               compact('siswa', 'mapel', 'academic_year_id', 'kkm', 'detail_mapel_ku', 'tahun_ajaran', 
                        'data_final_score', 'rata_kelas'));
    }
}

