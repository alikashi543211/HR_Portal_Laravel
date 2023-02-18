<?php

namespace App\Http\Controllers\Api\Inventory;

use App\Drivers\FirebaseDriver;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Inventory\InventoryRequest;
use App\Inventory;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{


    public function listing()
    {
        $inventories = Inventory::query()->where('user_id', Auth::id());
        $inventories = $inventories->orderBy('created_at', 'DESC');
        $inventories = $inventories->get();

        return $this->apiSuccessWithData('', $inventories);
    }

    public function requestInventory(InventoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $inventory = Inventory::where('id', $request->id)->where('user_id', Auth::id())->first();
            if (($inventory->request  == INVT_REQ_HOME_PENDING && $request->get('request')  == INVT_REQ_OFFICE_PENDING)
                || ($inventory->request  == INVT_REQ_OFFICE_PENDING && $request->get('request')  == INVT_REQ_HOME_PENDING)
            ) {
                return $this->apiError("You need admin approval first", ERROR_400);
            }
            $inventory->request = $request->get('request');
            $notify = new FirebaseDriver();
            $notify->setMessageBody("Inventory Request", "You Have New Inventory Request", NOTIFICATION_DEVICE);
            $notify->sendNotificationToUser("Admins", $inventory->user->id);
            $inventory->save();
            DB::commit();
            return $this->apiSuccess('Your request submitted successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            dd($e->getMessage());
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        }
    }
}
