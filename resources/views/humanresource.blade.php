@extends('layouts.amheader')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
             @if(session('Added'))
                <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ session('Added') }}
                </div>
            @endif
            @if(session('NotAdded'))
               <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                   {{ session('NotAdded') }}
                </div>
            @endif
            <button class="btn btn-default form-control" data-toggle="modal" data-target="#addEmployee" style="background-color:green;color:white;font-weight:bold">Add Employee</button>
            <br><br>
           
            <div class="panel panel-default" style="border-color:#f4811f">
                <div class="panel-heading" style="background-color:#f4811f">Departments</div>
                <div class="panel-body">
                    @foreach($departments as $department)
                        <a id="{{ $department->dept_name }}" class="list-group-item" href="#">{{ $department->dept_name }} ({{ $depts[$department->dept_name] }})</a>
                    @endforeach
                        <a id="Formeremployee" class="list-group-item" href="#">Former Employees ({{ $depts["FormerEmployees"] }})</a>
                </div>
            </div>
        </div>
        <div class="col-md-10" id="disp">

        </div>
    </div>
</div>
<form method="post" name="form1" action="{{ URL::to('/') }}/amaddEmployee">
    {{ csrf_field() }}
  <div class="modal fade" id="addEmployee" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header" style="background-color:#f4811f;color:white;fon-weight:bold">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Employee</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                  <input required type="text" placeholder="Employee Id" class="form-control" name="employeeId"><br>
                  <input required type="text" placeholder="User-ID Of MMT" class="form-control" name="email"><br>
                </div>
                <div class="col-md-6">
                  <input required type="text" placeholder="Name" class="form-control" name="name"><br>
                  <input required type="text" placeholder="Personal Contact No." class="form-control" name="phNo"><br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  Department:<br>
                  <select required class="form-control" name="dept">
                      <option value="">--Select--</option>
                      @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->dept_name }}</option>
                      @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  Designation:<br>
                  <select required class="form-control" name="designation">
                      <option value="">--Select--</option>
                      @foreach($groups as $designation)
                        <option value="{{ $designation->id }}">{{ $designation->group_name }}</option>
                      @endforeach
                  </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" onclick="phonenumber(document.form1.phNo)">
            Add </button>
          <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
        </div>
      </div>
    </div>
  </div>

</form>

<div class='b'></div>
<div class='bb'></div>
<div class='message'>
  <div class='check'>
    &#10004;
  </div>
  <p>
    Success
  </p>
  <p>
    @if(session('Success'))
    {{ session('Success') }}
    @endif
  </p>
  <button id='ok'>
    OK
  </button>
</div>

<script src="phoneno-all-numeric-validation.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
@foreach($departments as $department)
<script type="text/javascript">
$(document).ready(function () {
    $("#{{ $department->dept_name }}").on('click',function(){
        $(document.body).css({'cursor' : 'wait'});
        $("#disp").load("{{ URL::to('/') }}/humanresources/{{ $department->dept_name }}?page=hr", function(responseTxt, statusTxt, xhr){
            if(statusTxt == "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        });
        $(document.body).css({'cursor' : 'default'});
    });
});
$(document).ready(function () {
    $("#Formeremployee").on('click',function(){
        $(document.body).css({'cursor' : 'wait'});
        $("#disp").load("{{ URL::to('/') }}/humanresources/Formeremployee?page=hr", function(responseTxt, statusTxt, xhr){
            if(statusTxt == "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        });
        $(document.body).css({'cursor' : 'default'});
    });
});

function phonenumber(inputtxt)
{
  var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
  if(inputtxt.value.match(phoneno))
     {
     return true;      
   }
   else
     {
     alert("Not a valid Phone Number");
     return false;
     }
}

</script>
@endforeach
@endsection
