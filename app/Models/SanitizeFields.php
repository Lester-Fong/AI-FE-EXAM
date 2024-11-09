<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class SanitizeFields extends Model
{

   public function sanitize_fields($input, $request_path, $is_date = false)
   {

      $allowed_host = array('publish-article.local', 'publish.article.com');
      if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_host)) {
         header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
         exit;
      }


      $search_array = array(
         'javasript', 'alert', '(', ')', ';', 'cmd', '%%', '%p', '%c', '%u', '%x', '%s', '%n', '%', '!n', '!s', '`', '{', '}', '%', '^',
         '\x00', '\x01', '\x02', '\x03', '\x04', '\x05', '\x06', '\x07', '\x08', '\x09', '\x10',
         '\x11', '\x12', '\x13', '\x14', '\x15', '\x16', '\x17', '\x18', '\x19', '\x20'
      );

      if ($is_date === false) {
         array_push($search_array, "/");
      }

      if ($request_path == "category") {

         array_push($search_array, "=", "-", "@", "+", "'", "`", "!", "|");
      } else if ($request_path == "registration") {
         array_push($search_array, "=", "-", "'", "`", "!", "|");
      }

      $search_replace = array(
         '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
         '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
      );


      if ((!strpos($request_path, 'emails') !== false) && (!strpos($request_path, 'save-reminder') !== false)) {
         $input = strip_tags($input);
         $input = str_replace($search_array, $search_replace, $input);
         $input = trim($input);
      }


      return $input;
   }
}
