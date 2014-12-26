<?php

class APIController extends BaseController {

    public function getDepartmentOperators($department_id){
        return Response::json(API::getDepartmentOperators($department_id));
    }

    public function getDepartmentOperatorsWithAdmin($department_id){
        return Response::json(API::getDepartmentOperatorsWithAdmin($department_id));
    }

    public function changeStatus($status){
        $user = User::find(Auth::user()->id);
        $user->is_online = $status;
        $user->save();
        return Redirect::back();
    }

    public function getCompanyFreeDepartmentAdmins($company_id){
        return Response::json(API::getCompanyFreeDepartmentAdmins($company_id));
    }

    public function getCompanyDepartments($company_id){
        return Response::json(API::getCompanyDepartments($company_id));
    }

    public function getDepartmentPermissions($department_id){
        return Response::json(API::getDepartmentPermissions($department_id));
    }

    public function logIP(){
        if(Input::has('ip_address')){
            Session::put('client_ip',Input::get('ip_address'));
        }
    }

    public function conversationsRefresh(){

        if(Input::get('company_id',0)>0&&Input::get('department_id',0)>0){
            $online_users = OnlineUsers::where('company_id',Input::get('company_id'))->where('department_id',Input::get('department_id'))->get();
        }else{
            $online_users = OnlineUsers::all();
        }

        $conversations_arr = [];

        foreach($online_users as $online){
            $online->user = User::find($online->user_id);

            if($online->operator_id>0)
                $online->operator = User::find($online->operator_id);

            $single_conversation = [];
            $single_conversation[] = $online->id;
            $single_conversation[] = $online->user->name;
            $single_conversation[] = $online->user->email;
            $single_conversation[] = isset($online->operator)?$online->operator->name:"<label class='label label-warning'>NONE</label>";
            $single_conversation[] = \KodeInfo\Utilities\Utils::prettyDate($online->requested_on,true);
            $single_conversation[] = \KodeInfo\Utilities\Utils::prettyDate($online->started_on,true);
            $single_conversation[] = $online->locked_by_operator==1?"<label class='label label-warning'>Yes</label>":"<label class='label label-primary'>No</label>";

            if(!isset($online->operator))
                $single_conversation[] ='<td><a href="/conversations/accept/'.$online->thread_id.'" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> Accept </a></td>;';

            if(isset($online->operator)&&$online->operator->id==Auth::user()->id)
                $single_conversation[] ='<td><a href="/conversations/accept/'.$online->thread_id.'" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> Reply </a></td>';

            if(isset($online->operator)&&$online->operator->id!=Auth::user()->id)
                $single_conversation[] ='<td><a disabled class="btn btn-success btn-sm"> <i class="icon-lock3"></i> Accept </a></td>';

            $single_conversation[] = '<td><a href="/conversations/transfer/'.$online->id.'" class="btn btn-warning btn-sm"> <i class="icon-share3"></i> Transfer </a></td>';

            $single_conversation[] = '<td><a href="/conversations/close/'.$online->thread_id.'" class="btn btn-danger btn-sm"> <i class="icon-lock3"></i> Close </a></td>';

            $conversations_arr[] = $single_conversation;
        }

        return json_encode(['aaData'=>$conversations_arr]);
    }

    public function ticketsRefresh(){

        if(Input::get('company_id',0)>0&&Input::get('department_id',0)>0){
            $tickets = Tickets::orderBy('priority','desc')->where('company_id',Input::get('company_id'))->where('department_id',Input::get('department_id'))->get();
        }else{
            $tickets = Tickets::orderBy('priority','desc')->get();
        }

        $tickets_arr = [];

        foreach($tickets as $ticket){
            $ticket->customer = User::where('id',$ticket->customer_id)->first();
            $ticket->company = Company::where('id',$ticket->company_id)->first();
            $ticket->department = Department::where('id',$ticket->department_id)->first();

            if($ticket->operator_id > 0){
                $ticket->operator = User::where('id',$ticket->operator_id)->first();
            }

            $single_ticket = [];
            $single_ticket[] = $ticket->id;
            $single_ticket[] = isset($ticket->company)?$ticket->company->name:"NONE";
            $single_ticket[] = isset($ticket->department)?$ticket->department->name:"NONE";
            $single_ticket[] = isset($ticket->customer)?$ticket->customer->name:"NONE";
            $single_ticket[] = isset($ticket->customer)?$ticket->customer->email:"NONE";
            $single_ticket[] = $ticket->subject;
            $single_ticket[] = isset($ticket->operator)?$ticket->operator->name:"NONE";

            if($ticket->priority==Tickets::PRIORITY_LOW)
                $single_ticket[] = '<td ><label class="label label-primary" > Low</label ></td >';

            if($ticket->priority==Tickets::PRIORITY_MEDIUM)
                $single_ticket[] = '<td><label class="label label-primary">Medium</label></td>';

            if($ticket->priority==Tickets::PRIORITY_HIGH)
                $single_ticket[] = '<td><label class="label label-warning">High</label></td>';

            if($ticket->priority==Tickets::PRIORITY_URGENT)
                $single_ticket[] = '<td><label class="label label-danger">Urgent</label></td>';

            if($ticket->status==Tickets::TICKET_NEW)
                $single_ticket[] = '<td><label class="label label-warning">New</label></td>';

            if($ticket->status==Tickets::TICKET_PENDING)
                $single_ticket[] = '<td><label class="label label-primary">Pending</label></td>';

            if($ticket->status==Tickets::TICKET_RESOLVED)
                $single_ticket[] = '<td><label class="label label-success">Resolved</label></td>';

            if(!isset($ticket->operator))
                $single_ticket[] = '<td><a href="/tickets/read/'.$ticket->thread_id.'" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> Accept </a></td>';

            if(isset($ticket->operator)&&$ticket->operator->id==Auth::user()->id)
                $single_ticket[] = '<td><a href="/tickets/read/'.$ticket->thread_id.'" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> Reply </a></td>';

            if(isset($ticket->operator)&&$ticket->operator->id!=Auth::user()->id)
                $single_ticket[] = '<td><a disabled class="btn btn-success btn-sm"> <i class="icon-lock3"></i> Accept </a></td>';

            $single_ticket[] = '<td><a href="/tickets/transfer/'.$ticket->id.'" class="btn btn-warning btn-sm"> <i class="icon-share3"></i> Transfer </a></td>';
            $single_ticket[] = '<td><a href="/tickets/delete/'.$ticket->thread_id.'" class="btn btn-danger btn-sm"> <i class="icon-remove3"></i> Delete </a></td>';

            $tickets_arr[] = $single_ticket;

        }

        return json_encode(['aaData'=>$tickets_arr]);
    }

}