"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[857],{31198:(r,t,e)=>{const n=e(74880).v4,o=e(17741),i=function(r,t){if(!(this instanceof i))return new i(r,t);t||(t={}),this.options={reviver:void 0!==t.reviver?t.reviver:null,replacer:void 0!==t.replacer?t.replacer:null,generator:void 0!==t.generator?t.generator:function(){return n()},version:void 0!==t.version?t.version:2,notificationIdNull:"boolean"==typeof t.notificationIdNull&&t.notificationIdNull},this.callServer=r};r.exports=i,i.prototype.request=function(r,t,e,n){const i=this;let s=null;const u=Array.isArray(r)&&"function"==typeof t;if(1===this.options.version&&u)throw new TypeError("JSON-RPC 1.0 does not support batching");if(u||!u&&r&&"object"==typeof r&&"function"==typeof t)n=t,s=r;else{"function"==typeof e&&(n=e,e=void 0);const i="function"==typeof n;try{s=o(r,t,e,{generator:this.options.generator,version:this.options.version,notificationIdNull:this.options.notificationIdNull})}catch(r){if(i)return n(r);throw r}if(!i)return s}let c;try{c=JSON.stringify(s,this.options.replacer)}catch(r){return n(r)}return this.callServer(c,(function(r,t){i._parseResponse(r,t,n)})),s},i.prototype._parseResponse=function(r,t,e){if(r)return void e(r);if(!t)return e();let n;try{n=JSON.parse(t,this.options.reviver)}catch(r){return e(r)}if(3===e.length){if(Array.isArray(n)){const r=function(r){return void 0!==r.error},t=function(t){return!r(t)};return e(null,n.filter(r),n.filter(t))}return e(null,n.error,n.result)}e(null,n)}},17741:(r,t,e)=>{const n=e(74880).v4;r.exports=function(r,t,e,o){if("string"!=typeof r)throw new TypeError(r+" must be a string");const i="number"==typeof(o=o||{}).version?o.version:2;if(1!==i&&2!==i)throw new TypeError(i+" must be 1 or 2");const s={method:r};if(2===i&&(s.jsonrpc="2.0"),t){if("object"!=typeof t&&!Array.isArray(t))throw new TypeError(t+" must be an object, array or omitted");s.params=t}if(void 0===e){const r="function"==typeof o.generator?o.generator:function(){return n()};s.id=r(s,o)}else 2===i&&null===e?o.notificationIdNull&&(s.id=null):s.id=e;return s}}}]);