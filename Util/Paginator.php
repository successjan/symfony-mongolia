<?php

/**
 * Paginator
 *
 * @author Satjan
 */
class Paginator {

    protected $currentpage;
    protected $perPage;
    protected $numpages;
    protected $totalCount;
    protected $offset;
    protected $url;

    function __construct($pagination, $url) {
        $this->totalCount = $pagination['totalCount'];
        $this->currentpage = $pagination['page'];
        $this->perPage = $pagination['perPage'];
        $this->url = $url;
        $this->getNumPages();
        $this->calculateOffset();
    }

    private function getNumPages() {
        if (($this->perPage < 1) || ($this->perPage > $this->totalCount)) {
            $this->numpages = 1;
        } else {
            $restItemsNum = $this->totalCount % $this->perPage;
            $restItemsNum > 0 ? $this->numpages = intval($this->totalCount / $this->perPage) + 1 : $this->numpages = intval($this->totalCount / $this->perPage);
        }
    }

    private function calculateOffset() {
        $this->offset = ($this->currentpage - 1) * $this->perPage;
    }

    public function renderPaginator($criteria = null) {

        $html = '';
        if (!is_null($this->totalCount) && $this->totalCount > $this->perPage) {
            $criteriaTxt = '';
            if (!is_null($criteria)) {
                foreach ($criteria as $key => $value) {
                    if (!is_null($value) && $value != 0) {
                        $criteriaTxt .= '&' . $key . '=' . $value;
                    }
                }
            }

            $links = 5;
            $last = ceil($this->totalCount / $this->perPage);
            $start = ( ( $this->currentpage - $links ) > 0 ) ? $this->currentpage - $links : 1;
            $end = ( ( $this->currentpage + $links ) < $last ) ? $this->currentpage + $links : $last;

            $html = '<div class="dataTables_paginate paging_simple_numbers" id="table_paginate">';
            $html .= '<a href="/' . $this->url . '?page=' . ($this->currentpage - 1) . $criteriaTxt . '" class="paginate_button previous' . ($this->currentpage == 1 ? ' disabled' : '') . '" aria-controls="history-table" data-dt-idx="0" tabindex="0" id="history-table_previous">&lt;</a>';
            $html .= '<span>';

            if ($start > 1) {
                $html .= '<a href="/' . $this->url . '?page=1' . $criteriaTxt . '" class="paginate_button" aria-controls="history-table" data-dt-idx="1" tabindex="0">1</a>';
                $html .= '<span>...</span>';
            }

            for ($i = $start; $i <= $end; $i++) {
                $class = '';
                if ($i == $this->currentpage) {
                    $class = 'current';
                }
                $html .= '<a href="/' . $this->url . '?page=' . $i . $criteriaTxt . '" class="paginate_button ' . $class . '" aria-controls="history-table" data-dt-idx="' . $i . '" tabindex="0">' . $i . '</a>';
            }

            if ($end < $last) {
                $html .= '<span>...</span>';
                $html .= '<a href="/' . $this->url . '?page=' . $this->numpages . $criteriaTxt . '" class="paginate_button next" aria-controls="history-table" data-dt-idx="2" tabindex="0" id="history-table_next">' . $this->numpages . '</a>';
            }

            $html .= '</span>';
            $html .= '<a href="/' . $this->url . '?page=' . ($this->currentpage + 1) . $criteriaTxt . '" class="paginate_button next' . ($this->currentpage == $this->numpages ? ' disabled' : '') . '" aria-controls="history-table" data-dt-idx="2" tabindex="0" id="history-table_next">&gt;</a>';
            $html .= '</div>';
        }


        return $html;
    }

    public function getLimit() {
        return $this->perPage;
    }

    public function getOffset() {
        return $this->offset;
    }

}