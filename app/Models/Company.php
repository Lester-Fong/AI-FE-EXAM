<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Eloquent;

use Input;
use Config;
use Auth;
use Carbon\Carbon;
use Log;
use Crypt;
use Str;

class Company extends Eloquent
{

    protected $table = 'tblCompany';
    protected $primaryKey = 'fldCompanyID';
    public $timestamps = false;

    // relationship
    public function articles()
    {
        return $this->hasMany(Article::class, 'fldCompanyID', 'fldArticleCompanyID');
    }

    public function addUpdateRecord($id, $data, $file)
    {
        $response_obj = new \stdClass();
        $messages = Config::get('Constants.MESSAGES');

        if ($id == 0) {
            $company = new self;
        } else {
            $company = self::find($id);
        }

        $company->fldCompanyName = $data['name'];
        $company->fldCompanyStatus = $data['status'];
        $company->save();

        if (isset($file)) {
            $helper_model = new Helper();
            if ($file != null) {
                $filePathLink = $helper_model->ImageUpload($file, $company->fldCompanyID, 'company');
                if ($filePathLink) {
                    $company->fldCompanyLogo = $filePathLink;
                    $company->save();
                }
            }
        }

        $response_obj->message =  $messages['RECORD_SUCCESSFUL'];
        $response_obj->error = false;

        return $response_obj;
    }

    public function displayAllCompanies()
    {
        return self::orderBy('fldCompanyID', 'asc')->get();
    }


    public function deleteRecord($data)
    {
        $response_obj = new \stdClass();
        $messages = Config::get('Constants.MESSAGES');
        $company_id = Crypt::decryptString($data['company_id']);

        $feedback = self::find($company_id);

        if ($feedback) {
            $feedback->delete();
            $response_obj->message = $messages['RECORD_DELETED_SUCCESSFUL'];
            $response_obj->error = false;
        } else {
            $response_obj->message = $messages['RECORD_NOT_FOUND'];
            $response_obj->error = true;
        }

        return $response_obj;
    }
}
