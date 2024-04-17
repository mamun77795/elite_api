<?php

namespace App\Http\Controllers;



class TestController extends Controller
{
  
    public function test()
    {
       // $data= basegroup_stock(34,263);
      //  $data= volume_transfer_point(3349,13,1,10);
        $data= painter_level_update(13);
        
        return response()->json(['data' => $data], 200);
    }


}
