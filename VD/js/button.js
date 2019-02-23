$( document ).ready(function() {
    
    // datetimepicker for leftbox
    $("#datepicker").datepicker({
        "dateFormat":"yy-mm-dd",
    });

    // clear button for leftbox
    $("#box").find('#reset').click(function(){
        $(".form-control").val("");
        console.log("clear")
    });
})