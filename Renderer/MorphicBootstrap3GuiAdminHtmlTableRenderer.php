<?php


namespace GuiAdminTable\Renderer;


class MorphicBootstrap3GuiAdminHtmlTableRenderer extends Bootstrap3GuiAdminHtmlTableRenderer
{
    public function __construct()
    {
        parent::__construct();
        $this->addHtmlAttributes("table", [
            'class' => "morphic-table",
        ]);
    }


    protected function getHeaderColClasses($col)
    {
        $classes = parent::getHeaderColClasses($col);
        if (true === $this->useSort) {
            $classes[] = "morphic-table-sort";
            $classes[] = "morphic";
        }
        return $classes;
    }

    protected function getHeaderColAttributes($col)
    {
        $attributes = parent::getHeaderColAttributes($col);

        if (true === $this->useSort) {
            $dir = "null";
            if (array_key_exists($col, $this->headersOrder)) {
                $v = $this->headersOrder[$col];
                if (true === $v) {
                    $dir = "asc";
                } elseif (false === $v) {
                    $dir = "desc";
                }
            }
            if (null !== $dir) {
                $attributes["data-sort-dir"] = $dir;
            }
            $attributes["data-column"] = $col;
        }
        return $attributes;
    }
}