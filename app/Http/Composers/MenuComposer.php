<?php

namespace SmartBots\Http\Composers;

class MenuComposer {

	public function compose($view) {
		$view->withMenu([
			'Navigation header',
			'Dashboard' => [
				'icon' => 'ti-home',
				'href' => route('h::dashboard')
			],
			'Hub' => [
				'icon' => 'ti-settings',
				'href' => route('h::edit')
			],
			'Bot' => [
				'icon' => 'ti-package',
				'sub' => [
					'Bot list' => [
						'icon' => 'ti-menu-alt',
						'href' => route('h::b::index')
					],
					'Add new bot' => [
						'icon' => 'ti-plus',
						'href' => route('h::b::create')
					]
				]
			],
			'Member' => [
				'icon' => 'ti-user',
				'sub' => [
					'Member list' => [
						'icon' => 'ti-menu-alt',
						'href' => route('h::m::index')
					],
					'Add new member' => [
						'icon' => 'ti-plus',
						'href' => route('h::m::create')
					]
				]
			],
			'divider',
			'Schedule' => [
				'icon' => 'ti-bookmark-alt',
				'sub' => [
					'Schedule list' => [
						'icon' => 'ti-menu-alt',
						'href' => route('h::s::index')
					],
					'Add new schedule' => [
						'icon' => 'ti-plus',
						'href' => route('h::s::create')
					]
				]
			]
		]);
	}

}
