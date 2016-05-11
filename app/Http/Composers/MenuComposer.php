<?php

namespace SmartBots\Http\Composers;

class MenuComposer {

	public function compose($view) {
		$view->withMenu([
			'Dashboard' => [
				'icon' => 'fa-tachometer',
				'link' => route('h::dashboard')
			],
			'Hub' => [
				'icon' => 'fa-cog',
				'link' => route('h::edit')
			],
			'Bot' => [
				'icon' => 'fa-stop',
				'nextLevel' => [
					'Bot list' => [
						'icon' => 'fa-list-ul',
						'link' => route('h::b::index')
					],
					'Add new bot' => [
						'icon' => 'fa-plus',
						'link' => route('h::b::create')
					]
				]
			],
			'Member' => [
				'icon' => 'fa-users',
				'nextLevel' => [
					'Member list' => [
						'icon' => 'fa-list-ul',
						'link' => route('h::m::index')
					],
					'Add new member' => [
						'icon' => 'fa-plus',
						'link' => route('h::m::create')
					]
				]
			],
			'Schedule' => [
				'icon' => 'fa-ils',
				'nextLevel' => [
					'Schedule list' => [
						'icon' => 'fa-list-ul',
						'link' => route('h::s::index')
					],
					'Add new schedule' => [
						'icon' => 'fa-plus',
						'link' => route('h::s::create')
					]
				]
			],
			'Logout' => [
				'icon' => 'fa-sign-out',
				'link' => 'javascript:hubLogout()'
			]
		]);
	}

}
