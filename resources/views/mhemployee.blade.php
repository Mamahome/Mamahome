@extends('layouts.app')
@section('content')
<style type="text/css">
  .dot {
    height: 9px;
    width: 9px;
    background-color:green;
    border-radius: 50%;
    display: inline-block;
}
</style>
<div class="container">
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
            <button class="btn btn-default form-control" data-toggle="modal" data-target="#addEmployee" style="background-color:green;color:white;font-weight:bold">Add Employee </button>
            <br><br>
            <div class="panel panel-default" style="border-color:#f4811f">
                <div class="panel-heading" style="background-color:#f4811f"><b style="font-size:1.3em;color:white">Departments</b></div>
                <div class="panel-body">
                    @foreach($departments as $department)
                        <?php 
                            $content = explode(" ",$department->dept_name);
                            $con = implode("",$content);
                        ?>
                        <a id="{{ $con }}" class="list-group-item" href="#">{{ $department->dept_name }} </a>
                    @endforeach
                    <a id="FormerEmployees" class="list-group-item" href="#">Former Employees </a>
                </div>
            </div>
        </div>
        <div class="col-md-10" id="disp">
             <br><br><br><br>
                           <img src="http://mamahome360.com/public/android-icon-36x36.png">
                           MAMA HOME PVT LTD&nbsp;&nbsp;
                           Total employees &nbsp;&nbsp;<span class="dot" style=" height: 9px;
    width: 9px;
    background-color:green;
    border-radius: 50%;
    display: inline-block;"></span> 50
                
                      <br>
    <br>
    <div>
    <table  class="table table-hover table-responsive" style="border: 2px solid gray;">
      <thead>
        <th style="border: 1px solid gray;">Department</th>
        <th style="border: 1px solid gray;">Number of Employees</th>
        <th style="border: 1px solid gray;">Average Age</th>
      </thead>
      <tbody>
        <tr> 
                         <td style="border: 1px solid gray;">Operation</td>
                        <td style="border: 1px solid gray;">27</td>
                        <td style="border: 1px solid gray;">21</td>
                       
        </tr>
     
                        <tr> 
                                 <td style="border: 1px solid gray;">Sales</td>
                                  <td style="border: 1px solid gray;">10</td>
                                  <td style="border: 1px solid gray;">22</td>
                        </tr>
                        <tr> 
                            <td style="border: 1px solid gray;">Marketing</td>
                            <td style="border: 1px solid gray;">1</td>
                            <td style="border: 1px solid gray;">22</td>
                           
                        </tr>
                        <tr>
                           <td style="border: 1px solid gray;">IT</td>
                            <td style="border: 1px solid gray;">9</td>
                            <td style="border: 1px solid gray;">24</td>
                           
                        </tr>
                        <tr>
                            <td style="border: 1px solid gray;">Finance</td>
                            <td style="border: 1px solid gray;">1</td>
                            <td style="border: 1px solid gray;">23</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid gray;">Research and Development</td>
                            <td style="border: 1px solid gray;">1</td>
                            <td style="border: 1px solid gray;">27</td>
                        </tr>
                        <tr>
                             <td style="border: 1px solid gray;">Human Resource</td>
                            <td style="border: 1px solid gray;">1</td>
                            <td style="border: 1px solid gray;">23</td>
                        <tr>
                         <tr>
                            <td style="border: 1px solid gray;"></td>
                            <td style="border: 1px solid gray;"><b>50</b></td>
                            <td style="border: 1px solid gray;"><b>23(Avg)</b></td>
                        </tr>
                      </tbody>
                     </table>
                    </div>
                     <div>
    <table  class="table table-hover table-responsive" style="border: 2px solid gray;">
      <thead>
        <th style="border: 1px solid gray;">Qualification</th>
        <th style="border: 1px solid gray;">Count</th>
      </thead>
      <tbody>
        <tr> 
                        <td style="border: 1px solid gray;">MBA & MCA</td>
                        <td style="border: 1px solid gray;">6</td>
                       
        </tr>
         <tr> 
                        <td style="border: 1px solid gray;">Engineering</td>
                        <td style="border: 1px solid gray;">37</td>
                       
        </tr>
        <tr> 
                        <td style="border: 1px solid gray;">Degree</td>
                        <td style="border: 1px solid gray;">7</td>
                       
        </tr>
      </tbody>
    </table>
  </div>
    
        </div>
    </div>
</div>

                 
 
<!--Modal-->
<form method="post" action="{{ URL::to('/') }}/amaddEmployee">
    {{ csrf_field() }}
  <div class="modal fade" id="addEmployee" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header" style="background-color:#f4811f;color:white;fon-weight:bold">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Employee</h4>
        </div>
        <div class="modal-body">
          <table class="table table-hover">
              <tbody>
                  <tr>
                    <td><label>Emp Id</td>
                    <td> <input required type="text" placeholder="Employee Id" class="form-control" name="employeeId"></td>
                  </tr>
                  <tr>
                    <td><label>Name</label></td>
                    <td><input required type="text" placeholder="Name" class="form-control" name="name"></td>
                  </tr>
                  <tr>
                    <td><label>User-Id Of MMT</label></td>
                    <td><input required type="text" placeholder="User-id of MMT" class="form-control" name="email"></td>
                  </tr>
                  <tr>
                    <td><label>Department</label></td>
                      <td><select required class="form-control" name="dept">
                      <option value="">--Select--</option>
                      @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->dept_name }}</option>
                      @endforeach
                  </select></td>
                  </tr>
                  <tr>
                    <td><label>Designation</label></td>
                    <td> <select required class="form-control" name="designation">
                      <option value="">--Select--</option>
                      @foreach($groups as $designation)
                        <option value="{{ $designation->id }}">{{ $designation->group_name }}</option>
                      @endforeach
                  </select></td>
                  </tr> 
                </tbody>
              </table>
            </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success">Add</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

</form>
<!-- <div class="col-md-10">
        <div class="panel panel-default">
        <div class="panel-heading" style="background-color: green;color: white;padding-bottom: 20px;">Edit Asset Details
        <a class="pull-right btn btn-sm btn-danger" href="{{url()->previous()}}">Back</a>
        </div>
        <div class="panel-body">
             @if (session('Success'))
                        <div class="alert alert-success">
                            {{ session('Success') }}
                        </div>
               @endif
        </div>
    </div>
</div> -->

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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
@foreach($departments as $department)
<?php 
    $content = explode(" ",$department->dept_name);
    $con = implode("",$content);
?>
<script type="text/javascript">
$(document).ready(function () {
    $("#{{ $con }}").on('click',function(){
        $(document.body).css({'cursor' : 'wait'});
        $("#disp").load("{{ URL::to('/') }}/viewmhemployee?count={{$depts[$department->dept_name]}}&&dept="+encodeURIComponent("{{ $department->dept_name }}"), function(responseTxt, statusTxt, xhr){
            if(statusTxt == "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        });
        $(document.body).css({'cursor' : 'default'});
    });
});
</script>
<script type="text/javascript">
$(document).ready(function () {
    $("#FormerEmployees").on('click',function(){
        $(document.body).css({'cursor' : 'wait'});
        $("#disp").load("{{ URL::to('/') }}/viewmhemployee?dept=FormerEmployees&&count={{$depts["FormerEmployees"]}}", function(responseTxt, statusTxt, xhr){
            if(statusTxt == "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        });
        $(document.body).css({'cursor' : 'default'});
    });
});
</script>

@endforeach

@endsection
