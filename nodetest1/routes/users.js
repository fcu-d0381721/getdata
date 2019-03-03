var express = require('express');
var router = express.Router();
var jsonQuery  = require('json-query');
var mongoUtil = require('../mongoUtil');

var x = "";
var query = {};

/* GET users listing. */
router.get('/', function(req, res, next) {
  res.redirect('/users/list');
});

router.get('/list', function (req, res, next) {
  console.log(x)
  res.render('index', {"name": x['value']});  
});

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

    for (var key in req.body) {
        if (key == 'datepicker') {
            var whereStr = {"datacollecttime": new RegExp(req.body[key])}
        }
    }

    database.db('runoob').collection('test').find(whereStr).toArray(function(err, results) {
        x = jsonQuery('[*speed <=' + query.speed_small + ']', { data: results })
        console.log('[*speed <=' + query.speed_small + ']')
        console.log(x)
        res.redirect('/users/list');
    })
  });
});

router.post('/charts', function(req, res, next) {
  console.log(req.body);
});
module.exports = router;
