/* gulp dependencies */
const gulp = require('gulp')
const sass = require('gulp-sass')
const uglify = require('gulp-uglify')
const rename = require('gulp-rename')
const minifycss = require('gulp-minify-css')
const concat = require('gulp-concat')
const plumber = require('gulp-plumber')
const postcss = require('gulp-postcss')
const autoprefixer = require('autoprefixer')
const cssnano = require('cssnano')
const flexbugsfixes = require('postcss-flexbugs-fixes')
const livereload = require('gulp-livereload')
const jshint = require('gulp-jshint')
const pkg = require('./package')
const jshintConfig = pkg.jshintConfig
jshintConfig.lookup = false

/* vendors task */
gulp.task('vendors', function() {
	return gulp.src([
		'node_modules/fastclick/lib/fastclick.js',
		'node_modules/magnific-popup/dist/jquery.magnific-popup.js',
		'node_modules/flickity/dist/flickity.pkgd.js',
		'node_modules/stickyfilljs/dist/stickyfill.min.js',
	])
	.pipe(concat('vendors.min.js'))
	.pipe(uglify())
	.on('error', console.log)
	.pipe(gulp.dest('assets/js'))
})

/* scripts task */
gulp.task('scripts', function() {
	return gulp.src('assets/js/theme.js')
		.pipe(plumber())
		.pipe(jshint(jshintConfig))
		.pipe(jshint.reporter('jshint-stylish'))
		.pipe(rename({suffix: '.min'}))
		.pipe(uglify())
		.on('error', console.log)
		.pipe(gulp.dest('assets/js'))
		.pipe(livereload())
})

/* sass task */
gulp.task('sass', function() {
	gulp.src('assets/scss/theme.scss')
		.pipe(plumber())
		.pipe(sass({ includePaths: ['scss']}))
		.on('error', console.log)
		.pipe(gulp.dest('assets/css'))
})

/* postcss/cssnano task */
gulp.task('css', function() {
	var processors = [
		flexbugsfixes(),
		autoprefixer(),
		cssnano(),
	]
	return gulp.src('assets/css/theme.css')
		.pipe(postcss(processors))
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest('assets/css'))
		.pipe(livereload())
})

/* watch scss, js and html files, doing different things with each. */
gulp.task('default', ['scripts', 'sass', 'css'], function() {
	livereload.listen()
	/* watch scss, run the sass task on change. */
	gulp.watch(['assets/scss/**/*.scss'], ['sass', 'css'])
	/* watch app.js file, run the scripts task on change. */
	gulp.watch(['assets/js/theme.js'], ['scripts'])
	/* watch .php and image files, run the reload task on change. */
	gulp.watch(['**/*.php', '**/*.jpg', '**/*.png', '**/*.svg'], livereload.reload)
})