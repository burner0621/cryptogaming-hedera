if(!self.define){let e,s={};const a=(a,d)=>(a=new URL(a+".js",d).href,s[a]||new Promise((s=>{if("document"in self){const e=document.createElement("script");e.src=a,e.onload=s,document.head.appendChild(e)}else e=a,importScripts(a),s()})).then((()=>{let e=s[a];if(!e)throw new Error(`Module ${a} didn’t register its module`);return e})));self.define=(d,i)=>{const r=e||("document"in self?document.currentScript.src:"")||location.href;if(s[r])return;let c={};const f=e=>a(e,r),n={module:{uri:r},exports:c,require:f};s[r]=Promise.all(d.map((e=>n[e]||f(e)))).then((e=>(i(...e),c)))}}define(["./workbox-3e08d8a4"],(function(e){"use strict";e.setCacheNameDetails({prefix:"stake-pwa"}),self.skipWaiting(),e.clientsClaim(),e.precacheAndRoute([{url:"/audio/crash/rocket.mp4",revision:"0899082aba43cd6bd8c5dc8455b4e1a8"},{url:"/fonts/vendor/@mdi/materialdesignicons-webfont.eot?ac209be0c79e5dc10480fc3465741ba1",revision:"e30896f3c82228c900887d50d203b21d"},{url:"/fonts/vendor/@mdi/materialdesignicons-webfont.ttf?28ce6d308b0d5228531758d28dda3297",revision:"dd74f11e425603c7adb66100f161b2a5"},{url:"/fonts/vendor/@mdi/materialdesignicons-webfont.woff2?626f06dbe2e83a32e735458c474a7226",revision:"4a1b1c4acaf219c1f994743661b51150"},{url:"/fonts/vendor/@mdi/materialdesignicons-webfont.woff?e3dde18cd4c463d07847bca67e255a72",revision:"7a6671325b3bb365dc5ff5ff0b505b7c"},{url:"/images/airplane.svg?3a46316ed90db23495aca7c64f564b84",revision:"a6aaac5a855521ef83cd5641b004b973"},{url:"/images/animation.png?9c992e07c0d33de5e91fd9b7a96c2ba3",revision:"51e555ee2f240776485e0122d8c59993"},{url:"/images/any.png?8ff6ad712bded36b043a874ccb41d4fb",revision:"29b954e3072a5efc669bb670a57aa8b2"},{url:"/images/ball.png?85b4a878a919961347dffc385d51489c",revision:"94297dabd0c136e81a2932cc5e66c466"},{url:"/images/body.png?fb490f9b189ea5790870f1555240fb2d",revision:"989bb6ccf74056b4de418a530fbebdd1"},{url:"/images/clouds.svg?142228615ba26be9a481dcc4338e3cd9",revision:"d209bedb543298c869e6867ed000d388"},{url:"/images/explosion.svg?88b717fb08b583aeec97e134189199e7",revision:"1cd76f4d9d36f2f081496f33f8ede5f2"},{url:"/images/face.png?2d6522ae00d9b56d0e3cd5d9154c6181",revision:"b1866e47866f4228836518f7d3e45c19"},{url:"/images/fence.png?29e9dfc078c172966296d7e0bf1c68f0",revision:"b7f090aab0d3970f8578af98901bb149"},{url:"/images/fence_post.png?90805e260c2a6d5ab8392de6895a7bb4",revision:"0c969006408194e07e173cf524e68c13"},{url:"/images/grass.jpg?de94cd9e3917643b88071a86862e29fa",revision:"d12092542b7ee669f30240585bb27435"},{url:"/images/head.png?3f54aeb4081ac7da98b34dc6ed7b4390",revision:"5ba820a613d3360b7dd9c3f6972ebcf9"},{url:"/images/horse.png?d5a6db45d838eb6516fc96830c089884",revision:"8f76a6d10cfd41486ccd7cdcabb1d466"},{url:"/images/num_1.png?f5cab810df6f1df9cd55a0450d4f1609",revision:"73cfe707c1ea0b1df804f13d640c1279"},{url:"/images/num_10.png?d2549367a926585d2a939de97ff59393",revision:"85630010a9020051337498c13e6377ff"},{url:"/images/num_11.png?315b823889620b37568677974f292bcf",revision:"c60c1efcc054784ec3e22878172440b2"},{url:"/images/num_12.png?b3b08e65a471394f614a0f96a3581e41",revision:"83e0e34074db9c9ecabaea12caa91c7a"},{url:"/images/num_2.png?72aba1ba66f565feec6f3b310c85f24b",revision:"34d3aa41d5f64dbdd7fd173556fe36ea"},{url:"/images/num_3.png?a1bcbdd7906b2a422c11b8c8cd2cc418",revision:"6938b87c7956f428ae8f7d7750b93d0e"},{url:"/images/num_4.png?b569f981c8ecf8204918cbf869584f92",revision:"05c35cc93bec7e62d1456e1585d5f304"},{url:"/images/num_5.png?9f6fb518f8fd899855e33cb265be20c8",revision:"d5783b585ec380c5c837e27be74f3724"},{url:"/images/num_6.png?32911be5911ef36899f552f1f8e7c992",revision:"022091dcb11031895d05d6e3a1d767a3"},{url:"/images/num_7.png?91591370699e4e24a2cfa65ffa5fd179",revision:"1f1f280e139e3085ea4638c06686e6a5"},{url:"/images/num_8.png?834af04a5c369c8511645848f6538769",revision:"37d8b2a1303f148432a7e5eae813a019"},{url:"/images/num_9.png?435e68e99a974bdba7f28c66981d3094",revision:"5936eee6cbba87fd43d1c66f89df3887"},{url:"/images/planet.svg?89f59e57f4396060031701495273d5b7",revision:"c287df498d284f9e82422a67eb5d7900"},{url:"/images/rocket-stay.svg?ea4ba360e9f2ad9078581a6b420ecc78",revision:"87a79abd1ca885072de6cf8ea76e32a6"},{url:"/images/rocket.svg?9613cb433e44ea852b4493f92caf284b",revision:"d29ac32410c85823b8634138fa537403"},{url:"/images/saddle.png?32a0cdf77a80cd0054fec70afb4df789",revision:"057f72588cfbbf2b925a8d0ad820e24f"},{url:"/images/sand.jpg?ed12aaf7f23b8c5bd1a892ec2ab56cd8",revision:"99a135be2b88793799e9a1538113664e"},{url:"/images/shadow.png?8202a6efacf5d904c85067ee4c7fb1b7",revision:"c98c8b72c9b072690cffd980a7e3a289"},{url:"/images/tail.png?a30f5a7cf9fa9df7a5c22c288a56359d",revision:"00d18530d1803e7ba520da2ba83c973e"},{url:"/js/2fa.js",revision:"0edef397c5360a7dc3c5a43d91b11dcc"},{url:"/js/404.js",revision:"fdff98e809a005d6376782b52f1961a6"},{url:"/js/503.js",revision:"093363211decb7a314b6b74829878c8f"},{url:"/js/admin-accounts-credit.js",revision:"860da4360f18b99b0661bbbec8889885"},{url:"/js/admin-accounts-debit.js",revision:"4c9a13c9e843dd395ede2a03317da989"},{url:"/js/admin-accounts-show.js",revision:"63e496ded6b32087f4abb729f1ee53e0"},{url:"/js/admin-accounts-transactions.js",revision:"bf4c064160dbb03b2f882f8ae861d6ca"},{url:"/js/admin-accounts.js",revision:"a3baf660325bc96f0d4340d4c4a94e35"},{url:"/js/admin-add-ons-bundle.js",revision:"84568e7e8696986fe650fad546031208"},{url:"/js/admin-add-ons-changelog.js",revision:"7427e969e7fcc6db522769db51b43a7b"},{url:"/js/admin-add-ons-install.js",revision:"ea7639787d33986a78f60d48de61f25f"},{url:"/js/admin-add-ons.js",revision:"053af537de0710276a11a5da1564d635"},{url:"/js/admin-affiliate-commissions-approve.js",revision:"fde10fa88aaa61d58cf640c6a94ec9f7"},{url:"/js/admin-affiliate-commissions-reject.js",revision:"d95a8292c2d4e87fdcf4b58e120582a9"},{url:"/js/admin-affiliate-commissions-show.js",revision:"3366f64723a84a73d1449fa9898e7ce8"},{url:"/js/admin-affiliate-commissions.js",revision:"ed4630133bfca6fe0dcb69cdd50ab238"},{url:"/js/admin-affiliate-tree.js",revision:"7b97d080ef86cffc73b44e224148ff2e"},{url:"/js/admin-affiliate.js",revision:"18b77efc0d585864324b6278d069dd5d"},{url:"/js/admin-bonuses-general.js",revision:"4b470f880f801839e72f4197b338593a"},{url:"/js/admin-bonuses.js",revision:"2de71ece945a0c17730051ed9adf1cf4"},{url:"/js/admin-chat-messages-delete.js",revision:"fedd7f9e91c76a1d54aa82b1b08d1c99"},{url:"/js/admin-chat-messages.js",revision:"e0ef54ad1bda2bd188b19773af968f57"},{url:"/js/admin-chat-rooms-create.js",revision:"6ba5e0dd4e6064c483ee31aa3edc9587"},{url:"/js/admin-chat-rooms-delete.js",revision:"4ff0ffb9744e0a68cae50363fb9919da"},{url:"/js/admin-chat-rooms-edit.js",revision:"a7ec448593bae066dfa4d48929e47906"},{url:"/js/admin-chat-rooms.js",revision:"15d9609adcf00f3ebdc7025db57d16ff"},{url:"/js/admin-chat.js",revision:"89a6e18fb82b12776a76f7fc50087931"},{url:"/js/admin-dashboard-affiliates.js",revision:"92b94385274d0f6e8b02c83af9560c7d"},{url:"/js/admin-dashboard-bonuses.js",revision:"fcf1ce563ce5eda7f3fc8c907fafff8c"},{url:"/js/admin-dashboard-games.js",revision:"78d684af171f15befebde52397de8a2a"},{url:"/js/admin-dashboard-users.js",revision:"45467412bde7cb6e57db2ee59ebf91ec"},{url:"/js/admin-dashboard.js",revision:"e52803185e845ff7d5bf2621073adbe0"},{url:"/js/admin-games-show.js",revision:"eeb3cc7fc285f709e9a19eaa67f64b93"},{url:"/js/admin-games.js",revision:"78066f3d69577c583831254a6435d164"},{url:"/js/admin-help.js",revision:"fa39acc57e3ca221128c85fb260ebf59"},{url:"/js/admin-license.js",revision:"65fa06a020ecc4b82074e9a19e5f93f3"},{url:"/js/admin-maintenance-failed-jobs.js",revision:"5c8893a93093820166c7b14ffb20e54b"},{url:"/js/admin-maintenance-jobs.js",revision:"0f354c3e54b40b7ff0f1d583ca0a0325"},{url:"/js/admin-maintenance-logs-show.js",revision:"a68632281e91e5b046215912f8ffef41"},{url:"/js/admin-maintenance.js",revision:"53e40a664f38803d5d5eb37a4180e7c2"},{url:"/js/admin-settings.js",revision:"519da9ad1fe6c57a020bb3bd876d0266"},{url:"/js/admin-tests-poker.js",revision:"366fd9c2ba07dd1b8fd06f3d051de6d5"},{url:"/js/admin-users-delete.js",revision:"cb4ba5a978080ff6c312f259e4b063cd"},{url:"/js/admin-users-edit.js",revision:"c61a8bd086df885016c1485bf6cb3076"},{url:"/js/admin-users-mail.js",revision:"1ba3c5b39408b6433419f123a3c7c9a4"},{url:"/js/admin-users-show.js",revision:"0cc6cf45172600ba4491394a4df16c0b"},{url:"/js/admin-users.js",revision:"179f7153b584923c2e132a11d9116177"},{url:"/js/components.js",revision:"1a712df483d7e76784ea0a4caa1b1820"},{url:"/js/email-verify.js",revision:"cb38691bb8a24cc78b6aef0e6732950a"},{url:"/js/email.js",revision:"266a094a20851d2e3775cacc5a29d2db"},{url:"/js/games.js",revision:"1e1049ea9ed96fd554e28510360c378f"},{url:"/js/history-games-show.js",revision:"11347eb4ebb92b84787ee4fa35517319"},{url:"/js/history-games-verify.js",revision:"e955b6413865279c336d3c34b625f6f7"},{url:"/js/history-losses.js",revision:"53d2c8190150971cb99bc4a9e27b73b7"},{url:"/js/history-recent.js",revision:"e8cd16759b370f67e25c573c567e062e"},{url:"/js/history-user.js",revision:"b552c34867d4b0a72321158b79cced3e"},{url:"/js/history-wins.js",revision:"f2490343d4204ee365acf990e2a87b1f"},{url:"/js/history.js",revision:"3f47e38dd09901ffbb0dd3e49cdf70c1"},{url:"/js/leaderboard.js",revision:"a8f7854d57b0ab17efd54f1ff5f6727f"},{url:"/js/login-web3.js",revision:"476dd141e48a124e2d36a9cf43b2d22e"},{url:"/js/login.js",revision:"ce158ad3f5abfa1aae4415d1429d2b45"},{url:"/js/mixins.js",revision:"5cd40deadab4b7bb5d26c85ec99047ae"},{url:"/js/offline.js",revision:"b203d6942c2ebb6bd9f8dda9d26a8d0d"},{url:"/js/pages.js",revision:"bed4483544754a78477c094c12a7da1f"},{url:"/js/password-email.js",revision:"56f19d10ecd51b066f3dd92f9974b091"},{url:"/js/password-reset.js",revision:"27de38a477f2dd6dfa709f15debc8229"},{url:"/js/predictions.js",revision:"d44bff90aa2d40637dea9670ccdb3a29"},{url:"/js/register.js",revision:"f4d5ab4432a5b7c506fe513a1fa62bbd"},{url:"/js/user-account-faucet.js",revision:"0bd659e78349d730a3171f3f79c83575"},{url:"/js/user-account-transactions.js",revision:"9a1c14e869be1d38b51bc7105e5ded7a"},{url:"/js/user-affiliate-commissions.js",revision:"44658b65d40247347ef9f33dc7c06493"},{url:"/js/user-affiliate-info.js",revision:"9f266dc048713688011ab305ecfa8023"},{url:"/js/user-affiliate-stats.js",revision:"ed77bcce2e6f3096280be46876023e5f"},{url:"/js/user-affiliate.js",revision:"a7ab2e26efde51b6eedee71b34fe88c0"},{url:"/js/user-edit.js",revision:"d3329848e4fd8a25dfb2b3dc1e94b485"},{url:"/js/user-security-2fa.js",revision:"09fa5b6192ce38f8533d2bc3b651b489"},{url:"/js/user-security-password.js",revision:"bb0e4e5e8b0660979d4c0af1a316ff84"},{url:"/js/user-security.js",revision:"b9b807922c0ad26c5f054cf300f1a497"},{url:"/js/users-show.js",revision:"e65f6ad0cca615312f16eb33f240ac90"},{url:"/js/vendor/amcharts.js",revision:"c45c9e0dab6172b4cf10732c60e48da8"},{url:"/js/vendor/animejs.js",revision:"a7e8d45299c854c9e596611a2bbe568b"},{url:"/js/vendor/array-shuffle.js",revision:"43b41f992eceb9d8fba39edf63b8bd90"},{url:"/js/vendor/babel.js",revision:"ba104e9579771b3c3f01d52dad04ea2d"},{url:"/js/vendor/base-x.js",revision:"bb5d9f023c493a4168030be09259dd89"},{url:"/js/vendor/bigint-buffer.js",revision:"a31b24006c65b97a2a70b1de4b4255bc"},{url:"/js/vendor/bn.js.js",revision:"03bb08668a7cc00a5c91dbcd55afdf74"},{url:"/js/vendor/borsh.js",revision:"41b1c29a7e306da2d9a27db731a06dd6"},{url:"/js/vendor/bs58.js",revision:"da93f992eda919d513ffc20c68496830"},{url:"/js/vendor/cannon.js",revision:"0d10082160932a4eb3825e914037ef18"},{url:"/js/vendor/color-name.js",revision:"7863ea0bd58a54b59c76676836a36acc"},{url:"/js/vendor/color-string.js",revision:"ef83bfd4ea9223802e09f4a8f6792b8b"},{url:"/js/vendor/color.js",revision:"987aa7c258bab9ab3f5f5aa70cdf050e"},{url:"/js/vendor/colornames.js",revision:"f67edb7eca63914840405f9599f6dfe3"},{url:"/js/vendor/eventemitter3.js",revision:"6654c394dc776d4d2491f5b32e9cfa8c"},{url:"/js/vendor/fast-array-diff.js",revision:"0367a2a53395b1f005a91a39195a17c7"},{url:"/js/vendor/gsap.js",revision:"8349de34cafddd9d70de70b8c68fa436"},{url:"/js/vendor/image-promise.js",revision:"b1555f0c2c500365f2bcadbe99b6347a"},{url:"/js/vendor/jayson.js",revision:"24a1dace51a3052b0d94d7ca0e6569b3"},{url:"/js/vendor/matter-js.js",revision:"83fc6d99bc306207996eadef574226ac"},{url:"/js/vendor/noble.js",revision:"a6753650f34badb5303dc114cc235c43"},{url:"/js/vendor/pdfmake.js",revision:"63bcae78d078029f49fe8e5f29308103"},{url:"/js/vendor/performance-now.js",revision:"da4ac2f8c75f3f07175912002ab66e5f"},{url:"/js/vendor/raf.js",revision:"6938d3e6ed950e7d12d907aa8167b7ec"},{url:"/js/vendor/regenerator-runtime.js",revision:"05e914d6143371bb8688af4b39f95aa9"},{url:"/js/vendor/rgbcolor.js",revision:"3bd828d12e962f194c753dc421e47851"},{url:"/js/vendor/rpc-websockets.js",revision:"9579807f47d2b51bb65ee14e70e503b5"},{url:"/js/vendor/safe-buffer.js",revision:"76eecbcce33aa38c6d8f63ef6902c6e9"},{url:"/js/vendor/simple-swizzle.js",revision:"1b53c1a1708f5fc34527754c08138e1d"},{url:"/js/vendor/solana.js",revision:"24efc1b0a84aa92d44689c0bfdf19dd2"},{url:"/js/vendor/sortablejs.js",revision:"2d3ae40ec4267e84a90ef445b8cd4937"},{url:"/js/vendor/stackblur-canvas.js",revision:"7de977b5d5ee6a82edb6db3a0dc6823b"},{url:"/js/vendor/superstruct.js",revision:"41a58576f2f31a35ad3c65eb50af9ab3"},{url:"/js/vendor/text-encoding-utf-8.js",revision:"e5eb3051fe301a61203ff068615f42dd"},{url:"/js/vendor/three.js",revision:"ea2c44d7489df183c3170770beb7da5c"},{url:"/js/vendor/tslib.js",revision:"dec51c755a2f7a5ddaa5333b4711a1b5"},{url:"/js/vendor/tweenjs.js",revision:"9f594f98852270f8ec1de511a3c757cb"},{url:"/js/vendor/uuid.js",revision:"ed3c75a41050fb281cc88d34058408bc"},{url:"/js/vendor/v-click-outside.js",revision:"8a3c7851658e296f7e9238ac72a0ef14"},{url:"/js/vendor/vue-fullscreen.js",revision:"b2c957b5dd2f7c3271f493dccb4ce1f5"},{url:"/js/vendor/vue-recaptcha.js",revision:"e2c5d3b99e1c25c9b996f92ca78cdc5f"},{url:"/js/vendor/vue-tiny-slider.js",revision:"0adca2d3cd39b9fe4e4dee5883ec48b6"},{url:"/js/vendor/vuedraggable.js",revision:"78e29f13589c09d142417d8e199f20dd"},{url:"/js/vendor/vuetify.js",revision:"3145d7c8204cb3ae99c4727f216c4cca"},{url:"/js/vendor/web3.js",revision:"155cf1d4719cf824eec42d8ac4a01908"},{url:"/js/vendor/xlsx.js",revision:"bd2896e4078f6971ad2404ff57a72b21"},{url:"/js/xlsx.js",revision:"2845711553d93e9b2cd021d6fb5a3b71"}],{}),e.registerRoute(/^https:\/\/fonts\.(?:googleapis|gstatic)\.com\//,new e.NetworkFirst,"GET"),e.registerRoute(/\.(?:png|jpg|jpeg|svg|wav|mp3|webm|eot|ttf|woff|woff2)$/,new e.CacheFirst({cacheName:"assets",plugins:[new e.ExpirationPlugin({maxEntries:50})]}),"GET")}));