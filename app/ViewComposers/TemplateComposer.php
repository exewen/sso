<?php

namespace App\ViewComposers;
use Illuminate\Contracts\View\View;
use Config;
use Request;

class TemplateComposer
{
    public function compose(View $view)
    {
        if($view->name()=='common.template.sidebar') {
            $this->configureLeftSideMenu($view);
        }
    }

    /**
     * 配置左边菜单
     *
     * @param $view
     */
    private function configureLeftSideMenu($view)
    {
        $menus = \Permission::menusTemplate()['menus'];
        $url_path = Request::path();

        $is_open = false;
        $new_menus = [];
        foreach ($menus as $menu){
            if($menu['roles']) $menu['is_open'] = false;

            $new_sub_menus = [];
            foreach ($menu['menus'] as $sub_menu)
            {
                $sub_menu['is_active']=false;
                $path_info = parse_url($sub_menu['link']);
                if(starts_with('/'.$url_path,$path_info['path']) && !$is_open)
                {
                    $is_open = true;
                    $menu['is_open'] = true;
                    $sub_menu['is_active']=true;
                }
                $new_sub_menus[]=$sub_menu;
            }

            //若是子页面都没有权限,则不展示父目录
            if ($new_sub_menus){
                $menu['menus'] = $new_sub_menus;
                $new_menus[]=$menu;
            }

        }
        $view->with(['menus'=>$new_menus]);
    }
}
