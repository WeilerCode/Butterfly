<?php

namespace Weiler\Butterfly\Commands;

use Illuminate\Console\Command;
use Weiler\Butterfly\Models\AdminMenu;
use Weiler\Butterfly\Models\User;
use Weiler\Butterfly\Models\UserAdminGroup;
use Weiler\Butterfly\Models\UserMemberGroup;

class Init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'butterfly:init {--username=root} {--password=123123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Butterfly init';

    /**
     * 默认用户名
     * @var string
     */
    private $username = "root";
    /**
     * 默认密码
     * @var string
     */
    private $password = "123123";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('username'))
            $this->username = $this->option('username');
        if ($this->option('password'))
            $this->password = $this->option('password');
        // migrate
        $this->call('migrate:refresh');
        $this->createMenu();
        $this->createUser();
        echo "---------------后台账户-------------\r\n";
        echo "用户名:\t{$this->username}\r\n";
        echo "密码:\t{$this->password}\r\n";
    }

    /**
     * 创建后台用户
     */
    private function createUser() {
        //创建默认账户
        UserAdminGroup::create([
            'name'              =>              '超级管理员',
            'lv'                =>              '1',
            'color'             =>              '#c41313',
            'permissions'       =>              NULL
        ]);
        UserMemberGroup::create([
            'name'              =>              '会员',
            'lv'                =>              '0',
            'color'             =>              '#c41313',
            'permissions'       =>              NULL
        ]);
        User::create([
            'type'              =>              'system',
            'lv'                =>              '1',
            'name'              =>              $this->username,
            'realName'          =>              'Root',
            'thumb'             =>              '',
            'email'             =>              '',
            'phone'             =>              '',
            'verify'            =>              1,
            'verifyTime'        =>              time(),
            'groupID'           =>              1,
            'password'          =>              bcrypt($this->password)
        ]);
    }

    /**
     * 创建目录
     */
    private function createMenu() {
        /*
         * 排序
         * 升序排序
         */
        $order = [
            'index'         =>      0,  //控制面板
            'adminMange'    =>      3,  //后台管理
            'member'        =>      2,  //用户管理
            'me'            =>      1   //我的面板
        ];

        $menu = [
            /**
             * 仪表盘
             */
            [
                'id'            =>      1,
                'name'          =>      'menu.index.index',
                'parentID'      =>      0,
                'routeName'     =>      'admin-index',
                'listOrder'     =>      $order['index'],
                'display'       =>      1,
                'icon'          =>      'fa fa-dashboard'
            ],
            /**
             * Me
             */
            [
                'id'            =>      2,
                'name'          =>      'menu.me.index',
                'parentID'      =>      0,
                'routeName'     =>      'admin-me',
                'listOrder'     =>      $order['me'],
                'display'       =>      1,
                'icon'          =>      'fa fa-user-secret'
            ],
            [
                'id'            =>      3,
                'name'          =>      'menu.me.edit',
                'parentID'      =>      2,
                'routeName'     =>      'admin-me-update',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      4,
                'name'          =>      'menu.me.upload',
                'parentID'      =>      2,
                'routeName'     =>      'admin-me-uploadImg',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            /**
             * 后台管理
             */
            [
                'id'            =>      5,
                'name'          =>      'menu.manage.title',
                'parentID'      =>      0,
                'routeName'     =>      '',
                'listOrder'     =>      $order['adminMange'],
                'display'       =>      1,
                'icon'          =>      'fa fa-th-large'
            ],
            // 目录管理
            [
                'id'            =>      6,
                'name'          =>      'menu.manage.menu.index',
                'parentID'      =>      5,
                'routeName'     =>      'admin-manage-menu',
                'listOrder'     =>      0,
                'display'       =>      1,
                'icon'          =>      'fa fa-circle-o'
            ],
            [
                'id'            =>      7,
                'name'          =>      'menu.manage.menu.add',
                'parentID'      =>      6,
                'routeName'     =>      'admin-manage-menu-add',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      8,
                'name'          =>      'menu.manage.menu.add-post',
                'parentID'      =>      7,
                'routeName'     =>      'admin-manage-menu-add-post',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      9,
                'name'          =>      'menu.manage.menu.edit',
                'parentID'      =>      6,
                'routeName'     =>      'admin-manage-menu-edit',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      10,
                'name'          =>      'menu.manage.menu.edit-post',
                'parentID'      =>      9,
                'routeName'     =>      'admin-manage-menu-edit-post',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      11,
                'name'          =>      'menu.manage.menu.del',
                'parentID'      =>      6,
                'routeName'     =>      'admin-manage-menu-del',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      12,
                'name'          =>      'menu.manage.menu.display',
                'parentID'      =>      6,
                'routeName'     =>      'admin-manage-menu-display',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      30,
                'name'          =>      'menu.manage.menu.sort',
                'parentID'      =>      6,
                'routeName'     =>      'admin-manage-menu-sort',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            // 权限管理
            [
                'id'            =>      13,
                'name'          =>      'menu.manage.permissions.index',
                'parentID'      =>      5,
                'routeName'     =>      'admin-manage-permissions',
                'listOrder'     =>      0,
                'display'       =>      1,
                'icon'          =>      'fa fa-circle-o'
            ],
            [
                'id'            =>      14,
                'name'          =>      'menu.manage.permissions.add-group',
                'parentID'      =>      13,
                'routeName'     =>      'admin-manage-permissions-add-group',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      15,
                'name'          =>      'menu.manage.permissions.add-group-post',
                'parentID'      =>      14,
                'routeName'     =>      'admin-manage-permissions-add-group-post',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      16,
                'name'          =>      'menu.manage.permissions.edit-group',
                'parentID'      =>      13,
                'routeName'     =>      'admin-manage-permissions-edit-group',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      17,
                'name'          =>      'menu.manage.permissions.edit-group-post',
                'parentID'      =>      16,
                'routeName'     =>      'admin-manage-permissions-edit-group-post',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      18,
                'name'          =>      'menu.manage.permissions.del-group',
                'parentID'      =>      13,
                'routeName'     =>      'admin-manage-permissions-del-group',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      19,
                'name'          =>      'menu.manage.permissions.permissions',
                'parentID'      =>      13,
                'routeName'     =>      'admin-manage-permissions-permissions',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      20,
                'name'          =>      'menu.manage.permissions.permissions-post',
                'parentID'      =>      19,
                'routeName'     =>      'admin-manage-permissions-permissions-post',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            // 用户管理
            [
                'id'            =>      21,
                'name'          =>      'menu.manage.member.index',
                'parentID'      =>      5,
                'routeName'     =>      'admin-manage-member',
                'listOrder'     =>      0,
                'display'       =>      1,
                'icon'          =>      'fa fa-circle-o'
            ],
            [
                'id'            =>      22,
                'name'          =>      'menu.manage.member.add',
                'parentID'      =>      21,
                'routeName'     =>      'admin-manage-member-add',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      23,
                'name'          =>      'menu.manage.member.add-post',
                'parentID'      =>      22,
                'routeName'     =>      'admin-manage-member-add-post',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      24,
                'name'          =>      'menu.manage.member.edit',
                'parentID'      =>      21,
                'routeName'     =>      'admin-manage-member-edit',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      25,
                'name'          =>      'menu.manage.member.edit-post',
                'parentID'      =>      24,
                'routeName'     =>      'admin-manage-member-edit-post',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      26,
                'name'          =>      'menu.manage.member.del',
                'parentID'      =>      21,
                'routeName'     =>      'admin-manage-member-del',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      27,
                'name'          =>      'menu.manage.member.upload',
                'parentID'      =>      21,
                'routeName'     =>      'admin-manage-member-uploadImg',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            // 后台日志
            [
                'id'            =>      28,
                'name'          =>      'menu.manage.log.index',
                'parentID'      =>      5,
                'routeName'     =>      'admin-manage-log',
                'listOrder'     =>      0,
                'display'       =>      1,
                'icon'          =>      'fa fa-circle-o'
            ],
            /**
             * 会员管理
             */
            [
                'id'            =>      29,
                'name'          =>      'menu.member.title',
                'parentID'      =>      0,
                'routeName'     =>      '',
                'listOrder'     =>      $order['member'],
                'display'       =>      1,
                'icon'          =>      'fa fa-group'
            ],
            // 用户管理
            [
                'id'            =>      31,
                'name'          =>      'menu.member.member.index',
                'parentID'      =>      29,
                'routeName'     =>      'admin-member-member',
                'listOrder'     =>      0,
                'display'       =>      1,
                'icon'          =>      'fa fa-circle-o'
            ],
            [
                'id'            =>      32,
                'name'          =>      'menu.member.member.add',
                'parentID'      =>      31,
                'routeName'     =>      'admin-member-member-add',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      33,
                'name'          =>      'menu.member.member.add-post',
                'parentID'      =>      32,
                'routeName'     =>      'admin-member-member-add-post',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      34,
                'name'          =>      'menu.member.member.edit',
                'parentID'      =>      31,
                'routeName'     =>      'admin-member-member-edit',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      35,
                'name'          =>      'menu.member.member.edit-post',
                'parentID'      =>      34,
                'routeName'     =>      'admin-member-member-edit-post',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      36,
                'name'          =>      'menu.member.member.del',
                'parentID'      =>      31,
                'routeName'     =>      'admin-member-member-del',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      37,
                'name'          =>      'menu.member.member.upload',
                'parentID'      =>      31,
                'routeName'     =>      'admin-member-member-uploadImg',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            // 会员分组
            [
                'id'            =>      38,
                'name'          =>      'menu.member.group.index',
                'parentID'      =>      29,
                'routeName'     =>      'admin-member-group',
                'listOrder'     =>      0,
                'display'       =>      1,
                'icon'          =>      'fa fa-circle-o'
            ],
            [
                'id'            =>      39,
                'name'          =>      'menu.member.group.add',
                'parentID'      =>      38,
                'routeName'     =>      'admin-member-group-add',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      40,
                'name'          =>      'menu.member.group.add-post',
                'parentID'      =>      39,
                'routeName'     =>      'admin-member-group-add-post',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      41,
                'name'          =>      'menu.member.group.edit',
                'parentID'      =>      38,
                'routeName'     =>      'admin-member-group-edit',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      42,
                'name'          =>      'menu.member.group.edit-post',
                'parentID'      =>      41,
                'routeName'     =>      'admin-member-group-edit-post',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
            [
                'id'            =>      43,
                'name'          =>      'menu.member.group.del',
                'parentID'      =>      38,
                'routeName'     =>      'admin-member-group-del',
                'listOrder'     =>      0,
                'display'       =>      0,
                'icon'          =>      ''
            ],
        ];

        foreach ($menu as $v) {
            AdminMenu::create($v);
        }
    }
}
