<?php

// 三级管理菜单
return [
    ['name'=>'系统设置', 'menus'=> [
        [
            'title' => '系统管理',
            'items' => [
                [
                    'title'           => '系统首页',
                    'url'             => 'index/index',
                ],
                [
                    'title'           => '系统设置',
                    'url'             => 'settings/form',
                ],
                [
                    'title'           => '友情链接',
                    'url'             => 'links/index',
                ],
                [
                    'title'           => '素材中心',
                    'url'             => 'file/index',
                ],
            ],
        ],

        [
            'title' => '系统管理员',
            'items' => [
                [
                    'title'           => '系统管理员',
                    'url'             => 'admin/index',
                ],
                [
                    'title'           => '管理员角色',
                    'url'             => 'adminRole/index',
                ],

                [
                    'title'           => '操作日志',
                    'url'             => 'oplog/index',
                ],
                [
                    'title'           => '登录日志',
                    'url'             => 'loginlog/index',
                ],
            ],
        ],
//        [
//            'title' => '广告管理',
//            'items' => [
//                [
//                    'title'           => '广告位置',
//                    'url'             => 'adpos/index',
//                ],
//                [
//                    'title'           => '广告列表',
//                    'url'             => 'ad/index',
//                ],
//            ],
//        ],
    ],
    ],
    ['name'=>'文档管理', 'menus'=> [
        [
            'title' => '文章管理',
            'items' => [
                [
                    'title'           => '文章列表',
                    'url'             => 'article/index',
                ],
                [
                    'title'           => '文章分类',
                    'url'             => 'articleCategory/index',
                ],
                [
                    'title'           => '文章标签',
                    'url'             => 'articleTags/index',
                ],
                [
                    'title'           => '文章评论',
                    'url'             => 'articleComment/index',
                ],
            ],
        ],
    ]
    ],

    ['name'=>'会员管理', 'menus'=> [
        [
            'title' => '会员管理',
            'items' => [
                [
                    'title'           => '会员管理',
                    'url'             => 'member/index',
                ],
                [
                    'title'           => '登录日志',
                    'url'             => 'memberLoginlog/index',
                ],
                [
                    'title'           => '财务记录',
                    'url'             => 'memberFinance/index',
                ],
            ],
        ],
    ]
    ]
];
