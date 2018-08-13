@extends('layouts.app')
@section('title','Orders')

@section('content')
<div class="col-md-12">
    <div class="panel panel-primary" style="overflow-x: scroll;">
        <div class="panel-heading text-center">
            <b style="color:white;font-size:1.4em">Orders</b>
            <a class="pull-left btn btn-sm btn-danger" href="{{URL::to('/')}}/home" id="btn1" style="color:white;"><b>Back</b></a>
        </div>
        <div id="myordertable" class="panel-body">
        <form action="orders" method="get">
                <div class="input-group col-md-3 pull-right">
                    <input type="text" class="form-control pull-left" placeholder="Enter project id" name="projectId" id="projectId">
                    
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                </div>
            </form>
                
            <br><br>
            <table class="table table-responsive table-striped" border="1">
                <thead>
                    <tr>
                        <th>Project ID</th>
                        <th>Order Id</th>
                        <th>Generated By</th>
                        <th>Required</th>
                        <th>Quantity</th>
                        <th>Logistics Coordinator</th>
                        <th>Requirement Date</th>
                        <th>Payment Status</th>
                        <th>Payment Mode</th>
                        <th>Dispatch Status</th>
                        <th>Delivery Status</th>
                        <!-- <th>Print Invoice</th> -->
                        <th> Confirm Order </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($view as $rec)
                    <tr id="row-{{$rec->id}}">
                        <td><a href="{{URL::to('/')}}/showThisProject?id={{$rec->project_id}}">{{$rec -> project_id}}
                        </a>
                        </td>
                     
                        <td>{{ $rec->orderid }}  </td>
                        <td>{{$rec->name }}</td>
                        <td>
                            {{$rec -> main_category}}<br>
                            {{$rec -> sub_category}}<br>
                            {{$rec -> brand}}<br>
                        </td>
                        <td>{{$rec->quantity}} {{$rec->measurement_unit}}</td>
                        <td>
                            @if($rec->delivery_status == "Delivered")
                                {{ $rec->name }}
                            @else
                            <form method="POST" action="{{ URL::to('/') }}/addDeliveryBoy">
                            {{ csrf_field() }}
                            <input type="hidden" name="orderId" value="{{ $rec->orderid }}">

                    @if($rec->payment_mode != NULL && $rec->payment_mode != "Check" && $rec->order_status == "Order Confirmed")
                              @if($rec->delivery_boy != NULL)
                                 @foreach($users as $user)
                                   @if($rec->delivery_boy == $user->id)
                                        {{ $user->name }}
                                 @endif
                             @endforeach
                                @else
                                <select onchange="this.form.submit()" name="delivery" id="delivery" class="form-control">
                                        <option value="">--Select--</option>
                                    @foreach($users as $user)
                                        <option {{ $rec->delivery_boy == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @endif
                            </form>
                            @endif
                        </td>
                        <td>{{ date('d-m-Y',strtotime($rec -> requirement_date)) }}</td>
                        <td class="text-center" id="paymenttd-{{$rec->orderid}}">
                            @if($rec->payment_status == "Payment Received")
                                ₹ {{ $rec->total }} <br>Received<br>
                                <a data-toggle="modal" data-target="#signatureImage{{ $rec->orderid }}" href="#">View Signature</a>
                                <!-- Modal -->
                                <div id="signatureImage{{ $rec->orderid }}" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Signature</h4>
                                    </div>
                                    <div class="modal-body">
                                        <img src="{{ URL::to('/') }}/signatures/{{ $rec->signature }}" alt="Sign" title="Sign" class="img img-responsive">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    </div>

                                </div>
                                </div>
                            @else
                                {{ $rec->ostatus }}
                            @endif
                        </td>

                        <td>
                       
                            @if($rec->payment_mode == "RTGS" || $rec->payment_mode == "CASH")
                                {{ $rec->payment_mode }} 
                            @elseif($rec->payment_mode != "Check" &&  $rec->payment_mode != "Cheq Clear")

                            <form method="POST" id="payment" action="{{ URL::to('/') }}/paymentmode">
                            {{ csrf_field() }}
                            <input type="hidden" name="orderId" value="{{ $rec->orderid }}">
                                <select name="payment" id="pay" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="RTGS" id="rtgs" onclick="rtgs()"> RTGS(online) </option>
                                        <option value="CASH" id="cash" onclick="cash()">Cash</option>
                                        <option value="check" data-toggle="modal" data-target="#myModal{{ $rec->orderid }}" >Cheque</option>
                                </select>
                            </form>
                             
                        @elseif($rec->payment_mode == "Cheq Clear")
                            <button class="btn btn-success btn-sm disabled">Cheque Cleared</button>
                        @else
                            <button class="btn btn-success btn-sm disabled">Cheq Processing</button>
                        @endif

                            
                             
                             <!-- The Modal -->
  <div class="modal" id="myModal{{ $rec->orderid }}">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header" style="width:100%;padding:2px;background-color:green;">
        <center>  <h4 class="modal-title" style="color: white;">Cheque Details</h4></center>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <form action="{{ URL::to('/') }}/check?id={{ $rec->orderid }}" method="POST" enctype="multipart/form-data"> 
        {{ csrf_field() }}
        <table class="table">
        <input type="hidden" name="project_id" value="{{$rec->project_id}}">
        <input type="hidden" name="orderId" value="{{ $rec->orderid }}">
       <tr>
                <th>Cheque Number</th>
                <td>:</td>
                <td><input type="text" name="checkno" class="form-control" required></td>
            </tr>
            <tr>
                <th>Cheque Amount</th>
                <td>:</td>
                <td><input type="text" name="amount" class="form-control"  required></td>
            </tr>
            <tr>
                <th>Cheque Date</th>
                <td>:</td>
                <td><input type="date" name="date" class="form-control" required></td>
            </tr>
            <tr>
                <th>Bank Name</th>
                <td>:</td>
                <td><input type="text" name="bank" class="form-control" required></td>
            </tr>
            <tr>
                <th>Cheque Picture</th>
                <td>:</td>
                <td><input type="file" name="image" class="form-control" required></td>
            </tr>
        </table> 
        <center><button type="submit" value="submit" class="btn btn-primary ">Submit</button> 
        </form>    
         </div>
        
        <!-- Modal footer -->
        <div class="modal-footer" style="padding:2px">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
</div>

                        </td>







                        <td>
                            @if($rec->dispatch_status)
                            {{$rec->dispatch_status}}
                            @elseif(!$rec->dispatch_status)
                            Not Yet Dispatched
                            @endif
                        </td>
                        <td>
                            @if($rec -> order_delivery_status == "Delivered")
                            <a data-toggle="modal" data-target="#deliveryImage{{ $rec->orderid }}" href="#">Delivered</a>
                            <!-- Modal -->
                                <div id="deliveryImage{{ $rec->orderid }}" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Delivery Details</h4>
                                    </div>
                                    <div class="modal-body" style="overflow-y:scroll; max-height:400px; height:400px;">
                                        <br>
                                        <img src="{{ URL::to('/') }}/public/delivery_details/{{ $rec->vehicle_no }}" alt="Vehicle No." title="Vehicle No." class="img img-responsive img-thumbnail">
                                        <center><label for="above">Vehicle No.</label></center>
                                        <br>
                                        <img src="{{ URL::to('/') }}/public/delivery_details/{{ $rec->location_picture }}" alt="Location Picture" title="Location Picture" class="img img-responsive img-thumbnail">
                                        <center><label for="above">Location Picture</label></center>
                                        <br>
                                        <img src="{{ URL::to('/') }}/public/delivery_details/{{ $rec->quality_of_material }}" alt="Quality Of Material" title="Quality Of Material" class="img img-responsive img-thumbnail">
                                        <center><label for="above">Quality Of Material</label></center>
                                        <br>
                                        <video class="{{ $rec->delivery_video != null ? 'img img-responsive img-thumbnail' : 'hidden' }}" width="320" height="240" controls>
                                            <source src="{{ URL::to('/') }}/public/delivery_details/{{ $rec->delivery_video }}" type="video/mp4">
                                            <source src="{{ URL::to('/') }}/public/delivery_details/{{ $rec->delivery_video }}" type="video/ogg">
                                            Your browser does not support the video tag.
                                        </video>
                                        <center><label class="{{ $rec->delivery_video == null ? 'hidden': '' }}"  for="above">Delivery Video</label></div></center>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    </div>

                                </div>
                                </div>
                            @else
                            {{$rec -> order_delivery_status}}
                            @endif
                        </td>
                        <!-- <td>
                            <a href="{{URL::to('/')}}/{{$rec->orderid}}/printLPO" target="_blank" class="btn btn-sm btn-primary" >Print Invoice</a>
                        </td> -->
                        <td>
                            @if($rec->order_status == "Enquiry Confirmed")
                            <div class="btn-group">
                                <a class="btn btn-xs btn-success" href="{{URL::to('/')}}/confirmOrder?id={{ $rec->orderid }}">Confirm</a>
                                <button class="btn btn-xs btn-danger pull-right" onclick="cancelOrder('{{ $rec->orderid }}')">Cancel</button>
                            </div>
                            @else
                           {{ $rec->order_status }}
                            @endif
                          </td>
                       <td>
                                <a href="{{ URL::to('/') }}/editenq?reqId={{ $rec->id }}" class="btn btn-xs btn-primary">Edit</a>
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
<!-- Form -->
<form method="POST" id="payment" action="{{ URL::to('/') }}/paymentmode">
{{ csrf_field() }}

    <select name="payment" id="pay" class="form-control">
            <option value="">--Select--</option>
            <option value="RTGS" id="rtgs" onclick="rtgs()"> RTGS(online) </option>
            <option value="CASH" id="cash" onclick="cash()">Cash</option>
            <option value="check" data-toggle="modal" data-target="#myModal{{ $rec->orderid }}" >Cheque</option>
    </select>
</form>

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
                        alert(response);
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
    function rtgs(){
        
        document.getElementById('payment').form.submit();
    }
    function cash(){
        
        document.getElementById('cash').form.submit();
    }
</script>
@endsection
