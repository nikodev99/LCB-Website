$(function() {
  $('.ui.checkbox').checkbox()

  $('.ui.dropdown').dropdown()

  $('.sidebar-menu-toggler').on('click', function(event){
      event.preventDefault();
      var target = $(this).data('target')
      $(target).sidebar({
          dimPage: true,
          transition: 'overlay',
          mobileTransition: 'overlay'
      }).sidebar('toggle')
  })

  $('#datatables').DataTable()

  $('.tabular.menu .item').tab()

  $('[data-modal-target]').on('click', function () {
    var target = $(this).data('modal-target')
    $('[data-modal='+ target +']').modal('show')
  })

  $('#flashMessage').modal('show')

  $('.text.menu').popup({
    inline     : true,
    hoverable  : true,
    position   : 'bottom left',
    delay: {
      show: 300,
      hide: 800
    }
  })

  $('#menu-type').on('change', function() {
    let selected = $(this).children("option:selected").val();
    console.log(selected)
    if (selected === 'dropdown' && selected !== '') {
      $(".create-dropdown-menu").show(500)
      $(".create-megamenu").hide(500)
      $(".could.go").hide(500)
    }else {
      $(".create-megamenu").show(500)
      $(".create-dropdown-menu").hide(500)
      $(".could.go").hide(500)
    }
  })

})