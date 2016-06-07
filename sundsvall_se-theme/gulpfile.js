"use strict";

var gulp         = require('gulp'),
    watch        = require('gulp-watch'),
    sourcemaps   = require('gulp-sourcemaps'),

    sass         = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    critical     = require('critical'),

    concat       = require('gulp-concat'),
    rename       = require('gulp-rename'),
    uglify       = require('gulp-uglify'),

    browserify   = require('gulp-browserify'),

    svgstore     = require('gulp-svgstore'),
    svgmin       = require('gulp-svgmin'),

    browserSync  = require('browser-sync').create();

var config = {
	/* Local address of wordpress install. Used by browsersync  */
	PROXY: 'sundsvall.dev',

	template_directory: './'
}

gulp.task('styles', ['editor-styles'], function() {
	gulp.src('./assets/css/scss/style.scss')
		.pipe(sourcemaps.init())
		.pipe(sass().on('error', sass.logError))
		.pipe(autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./assets/css'))
		.pipe(browserSync.stream({match: '**/*.css'}));
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

gulp.task('critical', ['default'], function() {

	console.log('http://' + config.PROXY+'/');

	critical.generate({
		src: 'http://' + config.PROXY+'/',
		dest: 'partials/critical/index.css',
		minify: true,
		width: 1140,
		height: 720
	});

});

gulp.task('scripts', function() {
	gulp.src('./assets/js/source/app.dev.js')
		.pipe(sourcemaps.init())
		.pipe(browserify())
		.pipe(rename('app.js'))
		.pipe(uglify())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./assets/js'))
		.pipe(browserSync.stream());
});

gulp.task('icons', function() {
	gulp.src('./assets/images/icons/*.svg')
		.pipe(svgmin())
		.pipe(svgstore())
		.pipe(gulp.dest('./assets/images/'));
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

	watch('./assets/images/icons/*.svg', watchArgs, function() {
		gulp.start('icons');
	});

	watch('./**/*.php', watchArgs,function() {
		browserSync.reload();
	});
});

gulp.task('serve', ['browser-sync', 'watch']);

gulp.task('default', ['styles', 'scripts', 'icons']);

