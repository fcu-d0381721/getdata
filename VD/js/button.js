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

    $("#box").find('#success').click(function(){
        speed_small = document.getElementById('speed_small').value
        datepicker = document.getElementById('datepicker').value
        time_limit = document.getElementById('time_limit').value
        speed_big = document.getElementById('speed_big').value
        time2_limit = document.getElementById('time2_limit').value
        laneoccupy_big = document.getElementById('laneoccupy_big').value
    
        console.log(speed_small) 
        console.log(datepicker)  
        console.log(time_limit)  
        console.log(speed_big)  
        console.log(time2_limit)  
        console.log(laneoccupy_big)  
        Receive(datepicker,speed_small)
    });  
})
function Receive(datepicker,speed_small){
    var MongoClient = require('mongodb').MongoClient;
    var url = "mongodb://localhost:27017/";
    
    MongoClient.connect(url,{ useNewUrlParser: true }, function(err, db) {
        if (err) throw err;
        var dbo = db.db("OneYear");
        var whereStr = {"datacollecttime": new RegExp(datepicker),"speed": {$lt : speed_small}};  // 查询条件
        dbo.collection("unDivide").find(whereStr).toArray(function(err, result) {
            if (err) throw err;
            console.log(result);
            db.close();
        });
    });
}