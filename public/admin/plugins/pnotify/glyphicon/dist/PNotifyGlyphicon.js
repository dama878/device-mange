!function(t,n){"object"==typeof exports&&"undefined"!=typeof module?n(exports):"function"==typeof define&&define.amd?define(["exports"],n):n((t="undefined"!=typeof globalThis?globalThis:t||self).PNotifyGlyphicon={})}(this,(function(t){"use strict";function n(t){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function e(t,n){if(!(t instanceof n))throw new TypeError("Cannot call a class as a function")}function r(t,n){for(var e=0;e<n.length;e++){var r=n[e];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}function o(t){return(o=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}function i(t,n){return(i=Object.setPrototypeOf||function(t,n){return t.__proto__=n,t})(t,n)}function u(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}function c(t,n){return!n||"object"!=typeof n&&"function"!=typeof n?u(t):n}function f(t){var n=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var e,r=o(t);if(n){var i=o(this).constructor;e=Reflect.construct(r,arguments,i)}else e=r.apply(this,arguments);return c(this,e)}}function a(t){return function(t){if(Array.isArray(t))return l(t)}(t)||function(t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}(t)||function(t,n){if(!t)return;if("string"==typeof t)return l(t,n);var e=Object.prototype.toString.call(t).slice(8,-1);"Object"===e&&t.constructor&&(e=t.constructor.name);if("Map"===e||"Set"===e)return Array.from(t);if("Arguments"===e||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(e))return l(t,n)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function l(t,n){(null==n||n>t.length)&&(n=t.length);for(var e=0,r=new Array(n);e<n;e++)r[e]=t[e];return r}function p(){}function s(t){return t()}function y(){return Object.create(null)}function h(t){t.forEach(s)}function d(t){return"function"==typeof t}function g(t,e){return t!=t?e==e:t!==e||t&&"object"===n(t)||"function"==typeof t}function b(t){t.parentNode.removeChild(t)}function m(t){return Array.from(t.childNodes)}var v;function $(t){v=t}var _=[],x=[],O=[],j=[],k=Promise.resolve(),w=!1;function S(t){O.push(t)}var P=!1,A=new Set;function E(){if(!P){P=!0;do{for(var t=0;t<_.length;t+=1){var n=_[t];$(n),R(n.$$)}for($(null),_.length=0;x.length;)x.pop()();for(var e=0;e<O.length;e+=1){var r=O[e];A.has(r)||(A.add(r),r())}O.length=0}while(_.length);for(;j.length;)j.pop()();w=!1,P=!1,A.clear()}}function R(t){if(null!==t.fragment){t.update(),h(t.before_update);var n=t.dirty;t.dirty=[-1],t.fragment&&t.fragment.p(t.ctx,n),t.after_update.forEach(S)}}var T=new Set;function C(t,n){t&&t.i&&(T.delete(t),t.i(n))}function I(t,n,e){var r=t.$$,o=r.fragment,i=r.on_mount,u=r.on_destroy,c=r.after_update;o&&o.m(n,e),S((function(){var n=i.map(s).filter(d);u?u.push.apply(u,a(n)):h(n),t.$$.on_mount=[]})),c.forEach(S)}function M(t,n){-1===t.$$.dirty[0]&&(_.push(t),w||(w=!0,k.then(E)),t.$$.dirty.fill(0)),t.$$.dirty[n/31|0]|=1<<n%31}var N=function(t){!function(t,n){if("function"!=typeof n&&null!==n)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(n&&n.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),n&&i(t,n)}(r,t);var n=f(r);function r(t){var o;return e(this,r),function(t,n,e,r,o,i){var u=arguments.length>6&&void 0!==arguments[6]?arguments[6]:[-1],c=v;$(t);var f=n.props||{},a=t.$$={fragment:null,ctx:null,props:i,update:p,not_equal:o,bound:y(),on_mount:[],on_destroy:[],before_update:[],after_update:[],context:new Map(c?c.$$.context:[]),callbacks:y(),dirty:u,skip_bound:!1},l=!1;if(a.ctx=e?e(t,f,(function(n,e){var r=!(arguments.length<=2)&&arguments.length-2?arguments.length<=2?void 0:arguments[2]:e;return a.ctx&&o(a.ctx[n],a.ctx[n]=r)&&(!a.skip_bound&&a.bound[n]&&a.bound[n](r),l&&M(t,n)),e})):[],a.update(),l=!0,h(a.before_update),a.fragment=!!r&&r(a.ctx),n.target){if(n.hydrate){var s=m(n.target);a.fragment&&a.fragment.l(s),s.forEach(b)}else a.fragment&&a.fragment.c();n.intro&&C(t.$$.fragment),I(t,n.target,n.anchor),E()}$(c)}(u(o=n.call(this)),t,null,null,g,{}),o}return r}(function(){function t(){e(this,t)}var n,o,i;return n=t,(o=[{key:"$destroy",value:function(){var t,n;t=1,null!==(n=this.$$).fragment&&(h(n.on_destroy),n.fragment&&n.fragment.d(t),n.on_destroy=n.fragment=null,n.ctx=[]),this.$destroy=p}},{key:"$on",value:function(t,n){var e=this.$$.callbacks[t]||(this.$$.callbacks[t]=[]);return e.push(n),function(){var t=e.indexOf(n);-1!==t&&e.splice(t,1)}}},{key:"$set",value:function(t){var n;this.$$set&&(n=t,0!==Object.keys(n).length)&&(this.$$.skip_bound=!0,this.$$set(t),this.$$.skip_bound=!1)}}])&&r(n.prototype,o),i&&r(n,i),t}());t.default=N,t.defaults={},t.init=function(t){t.defaults.icons={prefix:"glyphicon",notice:"glyphicon glyphicon-exclamation-sign",info:"glyphicon glyphicon-info-sign",success:"glyphicon glyphicon-ok-sign",error:"glyphicon glyphicon-warning-sign",closer:"glyphicon glyphicon-remove",sticker:"glyphicon",stuck:"glyphicon-play",unstuck:"glyphicon-pause",refresh:"glyphicon glyphicon-refresh"}},t.position="PrependContainer",Object.defineProperty(t,"__esModule",{value:!0})}));