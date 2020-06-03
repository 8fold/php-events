'use strict';

const gulp = require("gulp");
const { series } = require('gulp');
const minify = require("gulp-minify");
const sass = require('gulp-sass');

gulp.task("sass", function(done) {
  gulp.src(["src/Styles/*.scss"])
    .pipe(sass({outputStyle: 'compressed'}).on("error", sass.logError))
    .pipe(gulp.dest("dist"))
    .pipe(gulp.dest("example"));
  done();
});

gulp.task("javascript", function(done) {
  gulp.src(["src/JavaScript/*.js"])
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

gulp.task("default", series("sass", "javascript"));
