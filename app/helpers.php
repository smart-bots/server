<?php

function breadcrumb($data) {
    $str = '<ol class="breadcrumb"><li><a href="#" class="text-inverse"><i class="fa fa-dot-circle-o m-r-5" aria-hidden="true"></i>SmartBots</a></li>';

    foreach ($data as $key => $value) {
        if ($value != 'active') {
            $str .='<li><a href="'.$value.'" class="text-inverse">'.$key.'</a></li>';
        } else {
            $str .= '<li class="active">'.$key.'</li>';
        }
    }

    $str .= '</ol>';
    return $str;
}

function content_header($title, $breadcrumb) {
    return '<div class="row">
        <div class="col-sm-12">
            <div class="pull-left button back-btn">
                <a href="javascript:history.back()" class="button text-inverse"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
            </div>
            <div class="content-header">
              <h4 class="page-title">'.$title.'</h4>'.
              breadcrumb($breadcrumb).'
            </div>
        </div>
    </div>';
}

function upload_base64_image($image_base64):string {
    $image_name = str_random(10).'.jpg';

    while (Storage::exists($image_name)) {
        $image_name = str_random(10).'.jpg';
    }

    $image_location = '/public'.Storage::url($image_name);

    Image::make($image_base64)->resize(200, 200)->encode('jpg')->save(base_path().$image_location);

    return $image_location;
}
