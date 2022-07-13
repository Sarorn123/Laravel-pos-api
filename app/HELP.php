<?php

namespace App;

use DateTime;

define('Paid', 1);
/////////////////

class HELP{

    public static function dateCoverter($date){
        if($date == null || $date == ""){
            return "";
        }

        $newDate = date("d-m-Y", strtotime($date)); 
        return $newDate;
    }

    public static function dateCoverterToDay($date){
        if($date == null || $date == ""){
            return "";
        }

        $date = new DateTime($date);
        return $date->format('l');
        
    }

    public static function dateCoverterToMonth($date){
        if($date == null || $date == ""){
            return "";
        }

        $date = new DateTime($date);
        return $date->format('M');
        
    }

    public static function dateCoverterToDB($date){
        if($date == null || $date == ""){
            return "";
        }

        $newDate = date("Y-m-d", strtotime($date)); 
        return $newDate;
    }

    public static function pagination($request, $query){

        if($request->perPage){
            if($request->page_number) {
                $offset = ($request->page_number-1) * $request->perPage;
                $query->offset($offset)->limit($request->perPage);
            }else{
                $query->limit($request->perPage);
            }
        }else{
            if($request->page_number) {
                $offset = ($request->page_number-1) * 10;
                $query->offset($offset)->limit(10);
            }else{
                $query->limit(10);
            }
        }

        return $query->get();
    }

    public static function storeImage($request){
        if($request->hasFile('profile_image')){
            $profile_image = $request->file('profile_image');
            $filename = time() . '.' . $profile_image->getClientOriginalExtension();
            $profile_image->storeAs('/images/' , $filename);
            return $filename;
        }
        return null;
    }


}

