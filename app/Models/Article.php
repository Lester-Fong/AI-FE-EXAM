<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Eloquent;

use Config;
use Auth;
use Carbon\Carbon;
use Log;
use Crypt;

class Article extends Eloquent
{

    protected $table = 'tblArticles';
    protected $primaryKey = 'fldArticleID';
    public $timestamps = false;


    public function company()
    {
        return $this->belongsTo(Company::class, 'fldArticleCompanyID', 'fldCompanyID');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'fldArticleEditorID', 'fldUserID')
                    ->where('fldUserType', 1);
    }
    
    public function writer()
    {
        return $this->belongsTo(User::class, 'fldArticleWriterID', 'fldUserID')
                    ->where('fldUserType', 0);
    }


    public function addUpdateRecord($id, $data, $file)
    {
        $response_obj = new \stdClass();
        $user_model = new User();
        $messages = Config::get('Constants.MESSAGES');

        $user = Auth::user();
        $user_type = $user->fldUserType;
        
        if ($id == 0) {
            $article = new self;
            $article->fldArticleStatus = 0;
            $article->fldArticleWriterID = $user->fldUserID;
        } else {
            $article = self::find($id);
            if ($data['status'] == 'publish') {
                $article->fldArticleStatus = 1;
                $article->fldArticleEditorID = $user->fldUserID;
            }
        }

        $article->fldArticleTitle = $data['title'];
        $article->fldArticleContent = $data['content'];
        $article->fldArticleDate = Carbon::parse($data['date'])->format('Y-m-d');
        $article->fldArticleLink = $data['link'];
        $article->fldArticleCompanyID = $data['company_id'];

        $article->save();

        //    file saving
        $helper_model = new Helper();
        if ($file != null) {
            $filePath = $helper_model->ImageUpload($file, $article->fldArticleID, 'article');
            if ($filePath != null) {
                $article->fldArticleImage = file_exists($filePath) ? $filePath : null;
                $article->save();
            }
        }

        $response_obj->message = $messages['RECORD_SUCCESSFUL'];
        $response_obj->error = false;

        return $response_obj;
    }

    public function displayAllArticles()
    {
        return self::orderBy('fldArticleID', 'asc')->with('editor')->with('writer')->get();
    }

    public function displayAllActiveArticles()
    {
        return self::where('fldArticleStatus', 1)
            ->with('article_category')
            ->with('author')
            ->orderBy('fldArticleDatePublished', 'desc')
            ->get();
    }


    public function deleteRecord($data)
    {
        $response_obj = new \stdClass();
        $messages = Config::get('Constants.MESSAGES');
        $article_id = Crypt::decryptString($data['article_id']);

        $article = self::find($article_id);
        if ($article) {
            $article->delete();
            $response_obj->message = $messages['RECORD_DELETED_SUCCESSFUL'];
            $response_obj->error = false;
        } else {
            $response_obj->message = $messages['RECORD_NOT_FOUND'];
            $response_obj->error = true;
        }

        return $response_obj;
    }
    public function displayLatest($count)
    {
        $articlesUpdated = self::whereNotNull('fldArticleDateUpdated')
            ->orderBy('fldArticleDateUpdated', 'desc')
            ->where('fldArticleStatus', 1)
            ->take($count)
            ->get();

        if ($articlesUpdated->count() < $count) {
            $remainingCount = $count - $articlesUpdated->count();

            $articlesCreated = self::whereNull('fldArticleDateUpdated')
                ->orderBy('fldArticleDateCreated', 'desc')
                ->where('fldArticleStatus', 1)
                ->take($remainingCount)
                ->get();

            $articles = $articlesUpdated->merge($articlesCreated);
        } else {
            $articles = $articlesUpdated;
        }

        return $articles;
    }


    public function displayArticleByLink($link)
    {
        $article = self::where('fldArticleLink', $link)->where('fldArticleStatus', 1)
            ->first();

        return $article;
    }

}
