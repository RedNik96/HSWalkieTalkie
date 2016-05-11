<?php

Class ProfileHandler {
  public static function GET() {
    $profile_data = array(
      'picture_src' => "/HSWalkieTalkie/src/img/profile_default.png"
    );

    Template::render('test', $profile_data, array('template_right' => 'profile'));
  }
}
