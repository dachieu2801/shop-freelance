@extends('admin::layouts.master')

@section('title', __('admin/setting.index'))

@section('page-bottom-btns')
  <button type="button" class="btn btn-lg w-min-100 btn-primary submit-form" form="app">{{ __('common.save') }}</button>
  <button class="btn btn-lg btn-default w-min-100 ms-3" onclick="bk.back()">{{ __('common.return') }}</button>
@endsection

@push('header')
  <script src="{{ asset('vendor/cropper/cropper.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/cropper/cropper.min.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

@endpush

@section('content')
  <div id="plugins-app-form" class="card h-min-600">
    <div class="card-body">
      <form action="{{ admin_route('settings.store') }}" class="needs-validation" novalidate method="POST" id="app" v-cloak>
        @csrf
        @if (session('success'))
          <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
        @endif
        @if (session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
        @endif
        <ul class="nav nav-tabs nav-bordered mb-5" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#tab-general">{{ __('admin/setting.basic_settings') }}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-store">{{ __('admin/setting.store_settings') }}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-checkout">{{ __('admin/setting.checkout_settings') }}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-image">{{ __('admin/setting.picture_settings') }}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-express-company">{{ __('order.express_company') }}</a>
          </li>
          <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab-shop-address"> Địa chỉ cửa hàng </a>
                </li>
{{--          <li class="nav-item" role="presentation">--}}
{{--            <a class="nav-link" data-bs-toggle="tab" href="#tab-mail">{{ __('admin/setting.mail_settings') }}</a>--}}
{{--          </li>--}}
          @hook('admin.setting.nav.after')
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-general">
            @hook('admin.setting.general.before')
            <x-admin-form-input name="meta_title" title="{{ __('admin/setting.meta_title') }}" value="{{ old('meta_title', system_setting('base.meta_title', '')) }}" />
            <x-admin-form-textarea name="meta_description" title="{{ __('admin/setting.meta_description') }}" value="{{ old('meta_description', system_setting('base.meta_description', '')) }}" />
            <x-admin-form-textarea name="meta_keywords" title="{{ __('admin/setting.meta_keywords') }}" value="{{ old('meta_keywords', system_setting('base.meta_keywords', '')) }}" />
            <x-admin-form-input name="telephone" title="{{ __('admin/setting.telephone') }}" value="{{ old('telephone', system_setting('base.telephone', '')) }}" />
            <x-admin-form-input name="email" title="{{ __('admin/setting.email') }}" value="{{ old('email', system_setting('base.email', '')) }}" />
            <!-- {{-- <x-admin-form-input name="license_code" title="{{ __('admin/setting.license_code') }}" value="{{ old('license_code', system_setting('base.license_code', '')) }}" /> --}} -->
            <!-- <x-admin::form.row title="{{ __('admin/setting.license_code') }}">
              <div class="input-group wp-400">
                <input type="text" class="form-control text-uppercase bg-light" name="license_code" placeholder="{{ __('admin/setting.license_code') }}" value="{{ old('license_code', system_setting('base.license_code', '')) }}" readonly="readonly">
                <button class="btn btn-outline-primary get-license-code" type="button">{{ __('admin/setting.license_code_get') }}</button>
              </div>
            </x-admin::form.row> -->
            @hook('admin.setting.general.after')
          </div>

          <div class="tab-pane fade" id="tab-store">
            @hook('admin.setting.store.before')
            <x-admin::form.row title="{{ __('admin/setting.default_address') }}">
              <div class="d-lg-flex">
                <div>
                  <select class="form-select wp-200 me-3" name="country_id" aria-label="Default select example">
                    @foreach ($countries as $country)
                      <option
                        value="{{ $country->id }}"
                        {{ $country->id == system_setting('base.country_id', '1') ? 'selected': '' }}>
                        {{ $country->name }}
                      </option>
                    @endforeach
                  </select>
                  <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_country_set') }}</div>
                </div>
                <div>
                  <select class="form-select wp-200 zones-select" name="zone_id" aria-label="Default select example"></select>
                  <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_zone_set') }}</div>
                </div>
              </div>
            </x-admin::form.row>

            <x-admin-form-select title="{{ __('admin/setting.default_currency') }}" name="currency" :value="old('currency', system_setting('base.currency', 'USD'))" :options="$currencies->toArray()" key="code" label="name">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_currency') }}</div>
            </x-admin-form-select>

            <x-admin-form-select title="{{ __('admin/setting.default_language') }}" name="locale" :value="old('locale', system_setting('base.locale', 'zh_cn'))" :options="$languages" key="code" label="name">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_language') }}</div>
            </x-admin-form-select>

            @php
              $weights = [['code'=>'kg','name'=>'kg'], ['code'=>'g','name'=>'g'], ['code'=>'oz','name'=>'oz'], ['code'=>'lb','name'=>'lb']];
            @endphp
            <x-admin-form-select title="{{ __('admin/setting.weight_unit') }}" name="weight" :value="old('weight', system_setting('base.weight', 'kg'))" :options="$weights" key="code" label="name">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.weight_unit') }}</div>
            </x-admin-form-select>

            <x-admin-form-select title="{{ __('admin/setting.default_customer_group') }}" name="default_customer_group_id" :value="old('locale', system_setting('base.default_customer_group_id', ''))" :options="$customer_groups" key="id" label="name">
                <div class="help-text font-size-12 lh-base">{{ __('admin/setting.default_customer_group') }}</div>
            </x-admin-form-select>

            <x-admin-form-input name="admin_name" title="{{ __('admin/setting.admin_name') }}" required value="{{ old('admin_name', system_setting('base.admin_name', 'admin')) }}">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.admin_name_info') }}</div>
            </x-admin-form-input>

            <x-admin-form-input name="product_per_page" title="{{ __('admin/setting.product_per_page') }}" required value="{{ old('product_per_page', system_setting('base.product_per_page', 20)) }}">
            </x-admin-form-input>

            <x-admin-form-input name="cdn_url" title="{{ __('admin/setting.cdn_url') }}" value="{{ old('cdn_url', system_setting('base.cdn_url', '')) }}">
            </x-admin-form-input>

            <x-admin-form-textarea name="head_code" title="{{ __('admin/setting.head_code') }}" value="{!! old('head_code', system_setting('base.head_code', '')) !!}">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.head_code_info') }}</div>
            </x-admin-form-textarea>

            @hook('admin.setting.store.after')

          </div>

          <div class="tab-pane fade" id="tab-image">

            @hook('admin.setting.image.before')

            <x-admin::form.row title="Logo">
              <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="380/100">
                <img src="{{ image_resize(old('logo', system_setting('base.logo', ''))) }}" class="img-fluid">
              </div>
              <input type="hidden" value="{{ old('logo', system_setting('base.logo', '')) }}" name="logo">
              <div class="help-text font-size-12 lh-base">{{ __('common.recommend_size') }} 380*100</div>
            </x-admin::form.row>

            <x-admin::form.row title="favicon">
              <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="32/32">
                <img src="{{ image_resize(old('favicon', system_setting('base.favicon', ''))) }}" class="img-fluid">
              </div>
              <input type="hidden" value="{{ old('favicon', system_setting('base.favicon', '')) }}" name="favicon">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.favicon_info') }}</div>
            </x-admin::form.row>

            <x-admin::form.row :title="__('admin/setting.placeholder_image')">
              <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="500/500">
                <img src="{{ image_resize(old('placeholder', system_setting('base.placeholder', ''))) }}" class="img-fluid">
              </div>
              <input type="hidden" value="{{ old('placeholder', system_setting('base.placeholder', '')) }}" name="placeholder">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.placeholder_image_info') }}</div>
            </x-admin::form.row>

            @hook('admin.setting.image.after')
          </div>

          <div class="tab-pane fade" id="tab-express-company">
            @hook('admin.setting.express.before')
            <x-admin::form.row title="{{ __('order.express_company') }}">
              <table class="table table-bordered w-max-600">
                <thead>
                  <th>{{ __('order.express_company') }}</th>
                  <th>Code</th>
                  @hook('admin.setting.express.table.thead.th')
                  <th></th>
                </thead>
                <tbody>
                  <tr v-for="item, index in express_company" :key="index">
                    <td>
                      <input required placeholder="{{ __('order.express_company') }}" type="text" :name="'express_company['+ index +'][name]'" v-model="item.name" class="form-control">
                      <div class="invalid-feedback">{{ __('common.error_required', ['name' => __('order.express_company')]) }}</div>
                    </td>
                    <td>
                      <input required placeholder="{{ __('admin/setting.express_code_help') }}" type="text" :name="'express_company['+ index +'][code]'" v-model="item.code" class="form-control">
                      <div class="invalid-feedback">{{ __('common.error_required', ['name' => 'Code']) }}</div>
                    </td>
                    @hook('admin.setting.express.table.tbody.td')
                    <td><i @click="express_company.splice(index, 1)" class="bi bi-x-circle fs-4 text-danger cursor-pointer"></i></td>
                  </tr>
                  <tr>
                    <td colspan="2"><input v-if="!express_company.length" name="express_company" class="d-none"></td>
                    <td><i class="bi bi-plus-circle cursor-pointer fs-4" @click="addCompany"></i></td>
                  </tr>
                </tbody>
              </table>
            </x-admin::form.row>
            @hook('admin.setting.express.after')
          </div>

          <div class="tab-pane fade" id="tab-shop-address">
                    @hook('admin.setting.express.before')
                    <x-admin::form.row title="{{ __('order.express_company') }}">
                        <table class="table table-bordered w-max-900">
                            <thead>
                                <th>Tên nhà hàng</th>
                                <th>Địa chỉ</th>
                                <th>Link bản đồ</th>
                                @hook('admin.setting.express.table.thead.th')
                                <th>Giờ làm việc</th>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in store_address" :key="index">
  <td>
    <input required placeholder="Tên nhà hàng" type="text" :name="'store_address['+ index +'][name]'" v-model="item.name" class="form-control timepicker">
    <div class="invalid-feedback">{{ __('common.error_required', ['name' => __('order.store_address')]) }}</div>
  </td>
  <td>
    <input required placeholder="Địa chỉ" type="text" :name="'store_address['+ index +'][address]'" v-model="item.address" class="form-control timepicker">
    <div class="invalid-feedback">{{ __('common.error_required', ['name' => __('order.store_address')]) }}</div>
  </td>
  <td>
    <input required placeholder="Link bản đồ" type="text" :name="'store_address['+ index +'][link_map]'" v-model="item.link_map" class="form-control timepicker">
    <div class="invalid-feedback">{{ __('common.error_required', ['name' => 'Code']) }}</div>
  </td>
  <td class="d-flex flex-column gap-1">

  <div v-for="(time, workingTimeIndex) in item.time_working" :key="workingTimeIndex" class="d-flex gap-2 align-items-center">
    <input type="text" class="form-control" placeholder="Giờ mở cửa"
           :name="'store_address['+ index +'][time_working]['+ workingTimeIndex +'][time_start]'"
           :data-time-index="index"
           v-model="time.time_start">
    -
    <input type="text" class="form-control" placeholder="Giờ đóng cửa"
           :name="'store_address['+ index +'][time_working]['+ workingTimeIndex +'][time_end]'"
           :data-time-index="index"
           v-model="time.time_end">
    <i @click="removeWorkingTime(index, workingTimeIndex)" class="bi bi-x-circle fs-4 text-danger cursor-pointer"></i>
  </div>
 
</div>

 
</div>
    <div class="text-center">
      <i @click="addWorkingTime(index)" class="bi bi-plus-circle cursor-pointer fs-4"></i>
    </div>
  </td>
</tr>


                                <tr>
                                    <td colspan="1"><input v-if="!express_company.length" name="express_company"
                                            class="d-none"></td>
                                    <td class="text-center"><i class="bi bi-plus-circle cursor-pointer fs-4"
                                            @click="addStoreAddress"></i></td>
                                </tr>

                            </tbody>
                        </table>
                    </x-admin::form.row>
                    @hook('admin.setting.express.after')
                </div>

          <div class="tab-pane fade" id="tab-mail">

            @hook('admin.setting.mail.before')

            <x-admin-form-switch name="use_queue" title="{{ __('admin/setting.use_queue') }}" value="{{ old('use_queue', system_setting('base.use_queue', '0')) }}">
              {{-- <div class="help-text font-size-12 lh-base">{{ __('admin/setting.enable_tax_info') }}</div> --}}
            </x-admin-form-switch>
            <x-admin::form.row title="{{ __('admin/setting.mail_engine') }}">
              <select name="mail_engine" v-model="mail_engine" class="form-select wp-200 me-3">
                <option :value="item.code" v-for="item, index in source.mailEngines" :key="index">@{{ item.name }}</option>
              </select>
              <div v-if="mail_engine == 'log'" class="help-text font-size-12 lh-base">{{ __('admin/setting.mail_log') }}</div>
            </x-admin::form.row>

            <div v-if="mail_engine == 'smtp'">
              <x-admin-form-input name="smtp[host]" required title="{{ __('admin/setting.smtp_host') }}" value="{{ old('host', system_setting('base.smtp.host', '')) }}">
              </x-admin-form-input>
              <x-admin-form-input name="smtp[username]" required title="{{ __('admin/setting.smtp_username') }}" value="{{ old('username', system_setting('base.smtp.username', '')) }}">
              </x-admin-form-input>
              <x-admin-form-input name="smtp[password]" required title="{{ __('admin/setting.smtp_password') }}" value="{{ old('password', system_setting('base.smtp.password', '')) }}">
                <div class="help-text font-size-12 lh-base">{{ __('admin/setting.smtp_password_info') }}</div>
              </x-admin-form-input>
              <x-admin-form-input name="smtp[encryption]" required title="{{ __('admin/setting.smtp_encryption') }}" value="{{ old('encryption', system_setting('base.smtp.encryption', 'TLS')) }}">
                <div class="help-text font-size-12 lh-base">{{ __('admin/setting.smtp_encryption_info') }}</div>
              </x-admin-form-input>
              <x-admin-form-input name="smtp[port]" required title="{{ __('admin/setting.smtp_port') }}" value="{{ old('port', system_setting('base.smtp.port', '465')) }}">
              </x-admin-form-input>
              <x-admin-form-input name="smtp[timeout]" required title="{{ __('admin/setting.smtp_timeout') }}" value="{{ old('timeout', system_setting('base.smtp.timeout', '5')) }}">
              </x-admin-form-input>
            </div>

            <div v-if="mail_engine == 'sendmail'">
              <x-admin-form-input name="sendmail[path]" :placeholder="222" required title="{{ __('admin/setting.sendmail_path') }}" value="{{ old('path', system_setting('base.sendmail.path', '')) }}">
                <div class="help-text font-size-12 lh-base">系统 sendmail 执行路径, 一般为 /usr/sbin/sendmail -bs</div>
              </x-admin-form-input>
            </div>

            <div v-if="mail_engine == 'mailgun'">
              <x-admin-form-input name="mailgun[domain]" required title="{{ __('admin/setting.mailgun_domain') }}" value="{{ old('domain', system_setting('base.mailgun.domain', '')) }}">
              </x-admin-form-input>
              <x-admin-form-input name="mailgun[secret]" required title="{{ __('admin/setting.mailgun_secret') }}" value="{{ old('secret', system_setting('base.mailgun.secret', '')) }}">
              </x-admin-form-input>
              <x-admin-form-input name="mailgun[endpoint]" required title="{{ __('admin/setting.mailgun_endpoint') }}" value="{{ old('endpoint', system_setting('base.mailgun.endpoint', '')) }}">
              </x-admin-form-input>
            </div>

            @hook('admin.setting.mail.after')

          </div>

          <div class="tab-pane fade" id="tab-checkout">
            <x-admin-form-switch name="show_price_after_login" title="{{ __('admin/setting.show_price_after_login') }}" value="{{ old('show_price_after_login', system_setting('base.show_price_after_login', '0')) }}">
              <div class="help-text font-size-12 lh-base show-price-error-text">{{ __('admin/setting.show_price_after_login_tips') }}</div>
            </x-admin-form-switch>

            <x-admin-form-switch name="guest_checkout" title="{{ __('admin/setting.guest_checkout') }}" value="{{ old('guest_checkout', system_setting('base.guest_checkout', '1')) }}" />

            <x-admin-form-switch name="customer_approved" title="{{ __('admin/setting.customer_approved') }}" value="{{ old('customer_approved', system_setting('base.customer_approved', '0')) }}">
            </x-admin-form-switch>
            
            
           

            <x-admin-form-switch name="tax" title="{{ __('admin/setting.enable_tax') }}" value="{{ old('tax', system_setting('base.tax', '0')) }}">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.enable_tax_info') }}</div>
            </x-admin-form-switch>

            
           

            <x-admin-form-select title="{{ __('admin/setting.tax_address') }}" name="tax_address" :value="old('tax_address', system_setting('base.tax_address', 'shipping'))" :options="$tax_address">
            </x-admin-form-select>

            <x-admin-form-input name="rate_api_key" title="{{ __('admin/setting.rate_api_key') }}" value="{{ old('rate_api_key', system_setting('base.rate_api_key', '')) }}">
              <div class="help-text font-size-12 lh-base">
                <a class="text-secondary" href="https://www.exchangerate-api.com/" target="_blank">www.exchangerate-api.com</a>
              </div>
            </x-admin-form-input>

            <x-admin::form.row :title="__('admin/setting.order_auto_cancel')">
              <div class="input-group wp-400">
                <input type="number" value="{{ old('order_auto_cancel', system_setting('base.order_auto_cancel', '')) }}" name="order_auto_cancel" class="form-control" placeholder="{{ __('admin/setting.order_auto_cancel') }}">
                <span class="input-group-text">{{ __('common.text_hour') }}</span>
              </div>
            </x-admin::form.row>

            <x-admin::form.row :title="__('admin/setting.order_auto_complete')">
              <div class="input-group wp-400">
                <input type="number" value="{{ old('order_auto_complete', system_setting('base.order_auto_complete', '')) }}" name="order_auto_complete" class="form-control" placeholder="{{ __('admin/setting.order_auto_complete') }}">
                <span class="input-group-text">{{ __('common.text_hour') }}</span>
              </div>
            </x-admin::form.row>

            <div class="w-auto p-2 my-2">
              <h3 class="font-bold text-primary">Phương thức nhận hàng</h3>
                <x-admin-form-switch name="store_address_status" title="Lấy hàng tại cửa hàng" value="{{ old('store_address_status', system_setting('base.store_address_status', '0')) }}">
                   </x-admin-form-switch>
                <x-admin-form-switch name="address_status" title="Ship hàng tận nơi" value="{{ old('address_status', system_setting('base.address_status', '0')) }}">
                  <div class="help-text font-size-12 lh-base">Lưu ý : Vui lòng không vô hiệu hóa cả 2 phương thức nhận hàng</div>
                </x-admin-form-switch>
            </div>
          </div>

          @hook('admin.setting.after')
        </div>

        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary d-none mt-4">{{ __('common.submit') }}</button>
        </x-admin::form.row>
      </form>
    </div>
  </div>

  <div class="modal fade" id="modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <div class="d-flex align-items-center">
            <h5 class="modal-title" id="exampleModalLabel">{{ __('shop/account/edit.crop') }}</h5>
            <div class="cropper-size ms-4">{{ __('common.cropper_size') }}：<span></span></div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="img-container">
            <img id="cropper-image" src="{{ image_resize('/') }}" class="img-fluid">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('shop/common.cancel') }}</button>
          <button type="button" class="btn btn-primary cropper-crop">{{ __('shop/common.confirm') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('footer')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    @if (session('success'))
      layer.msg('{{ session('success') }}')
    @endif

    const country_id = {{ system_setting('base.country_id', '1') }};
    const zone_id = {{ system_setting('base.zone_id', '1') ?: 1 }};

    // 获取省份
    const getZones = (country_id) => {
      $http.get(`countries/${country_id}/zones`, null, {hload: true}).then((res) => {
        if (res.data.zones.length > 0) {
          $('select[name="zone_id"]').html('');
          res.data.zones.forEach((zone) => {
            $('select[name="zone_id"]').append(`
              <option ${zone_id == zone.id ? 'selected' : ''} value="${zone.id}">${zone.name}</option>
            `);
          });
        } else {
          $('select[name="zone_id"]').html(`
            <option value="">{{ __('common.please_choose') }}</option>
          `);
        }
      })
    }

    $(function() {
      const line = bk.getQueryString('line');
      getZones(country_id);

      $('select[name="country_id"]').on('change', function () {
        getZones($(this).val());
      });

      if (line) {
        $(`textarea[name="${line}"], select[name="${line}"], input[name="${line}"]`).parents('.row').addClass('active-line');

        setTimeout(() => {
          $('div').removeClass('active-line');
        }, 1200);
      }

      {{--$('.get-license-code').click(function (event) {--}}
      {{--  $http.get(`${config.api_url}/api/licensed`, {domain: config.app_url}).then((res) => {--}}
      {{--    if (res.license_code == '') {--}}
      {{--      layer.alert('{{ __('admin/setting.license_code_get_error') }}', {--}}
      {{--        icon: 7,--}}
      {{--        btn: ['{{ __('common.cancel') }}', '{{ __('admin/setting.license_Buy') }}'],--}}
      {{--        title: '{{__("common.text_hint")}}',--}}
      {{--        btn2: function(index, layero) {--}}
      {{--          window.open('{{ beike_api_url() }}/vip/subscription?domain={{ config('app.url') }}&developer_token={{ system_setting('base.developer_token') }}&type=tab-license');--}}
      {{--          layer.close(index);--}}
      {{--        }--}}
      {{--      });--}}

      {{--      return;--}}
      {{--    }--}}

      {{--    $('input[name="license_code"]').val(res.license_code);--}}
      {{--  })--}}
      {{--})--}}
    });

  </script>

  <script>
    let ratio = 1;
    let $crop = null
    var cropper;

    $(function() {
      $('.open-crop').click(function() {
        var image = document.getElementById('cropper-image');
        $crop = $(this);
        ratio = $(this).attr('ratio')
        var cropper;
        var $input = $('<input type="file" accept="image/*" class="d-none">');
        $input.click();
        $input.change(function() {
          var files = this.files;
          var done = function(url) {
            image.src = url;
            $('#modal').modal('show');
          };

          if (files && files.length > 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
              done(reader.result);
            };
            reader.readAsDataURL(files[0]);
          }
        });
      });

      $('input[name="show_price_after_login"]').change(function () {
        if ($(this).val() == 1 && $('input[name="guest_checkout"]').prop('checked') == true) {
          $('input[name="guest_checkout"]').prop('checked', true);
          $('.show-price-error-text').addClass('text-danger fw-bold');
          setTimeout(() => {
            $('.show-price-error-text').removeClass('text-danger fw-bold');
          }, 1200);
        }
      });

      $('input[name="guest_checkout"]').change(function () {
        if ($(this).val() == 1 && $('input[name="show_price_after_login"]').prop('checked') == true) {
          $('input[name="show_price_after_login"]').prop('checked', 1);
          $('.show-price-error-text').addClass('text-danger fw-bold');
          setTimeout(() => {
            $('.show-price-error-text').removeClass('text-danger fw-bold');
          }, 1200);
        }
      });
    });

    $('#modal').on('shown.bs.modal', function() {
      var image = document.getElementById('cropper-image');
      cropper = new Cropper(image, {
        initialAspectRatio: ratio.split('/')[0] / ratio.split('/')[1],
        autoCropArea: 1,
        viewMode: 1,
        // 回调 获取尺寸
        crop: function(event) {
          $('.cropper-size span').html(parseInt(event.detail.width) + ' * ' + parseInt(event.detail.height))
        }
      });
    }).on('hidden.bs.modal', function() {
      cropper.destroy();
      cropper = null;
    });

    $('.cropper-crop').click(function(event) {
      var canvas;

      $('#modal').modal('hide');

      if (cropper) {
        canvas = cropper.getCroppedCanvas({
          imageSmoothingQuality: 'high',
        });
        canvas.toBlob(function(blob) {
          var formData = new FormData();

          formData.append('file', blob, 'avatar.png');
          formData.append('type', 'avatar');
          $http.post('{{ shop_route('file.store') }}', formData).then(res => {
            $crop.find('img').attr('src', res.data.url);
            $crop.next('input').val(res.data.value);
          })
        });
      }
    });
  </script>

  <script>
let app = new Vue({
  el: '#app',
  data: {
    mail_engine: @json(old('mail_engine', system_setting('base.mail_engine', ''))),
    express_company: @json(old('express_company', system_setting('base.express_company', []))),
    store_address: @json(old('store_address', system_setting('base.store_address', []))),
    flatpickrInstances: [], // To store flatpickr instances
    source: {
      mailEngines: [
        { name: '{{ __('admin/builder.text_no') }}', code: '' },
        { name: 'SMTP', code: 'smtp' },
        { name: 'Sendmail', code: 'sendmail' },
        { name: 'Mailgun', code: 'mailgun' },
        { name: 'Log', code: 'log' }
      ]
    }
  },
  methods: {
    addCompany() {
      if (typeof this.express_company === 'string') {
        this.express_company = [];
      }
      this.express_company.push({ name: '', code: '' });
    },
    addStoreAddress() {
      if (typeof this.store_address === 'string') {
        this.store_address = [];
      }
      this.store_address.push({ address: '', link_map: '', time_working: [] });
    },
    addWorkingTime(index) {
      let newWorkingTime = {
        time_start: '',
        time_end: ''
      };

      if (!Array.isArray(this.store_address[index].time_working)) {
        this.$set(this.store_address[index], 'time_working', []);
      }

      this.store_address[index].time_working.push(newWorkingTime);

      this.$nextTick(() => {
        this.initializeFlatpickrForIndex(index);
      });
    },
    removeWorkingTime(storeIndex, timeIndex) {
      this.store_address[storeIndex].time_working.splice(timeIndex, 1);

      if (this.store_address[storeIndex].time_working.length === 0) {
        this.store_address[storeIndex].time_working.push({
          time_start: '',
          time_end: ''
        });
      }

      this.$nextTick(() => {
        this.initializeFlatpickrForIndex(storeIndex);
      });
    },
    initializeFlatpickrForIndex(storeIndex) {
      if (this.flatpickrInstances[storeIndex]) {
        this.flatpickrInstances[storeIndex].forEach(instance => instance.destroy());
      }

      this.flatpickrInstances[storeIndex] = [];

      const timeInputs = document.querySelectorAll(`[data-time-index="${storeIndex}"]`);
      timeInputs.forEach(input => {
        const flatpickrInstance = flatpickr(input, {
          enableTime: true,
          noCalendar: true,
          dateFormat: "H:i",
          time_24hr: true
        });

        this.flatpickrInstances[storeIndex].push(flatpickrInstance);
      });
    }
  },
  mounted() {
    this.store_address.forEach((_, index) => {
      this.initializeFlatpickrForIndex(index);
    });
  }
});
</script>






@endpush



