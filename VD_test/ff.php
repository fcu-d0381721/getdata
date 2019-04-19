<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .mytest {
            color: red;
        }
    </style>
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
    <h2>Greetings</h2>
    <div class="container">
            <div class="row">
                <div class="col-6 costom">
                    <div id="leftbox">
                        <nav class="navbar navbar-light color-left">
                                <span class="navbar-text font">車道篩選</span>
                        </nav>
                        <p class = "font">日期選擇：<input type="text" id="datepicker_start">至<input type="text" id="datepicker_end"></p>
                        <div id="rightdown">
                            <?php include 'getmysql.php';?>
                        </div>
                        
                        <div id="box">
                            <button  type="button" class="btn btn-outline-success font-color" id="download" >下載資料</button>
                        </div>
                    </div>
                </div>
                <div class="col-6 costom">
                    <div id = "rightbox">
                        <nav class="navbar navbar-light color-right">
                                <span class="navbar-text font">條件搜尋</span>
                        </nav>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text font">速率小於</span>
                            </div>
                            <input type="text" class="form-control" id="speed_small" aria-label="Amount (to the nearest dollar)">
                            <div class="input-group-append">
                                <span class="input-group-text font">以下</span>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text font">在</span>
                            </div>
                            <input type="text" class="form-control" id="time_limit" aria-label="Amount (to the nearest dollar)">
                                <select class="Time">
                                　<option value="Minute">分鐘</option>
                                　<option value="Hour">小時</option>
                                </select>
                            <div class="input-group-append">
                                <span class="input-group-text font">內，速率下降</span>
                            </div>
                            <input type="text" class="form-control" id="speed_big" aria-label="Amount (to the nearest dollar)">
                            <div class="input-group-append">
                                    <span class="input-group-text font">以上</span>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text font">在</span>
                            </div>
                            <input type="text" class="form-control" id="time2_limit" aria-label="Amount (to the nearest dollar)">
                            <select class="Time1">
                                　<option value="Minute">分鐘</option>
                                　<option value="Hour">小時</option>
                                </select>
                            <div class="input-group-append">
                                <span class="input-group-text font">內，流率上升原流率的</span>
                            </div>
                            <input type="text" class="form-control" id="laneoccupy_big" aria-label="Amount (to the nearest dollar)">
                            <div class="input-group-append">
                                    <span class="input-group-text font">倍以上</span>
                            </div>
                        </div>
                        <div id="box">
                            <button type="button" class="btn btn-outline-dark font" id="reset">清除</button>
                            <button type="button" class="btn btn-outline-info font-color" id="success">輸出圖檔</button> 
                        </div>
                    </div>
                </div>
                <div id="downbox"></div>
                <!-- <div id="container" style="width: 100%; height: 100%; margin: 0 auto;float:right;"></div> -->
                <!-- <ol id="space">
                </ol> -->
                <!-- <div id="container1" style="width: 100%; height: 100%; margin: 0 auto;float:right;"></div> -->
            </div>
        </div>
    <button>append</button>
    <script>

        var count = 0
        $(".row").append("<div id='con"+count+"' style='width: 100%; height: 100%; margin: 0 auto;float:right;'></div>");
        $(".row").append("<ol id = 'space"+count+"'  style='width: 100%; background-color:white; border: 1px solid #ced4da; border-radius: 0.25rem; height: 100px; overflow-y: auto; padding:5px; font-family: \'Microsoft JhengHei\';'></ol>")
        $("button").click(function () {
            $("#space0").append("<li>hihihi</li>");
            count += 1
        });
        
    </script>
</body>
</html>