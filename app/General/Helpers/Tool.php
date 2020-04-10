<?php
if (!function_exists('starts_with')) {
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string $haystack
     * @param string|array $needles
     * @return bool
     */
    function starts_with($haystack, $needles)
    {
        return strpos($needles, $haystack) === 0 ? true : false;
    }
}

/**
 * 根据 URI 获取路径的面包屑导航
 */
function getPathCate($uri)
{
    if (!$uri || empty($uri)) return '';
    $uri = explode('?', $uri);  //去除查询字符串
    $uri = $uri[0];

    $path = [];
    $path[$uri][] = "<li><a href='javascript:void(0)'>Home</a></li>";
    $templates = \Permission::menusTemplate();
    foreach ($templates['menus'] as $key => $menus) {
        if (is_array($menus['menus']) && !empty($menus['menus'])) {
            foreach ($menus['menus'] as $k => $menu) {
                $menu_link = $menu['link'];
                $menu_link = explode('?', $menu_link);  //去除查询字符串
                if ($menu_link[0] == $uri) {
                    $path[$uri][] = "<li><a href='javascript:void(0)'>" . $menus['title'] . "</a></li>";
                    $path[$uri][] = "<li><a href={$menu['link']}  class='active'><strong>" .$menu['title']. "</strong></a></li>";
                    break 2;
                }
            }
        }
    }
    return implode('', $path[$uri]);
}

/**
 * 根据 path 获取菜单的名称
 * @param $path
 * @return string
 */
function getMenuTitle($path)
{
    if (!$path) return '';
    $templates = \Permission::menusTemplate();
    $path = '/'.$path;
    foreach ($templates['menus'] as $key => $menus) {
        if (is_array($menus['menus']) && !empty($menus['menus'])) {
            foreach ($menus['menus'] as $k => $menu) {
                if ($menu['link'] == $path) {
                    return $menu['title'];
                }
            }
        }
    }
}


if (!function_exists('validate_heading')) {
    /**
     * 验证表格头
     * @param \Maatwebsite\Excel\Readers\LaravelExcelReader $reader
     * @param array $heading
     * @return array
     */
    function validate_heading(\Maatwebsite\Excel\Readers\LaravelExcelReader $reader, array $heading)
    {
        $reader = clone $reader; // 这里的clone非常重要
        try {
            $header_line = $reader->select($heading)->first();
            if (!$header_line) {
                return array('status' => false, 'errMsg' => '表格信息为空或者无效');
            }
            $header_line = $header_line->toArray();
        } catch (Exception $e) {
            return array('status' => false, 'errMsg' => $e->getMessage());
        }

        foreach ($heading as $key) {
            if (!array_key_exists($key, $header_line)) {
                return array("status" => false, 'errMsg' => "表格缺少头信息：" . $key);
            }
        }
        return ['status' => true];
    }
}
