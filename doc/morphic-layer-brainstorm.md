Morphic layer
===================
2018-01-15



The morphic layer is an abstract js layer that provides behaviour to the GuiAdminTable's table.
It needs to be implemented for your needs.

Basically, here I just describe the abstract ideas that I have on that subject.



- js api
- js listening system


The js api
-------------

- + filter ( filters )
        with: filters being an array of key => value
- + order ( orderFields )
        with: orderFields being an array of key => value
- + getSelectedRows ( )
- + refresh ( )
- - getCurrentRow ( )


Js listening system
---------------------

Jquery delegating system.
The css class to trigger a morphic event should be: morphic


We recommend the following css class markup:


- morphic-container: the element containing all the markup for a table and its widgets
----- morphic-table: the table 
--------- morphic-table-sort: on a sort trigger.
                    The following html attributes should be added accordingly:
                    - ?data-sort-dir: asc|desc, represent the current sorting being applied for 
                            this column.
                            Any other value is equivalent to null, meaning no particular 
                            sorting was applied for this column.
                            Same if the data-sort-dir attribute is not present.
                    - data-column: string, the name of the column to which the sort is applied 




