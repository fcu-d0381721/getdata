var express = require('express');
var router = express.Router();
var jsonQuery  = require('json-query');
var mongoUtil = require('../mongoUtil');

var x = "";
var query = {};
//var whereStr = "";

/* GET users listing. */
router.get('/', function(req, res, next) {
  res.render('index', {"name": x});
});

// homepage action to users.js search function
router.post('/search', function(req, res, next) {
 
  query = {};
  x = "";
  mongoUtil.connectToServer( function( err ) {
    // start the rest of your app here
    
    var database = mongoUtil.getDb();

    query.speed_big = req.body.speed_big;
    query.speed_small = req.body.speed_small;
    query.time_limit = req.body.time_limit;
    query.time2_limit = req.body.time2_limit;
    query.laneoccupy_big = req.body.laneoccupy_big;

    // datetime filter
    for (var key in req.body) {
        if (key == 'datepicker') {
            // var aa = {"datacollecttime": new RegExp(req.body[key])}
            var datetime = req.body[key]
        }
    }
    // query filter
    var whereStr = {"speed": { "$lte": Number(query.speed_small) }};

    database.db('runoob').collection(datetime).find(whereStr).toArray(function(err, results) {
        // x = jsonQuery('[*speed <=' + query.speed_small + ']', { data: results })
        // console.log('[*speed <=' + query.speed_small + ']')
        x = results;
        console.log(x);
        res.redirect('/users/');
    })
  });
});

// homepage action to users.js charts function
router.post('/charts', function(req, res, next) {

  console.log(Object.keys(req.body));
  var temp = Object.keys(req.body);
  mongoUtil.connectToServer( function( err ) {
    
    var database = mongoUtil.getDb();

    query.speed_big = req.body.speed_big;
    query.speed_small = req.body.speed_small;
    query.time_limit = req.body.time_limit;
    query.time2_limit = req.body.time2_limit;
    query.laneoccupy_big = req.body.laneoccupy_big;

  });

});

module.exports = router;
