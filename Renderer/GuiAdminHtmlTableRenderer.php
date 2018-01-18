<?php


namespace GuiAdminTable\Renderer;


use Bat\StringTool;

class GuiAdminHtmlTableRenderer extends GuiAdminTableRenderer
{
    public function render()
    {

        $htmlAttrTable = StringTool::htmlAttributes($this->getHtmlAttributes("table"));
        $htmlAttrTrSearch = StringTool::htmlAttributes($this->getHtmlAttributes("trSearch"));
        $htmlAttrTrRow = StringTool::htmlAttributes($this->getHtmlAttributes("trRow"));


        ?>
        <table <?php echo $htmlAttrTable; ?>>
            <thead>
            <tr>
                <?php if (true === $this->useCheckboxes): ?>
                    <th>--</th>
                <?php endif; ?>
                <?php foreach ($this->headers as $col => $label): ?>
                    <?php if (true === $this->headerIsVisible($col)): ?>
                        <?php
                        $headerAttributes = $this->getHeaderColAttributes($col);
                        ?>
                        <th <?php echo StringTool::htmlAttributes($headerAttributes); ?>><?php echo $label; ?></th>
                    <?php else: ?>
                        <th><?php echo $label; ?></th>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
            </thead>

            <tbody>

            <?php if (true === $this->useFilters): ?>
                <tr <?php echo $htmlAttrTrSearch; ?>>
                    <?php if (true === $this->useCheckboxes): ?>
                        <td></td>
                    <?php endif; ?>
                    <?php foreach ($this->headers as $col => $label): ?>
                        <?php if (true === $this->headerIsVisible($col)): ?>
                            <td>
                                <?php if ($this->searchButtonExtraColumnName === $col): ?>
                                    <?php $this->displaySearchButton(); ?>
                                <?php else: ?>
                                    <?php $this->displaySearchCol($col); ?>
                                <?php endif; ?>
                            </td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            <?php endif; ?>


            <?php foreach ($this->rows as $row): ?>
                <tr <?php echo $htmlAttrTrRow; ?>>
                    <?php if (true === $this->useCheckboxes): ?>
                        <?php $this->displayCheckboxCell(); ?>
                    <?php endif; ?>
                    <?php foreach ($this->headers as $col => $label): ?>
                        <?php if (true === $this->headerIsVisible($col)): ?>
                            <?php
                            $value = null;
                            if (array_key_exists($col, $row)) {
                                $value = $row[$col];
                            }
                            $originalValue = $value;
                            if (array_key_exists($col, $this->colTransformers)) {
                                $transformers = $this->colTransformers[$col];
                                foreach ($transformers as $callable) {
                                    $value = call_user_func($callable, $value, $row);
                                }
                            }

                            $colAttr = $this->getBodyColAttributes($col, $originalValue, $value);
                            ?>
                            <td <?php echo StringTool::htmlAttributes($colAttr); ?>><?php echo $value; ?></td>
                        <?php else: ?>
                            <td <?php echo StringTool::htmlAttributes($colAttr); ?>
                            ><?php echo $value; ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
        <?php
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    protected function getBodyColAttributes($columnName, $originalValue, $value)
    {
        return [
            "data-column" => $columnName,
            "data-value" => $originalValue,
        ];
    }

    protected function displayCheckboxCell()
    {
        ?>
        <td><input type="checkbox"></td>
        <?php
    }

    protected function displaySearchButton()
    {
        ?>
        <button type="button" class="btn btn-default btn-sm">
            <i class="fa fa-search"></i>
            Rechercher
        </button>
        <?php
    }


    protected function displaySearchCol($col)
    {
        if (array_key_exists($col, $this->searchColumnGenerators)) {
            call_user_func($this->searchColumnGenerators[$col]);
        } else {
            $this->displayDefaultSearchCol($col);
        }
    }

    protected function displayDefaultSearchCol($col)
    {
        $value = "";
        if (array_key_exists($col, $this->searchValues)) {
            $value = $this->searchValues[$col];
        }
        ?>
        <input type="text" value="<?php echo htmlspecialchars($value); ?>">
        <?php
    }

    /**
     * This method is just a personal memo of what's possible, it's not used.
     */
    protected function displayDefaultSearchColAlt()
    {
        ?>
        <select class="form-control input-sm">
            <option>doo</option>
            <option>voo</option>
        </select>
        <?php
    }

    protected function getHeaderColAttributes($col)
    {
        $attributes = [];
        $classes = $this->getHeaderColClasses($col);
        if ($classes) {
            $attributes['class'] = implode(' ', $classes);
        }
        return $attributes;
    }

    protected function getHeaderColClasses($col)
    {
        $classes = [];

        if (true === $this->useSort) {
            if (array_key_exists($col, $this->headersOrder)) {
                $v = $this->headersOrder[$col];
                if (true === $v || 'asc' === $v) {
                    $classes[] = 'sorting_asc';
                } elseif (false === $v || 'desc' === $v) {
                    $classes[] = 'sorting_desc';
                } else {
                    $classes[] = 'sorting';
                }
            } else {
                $classes[] = 'sorting';
            }
        }
        return $classes;
    }
}