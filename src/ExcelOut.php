<?php

namespace Topfunction\Topfunction;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * ExcelOut
 * Class ExcelOut
 * @package Topfunction\Topfunction
 */
class ExcelOut
{
    public $file  = '';          // 文件名
    public $title = '';          // 表名
    public $head  = [];          // 表头
    public $list  = [];          // 数据


    public function __construct()
    {
        // 设置php超时时间及内存
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
    }


    private function getStyleArray($horizontal = Alignment::HORIZONTAL_CENTER, $vertical = Alignment::VERTICAL_CENTER)
    {
        return [
            'alignment' => [
                //水平居中
                'horizontal' => $horizontal,

                //垂直居中
                'vertical'   => $vertical,
            ],
        ];
    }


    /**
     * 保存
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function save()
    {
        if (empty($this->file)) {
            return [
                'errcode' => 1,
                'errmsg'  => 'file不能为空',
            ];
        }

        if (empty($this->title)) {
            return [
                'errcode' => 1,
                'errmsg'  => 'title不能为空',
            ];
        }

        $newExcel = new Spreadsheet();            //创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle($this->title);        //设置当前sheet的标题

        if (!empty($this->head)) {
            $list_new   = [];
            $list_new[] = $this->head;

            foreach ($this->list as $k => $v) {
                $tmp = [];
                foreach ($this->head as $k2 => $v2) {
                    $tmp[$k2] = $v[$k2];
                }
                $list_new[] = $tmp;
            }
        } else {
            $list_new = $this->list;
        }

        foreach ($list_new as $k => $v) {
            $v = array_values($v);
            foreach ($v as $k2 => $v2) {
                if ($k2 < 26) {
                    $col = chr(65 + $k2);
                } elseif ($k2 >= 26) {
                    $col = 'A' . chr(65 + $k2 - 26);
                }

                $objSheet->getColumnDimension($col)
                    ->setWidth(30);

                $objSheet->setCellValue($col . ($k + 1), " " . (new StringApp())->removeEmoji($v2));

                $newExcel->getActiveSheet()
                    ->getStyle($col . ($k + 1))
                    ->applyFromArray($this->getStyleArray());
            }
        }

        /*--------------下面是设置其他信息------------------*/
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=" . urlencode($this->file) . ".xls");
        header('Cache-Control: max-age=0');
        $objWriter = IOFactory::createWriter($newExcel, 'Xls');
        $objWriter->save('php://output');
    }

}
