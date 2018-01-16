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


The js api basically updates the view (table and widgets) by fetching the html code
directly from the server (using ajax) and replacing/pasting it in the current view.




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
                    - ?data-sort-dir: asc|desc|null, represent the current sorting being applied for 
                            this column.
                            Any other value is equivalent to null, meaning no particular 
                            sorting was applied for this column.
                            Same if the data-sort-dir attribute is not present.
                            
                            The orders cycle in the following order:
                            - null
                            - asc
                            - desc
                             
                            
                    - data-column: string, the name of the column to which the sort is applied 




Implementation
---------------
The table should have the following attributes:

- data-view-id: the name of the table to interact with
- ?data-page: the number of the current page to display,
            otherwise (if not set), 1 is assumed
- data-nipp: the number of items per page to use,
            otherwise (if not set), 20 is assumed
- data-service-uri: the url of the service to fetch
        
        
        
### Ajax service

An ajax service should be available.
The communication consists of the actions described below.
It will use the [ecp](https://github.com/lingtalfi/Ecp) protocol.
All parameters are passed via post.
The key introducing the actions is: "actionType".


- fetching data:
        - actionType: fetch
        - viewId: the view id.
                    Often, this is the name of the table.
                    It's an identifier representing the view being displayed.
        - sort: array of columnName => sortDir
                        with sortDir: asc|desc|null
                        Note: if the columnName is not passed, it has the same
                        effects than if it had the value null.
        - filters: array of columnName => searchString
                        @todo-ling: define searchString
        - page: int, the minimum is 1.
        - nipp: int, the number of items per page
                        
        The result is a string containing the html table and widgets.
        It should depend on the theme.
        Therefore, we ask the Theme to give us a "table & widgets" renderer,
        also called GuiAdminTableRenderer.
        
        







        