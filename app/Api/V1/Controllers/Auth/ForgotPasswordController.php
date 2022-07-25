<?php

namespace App\Api\V1\Controllers\Auth;
use Illuminate\Http\Request;
use JWTAuth;
use App\Api\V1\Controllers\ApiController;
use App\Model\User\Main as User;

class ForgotPasswordController extends ApiController{

    public function changePassword( Request $request){

        // Input old password.
        $old_password = $request->input('old_password');

        // Current user logged in.
        $user_id =JWTAuth::parseToken()->authenticate()->id;

        // Find current user password.
        $current_password = User::find($user_id)->password;

        if (password_verify($old_password, $current_password)){

            $this->validate($request,[
                'password'              => 'required|min:6|max:20',
                'password_confirmation' => 'required|same:password',
            ],[
                'password.required'     => 'សូមបញ្ជាក់ពាក្យសម្ងាត់ថ្មីរបស់អ្នក។',
                'password.min'          => 'លេខសម្ងាត់ត្រូវតែមានយ៉ាងហោចណាស់ ៦ តួអក្សរ',
                'password.max'          => 'លេខសម្ងាត់ត្រូវតែមានច្រើនជាង ២០ តួអក្សរ។',

                'password_confirmation.required' => 'សូមបញ្ចូលពាក្យសម្ងាត់របស់អ្នកឡើងវិញ',
                'password_confirmation.same'     => 'សូមបញ្ចូលពាក្យសម្ងាត់បញ្ជាក់របស់អ្នកឡើងវិញទៅពាក្យសម្ងាត់ថ្មីដដែល',
            ]);

            $id = 0;
            $user = User::findOrFail($user_id);
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json([
                'status' =>'sucess',
                'message'=> 'លេខសម្ងាត់ត្រូវបានកែប្រែថ្មីដោយជោគជ័យ'
            ], 200);
            
        }else{
            return response()->json([
                   'status' => 'error',
                   'message' => 'ពាក្យសម្ងាត់ចាស់របស់អ្នកមិនត្រឹមត្រូវ។ សូមបន្ថែមមួយផ្សេងទៀត'
               ], 401);
           }
    }
}