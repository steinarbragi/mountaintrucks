"use strict";

var gulp = require('gulp'),
    concat = require('gulp-concat'),
    less = require('gulp-less'),
    autoprefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    rev = require('gulp-rev'),
    livereload = require('gulp-livereload');
var browsers = "'last 2 versions', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'";
var dist = '_site/';
var paths = {
 scripts: ['scripts/**/*.js'],
 styles: ['less/**/*.*']
};


// COMPILE SCRIPTS
gulp.task('scripts', function() {
  gulp.src(paths.scripts)
    .pipe(uglify())
    .pipe(concat('script.js'))
    .pipe(gulp.dest(dist + 'assets/js'))
    .pipe(rev())
    .pipe(gulp.dest(dist + 'assets/js'))  // write rev'd assets to build dir
    .pipe(rev.manifest("_site/assets/rev-manifest.json", {
      base: process.cwd() + '/_site/assets/',
      merge: true
    }))
    .pipe(gulp.dest(dist + 'assets/')); // write manifest to build dir
});

// COMPILE STYLES
gulp.task('styles', function() {
  gulp.src(['less/style.less'])
    .pipe(less({compress: true}))
    .pipe(autoprefixer(browsers))
    .pipe(gulp.dest(dist + 'assets/css'))
    .pipe(rev())
    .pipe(gulp.dest(dist + 'assets/css'))  // write rev'd assets to build dir
    .pipe(rev.manifest("_site/assets/rev-manifest.json", {
      base: process.cwd() + '/_site/assets/',
      merge: true
    }))
    .pipe(gulp.dest(dist + 'assets/')); // write manifest to build dir
});

gulp.task('autoprefixer', function () {
    var postcss = require('gulp-postcss');
    var autoprefixer = require('autoprefixer-core');
    return gulp.src('assets/css/*.css')
        .pipe(postcss([ autoprefixer({ browsers: [browsers] }) ]))
        .pipe(gulp.dest('./assets/css'));
});

// WATCH
gulp.task('watch', function() {
  gulp.watch(paths.styles, ['styles']);
  gulp.watch(paths.scripts, ['scripts']);

  // Create LiveReload server
  // Watch any files in dist/, reload on change
  livereload.listen();
  gulp.watch([dist + '**']).on('change', livereload.changed);
});

// DEFAULT TASK

gulp.task('default', ['scripts', 'styles']);
