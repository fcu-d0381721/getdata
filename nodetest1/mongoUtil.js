const express = require("express");
const MongoClient = require("mongodb").MongoClient;
const bodyParser = require("body-parser");
const assert = require('assert');
const app = express();

var _db;
// Bodyparser middleware
app.use(
  bodyParser.urlencoded({
    extended: false
  })
);
app.use(bodyParser.json());

// Connect to MongoDB

module.exports = {

  connectToServer: function( callback ) {
    MongoClient.connect('mongodb://localhost:27017/runoob',{ useNewUrlParser: true }, function (err, db) { 
      assert.equal(null, err);
      console.log("Connected successfully to mongodb");
      _db = db;
      return callback( err );
    });
  },
  getDb: function() {
    return _db;
  }
};