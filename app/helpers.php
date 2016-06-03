<?php
function breadcrumb($data) {
	$str = '<ol class="breadcrumb"><li><a href="#" class="text-inverse"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;SmartBots</a></li>';
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

function is_email($email) {
    return !filter_var($email, FILTER_VALIDATE_EMAIL) === false ? true : false;
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
