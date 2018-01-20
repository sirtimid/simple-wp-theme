simple-wp-theme
======================

A very simple wordpress starter theme, intended for quick and simple websites.

### Requirements

- [Node.js](http://nodejs.org/download/)
- [Yarn](https://yarnpkg.com/en/) or [npm](https://www.npmjs.com/)

### Uses

- [Webpack](https://webpack.js.org/) as module bundler. Responsible for building Sass styles and uglifying javascript files
- [Babel](https://babeljs.io/) for ES6+ support
- [StandardJS](https://standardjs.com) for JS linter
- [Stylelint](https://github.com/stylelint/stylelint) for CSS linter
- [Bourbon](http://bourbon.io/) mixin library for Sass

Installation
------------

```bash
$ git clone https://github.com/sirtimid/simple-wp-theme.git
$ cd ./simple-wp-theme
$ yarn install
```

Developing
------------

Watch the change of .scss files and build the .css

```bash
$ yarn watch
```

Build it
------------

Build the project to minify assets

```bash
$ yarn build
```
