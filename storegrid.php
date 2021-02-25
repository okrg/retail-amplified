<div id="content">
<h1>:: Stores</h1>
<div class="databox">
<script>

    // data source
    var myData = [
        ["text1", 123.45],
        ["text2", 678.90]
    ];

    // create grid object
    var objGrid = new Active.Controls.Grid;

    // set number of columns/rows
    objGrid.setColumnCount(2);
    objGrid.setRowCount(2);

    // link to cell text
    objGrid.setDataText(function(i,j){return myData[i][j]});

    // write control to the page
    document.write(objGrid);

</script>
</div>
</div>