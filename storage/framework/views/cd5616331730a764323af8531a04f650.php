<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <meta name="asset" content="<?php echo e(asset('/')); ?>">
  <base href="<?php echo e($admin_base_url); ?>">
  <title><?php echo e(__('admin/builder.text_edit_home')); ?></title>
  <script src="<?php echo e(asset('vendor/jquery/jquery-3.6.0.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/layer/3.5.1/layer.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/cookie/js.cookie.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/vue/2.7/vue' . (!config('app.debug') ? '.min' : '') . '.js')); ?>"></script>
  <script src="<?php echo e(mix('build/beike/admin/js/app.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/vue/Sortable.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/vue/vuedraggable.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/tinymce/5.9.1/tinymce.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/element-ui/index.js')); ?>"></script>
  <link rel="stylesheet" href="<?php echo e(asset('vendor/element-ui/index.css')); ?>">
  <?php if(locale() != 'zh_cn'): ?>
    <script src="<?php echo e(asset('vendor/element-ui/language/' . locale() . '.js')); ?>"></script>
  <?php endif; ?>
  <link rel="stylesheet" type="text/css" href="<?php echo e(asset('/build/beike/admin/css/design.css')); ?>">
  <?php echo $__env->yieldPushContent('header'); ?>
  <script>
    <?php if(locale() != 'zh_cn'): ?>
      ELEMENT.locale(ELEMENT.lang['<?php echo e(locale()); ?>'])
    <?php endif; ?>
    const lang = {
      file_manager: '<?php echo e(__('admin/file_manager.file_manager')); ?>',
    }

    const config = {
      beike_version: '<?php echo e(config('beike.version')); ?>',
      api_url: '<?php echo e(beike_api_url()); ?>',
      app_url: '<?php echo e(config('app.url')); ?>',
    }
  </script>
</head>

<body class="page-design">
  <div class="design-box">
    <div :class="['sidebar-edit-wrap', !design.sidebar ? 'v-hide' : '']" id="app" v-cloak v-loading="!design.ready">
      <div class="switch-design" :class="['hide-design', !design.sidebar ? 'v-hide' : '']" @click="design.sidebar = !design.sidebar"><i class="iconfont">{{ design.sidebar ? '&#xe659;' : '&#xe65b;' }}</i></div>

      <div class="design-head">
        <div v-if="design.editType != 'add'" @click="showAllModuleButtonClicked"><i class="el-icon-back"></i><?php echo e(__('common.return')); ?></div>
        <div @click="viewHome"><i class="el-icon-switch-button"></i><?php echo e(__('common.exit')); ?></div>
        <div @click="saveButtonClicked"><i class="el-icon-check"></i><?php echo e(__('common.save')); ?></div>
      </div>
      <div class="module-edit" v-if="form.modules.length > 0 && design.editType == 'module'">
        <component
          :is="editingModuleComponent"
          :key="design.editingModuleIndex"
          :module="form.modules[design.editingModuleIndex].content"
          @on-changed="moduleUpdated"
        ></component>
      </div>

      <div class="modules-list">
        <div style="padding: 5px; color: #666;"><i class="el-icon-microphone"></i> <?php echo e(__('admin/builder.modules_instructions')); ?></div>

        <el-row v-show="design.editType == 'add'" id="module-list-wrap">
          <el-col :span="12" v-for="(item, index) in source.modules" :key="index" class="iframe-modules-sortable-ghost">
            <div @click="addModuleButtonClicked(item.code)" class="module-list" :data-code="item.code">
              <div class="module-info">
                <div class="icon">
                  <i :style="item.style" class="iconfont" v-if="isIcon(item.icon)" v-html="item.icon"></i>
                  <div class="img-icon" v-else><img :src="item.icon" class="img-fluid"></div>
                </div>
                <div class="name">{{ item.name }}</div>
              </div>
            </div>
          </el-col>
        </el-row>
      </div>
    </div>
    <div class="preview-iframe">
      <iframe src="<?php echo e(url('/')); ?>?design=1" frameborder="0" id="preview-iframe" width="100%" height="100%"></iframe>
    </div>
  </div>

  <script>
    var $languages = <?php echo json_encode(locales(), 15, 512) ?>;
    var $locale = '<?php echo e(locale()); ?>';

    function languagesFill(text) {
      var obj = {};
      $languages.map(e => {
        obj[e.code] = text
      })

      return obj;
    }

    // iframe 操作
    var previewWindow = null;
    $('#preview-iframe').on('load', function(event) {
      previewWindow = document.getElementById("preview-iframe").contentWindow;
      app.design.ready = true;

      // 编辑模块
      $(previewWindow.document).on('click', '.module-edit .edit', function(event) {
        const module_id = $(this).parents('.module-item').prop('id').replace('module-', '');
        const modules = app.form.modules;
        const editingModuleIndex = modules.findIndex(e => e.module_id == module_id);
        app.editModuleButtonClicked(editingModuleIndex);
      });

      // 删除模块
      $(previewWindow.document).on('click', '.module-edit .delete', function(event) {
        const module_id = $(this).parents('.module-item').prop('id').replace('module-', '');
        const editingModuleIndex = app.form.modules.findIndex(e => e.module_id == module_id);
        app.design.editType = 'add';
        app.design.editingModuleIndex = 0;
        $(previewWindow.document).find('.tooltip').remove();
        $(this).parents('.module-item').remove();
        app.form.modules.splice(editingModuleIndex, 1);
      });

      // 模块位置改变，点击.module-edit .up或者.down
      $(previewWindow.document).on('click', '.module-edit .up, .module-edit .down', function(event) {
        const module_id = $(this).parents('.module-item').prop('id').replace('module-', '');
        const modules = app.form.modules;
        const editingModuleIndex = modules.findIndex(e => e.module_id == module_id);
        const up = $(this).hasClass('up');
        app.design.editType = 'add';
        app.design.editingModuleIndex = 0;
        if (up) {
          if (editingModuleIndex > 0) {
            const module = modules[editingModuleIndex];
            modules.splice(editingModuleIndex, 1);
            modules.splice(editingModuleIndex - 1, 0, module);
            // dom操作
            $(this).parents('.module-item').insertBefore($(this).parents('.module-item').prev());
          }
        } else {
          if (editingModuleIndex < modules.length - 1) {
            const module = modules[editingModuleIndex];
            modules.splice(editingModuleIndex, 1);
            modules.splice(editingModuleIndex + 1, 0, module);
            // dom操作
            $(this).parents('.module-item').insertAfter($(this).parents('.module-item').next());
          }
        }
        app.form.modules = modules;
      });

      new Sortable(document.getElementById('module-list-wrap'), {
        group: {
          name: 'shared',
          pull: 'clone',
          put: false // 不允许拖拽进这个列表
        },
        // ghostClass: 'iframe-modules-sortable-ghost',
        animation: 150,
        sort: false, // 设为false，禁止sort
        onEnd: function (evt) {
          if (evt.to.id != 'home-modules-box') {
            return;
          }

          // 获取 当前位置 在modules-box 是第几个
          const index = $(previewWindow.document).find('.modules-box').children().index(evt.item);
          const moduleCode = $(evt.item).find('.module-list').data('code');

          app.addModuleButtonClicked(moduleCode, index, () => {
            evt.item.parentNode.removeChild(evt.item);
          });
        }
      });

      new Sortable(previewWindow.document.getElementById('home-modules-box'), {
        group: {
          name: 'shared',
          pull: 'clone',
        },
        animation: 150,
        onUpdate: function (evt) {
          const modules = app.form.modules;
          const module = modules.splice(evt.oldIndex, 1)[0];
          modules.splice(evt.newIndex, 0, module);
          app.form.modules = modules;
        }
      });
    });
  </script>

  <?php $__currentLoopData = $editors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $editor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $editor] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\DynamicComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  <?php echo $__env->make('admin::pages.design.builder.component.image_selector', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->make('admin::pages.design.builder.component.link_selector', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->make('admin::pages.design.builder.component.text_i18n', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->make('admin::pages.design.builder.component.rich_text_i18n', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <script>
    let register = null;

    let app = new Vue({
      el: '#app',
      data: {
        form: {
          modules: []
        },

        design: {
          type: 'pc',
          editType: 'add',
          sidebar: true,
          editingModuleIndex: 0,
          ready: false,
          moduleLoadCount: 0, // 第一次选择已配置模块时，不需要请求数据，
        },

        source: {
          modules: [],
          config: []
        },
      },
      // 计算属性
      computed: {
        // 编辑中的模块编辑组件
        editingModuleComponent() {
          return 'module-editor-' + this.editingModuleCode.replace('_', '-');
        },

        // 编辑中的模块 code
        editingModuleCode() {
          return this.form.modules[this.design.editingModuleIndex].code;
        },
      },
      // 侦听器
      watch: {},
      // 组件方法
      methods: {
        moduleUpdated: bk.debounce(function(val) {
          if (!this.design.moduleLoadCount) return this.design.moduleLoadCount = 1;
          this.form.modules[this.design.editingModuleIndex].content = val;
          const data = this.form.modules[this.design.editingModuleIndex]

          $http.post('design/builder/preview?design=1', data, {hload: true}).then((res) => {
            $(previewWindow.document).find('#module-' + data.module_id).replaceWith(res);
            $(previewWindow.document).find('.tooltip').remove();
            const tooltipTriggerList = previewWindow.document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new previewWindow.bootstrap.Tooltip(tooltipTriggerEl))
          })
        }, 300),

        addModuleButtonClicked(code, moduleItemIndex = null, callback = null) {
          const sourceModule = this.source.modules.find(e => e.code == code)
          const module_id = bk.randomString(16)
          const _data = {
            code: code,
            content: sourceModule.make,
            module_id: module_id,
            name: sourceModule.name,
            view_path: sourceModule.view_path || '',
          }

          $http.post('design/builder/preview?design=1', _data, {hload: true}).then((res) => {
            if (moduleItemIndex === null) {
              $(previewWindow.document).find('.modules-box').append(res);
              this.form.modules.push(_data);
              this.design.editingModuleIndex = this.form.modules.length - 1;
              this.design.editType = 'module';
            } else {
              $(previewWindow.document).find('.modules-box').children().eq(moduleItemIndex).before(res);
              this.form.modules.splice(moduleItemIndex, 0, _data);
              this.design.editingModuleIndex = moduleItemIndex;
              this.design.editType = 'module';
            }

            setTimeout(() => {
              $(previewWindow.document).find("html, body").animate({
                scrollTop: $(previewWindow.document).find('#module-' + module_id).offset().top - 96
              }, 50);
            }, 200)
          }).finally(() => {
            if (callback) {
              callback();
            }
          })
        },

        // 编辑模块
        editModuleButtonClicked(index) {
          this.design.moduleLoadCount = 0;
          this.design.editingModuleIndex = index;
          this.design.editType = 'module';
        },

        saveButtonClicked() {
          $http.put('design/builder', this.form).then((res) => {
            layer.msg(res.message)
          })
        },

        exitDesign() {
          history.back();
        },

        viewHome() {
          location = '/';
        },

        isIcon(code) {
          // 判断 code 是否以 &# 开头
          return code.indexOf('&#') == 0;
        },

        showAllModuleButtonClicked() {
          this.design.editType = 'add';
          this.design.editingModuleIndex = 0;
        }
      },
      created () {
        this.form = <?php echo json_encode($design_settings ?: ['modules' => []], 15, 512) ?>
      },
      mounted () {
      },
    })
  </script>
  <?php echo $__env->yieldPushContent('footer-script'); ?>
</body>
</html>
<?php /**PATH G:\workspace\new\resources\/beike/admin/views/pages/design/builder/index.blade.php ENDPATH**/ ?>