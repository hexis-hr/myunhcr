var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    prefix = require('gulp-autoprefixer'),
    plumber = require('gulp-plumber');

var path = {
  root: __dirname,
  scss: __dirname + '/module/Application/public/scss',
  css: __dirname + '/module/Application/public/css',
}


/*-------------------------------------------------------------------------------------------------
  Default
-------------------------------------------------------------------------------------------------*/
gulp.task('default', ['css', 'watch']);


/*-------------------------------------------------------------------------------------------------
  CSS
-------------------------------------------------------------------------------------------------*/
gulp.task('css', function (){
  gulp.src(path.scss + '/**/*.scss')
    .pipe(plumber())
    .pipe(sass({
      loadPath: [
        path.root,
        path.scss
      ],
      sourcemapPath: path.scss,
      require: [
        path.root + '/sass_modules/list-files.rb',
        path.root + '/sass_modules/file-exists.rb',
        path.root + '/sass_modules/image_size.rb',
        path.root + '/sass_modules/inline_image.rb'
      ],
      unixNewlines: true,
      style: 'expanded',
      noCache: true,
      bundleExec: true
    }))
    .pipe(prefix('last 2 version', 'Explorer >= 8', '> 1%', 'Android >= 2.3.3'))
    .pipe(gulp.dest(path.css));
});


/*-------------------------------------------------------------------------------------------------
  Watch for changes
-------------------------------------------------------------------------------------------------*/
gulp.task('watch', function(){
  gulp.watch(path.scss + '/**/*.scss', ['css']);
});