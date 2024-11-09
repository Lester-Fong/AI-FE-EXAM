<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\Passport;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Hash;
use Config;
use Log;
use Crypt;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Str;

class User extends Authenticatable
{

   protected $table = 'tblUser';
   protected $primaryKey = 'fldUserID';
   public $timestamps = false;

   use HasApiTokens, Notifiable;

   protected $fillable = [
      'fldUserID', 'fldUserFirstname', 'fldUserLastname', 'fldUserEmail', 'fldUserPassword', 'fldUserMobile'
   ];


   public function findForPassport($username)
   {
      $user = $this
         ->where('fldUserEmail', $username)
         ->first();

      return $user;
   }


   public function validateForPassportPasswordGrant($password)
   {
      return Hash::check($password, $this->fldUserPassword);
   }

   private function addUpdateUserProfileImage($id, $filename)
   {
      $user = self::find($id);
      if ($user) {
         $user->fldUserProfileImage = $filename;
         $user->save();
      }
   }



   public function AddUpdateRecord($id, $data)
   {
      $response_obj = new \stdClass();
      
      if ($id == 0) {
         $user = new self;
      } else {
         $user = self::find($id);
      }

      $sanitize_model = new SanitizeFields();

      if ($user) {
         $user->fldUserFirstname = $sanitize_model->sanitize_fields($data['firstname'], "registration");
         $user->fldUserLastname = $sanitize_model->sanitize_fields($data['lastname'], "registration");
         $user->fldUserEmail = $sanitize_model->sanitize_fields($data['email'], "registration");
         $user->fldUserType = $data['user_type'];
         $user->fldUserStatus = $data['user_status'];
       
         if (isset($data['password']) && $data['password'] != "") {
            $user->fldUserPassword = Hash::make($data['password']);
         }

         if ($user->save()) {
            $response_obj->error = false;
            $response_obj->message = Config::get('Constants.MESSAGES')['RECORD_SUCCESSFUL'];
            $response_obj->user = $user;
         } else {
            $response_obj->error = true;
            $response_obj->message = Config::get('Constants.MESSAGES')['RECORD_NOT_FOUND'];
         }


         return $response_obj;
      }
   }


   public function checkAccess($data) {
      $response_obj = new \stdClass();
      $helper_token_model = new HelperToken();
      $messages = Config::get('Constants.MESSAGES');
      $user = self::where('fldUserEmail', $data['email'])->first();
  
      if (!$user) {
          $response_obj->error = true;
          $response_obj->message = $messages['INVALID_CREDENTIALS'];
          return $response_obj;
      }
  
      if (!Hash::check($data['password'], $user->fldUserPassword)) {
          $response_obj->error = true;
          $response_obj->message = $messages['INVALID_CREDENTIALS'];
          return $response_obj;
      }
  
      $response = $helper_token_model->GenerateUserToken($data['email'], $data['password']);
      $response = json_decode($response);
      $token_expiration_date = date('Y-m-d H:i:s', strtotime('+ 20 minutes'));

      $response_obj->error = false;
      $response_obj->refresh_token = $response->refresh_token;
      $response_obj->token_expiration = $token_expiration_date;
      $response_obj->access_token = $response->access_token;
      return $response_obj;
  }

   public function displayAllUser()
   {
      $user = self::orderBy('fldUserFirstname', 'asc')->where('fldUserID', '!=', Auth::user()->fldUserID)->get();
      return $user;
   }


   public function changeStatus($data)
   {
      $user_id = Crypt::decryptString($data["user_id"]);
      $status = $data["status"];
      $response_obj = new \stdClass();
      $user = self::findOrFail($user_id);

      if ($user) {
         $user->fldUserActive = $status;
         $user->save();

         $response_obj->error = false;
         $response_obj->message = $status == 1 ? Config::get('Constants.MESSAGES')['ACTIVATE_MESSAGE'] : Config::get('Constants.MESSAGES')['DEACTIVATE_MESSAGE'];
      } else {
         $response_obj->error = true;
         $response_obj->message = Config::get('Constants.MESSAGES')['RECORD_NOT_FOUND'];
      }

      return $response_obj;
   }

   public function removeUser($data)
   {
      $user_id = Crypt::decryptString($data["user_id"]);
      $response_obj = new \stdClass();
      $user = self::findOrFail($user_id);

      if (isset($user)) {
         $user->delete();

         $response_obj->error = false;
         $response_obj->message = Config::get('Constants.MESSAGES')['ACTIVATE_MESSAGE']                                                       ;
      } else {
         $response_obj->error = true;
         $response_obj->message = Config::get('Constants.MESSAGES')['RECORD_NOT_FOUND'];
      }

      return $response_obj;
   }

   public function getInfo() {
      if (Auth::guard('user')->check()) {
         $user = Auth::guard('user')->user();
         return $user;
      }
   }


}
