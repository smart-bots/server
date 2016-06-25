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
            'Schedule' => [
                'icon' => 'ti-timer',
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
            ],
            'Automation' => [
                'icon' => 'ti-bolt',
                'sub' => [
                    'Automation list' => [
                        'icon' => 'ti-menu-alt',
                        'href' => route('h::a::index')
                    ],
                    'Add new automation' => [
                        'icon' => 'ti-plus',
                        'href' => route('h::a::create')
                    ]
                ]
            ],
            'Event' => [
                'icon' => 'ti-panel',
                'sub' => [
                    'Event list' => [
                        'icon' => 'ti-menu-alt',
                        'href' => route('h::e::index')
                    ],
                    'Add new Event' => [
                        'icon' => 'ti-plus',
                        'href' => route('h::e::create')
                    ]
                ]
            ]
        ]);
    }

}
