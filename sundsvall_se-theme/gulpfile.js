"use strict";

var gulp         = require('gulp'),
    watch        = require('gulp-watch'),
    sass         = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps   = require('gulp-sourcemaps'),
    concat       = require('gulp-concat'),
    browserSync  = require('browser-sync').create();

var config = {
	/* Local address of wordpress install. Used by browsersync  */
	PROXY: 'sundsvall.dev'
}

gulp.task('styles', function() {
	gulp.src('./assets/css/scss/style.scss')
		.pipe(sourcemaps.init())
		.pipe(sass().on('error', sass.logError))
		.pipe(sourcemaps.write())
		.pipe(autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}))
		.pipe(gulp.dest('./assets/css'))
		.pipe(browserSync.stream());
});

gulp.task('scripts', function() {
	gulp.src('./assets/js/source/**/*.js')
		.pipe(concat('app.js'))
		.pipe(gulp.dest('./assets/js'))
		.pipe(browserSync.stream());
});

gulp.task('browser-sync', function() {
	browserSync.init({
		proxy: process.env.PROXY || config.PROXY
	});
});

gulp.task('watch', ['default'], function() {
	watch('./assets/css/scss/**/*.scss', function() {
		gulp.start('styles');
	});

	watch('./assets/js/source/**/*.js', function() {
		gulp.start('scripts');
	});

	watch('./**/*.php', function() {
		browserSync.reload();
	});
});

gulp.task('serve', ['browser-sync', 'watch']);

gulp.task('default', ['styles', 'scripts']);

