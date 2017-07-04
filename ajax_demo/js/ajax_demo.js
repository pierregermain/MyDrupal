(function ($) {
  Drupal.behaviors.ajax_demo = {
    attach: function (context, settings) {
      // Attach ajax action click event of each view column.
      $('.view-integrantes .views-col').once('attach-links').each(this.attachLink);

      // Mostrar ventana emergente al dar click a columna
      $( ".view-demo .views-col" ).click(function() {
        // Cambiar Opacidad a demás regiones
        $("#navbar").css({ opacity: 0.3 });
        $(".main-container").css({ opacity: 0.3 });
        $(".footer").css({ opacity: 0.3 });
        // Mostrar Modal si no esta abierto
        console.log($('#block-ajaxviewblock').css("display"));
        if ($('#block-ajaxviewblock').css("display") == "none"){
          $('#block-ajaxviewblock').show();
        }
      });

      // Cuando le dan close a la ventana emergente
      $( ".popup-cerrar" ).click(function() {
        // Esconder Modal
        $('#block-ajaxviewblock').hide();
        // Cambiar Opacidad a demás regiones
        $("#navbar").css({ opacity: 1 });
        $(".main-container").css({ opacity: 1 });
        $(".footer").css({ opacity: 1 });
        // Esconder Modal
        $('#block-ajaxviewblock').hide();
      });
    },

 attachLink: function (idx, column) {
 
      // Dig out the node id from the header link.
      var link = $(column).find('.mi-clase-a-ser-buscada a');
      var href = $(link).attr('href');
      var matches = /user\/(\d*)/.exec(href);
      var uid = matches[1];
      console.log( uid);
 
      // Everything we need to specify about the view.
      var view_info = {
        view_name: 'nombre-de-mi-vista',
        view_display_id: 'embed_1',
        view_args: uid,
        view_dom_id: 'ajax-demo'
      };
 
      // Details of the ajax action.
      var ajax_settings = {
        submit: view_info,
        url: '/views/ajax',
        element: column,
        event: 'click'
      };
 
      Drupal.ajax(ajax_settings);
    }
  };
})(jQuery, Drupal, drupalSettings);
