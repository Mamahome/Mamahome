
<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 14? "layouts.app":"layouts.amheader");
?>
@extends($ext)
@section('content')
<br><br>
<div style="background-color:white" class="container" >
<h2 ><center>WELCOME TO {{ Auth::user()->group_id == 14 ? ' HUMAN RESOURCES DASHBOARD' : 'ASSISTANT MANAGER OF SALES AND MARKETING' }}
<br>ZONE 1, BANGALORE'S DASHBOARD
<BR><BR>
    <SMALL>You must know your responsibilities and carry out your tasks responsibly.<br>
    We appreciate you services.
    </SMALL>
</center></h2></div>
@endsection