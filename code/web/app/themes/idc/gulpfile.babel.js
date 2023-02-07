//Globals
var argv = require('minimist')(process.argv.slice(2)),
  manifest = require('asset-builder')('./src/static/manifest.json');

import { src, dest, watch, series, parallel } from 'gulp';
import browserSync from 'browser-sync';
import lazypipe from 'lazypipe';
// import runSequence from 'run-sequence';
import merge from 'merge-stream';
import map from 'map-stream';
// import colors from 'colors';
import changed from 'gulp-changed';
import concat from 'gulp-concat';
import flatten from 'gulp-flatten';
import gulpif from 'gulp-if';
import imagemin from 'gulp-imagemin';
import jshint from 'gulp-jshint';
import plumber from 'gulp-plumber';
import less from 'gulp-less';
import lesshint from 'gulp-lesshint';
import sourcemaps from 'gulp-sourcemaps';
import uglify from 'gulp-uglify-es';
import size from 'gulp-size';
import notify from 'gulp-notify';
import cleanCSS from 'gulp-clean-css';
import del from 'del';
import babel from 'gulp-babel';

var LessPluginAutoPrefix = require('less-plugin-autoprefix'),
  autoprefixPlugin = new LessPluginAutoPrefix({
    browsers: ['last 2 versions'],
  });

var path = manifest.paths,
  config = manifest.config || {},
  globs = manifest.globs,
  project = manifest.getProjectGlobs(),
  enabled = {
    // Enable static asset revisioning when `--production`
    rev: argv.production,
    // Disable source maps when `--production`
    maps: !argv.production,
    // Fail styles task on error when `--production`
    failStyleTask: argv.production,
    // Fail due to JSHint warnings only when `--production`
    failJSHint: argv.production,
    // Strip debug statments from javascript when `--production`
    stripJSDebug: argv.production,
  };

// Function that handles pipe breaks in JS
export const jsErrorAlert = function (error) {
  notify.onError({
    title: 'JS Compile Error!',
    message: error.toString(),
    sound: 'Sosumi',
  })(error);
  console.log(error.toString());
  this.emit('end');
};

// Function that handles pipe breaks in LESS
export const lessErrorAlert = function (error) {
  notify.onError({
    title: 'LESS Compile Error!',
    message: error.toString(),
    sound: 'Sosumi',
  })(error);
  console.log(error.toString());
  this.emit('end');
};

//Function that handles pipe breaks in fonts task
export const fontsErrorAlert = function (error) {
  notify.onError({
    title: 'Fonts Error!',
    message: error.toString(),
    sound: 'Sosumi',
  })(error);
  console.log(error.toString());
  this.emit('end');
};

//Function that handles pipe breaks in meta task
export const metaErrorAlert = function (error) {
  notify.onError({
    title: 'Meta Error!',
    message: error.toString(),
    sound: 'Sosumi',
  })(error);
  console.log(error.toString());
  this.emit('end');
};

//Function that handles pipe breaks in lottie task
export const lottieErrorAlert = function (error) {
  notify.onError({
    title: 'Lottie Error!',
    message: error.toString(),
    sound: 'Sosumi',
  })(error);
  console.log(error.toString());
  this.emit('end');
};


//Function that handles pipe breaks in image compression
export const imageCompressionErrorAlert = function (error) {
  notify.onError({
    title: 'Image Compression Error!',
    message: error.toString(),
    sound: 'Sosumi',
  })(error);
  console.log(error.toString());
  this.emit('end');
};

export const jsLintFailReporter = function () {
  return map(function (file, cb) {
    if (file.jshint.success) {
      return cb(null, file);
    }

    console.log('JSHINT fail in ' + file.path.red);
    file.jshint.results.forEach(function (result) {
      if (!result.error) {
        return;
      }

      const err = result.error;
      console.log(
        `  line ${err.line}, col ${err.character}, code ${err.code}, ${err.reason}`
          .yellow
      );
      notify.onError({
        title: 'JS Lint Warning!',
        message: err.reason,
        sound: 'Sosumi',
      })(err);
    });

    cb(null, file);
  });
};

// Reuseable pipeline for processing styles
export const stylesTasks = function (filename) {
  return (
    lazypipe()
      .pipe(plumber, { errorHandler: lessErrorAlert })
      .pipe(lesshint)
      .pipe(lesshint.reporter)
      .pipe(function () {
        return gulpif(enabled.maps, sourcemaps.init());
      })
      .pipe(less, { plugins: [autoprefixPlugin] })
      // .pipe(less, { javascriptEnabled: true })
      .pipe(concat, filename)
      .pipe(cleanCSS, { compatibility: 'ie9' })
      .pipe(function () {
        return gulpif(
          enabled.maps,
          sourcemaps.write('.', {
            sourceRoot: path.source + 'styles/',
          })
        );
      })()
  );
};

// Reuseable Pipeline to process JS
export const scriptTasks = function (filename) {
  return lazypipe()
    .pipe(plumber, { errorHandler: jsErrorAlert })
    .pipe(function () {
      return gulpif(enabled.maps, sourcemaps.init());
    })
    .pipe(concat, filename)
    .pipe(uglify, {
      compress: {
        drop_debugger: enabled.stripJSDebug,
        dead_code: enabled.stripJSDebug,
        drop_console: enabled.stripJSDebug,
      },
    })
    .pipe(function () {
      return gulpif(
        enabled.maps,
        sourcemaps.write('.', {
          sourceRoot: path.source + 'scripts/',
        })
      );
    })();
};

// Compiles, combines, and optimizes Bower CSS and project CSS. By default this task will only log a warning if a precompiler error is raised. If the `--production` flag is set: this task will fail outright.
export const styles = () => {
  const merged = merge();

  manifest.forEachDependency('css', function (dep) {
    var stylesTasksInstance = stylesTasks(dep.name);
    if (!enabled.failStyleTask) {
      stylesTasksInstance.on('error', function (err) {
        console.error(err.message);
        this.emit('end');
      });
    }
    merged.add(
      src(dep.globs, { base: 'styles' })
        .pipe(plumber({ errorHandler: lessErrorAlert }))
        .pipe(stylesTasksInstance)
    );
  });

  return merged
    .pipe(plumber({ errorHandler: lessErrorAlert }))
    .pipe(changed(path.dist + 'styles'))
    .pipe(size({ showFiles: true }))
    .pipe(dest(path.dist + 'styles'))
    .pipe(notify({ message: 'Styles successfully compiled!', onLast: true }))
    .pipe(server.stream());
};

// Runs JSHint then compiles, combines, and optimizes Bower JS and project JS.
export const scripts = () => {
  var merged = merge();
  manifest.forEachDependency('js', function (dep) {
    var scriptTasksInstance = scriptTasks(dep.name);
    if (!enabled.failJSHint) {
      scriptTasksInstance.on('error', function (err) {
        console.error(err);
        this.emit('end');
      });
    }
    merged.add(
      src(dep.globs, { base: 'scripts' })
        .pipe(plumber({ errorHandler: jsErrorAlert }))
        .pipe(
          babel({
            presets: [['@babel/env']],
          })
        )
        .pipe(scriptTasksInstance)
    );
  });
  return merged
    .pipe(plumber({ errorHandler: jsErrorAlert }))
    .pipe(changed(path.dist + 'scripts'))
    .pipe(size({ showFiles: true }))
    .pipe(dest(path.dist + 'scripts'))
    .pipe(notify({ message: 'JS successfully compiled!', onLast: true }))
    .pipe(server.stream());
};

// Grabs all the fonts and outputs them in a flattened directory
export const fonts = () => {
  return src(globs.fonts)
    .pipe(changed(path.dist + 'webfonts'))
    .pipe(plumber({ errorHandler: fontsErrorAlert }))
    .pipe(flatten())
    .pipe(size({ showFiles: true }))
    .pipe(dest(path.dist + 'webfonts'))
    .pipe(notify({ message: 'Fonts successfully processed!', onLast: true }))
    .pipe(server.stream());
};

// Grabs all meta-related images and files and copy to dist directory
export const meta = () => {
  return src(manifest.dependencies.meta.files)
    .pipe(plumber({ errorHandler: metaErrorAlert }))
    .pipe(size({ showFiles: true }))
    .pipe(dest(path.dist + 'meta'))
    .pipe(notify({ message: 'Meta successfully processed!', onLast: true }))
};

// Grabs all lottie-related images and files and copy to dist directory
export const lottie = () => {
  return src(manifest.dependencies.lottie.files)
    .pipe(plumber({ errorHandler: lottieErrorAlert }))
    .pipe(size({ showFiles: true }))
    .pipe(dest(path.dist + 'lottie'))
    .pipe(notify({ message: 'Lottie successfully processed!', onLast: true }))
};

// Run lossless compression on all the images.
export const images = () => {
  return src(globs.images)
    .pipe(changed(path.dist + 'images'))
    .pipe(plumber({ errorHandler: imageCompressionErrorAlert }))
    .pipe(
      imagemin([
        imagemin.gifsicle({ interlaced: true }),
        imagemin.jpegtran({ progressive: true }),
        imagemin.optipng({ optimizationLevel: 5 }),
        imagemin.svgo({
          plugins: [
            { removeViewBox: false },
            { removeUnknownsAndDefaults: false },
            { cleanupIDs: false },
            { inlineStyles: false },
          ],
        }),
      ])
    )
    .pipe(dest(path.dist + 'images'))
    .pipe(size({ showFiles: true }))
    .pipe(notify({ message: 'Images successfully processed!', onLast: true }))
    .pipe(server.stream());
};

// Lints configuration JSON and project JS.
export const jshintTask = () => {
  return src(['bower.json'].concat(project.js))
    .pipe(jshint())
    .pipe(jsLintFailReporter());
};

// Deletes the distribution directory entirely
export const clean = () => {
  return del([path.dist]);
};

// Builds static assets and imagery
export const buildManifest = () => {
  series(
    'styles',
    'jshint',
    'scripts',
    'fonts',
    'meta',
    'lottie',
    'images',
    'reload',
    function () {
      console.log('Build Succeeded!');
      console.log('Listening for changes...');
    }
  );
};

/* Watch Tasks */
export const watchForChanges = () => {
  watch([path.source + 'styles/**/*'], series(styles, reload));
  watch([path.source + 'images/**/*'], series(images, reload));
  watch([path.source + 'fonts/**/*'], fonts);
  watch([path.source + 'meta/**/*'], meta);
  watch([path.source + 'lottie/**/*'], lottie);
  watch([path.source + 'scripts/**/*'], series(scripts, reload));
  watch('**/*.php', reload);
  watch([path.source + 'manifest.json']).on('change', function (file) {
    series(buildManifest, reload);
  });
};

const server = browserSync.create();
export const serve = (done) => {
  server.init({
    files: [
      '{partials,templates}/**/*.php',
      'lib/theme/*.php',
      '*.php',
      'dist/**',
    ],
    watchTask: true,
    proxy: process.env.DEV_URL || config.devUrl,
    notify: true,
    open: true,
  });
  done();
};
export const reload = (done) => {
  server.reload();
  done();
};

// Default gulp task

export const dev = series(
  clean,
  parallel(styles, jshintTask, images, fonts, meta, lottie, scripts),
  serve,
  watchForChanges
);
export const build = series(
  clean,
  parallel(styles, jshintTask, images, fonts, meta, lottie, scripts)
);
export default dev;
