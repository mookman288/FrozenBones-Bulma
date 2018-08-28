//Declare variables.
const	autoprefix	=	require("gulp-autoprefixer");
const	concat		=	require('gulp-concat');
const	cleanCSS	=	require('gulp-clean-css');
const	fs			=	require('fs');
const	gulp		=	require("gulp");
const	gulpif		=	require("gulp-if");
const	jshint		=	require("gulp-jshint");
const	minify		=	require('gulp-babel-minify');
const	notify		=	require("gulp-notify");
const	sass		=	require("gulp-sass");
const	sourcemaps	=	require("gulp-sourcemaps");

//Get all arguments.
var	argv		=	require('yargs').argv;

//Check if this is a development version.
var	dev			=	(!argv.dev) ? false : true;

/*
 * Tasks
 */

//Custom Hinting.
gulp.task('hintjs', function() {
	//Run Gulp.
	return gulp.src('./src/js/**/*.js')
		.pipe(jshint())
		.pipe(notify(function(file) {
			//If not success.
			if (!file.jshint.success) {
				//Get the errors.
				var	errors	=	file.jshint.results.map(function(data) {
					//If there's an error.
					if (data.error) {
						//Increment the error.
						return "(" + data.error.line + ":" + data.error.character + ") " + data.error.reason;
					}
				}).join("\n");

				//Display the errors.
				return file.relative + "[" + file.jshint.results.length + " errors]\n" + errors;
			}
		}))
		.pipe(jshint.reporter('default'));
});

//Custom JavaScript.
gulp.task("js", ['hintjs'], function() {
	//Run Gulp.
	return gulp.src('./src/js/**/*.js')
		.pipe(concat('frozen.custom.js'))
		.pipe(gulpif(!dev, minify({mangle: {keepClassName: true}})))
		.pipe(gulp.dest('./js'));
});

//Global Sass.
gulp.task('sass', function() {
	//Declare variables.
	var	die	=	false;

	//If not dev remove the map file.
	if (!dev) fs.unlinkSync('./css/stylesheet.css.map');

	//Run Gulp.
	gulp.src('./src/sass/*.scss')
		.pipe(sass({
			sourcemap: true,
			includePaths: [
				'./node_modules/bulma/'
			]
		}))
		.on('error', notify.onError(function(error) {
			//Set die as true.
			die	=	true;

			//Log to the console.
			console.error(error);

			//Return the error.
			return error;
		}))
		.pipe(gulpif(!die, gulpif(dev, sourcemaps.init())))
		.pipe(autoprefix({browsers: 'last 2 versions'}))
		.pipe(gulpif(!die, gulpif(!dev, cleanCSS({compatibility: 'ie8'}))))
		.pipe(gulpif(!die, gulpif(dev, sourcemaps.write('./'))))
		.pipe(gulpif(!die, gulp.dest('./css')));
});

//Default task runner.
gulp.task('default', ['js', 'sass']);

//Watch.
gulp.task('watch', ['default'], function() {
	//Watch for Sass.
	gulp.watch('./sass/**/*.scss', ['sass']);

	//Watch for JavaScript.
	gulp.watch('./js/**/*.js', ['js']);
});