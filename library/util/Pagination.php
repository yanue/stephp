<?php
namespace Library\Util;

use Library\Core\View;

/**
 * Class Pagination
 * @package Library\Util
 *
 */

/**
 * 用法：
 * 1. fst step
 * in xxxAction:
 * get param
 * $page = $this->uri->getParam('p', 0);
 * $curpage = $page <= 0 ? 0 : $page - 1;
 * $limit = 20;
 * $count // get total count
 * Paged::instance($this->view->showPage($page, $count, $limit));
 * 2. sec step
 * in view xxx.php file
 * <?php \App\Admin\Lib\Paged::display($this);?>
 */
class Pagination
{
    protected static $view = null;


    public function __construct(View $view)
    {
        self::$view = $view;
    }

    public static function instance(View $view)
    {
        static $obj;
        if (!$obj) $obj = new self($view);
        return $obj;
    }


    // set pages to view

    public function showPage($page, $count, $limit = 14, $range = 10)
    {
        $total = ceil($count / $limit);
        // 总页数
        $page = $page > $total ? $total : $page;
        $page = $page <= 0 ? 1 : $page;
        // 上一页
        if ($page > 1) {
            self::$view->previous = $page - 1;
            self::$view->first = 1;
        }
        // 下一页
        if ($total > $page) {
            self::$view->next = $page + 1;
            self::$view->last = $total;
        }
        self::$view->current = $page;
        // $range表示显示条数的一半-1
        if ($page <= $range) {
            if ($total > $range * 2) {
                $pagesInRange = $this->getPagesInRange(1, $range * 2);
            } else {
                $pagesInRange = $this->getPagesInRange(1, $total);
            }

        } elseif ($total - $page < $range) {
            $pagesInRange = $this->getPagesInRange($total - $range * 2, $total);
        } else {
            $pagesInRange = $this->getPagesInRange($page - $range, $page + $range);
        }

        self::$view->pagesInRange = $pagesInRange;
        self::$view->total = $total;
        self::$view->page = $page;
        self::$view->perpage = $limit;
        self::$view->pageCount = $count;
    }

    // page range
    private function getPagesInRange($lowerBound, $upperBound)
    {
        $pages = array();
        for ($pageNumber = $lowerBound; $pageNumber <= $upperBound; $pageNumber++) {
            $pages [$pageNumber] = $pageNumber;
        }
        return $pages;
    }

    // display

    public static final function display(View $view)
    {

        $str = '';
        if (isset($view->total)) {

            if ($view->total) {
                $str .= '<span style="float: right;">总' . $view->pageCount . '条记录,总' . $view->total . '页,
        每页' . $view->perpage . '条,当前' . $view->total . '/' . $view->page . '页</span>';
            }

            // first
            if (isset($view->previous)) {
                $str .= ' <a href="' . $view->uri->setUrl(array('p' => $view->first), '', true) . '"> 首页 </a> ';
            } else {
                $str .= ' <span class="disabled">首页</span> ';
            }

            // previous
            if (isset($view->previous)) {
                $str .= ' <a href="' . $view->uri->setUrl(array('p' => $view->previous), '', true) . '"> &lt; 上一页  </a> | ';
            } else {
                $str .= ' <span class="disabled">&lt; 上一页</span> ';
            }

            // Numbered p links
            foreach ($view->pagesInRange as $page) {
                if ($page > 0) {
                    if ($page != $view->current) {
                        $str .= ' <a href="' . $view->uri->setUrl(array('p' => $page), '', true) . '"> ' . $page . ' </a> | ';
                    } else {
                        $str .= $view->current . ' | ';
                    }
                };
            }


            // next
            if (isset($view->next)) {
                $str .= ' <a href="' . $view->uri->setUrl(array('p' => $view->next), '', true) . '"> 下一页 &gt;  </a> ';
            } else {
                $str .= ' <span class="disabled">下一页 &gt; </span> ';
            }

            // last
            if (isset($view->next)) {
                $str .= ' <a href="' . $view->uri->setUrl(array('p' => $view->last), '', true) . '"> 尾页 </a> ';
            } else {
                $str .= ' <span class="disabled">尾页</span> ';
            }

            echo $str;

        }
    }

}