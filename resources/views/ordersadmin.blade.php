@extends('layouts.app')
@section('title','Orders')

@section('content')
<div class="col-md-12">
	<div class="panel panel-primary" style="overflow-x: scroll;">
		<div class="panel-heading text-center">
			<b style="color:white;font-size:1.4em">Orders</b>
			<a class="pull-right btn btn-sm btn-danger" href="{{URL::to('/')}}/home" id="btn1" style="color:white;"><b>Back</b></a>
		</div>
		<div id="myordertable" class="panel-body">
			<table class="table table-responsive table-striped" border="1">
				<thead>
					<tr>
					    <th>Project ID</th>
					    <th>Order Id</th>
					    <th>Generated By</th>
					    <!-- <th>Designation</th> -->
						<th>Required</th>
						<th>Quantity</th>
						<th>Status</th>
						<th>Requirement Date</th>
						<th>Payment Status</th>
						<th>Dispatch Status</th>
						<th>Delivery Status</th>
						<th>Print Invoice</th>
						<th>&nbsp;&nbsp;&nbsp; Confirm Order &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@foreach($view as $rec)
					<tr id="row-{{$rec->id}}">
						<td><a href="{{URL::to('/')}}/showProjectDetails?id={{$rec->project_id}}">{{$rec -> project_id}}</a></td>
						<td>{{ $rec->orderid }}</td>
						<td>{{$rec -> name }}</td>
						
						<td>
							{{$rec -> main_category}}<br>
							{{$rec -> sub_category}}<br>
							{{$rec -> brand}}<br>
						</td>
						<td>{{$rec->quantity}} {{$rec->measurement_unit}}</td>
						<td>{{$rec->status}}</td>
						<td>{{ date('d-m-Y',strtotime($rec -> requirement_date)) }}</td>
                        <td id="paymenttd-{{$rec->orderid}}">
                            {{ $rec->payment_status }}
                        </td>
                        <td>
                            @if($rec->dispatch_status)
                            {{$rec->dispatch_status}}
                            @elseif(!$rec->dispatch_status)
                            Not Yet Dispatched
                            @endif
                        </td>
						<td>
						    @if($rec -> delivery_status)
						    {{$rec -> delivery_status}}
						    @else
						    Not Delivered
						    @endif
						</td>
						<td>
						    <a href="{{URL::to('/')}}/{{$rec->orderid}}/printLPO" target="_blank" class="btn btn-sm btn-primary" >Print Invoice</a>
					    </td>
					    <td>
					    	@if($rec->status == "Enquiry Confirmed")
					    	<div class="btn-group">
						    	<button class="btn btn-sm btn-success pull-left" onclick="confirmOrder('{{ $rec->orderid }}')">Confirm</button>
						    	<button class="btn btn-sm btn-danger pull-right" onclick="cancelOrder('{{ $rec->orderid }}')">Cancel</button>
					    	</div>
					    	@else
					    	{{ $rec->status }}
					    	@endif
					    </td>
					</tr>
					@endforeach
				</tbody>	
			</table>
			<br>
			<center>{{$view->links()}}</center>	
		</div>
	</div>
</div>
<script type="text/javascript">
	
	function pay(arg)
	{
		var e = document.getElementById("selectPayment-"+arg);
		var strUser = e.options[e.selectedIndex].value;
		var ans = confirm('Are You Sure ? Note: Changes Made Once CANNOT Be Undone');
		if(ans){
			$.ajax({
				type: 'GET',
				url: "{{URL::to('/')}}/updateampay",
				data: {payment: strUser, id: arg},
				async: false,
				success: function(response){
					console.log(response);
				}
			});
		}
		return false;
	}

	function updateDispatch(arg)
	{
		var e = document.getElementById("selectdispatch-"+arg);
		var strUser = e.options[e.selectedIndex].value;
		var ans = confirm('Are You Sure ? Note: Changes Made Once CANNOT Be Undone');
		if(ans){
    		$.ajax({
    			type: 'GET',
    			url: "{{URL::to('/')}}/updateamdispatch",
    			data: {dispatch: strUser, id: arg},
    			async: false,
    			success: function(response){
    				console.log(response);	
    			}
    		});
		}
		return false;	
	}
	
	function confirmOrder(arg)
	{
	    var ans = confirm('Are You Sure To Confirm This Order ?');
	    if(ans)
	    {
    	    $.ajax({
    	       type:'GET',
    	       url: "{{URL::to('/')}}/confirmOrder",
    	       data: {id : arg},
    	       async: false,
    	       success: function(response)
    	       {
    	           console.log(response);
    	           $("#myordertable").load(location.href + " #myordertable>*", "");
    	       }
    	    });
	    }    
	}
	
	function cancelOrder(arg)
	{
	    var ans = confirm('Are You Sure To Cancel This Order ?');
	    if(ans)
	    {
    	    $.ajax({
    	       type:'GET',
    	       url: "{{URL::to('/')}}/cancelOrder",
    	       data: {id : arg},
    	       async: false,
    	       success: function(response)
    	       {
    	           console.log(response);
    	           $("#myordertable").load(location.href + " #myordertable>*", "");
    	       }
    	    });
	    }
	 }

</script>
@endsection
