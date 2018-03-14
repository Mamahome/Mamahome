@extends('layouts.sales')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <a href="{{ URL::to('/') }}/projectsUpdate" id="updates" class="form-control btn btn-primary {{ count($assignment) == 0? 'disabled': ''}}">Project Updates</a><br><br>
            <a href="{{ URL::to('/') }}/followupproject" class="form-control btn btn-primary {{ count($assignment) == 0? 'disabled': ''}}">Follow up projects</a><br><br>
            <a href="{{ URL::to('/') }}/kra" class="form-control btn btn-primary">KRA</a><br><br>
            <table class="table table-responsive">
                <tr><td>You have attended {{ $calls }} calls so far.</td></tr>
                <tr><td>Marked {{ $followups }} projects for followups.</td></tr>
                <tr><td>{{ $ordersinitiate }} orders initiated.</td></tr>
                <tr><td>{{ $ordersConfirmed }} orders confirmed.</td></tr>
                <tr><td>Genuine Projects : {{ $genuineProjects }}</td></tr>
                <tr><td>Fake Projects: {{ $fakeProjects }}</td></tr>
                <tr><td>Total : {{ $genuineProjects + $fakeProjects }}</td></tr>
            </table>
        </div>
    </div>
</div>
@endsection