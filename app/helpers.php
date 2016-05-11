<?php
function breadcrumb($data) {
	echo '<ol class="breadcrumb"><li><a href="#"><i class="fa fa-circle" aria-hidden="true"></i>SmartBots</a></li>';
	foreach ($data as $key => $value) {
		if ($value != 'active') {
			echo '<li><a href="'.$value.'">'.$key.'</a></li>';
		} else {
			echo '<li class="active">'.$key.'</li>';
		}
	}
	echo '</ol>';
}
