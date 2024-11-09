<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Log;
use Config;
use Illuminate\Support\Facades\Crypt;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UserMutation extends Mutation
{

    protected $attributes = [
        'name' => 'UserMutation'
    ];


    public function args(): array
    {
        return [
            'user' => ['type' => GraphQL::type('user_input')],
        ];
    }

    public function type(): Type
    {
        return GraphQL::type('response_type');
    }


    public function validationErrorMessages(array $args = []): array
    {
        return [
            'user.firstname.required' => 'Please enter your firstname',
            'user.lastname.required' => 'Please enter your lastname',
            'user.email.required' => 'Please enter your email',
            'user.email.email' => 'Please enter your valid email address',
            'user.email.unique' => 'Email address already in used',
            'user.password.required' => 'Please enter your password',
            'user.password.min' => 'Password must be atleast 8 characters in length',
            'user.password.regex' => 'Password must be atleast one lowercase, uppercase, digit and character',
        ];
    }

    public function rules(array $args = []): array
    {
        $rules = [];
        $user = $args['user'];


        if ($user['action_type'] == "login") {
            $rules['user.email'] = ['required', 'email'];
            $rules['user.password'] = ['required'];
        } else if ($user['action_type'] == "new_record") {
            $rules['user.firstname'] = ['required'];
            $rules['user.lastname'] = ['required'];
            $rules['user.mobile'] = ['required'];
            $rules['user.email'] = ['required', 'email', 'unique:tblUser,fldUserEmail'];
            $rules['user.password'] = ['required'];
        } else if ($user['action_type'] == "update_record") {
            $user = $args['user'];
            $user_id = Crypt::decryptString($user["user_id"]);

            $rules['user.firstname'] = ['required'];
            $rules['user.lastname'] = ['required'];
            $rules['user.email'] = ['required', 'email', 'unique:tblUser,fldUserEmail,' . $user_id . ',fldUserID'];
            $rules['user.mobile'] = ['required'];
            if ($user['password'] != "") {
                $rules['user.password'] = ['min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&_])[A-Za-z\d@$!%*#?&_]{8,}$/'];
                $rules['user.confirm_password'] = ['required', 'same:user.password'];
            }
        }
        return $rules;
    }


    public function resolve($root, $args)
    {

        $user = $args['user'];
        $response_obj = new \stdClass();
        $user_model = new User();

        if ($user['action_type'] == "login") {
            $response_obj = $user_model->checkAccess($user);
        }


        if ($user["action_type"] == "create_user") {
            $response_obj = $user_model->AddUpdateRecord(0, $user);
        }

        if ($user["action_type"] == "update_user") {
            $user_id = Crypt::decryptString($user['user_id']);

            $user_rec = User::find($user_id);
            if ($user_rec) {
                $response_obj = $user_model->AddUpdateRecord($user_id, $user);
            } else {
                $response_obj = new \stdClass();
                $response_obj->error = true;
                $response_obj->message = Config::get('Constants.ERROR_MESSAGE')['RECORD_NOT_FOUND'];
            }
        }

        if ($user["action_type"] == "change_status") {
            $response_obj = $user_model->changeStatus($user);
        }

        if ($user["action_type"] == "remove_user") {
            $response_obj = $user_model->removeUser($user);
        }

        return $response_obj;
    }
}
