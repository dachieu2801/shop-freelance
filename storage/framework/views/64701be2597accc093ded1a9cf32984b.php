<div class="text-center">
  <button id="button-internet-banking"
          style="
             width: 300px;
             height: 40px;
             font-size: 1rem;
             color: #fff;
             border: none;
             background-color: #ee4d2d;
             margin-top: 2rem;
            "
  >
    <?php echo e(__('Cod::setting.button_text')); ?>

  </button>
</div>

<script>
  $(document).ready(function(){
    $('#button-internet-banking').click(function(){
      window.location.href = '/account/orders';
    });
  });

</script>
<?php /**PATH G:\workspace\new\plugins/Cod/Views/checkout/payment.blade.php ENDPATH**/ ?>