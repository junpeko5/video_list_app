/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/assets/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/js/likes.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/js/likes.js":
/*!****************************!*\
  !*** ./assets/js/likes.js ***!
  \****************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("$(function () {\n  $('.userLikesVideo').show();\n  $('.userDoesNotLikeVideo').show();\n  $('.noActionYet').show();\n  $('.toggle-likes').on('click', function (e) {\n    e.preventDefault();\n    var $link = $(e.currentTarget);\n    $.ajax({\n      method: 'POST',\n      url: $link.attr('href')\n    }).done(function (data) {\n      var number_of_likes_str = $('.number-of-likes-' + data.id);\n      var number_of_likes = parseInt(number_of_likes_str.html().replace(/\\D/g, ''));\n      var number_of_dislikes_str = $('.number-of-dislikes-' + data.id);\n      var number_of_dislikes = parseInt(number_of_dislikes_str.html().replace(/\\D/g, ''));\n      var $video_id_obj = $('.video-id-' + data.id);\n      var $likes_video_id_obj = $('.likes-video-id-' + data.id);\n      var $dislikes_video_id_obj = $('.dislikes-video-id-' + data.id);\n\n      switch (data.action) {\n        case 'liked':\n          number_of_likes++;\n          number_of_likes_str.html('(' + number_of_likes + ')');\n          $likes_video_id_obj.show();\n          $video_id_obj.hide();\n          break;\n\n        case 'disliked':\n          number_of_dislikes++;\n          number_of_dislikes_str.html('(' + number_of_dislikes + ')');\n          $dislikes_video_id_obj.show();\n          $video_id_obj.hide();\n          break;\n\n        case 'undo liked':\n          number_of_likes--;\n          number_of_likes_str.html('(' + number_of_dislikes + ')');\n          $video_id_obj.show();\n          $likes_video_id_obj.hide();\n          $dislikes_video_id_obj.hide();\n          break;\n\n        case 'undo disliked':\n          number_of_dislikes--;\n          number_of_dislikes_str.html('(' + number_of_dislikes + ')');\n          $video_id_obj.show();\n          $likes_video_id_obj.hide();\n          $dislikes_video_id_obj.hide();\n          break;\n      }\n    });\n  });\n});\n\n//# sourceURL=webpack:///./assets/js/likes.js?");

/***/ })

/******/ });