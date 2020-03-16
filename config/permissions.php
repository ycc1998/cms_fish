<?php
return [
    // 公共权限
    'public' => [
        'index/index',
        'login/index',
        'login/out',
        'login/check',
        'admin/changePassword',
        'upload/editorup',
        'upload/uploadify',
        'upload/up',
        'upload/index',
        'loginlog/activeLoginLog',
    ],

    // 相同权限
    'equal' => [
        // '控制器/操作' => ['控制器/操作', '控制器/操作']
    ],

    // 一级菜单，顶部显示
    'tops' => [
        [
            'id' => '101',
            'name' => '系统管理'
        ],
        [
            'id' => '102',
            'name' => '文档管理'
        ],
    ],


    // 一级菜单对应的左侧菜单
    'permissions' => [
        '101' => [
            [
                'name' => '系统设置',
                'permissions' => [
                    'settings/form'    => '系统设置',
                ],
            ],
            [
                'name' => '友情链接',
                'permissions' => [
                    'links/index'    => '友情链接列表',
                    'links/form'     => '添加 / 编辑友情链接',
                    'links/delete'   => '删除友情链接',
                    'links/doAction' => '批量操作', // 批量操作友情链接
                ],
            ],
            [
                'name' => '素材中心',
                'permissions' => [
                    'file/index'       => '素材列表',
                    'file/download'    => '下载素材',
                    'file/delete'      => '删除素材',
                ],
            ],

//            [
//                'name' => '广告位管理',
//                'permissions' => [
//                    'adpos/index'    => '广告位列表',
//                    'adpos/form'     => '添加 / 编辑广告位',
//                    'adpos/delete'   => '删除广告位',
//                    'adpos/doAction' => '批量操作',
//                ],
//            ],
//
//            [
//                'name' => '广告管理',
//                'permissions' => [
//                    'ad/index'    => '广告列表',
//                    'ad/form'     => '添加 / 编辑广告',
//                    'ad/delete'   => '删除广告',
//                    'ad/doAction' => '批量操作',
//                ],
//            ],

            [
                'name' => '会员管理',
                'permissions' => [
                    'member/index'   => '会员列表',
                    'member/preview' => '查看会员',
                    'member/charge'  => '会员充值',
                    'member/delete'  => '删除会员',
                ],

            ],

            [
                'name' => '会员登录日志',
                'permissions' => [
                    'memberLoginlog/index' => '查看会员登录日志',
                ],
            ],

            [
                'name' => '会员财务记录',
                'permissions' => [
                    'MemberFinance/index' => '查看会员财务记录',
                ],
            ],

            [
                'name' => '系统管理员',
                'permissions' => [
                    'admin/index'  => '管理员列表',
                    'admin/form'   => '添加 / 编辑管理员',
                    'admin/delete' => '删除管理员',
                ],

            ],
            [
                'name' => '管理员角色',
                'permissions' => [
                    'adminRole/index'  => '管理员角色列表',
                    'adminRole/form'   => '添加 / 编辑管理员角色',
                    'adminRole/delete' => '删除管理员角色',
                ],

            ],
            [
                'name' => '系统操作日志',
                'permissions' => [
                    'oplog/index' => '查看操作日志',
                ],
            ],

            [
                'name' => '系统登录日志',
                'permissions' => [
                    'loginlog/index'   => '查看登录日志',
                ],
            ],
        ],
        '102' => [
            [
                'name' => '文章列表',
                'permissions' => [
                    'article/index'    => '文章列表',
                    'article/form'     => '添加 / 编辑文章',
                    'article/tags'     => '文章标签',
                    'article/delete'   => '删除文章',
                    'article/doAction' => '批量操作',
                ],
            ],

            [
                'name' => '文章分类',
                'permissions' => [
                    'articleCategory/index'    => '文章分类列表',
                    'articleCategory/form'     => '添加 / 编辑文章分类',
                    'articleCategory/delete'   => '删除文章分类',
                    'articleCategory/doAction' => '更新文章分类排序',
                ],
            ],
            [
                'name' => '文章标签',
                'permissions' => [
                    'articleTags/index'    => '文章标签列表',
                    'articleTags/delete'   => '删除文章标签',
                ],
            ],
            [
                'name' => '文章评论',
                'permissions' => [
                    'articleComment/index'    => '文章评论列表',
                    'articleComment/delete'   => '删除文章评论',
                    'articleComment/doAction' => '批量操作',
                    'articleComment/preview'  => '查看评论',
                ],
            ],
        ]
    ],
];
