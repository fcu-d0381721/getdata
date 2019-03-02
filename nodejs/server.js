// mongoose setup
//var db = require( './db' );
 
var express = require( 'express' );
var indexRouter = require('./routes/index');
var usersRouter = require('./routes/users');

var http    = require( 'http' );
var path    = require( 'path' );
var app     = express();
var engine  = require( 'ejs-locals' );
var favicon = require('serve-favicon');
var logger = require('morgan');
var methodOverride = require('method-override');
var session = require('express-session');
var bodyParser = require('body-parser');
var multer = require('multer');
var errorHandler = require('errorhandler');
var expressLayouts = require('express-ejs-layouts');
var mongo = require('mongodb');
var monk = require('monk');
var db = monk('localhost:27017/runoob');

// all environments
app.set('port', process.env.PORT || 3000);
// app.use(express.static(path.join(__dirname + '/views')));
app.set('views', __dirname + '/views');
app.set('view engine', 'ejs');
// app.use(favicon(__dirname + '/public/favicon.ico'));
app.use(logger('dev'));
app.use(methodOverride());
app.use(session({ resave: true, saveUninitialized: true, 
                  secret: 'uwotm8' }));
app.use(function(req, res, next) {
res.header("Access-Control-Allow-Origin", "*");
res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
next();
});
// parse application/json
app.use(bodyParser.json());                        

// parse application/x-www-form-urlencoded
app.use(bodyParser.urlencoded({ extended: true }));

// parse multipart/form-data
// app.use(multer());

// app.use(express.static(path.join(__dirname, 'public')));
 
// development only
if ( 'development' == app.get( 'env' )) {
  app.use( errorHandler());
}

//read routes
app.use(express.static(__dirname + '/routes'));
//read css
app.use(express.static(__dirname + '/public'));
app.use(function(req,res,next){
    req.db = db;
    next();
  });

app.use('/', indexRouter);
app.use('/users', usersRouter);

//serve the homepage
app.get('/', function(req, res) {
    res.render('index', { title: 'The index page!' })
});

app.post('/server', function(req, res){
    var obj = {};
    console.log(res);
	console.log('body: ' + JSON.stringify(req.body));
    res.send(req.body);
    //db connect ....
});

 
http.createServer( app ).listen( app.get( 'port' ), function(){
  console.log( 'Express server listening on port ' + app.get( 'port' ));
} );