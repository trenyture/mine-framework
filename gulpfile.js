var gulp = require('gulp');
var notify = require("gulp-notify");
var gulpUtil = require('gulp-util');
/* CSS */
var sass = require('gulp-sass');
var sourcemaps = sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var concatCSS = require('gulp-concat-css');
/* JS */
var jshint = require('gulp-jshint');
var uglify = require('gulp-uglify');
var concatJS = require('gulp-concat');
/* SERVER */
var refresh = require('gulp-livereload');
var lr = require('tiny-lr');
var server = lr();
var browserSync = require('browser-sync').create();
var proxyServer = 'www.mine-framework.dev';

/*
 * minify and check syntax of all the scripts (not vendors)
 */
gulp.task('scripts', function() {
	gulp.src(['./resources/js/**/*.js', '!./resources/js/vendors/**/*.js'])
		.pipe(jshint().on('error', gulpUtil.log))
		.pipe(jshint.reporter('default'))
		.pipe(uglify().on('error', gulpUtil.log))
		.pipe(gulp.dest('./public/assets/scripts'))
		.pipe(notify({
			title: "JS Compiled",
			message: "Compiled file: <%= file.relative %> \n\r <%= options.date %>!",
			templateOptions: {
				date: new Date()
			}
		}))
		.pipe(browserSync.stream());
});

/*
 * Concatenate all the vendors scripts and make one vendors.min.js file in public
 */
gulp.task('concatJSVendors', function() {
	gulp.src(['./resources/js/vendors/jquery.min.js', './resources/js/vendors/**/!(jquery.min)*.js'])
		.pipe(concatJS('vendors.min.js'))
		.pipe(gulp.dest('./public/assets/scripts'))
		.pipe(notify({
			title: "JS Vendors ready",
			message: "Your JavaScript vendors are ready!",
			templateOptions: {
				date: new Date()
			}
		}))
		.pipe(browserSync.stream());
});

/*
 * minify and check syntax of all the styles (not vendors)
 */
gulp.task('styles', function() {
	gulp.src(['./resources/sass/**/*.scss', '!./resources/sass/vendors/**/*.css'])
		.pipe(sourcemaps.init())
		.pipe(sass({
			outputStyle: 'compressed'
		}).on('error', sass.logError))
		.pipe(autoprefixer({
			browsers: ['last 10 versions', 'ie >= 10'],
			cascade: true
		}))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./public/assets/styles'))
		.pipe(notify({
			title: "SCSS Compiled",
			message: "Compiled file: <%= file.relative %> \n\r <%= options.date %>!",
			templateOptions: {
				date: new Date()
			}
		}))
		.pipe(browserSync.stream());
});

/*
 * Concatenate all the vendors styles and make one vendors.min.css file in public
 */
gulp.task('concatCSSVendors', function() {
	gulp.src(['./resources/sass/vendors/**/*.css'])
		.pipe(concatCSS('vendors.min.css'))
		.pipe(gulp.dest('./public/assets/styles'))
		.pipe(notify({
			title: "CSS Vendors ready",
			message: "Your Styles vendors are ready!",
			templateOptions: {
				date: new Date()
			}
		}))
		.pipe(browserSync.stream());
});

/*
 * Make the passerell with the navigator (creating a web server)
 */
gulp.task('serve', function() {
	browserSync.init({
		proxy: proxyServer,
		host: proxyServer,
		open: 'external'
	});

	gulp.watch(['./resources/js/**/*.js', '!./resources/js/vendors/**/*.js'], function(event) {
		gulp.run('scripts');
	});
	
	gulp.watch('./resources/js/vendors/**/*.js', function(event) {
		gulp.run('concatJSVendors');
	});
	
	gulp.watch(['./resources/sass/**/*.scss', '!./resources/sass/vendors/**/*.css'], function(event) {
		gulp.run('styles');
	});
	
	gulp.watch('./resources/sass/vendors/**/*.css', function(event) {
		gulp.run('concatCSSVendors');
	});
	
	gulp.watch("./sources/**").on('change', browserSync.reload);
});

/*
 * Making the default command in gulp to create all the server
 */
gulp.task('default', ['scripts', 'styles', 'concatCSSVendors', 'concatJSVendors', 'serve']);