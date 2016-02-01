"use strict";

var gulp         = require('gulp'),
    watch        = require('gulp-watch'),
    babel        = require('gulp-babel'),
    sass         = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps   = require('gulp-sourcemaps'),
    concat       = require('gulp-concat'),
    uglify       = require('gulp-uglify'),
    browserSync  = require('browser-sync').create();

var config = {
	/* Local address of wordpress install. Used by browsersync  */
	PROXY: 'sundsvall.dev'
}

gulp.task('styles', function() {
	gulp.src('./assets/css/scss/style.scss')
		.pipe(sourcemaps.init())
		.pipe(sass().on('error', sass.logError))
		.pipe(autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./assets/css'))
		.pipe(browserSync.stream());
});

gulp.task('editor-styles', function() {
	gulp.src('./assets/css/scss/editor-styles.scss')
		.pipe(sourcemaps.init())
		.pipe(sass().on('error', sass.logError))
		.pipe(autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./assets/css'));
});

var themeScripts = [
	'*.js',
	//'/bootstrap/util.js',
	//'/bootstrap/alert.js',
	//'/bootstrap/button.js',
	//'/bootstrap/carousel.js',
	//'/bootstrap/collapse.js',
	'/bootstrap/dropdown.js',
	//'/bootstrap/modal.js',
	//'/bootstrap/scrollspy.js',
	//'/bootstrap/tab.js',
	//'/bootstrap/tooltip.js',
	//'/bootstrap/popover.js'
].map(s => './assets/js/source/' + s);

gulp.task('scripts', function() {
	gulp.src(themeScripts)
		.pipe(sourcemaps.init())
		.pipe(babel({
			presets: ['es2015']
		}))
		.pipe(concat('app.js'))
		.pipe(uglify())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./assets/js'))
		.pipe(browserSync.stream());
});

gulp.task('browser-sync', function() {
	browserSync.init({
		proxy: process.env.PROXY || config.PROXY
	});
});

gulp.task('watch', ['default'], function() {

	var watchArgs = { interval: 500 };
	watch('./assets/css/scss/**/*.scss', watchArgs,function() {
		gulp.start('styles');
	});

	watch('./assets/js/source/**/*.js', watchArgs, function() {
		gulp.start('scripts');
	});

	watch('./**/*.php', watchArgs,function() {
		browserSync.reload();
	});
});

gulp.task('serve', ['browser-sync', 'watch']);

gulp.task('default', ['styles', 'scripts']);

