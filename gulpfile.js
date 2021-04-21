// REQUIRED VARS
var browserSync = require('browser-sync');
var gulp = require('gulp');
var plumber = require('gulp-plumber');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var minify = require('gulp-minify');
var cssnano = require('gulp-cssnano');
var sass = require('gulp-sass');


// SCSS
gulp.task('sass', function() {
  return gulp.src([
    'node_modules/jquery-typeahead/dist/jquery.typeahead.min.css',
    'src/css/bootstrap-editable.css',
    'src/css/ekko-lightbox.css',
    'fine-uploader/fine-uploader-new.css',
    'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
    'node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
    'src/css/**/*.scss'
    ])
    .pipe(plumber({
      errorHandler: function(error) {
        console.log(error.message);
        this.emit('end');
      }
    }))
    .pipe(sass())
    .pipe(cssnano())
    .pipe(concat('screen.css'))
    .pipe(gulp.dest('dist/css'))
    .pipe(browserSync.stream());
});


// COMPILE JS
gulp.task('js', function() {
  return gulp.src([
        'node_modules/jquery/dist/jquery.min.js',
        //'node_modules/jquery-ui-dist/jquery-ui.min.js',
        'node_modules/jquery-typeahead/dist/jquery.typeahead.min.js',
        'node_modules/popper.js/dist/umd/popper.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.min.js',
        'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',
        'node_modules/moment/min/moment.min.js',
        'node_modules/slick-carousel/slick/slick.min.js',
        'node_modules/datatables.net/js/jquery.dataTables.min.js',
        'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
        'node_modules/sortablejs/Sortable.min.js',
        //'node_modules/tablesorter/dist/js/jquery.tablesorter.min.js',
        //'node_modules/tablesorter/dist/js/jquery.tablesorter.widgets.min.js',
        'node_modules/@excellalabs/jquery-autonumeric-v1.9.17/autoNumeric.js',
        'src/js/bootstrap-editable.min.js',
        'src/js/ekko-lightbox.min.js',
        'fine-uploader/jquery.fine-uploader.js',
        'src/js/script.js',
        'src/js/comment.js'
    ])
    .pipe(plumber({
      errorHandler: function(error) {
        console.log(error.message);
        this.emit('end');
      }
    }))
    .pipe(concat('site.js'))
    //.pipe(uglify())
    .pipe(minify())
    .pipe(gulp.dest('dist/js'))
    .pipe(browserSync.stream());
});



//COPY FONTAWESOME FILES TO DEPLOYED DIRECTORY
gulp.task('fontawesome', function() {
  gulp.src([
      'node_modules/@fortawesome/fontawesome-free/webfonts/*',
    ])
    .pipe(gulp.dest('dist/fonts'));
});

// Static Server + watching scss/js/html files
gulp.task('serve', gulp.series('sass', function() {

  browserSync.init({
    // SET THIS TO YOUR LOCAL URL
    proxy: 'http://retail-amplified:8888/'
  });

  gulp.watch("src/css/**/*.scss", gulp.series('sass'));
  gulp.watch("src/js/**/*.js", gulp.series('js'));
  gulp.watch("include/*.php").on('change', browserSync.reload);
  gulp.watch("*.php").on('change', browserSync.reload);
}));

gulp.task('default', gulp.parallel('js', 'sass', 'fontawesome', 'serve'));