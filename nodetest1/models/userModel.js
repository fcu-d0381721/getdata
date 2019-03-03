var mongoose = require('mongoose');
mongoose.Promise = global.Promise;
mongoose.connect('mongodb://localhost:27017/runoob',{ useNewUrlParser: true });

var userSchema = new mongoose.Schema({
    datepicker: String,
    speed_small: String,
    time_limit: String,
    speed_big:String,
    time2_limit:String,
    laneoccupy_big:String,
})

var model = mongoose.model('test', userSchema);

module.exports = model;