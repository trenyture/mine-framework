const gulp = require('gulp');
const notify = require("gulp-notify");
const gulpUtil = require('gulp-util');
const clean = require('gulp-dest-clean');
/* CSS */
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const concatCSS = require('gulp-concat-css');
const cleanCSS = require('gulp-clean-css');
/* JS */
const jshint = require('gulp-jshint');
const uglify = require('gulp-uglify');
const concatJS = require('gulp-concat');
/* IMAGES */
const image = require('gulp-image');
/* SERVER */
const refresh = require('gulp-livereload');
const lr = require('tiny-lr');
const server = lr();
const browserSync = require('browser-sync').create();
const proxyServer = 'mine.framework.com';

///////////////////////////////////////////
//			ACTIONS ON FONTS			//
/////////////////////////////////////////

/*
 * Clean the public folder of the fonts
 */
gulp.task('cleanFonts', function () {
	return gulp
			.src('./resources/fonts/**', {read: false})
			.pipe(clean('./public/assets/fonts'));
});

/*
 * Compile Fonts from ressources to public
 */
gulp.task('fonts', ['cleanFonts'], function() {
	gulp.src('./resources/fonts/**')
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(notify({
			title: "Fonts Compiled",
			message: "Compiled file: <%= file.relative %> \n\r <%= options.date %>!",
			templateOptions: {
				date: new Date()
			}
		}))
		.pipe(browserSync.stream());
});

///////////////////////////////////////////
//			ACTIONS ON IMAGES			//
/////////////////////////////////////////

/*
 * Clean the public folder of images
 */
gulp.task('cleanImages', function () {
	return gulp
			.src('./resources/images/**', {read: false})
			.pipe(clean('./public/assets/images'));
});

/*
 * Compile Images from ressources to public
 */
gulp.task('images', ['cleanImages'],  function() {
	gulp.src('./resources/images/**')
		.pipe(image())
		.pipe(gulp.dest('./public/assets/images'))
		.pipe(notify({
			title: "Images Compiled",
			message: "Compiled file: <%= file.relative %> \n\r <%= options.date %>!",
			templateOptions: {
				date: new Date()
			}
		}))
		.pipe(browserSync.stream());
});

///////////////////////////////////////////
//			ACTIONS ON STYLES			//
/////////////////////////////////////////

/*
 * Clean the public folder of styles
 */
gulp.task('cleanStyles', function () {
	return gulp
			.src('./resources/sass/**', {read: false})
			.pipe(clean('./public/assets/styles'));
});

/*
 * Concatenate all the vendors styles and make one vendors.min.css file in public
 */
gulp.task('concatCSSVendors', function() {
	return gulp
			.src(['./resources/sass/vendors/**/*.css'])
			.pipe(concatCSS('vendors.min.css'))
			.pipe(cleanCSS({compatibility: 'ie8'}))
			.pipe(gulp.dest('./public/assets/styles'));
});

/*
 * minify and check syntax of all the styles (not vendors)
 */
gulp.task('styles', ['cleanStyles', 'concatCSSVendors'], function() {
	return gulp.src(['./resources/sass/**/*.scss', '!./resources/sass/vendors/**/*.css'])
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

///////////////////////////////////////////
//			ACTIONS ON SCRIPTS			//
/////////////////////////////////////////

/*
 * Clean the public folder of scripts
 */
gulp.task('cleanScripts', function () {
	return gulp
			.src('./resources/js/**', {read: false})
			.pipe(clean('./public/assets/scripts'));
});

/*
 * Concatenate all the vendors scripts and make one vendors.min.js file in public
 */
gulp.task('concatJSVendors', function() {
	return gulp
			.src(['./resources/js/vendors/jquery.min.js', './resources/js/vendors/**/!(jquery.min)*.js'])
			.pipe(concatJS('vendors.min.js'))
			.pipe(gulp.dest('./public/assets/scripts'));
});

/*
 * minify and check syntax of all the scripts (not vendors)
 */
gulp.task('scripts', ['cleanScripts', 'concatJSVendors'], function() {
	return gulp.src(['./resources/js/**/*.js', '!./resources/js/vendors/**/*.js'])
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

///////////////////////////////////////////
//			SERVE THE APPLET			//
/////////////////////////////////////////

/*
 * Make the passerell with the navigator (creating a web server)
 */
gulp.task('serve', function() {
	browserSync.init({
		proxy: proxyServer,
		host: proxyServer,
		open: 'external'
	});

	gulp.watch('./resources/js/**', function(){
		gulp.run('scripts');
	});

	gulp.watch('./resources/sass/**', function(){
		gulp.run('styles');
	});

	gulp.watch('./resources/fonts/**', function(){
		gulp.run('fonts');
	});

	gulp.watch('./resources/images/**', function(){
		gulp.run('images');
	});

	gulp.watch("./sources/**").on('change', browserSync.reload);
});

/*
 * Making the default command in gulp to create all the server
 */
gulp.task('default', ['fonts', 'images', 'scripts', 'styles', 'serve']);
