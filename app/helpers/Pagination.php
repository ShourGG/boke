<?php
/**
 * Pagination Helper
 * 分页助手类 - 生成分页导航和计算分页数据
 */
class Pagination
{
    private $currentPage;
    private $totalItems;
    private $itemsPerPage;
    private $totalPages;
    private $baseUrl;
    private $queryParams;
    
    public function __construct($currentPage, $totalItems, $itemsPerPage = 10, $baseUrl = '', $queryParams = [])
    {
        $this->currentPage = max(1, intval($currentPage));
        $this->totalItems = max(0, intval($totalItems));
        $this->itemsPerPage = max(1, intval($itemsPerPage));
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->queryParams = $queryParams;
        
        // 确保当前页不超过总页数
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
     * 获取当前页码
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }
    
    /**
     * 获取总页数
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }
    
    /**
     * 获取总项目数
     */
    public function getTotalItems()
    {
        return $this->totalItems;
    }
    
    /**
     * 获取每页项目数
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }
    
    /**
     * 获取当前页的偏移量（用于SQL LIMIT）
     */
    public function getOffset()
    {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }
    
    /**
     * 检查是否有上一页
     */
    public function hasPrevious()
    {
        return $this->currentPage > 1;
    }
    
    /**
     * 检查是否有下一页
     */
    public function hasNext()
    {
        return $this->currentPage < $this->totalPages;
    }
    
    /**
     * 获取上一页页码
     */
    public function getPreviousPage()
    {
        return $this->hasPrevious() ? $this->currentPage - 1 : null;
    }
    
    /**
     * 获取下一页页码
     */
    public function getNextPage()
    {
        return $this->hasNext() ? $this->currentPage + 1 : null;
    }
    
    /**
     * 获取页码范围（用于显示页码链接）
     */
    public function getPageRange($range = 5)
    {
        $start = max(1, $this->currentPage - floor($range / 2));
        $end = min($this->totalPages, $start + $range - 1);
        
        // 调整起始页，确保显示足够的页码
        if ($end - $start + 1 < $range) {
            $start = max(1, $end - $range + 1);
        }
        
        return range($start, $end);
    }
    
    /**
     * 生成页面URL
     */
    public function getPageUrl($page)
    {
        $params = $this->queryParams;
        $params['page'] = $page;
        
        $queryString = http_build_query($params);
        
        return $this->baseUrl . ($queryString ? '?' . $queryString : '');
    }
    
    /**
     * 生成Bootstrap样式的分页HTML
     */
    public function render($options = [])
    {
        $options = array_merge([
            'show_info' => true,
            'show_first_last' => true,
            'show_prev_next' => true,
            'range' => 5,
            'class' => 'pagination justify-content-center',
            'size' => '', // sm, lg
            'prev_text' => '上一页',
            'next_text' => '下一页',
            'first_text' => '首页',
            'last_text' => '末页'
        ], $options);
        
        if ($this->totalPages <= 1) {
            return $options['show_info'] ? $this->renderInfo() : '';
        }
        
        $html = '<nav aria-label="分页导航">';
        
        // 显示分页信息
        if ($options['show_info']) {
            $html .= $this->renderInfo();
        }
        
        $html .= '<ul class="' . $options['class'];
        if ($options['size']) {
            $html .= ' pagination-' . $options['size'];
        }
        $html .= '">';
        
        // 首页链接
        if ($options['show_first_last'] && $this->currentPage > 1) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getPageUrl(1) . '">' . $options['first_text'] . '</a>';
            $html .= '</li>';
        }
        
        // 上一页链接
        if ($options['show_prev_next'] && $this->hasPrevious()) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getPageUrl($this->getPreviousPage()) . '">' . $options['prev_text'] . '</a>';
            $html .= '</li>';
        }
        
        // 页码链接
        $pageRange = $this->getPageRange($options['range']);
        foreach ($pageRange as $page) {
            $isActive = ($page == $this->currentPage);
            $html .= '<li class="page-item' . ($isActive ? ' active' : '') . '">';
            
            if ($isActive) {
                $html .= '<span class="page-link">' . $page . '</span>';
            } else {
                $html .= '<a class="page-link" href="' . $this->getPageUrl($page) . '">' . $page . '</a>';
            }
            
            $html .= '</li>';
        }
        
        // 下一页链接
        if ($options['show_prev_next'] && $this->hasNext()) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getPageUrl($this->getNextPage()) . '">' . $options['next_text'] . '</a>';
            $html .= '</li>';
        }
        
        // 末页链接
        if ($options['show_first_last'] && $this->currentPage < $this->totalPages) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getPageUrl($this->totalPages) . '">' . $options['last_text'] . '</a>';
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</nav>';
        
        return $html;
    }
    
    /**
     * 渲染分页信息
     */
    private function renderInfo()
    {
        $start = ($this->currentPage - 1) * $this->itemsPerPage + 1;
        $end = min($this->currentPage * $this->itemsPerPage, $this->totalItems);
        
        return '<div class="pagination-info text-muted mb-3">' .
               "显示第 {$start} 到 {$end} 项，共 {$this->totalItems} 项" .
               '</div>';
    }
    
    /**
     * 生成简单的分页HTML（仅上一页/下一页）
     */
    public function renderSimple($options = [])
    {
        $options = array_merge([
            'prev_text' => '← 上一页',
            'next_text' => '下一页 →',
            'class' => 'pagination-simple d-flex justify-content-between'
        ], $options);
        
        if ($this->totalPages <= 1) {
            return '';
        }
        
        $html = '<nav class="' . $options['class'] . '">';
        
        // 上一页
        if ($this->hasPrevious()) {
            $html .= '<a href="' . $this->getPageUrl($this->getPreviousPage()) . '" class="btn btn-outline-primary">' . $options['prev_text'] . '</a>';
        } else {
            $html .= '<span class="btn btn-outline-secondary disabled">' . $options['prev_text'] . '</span>';
        }
        
        // 下一页
        if ($this->hasNext()) {
            $html .= '<a href="' . $this->getPageUrl($this->getNextPage()) . '" class="btn btn-outline-primary">' . $options['next_text'] . '</a>';
        } else {
            $html .= '<span class="btn btn-outline-secondary disabled">' . $options['next_text'] . '</span>';
        }
        
        $html .= '</nav>';
        
        return $html;
    }
    
    /**
     * 获取分页数据数组（用于API）
     */
    public function toArray()
    {
        return [
            'current_page' => $this->currentPage,
            'total_pages' => $this->totalPages,
            'total_items' => $this->totalItems,
            'items_per_page' => $this->itemsPerPage,
            'has_previous' => $this->hasPrevious(),
            'has_next' => $this->hasNext(),
            'previous_page' => $this->getPreviousPage(),
            'next_page' => $this->getNextPage(),
            'page_range' => $this->getPageRange(),
            'offset' => $this->getOffset()
        ];
    }
}
