const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

//拷贝inspinia
mix.copy('resources/assets/inspinia/js','public/assets/inspinia/js');
mix.copy('resources/assets/inspinia/css','public/assets/inspinia/css');
mix.copy('resources/assets/inspinia/img','public/assets/inspinia/img');
mix.copy('resources/assets/inspinia/fonts','public/assets/inspinia/fonts');
mix.copy('resources/assets/inspinia/font-awesome','public/assets/inspinia/font-awesome');

//拷贝AdminLTE相关的文件和插件
mix.copy('resources/assets/plugins/AdminLTE/dist','public/assets/adminlte');
mix.copy('resources/assets/plugins/AdminLTE/plugins','public/assets/adminlte/plugins');
//拷贝bootstrap到css目录
mix.copy('resources/assets/plugins/AdminLTE/bootstrap/css/bootstrap.min.css','public/assets/css/bootstrap.min.css');
//拷贝mloding
mix.copy('resources/assets/plugins/mloading/jquery.mloading.css','public/assets/css/jquery.mloading.css').version();
//拷贝看图插件
mix.copy('resources/assets/plugins/fancybox','public/assets/fancybox');
//拷贝看图插件 fancybox3
mix.copy('resources/assets/plugins/fancybox3','public/assets/fancybox3');
//拷贝 barcode js
mix.copy('resources/assets/plugins/barcode','public/assets/barcode');
//拷贝layer插件
mix.copy('resources/assets/plugins/layer','public/assets/layer');
//拷贝imagezoom
mix.copy('resources/assets/plugins/imagezoom/jquery.imagezoom.min.js','public/assets/imagezoom/jquery.imagezoom.min.js');
//拷贝图片懒加载插件
mix.copy('resources/assets/plugins/lazyload','public/assets/lazyload');
//copy jquery.media.js
mix.copy('resources/assets/js/jquery.media.js','public/assets/js/jquery.media.js');
mix.copy('resources/assets/js/echarts.simple.min.js','public/assets/js/echarts.simple.min.js');

//webuploader
mix.copy('resources/assets/plugins/webuploader', 'public/assets/webuploader');

//文件下载
mix.scripts([
    'jszip.js',
    'FileSaver.js',
    'jszip-utils.min.js',
],  'public/assets/js/patpat-file-download.js');

//layui
mix.copy('resources/assets/plugins/layui', 'public/assets/layui');
//拷贝自定义导出
mix.copy('resources/assets/js/export.js','public/assets/js/export.js');
//拷贝file-save
mix.copy('resources/assets/js/file-saver.js','public/assets/js/file-saver.js');
//拷贝sheetjs
mix.copy('resources/assets/js/xlsx.core.min.js','public/assets/js/xlsx.core.min.js');

//拷贝图片
mix.copy('resources/assets/img','public/assets/img');
//拷贝 FooTable js
mix.copy('resources/assets/js/footable.min.js','public/assets/js/footable.min.js');

//将所有的less转css
mix.less("resources/assets/less/app.less",'public/assets/css/application.min.css').version();

//合并所有css
mix.styles([
    'resources/assets/plugins/toastr/toastr.min.css',
    'resources/assets/plugins/sweetalert/dist/sweetalert.css',
    "resources/assets/css/style.css",
],'public/assets/css/style.min.css').version();


//合并所有js
mix.scripts([
    'resources/assets/plugins/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js',
    'resources/assets/plugins/AdminLTE/bootstrap/js/bootstrap.min.js',
    // 'plugins/vue/vue.js',
    'resources/assets/plugins/cookie/jquery.cookie.min.js',
    'resources/assets/plugins/toastr/toastr.min.js',
    'resources/assets/plugins/sweetalert/dist/sweetalert.min.js',
    "resources/assets/js/adminlte_rightside.js",
    "resources/assets/js/jquery-ui.js",
    "resources/assets/js/common.js",
    "resources/assets/js/app.js",
    'resources/assets/plugins/mloading/jquery.mloading.js',
],'public/assets/js/application.min.js').version();

//合并所有js
mix.scripts([
    'resources/assets/inspinia/js/jquery-2.1.1.js',
    'resources/assets/inspinia/js/bootstrap.min.js',
    'resources/assets/inspinia/js/plugins/metisMenu/jquery.metisMenu.js',
    'resources/assets/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js',
    'resources/assets/inspinia/js/plugins/flot/jquery.flot.js',
    'resources/assets/inspinia/js/plugins/flot/jquery.flot.tooltip.min.js',
    "resources/assets/inspinia/js/plugins/flot/jquery.flot.spline.js",
    "resources/assets/inspinia/js/plugins/flot/jquery.flot.resize.js",
    "resources/assets/inspinia/js/plugins/flot/jquery.flot.pie.js",
    "resources/assets/inspinia/js/plugins/peity/jquery.peity.min.js",
    'resources/assets/inspinia/js/inspinia.js',
    'resources/assets/inspinia/js/plugins/pace/pace.min.js',
    'resources/assets/inspinia/js/plugins/jquery-ui/jquery-ui.min.js',
    'resources/assets/inspinia/js/plugins/gritter/jquery.gritter.min.js',
    'resources/assets/inspinia/js/plugins/sweetalert/sweetalert.min.js',
    'resources/assets/inspinia/js/plugins/sparkline/jquery.sparkline.min.js',
    'resources/assets/inspinia/js/plugins/datapicker/bootstrap-datepicker.js',
    'resources/assets/inspinia/js/plugins/chartJs/Chart.min.js',
    'resources/assets/inspinia/js/plugins/toastr/toastr.min.js',
    'resources/assets/inspinia/js/plugins/footable/footable.all.min.js',
    'resources/assets/inspinia/js/plugins/iCheck/icheck.min.js',
    'resources/assets/inspinia/js/plugins/blueimp/jquery.blueimp-gallery.min.js',
],'public/assets/js/application_new.min.js').version();

//给js和css加版本号
//mix.version(['assets/css/application.min.css','assets/css/style.min.css','assets/js/application.min.js','assets/css/jquery.mloading.css','assets/js/application_new.min.js']);
/* mix.sass('app.scss');*/
