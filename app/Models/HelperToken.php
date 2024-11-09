<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contacts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\Passport;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;

use Hash;
use Log;
use Session;
use Request;
use GuzzleHttp\Client;

use App\User;

class HelperToken extends Authenticatable
{
  use HasApiTokens, Notifiable;

  private $user_client_secret = 'z9j6fyHYN59hKhGMKGrxO0f6Op4X2ITbpIyEuo88';
  private $user_client_id = '1';

  public function GenerateUserToken($email, $password)
    {
        $client = new Client();
        $form_params = [
            'client_id' => $this->user_client_id, // Ensure this is the correct client ID
            'client_secret' => $this->user_client_secret,
            'grant_type' => 'password',
            'username' => $email,
            'password' => $password,
            'scope' => '',
        ];

        try {
            $response = $client->post(url('oauth/token'), [
                'form_params' => $form_params,
            ])->getBody()->getContents();

            return $response;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            Log::debug(print_r('err', true));
            Log::error('Error: ' . $responseBodyAsString);
            return $responseBodyAsString;
        } catch (\Throwable $th) {
            Log::error('Error: ' . $th->getMessage());
            return null;
        }
    }


  public function RefreshUserToken($refresh_token)
  {
    $client = new Client;

    try {
      $response = $client->post(url('oauth/token'), [
        'form_params' => [
          'grant_type' => 'refresh_token',
          'refresh_token' => $refresh_token,
          'client_id' => $this->user_client_id,
          'client_secret' => $this->user_client_secret,
          'scope' => '',
        ],
      ])->getBody()->getContents();
    } catch (\Exception $e) {
      Log::debug($e->getMessage());
    }


    return $response;
  }
  
}
