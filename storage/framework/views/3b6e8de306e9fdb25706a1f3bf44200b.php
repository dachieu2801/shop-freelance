<template id="module-editor-slideshow-template">
  <div>
    <div class="module-editor-row"><?php echo e(__('admin/builder.text_set_up')); ?></div>
    <div class="module-edit-group">
      <div class="module-edit-title"><?php echo e(__('admin/builder.modules_full_screen')); ?></div>
      <el-switch v-model="form.full"></el-switch>
    </div>

    <div class="module-editor-row"><?php echo e(__('admin/builder.modules_content')); ?></div>
    <div class="module-edit-group">
      <div class="module-edit-title"><?php echo e(__('admin/builder.modules_select_image')); ?></div>
      <draggable
        ghost-class="dragabble-ghost"
        :list="form.images"
        :options="{animation: 330, handle: '.icon-rank'}"
      >
        <div class="pb-images-selector" v-for="(item, index) in form.images" :key="index">
          <div class="selector-head" @click="itemShow(index)">
            <div class="left">
              <el-tooltip class="icon-rank" effect="dark" content="<?php echo e(__('admin/builder.text_drag_sort')); ?>" placement="left">
                <i class="el-icon-rank"></i>
              </el-tooltip>

              <img :src="thumbnail(item.image['<?php echo e(locale()); ?>'], 40, 40)" class="img-responsive">
            </div>

            <div class="right">
              <el-tooltip class="" effect="dark" content="<?php echo e(__('admin/builder.text_delete')); ?>" placement="left">
                <div class="remove-item" @click.stop="removeImage(index)"><i class="el-icon-delete"></i></div>
              </el-tooltip>
              <i :class="'el-icon-arrow-'+(item.show ? 'up' : 'down')"></i>
            </div>
          </div>
          <div :class="'pb-images-list ' + (item.show ? 'active' : '')">
            <div class="pb-images-top">
              <pb-image-selector v-model="item.image"></pb-image-selector>
              <div class="tag"><?php echo e(__('admin/builder.text_suggested_size')); ?> 1920 x 600</div>
            </div>
            <link-selector v-model="item.link"></link-selector>
          </div>
        </div>
      </draggable>

      <div class="add-item">
        <el-button type="primary" size="small" @click="addImage" icon="el-icon-circle-plus-outline"><?php echo e(__('admin/builder.text_add_pictures')); ?></el-button>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">

Vue.component('module-editor-slideshow', {
  template: '#module-editor-slideshow-template',

  props: ['module'],

  data: function () {
    return {
      form: null
    }
  },

  watch: {
    form: {
      handler: function (val) {
        this.$emit('on-changed', val);
      },
      deep: true,
    }
  },

  created: function () {
    this.form = JSON.parse(JSON.stringify(this.module));
  },

  methods: {
    removeImage(index) {
      this.form.images.splice(index, 1);
    },

    itemShow(index) {
      this.form.images.find((e, key) => {if (index != key) return e.show = false});
      this.form.images[index].show = !this.form.images[index].show;
    },

    addImage() {
      this.form.images.find(e => e.show = false);
      this.form.images.push({image: languagesFill('catalog/demo/banner/banner-4-en.jpg'), show: true, link: {type: 'product', value:''}});
    }
  }
});

</script>

<?php $__env->startPush('footer-script'); ?>
  <script>
    register = <?php echo json_encode($register, 15, 512) ?>;

    // 定义模块的配置项
    register.make = {
      style: {
        background_color: ''
      },
      full: true,
      floor: languagesFill(''),
      images: [
        {
          image: languagesFill('catalog/demo/banner/banner-4-en.jpg'),
          show: true,
          link: {
            type: 'product',
            value:''
          }
        },
        {
          image: languagesFill('catalog/demo/banner/banner-3-en.jpg'),
          show: false,
          link: {
            type: 'product',
            value:''
          }
        }
      ]
    }

    app.source.modules.push(register)
  </script>
<?php $__env->stopPush(); ?><?php /**PATH G:\workspace\new\resources\/beike/admin/views/pages/design/module/slideshow.blade.php ENDPATH**/ ?>