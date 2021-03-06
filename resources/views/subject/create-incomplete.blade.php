@extends('layout.master')

@section('header')
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/select2/dist/css/select2.min.css')}}">
@stop

@section('content')
    <div class="main">
        <div class="main-content">
            <div class="container-fluid">
                @if ($sukses = Session::get('sukses'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $sukses }}</strong>
                    </div>
                @elseif($error = Session::get('error'))    
                    <div class="alert alert-warning alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $error }}</strong>
                    </div> 
                @endif
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel-heading">
                            <h3 class="box-title">BUAT LAPORAN KETIDAKTUNTASAN SISWA</h3>            
                        </div>
                        <div class="box">
                            <div class="box-body">
                            <form action="{{ route('subject.storeIncomplete') }}" method="post"  enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="form-group{{ $errors->has('vr_date') ? 'has-error' : '' }} ">
                                    <label>Tanggal</label>
                                    <input name="vr_date" type="date" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{old('vr_date')}}">            
                                    @if($errors->has('vr_date'))
                                        <span class="help-block" style="color: red">*Tanggal wajib diisi</span>
                                    @endif
                                </div>

                                @if(Auth::guard('web')->user()->staff->ROLE === "ADMIN")
                                <div class="form-group">
                                    <label>Kelas</label>                
                                    <select id="selected_grade" class="form-control select2" style="width: 100%;">
                                        @foreach($kelas as $k)
                                            <option value='{{ $k->id }}'>{{ $k->NAME }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('a_type'))
                                        <span class="help-block"></span>
                                    @endif
                                </div>
                                @endif

                                <div class="form-group{{ $errors->has('vr_student_name') ? 'has-error' : '' }} ">
                                    <label>Nama Siswa</label>
                                    <select name="vr_student_name" class="form-control select2" style="width: 100%;">
                                        @foreach($siswa as $s)
                                            <option value='{{ $s->student->id }}'>{{ $s->student->FNAME }}{{" "}}{{$s->student->LNAME}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('a_type'))
                                        <span class="help-block">{{$errors->first('vr_student_name')}}</span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('vr_violation_name') ? 'has-error' : '' }} ">
                                    <label>Nama Pelanggaran</label>
                                    <select name="vr_violation_name" class="form-control" id="inputGroupSelect01">
                                        @foreach($pelanggaran as $p)
                                            <option value="{{ $p->id }}" @if($p->NAME == 'TTS') selected @else disabled @endif>{{$p->NAME}}{{" - "}}{{ $p->DESCRIPTION }}</option>                                           
                                        @endforeach
                                    </select>
                                    @if($errors->has('vr_violation_name'))
                                        <span class="help-block">{{$errors->first('vr_violation_name')}}</span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('vr_description') ? 'has-error' : '' }} ">
                                    <label>Deskripsi</label>
                                    <textarea name="vr_description" class="form-control" id="exampleFormControlTextarea1" rows="3">{{old('vr_desc')}}</textarea>
                                    @if($errors->has('vr_description'))
                                        <span class="help-block" style="color: red">*Deskripsi wajib diisi</span>
                                    @endif
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
<!-- Select2 -->
<script src="{{asset('adminlte/bower_components/select2/dist/js/select2.full.min.js')}}"></script>

<script>
    $(function () {    
        $('.select2').select2()
    })

    $('#selected_grade').change(function(){
        var gradeId = $(this).val()   
        var route =  "{{ route('subject.createIncomplete') }}"  
        window.location = route+"?gradeId="+gradeId;        
    })
</script>
@stop
