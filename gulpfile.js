'use strict';

const gulp = require("gulp");
const { series } = require('gulp');
const minify = require("gulp-minify");
var sass = require('gulp-dart-sass');

gulp.task("compile-sass", function(done) {
  gulp.src(["./sass/*.scss"])
    .pipe(sass({outputStyle: 'compressed'}).on("error", sass.logError))
    .pipe(gulp.dest("dist"))
    .pipe(gulp.dest("example"));
  done();
});

gulp.task("compile-javascript", function(done) {
  gulp.src(["./javascript/*.js"])
    .pipe(
      minify({
        ext:{
          src: ".js",
          min: ".min.js"
        }
      })
    )
    .pipe(gulp.dest("dist"))
    .pipe(gulp.dest("example"));
  done();
});

gulp.task("default", series("compile-sass", "compile-javascript"));
