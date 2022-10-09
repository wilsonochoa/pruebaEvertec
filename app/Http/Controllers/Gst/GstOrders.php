<?php

namespace App\Http\Controllers\Gst;

use App\Models\Orders;
use App\Models\Users;
use DateTime;

class GstOrders
{
    /**
     * @param $data
     * Inserta un registro en la tabla users
     * @return Users|false
     */
    public static function insertUser($data)
    {
        $users = new Users();
        $users->name = trim($data['name']);
        $users->email = trim($data['email']);
        $users->mobile = $data['mobile'];
        if (!$users->save()) {
            return false;
        }
        return $users;
    }

    /**
     * @param $id_user
     * Inserta un registro en la tabla orders
     * @return Orders|false
     */
    public static function insertOrder($id_user)
    {
        $orders = new Orders();
        $orders->status = 1; //creada
        $orders->id_user = $id_user;
        $orders->id_request = null;
        $orders->process_url = null;
        if (!$orders->save()) {
            return false;
        }
        return $orders;
    }

    /**
     * @param $data
     * @param $id_user
     * Actualiza un registro en la tabla users
     * @return false
     */
    public static function updateUser($data, $id_user)
    {
        $users = Users::find($id_user);
        $users->name = trim($data['name']);
        $users->email = trim($data['email']);
        $users->mobile = $data['mobile'];
        if (!$users->save()) {
            return false;
        }
        return $users;
    }

    /**
     * @param $id
     * @param $status
     * @param false $id_request
     * @param false $process_url
     * Actualiza un registro en la tabla orders
     * @return false
     */
    public static function updateOrder($id, $status, $id_request = false, $process_url = false)
    {
        $orders = Orders::find($id);
        $orders->status = $status;
        if($id_request){
            $orders->id_request = $id_request;
            $orders->process_url = $process_url;
        }
        if (!$orders->save()) {
            return false;
        }
        return $orders;
    }
}
