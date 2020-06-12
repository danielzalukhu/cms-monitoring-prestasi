<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// DASHBOARD
Route::get('/', 'DashboardController@index')->middleware('auth')->name('dashboard.index');

// AJAX
Route::get('vr/violation-item-by-category', 'AjaxController@showViolationItemByCategory')->middleware('auth')->name('violationrecord.violationItem');
Route::get('vr/show-violation-record', 'AjaxController@getViolationRecord')->middleware('auth')->name('violationrecord.showViolationRecord');
Route::get('vr/show-student-violation-detail', 'AjaxController@studentDetailViolation')->middleware('auth')->name('violationrecord.studentDetailViolation');

Route::get('ar/achievement-item-by-grade', 'AjaxController@showAchievementItemByGrade')->middleware('auth')->name('achievementrecord.achievementItem');
Route::get('ar/show-achievement-record', 'AjaxController@getAchievementRecord')->middleware('auth')->name('achievementrecord.showAchievementRecord');
Route::get('ar/show-student-achievement-detail', 'AjaxController@studentDetailAchievement')->middleware('auth')->name('achievementrecord.studentDetailAchievement');

Route::get('absent/show-absent-each-grade', 'AjaxController@absentEachGrade')->middleware('auth')->name('absent.absentEachGrade');

// STUDENT
Route::get('achievement/chart', 'StudentController@returnDataAchievementChart')->middleware('auth')->name('student.achievementChart');
Route::get('violation/chart', 'StudentController@returnDataViolationChart')->middleware('auth')->name('student.violationChart');
Route::get('absent/chart', 'StudentController@returnDataAbsentChart')->middleware('auth')->name('student.absentChart');

Route::get('student/{id}/profile', 'StudentController@profile')->middleware('auth')->name('student.profile');
Route::get('student/detailabsent' , 'StudentController@showDetailAbsent')->middleware('auth')->name('student.detailAbsent');
Route::get('student/mapelku', 'StudentController@mapelku')->middleware('auth')->name('student.mapelku');
Route::get('student/mapelguru', 'StudentController@mapelguru')->middleware('auth')->name('student.mapelguru');
Route::get('student/subject/{id}/{kodekelas}', 'StudentController@teacherGetStudentSubject')->middleware('auth')->name('subject.studentSubject');
Route::get('student/subject/detail/{siswaid}/{mapelid}', 'StudentController@getStudentSubjectDetail')->middleware('auth')->name('subject.studentDetailSubject');
Route::resource('student', 'StudentController')->middleware('auth');

// IMPORT
Route::get('subject/assesment', 'SubjectController@assesmentImport')->middleware('auth')->name('subject.assesment');
Route::get('subject/assesment/{id}/edit', 'SubjectController@editAssesment')->middleware('auth')->name('subject.editAssesment');
Route::post('subject/assesment/{id}/update', 'SubjectController@updateAssesment')->middleware('auth')->name('subject.updateAssesment');
Route::get('subject/assesment/{id}/delete', 'SubjectController@destroyAssesment')->middleware('auth')->name('subject.destroyAssesment');
Route::get('subject/assesment/status', 'SubjectController@setStatus')->middleware('auth')->name('subject.setStatus');
Route::get('subject/detail/{id}', 'SubjectController@subjectDetail')->middleware('auth')->name('subject.detail');
Route::post('student', 'ImportController@importStudent')->middleware('auth')->name('student.importStudent');
Route::post('subject/assesment/import', 'ImportController@importAssesment')->middleware('auth')->name('subject.importAssesment');

// ACHIEVEMENT & ACHIEVEMENT RECORD
Route::get('ar/academicyear', 'AchievementRecordController@ajaxChangeAchievementRecord')->middleware('auth')->name('achievement.academicYearAjax');
Route::resource('achievement', 'AchievementController')->middleware('auth');
Route::resource('achievementrecord', 'AchievementRecordController')->middleware('auth');

// VIOLATION & VIOLATION RECORD
Route::get('vr/academicyear', 'ViolationRecordController@ajaxChangeViolationRecord')->middleware('auth')->name('violationrecord.academicYearAjax');
Route::resource('violation', 'ViolationController')->middleware('auth');
Route::resource('violationrecord', 'ViolationRecordController')->middleware('auth');

// ABSENT
Route::get('absent/academicyear', 'AbsentController@ajaxChangeAbsentRecord')->middleware('auth')->name('absent.academicYearAjax');
Route::resource('absent', 'AbsentController')->middleware('auth');

// SUBJECT (INCOMPLETE, ASSESMENT)
Route::get('incomplete', 'SubjectController@incomplete')->middleware('auth')->name('subject.incomplete');
Route::get('incompleteku', 'SubjectController@incompleteku')->middleware('auth')->name('subject.incompleteku');
Route::get('incomplete/create', 'SubjectController@createIncomplete')->middleware('auth')->name('subject.createIncomplete');
Route::post('incomplete', 'SubjectController@storeIncomplete')->name('subject.storeIncomplete');
Route::get('incomplete/{id}/edit', 'SubjectController@editIncomplete')->name('subject.editIncomplete');
Route::post('incomplete/{id}/update', 'SubjectController@updateIncomplete')->name('subject.updateIncomplete');
Route::get('incomplete/{id}/delete', 'SubjectController@destroyIncomplete')->name('subject.destroyIncomplete');
Route::resource('subject', 'SubjectController')->middleware('auth');

// EXTRACURRICULER 
Route::get('extracurricular/assesment', 'ExtracurricularController@ekskulAssesment')->middleware('auth')->name('extracurricular.assesment');
Route::post('extracurricular/assesment/input', 'ExtracurricularController@storeAssesment')->middleware('auth')->name('extracurricular.storeAssesment');
Route::get('extracurricular/assesment/edit/{id}', 'ExtracurricularController@editAssesment')->middleware('auth')->name('extracurricular.editAssesment');
Route::put('extracurricular/assesment/update/{id}', 'ExtracurricularController@updateAssesment')->middleware('auth')->name('extracurricular.updateAssesment');
Route::get('extracurricular/delete/{id}', 'ExtracurricularController@destroyAssesment')->middleware('auth')->name('extracurricular.destroyAssesment');
Route::get('extracurricular/ekskul', 'ExtracurricularController@showEkskul')->middleware('auth')->name('extracurricular.ekskul');
Route::get('extracurricular/ekskulku', 'ExtracurricularController@showEkskulKu')->middleware('auth')->name('extracurricular.ekskulKu');
Route::resource('extracurricular', 'ExtracurricularController')->middleware('auth');

// AUTH
Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');
