var express = require('express');
var router = express.Router();

/* GET home page. */
router.get('/', function(req, res, next) {
  console.log(10000)
  res.redirect('/users/list');
});

module.exports = router;

