var createError = require('http-errors');
var express = require('express');
var http    = require( 'http' );
var path = require('path');
var cookieParser = require('cookie-parser');
var logger = require('morgan');
var bodyParser = require('body-parser');
var engine  = require( 'ejs-locals' );
var favicon = require('serve-favicon');
var session = require('express-session');
var multer = require('multer');
var methodOverride = require('method-override');
var errorHandler = require('errorhandler');
var expressLayouts = require('express-ejs-layouts');
var indexRouter = require('./routes/index');
var usersRouter = require('./routes/users');

var mongo = require('mongodb');
var monk = require('monk');
var db = monk('localhost:27017/runoob');

var app = express();

// view engine setup
app.set('views', __dirname + '/views');
app.set('view engine', 'ejs');

app.use(logger('dev'));
app.use(methodOverride());
app.use(session({ resave: true, saveUninitialized: true, 
  secret: 'uwotm8' }));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cookieParser());

app.use(express.static(__dirname + '/public'));
app.use(function(req, res, next) {
  res.header("Access-Control-Allow-Origin", "*");
  res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
  next();
  });


app.use(function(req,res,next){
  req.db = db;
  next();
});

app.use('/', indexRouter);
app.use('/users', usersRouter);

// catch 404 and forward to error handler
app.use(function(req, res, next) {
  var err = new Error('Not Found');
  err.status = 404;
  next(err);
});



if ( 'development' == app.get( 'env' )) {
    app.use( errorHandler());
  }

app.use(function(err, req, res, next) {
  res.status(err.status || 500);
  res.render('error', {
    message: err.message,
    error: {}
  });
});

app.get('/', function(req, res) {
  res.render('index', { title: 'The index page!' })
});

app.post('/', function(req, res){
	var obj = {};
	console.log('body: ' + JSON.stringify(req.body));
    res.send(req.body);
    //db connect ....

});
console.log("Express server listening on port 3000...");

module.exports = app;
