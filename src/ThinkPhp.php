<?php

namespace Topfunction\Topfunction;

/**
 * ThinkPhp
 * Class ThinkPhp
 * @package Topfunction\Topfunction
 */
class ThinkPhp
{

    /**
     * 列表（带分页）
     * @param       $app
     * @param array $param
     * @param array $append
     * @return array
     */
    public function getListPage($app, $param = [], $append = [])
    {
        $row  = $param['row'] ?? 20;
        $curr = $param['curr'] ?? 1;

        $list_page = $app->paginate([
            'list_rows' => $row,
            'page'      => $curr
        ])
            ->each(function ($item) use ($append) {
                $item->append($append);

                return $item;
            })
            ->toArray();

        return [
            'count' => $list_page['total'],
            'row'   => $list_page['per_page'],
            'curr'  => $list_page['current_page'],
            'pages' => $list_page['last_page'],
            'list'  => $list_page['data'],
        ];
    }


}