<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Order;
use App\SiteAddress;
use App\ProcurementDetails;
use App\PaymentDetails;
use App\Message;
use App\User;
use App\Tlwards;
use App\MamahomePrice;
use App\Supplierdetails;
use App\Country;
use App\Zone;
use App\ManufacturerDetail;
use App\Manufacturer;
use App\Mprocurement_Details;
use App\Requirement;
use App\Quotation;
use App\Gst;
use App\Category;
use DB;
use Auth;
use PDF;
date_default_timezone_set("Asia/Kolkata");
class FinanceDashboard extends Controller
{
    public function financeIndex()
    {
        return view('finance.index');
    }
    public function getFinanceDashboard()
    {
        if(Auth::user()->group_id == 22){
            $tlward = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
            $ward = explode(",",$tlward);
            $orders = DB::table('orders')->where('status','Order Confirmed')->orderBy('updated_at','desc')
                      ->leftjoin('project_details','project_details.project_id','orders.project_id')
                       ->leftjoin('sub_wards','sub_wards.id','project_details.sub_ward_id')
                       ->leftJoin('wards','wards.id','sub_wards.ward_id')
                       ->whereIn('wards.id',$ward)
                       ->select('orders.*','project_details.sub_ward_id')
                       ->paginate('20');
                      
        }
        else{
            $orders = DB::table('orders')->where('status','Order Confirmed')->orderBy('updated_at','desc')->paginate('20');

        }
      
        $reqs = Requirement::all();
        $payments = PaymentDetails::get();
        $data = MamahomePrice::distinct()->select('mamahome_prices.order_id','mamahome_prices.id')->pluck('mamahome_prices.id','mamahome_prices.order_id');
        $mamaprices = MamahomePrice::whereIn('id',$data)->get();
        $messages = Message::all();
        $counts = array();
        $users = User::all();
        foreach($orders as $order){
            $counts[$order->id] = Message::where('to_user',$order->id)->count();
        }
        return view('finance.financeOrders',['mamaprices'=>$mamaprices,'users'=>$users,'orders'=>$orders,'payments'=>$payments,'messages'=>$messages,'counts'=>$counts,'reqs'=>$reqs]);
    }
    public function clearOrderForDelivery(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->clear_for_delivery = "Yes";
        $order->save();
        return back()->with('Success','Order Cleared For Delivery');
    }
    public function downloadquotation(Request $request)
    {
        $price = Quotation::where('req_id',$request->id)->first();
        $procurement = ProcurementDetails::where('project_id',$price->project_id)->first();
        $mprocurement = Mprocurement_Details::where('manu_id',$request->manu_id)->first();
        $data = array(
            'price'=>$price,
            'procurement'=>$procurement,
            'mprocurement'=>$mprocurement
        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.quotation')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('pdf.quotation');
        }
    }
    public function downloadInvoice(Request $request)
    {
        $products = DB::table('orders')->where('id',$request->id)->first();
        $address = SiteAddress::where('project_id',$products->project_id)->first();

        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $payment = PaymentDetails::where('order_id',$request->id)->first();
        $price = MamahomePrice::where('order_id',$request->id)->orderby('created_at','DESC')->first()->getOriginal();
        // $manuid =  MamahomePrice::where('order_id',$request->id)->pluck('manu_id')->first();
         if( $request->manu_id != null){
        $manu = Manufacturer::where('id',$request->manu_id)->first()->getOriginal();
        $mprocurement = Mprocurement_Details::where('manu_id',$request->manu_id)->first()->getOriginal();
            }
            else{
                $mprocurement = "";
                $manu = "";
            }
          
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement,
            'payment'=>$payment,
            'price'=>$price,
            'manu'=>$manu,
            'mprocurement'=>$mprocurement
        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.invoice')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('pdf.invoice');
        }
    }
    function downloadTaxInvoice(Request $request){
        $products = DB::table('orders')->where('id',$request->id)->first();

        $address = SiteAddress::where('project_id',$products->project_id)->first();
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $payment = PaymentDetails::where('order_id',$request->id)->first();
        $price = MamahomePrice::where('order_id',$request->id)->orderby('created_at','DESC')->first()->getOriginal();
        if( $request->manu_id != null){
        $manu = Manufacturer::where('id',$request->manu_id)->first()->getOriginal();
        dd($request->manu_id);
        $mprocurement = Mprocurement_Details::where('manu_id',$request->manu_id)->first()->getOriginal();
            }
            else{
                 $mprocurement = "";
                $manu = "";
            }
        
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement,
            'payment'=>$payment,
            'price'=>$price,
             'manu'=>$manu,
            'mprocurement'=>$mprocurement
        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.proformaInvoice')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('pdf.proformaInvoice');
        }
    }
    function downloadpurchaseOrder(Request $request){
        $products = DB::table('orders')->where('id',$request->id)->first();
         if( $products->project_id != null){
        $address = SiteAddress::where('project_id',$products->project_id)->first();
            }
            else{
                $address = "";
            }
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $payment = PaymentDetails::where('order_id',$request->id)->first();
        $sp = Supplierdetails::where('order_id',$request->id)->pluck('id')->first();
        $supplier = Supplierdetails::where('id',$sp)->first()->getOriginal();
        if( $request->mid != null){
        $manu = Manufacturer::where('id',$request->mid)->first()->getOriginal();
            }
            else{
                $manu = "";
            }
          
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement,
            'payment'=>$payment,
            'manu'=>$manu,
            'supplier'=>$supplier
        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.purchaseOrder')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('pdf.purchaseOrder');
        }
    }
    public function savePaymentDetails(Request $request)
    {
        $totalRequests = count($request->payment_slip);
        $order = Order::where('id',$request->id)->first();
        $order->payment_status = "Payment Received";
        $order->payment_mode = $request->method;
        $order->save();
        $i = 0;
        $paymentimage ="";
            if($request->payment_slip){
                foreach($request->payment_slip as $pimage){
                     $imageName3 = $i.time().'.'.$pimage->getClientOriginalExtension();
                     $pimage->move(public_path('payment_details'),$imageName3);
                     if($i == 0){
                        $paymentimage .= $imageName3;
                     }
                     else{
                            $paymentimage .= ",".$imageName3;
                     }
                     $i++;
                }
            }
        if($request->method == "CASH"){
               
                    $paymentDetails = new PaymentDetails;
                    $paymentDetails->order_id = $request->id;
                    $paymentDetails->payment_mode = $request->method;
                    $paymentDetails->date = $request->date;
                    $paymentDetails->Totalamount = $request->totalamount;
                    $paymentDetails->damount = $request->damount;
                    $paymentDetails->file = $paymentimage;
                    $paymentDetails->payment_note = $request->notes;
                    $paymentDetails->bank_name =$request->bankname;
                    $paymentDetails->branch_name = $request->branchname;
                    $paymentDetails->save();
                    
               
            }
            else if($request->method == "RTGS"){
                    $paymentDetails = new PaymentDetails;
                    $paymentDetails->order_id = $request->id;
                    $paymentDetails->payment_mode = $request->method;
                    $paymentDetails->account_number = $request->accnum;
                    $paymentDetails->branch_name =$request->accname;
                    $paymentDetails->date =$request->date;
                    $paymentDetails->Totalamount = $request->totalamount;
                    $paymentDetails->damount = $request->damount;
                    $paymentDetails->file = "";
                    $paymentDetails->payment_note = $request->notes;
                    $paymentDetails->save();
            }
            else if($request->method == "CHEQUE"){
               
                $paymentDetails = new PaymentDetails;
                $paymentDetails->order_id = $request->id;
                $paymentDetails->payment_mode = $request->method;
                $paymentDetails->cheque_number =$request->cheque_num;
                $paymentDetails->date =$request->date;
                $paymentDetails->Totalamount = $request->totalamount;
                $paymentDetails->damount = $request->damount;
                $paymentDetails->file = "";
                $paymentDetails->payment_note = $request->notes;
                $paymentDetails->bank_name =$request->bankname;
                $paymentDetails->branch_name = $request->branchname;
                $paymentDetails->save();
            }
            else{
                $paymentDetails = new PaymentDetails;
                $paymentDetails->order_id = $request->id;
                $paymentDetails->payment_mode = $request->method;
                $paymentDetails->cash_holder = $request->name;
                $paymentDetails->date =$request->date;
                $paymentDetails->damount = $request->damount;
                $paymentDetails->payment_note = $request->notes;
                $paymentDetails->Totalamount = $request->totalamount;
                $paymentDetails->save();
            }

        return redirect('/orders')->with('Success','Payment Details Saved Successfully');
    }
    public function getFinanceAttendance()
    {
        return view('finance.attendance');
    }
    public function getViewProformaInvoice(Request $request)
    {
        $products = DB::table('orders')->where('id',$request->id)->first();
        $address = SiteAddress::where('project_id',$products->project_id)->first();
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement
        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.proformaInvoice')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('pdf.proformaInvoice');
        }
    }
    public function getViewPurchaseOrder(Request $request)
    {
        $products = DB::table('orders')->where('id',$request->id)->first();
        $address = SiteAddress::where('project_id',$products->project_id)->first();
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement
        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.purchaseOrder')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('pdf.purchaseOrder');
        }
    }
    public function sendMessage(Request $request)
    {
        $message = New Message;
        $message->from_user = Auth::user()->id;
        $message->to_user = $request->orderId;
        $message->body = $request->message;
        $message->save();
        return back();
    }
    // public function confirmpayment(Request $request){    
    //     Order::where('id',$request->id)->update([
    //         'final_payment'=>"Received"
    //     ]);
    //     return back()->with('Success','Payment Received Successfully');
    // }
    public function paymentmode(Request $request){
        $users = User::where('department_id','!=',10)->get();
       return view('finance.payment',['id'=>$request->id,'users'=>$users]);
    }
    public function saveunitprice(Request $request){
       
        $unitwithoutgst = round($request->unitwithoutgst,2);
        $cgst = round($request->cgst,2);
        $sgst = round($request->sgst,2);
        $check = MamahomePrice::where('order_id',$request->id)->pluck("edited")->last();
        if($check == "No"){
        $order = Order::where('id',$request->id)->first();
        $order->confirm_payment = " Received";
        $order->save();
        $check = MamahomePrice::where('order_id',$request->id)->get()->last();
        $check->edited = "yes";
        $check->save();
        $price = new MamahomePrice;
        $price->order_id = $request->id;
        $price->unit = $request->unit;
        $price->mamahome_price = $request->price;
        $price->unitwithoutgst = $unitwithoutgst;
        $price->totalamount = $request->tamount;
        $price->cgst = $cgst;
        $price->sgst = $sgst;
        $price->totaltax = $request->totaltax;
        $price->amountwithgst = $request->gstamount;
        $price->amount_word = $request->dtow1;
        $price->tax_word = $request->dtow2;
        $price->gstamount_word =  $request->dtow3;
        $price->quantity = $request->quantity;
        $price->manu_id = $request->manu_id;
        $price->description = $request->desc;
        $price->billaddress = $request->bill;
        $price->shipaddress = $request->ship;
        $price->edited = "No";
        $price->updated_by = Auth::user()->id;
        $price->cgstpercent = $request->g1;
        $price->sgstpercent = $request->g2;
        $price->gstpercent = $request->g3;
        $price->save();
            
        }
        else{
        $order = Order::where('id',$request->id)->first();
        $order->confirm_payment = " Received";
        $order->save();    
        $price = MamahomePrice::where('order_id',$request->id)->first();
        $price->unit = $request->unit;
        $price->mamahome_price = $request->price;
        $price->unitwithoutgst = $unitwithoutgst;
        $price->totalamount = $request->tamount;
        $price->cgst = $cgst;
        $price->sgst = $sgst;
        $price->totaltax = $request->totaltax;
        $price->amountwithgst = $request->gstamount;
        $price->amount_word = $request->dtow1;
        $price->tax_word = $request->dtow2;
        $price->gstamount_word =  $request->dtow3;
        $price->quantity = $request->quantity;
        $price->manu_id = $request->manu_id;
        $price->description = $request->desc;
        $price->billaddress = $request->bill;
        $price->shipaddress = $request->ship;
        $price->updated_by = Auth::user()->id;
        $price->cgstpercent = $request->g1;
        $price->sgstpercent = $request->g2;
        $price->gstpercent = $request->g3;
        $price->edited = "No";
        $price->save();
        }
         PaymentDetails::where('order_id',$request->id)->update([
            'status'=>"Received"
        ]);
        return back()->with('Success','Payment Confirmed');
    }
    public function savesupplierdetails(Request $request){
        $order = Order::where('id',$request->id)->first();
        $order->purchase_order = "yes";
        $order->save();
        $year = date('Y');
        $country_initial = "O";
        $country_code = Country::pluck('country_code')->first();
        $zone = Zone::pluck('zone_number')->first();
        $name = ManufacturerDetail::where('manufacturer_id',$request->name)->pluck('company_name')->first();
        $supply = New Supplierdetails;
        $supply->manu_id = $request->mid;
        $supply->address = $request->address;
        $supply->order_id = $request->id;
        $supply->supplier_name = $name;
        $supply->gst = $request->gst;
        $supply->description = $request->desc;
        $supply->quantity = $request->quantity;
        $supply->unit = $request->unit;
        $supply->unit_price = $request->uprice;
        $supply->amount = $request->amount;
        $supply->amount_words = $request->dtow;
        $supply->totalamount = $request->totalamount;
        $supply->tamount_words = $request->dtow1;
        $supply->unitwithoutgst =$request->unitwithoutgst;
        $supply->cgstpercent = $request->cgstpercent;
        $supply->sgstpercent = $request->sgstpercent;
        $supply->gstpercent = $request->gstpercent;
        $supply->save();

        $lpoNo = "MH_".$country_code."_".$zone."_LPO_".$year."_".$supply->id; 
        $supply =Supplierdetails::where('id',$supply->id)->update(['lpo'=>
            $lpoNo]);
        return back();
    }
     public function getgst(Request $request){
        $res = ManufacturerDetail::where('company_name',$request->name)->pluck('registered_office')->first();
        $gst = ManufacturerDetail::where('company_name',$request->name)->pluck('gst')->first();
        $category = ManufacturerDetail::where('company_name',$request->name)->pluck('category')->first();
        $unit = Category::where('category_name',$category)->pluck('measurement_unit')->first();
        $id = $request->x;
        return response()->json(['res'=>$res,'id'=>$id,'gst'=>$gst,'category'=>$category,'unit'=>$unit]);
    }
    
}
