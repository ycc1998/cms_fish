<?php
return [
    'actions'  =>  [
        'form'        => ['添加','编辑'],
        'delete'      => ['删除'],
        'doaction'    => ['批量操作'],
        'preview'     => ['查看/预览'],
        'charge'      => ['充值'],
        'index'       => '',
    ],

    'settings'		  =>  ['系统设置',
        'actions' => [
            'form' => '#系统设置',
        ]
    ],

    'admin'			  =>  ['系统管理员',
        'actions' => [
            'changepassword' => '#修改我的密码',
        ],
    ],

    'adminRole' 	  	=>  ['管理员角色'],

    'links'	    		=>  ['友情链接'],

    'file'	    		=>  ['文件素材'],

    'adpos'	            =>  ['广告位管理'],

    'ad'	    		=>  ['广告管理'],

    'member'	        =>  ['会员'],

    'articleTags'	    =>  ['文章标签'],

    'articleComment'    =>  ['文章评论',
        'actions' => [
            'preview' => '#查看文章评论',
        ],
    ],

    'article'		  =>  ['文章'],

    'articleCategory' =>  ['文章分类',
        'actions'  => [
            'doaction' => '#更新文章分类排序',
        ],
    ],

];