var express = require('express');
var jsonQuery  = require('json-query');
var router = express.Router();





/* GET home page. */
router.get('/', function (req, res, next) {
    var collection = req.db.get('test');
    var whereStr = {"datacollecttime": new RegExp("2018-11-01")}
    // collection.find({whereStr}, {}, function (e, docs) {
    //     res.render('userlist', {"name": docs});
    // });
    collection.find(whereStr,function(err, result) {
        var x = jsonQuery('[*speed <= 56]', { data: result })
        console.log(x)
        res.render('index', {"name": x['value']});
        
    });
});

module.exports = router;
