<?php

namespace App\Http\Controllers\Employee\Inventory;

use App\Drivers\FirebaseDriver;
use App\Http\Controllers\Controller;
use App\Inventory;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{

    public function listing(Request $request)
    {
        $inventories = Inventory::query()->where('user_id', Auth::id());
        $inputs = $request->all();
        // // if (!empty($inputs['status'])) {
        // //     if ($inputs['status'] ==  ASSIGNED) {
        // //         $inventories =  $inventories->where('user_id', '!=', 'null');
        // //     }
        // //     if ($inputs['status'] ==  UNASSIGNED) {
        // //         $inventories =  $inventories->whereNull('user_id');
        // //     }
        // // }
        // // if (!empty($inputs['user_id'])) {

        // //     $inventories =  $inventories->where(function ($q) use ($inputs) {
        // //         $this->search($q, $inputs['user_id'], ['user_id']);
        // //     });
        // // }
        // if (!empty($inputs['search'])) {

        //     $inventories =  $inventories->where(function ($q) use ($inputs) {
        //         $this->search($q, $inputs['search'], ['name', 'code'], 'user');
        //     });
        // }

        $inventories = $inventories->orderBy('created_at', 'DESC');
        $inventories = $inventories->paginate(DATA_PER_PAGE);
        return view('employees.inventories.index', compact('inventories'));
    }

    public function requestInventory(Request $request)
    {
        try {

            // dd($request->all()/);
            DB::beginTransaction();
            $inventory = Inventory::where('id', $request->id)->where('user_id', Auth::id())->first();
            $inventory->request = $request->get('request');
            // $inventory->status = $inventory->status == INVENTORY_OFFICE ? INVENTORY_HOME : INVENTORY_OFFICE;
            $notify = new FirebaseDriver();
            $notify->setMessageBody("Inventory Request", "You Have New Inventory Request", NOTIFICATION_DEVICE);
            $notify->sendNotificationToUser("Admins", $inventory->user->id);
            $inventory->save();
            DB::commit();
            return redirect()->route('employee.inventories.listing')->with('success', 'Your request submitted successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Somthing went wrong');
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Somthing went wrong');
        }
    }
}
