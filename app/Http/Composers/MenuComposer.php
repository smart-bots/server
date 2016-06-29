<?php

namespace SmartBots\Http\Composers;

use SmartBots\Hub;

class MenuComposer {

    public function compose($view) {
        $menu = ['Menu'];

        $menu['Dashboard'] = [
                'icon' => 'ti-home',
                'href' => route('h::dashboard')
            ];
        if (auth()->user()->can('view',Hub::findOrFail(session('currentHub')))) {
            $menu['Hub'] = [
                    'icon' => 'ti-settings',
                    'href' => route('h::edit')
                ];
        }

        if (auth()->user()->can('viewAllMembers',Hub::findOrFail(session('currentHub')))) {

            $menu['Member'] = [
                    'icon' => 'ti-user',
                    'sub' => [
                        'Member list' => [
                            'icon' => 'ti-menu-alt',
                            'href' => route('h::m::index')
                        ]
                    ]
                ];

            if (auth()->user()->can('addMembers',Hub::findOrFail(session('currentHub')))) {

                $menu['Member']['sub']['Add new member'] = [
                    'icon' => 'ti-plus',
                    'href' => route('h::m::create')
                ];
            }
        }

        $menu['Bot'] = [
                'icon' => 'ti-package',
                'sub' => [
                    'Bot list' => [
                        'icon' => 'ti-menu-alt',
                        'href' => route('h::b::index')
                    ]
                ]
            ];

        if (auth()->user()->can('addBots',Hub::findOrFail(session('currentHub')))) {

            $menu['Bot']['sub']['Add new bot'] = [
                'icon' => 'ti-plus',
                'href' => route('h::b::create')
            ];
        }

        $menu['Schedule'] = [
                'icon' => 'ti-timer',
                'sub' => [
                    'Schedule list' => [
                        'icon' => 'ti-menu-alt',
                        'href' => route('h::s::index')
                    ]
                ]
            ];

        if (auth()->user()->can('createSchedules',Hub::findOrFail(session('currentHub')))) {

            $menu['Schedule']['sub']['Add new schedule'] = [
                'icon' => 'ti-plus',
                'href' => route('h::s::create')
            ];
        }

        $menu['Event'] = [
                'icon' => 'ti-panel',
                'sub' => [
                    'Event list' => [
                        'icon' => 'ti-menu-alt',
                        'href' => route('h::e::index')
                    ]
                ]
            ];

        if (auth()->user()->can('createEvents',Hub::findOrFail(session('currentHub')))) {

            $menu['Event']['sub']['Add new event'] = [
                'icon' => 'ti-plus',
                'href' => route('h::e::create')
            ];
        }

        $menu['Automation'] = [
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
            ];

        if (auth()->user()->can('createAutomations',Hub::findOrFail(session('currentHub')))) {

            $menu['Automation']['sub']['Add new automation'] = [
                'icon' => 'ti-plus',
                'href' => route('h::a::create')
            ];
        }

        $view->withMenu($menu);
    }

}
