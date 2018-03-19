<?php


namespace GuiAdminTable\Renderer;


use GuiAdminTable\Exception\GuiAdminTableException;

class MorphicBootstrap3GuiAdminHtmlTableRenderer extends Bootstrap3GuiAdminHtmlTableRenderer
{


    protected $searchColumnHelpers;

    public function __construct()
    {
        parent::__construct();
        $this->addHtmlAttributes("table", [
            'class' => "morphic-table",
        ]);
        $this->searchColumnHelpers = [];
    }

    public function render()
    {
        if ($this->searchColumnHelpers) {
            foreach ($this->searchColumnHelpers as $column => $helper) {
                list($type, $param1) = $helper;
                switch ($type) {
                    case "list":
                        $this->addSearchColumnGenerator($column, function ($col) use ($param1) {
                            $value = "";
                            if (array_key_exists($col, $this->searchValues)) {
                                $value = $this->searchValues[$col];
                            }
                            ?>
                            <select data-column="<?php echo $col; ?>" class="morphic-table-filter">
                                <option value="">-</option>
                                <?php foreach ($param1 as $val => $label):
                                    $sSel = ((string)$val === (string)$value) ? 'selected="selected"' : "";
                                    ?>
                                    <option <?php echo $sSel; ?>
                                            value="<?php echo htmlspecialchars($val); ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php
                        });
                        break;
                    default:
                        throw new GuiAdminTableException("Unknown searchColumnHelper: $type");
                        break;
                }
            }
        }
        parent::render();
    }


    public function addSearchColumnHelper($column, $helperType, $param1 = null)
    {
        $this->searchColumnHelpers[$column] = [$helperType, $param1];
        return $this;
    }




    //--------------------------------------------
    //
    //--------------------------------------------
    protected function getHeaderColClasses($col)
    {
        $classes = parent::getHeaderColClasses($col);
        if (true === $this->useSort) {
            if (!in_array($col, $this->deadCols, true)) {
                $classes[] = "morphic-table-sort";
                $classes[] = "morphic";
            }
        }
        return $classes;
    }

    protected function getHeaderColAttributes($col)
    {
        $attributes = parent::getHeaderColAttributes($col);

        if (true === $this->useSort) {
            $dir = "null";
            if (array_key_exists($col, $this->headersDirection)) {
                $v = $this->headersDirection[$col];
                if (true === $v || 'asc' === $v) {
                    $dir = "asc";
                } elseif (false === $v || 'desc' === $v) {
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

    protected function displayCheckboxCell()
    {
        ?>
        <td><input class="morphic morphic-checkbox" type="checkbox"></td>
        <?php
    }


    protected function displaySearchButton()
    {
        ?>
        <button type="button" class="btn btn-default btn-sm morphic morphic-table-search-btn">
            <i class="fa fa-search"></i>
            Rechercher
        </button>
        <button type="button" class="btn btn-default btn-sm morphic morphic-table-search-reset-btn">
            <i class="fa fa-close"></i>
        </button>
        <?php
    }


    protected function displayDefaultSearchCol($col)
    {
        $value = "";
        if (array_key_exists($col, $this->searchValues)) {
            $value = $this->searchValues[$col];
        }
        ?>
        <input data-column="<?php echo $col; ?>" class="morphic-table-filter" type="text"
               value="<?php echo htmlspecialchars($value); ?>">
        <?php
    }
}