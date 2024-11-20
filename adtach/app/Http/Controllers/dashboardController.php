<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\help;
use App\Models\customer;
use Carbon\Carbon;

class dashboardController extends Controller
{
     public function viewDashboard(){
        $userCount = user::where('role','user')->count();
        $sale = customer::where('status','sale')->count();
        $trial = customer::where('status','trial')->count();
        $lead = customer::where('status','lead')->count();
        $help = help::where('status','pending')->count();
        $price = Customer::sum('price');
        return  view('admin.dashbord',compact(['userCount','sale','trial','lead','price','help']));
     }

     public function  viewAgentSaleTable(){
      
       $customers = Customer::with('user')->where('status','sale')->get();

       return view('admin.agent_sale',compact('customers'));
     }

     public function cutomerUPdateSaleDetailFormVIew(string $id){
      $customer = customer::find($id); 

     return view('admin.edit_agent_sale',compact('customer'));
}

public function cutomerUPdateDetailSaleStore(Request $req, string $id){
  $req->validate([
    'customer_name' => 'required|string',
    'customer_number' => 'required|numeric',
    'price' => 'required|numeric',
    'remarks' => 'required',
    'status' => 'required', 
]);

    $customer = customer::find($id);
    $email = $req->customer_email ?: 'No Email'; 
    $customer->update([
      'customer_name' => $req->customer_name,
      'customer_email' => $email,
      'customer_number' => $req->customer_number,
      'price' => $req->price,
      'remarks' => $req->remarks,
      'status' => $req->status,  
    ]);

    return  redirect()->route('viewAgentSaleTable')->with(['success' => 'Customer Detail Update Successfuly']);
 }

 public function deleteSaleCustomerDetails(string $id){
  $customer = customer::find($id);
  $customer->delete();
  return  redirect()->route('viewAgentSaleTable')->with(['success' => 'Customer Detail Deleted Successfuly']);
}

     public function  viewAgentLeadlTable(){
      
       $customers = Customer::with('user')->where('status','lead')->get();

       return view('admin.agent_lead',compact('customers'));
     }

     public function cutomerUPdateDetailFormVIew(string $id){
       $customer = customer::find($id); 

      return view('admin.edit_agent_lead',compact('customer'));
 }

   public function cutomerUPdateDetailStore(Request $req, string $id){
    $req->validate([
      'customer_name' => 'required|string',
      'customer_number' => 'required|numeric',
      'price' => 'required|numeric',
      'remarks' => 'required',
      'status' => 'required', 
  ]);
  
      $customer = customer::find($id);
      $email = $req->customer_email ?: 'No Email'; 
      $customer->update([
        'customer_name' => $req->customer_name,
        'customer_email' => $email,
        'customer_number' => $req->customer_number,
        'price' => $req->price,
        'remarks' => $req->remarks,
        'status' => $req->status,  
      ]);

      return  redirect()->route('viewAgentLeadlTable')->with(['success' => 'Customer Detail Updated Successfuly']);
   }

   public function deleteLeadCustomerDetails(string $id){
      $customer = customer::find($id);
      $customer->delete();
      return  redirect()->route('viewAgentLeadlTable')->with(['success' => 'Customer Detail Deleted Successfuly']);
   }

     public function  viewAgentTrialTable(){
      
       $customers = Customer::with('user')->where('status','trial')->get();
       return view('admin.agent_trial',compact('customers'));
     }

     public function cutomerUPdateTrialDetailFormVIew(string $id){
      $customer = customer::find($id); 

     return view('admin.edit_agent_trial',compact('customer'));
}

public function cutomerUPdateDetailTrialStore(Request $req, string $id){
  $req->validate([
    'customer_name' => 'required|string',
    'customer_number' => 'required|numeric',
    'price' => 'required|numeric',
    'remarks' => 'required',
    'status' => 'required', 
]);

    $customer = customer::find($id);
    $email = $req->customer_email ?: 'No Email'; 
    $customer->update([
      'customer_name' => $req->customer_name,
      'customer_email' => $email,
      'customer_number' => $req->customer_number,
      'price' => $req->price,
      'remarks' => $req->remarks,
      'status' => $req->status,  
    ]);

    return  redirect()->route('viewAgentTrialTable')->with(['success' => 'Customer Detail Update Successfuly']);
 }

 public function deleteTrialCustomerDetails(string $id){
  $customer = customer::find($id);
  $customer->delete();
  return  redirect()->route('viewAgentTrialTable')->with(['success' => 'Customer Detail Deleted Successfuly']);
}



    public function updateCustomerStatus(string $id){
        $customer = customer::find($id);
        $customer->status = 'sale';
        $customer->save();
        return  redirect()->route('viewAgentTrialTable')->with(['success' => 'Customer Detail Updated Successfuly']);
      }
      
      public function deleteCustomerDetails(string $id){
        $customer = customer::find($id);
        $customer->delete();
        return  redirect()->route('viewAgentTrialTable')->with(['success' => 'Customer Cencel Successfuly']);
    }


    public function viewHelpRequestTableDashboard(){
      $helpRequest = help::all();
       return view('admin.helpTable',compact('helpRequest'));
    }

    public function downHelpRequestStatus(string $id){
      $help = help::find($id);
      $help->status = 'down';
      $help->save();
      return redirect()->route('viewHelpRequestTableDashboard')->with(['success' => 'Help Request is Down Successfuly']);     
    }

    public function cancelHelpRequestStatus(string $id){
      $help = help::find($id);
      $help->status = 'cancel';
      $help->save();
      return redirect()->route('viewHelpRequestTableDashboard')->with(['success' => 'Help Request is Cancel Successfuly']);     
    }

    public function viewTrialDaysForm(string $id){
      $customer = customer::find($id);
      return view('admin.trial_Days',compact('customer'));
    }
    
    public function storeTrialDays(Request $req,string $id){
          $req->validate([
           'make_address' => 'required',
           'start_date' => 'required|date',
           'end_date' => 'required|date|after_or_equal:start_date',
         ]);

    // Parse the start_date and end_date using PHP's DateTime class
         $startDate = new \DateTime($req->start_date);
         $endDate = new \DateTime($req->end_date);

    // Calculate the difference in days between start_date and end_date
         $interval = $startDate->diff($endDate);
         $daysDifference = $interval->days; // Number of days difference
    // Fetch the customer and update details
         $customer = Customer::find($id);
         $customer->active_status = 'active';
         $customer->make_address = $req->make_address;
         $customer->start_date = $req->start_date;
         $customer->end_date = $req->end_date;
         $customer->date_count = $daysDifference;
         $customer->save();

         return redirect()->route('viewAgentTrialTable')->with(['success' => 'Customer Trial Days Is Start Now']);

    }

    public function updateStatusCustomerTrial(){
      $customers = Customer::where('active_status', 'active')->get();

      foreach ($customers as $customer) {
          if ($customer->date_count > 0) {
              // Decrement the date_count
              $customer->date_count = (int) $customer->date_count - 1;

              // If date_count reaches 0, set the status to inactive
              if ($customer->date_count == 0) {
                  $customer->active_status = 'inactive';
              }

              // Save the updated customer record
              $customer->save();
          }
      }

      // Return a response indicating the update is complete
      return response()->json(['status' => 'Update complete']);
    }

}