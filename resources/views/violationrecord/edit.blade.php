@extends('layout.master')

@section('content')
    <div class="main">
        <div class="main-content">
            <div class="container-fluid">
                @if(session('sukses'))
                    <div class="alert alert-success" role="alert">
                        {{ session('sukses') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel-heading">
                            <h3 class="box-title">EDIT STUDENT VIOLATION RECORD</h3>            
                        </div>
                        <div class="box">
                            <div class="box-body">
                                <form action="{{ route ('violationrecord.update', $catatan_pelanggaran->id) }}" method="post" enctype="multipart/form-data">
                                {{ method_field("PUT") }}
                                {{ csrf_field() }}

                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    
                                    <div class="form-group{{ $errors->has('vr_date') ? 'has-error' : '' }} ">
                                        <label>Date</label>
                                        <input name="vr_date" type="date" class="form-control"  value="{{$catatan_pelanggaran->DATE}}">            
                                        @if($errors->has('vr_date'))
                                            <span class="help-block">{{$errors->first('vr_date')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('vr_student_name') ? 'has-error' : '' }} ">
                                        <label>Student Name</label>
                                        <select name="vr_student_name" class="form-control">
                                            @foreach($siswa as $s)
                                                <option value="{{ $s->id }}" @if($catatan_pelanggaran->STUDENTS_ID == $s->id) selected @endif>{{ $s->FNAME }}{{" "}}{{$s->LNAME}}</option>                                                    
                                            @endforeach                                                
                                        </select>
                                        @if($errors->has('vr_student_name'))
                                            <span class="help-block">{{$errors->first('vr_student_name')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('vr_violation_name') ? 'has-error' : '' }} ">
                                        <label>Violation Name</label>
                                        <select name="vr_violation_name" class="form-control">
                                            @foreach($pelanggaran as $p)
                                                <option value="{{ $p->id }}" @if($catatan_pelanggaran->VIOLATIONS_ID == $p->id) selected @endif>{{ $p->NAME }}{{" - "}}{{ $p->DESCRIPTION }}</option>                                                    
                                            @endforeach                                                
                                        </select>
                                        @if($errors->has('vr_violation_name'))
                                            <span class="help-block">{{$errors->first('vr_violation_name')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('vr_desc') ? 'has-error' : '' }} ">
                                        <label>Description</label>            
                                        <textarea name="vr_desc" class="form-control" rows="3">{{ $catatan_pelanggaran->DESCRIPTION }}</textarea>
                                        @if($errors->has('vr_desc'))
                                            <span class="help-block">{{$errors->first('vr_desc')}}</span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('vr_punishment') ? 'has-error' : '' }} ">
                                        <label>Punishment</label>
                                        <textarea name="vr_punishment" class="form-control" rows="3">{{ $catatan_pelanggaran->PUNISHMENT }}</textarea>
                                        @if($errors->has('vr_punishment'))
                                            <span class="help-block">{{$errors->first('vr_punishment')}}</span>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-warning">Update</button>
                                </form>                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
