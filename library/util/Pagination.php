<?php
namespace Library\Util;

use Library\Core\Plugin;
use Library\Core\Router;

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
 * Pagination::instance($this->view)->showPage($page, $count, $limit);
 * 2. sec step
 * in view xxx.php file
 * <?php \App\Admin\Lib\Paged::display($this);?>
 */
class Pagination extends Plugin
{
    public static function instance()
    {
        static $obj;
        if (!$obj) $obj = new self();
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
            $this->view->previous = $page - 1;
            $this->view->first = 1;
        }
        // 下一页
        if ($total > $page) {
            $this->view->next = $page + 1;
            $this->view->last = $total;
        }
        $this->view->current = $page;
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

        $this->view->pagesInRange = $pagesInRange;
        $this->view->total = $total;
        $this->view->page = $page;
        $this->view->perpage = $limit;
        $this->view->pageCount = $count;
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

    public final function display()
    {

        $str = '';
        if (isset($this->view->total)) {

            if ($this->view->total) {
                $str .= '<span style="float: right;">总' . $this->view->pageCount . '条记录,总' . $this->view->total . '页,
        每页' . $this->view->perpage . '条,当前' . $this->view->total . '/' . $this->view->page . '页</span>';
            }

            // first
            if (isset($this->view->previous)) {
                $str .= ' <a href="' . $this->uri->setUrl(array('p' => $this->view->first), '', true) . '"> 首页 </a> ';
            } else {
                $str .= ' <span class="disabled">首页</span> ';
            }

            // previous
            if (isset($this->view->previous)) {
                $str .= ' <a href="' . $this->uri->setUrl(array('p' => $this->view->previous), '', true) . '"> &lt; 上一页  </a> | ';
            } else {
                $str .= ' <span class="disabled">&lt; 上一页</span> ';
            }

            // Numbered p links
            foreach ($this->view->pagesInRange as $page) {
                if ($page > 0) {
                    if ($page != $this->view->current) {
                        $str .= ' <a href="' . $this->uri->setUrl(array('p' => $page), '', true) . '"> ' . $page . ' </a> | ';
                    } else {
                        $str .= $this->view->current . ' | ';
                    }
                };
            }
            // 去除多余|
            $str = rtrim($str, '| ');


            // next
            if (isset($this->view->next)) {
                $str .= ' <a href="' . $this->uri->setUrl(array('p' => $this->view->next), '', true) . '"> 下一页 &gt;  </a> ';
            } else {
                $str .= ' <span class="disabled">下一页 &gt; </span> ';
            }

            // last
            if (isset($this->view->next)) {
                $str .= ' <a href="' . $this->uri->setUrl(array('p' => $this->view->last), '', true) . '"> 尾页 </a> ';
            } else {
                $str .= ' <span class="disabled">尾页</span> ';
            }

            echo $str;

        }
    }

    public final function displayByRouter()
    {
        $path = Router::$originalSegment; // 获取路由前path部分

        $str = '';
        if (isset($this->view->total)) {

            if ($this->view->total) {
                $str .= '<span style="float: right;">总' . $this->view->pageCount . '条记录,总' . $this->view->total . '页,
        每页' . $this->view->perpage . '条,当前' . $this->view->total . '/' . $this->view->page . '页</span>';
            }

// first
            if (isset($this->view->previous)) {
                $str .= ' <a href="' . $this->uri->baseUrl($path) . '?p=' . $this->view->first . '"> 首页 </a> ';
            } else {
                $str .= ' <span class="disabled">首页</span> ';
            }

// previous
            if (isset($this->view->previous)) {
                $str .= ' <a href="' . $this->uri->baseUrl($path) . '?p=' . $this->view->previous . '"> &lt; 上一页  </a> | ';
            } else {
                $str .= ' <span class="disabled">&lt; 上一页</span> ';
            }

// Numbered p links
            foreach ($this->view->pagesInRange as $page) {
                if ($page > 0) {
                    if ($page != $this->view->current) {
                        $str .= ' <a href="' . $this->uri->baseUrl($path) . '?p=' . $page . '"> ' . $page . ' </a> | ';
                    } else {
                        $str .= $this->view->current . ' | ';
                    }
                };
            }


            // next
            if (isset($this->view->next)) {
                $str .= ' <a href="' . $this->uri->baseUrl($path) . '?p=' . $this->view->next . '"> 下一页 &gt;  </a> ';
            } else {
                $str .= ' <span class="disabled">下一页 &gt; </span> ';
            }

            // last
            if (isset($this->view->next)) {
                $str .= ' <a href="' . $this->uri->baseUrl($path) . '?p=' . $this->view->last . '"> 尾页 </a> ';
            } else {
                $str .= ' <span class="disabled">尾页</span> ';
            }

            echo $str;

        }
    }

}