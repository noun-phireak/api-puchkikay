<?php
namespace App\Api\V1\Controllers\Printing;

use App\Model\Order\Order;

use Illuminate\Http\Request;


class InvoicePrintingController{

    function generateInvoice(Request $request, $id){

    
       $order = Order::where(['id'=> $id])
       ->with('details','customer')
       ->first();


        $fileName = 'Invoice#'.$order->receipt_number.'.pdf';

        $mpdf = new \Mpdf\Mpdf([
            'margin_left'   => 5,
            'margin_right'  => 5,
            'margin_top'    => 15,
            'margin_bottom' => 5,
            'margin_header' => 10,
            'margin_footer' => 10,
            'mode' => 'utf-8', 
            'format' => [160, 190]
        ]);

        

        $html = \View::make('pdf.demo')->with('data', $order);
        $html = $html->render();
        $mpdf->WriteHTML($html);
        $mpdf->Output($fileName, 'I');
   

    }

}