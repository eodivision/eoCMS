<?php
//created by cathy and fixed by luce at http://php.net/str_word_count
function limit_text($text, $limitstr, $limitwrd) {
      if (strlen($text) > $limitstr) {
          $words = str_word_count($text, 2);
          if ($words > $limitwrd) {
              $pos = array_keys($words);
              $text = substr($text, 0, $pos[$limitwrd]);
          }
      }
      return $text;
    }
?>